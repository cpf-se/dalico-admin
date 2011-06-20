<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class IvpModel extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	private function load_editors(&$ivp) {
		$ivp['editors'] = array();
		if (isset($ivp['document'])) {
			$editors = $this->db
				->select('*')
				->from('document_edits')
				->where('document', $ivp['document'])
				->order_by('stamp', 'desc')
				->get();
			foreach ($editors->result_array() as $ed) {
				$ivp['editors'][] = $ed;
			}
		}
	}

	function load($token, $date, $log = TRUE) {
		$ivp = $this->db
			->select('ivps.id')
			->select('occasion')
			->select('dialogue')
			->select('measure')
			->select('iv_minutes')
			->select('own')
			->select('group')
			->select('misc')
			->select('document')
			->select('consultations.name as consultation')
			->select('documents.patient as patient')
			->select('documents.date as date')
			->from('ivps')
			->join('consultations', 'ivps.consultation = consultations.id', 'left outer')
			->join('documents', 'ivps.document = documents.id', 'inner')
			->where('patient', $token)
			->where('date', $date)
			->limit(1)
			->get();
		if ($ivp->num_rows() > 0) {
			$ivp = $ivp->row_array();
			$this->load_editors($ivp);

			$ivp['measures'] = array();
			$measures = $this->db
				->select('measures.name as name')
				->select('measures.label as label')
				->from('measures')
				->join('ivps_measures', 'measures.id = ivps_measures.measure', 'inner')
				->where('ivps_measures.ivp', $ivp['id'])
				->get();
			foreach ($measures->result_array() as $measure) {
				$ivp['measures'][] = $measure;
			}

			$ivp['professions'] = array();
			$professions = $this->db
				->select('professions.name as name')
				->select('professions.label as label')
				->select('ivps_professions.minutes as minutes')
				->from('professions')
				->join('ivps_professions', 'professions.id = ivps_professions.profession', 'inner')
				->where('ivps_professions.ivp', $ivp['id'])
				->get();
			foreach ($professions->result_array() as $profession) {
				$ivp['professions'][$profession['name']] = $profession['minutes'];
			}
			if ($log) {
				$event_type = $this->db
					->select('id')
					->from('event_types')
					->where('name', 'LOAD')
					->limit(1)
					->get();
				if ($event_type->num_rows() > 0) {
					$event_type = $event_type->row_array();
					$event_log = array(
						'user' => $this->tank_auth->get_user_id(),
						'event_type' => $event_type['id'],
						'document' => $ivp['document']);
					$this->db->insert('event_log', $event_log);
				}
			}
			return $ivp;
		}
		return FALSE;
	}

	function init($token, $date) {
		$professions = array();
		$q = $this->db
			->select('name')
			->from('professions')
			->order_by('id', 'asc')
			->get();
		foreach ($q->result_array() as $p) {
			$professions[$p['name']] = '';
		}
		$ivp = array(
			'patient' => $token,
			'date' => $date,
			'occasion' => '',
			'measures' => array(),
			'professions' => $professions,
			'dialogue' => '',
			'measure' => '',
			'iv_minutes' => '',
			'own' => '',
			'group' => '',
			'misc' => '',
			'editors' => array());
		return $ivp;
	}

	function init_from_post() {
		$ivp = array();
		foreach ($this->input->post() as $key => $value) {
			$ivp[$key] = $value;
		}
		$this->load_editors($ivp);
		return $ivp;
	}

	private function not_empty($value) {
		return is_string($value) && !empty($value);
	}

	function save() {
		$document_type = $this->db
			->select('id')
			->from('document_types')
			->where('name', 'IVP')
			->limit(1)
			->get();
		if ($document_type->num_rows() > 0) {
			$document_type = $document_type->row_array();
			$document_type = $document_type['id'];

			$patient = $this->input->post('patient');
			$date = $this->input->post('date');

			$newdoc = array_filter(array(
				'patient' => $patient,
				'date' => $date,
				'document_type' => $document_type), array($this, 'not_empty'));

			$this->db->insert('documents', $newdoc);

			$document = $this->db
				->select('id')
				->from('documents')
				->where('patient', $patient)
				->where('date', $date)
				->where('document_type', $document_type)
				->get();

			if ($document->num_rows() > 0) {
				$document = $document->row_array();
				$document = $document['id'];
			} else {
				die('FATAL: Error in application/models/crfmodel.php, newly created DOCUMENT not found.');
			}

			$consultation = '';
			if ($cstr = $this->input->post('consultation')) {
				$cid = $this->db
					->select('id')
					->from('consultations')
					->where('name', $cstr)
					->limit(1)
					->get();
				if ($cid->num_rows() > 0) {
					$row = $cid->row_array();
					$consultation = $row['id'];
				}
			}
			$newivp = array_filter(array(
				'document' => $document,
				'consultation' => $consultation,
				'occasion' => $this->input->post('occasion'),
				'dialogue' => $this->input->post('dialogue'),
				'measure' => $this->input->post('measure'),
				'iv_minutes' => $this->input->post('iv_minutes'),
				'own' => $this->input->post('own'),
				'group' => $this->input->post('group'),
				'misc' => $this->input->post('misc')), array($this, 'not_empty'));

			$this->db->insert('ivps', $newivp);

			$ivp_id = $this->db
				->distinct()
				->select('ivps.id')
				->from('ivps')
				->join('documents', 'ivps.document = documents.id', 'inner')
				->where('patient', $patient)
				->where('date', $date)
				->limit(1)
				->get();

			$ivpid = 0;
			if ($ivp_id->num_rows() > 0) {
				$ivpid = $ivp_id->row_array();
				$ivpid = $ivpid['id'];
			} else {
				die('FATAL: Error in application/models/ivpmodel.php, newly created IVP not found.');
			}

			if ($posted_professions = $this->input->post('professions')) {
				$professions = $this->db
					->select('id')
					->select('name')
					->from('professions')
					->get();
				foreach ($professions->result_array() as $p) {
					$minutes = $posted_professions[$p['name']];
					$ivps_professions = array(
						'ivp' => $ivpid,
						'profession' => $p['id'],
						'minutes' => ($minutes !== FALSE && !empty($minutes)) ? $minutes : 0);

					$this->db->insert('ivps_professions', $ivps_professions);
				}
			}

			if ($posted_measures = $this->input->post('measures')) {
				foreach ($posted_measures as $pm) {
					$measure = $this->db
						->select('id')
						->from('measures')
						->where('name', $pm)
						->limit(1)
						->get();
					if ($measure->num_rows() > 0) {
						$m = $measure->row_array();
						$ivps_measures = array(
							'ivp' => $ivpid,
							'measure' => $m['id']);
						$this->db->insert('ivps_measures', $ivps_measures);
					}
				}
			}

			$event_type = $this->db
				->select('id')
				->from('event_types')
				->where('name', 'CREATE')
				->get();

			if ($event_type->num_rows() > 0) {
				$event_type = $event_type->row_array();
				$event_type = $event_type['id'];

				$event = array(
					'user' => $this->tank_auth->get_user_id(),
					'event_type' => $event_type,
					'document' => $document);

				$this->db->insert('event_log', $event);
			}
		}
	}

	function update($oldivp) {
		$ivpid = $oldivp['id'];
		$document = $oldivp['document'];

		$changed = FALSE;

		$consultation = '';
		if ($cstr = $this->input->post('consultation')) {
			$cid = $this->db
				->select('id')
				->from('consultations')
				->where('name', $cstr)
				->limit(1)
				->get();
			if ($cid->num_rows() > 0) {
				$row = $cid->row_array();
				$consultation = $row['id'];
				if (!isset($oldivp['consultation']) || $oldivp['consultation'] != $consultation) {
					$change = array('consultation' => $consultation);
					$this->db->where('id', $ivpid);
					$this->db->update('ivps', $change);
					$changed = TRUE;
				}
			}
		}

		$changes = array();
		$local_attribs = array('occasion', 'dialogue', 'measure', 
			'iv_minutes', 'own', 'group', 'misc');

		foreach ($local_attribs as $la) {
			if ($new = $this->input->post($la)) {
				if (isset($oldivp[$la]) && $oldivp[$la] === $new) {
					continue;
				} else {
					$changes[$la] = $new;
				}
			} else {
				if (isset($oldivp[$la])) {
					$changes[$la] = NULL;
				}
			}
		}

		if (!empty($changes)) {
			$this->db
				->where('id', $ivpid)
				->update('ivps', $changes);
			$changed = TRUE;
		}

		$professions = array();
		$q_professions = $this->db
			->select('id')
			->select('name')
			->from('professions')
			->order_by('id', 'asc')
			->get();
		foreach ($q_professions->result_array() as $p) {
			$professions[$p['id']] = $p['name'];
		}
		$old_p = array();
		foreach ($oldivp['professions'] as $pname => $p) {
			$old_p[$pname] = $p;
		}
		if ($posted_professions = $this->input->post('professions')) {
			$upd = array();
			$ins = array();
			foreach ($professions as $pid => $p) {
				if (!isset($posted_professions[$p])) continue;
				/* else */
				$posted_value = $posted_professions[$p];
				if (empty($posted_value)) $posted_value = 0;
				if (isset($old_p[$p])) {
					if ($old_p[$p] !== $posted_value) {
						$upd[] = array(
							'ivp' => $ivpid,
							'profession' => $pid,
							'minutes' => $posted_value);
					}
				} else {
					$ins[] = array(
						'ivp' => $ivpid,
						'profession' => $pid,
						'minutes' => $posted_value);
				}
			}
			foreach ($ins as $i) {
				$this->db->insert('ivps_professions', $i);
			}
			foreach ($upd as $u) {
				$this->db
					->where('ivp', $u['ivp'])
					->where('profession', $u['profession'])
					->update('ivps_professions', array('minutes' => $u['minutes']));
			}
		}

		$old_measures = array();
		$q_om = $this->db
			->select('ivp')
			->select('measures.name as name')
			->from('ivps_measures')
			->join('measures', 'ivps_measures.measure = measures.id', 'inner')
			->where('ivp', $ivpid)
			->get();
		foreach ($q_om->result_array() as $om) {
			$old_measures[$om['name']] = 1;
		}

		$measures = array();
		$q_measures = $this->db
			->select('id as mid')
			->select('name')
			->from('measures')
			->order_by('mid', 'asc')
			->get();
		foreach ($q_measures->result_array() as $m) {
			$measures[$m['name']] = array(
				'mid' => $m['mid'],
				'ivp' => (isset($old_measures[$m['name']])) ? $ivpid : NULL);
		}
		if ($posted_measures = $this->input->post('measures')) {
			$ipm = array();			// inverted posted measures
			foreach ($posted_measures as $pm) {
				$ipm[$pm] = 1;
			}
			$ins = array();
			$del = array();
			foreach ($measures as $name => $m) {
				if (isset($ipm[$name])) {
					if (!isset($m['ivp'])) {
						$ins[] = array(
							'ivp' => $ivpid,
							'measure' => $m['mid']);
					}
				} else {
					if (isset($m['ivp'])) {
						$del[] = array(
							'ivp' => $ivpid,
							'measure' => $m['mid']);
					}
				}
			}

			foreach ($ins as $i) {
				$this->db->insert('ivps_measures', $i);
				$changed = TRUE;
			}

			foreach ($del as $d) {
				$this->db
					->where('ivp', $ivpid)
					->where('measure', $d['measure'])
					->delete('ivps_measures');
				$changed = TRUE;
			}
		} else {
			$this->db
				->where('ivp', $ivpid)
				->truncate('ivps_measures');
			$changed = TRUE;
		}

		if ($changed) {
			$event_type = $this->db
				->select('id')
				->from('event_types')
				->where('name', 'CHANGE')
				->get();

			if ($event_type->num_rows() > 0) {
				$event_type = $event_type->row_array();
				$event_type = $event_type['id'];

				$event = array(
					'user' => $this->tank_auth->get_user_id(),
					'event_type' => $event_type,
					'document' => $document);

				$this->db->insert('event_log', $event);
			}
		}
	}
}

