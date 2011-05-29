<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserDataModel extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function get_user_data($userid) {
		$userdata = $this->db
			->select('users.firstname as firstname')
			->select('users.lastname as lastname')
			->select('users.email as email')
			->from('users')
			->where('users.id', $userid)
			->limit(1)
			->get();

		return array('userdata' => $userdata->row_array());
	}
}

