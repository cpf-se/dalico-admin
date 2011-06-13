<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MainModel extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function get_all_patients($offset, $ipp, $all = FALSE) {
/*		$lists = $this->db
			->select('lists.id as list')
			->from('lists')
			->join('idops', 'idops.id = idop', 'inner')
			->join('vcs', 'vcs.id = idops.vc');
*/

		$total = $this->db
			->select('responses.patient as patient')
			->from('responses')
			->join('surveys', 'surveys.id = responses.survey', 'inner')
			->where('surveys.name', 'Dalby 1')
			->count_all_results();

		if ($total > 0) {
			$table = array();
			$prs = $this->db
				->select('responses.patient as patient')
				->select('responses.stamp as dalby1')
				->select('lists.num as num')
				->select('idops.name as name')
				->select('vcs.name as vc')
				->from('responses')
				->join('surveys', 'surveys.id = responses.survey', 'inner')
				->join('patients', 'patients.token = responses.patient', 'inner')
				->join('lists', 'lists.id = patients.list', 'inner')
				->join('idops', 'idops.id = lists.idop', 'inner')
				->join('vcs', 'vcs.id = idops.vc', 'inner')
				->where("surveys.name = 'Dalby 1'")
				->order_by('responses.stamp', 'desc')
				->limit($ipp, $offset)
				->get();
			if ($prs->num_rows() > 0) {
				foreach ($prs->result_array() as $pr) {
					$table[/*$pr['patient']*/] = array(
						'token' => $pr['patient'],
						'list' => $pr['name'] . ' ' . $pr['num'],
						'vc' => $pr['vc'],
						'dalby1' => $pr['dalby1']);
				}
			}
			return array('total' => $total, 'patients' => $table);
		} else {
			return array('total' => 0, 'patients' => array());
		}
	}
}

