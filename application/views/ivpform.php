
<?php $this->load->view('header');?>

<h1>Interventionsprotokoll</h1>
<?php echo "<p><strong>$patient</strong>&nbsp;/&nbsp;$date</p>\n";?>

<?php echo validation_errors();?>

<?php // initialize fields
	$f_hidden = array('patient' => $patient, 'date' => $date);
?>

<?php
echo form_open('ivp/edit/' . $patient);
echo form_hidden($f_hidden);
echo '<p>' . form_fieldset('Konsultation via');
echo form_label('besök:', 'visit');
echo form_radio(array(
	'name'	=> 'consultation',
	'id'	=> 'visit'));
echo form_label('telefon:', 'phone');
echo form_radio(array(
	'name'	=> 'consultation',
	'id'	=> 'phone'));
echo form_fieldset_close() . '</p>';
?>

<?php
echo '<p>' . form_fieldset('Besökstillfälle:');
echo form_label('', 'occasion');
echo form_input(array(
	'name'	=> 'occasion',
	'id'	=> 'occasion',
	'size'	=> 3)) . "&nbsp;månader (Ange 0 för baseline!)\n";
echo form_fieldset_close() . '</p>';
?>

<?php
echo '<p>' . form_fieldset('Insats:');
$measures = $this->db
	->select('name')
	->select('label')
	->from('measures')
	->order_by('id', 'asc')
	->get()
	->result_array();
echo "<table>";
foreach ($measures as $measure) {
	echo "<tr><td style='text-align: right'>" . form_label($measure['label'], $measure['name']) . "</td>\n";
	echo "<td>" . form_checkbox(array(
		'name'	=> 'measures[]',
		'id'	=> $measure['name'],
		'value'	=> $measure['name'])) . "</td></tr>\n";
}
echo "</table>";
echo form_fieldset_close() . '</p>';
?>

<?php
echo '<p>' . form_fieldset('Yrkeskategori (tidsåtgång för respektive):');
$professions = $this->db
	->select('name')
	->select('label')
	->from('professions')
	->order_by('id', 'asc')
	->get()
	->result_array();
echo "<p>Utelämnat värde är liktydigt med 0. Om två personer av samma yrkeskategori (eller fler) är verksamma under en och samma dag får en person (eller fler) <em>addera</em> till föregående angivelse. Det är tillåtet att använda räknedosa.</p>";
echo "<table>";
foreach ($professions as $profession) {
	echo "<tr><td style='text-align: right'>" . form_label($profession['label'] . ':', $profession['name'] . '_minutes') . "</td>\n";
	echo "<td>" . form_input(array(
		'name'	=> $profession['name'] . '_minutes',
		'id'	=> $profession['name'] . '_minutes',
		'size'	=> 3)) . "&nbsp;minuter</td>\n</tr>";
}
echo "</table>";
echo form_fieldset_close() . '</p>';
?>

<?php // TABELLERA!
echo '<p>' . form_fieldset('Intervention:');
echo form_label('Uppföljande utökat FaR-samtal nummer:', 'dialogue');
echo form_input(array(
	'name'	=> 'dialogue',
	'id'	=> 'dialogue',
	'size'	=> 3)) . "&nbsp;(Ange 0 för första FaR-samtal)<br />";
echo form_label('Insats:', 'measure');
echo form_textarea(array(
	'name'	=> 'measure',
	'id'	=> 'measure',
	'rows'	=> 5,
	'cols'	=> 50)) . '<br />';
echo form_label('Tidsåtgång:', 'iv_minutes');
echo form_input(array(
	'name'	=> 'iv_minutes',
	'id'	=> 'iv_minutes',
	'size'	=> 3)) . '<br />';
echo form_label('FaR till egen aktivitet:', 'own');
echo form_textarea(array(
	'name'	=> 'own',
	'id'	=> 'own',
	'rows'	=> 5,
	'cols'	=> 50)) . '<br />';
echo form_label('FaR till gruppaktivitet:', 'group');
echo form_textarea(array(
	'name'	=> 'group',
	'id'	=> 'group',
	'rows'	=> 5,
	'cols'	=> 50)) . '<br />';
echo form_fieldset_close() . '</p>';
?>

<?php
echo '<p>' . form_fieldset('Övrig relevant information:');
echo form_textarea(array(
	'name'	=> 'misc',
	'id'	=> 'misc',
	'rows'	=> 5,
	'cols'	=> 50)) . '<br />';
echo form_fieldset_close() . '</p>';
?>

<?php
echo '<p>' . form_reset('reset', 'Ångrä́') . form_submit('submit', 'Spara') . '</p>';
?>

<?php $this->load->view('footer');?>

