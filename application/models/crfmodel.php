<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CrfModel extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	private function load_editors(&$crf) {
		$crf['editors'] = array();
		if (isset($crf['document'])) {
			$editors = $this->db
				->select('*')
				->from('document_edits')
				->where('document', $crf['document'])
				->order_by('stamp', 'desc')
				->get();
			foreach ($editors->result_array() as $ed) {
				$crf['editors'][] = $ed;
			}
		}
	}

	function load($token, $date, $log = TRUE) {
		$crf = $this->db
			->select('crfs.*')
			->select('documents.patient as patient')
			->select('documents.date as date')
			->from('crfs')
			->join('documents', 'crfs.document = documents.id', 'inner')
			->where('patient', $token)
			->where('date', $date)
			->limit(1)
			->get();
		if ($crf->num_rows() > 0) {
			$crf = $crf->row_array();
			$this->load_editors($crf);
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
						'document' => $crf['document']);
					$this->db->insert('event_log', $event_log);
				}
			}
			return $crf;
		}
		return FALSE;
	}

	function init($token, $date) {
		$crf = array(
			'patient'		=> $token,
			'date'			=> $date,
			'length'		=> '',
			'weight'		=> '',
			'waist'			=> '',
			'hip'			=> '',
			'bhb'			=> '',
			'fpglukos'		=> '',
			'bhba1c'		=> '',
			'pnatrium'		=> '',
			'pkalium'		=> '',
			'pkreatinin'		=> '',
			'pkolesterol'		=> '',
			'pldlkolesterol'	=> '',
			'phdlkolesterol'	=> '',
			'fptriglycerider'	=> '',
			'ptsh'			=> '',
			'pft4'			=> '',
			'pcrp'			=> '',
			'ualbumin'		=> '',
			'bts'			=> '',
			'btd'			=> '',
			'pulse'			=> '',
			'bts24day'		=> '',
			'btd24day'		=> '',
			'bts24night'		=> '',
			'btd24night'		=> '',
			'bts24'			=> '',
			'btd24'			=> '',
			'serum'			=> '',
			'plasma'		=> '',
			'editors'		=> array());
		return $crf;
	}

	function init_from_post() {
		$crf = array();
		foreach ($this->input->post() as $key => $value) {
			$crf[$key] = $value;
		}
		$this->load_editors($crf);
		return $crf;
	}

	private function not_empty($value) {
		return is_string($value) && !empty($value);
	}

	function save() {
		$document_type = $this->db
			->select('id')
			->from('document_types')
			->where('name', 'CRF')
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

			$newcrf = array_filter(array(
				'document' => $document,
				'length' => $this->input->post('length'),
				'weight' => $this->input->post('weight'),
				'waist' => $this->input->post('waist'),
				'hip' => $this->input->post('hip'),
				'bhb' => $this->input->post('bhb'),
				'fpglukos' => $this->input->post('fpglukos'),
				'bhba1c' => $this->input->post('bhba1c'),
				'pnatrium' => $this->input->post('pnatrium'),
				'pkalium' => $this->input->post('pkalium'),
				'pkreatinin' => $this->input->post('pkreatinin'),
				'pkolesterol' => $this->input->post('pkolesterol'),
				'pldlkolesterol' => $this->input->post('pldlkolesterol'),
				'phdlkolesterol' => $this->input->post('phdlkolesterol'),
				'fptriglycerider' => $this->input->post('phdlkolesterol'),
				'ptsh' => $this->input->post('ptsh'),
				'pft4' => $this->input->post('pft4'),
				'pcrp' => $this->input->post('pcrp'),
				'ualbumin' => $this->input->post('ualbumin'),
				'bts' => $this->input->post('bts'),
				'btd' => $this->input->post('btd'),
				'pulse' => $this->input->post('pulse'),
				'bts24day' => $this->input->post('bts24day'),
				'btd24day' => $this->input->post('btd24day'),
				'bts24night' => $this->input->post('bts24night'),
				'btd24night' => $this->input->post('btd24night'),
				'bts24' => $this->input->post('bts24'),
				'btd24' => $this->input->post('btd24'),
				'serum' => $this->input->post('serum'),
				'plasma' => $this->input->post('plasma')), array($this, 'not_empty'));

			$this->db->insert('crfs', $newcrf);

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

	function update($oldcrf) {
		$crfid = $oldcrf['id'];
		$document = $oldcrf['document'];

		$changed = FALSE;

		$changes = array();
		$local_attribs = array('length', 'weight', 'waist', 'hip', 
			'bhb', 'fpglukos', 'bhba1c', 'pnatrium', 'pkalium', 
			'pkreatinin', 'pkolesterol', 'pldlkolesterol', 
			'phdlkolesterol', 'fptriglycerider', 'ptsh', 'ptf4', 
			'pcrp', 'ualbumin', 'bts', 'btd', 'pulse', 'bts24day', 
			'btd24day', 'bts24night', 'btd24night', 'bts24', 
			'btd24', 'serum', 'plasma');

		foreach ($local_attribs as $la) {
			if ($new = $this->input->post($la)) {
				if (isset($oldcrf[$la]) && $oldcrf[$la] === $new) {
					continue;
				} else {
					$changes[$la] = $new;
				}
			} else {
				if (isset($oldcrf[$la])) {
					$changes[$la] = NULL;
				}
			}
		}

		if (!empty($changes)) {
			$this->db
				->where('id', $crfid)
				->update('crfs', $changes);
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

