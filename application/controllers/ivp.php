<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ivp extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('tank_auth');
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
		} else if (preg_match('/^\d{4}(-\d{2}){2}$/', $date)) {	// giltigt $date, datumlänk
			if ($date === date('Y-m-d')) {			// idag, aktivt formulär
				$this->load->model('IvpModel');
				$ivp = $this->IvpModel->load($token, $date);
				$this->load->view('ivpform', $ivp);
			} else if ($date < date('Y-m-d')) {		// historisk, dirigera till PDF
				redirect("/pdf/ipv_$token_$date.pdf");
			} else {					// back to the future
				die("Failed searching for future IVP");
			}
		} else if ($submit = $this->input->post('submit')) {	// från submit
			$this->load->library('form_validation');
			$this->form_validation->set_rules('occasion', 'Besökstillfälle', 'numeric');
			$this->form_validation->set_rules('dialogue', 'FaR-samtal', 'numeric');
			$this->form_validation->set_rules('iv_minutes', 'Tidsåtgång FaR-samtal', 'numeric');

			if ($this->form_validation->run() == FALSE) {
				$ivp['invalid'] = 1;
				$this->load->view('ivpform', $ivp);
			} else {
				echo "<pre>"; var_dump($_POST); echo "</pre>";
				$this->load->model('IvpModel');
				$patient = $this->input->post('patient');
				$date = $this->input->post('date');
				$old_ivp = $this->IvpModel->load($patient, $date);

				if (!$old_ivp) {
					$this->IvpModel->save();
				} else {
					$this->IvpModel->update($old_ivp);
				}
				//redirect('main');
			}
		} else {
			$this->load->model('IvpModel');
			$new_ivp = $this->IvpModel->init($token, date('Y-m-d'));
			$this->load->view('ivpform', $new_ivp);
		}
	}
}

