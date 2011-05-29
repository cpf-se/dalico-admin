<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class BugsModel extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function get_all_bugs() {
		$bugs = $this->db
			->select('bugs.title as title')
			->select('bugs.description as description')
			->select('bugs.fixed as fixed')
			->select('users.username as reporter')
			->from('bugs')
			->join('users', 'users.id = bugs.reporter', 'inner')
			->get();

		return array('bugs' => $bugs->result_array());
	}

	function save() {
		$bug = array(
			'title' => $this->input->post('title'),
			'description' => $this->input->post('description'),
			'reporter' => $this->input->post('reporter'));

		$this->db->insert('bugs', $bug);
	}
}

