<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CrfModel extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	private function load_editors(&$crf) {
		$crf['editors'] = array();
		$editors = $this->db
			->select('username')
			->select("to_char(stamp, 'YYYY-MM-DD HH24:MI:SS') as stamp", FALSE)
			->from('crf_editors')
			->join('users', 'crf_editors.user = users.id', 'inner')
			->where('crf_editors.crf', $crf['id'])
			->order_by('stamp', 'desc')
			->get();
		foreach ($editors->result_array() as $ed) {
			$crf['editors'][] = $ed;
		}
	}

	function load($token, $date) {
		$crf = $this->db
			->select('*')
			->from('crfs')
			->where('patient', $token)
			->where('date', $date)
			->limit(1)
			->get();
		if ($crf->num_rows() > 0) {
			$crf = $crf->row_array();
			$this->load_editors($crf);
			return $crf;
		}
		return FALSE;
	}

	function init($token, $date) {
		$crf = array(
			'patient'		=> $token,
			'date'			=> $date,
			'length'		=> '',
			'weight'		=> '',
			'waist'			=> '',
			'hip'			=> '',
			'bhb'			=> '',
			'fpglukos'		=> '',
			'bhba1c'		=> '',
			'pnatrium'		=> '',
			'pkalium'		=> '',
			'pkreatinin'		=> '',
			'pkolesterol'		=> '',
			'pldlkolesterol'	=> '',
			'phdlkolesterol'	=> '',
			'fptriglycerider'	=> '',
			'ptsh'			=> '',
			'pft4'			=> '',
			'pcrp'			=> '',
			'ualbumin'		=> '',
			'bts'			=> '',
			'btd'			=> '',
			'pulse'			=> '',
			'bts24day'		=> '',
			'btd24day'		=> '',
			'bts24night'		=> '',
			'btd24night'		=> '',
			'bts24'			=> '',
			'btd24'			=> '',
			'serum'			=> '',
			'plasma'		=> '',
			'editors'		=> array());
		return $crf;
	}

	function init_from_post() {
		$crf = array();
		foreach ($this->input->post() as $key => $value) {
			$crf[$key] = $value;
		}
		$this->load_editors($crf);
		return $crf;
	}

	private function not_empty($value) {
		return is_string($value) && !empty($value);
	}

	function save() {
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
			'user' => $this->tank_auth->get_user_id(),
			'crf' => $crfid);

		$this->db->insert('crf_editors', $edtr);
	}

	function update($oldcrf) {
		$crfid = $oldcrf['id'];

		$changed = FALSE;

		$changes = array();
		$local_attribs = array('length', 'weight', 'waist', 'hip', 
			'bhb', 'fpglukos', 'bhba1c', 'pnatrium', 'pkalium', 
			'pkreatinin', 'pkolesterol', 'pldlkolesterol', 
			'phdlkolesterol', 'fptriglycerider', 'ptsh', 'ptf4', 
			'pcrp', 'ualbumin', 'bts', 'btd', 'pulse', 'bts24day', 
			'btd24day', 'bts24night', 'btd24night', 'bts24', 
			'btd24', 'serum', 'plasma');

		foreach ($local_attribs as $la) {
			if ($new = $this->input->post($la)) {
				if (isset($oldcrf[$la]) && $oldcrf[$la] === $new) {
					continue;
				} else {
					$changes[$la] = $new;
				}
			} else {
				if (isset($oldcrf[$la])) {
					$changes[$la] = NULL;
				}
			}
		}

		if (!empty($changes)) {
			$this->db
				->where('id', $crfid)
				->update('crfs', $changes);
			$changed = TRUE;
		}

		if ($changed) {
			$edtr = array(
				'user'	=> $this->tank_auth->get_user_id(),
				'crf'	=> $crfid);
			$this->db->insert('crf_editors', $edtr);
		}
 	}                           
}

