<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bugs extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('tank_auth');
	}

	function index() {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$this->load->model('BugsModel');
			$data = $this->BugsModel->get_all_bugs();
			$this->load->view('bugs', $data);
		}
	}

	function add() {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$this->load->model('BugsModel');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', 'Rubrik', 'required');
			$this->form_validation->set_rules('description', 'Detaljerad beskrivning', 'required');
			$this->form_validation->set_rules('reporter', 'Rapportör', 'required|numeric');

			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'reporter' => $this->tank_auth->get_user_id(),
					'userdata' => $this->UserDataModel->get_user_data($this->tank_auth->get_user_id()));
				$this->load->view('addbug', $data);
			} else {
				$this->BugsModel->save();
				redirect('bugs');
			}
		}
	}
}

