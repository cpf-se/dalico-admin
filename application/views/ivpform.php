
<?php $this->load->view('header');?>

<h1>Interventionsprotokoll</h1>
<?php echo "<p><strong>$patient</strong>&nbsp;/&nbsp;$date</p>\n";?>

<?php echo validation_errors();?>

<?php
$f_corrdate = array(
	'label' => 'Korrigerat datum',
	'field' => array(
		'name' => 'corrdate',
		'id' => 'corrdate',
		'size' => 10,
		'style' => 'text-align: right; background-color: #ff5',
		'value' => isset($corrdate) ? $corrdate : NULL),
	'suffix' => '&nbsp;(format YYYY-MM-DD)');
$f_visit = array(
	'label' => 'besök',
	'box' => array(
		'name' => 'consultation',
		'id' => 'visit',
		'value' => 'visit',
		'checked' => isset($consultation) && $consultation === 'visit'));
$f_phone = array(
	'label' => 'telefon',
	'box' => array(
		'name' => 'consultation',
		'id' => 'phone',
		'value' => 'phone',
		'checked' => isset($consultation) && $consultation === 'phone'));
$f_occasion = array(
	'label' => '',
	'field' => array(
		'name' => 'occasion',
		'id' => 'occasion',
		'size' => 3,
		'style' => 'text-align: right',
		'value' => $occasion),
	'suffix' => '&nbsp;månader (Ange 0 eller lämna blank för baseline!)');
$f_measures = array();
$q_measures = $this->db
	->select('name')
	->select('label')
	->from('measures')
	->order_by('id', 'asc')
	->get();
foreach ($q_measures->result_array() as $m) {
	$checked = FALSE;
	if (isset($measures)) {
		foreach ($measures as $mm) {
			if ($m['name'] === $mm['name']) {
				$checked = TRUE;
				break;
			}
		}
	}
	$f_measures[] = array(
		'label' => $m['label'],
		'box' => array(
			'name' => 'measures[]',
			'id' => $m['name'],
			'value' => $m['name'],
			'checked' => $checked));
}
$f_professions = array();
$q_professions = $this->db
	->select('name')
	->select('label')
	->from('professions')
	->order_by('id', 'asc')
	->get();
foreach ($q_professions->result_array() as $p) {
	$f_professions[] = array(
		'label' => $p['label'],
		'field' => array(
			'name' => 'professions[' . $p['name'] . ']',
			'id' => 'professions[' . $p['name'] . ']',
			'size' => 3,
			'style' => 'text-align: right',
			'value' => $professions[$p['name']]));
}
$f_dialogue = array(
	'label' => 'Uppföljande utökat FaR-samtal nummer',
	'field' => array(
		'name' => 'dialogue',
		'id' => 'dialogue',
		'size' => 3,
		'style' => 'text-align: right',
		'value' => $dialogue),
	'suffix' => '&nbsp;(Ange 0 eller lämna blank för första FaR-samtal)');
$f_measure = array(
	'label' => 'Insats',
	'field' => array(
		'name' => 'measure',
		'id' => 'measure',
		'rows' => 5,
		'cols' => 50,
		'value' => $measure));
$f_iv_minutes = array(
	'label' => 'Tidsåtgång',
	'field' => array(
		'name' => 'iv_minutes',
		'id' => 'iv_minutes',
		'size' => 3,
		'style' => 'text-align: right',
		'value' => $iv_minutes),
	'suffix' => '&nbsp;minuter');
$f_own = array(
	'label' => 'FaR till egen aktivitet',
	'field' => array(
		'name' => 'own',
		'id' => 'own',
		'rows' => 5,
		'cols' => 50,
		'value' => $own));
$f_group = array(
	'label' => 'FaR till gruppaktivitet',
	'field' => array(
		'name' => 'group',
		'id' => 'group',
		'rows' => 5,
		'cols' => 50,
		'value' => $group));
$f_misc = array(
	'label' => '',
	'field' => array(
		'name' => 'misc',
		'id' => 'misc',
		'rows' => 5,
		'cols' => 50,
		'value' => $misc));
$f_hidden = array(
	'patient' => $patient,
	'date' => $date);
?>

<?php
echo form_open('ivp/edit/' . $patient);
echo form_hidden($f_hidden);

if (isset($CREATE) && $CREATE == 'CREATE') {
	echo form_label($f_corrdate['label'], $f_corrdate['field']['id']) . "\n";
	echo form_input($f_corrdate['field']) . $f_corrdate['suffix'] . "\n";
}
?>
<h2>Konsultation via</h2>
<?php
echo form_label($f_visit['label'], $f_visit['box']['id']) . "\n";
echo form_radio($f_visit['box']). "\n";
echo form_label($f_phone['label'], $f_phone['box']['id']) . "\n";
echo form_radio($f_phone['box']);
?>

<h2>Besökstillfälle</h2>
<?php
echo form_label($f_occasion['label'], $f_occasion['field']['id']) . "\n";
echo form_input($f_occasion['field']) . $f_occasion['suffix'];
?>

<h2>Insats</h2>
<table>
<?php
foreach ($f_measures as $m) {
	echo "<tr><td style='text-align: right'>" . form_label($m['label'], $m['box']['id']) . "</td>\n";
	echo "<td>" . form_checkbox($m['box']) . "</td></tr>\n";
}
?>
</table>

<h2>Yrkeskategori</h2>
<p>Utelämnat värde är likvärdigt med 0.</p>
<table>
<?php
foreach ($f_professions as $p) {
	echo "<tr><td style='text-align: right'>" . form_label($p['label'] . ':', $p['field']['name']) . "</td>\n";
	echo "<td>" . form_input($p['field']) . "&nbsp;minuter</td></tr>\n";
}
?>
</table>

<h2>Intervention</h2>
<?php
echo form_label($f_dialogue['label'] . ':', $f_dialogue['field']['id']);
echo form_input($f_dialogue['field']) . $f_dialogue['suffix'];
?>

<table>
<?php
echo "<tr><td style='text-align: right; vertical-align: top'>" . form_label($f_measure['label'] . ':', $f_measure['field']['id']) . "</td>\n";
echo "<td>" . form_textarea($f_measure['field']) . "</td></tr>\n";
echo "<tr><td style='text-align: right; vertical-align: top'>" . form_label($f_iv_minutes['label'] . ':', $f_iv_minutes['field']['id']) . "</td>\n";
echo "<td>" . form_input($f_iv_minutes['field']) . $f_iv_minutes['suffix'] . "</td></tr>\n";
echo "<tr><td style='text-align: right; vertical-align: top'>" . form_label($f_own['label'] . ':', $f_own['field']['id']) . "</td>\n";
echo "<td>" . form_textarea($f_own['field']) . "</td></tr>\n";
echo "<tr><td style='text-align: right; vertical-align: top'>" . form_label($f_group['label'] . ':', $f_group['field']['id']) . "</td>\n";
echo "<td>" . form_textarea($f_group['field']) . "</td></tr>\n";
?>
</table>

<h2>Övrig information</h2>
<?php
echo form_label($f_misc['label'], $f_misc['field']['id']);
echo form_textarea($f_misc['field']);
?>

<?php
if (isset($READONLY) && $READONLY == 'READONLY') {
	echo '<p></p>';
} else {
	echo '<p>' . form_reset('reset', 'Ångra') . form_submit('submit', 'Spara') . '</p>';
}
echo form_close();
?>

<?php if (!empty($editors)) { ?>
<div class='grey'>
<h2>Historik</h2>
<ul>
<?php
foreach ($editors as $ed) {
	echo '<li>' . $ed['stamp'] . ' (<b>' . $ed['username'] . '</b>) ' . $ed['event_type'] . '</li>' . "\n";
}}
?>
</ul></div>

<?php $this->load->view('footer');?>

