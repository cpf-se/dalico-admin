<?php

function list_documents_by_date($patient, $doctype)
{
	$docs = array(
		'crf' => array('table' => 'crfs'),
		'ivp' => array('table' => 'ivps'),
		'wtp' => array('table' => 'wtps'));

	if ($doctype === 'wtp') return '---';		// <<< XXX: TEMPORARY

	$CI =& get_instance();

	$today = date('Y-m-d');

	$html = '';

	$active = $CI->db
		->select('*')
		->from($docs[$doctype]['table'])
		->join('documents', $docs[$doctype]['table'] . '.document = documents.id', 'inner')
		->where('patient', $patient)
		->where('date', $today)
		->where('pdf_url IS NULL')
		->get();

	if ($active->num_rows() > 0) {
		$doc = $active->row_array();
		$html .= "<a href='/$doctype/edit/"
			. $doc['patient'] . "/"
			. $doc['date'] . "'>"
			. $doc['date'] . "</a>";
	} else {
		$html .= "<a href='/$doctype/edit/$patient'>Ny</a>";
	}

	$historic = $CI->db
		->select('*')
		->from($docs[$doctype]['table'])
		->join('documents', $docs[$doctype]['table'] . '.document = documents.id', 'inner')
		->where('patient', $patient)
		->where('date <', $today)
		->where('pdf_url IS NOT NULL')
		->order_by('date', 'desc')
		->get();

	foreach ($historic->result_array() as $doc) {
		$html .= "<br />\n<small><img src='/pdf.png' alt='PDF icon' />&nbsp;";
		//	$html .= "<a href='/$doctype/pdf/"
		//		. $doc['patient'] . "/"
		//		. $doc['date'] . "'>"
		//		. $doc['date'] . "</a></small>";
		$html .= "<a href='" . $doc['pdf_url'] . "'>" . $doc['date'] . "</a></small>";
	}

	return $html;
}

function list_surveys_by_date($patient, $survey)
{
	$CI =& get_instance();

	$p = $CI->db				// Visa inte Bara-PDF
		->select('vcs.name as vc')
		->from('responses')
		->join('patients', 'patients.token = responses.patient', 'inner')
		->join('lists', 'lists.id = patients.list', 'inner')
		->join('idops', 'idops.id = lists.idop', 'inner')
		->join('vcs', 'vcs.id = idops.vc', 'inner')
		->where('vcs.name', 'Bara')
		->where('patients.token', $patient)
		->count_all_results();
	if ($p > 0) {
		return '---';			// too harsh
	}

	$s = $CI->db
		->select('id')
		->from('surveys')
		->where('name', $survey)
		->limit(1)
		->get();

	if ($s->num_rows() > 0) {
		$s = $s->row_array();
		$s = $s['id'];

		$historic = $CI->db
			->select('pdf_url')
			->select('stamp')
			->from('responses')
			->where('pdf_url IS NOT NULL')
			->where('patient', $patient)
			->where('survey', $s)
			->get();

		$rows = array();
		foreach ($historic->result_array() as $h) {
			$html  = "<small><img src='/pdf.png' alt='PDF icon' />&nbsp;";
			$html .= "<a href='" . $h['pdf_url'] . "'>" . date('Y-m-d', strtotime($h['stamp'])) . "</a></small>";
			$rows[] = $html;
		}
		$html = '---';
		if (!empty($rows)) {
			$html = implode('<br />' . "\n", $rows);
		}
		return $html;
	} else {
		return '---';
	}
}

?>

