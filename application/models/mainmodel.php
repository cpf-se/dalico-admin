<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MainModel extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function get_all_patients($offset, $ipp, $all = FALSE) {
		$userid = $this->tank_auth->get_user_id();
		$groups = $this->db
			->select('groups.name as group')
			->from('groups')
			->join('users_groups', 'groups.id = users_groups.group', 'inner')
			->where('users_groups.user', $userid)
			->get();
		$vcs = array('Dalby', 'Bara');
		foreach ($groups->result_array() as $group) {
			if ($group['group'] == 'cpf') {
				$vcs[] = 'CPF';
			}
		}

		if (!empty($vcs)) {
			$lists = $this->db
				->select('lists.id as list')
				->from('lists')
				->join('idops', 'lists.idop = idops.id', 'inner')
				->join('vcs', 'idops.vc = vcs.id', 'inner')
				->where_in('vcs.name', $vcs)
				->get();
			$L = array();
			foreach ($lists->result_array() as $list) {
				$L[] = $list['list'];
			}

			if (!empty($L)) {
				$active_patients_q = $this->db
					->select('patient')
					->select('stamp')
					->from('active_patients')
					->join('patients', 'patients.token = patient', 'inner')
					->where_in('patients.list', $L)
					->get();
				$active_patients = array();
				foreach ($active_patients_q->result_array() as $ap) {
					$active_patients[] = sprintf('%s - %s', $ap['patient'], $ap['stamp']);
				}

				if (count($active_patients) > 0) {
					$prs = $this->db
						->select('responses.patient')
						->select('responses.stamp')
						->select('patients_vcs.listid')
						->select('patients_vcs.vc')
						->select('patients.warning')
						->select('patients.sex')
						->from('responses')
						->join('patients_vcs', 'patients_vcs.patient = responses.patient', 'inner')
						->join('patients', 'patients.token = responses.patient', 'inner')
						->where_in("responses.patient || ' - ' || stamp", $active_patients)
						->order_by('stamp', 'desc')
						->limit($ipp, $offset)
						->get();
					$table = array();
					foreach ($prs->result_array() as $pr) {
						$table[] = array(
							'token' => $pr['patient'],
							'warning' => $pr['warning'],
							'sex' => $pr['sex'],
							'listid' => $pr['listid'],
							'vc' => $pr['vc']);
					}
					return array('total' => count($active_patients), 'patients' => $table);
				}
			}
		}

		/* else */
		return array('total' => 0, 'patients' => array());
	}
}

