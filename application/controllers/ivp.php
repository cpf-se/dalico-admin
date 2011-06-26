<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ivp extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('tank_auth');
	}

	function empty_or_numeric($str) {
		if (!empty($str) && !is_numeric($str)) {
			$this->form_validation->set_message('empty_or_numeric', 'Fältet %s får bara innehålla siffror.');
			return FALSE;
		}
		return TRUE;
	}

	function dateform($str) {
		if (!empty($str) && !preg_match('/^\d{4}(-\d{2}){2}$/', $str)) {
			$this->form_validation->set_message('dateform', 'Fältet %s är på fel form.');
			return FALSE;
		}
		return TRUE;
	}

	function passed_date($str) {
		if (!empty($str) && $str > date('Y-m-d')) {
			$this->form_validation->set_message('passed_date', 'Datumet ' . $str . ' har ännu inte passerat.');
			return FALSE;
		}
		return TRUE;
	}

	private function _readonly() {
		$may_write = $this->db
			->select('*')
			->from('users_groups')
			->join('groups', 'users_groups.group = groups.id', 'inner')
			->where('user', $this->tank_auth->get_user_id())
			->where('groups.name', 'wuser')
			->get();
		return $may_write->num_rows() === 0;
	}

	function edit($token = -1, $date = -1) {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else if (is_numeric($token) || strlen($token) < 7) {
			redirect('/');
		} else if (preg_match('/^\d{4}(-\d{2}){2}$/', $date)) {
			$this->load->model('IvpModel');
			$ivp = $this->IvpModel->load($token, $date);
			if ($this->_readonly()) {
				$ivp['READONLY'] = 'READONLY';
			}
			$this->load->view('ivpform', $ivp);
		} else if ($submit = $this->input->post('submit')) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('corrdate', 'Korrigerat datum', 'callback_dateform|callback_passed_date');
			$this->form_validation->set_rules('occasion', 'Besökstillfälle', 'numeric');
			$this->form_validation->set_rules('dialogue', 'FaR-samtal', 'numeric');
			$this->form_validation->set_rules('iv_minutes', 'Tidsåtgång FaR-samtal', 'numeric');
			$this->form_validation->set_rules('professions[nurse]', 'Tidsåtgång sjuksköterska', 'callback_empty_or_numeric');
			$this->form_validation->set_rules('professions[doctor]', 'Tidsåtgång läkare', 'callback_empty_or_numeric');
			$this->form_validation->set_rules('professions[physiotherapist]', 'Tidsåtgång sjukgymnast', 'callback_empty_or_numeric');
			$this->form_validation->set_rules('professions[psycologist]', 'Tidsåtgång psykolog', 'callback_empty_or_numeric');
			$this->form_validation->set_rules('professions[occupationaltherapist]', 'Tidsåtgång arbetsterapeut', 'callback_empty_or_numeric');

			$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");

			$this->form_validation->set_message('numeric', 'Fältet %s får bara innehålla siffror.');
			$this->form_validation->set_message('required', '%s måste anges.');

			if ($this->form_validation->run() == FALSE) {
				$this->load->model('IvpModel');
				$ivp = $this->IvpModel->init_from_post();
				if ($this->_readonly()) {
					$ivp['READONLY'] = 'READONLY';
				}
				$ivp['CREATE'] = 'CREATE';
				$this->load->view('ivpform', $ivp);
			} else {
				$this->load->model('IvpModel');
				$patient = $this->input->post('patient');
				$date = $this->input->post('date');
				$old_ivp = $this->IvpModel->load($patient, $date, FALSE);

				if (!$old_ivp) {
					$this->IvpModel->save();
				} else {
					$this->IvpModel->update($old_ivp);
				}
				redirect('/');
			}
		} else {
			$this->load->model('IvpModel');
			$ivp = $this->IvpModel->init($token, date('Y-m-d'));
			if ($this->_readonly()) {
				$ivp['READONLY'] = 'READONLY';
			}
			$ivp['CREATE'] = 'CREATE';
			$this->load->view('ivpform', $ivp);
		}
	}
}

