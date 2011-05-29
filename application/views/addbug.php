<?php $this->load->view('header', array('userdata' => $userdata['userdata'])); ?>

<h1>Rapportera bugg</h1>

<?php echo validation_errors(); ?>

<?php
$f_title = array('name' => 'title', 'id' => 'title', 'value' => set_value('title'), 'size' => '100');
$f_description = array('name' => 'description', 'id' => 'description', 'value' => set_value('description'), 'rows' => 10, 'cols' => 100);
$f_hidden = array('reporter' => $reporter);
?>

<?php echo form_open('bugs/add');?>
<p><?php echo form_label('Rubrik:', 'title');?></p>
<p><?php echo form_input($f_title);?></p>
<p><?php echo form_label('Detaljerad beskrivning:', 'description');?></p>
<p><?php echo form_textarea($f_description);?></p>
<p><?php echo form_hidden($f_hidden);?></p>
<p><?php echo form_submit('submit', 'Spara');?></p>
<?php echo form_close();?>

<?php $this->load->view('footer');?>

