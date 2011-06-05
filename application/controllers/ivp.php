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
				//echo "<pre>"; var_dump($ivp); echo "</pre>"; die();
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
			$this->form_validation->set_rules('professions[nurse]', 'Tidsåtgång sjuksköterska', 'numeric');
			$this->form_validation->set_rules('professions[doctor]', 'Tidsåtgång läkare', 'numeric');
			$this->form_validation->set_rules('professions[physiotherapist]', 'Tidsåtgång sjukgymnast', 'numeric');
			$this->form_validation->set_rules('professions[psycologist]', 'Tidsåtgång psykolog', 'numeric');
			$this->form_validation->set_rules('professions[occupationaltherapist]', 'Tidsåtgång arbetsterapeut', 'numeric');

			if ($this->form_validation->run() == FALSE) {
				$this->load->model('IvpModel');
				$ivp = $this->IvpModel->init_from_post();
				//echo "<pre>"; var_dump($ivp); echo "</pre>"; //die();
				$this->load->view('ivpform', $ivp);
			} else {
				//echo "<pre>"; var_dump($this->input->post()); echo "</pre>"; die();
				$this->load->model('IvpModel');
				$patient = $this->input->post('patient');
				$date = $this->input->post('date');
				$old_ivp = $this->IvpModel->load($patient, $date);

				if (!$old_ivp) {
					//echo "<pre>"; var_dump($this->input->post()); echo "</pre>"; die();
					$this->IvpModel->save();
				} else {
					echo "<pre>"; var_dump($old_ivp); echo "</pre>\n";
					echo "<pre>"; var_dump($this->input->post()); echo "</pre>\n"; die();
					$this->IvpModel->update($old_ivp);
				}
				redirect('/');
			}
		} else {
			$this->load->model('IvpModel');
			$new_ivp = $this->IvpModel->init($token, date('Y-m-d'));
			//echo "<pre>"; var_dump($new_ivp); echo "</pre>\n"; die();
			$this->load->view('ivpform', $new_ivp);
		}
	}
}

