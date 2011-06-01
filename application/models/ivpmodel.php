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
			->select('consultations.label as consultation_label')
			->from('ivps')
			->join('consultations', 'ivps.consultation = consultations.id', 'inner')
			->where('ivps.patient', $token)
			->where('ivps.date', $date)
			->get();
		if ($ivps->num_rows() > 0) {
			$ivp = $ivps->result_array(); // borde bli en och endast en

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
				->select('profession.label as label')
				->from('professions')
				->join('ivps_professions', 'professions.id = ivps_professions.profession', 'inner')
				->where('ivps_professions.ivp', $ivp['id'])
				->get();
			foreach ($professions->result_array() as $profession) {
				$ivp['professions'][] = $profession;
			}

			return $ivp;
		}
	}

	function init($token, $date) {
		return array('patient' => $token, 'date' => $date);
	}

	function save() {
	}

	function update($old_ivp) {
	}
}

