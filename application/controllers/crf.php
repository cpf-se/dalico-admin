<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Crf extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('tank_auth');
	}

	function edit($token = 0, $date = 0) {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else if (is_numeric($token) || strlen($token) < 7) {
			redirect('/');
		} else {
			$this->load->model('CrfModel');
			$this->load->model('UserDataModel');
			$this->load->library('form_validation');
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
			$this->form_validation->set_rules('bts24day', 'BTS (MV Dag)', 'numeric');
			$this->form_validation->set_rules('btd24day', 'BTD (MV Dag)', 'numeric');
			$this->form_validation->set_rules('bts24night', 'BTS (MV Natt)', 'numeric');
			$this->form_validation->set_rules('btd24night', 'BTD (MV Natt)', 'numeric');
			$this->form_validation->set_rules('bts24', 'BTS (MV Dygn)', 'numeric');
			$this->form_validation->set_rules('btd24', 'BTD (MV Dygn)', 'numeric');
			$this->form_validation->set_rules('serum', 'Serum', 'callback_tube_check');
			$this->form_validation->set_rules('plasma', 'Plasma', 'callback_tube_check');

			$crf = $this->CrfModel->active_crf($token);

			if (!$crf) {
				$submit = $this->input->post('submit');
				if (!$submit) {
					$newcrf = $this->init_new_crf($token);
					$newcrf['userdata'] = $this->UserDataModel->get_user_data($this->tank_auth->get_user_id());
					$this->load->view('crfform', $newcrf);
				} else if ($submit === 'Spara') {
					if ($this->form_validation->run() == FALSE) {
						$crf['userdata'] = $this->UserDataModel->get_user_data($this->tank_auth->get_user_id());
						$this->load->view('crfform', $crf);
					} else {
						$this->CrfModel->save($this->tank_auth->get_user_id());
						redirect('main');
					}
				} else {
					die("FATAL: Illegal logic");
				}
			} else if ($this->form_validation->run() == FALSE) {
				$crf['userdata'] = $this->UserDataModel->get_user_data($this->tank_auth->get_user_id());
				$this->load->view('crfform', $crf);
			} else {
				$this->CrfModel->update($this->tank_auth->get_user_id(), $crf);
				redirect('main');
			}
		}
	}

	function init_new_crf($token) {
		return array(
			'patient' => $token,
			'date' => date('Y-m-d'),
			'length' => '',
			'weight' => '',
			'waist' => '',
			'hip' => '',
			'bhb' => '',
			'fpglukos' => '',
			'bhba1c' => '',
			'pnatrium' => '',
			'pkalium' => '',
			'pkreatinin' => '',
			'pkolesterol' => '',
			'pldlkolesterol' => '',
			'phdlkolesterol' => '',
			'fptriglycerider' => '',
			'ptsh' => '',
			'pft4' => '',
			'pcrp' => '',
			'ualbumin' => '',
			'bts' => '',
			'btd' => '',
			'pulse' => '',
			'bts24day' => '',
			'btd24day' => '',
			'bts24night' => '',
			'btd24night' => '',
			'bts24' => '',
			'btd24' => '',
			'serum' => '',
			'plasma' => '');
	}

	function tube_check($str) {
		return TRUE;
	}
}

