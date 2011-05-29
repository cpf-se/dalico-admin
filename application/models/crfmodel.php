<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CrfModel extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function active_crf($token) {
		$acrf = $this->db
			->get_where('crfs', array('patient' => $token, 'date' => date('Y-m-d')), 1);
		if ($acrf->num_rows() > 0) {
			return $acrf->row_array();
		}
		return FALSE;
	}
}

