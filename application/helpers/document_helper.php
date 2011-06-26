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

	$html = "<a href='/$doctype/edit/$patient'>Ny</a>";

	$documents = $CI->db
		->select('*')
		->from($docs[$doctype]['table'])
		->join('documents', $docs[$doctype]['table'] . '.document = documents.id', 'inner')
		->where('patient', $patient)
		->order_by('date', 'desc')
		->get();

	foreach ($documents->result_array() as $doc) {
		if (isset($doc['pdf_url']) && $doc['pdf_url'] != NULL) {
			$html .= "<br />\n<small><img src='/pdf.png' alt='PDF icon' />&nbsp;";
			$html .= "<a href='" . $doc['pdf_url'] . "'>" . $doc['date'] . "</a></small>";
		} else {
			$html .= "<br />\n<a href='/$doctype/edit/"
				. $doc['patient'] . "/"
				. $doc['date'] . "'>"
				. $doc['date'] . "</a>";
		}
	}

	return $html;
}

function list_surveys_by_date($patient, $survey)
{
	$CI =& get_instance();

	$historic = $CI->db
		->select('pdf_url')
		->select("to_char(stamp, 'YYYY-MM-DD') as date", FALSE)
		->select('responses.patient')
		->select('vc')
		->from('responses')
		->join('patients_vcs', 'responses.patient = patients_vcs.patient', 'inner')
		->join('surveys', 'responses.survey = surveys.id', 'inner')
		->where('responses.patient', $patient)
		->where('surveys.name', $survey)
		->get();

	$rows = array();
	foreach ($historic->result_array() as $h) {
		if ($h['vc'] == 'Bara' || $h['pdf_url'] == NULL) {
			$html = '<small>&nbsp;&nbsp;' . $h['date'] . '</small>';
		} else {
			$html  = "<small><img src='/pdf.png' alt='PDF icon' />&nbsp;";
			$html .= "<a href='" . $h['pdf_url'] . "'>" . $h['date'] . "</a></small>";
		}
		$rows[] = $html;
	}
	$html = '---';
	if (!empty($rows)) {
		$html = implode('<br />' . "\n", $rows);
	}
	return $html;
}

?>

