<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
	const ITEMS_PER_PAGE = 9;

	function __construct() {
		parent::__construct();
		$this->load->library('tank_auth');
		$this->load->helper('document_helper');
	}

	function index($offset = 0) {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			if (is_numeric($offset)) {
				$offset = floor($offset);
			} else {
				$offset = 0;
			}
			$this->load->model('MainModel');
			$this->load->model('UserDataModel');
			$data = $this->MainModel->get_all_patients($offset, self::ITEMS_PER_PAGE);
			$data['per_page'] = self::ITEMS_PER_PAGE;
			$data['userdata'] = $this->UserDataModel->get_user_data($this->tank_auth->get_user_id());

			$this->load->view('main', $data);
		}
	}

	function view($offset = 0) {
		$this->index($offset);
	}
}

