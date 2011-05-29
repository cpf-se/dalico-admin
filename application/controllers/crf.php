<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Crf extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('tank_auth');
	}

	function index($token = 0) {
	}

	function edit($token = 0, $date = 0) {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else if (is_numeric($token) || strlen($token) < 7) {
			redirect('/');
		} else {
			$this->load->model('CrfModel');
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

			$crf = $this->CrfModel->active_crf($token);

			if (!$crf) {
				$this->load->view('crfform', array('patient' => $token, 'date' => date('Y-m-d')));
			} else if ($this->form_validation->run() == FALSE) {
				$this->load->view('crfform', $crf);
			} else {
				echo "<pre>VALIDERAR</pre>";
				echo "<pre>"; var_dump($crf); echo "</pre>";
				echo "<pre>"; var_dump($_POST); echo "</pre>";
			}
		}
	}
}

