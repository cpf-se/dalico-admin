<?php
$CI = &get_instance();
$CI->load->library('pagination');
$config['base_url'] = site_url('main/view');
$config['total_rows'] = $total;
$config['per_page'] = $per_page;
$CI->pagination->initialize($config);
?>

<?php $this->load->view('header'); ?>

<div class='pagination'>
<?php echo $CI->pagination->create_links(); ?>
</div>

<table>
<tr class="grey">
<th>Behörighetskod</th>
<th>ID-lista</th>
<th>Dalby&nbsp;1</th>
<th>Dalby&nbsp;2</th>
<th>Dalby&nbsp;3</th>
<th>Case Report Form</th>
<th>Interventionsprotokoll</th>
<th>6-minuters gångtest</th>
</tr>
<?php $blink = 0; foreach ($patients as $pat) { ?>
<tr class="<?php
if (isset($pat['warning'])) {
	echo (($blink++ % 2 == 0) ? 'redlight' : 'reddark');
} else if ($pat['vc'] == 'CPF') {
	echo (($blink++ % 2 == 0) ? 'greenlight' : 'greendark');
} else {
	echo (($blink++ % 2 == 0) ? 'bluelight' : 'bluedark');
} ?>">
	<td class="frame"><tt><b><?=$pat['token']?></b></tt><!--&nbsp;<img src='/<?=$pat['sex']?>.png' alt='sex' /--></td>
	<td class="frameright"><small><?=$pat['list']?> (<?=$pat['vc']?>)</small></td>

	<td class="frameleft"><?php  echo list_surveys_by_date($pat['token'], 'Dalby 1');?></td>
	<td class="frame"><?php      echo list_surveys_by_date($pat['token'], 'Dalby 2');?></td>
	<td class="frameright"><?php echo list_surveys_by_date($pat['token'], 'Dalby 3');?></td>

	<td class="frameleft"><?php  echo list_documents_by_date($pat['token'], 'crf');?></td>
	<td class="frame"><?php      echo list_documents_by_date($pat['token'], 'ivp');?></td>
	<td class="frame"><?php      echo list_documents_by_date($pat['token'], 'wtp');?></td>
</tr>
<?php } ?>
</table>

<div class='pagination'>
<?php echo $CI->pagination->create_links(); ?>
</div>

<?php $this->load->view('footer'); ?>

