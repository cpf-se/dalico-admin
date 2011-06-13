<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Crf extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('tank_auth');
	}

	function edit($token = -1, $date = -1) {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else if (is_numeric($token) || strlen($token) < 7) {
			redirect('/');
		} else if (preg_match('/^\d{4}(-\d{2}){2}$/', $date)) {
			if ($date === date('Y-m-d')) {
				$this->load->model('CrfModel');
				$crf = $this->CrfModel->load($token, $date);
				$this->load->view('crfform', $crf);
			} else if ($date < date('Y-m-d')) {
				redirect("/pdf/$token" . '_crf_' . "$date.pdf");
			} else {
				die('Failed searching for future CRF');
			}
		} else if ($submit = $this->input->post('submit')) {
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
				$this->load->view('crfmodel', $crf);
			} else {
				$this->load->model('CrfModel');
				$patient = $this->input->post('patient');
				$date = $this->input->post('date');
				$old_crf = $this->CrfModel->load($patient, $date);

				if (!$old_crf) {
					$this->CrfModel->save();
				} else {
					$this->CrfModel->update($old_crf);
				}
				redirect('/');
			}
		} else {
			$this->load->model('CrfModel');
			$new_crf = $this->CrfModel->init($token, date('Y-m-d'));
			$this->load->view('crfform', $new_crf);
		}
	}
}

