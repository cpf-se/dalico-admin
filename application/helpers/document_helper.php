<?php

function list_documents_by_date($patient, $doctype)
{
	$docs = array(
		'crf' => array('table' => 'crfs'),
		'ivp' => array('table' => 'ivps'),
		'wtp' => array('table' => 'wtps'));

	$CI =& get_instance();

	$today = date('Y-m-d');

	$html = '';

	$active = $CI->db
		->select('*')
		->from($docs[$doctype]['table'])
		->where('patient', $patient)
		->where('date', $today)
		->where('pdf IS NULL')
		->get();

	if ($active->num_rows() > 0) {
		$doc = $active->row_array();
		$html .= "<a href='/$doctype/edit/" . $doc['patient'] . "/" . $doc['date'] . "'>" . $doc['date'] . "</a>";
	} else {
		$html .= "<a href='/$doctype/edit/$patient'>Ny</a>";
	}

	$historic = $CI->db
		->select('*')
		->from($docs[$doctype]['table'])
		->where('patient', $patient)
		->where('date <', $today)
		//->where('pdf IS NOT NULL')
		->get();

	foreach ($historic->result_array() as $doc) {
		$html .= "<br />\n<small><img src='/pdf.png' alt='PDF icon' />&nbsp;<a href='/$doctype/pdf/" . $doc['patient'] . "/" . $doc['date'] . "'>" . $doc['date'] . "</a></small>";
	}

	return $html;
}


?>

