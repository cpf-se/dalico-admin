<?php $this->load->view('header');?>

<h1>Case Report Form</h1>
<?php echo "<p><strong>$patient</strong>&nbsp;/&nbsp;$date</p>\n";?>

<?php echo validation_errors();?>

<?php
$f_length = array(
	'label' => 'Längd',
	'field' => array(
		'name' => 'length',
		'id' => 'length',
		'style' => 'text-align: right',
		'value' => $length,
		'size' => 10),
	'suffix' => '(cm)');
$f_weight = array(
	'label' => 'Vikt',
	'field' => array(
		'name' => 'weight',
		'id' => 'weight',
		'style' => 'text-align: right',
		'value' => $weight,
		'size' => 10),
	'suffix' => '(kg)');
$f_waist = array(
	'label' => 'Midjemått',
	'field' => array(
		'name' => 'waist',
		'id' => 'waist',
		'style' => 'text-align: right',
		'value' => $waist,
		'size' => 10),
	'suffix' => '(cm)');
$f_hip = array(
	'label' => 'Höftmått',
	'field' => array(
		'name' => 'hip',
		'id' => 'hip',
		'style' => 'text-align: right',
		'value' => $hip,
		'size' => 10),
	'suffix' => '(cm)');
$f_bhb = array(
	'label' => 'B-Hb',
	'field' => array(
		'name' => 'bhb',
		'id' => 'bhb',
		'style' => 'text-align: right',
		'value' => $bhb,
		'size' => 10),
	'suffix' => '(g/l)');
$f_fpglukos = array(
	'label' => 'fP-Glukos',
	'field' => array(
		'name' => 'fpglukos',
		'id' => 'fpglukos',
		'style' => 'text-align: right',
		'value' => $fpglukos,
		'size' => 10),
	'suffix' => '(mmol/l)');
$f_bhba1c = array(
	'label' => 'B-HbA1c',
	'field' => array(
		'name' => 'bhba1c',
		'id' => 'bhba1c',
		'style' => 'text-align: right',
		'value' => $bhba1c,
		'size' => 10),
	'suffix' => '(mmol/l)');
$f_pnatrium = array(
	'label' => 'P-Natrium',
	'field' => array(
		'name' => 'pnatrium',
		'id' => 'pnatrium',
		'style' => 'text-align: right',
		'value' => $pnatrium,
		'size' => 10),
	'suffix' => '(mmol/l)');
$f_pkalium = array(
	'label' => 'P-Kalium',
	'field' => array(
		'name' => 'pkalium',
		'id' => 'pkalium',
		'style' => 'text-align: right',
		'value' => $pkalium,
		'size' => 10),
	'suffix' => '(mmol/l)');
$f_pkreatinin = array(
	'label' => 'P-Kreatinin(enz)',
	'field' => array(
		'name' => 'pkreatinin',
		'id' => 'pkreatinin',
		'style' => 'text-align: right',
		'value' => $pkreatinin,
		'size' => 10),
	'suffix' => '(&mu;mol/l)');
$f_pkolesterol = array(
	'label' => 'P-Kolesterol',
	'field' => array(
		'name' => 'pkolesterol',
		'id' => 'pkolesterol',
		'style' => 'text-align: right',
		'value' => $pkolesterol,
		'size' => 10),
	'suffix' => '(mmol/l)');
$f_pldlkolesterol = array(
	'label' => 'P-LDL-Kolesterol',
	'field' => array(
		'name' => 'pldlkolesterol',
		'id' => 'pldlkolesterol',
		'style' => 'text-align: right',
		'value' => $pldlkolesterol,
		'size' => 10),
	'suffix' => '(mmol/l)');
$f_phdlkolesterol = array(
	'label' => 'P-HDL-Kolesterol',
	'field' => array(
		'name' => 'phdlkolesterol',
		'id' => 'phdlkolesterol',
		'style' => 'text-align: right',
		'value' => $phdlkolesterol,
		'size' => 10),
	'suffix' => '(mmol/l)');
$f_fptriglycerider = array(
	'label' => 'fP-Triglycerider',
	'field' => array(
		'name' => 'fptriglycerider',
		'id' => 'fptriglycerider',
		'style' => 'text-align: right',
		'value' => $fptriglycerider,
		'size' => 10),
	'suffix' => '(mmol/l)');
$f_ptsh = array(
	'label' => 'P-TSH',
	'field' => array(
		'name' => 'ptsh',
		'id' => 'ptsh',
		'style' => 'text-align: right',
		'value' => $ptsh,
		'size' => 10),
	'suffix' => '(mlE/l)');				// <-- KONSTIG ENHET!
$f_pft4 = array(
	'label' => 'P-FT4',
	'field' => array(
		'name' => 'pft4',
		'id' => 'pft4',
		'style' => 'text-align: right',
		'value' => $pft4,
		'size' => 10),
	'suffix' => '(pmol/l)');
$f_pcrp = array(
	'label' => 'P-CRP',
	'field' => array(
		'name' => 'pcrp',
		'id' => 'pcrp',
		'style' => 'text-align: right',
		'value' => $pcrp,
		'size' => 10),
	'suffix' => '(mg/l)');
$f_ualbumin = array(
	'label' => 'U-Albumin/krea index',
	'field' => array(
		'name' => 'ualbumin',
		'id' => 'ualbumin',
		'style' => 'text-align: right',
		'value' => $ualbumin,
		'size' => 10),
	'suffix' => '(g/mol)');
$f_bts = array(
	'label' => 'BTS',
	'field' => array(
		'name' => 'bts',
		'id' => 'bts',
		'style' => 'text-align: right',
		'value' => $bts,
		'size' => 10),
	'suffix' => '(mmHg)');
$f_btd = array(
	'label' => 'BTD',
	'field' => array(
		'name' => 'btd',
		'id' => 'btd',
		'style' => 'text-align: right',
		'value' => $btd,
		'size' => 10),
	'suffix' => '(mmHg)');
$f_pulse = array(
	'label' => 'Puls',
	'field' => array(
		'name' => 'pulse',
		'id' => 'pulse',
		'style' => 'text-align: right',
		'value' => $pulse,
		'size' => 10),
	'suffix' => '(slag/min)');
$lablist = array(
	$f_length, $f_weight, $f_waist, $f_hip, $f_bhb, $f_fpglukos, $f_bhba1c, 
	$f_pnatrium, $f_pkalium, $f_pkreatinin, $f_pkolesterol, 
	$f_pldlkolesterol, $f_phdlkolesterol, $f_fptriglycerider, $f_ptsh, 
	$f_pft4, $f_pcrp, $f_ualbumin, $f_bts, $f_btd, $f_pulse);

$f_bts24day = array(
	'label' => 'BTS',
	'field' => array(
		'name' => 'bts24day',
		'id' => 'bts24day',
		'style' => 'text-align: right',
		'value' => $bts24day,
		'size' => 10),
	'suffix' => '(mmHg)');
$f_btd24day = array(
	'label' => 'BTD',
	'field' => array(
		'name' => 'btd24day',
		'id' => 'btd24day',
		'style' => 'text-align: right',
		'value' => $btd24day,
		'size' => 10),
	'suffix' => '(mmHg)');
$meanday = array(
	$f_bts24day, $f_btd24day);

$f_bts24night = array(
	'label' => 'BTS',
	'field' => array(
		'name' => 'bts24night',
		'id' => 'bts24night',
		'style' => 'text-align: right',
		'value' => $bts24night,
		'size' => 10),
	'suffix' => '(mmHg)');
$f_btd24night = array(
	'label' => 'BTD',
	'field' => array(
		'name' => 'bts24night',
		'id' => 'bts24night',
		'style' => 'text-align: right',
		'value' => $bts24night,
		'size' => 10),
	'suffix' => '(mmHg)');
$meannight = array(
	$f_bts24night, $f_btd24night);

$f_bts24 = array(
	'label' => 'BTS',
	'field' => array(
		'name' => 'bts24',
		'id' => 'bts24',
		'style' => 'text-align: right',
		'value' => $bts24,
		'size' => 10),
	'suffix' => '(mmHg)');
$f_btd24 = array(
	'label' => 'BTD',
	'field' => array(
		'name' => 'bts24',
		'id' => 'bts24',
		'style' => 'text-align: right',
		'value' => $bts24,
		'size' => 10),
	'suffix' => '(mmHg)');
$meandygn = array(
	$f_bts24, $f_btd24);

$f_serum = array(
	'label' => 'Serum',
	'field' => array(
		'name' => 'serum',
		'id' => 'serum',
		'style' => 'text-align: left',
		'value' => $serum,
		'size' => 10));
$f_plasma = array(
	'label' => 'Plasma',
	'field' => array(
		'name' => 'plasma',
		'id' => 'plasma',
		'style' => 'text-align: left',
		'value' => $plasma,
		'size' => 10));
$tubes = array(
	$f_serum, $f_plasma);

$f_hidden = array(
	'patient' => $patient,
	'date' => $date);
?>

<?php
echo form_open("crf/edit/$patient");
echo form_hidden($f_hidden);
?>
<h2>Labblista</h2>
<table>
<?php
foreach ($lablist as $field) {
	echo '<tr><td>'
		. form_label($field['label'], $field['field']['id']) . '</td><td>'
		. form_input($field['field']) . '</td><td>'
		. $field['suffix'] . '</td></tr>' . "\n";
}
?>
</table>

<h2>24H blodtryck</h2>
<h3>Medelvärde dag</h3>
<table>
<?php
foreach ($meanday as $field) {
	echo '<tr><td>'
		. form_label($field['label'], $field['field']['id']) . '</td><td>'
		. form_input($field['field']) . '</td><td>'
		. $field['suffix'] . '</td></tr>' . "\n";
}
?>
</table>

<h3>Medelvärde natt</h3>
<table>
<?php
foreach ($meannight as $field) {
	echo '<tr><td>'
		. form_label($field['label'], $field['field']['id']) . '</td><td>'
		. form_input($field['field']) . '</td><td>'
		. $field['suffix'] . '</td></tr>' . "\n";
}
?>
</table>

<h3>Medelvärde dygn</h3>
<table>
<?php
foreach ($meandygn as $field) {
	echo '<tr><td>'
		. form_label($field['label'], $field['field']['id']) . '</td><td>'
		. form_input($field['field']) . '</td><td>'
		. $field['suffix'] . '</td></tr>' . "\n";
}
?>
</table>

<h2>Provrör</h2>
<table>
<?php
foreach ($tubes as $field) {
	echo '<tr><td>'
		. form_label($field['label'], $field['field']['id']) . '</td><td>'
		. form_input($field['field']) . '</td></tr>' . "\n";
}
?>
</table>

<?php
echo '<p>' . form_reset('reset', 'Ångra') . form_submit('submit', 'Spara') . '</p>';
echo form_close();
?>

<?php if (!empty($editors)) { ?>
<div class='grey'>
<h2>Historik</h2>
<ul>
<?php
foreach ($editors as $ed) {
	echo '<li>' . $ed['stamp'] . ' (<b>' . $ed['username'] . '</b>)</li>' . "\n";
}}
?>
</ul></div>

<?php $this->load->view('footer'); ?>

