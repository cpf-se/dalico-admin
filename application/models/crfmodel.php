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

	function not_empty($value) {
		return is_string($value) && !empty($value);
	}

	function save($userid) {
		$newcrf = array_filter(array(
			'patient' => $this->input->post('patient'),
			'date' => $this->input->post('date'),
			'length' => $this->input->post('length'),
			'weight' => $this->input->post('weight'),
			'waist' => $this->input->post('waist'),
			'hip' => $this->input->post('hip'),
			'bhb' => $this->input->post('bhb'),
			'fpglukos' => $this->input->post('fpglukos'),
			'bhba1c' => $this->input->post('bhba1c'),
			'pnatrium' => $this->input->post('pnatrium'),
			'pkalium' => $this->input->post('pkalium'),
			'pkreatinin' => $this->input->post('pkreatinin'),
			'pkolesterol' => $this->input->post('pkolesterol'),
			'pldlkolesterol' => $this->input->post('pldlkolesterol'),
			'phdlkolesterol' => $this->input->post('phdlkolesterol'),
			'fptriglycerider' => $this->input->post('phdlkolesterol'),
			'ptsh' => $this->input->post('ptsh'),
			'pft4' => $this->input->post('pft4'),
			'pcrp' => $this->input->post('pcrp'),
			'ualbumin' => $this->input->post('ualbumin'),
			'bts' => $this->input->post('bts'),
			'btd' => $this->input->post('btd'),
			'pulse' => $this->input->post('pulse'),
			'bts24day' => $this->input->post('bts24day'),
			'btd24day' => $this->input->post('btd24day'),
			'bts24night' => $this->input->post('bts24night'),
			'btd24night' => $this->input->post('btd24night'),
			'bts24' => $this->input->post('bts24'),
			'btd24' => $this->input->post('btd24'),
			'serum' => $this->input->post('serum'),
			'plasma' => $this->input->post('plasma')), array($this, 'not_empty'));

		$this->db->insert('crfs', $newcrf);

		$crf_id = $this->db
			->distinct()
			->select('id')
			->from('crfs')
			->where('patient', $newcrf['patient'])
			->where('date', $newcrf['date'])
			->limit(1)
			->get();

		$crfid = 0;
		if ($crf_id->num_rows() > 0) {
			$row = $crf_id->row_array();
			$crfid = $row['id'];
		} else {
			die('FATAL: Error in application/models/crfmodel.php, newly created CRF not found.');
		}

		$edtr = array(
			'user' => $userid,
			'crf' => $crfid);

		$this->db->insert('crf_editors', $edtr);
	}

 	function update($userid, $oldcrf) {
		$newcrf = array(
			'patient' => $this->input->post('patient'),
			'date' => $this->input->post('date'),
			'length' => $this->input->post('length'),
			'weight' => $this->input->post('weight'),
			'waist' => $this->input->post('waist'),
			'hip' => $this->input->post('hip'),
			'bhb' => $this->input->post('bhb'),
			'fpglukos' => $this->input->post('fpglukos'),
			'bhba1c' => $this->input->post('bhba1c'),
			'pnatrium' => $this->input->post('pnatrium'),
			'pkalium' => $this->input->post('pkalium'),
			'pkreatinin' => $this->input->post('pkreatinin'),
			'pkolesterol' => $this->input->post('pkolesterol'),
			'pldlkolesterol' => $this->input->post('pldlkolesterol'),
			'phdlkolesterol' => $this->input->post('phdlkolesterol'),
			'fptriglycerider' => $this->input->post('phdlkolesterol'),
			'ptsh' => $this->input->post('ptsh'),
			'pft4' => $this->input->post('pft4'),
			'pcrp' => $this->input->post('pcrp'),
			'ualbumin' => $this->input->post('ualbumin'),
			'bts' => $this->input->post('bts'),
			'btd' => $this->input->post('btd'),
			'pulse' => $this->input->post('pulse'),
			'bts24day' => $this->input->post('bts24day'),
			'btd24day' => $this->input->post('btd24day'),
			'bts24night' => $this->input->post('bts24night'),
			'btd24night' => $this->input->post('btd24night'),
			'bts24' => $this->input->post('bts24'),
			'btd24' => $this->input->post('btd24'),
			'serum' => $this->input->post('serum'),
			'plasma' => $this->input->post('plasma'));

		foreach (array_keys($newcrf, '', TRUE) as $empti) {
			$newcrf[$empti] = NULL;
		}

		$this->db
			->where('id', $oldcrf['id'])
			->update('crfs', $newcrf);

		$this->db->insert('crf_editors', array('user' => $userid, 'crf' => $oldcrf['id']));
 	}                           
}

