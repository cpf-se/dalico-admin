<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Crf extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('tank_auth');
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

	function edit($token = -1, $date = -1) {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else if (is_numeric($token) || strlen($token) < 7) {
			redirect('/');
		} else if (preg_match('/^\d{4}(-\d{2}){2}$/', $date)) {
			$this->load->model('CrfModel');
			$crf = $this->CrfModel->load($token, $date);
			if ($this->_readonly()) {
				$crf['READONLY'] = 'READONLY';
			}
			$this->load->view('crfform', $crf);
		} else if ($submit = $this->input->post('submit')) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('corrdate', 'Korrigerat datum', 'callback_dateform|callback_passed_date');
			$this->form_validation->set_rules('length', 'Längd', 'numeric');
			$this->form_validation->set_rules('weight', 'Vikt', 'numeric');
			$this->form_validation->set_rules('waist', 'Midjemått', 'numeric');
			$this->form_validation->set_rules('hip', 'Höftmått', 'numeric');
			$this->form_validation->set_rules('bhb', 'B-Hb', 'numeric');
			$this->form_validation->set_rules('fpglukos', 'fP-Glukos', 'numeric');
			$this->form_validation->set_rules('bhba1c', 'B-HbA1c', 'numeric');
			$this->form_validation->set_rules('pnatrium', 'P-Natrium', 'numeric');
			$this->form_validation->set_rules('pkalium', 'P-Kalium', 'numeric');
			$this->form_validation->set_rules('pkreatinin', 'P-Kreatinin(enz)', 'numeric');
			$this->form_validation->set_rules('pkolesterol', 'P-Kolesterol', 'numeric');
			$this->form_validation->set_rules('pldlkolesterol', 'P-LDL-Kolesterol', 'numeric');
			$this->form_validation->set_rules('phdlkolesterol', 'P-HDL-Kolesterol', 'numeric');
			$this->form_validation->set_rules('fptriglycerider', 'fP-Triglycerider', 'numeric');
			$this->form_validation->set_rules('ptsh', 'P-TSH', 'numeric');
			$this->form_validation->set_rules('pft4', 'P-FT4', 'numeric');
			$this->form_validation->set_rules('pcrp', 'P-CRP', 'numeric');
			$this->form_validation->set_rules('ualbumin', 'U-Albumin/krea index', 'numeric');
			$this->form_validation->set_rules('bts', 'BTS', 'numeric');
			$this->form_validation->set_rules('btd', 'BTD', 'numeric');
			$this->form_validation->set_rules('pulse', 'Puls', 'numeric');
			$this->form_validation->set_rules('bts24day', 'BTS (medelvärde dag)', 'numeric');
			$this->form_validation->set_rules('btd24day', 'BTD (medelvärde dag)', 'numeric');
			$this->form_validation->set_rules('bts24night', 'BTS (medelvärde natt)', 'numeric');
			$this->form_validation->set_rules('btd24night', 'BTD (medelvärde natt)', 'numeric');
			$this->form_validation->set_rules('bts24', 'BTS (medelvärde dygn)', 'numeric');
			$this->form_validation->set_rules('btd24', 'BTD (medelvärde dygn)', 'numeric');

			$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");

			$this->form_validation->set_message('numeric', 'Fältet %s får bara innehålla siffror.');

			if ($this->form_validation->run() == FALSE) {
				$this->load->model('CrfModel');
				$crf = $this->CrfModel->init_from_post();
				if ($this->_readonly()) {
					$crf['READONLY'] = 'READONLY';
				}
				$crf['CREATE'] = 'CREATE';
				$this->load->view('crfform', $crf);
			} else {
				$this->load->model('CrfModel');
				$patient = $this->input->post('patient');
				$date = $this->input->post('date');
				$old_crf = $this->CrfModel->load($patient, $date, FALSE);

				if (!$old_crf) {
					$this->CrfModel->save();
				} else {
					$this->CrfModel->update($old_crf);
				}
				redirect('/');
			}
		} else {
			$this->load->model('CrfModel');
			$crf = $this->CrfModel->init($token, date('Y-m-d'));
			if ($this->_readonly()) {
				$crf['READONLY'] = 'READONLY';
			}
			$crf['CREATE'] = 'CREATE';
			$this->load->view('crfform', $crf);
		}
	}
}

