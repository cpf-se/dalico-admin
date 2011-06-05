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
					'minutes' => ($minutes !== FALSE) ? $minutes : 0);

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
	}
}

