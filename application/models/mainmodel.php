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
				$total = $this->db
					->select('responses.patient as patient')
					->from('responses')
					->join('surveys', 'surveys.id = responses.survey', 'inner')
					->join('patients', 'patients.token = responses.patient', 'inner')
					->where_in('patients.list', $L)
					->where('surveys.name', 'Dalby 1')
					->count_all_results();

				if ($total > 0) {
					$prs = $this->db
						->select('responses.patient as patient')
						->select('responses.stamp as dalby1')
						->select('patients.warning as warning')
						->select('patients.sex as sex')
						->select('lists.num as num')
						->select('idops.name as name')
						->select('vcs.name as vc')
						->from('responses')
						->join('surveys', 'surveys.id = responses.survey', 'inner')
						->join('patients', 'patients.token = responses.patient', 'inner')
						->join('lists', 'lists.id = patients.list', 'inner')
						->join('idops', 'idops.id = lists.idop', 'inner')
						->join('vcs', 'vcs.id = idops.vc', 'inner')
						->where_in('patients.list', $L)
						->where('surveys.name', 'Dalby 1')
						->order_by('responses.stamp', 'desc')
						->limit($ipp, $offset)
						->get();
					$table = array();
					foreach ($prs->result_array() as $pr) {
						$table[] = array(
							'token' => $pr['patient'],
							'warning' => $pr['warning'],
							'sex' => $pr['sex'],
							'list' => $pr['name'] . ' ' . $pr['num'],
							'vc' => $pr['vc'],
							'dalby1' => $pr['dalby1']);
					}
					return array('total' => $total, 'patients' => $table);
				}
			}
		}

		/* else */
		return array('total' => 0, 'patients' => array());
	}
}

