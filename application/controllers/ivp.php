<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ivp extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('tank_auth');
		$this->load->library('UserDataModel');
	}

	function edit($token = -1, $date = -1) {
		//
		// Vägar in hit:
		// 	- från ny-länk i main - isset($token)
		// 	- från datumlänk i main - isset($token, $date)
		// 	- från this via submit - isset($_POST['submit')
		//
		if (!$this->tank_auth->is_logged_in()) {		// inte inloggad
			redirect('/auth/login/');
		} else if (is_numeric($token) || strlen($token) < 7) {	// ogiltig $token
			redirect('/');
		} else if (preg_match('^\d{4}(-\d{2}){2}$', $date)) {	// giltigt $date, datumlänk
			if ($date === date('Y-m-d')) {			// idag, aktivt formulär
				$this->load->model('IvpModel');
				$this->IvpModel->load($token, $date);
			} else if ($date < date('Y-m-d')) {		// historisk, dirigera till PDF
				redirect("/pdf/ipv_$token_$date.pdf";
			} else {					// back to the future
				die("Failed searching for future IVP");
			}
		} else if (isset($this->input->post('submit'))) {	// från submit
			$this->load->library('form_validation');
			$this->form_validation->set_rules('occasion', 'Besökstillfälle', 'numeric');
			$this->form_validation->set_rules('dialogue', 'FaR-samtal', 'numeric');
			$this->form_validation->set_rules('iv_minutes', 'Tidsåtgång FaR-samtal', 'numeric');

			if ($this->form_validation->run() == FALSE) {
				$ivp['userdata'] = $this->UserDataModel->get_user_data($this->tank_auth->get_user_id());
				$this->load->view('ivpform', $ivp);
			} else {
				$this->load->model('IvpModel');
				$patient = $this->input->post('patient');
				$date = $this->input->post('date');
				$old_ivp = $this->IvpModel->load($patient, $date);

				if (!$old_ivp) {
					$this->IvpModel->save();
				} else {
					$this->IvpModel->update($old_ivp);
				}
				redirect('main');
			}
		} else {
			die('Undefined branch');
		}
	}
}

