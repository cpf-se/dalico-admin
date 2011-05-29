<?php $this->load->view('header'); ?>

<h1>Case Report Form</h1>
<?php
echo "<p><strong>$patient</strong>&nbsp;/&nbsp;$date</p>";
?>

<?php echo validation_errors(); ?>

<?php
$f_length = array('name' => 'length', 'id' => 'length', 'style' => 'text-align: right', 'value' => $length, 'size' => 10);
$f_weight = array('name' => 'weight', 'id' => 'weight', 'style' => 'text-align: right', 'value' => $weight, 'size' => 10);
$f_waist = array('name' => 'waist', 'id' => 'waist', 'style' => 'text-align: right', 'value' => $waist, 'size' => 10);
$f_hip = array('name' => 'hip', 'id' => 'hip', 'style' => 'text-align: right', 'value' => $hip, 'size' => 10);
$f_bhb = array('name' => 'bhb', 'id' => 'bhb', 'style' => 'text-align: right', 'value' => $bhb, 'size' => 10);
$f_fpglukos = array('name' => 'fpglukos', 'id' => 'fpglukos', 'style' => 'text-align: right', 'value' => $fpglukos, 'size' => 10);
$f_bhba1c = array('name' => 'bhba1c', 'id' => 'bhba1c', 'style' => 'text-align: right', 'value' => $bhba1c, 'size' => 10);
$f_pnatrium = array('name' => 'pnatrium', 'id' => 'pnatrium', 'style' => 'text-align: right', 'value' => $pnatrium, 'size' => 10);
$f_pkalium = array('name' => 'pkalium', 'id' => 'pkalium', 'style' => 'text-align: right', 'value' => $pkalium, 'size' => 10);
$f_pkreatinin = array('name' => 'pkreatinin', 'id' => 'pkreatinin', 'style' => 'text-align: right', 'value' => $pkreatinin, 'size' => 10);
$f_pkolesterol = array('name' => 'pkolesterol', 'id' => 'pkolesterol', 'style' => 'text-align: right', 'value' => $pkolesterol, 'size' => 10);
$f_pldlkolesterol = array('name' => 'pldlkolesterol', 'id' => 'pldlkolesterol', 'style' => 'text-align: right', 'value' => $pldlkolesterol, 'size' => 10);
$f_phdlkolesterol = array('name' => 'phdlkolesterol', 'id' => 'phdlkolesterol', 'style' => 'text-align: right', 'value' => $phdlkolesterol, 'size' => 10);
$f_fptriglycerider = array('name' => 'fptriglycerider', 'id' => 'fptriglycerider', 'style' => 'text-align: right', 'value' => $fptriglycerider, 'size' => 10);
$f_ptsh = array('name' => 'ptsh', 'id' => 'ptsh', 'style' => 'text-align: right', 'value' => $ptsh, 'size' => 10);
$f_pft4 = array('name' => 'pft4', 'id' => 'pft4', 'style' => 'text-align: right', 'value' => $pft4, 'size' => 10);
$f_pcrp = array('name' => 'pcrp', 'id' => 'pcrp', 'style' => 'text-align: right', 'value' => $pcrp, 'size' => 10);
$f_ualbumin = array('name' => 'ualbumin', 'id' => 'ualbumin', 'style' => 'text-align: right', 'value' => $ualbumin, 'size' => 10);
$f_bts = array('name' => 'bts', 'id' => 'bts', 'style' => 'text-align: right', 'value' => $bts, 'size' => 10);
$f_btd = array('name' => 'btd', 'id' => 'btd', 'style' => 'text-align: right', 'value' => $btd, 'size' => 10);
$f_pulse = array('name' => 'pulse', 'id' => 'pulse', 'style' => 'text-align: right', 'value' => $pulse, 'size' => 10);
$f_bts24day = array('name' => 'bts24day', 'id' => 'bts24day', 'style' => 'text-align: right', 'value' => $bts24day, 'size' => 10);
$f_btd24day = array('name' => 'btd24day', 'id' => 'btd24day', 'style' => 'text-align: right', 'value' => $btd24day, 'size' => 10);
$f_bts24night = array('name' => 'bts24night', 'id' => 'bts24night', 'style' => 'text-align: right', 'value' => $bts24night, 'size' => 10);
$f_btd24night = array('name' => 'bts24night', 'id' => 'bts24night', 'style' => 'text-align: right', 'value' => $bts24night, 'size' => 10);
$f_bts24 = array('name' => 'bts24', 'id' => 'bts24', 'style' => 'text-align: right', 'value' => $bts24, 'size' => 10);
$f_btd24 = array('name' => 'bts24', 'id' => 'bts24', 'style' => 'text-align: right', 'value' => $bts24, 'size' => 10);
$f_serum = array('name' => 'serum', 'id' => 'serum', 'style' => 'text-align: left', 'value' => $serum, 'size' => 10);
$f_plasma = array('name' => 'plasma', 'id' => 'plasma', 'style' => 'text-align: left', 'value' => $plasma, 'size' => 10);
?>

<?php echo form_open("crf/edit/$patient"); ?>
<h2>Labblista</h2>
<table>
<tr><td><?php echo form_label('Längd:', 'length');?></td><td><?php echo form_input($f_length);?></td><td>(cm)</td></tr>
<tr><td><?php echo form_label('Vikt:', 'weight');?></td><td><?php echo form_input($f_weight);?></td><td>(kg)</td></tr>
<tr><td><?php echo form_label('Midjemått:', 'waist');?></td><td><?php echo form_input($f_waist);?></td><td>(cm)</td></tr>
<tr><td><?php echo form_label('Höftmått:', 'hip');?></td><td><?php echo form_input($f_hip);?></td><td>(cm)</td></tr>
<tr><td><?php echo form_label('B-Hb:', 'bhb');?></td><td><?php echo form_input($f_bhb);?></td><td>(g/l)</td></tr>
<tr><td><?php echo form_label('fP-Glukos:', 'fpglukos');?></td><td><?php echo form_input($f_fpglukos);?></td><td>(mmol/l)</td></tr>
<tr><td><?php echo form_label('B-HbA1c (IFCC):', 'bhba1c');?></td><td><?php echo form_input($f_bhba1c);?></td><td>(mmol/l)</td></tr>
<tr><td><?php echo form_label('P-Natrium:', 'pnatrium');?></td><td><?php echo form_input($f_pnatrium);?></td><td>(mmol/l)</td></tr>
<tr><td><?php echo form_label('P-Kalium:', 'pkalium');?></td><td><?php echo form_input($f_pkalium);?></td><td>(mmol/l)</td></tr>
<tr><td><?php echo form_label('P-Kreatinin(enz):', 'pkreatinin');?></td><td><?php echo form_input($f_pkreatinin);?></td><td>(&mu;mol/l)</td></tr>
<tr><td><?php echo form_label('P-Kolesterol:', 'pkolesterol');?></td><td><?php echo form_input($f_pkolesterol);?></td><td>(mmol/l)</td></tr>
<tr><td><?php echo form_label('P-LDL-Kolesterol:', 'pldlkolesterol');?></td><td><?php echo form_input($f_pldlkolesterol);?></td><td>(mmol/l)</td></tr>
<tr><td><?php echo form_label('P-HDL-Kolesterol:', 'phdlkolesterol');?></td><td><?php echo form_input($f_phdlkolesterol);?></td><td>(mmol/l)</td></tr>
<tr><td><?php echo form_label('fP-Triglycerider:', 'fptriglycerider');?></td><td><?php echo form_input($f_fptriglycerider);?></td><td>(mmol/l)</td></tr>
<tr><td><?php echo form_label('P-TSH:', 'ptsh');?></td><td><?php echo form_input($f_ptsh);?></td><td>(mlE/l)</td></tr>
<tr><td><?php echo form_label('P-FT4:', 'pft4');?></td><td><?php echo form_input($f_pft4);?></td><td>(pmol/l)</td></tr>
<tr><td><?php echo form_label('P-CRP:', 'pcrp');?></td><td><?php echo form_input($f_pcrp);?></td><td>(mg/l)</td></tr>
<tr><td><?php echo form_label('U-Albumin/krea index:', 'ualbumin');?></td><td><?php echo form_input($f_ualbumin);?></td><td>(g/mol)</td></tr>
<tr><td><?php echo form_label('BTS:', 'bts');?></td><td><?php echo form_input($f_bts);?></td><td>(mmHg)</td></tr>
<tr><td><?php echo form_label('BTD:', 'btd');?></td><td><?php echo form_input($f_btd);?></td><td>(mmHg)</td></tr>
<tr><td><?php echo form_label('Puls:', 'pulse');?></td><td><?php echo form_input($f_pulse);?></td><td>(slag/min)</td></tr>
</table>

<h2>24H blodtryck</h2>
<h3>Medelvärde dag</h3>
<table>
<tr><td><?php echo form_label('BTS:','bts24day');?></td><td><?php echo form_input($f_bts24day);?></td><td>(mmHg)</td></tr>
<tr><td><?php echo form_label('BTD:', 'btd24day');?></td><td><?php echo form_input($f_btd24day);?></td><td>(mmHg)</td></tr>
</table>

<h3>Medelvärde natt</h3>
<table>
<tr><td><?php echo form_label('BTS:', 'bts24night');?></td><td><?php echo form_input($f_bts24night);?></td><td>(mmHg)</td></tr>
<tr><td><?php echo form_label('BTD:', 'btd24night');?></td><td><?php echo form_input($f_btd24night);?></td><td>(mmHg)</td></tr>
</table>

<h3>Medelvärde dygn</h3>
<table>
<tr><td><?php echo form_label('BTS:', 'bts24');?></td><td><?php echo form_input($f_bts24);?></td><td>(mmHg)</td></tr>
<tr><td><?php echo form_label('BTD:', 'btd24');?></td><td><?php echo form_input($f_btd24);?></td><td>(mmHg)</td></tr>
</table>

<h2>Provrör</h2>
<table>
<tr><td><?php echo form_label('Serum:', 'serum');?></td><td><?php echo form_input($f_serum);?></td></tr>
<tr><td><?php echo form_label('Plasma:', 'plasma');?></td><td><?php echo form_input($f_plasma);?></td></tr>
</table>

<p>
<?php echo form_reset('reset', 'Ångra');?>
<?php echo form_submit('submit', 'Spara');?>
</p>

</form>

<?php
// List of editors
?>

<?php $this->load->view('footer'); ?>

