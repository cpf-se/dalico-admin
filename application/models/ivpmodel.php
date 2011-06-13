<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class IvpModel extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function load($token, $date) {
		$ivps = $this->db
			->select('ivps.id as id')
			->select('ivps.patient as patient')
			->select('ivps.date as date')
			->select('consultations.name as consultation')
			->select('ivps.occasion as occasion')
			->select('ivps.dialogue as dialogue')
			->select('ivps.measure as measure')
			->select('ivps.iv_minutes as iv_minutes')
			->select('ivps.own as own')
			->select('ivps.group as group')
			->select('ivps.misc as misc')
			->from('ivps')
			->join('consultations', 'ivps.consultation = consultations.id', 'left outer')
			->where('ivps.patient', $token)
			->where('ivps.date', $date)
			->limit(1)
			->get();
		if ($ivps->num_rows() > 0) {
			$ivp = $ivps->row_array(); // borde bli en och endast en

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

			return $ivp;
		}
		return NULL;
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
		$newivp = array(
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
			'misc' => '');
		return $newivp;
	}

	function init_from_post() {
		$ivp = array();
		if ($P = $this->input->post()) {
			foreach ($this->input->post() as $key => $value) {
				$ivp[$key] = $value;
			}
		}
		return $ivp;
	}

	function not_empty($value) {
		return is_string($value) && !empty($value);
	}

	function save() {
		$consultation = '';
		if ($cstr = $this->input->post('consultation')) {		// "visit" eller "phone"
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
			'patient' => $this->input->post('patient'),
			'date' => $this->input->post('date'),
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
			->select('id')
			->from('ivps')
			->where('patient', $newivp['patient'])
			->where('date', $newivp['date'])
			->limit(1)
			->get();

		$ivpid = 0;
		if ($ivp_id->num_rows() > 0) {
			$row = $ivp_id->row_array();
			$ivpid = $row['id'];
		} else {
			die('FATAL: Error in application/models/ivpmodel.php, newly created IVP not found.');
		}

		$edtr = array(
			'user' => $this->tank_auth->get_user_id(),
			'ivp' => $ivpid);

		$this->db->insert('ivp_editors', $edtr);

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
	}

	function update($old_ivp) {
		//echo '<pre>'; var_dump($old_ivp); echo '</pre>'; die();

		$ivpid = $old_ivp['id'];
		$changed = FALSE;

		$consultation = '';
		if ($cstr = $this->input->post('consultation')) {	// "visit" eller "phone"
			$cid = $this->db
				->select('id')
				->from('consultations')
				->where('name', $cstr)
				->limit(1)
				->get();
			if ($cid->num_rows() > 0) {
				$row = $cid->row_array();
				$consultation = $row['id'];
				if (!isset($old_ivp['consultation']) || $old_ivp['consultation'] != $consultation) {
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
				if (isset($old_ivp[$la]) && $old_ivp[$la] === $new) {
					continue;
				} else {
					$changes[$la] = $new;
				}
			} else {
				if (isset($old_ivp[$la])) {
					$changes[$la] = NULL;
				}
			}
		}
		//echo '<pre>'; var_dump($changes); echo '</pre>'; die();

		if (!empty($changes)) {
			$this->db->where('id', $ivpid);
			$this->db->update('ivps', $changes);
			$changed = TRUE;
		}

		//echo '<pre>'; var_dump($this->input->post('professions')); echo '</pre>';
		// PROFESSIONS
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
		foreach ($old_ivp['professions'] as $pname => $p) {
			$old_p[$pname] = $p;
		}
		//echo '<pre>professions = '; var_dump($professions); echo '</pre>';
		//echo '<pre>old_p = '; var_dump($old_p); echo '</pre>';
		if ($posted_professions = $this->input->post('professions')) {	// minst en angiven
			//echo "<pre>posted_professions = "; var_dump($posted_professions); echo "</pre>";
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
			//echo '<pre>$upd ='; var_dump($upd); echo '</pre>';
			//echo '<pre>$ins ='; var_dump($ins); echo '</pre>';
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

		// MEASURES
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
			//echo "<pre>m = "; var_dump($m); echo "</pre>";
			$measures[$m['name']] = array(
				'mid' => $m['mid'],
				'ivp' => (isset($old_measures[$m['name']])) ? $ivpid : NULL);
		}
		//echo "<pre>measures = "; var_dump($measures); echo "</pre>";
		if ($posted_measures = $this->input->post('measures')) {
			$ipm = array();			// inverted posted measures
			foreach ($posted_measures as $pm) {
				$ipm[$pm] = 1;
			}
			//echo "<pre>ipm = "; var_dump($ipm); echo "</pre>";
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
			//echo "<pre>ins = "; var_dump($ins); echo "</pre>";
			//echo "<pre>del = "; var_dump($del); echo "</pre>"; die();

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
			$edtr = array(
				'user' => $this->tank_auth->get_user_id(),
				'ivp' => $ivpid);
			$this->db->insert('ivp_editors', $edtr);
		}
	}
}

