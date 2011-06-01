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
<th>Case Report Form</th>
<th>Interventionsprotokoll</th>
</tr>
<?php
$blink = 0;
foreach ($patients as $pat) {
?>
	<tr class="<?php echo (($blink++ % 2 == 0) ? 'light' : 'dark'); ?>">

	<td class="frame"><tt><b><?=$pat['token']?></b></tt></td>

	<td class="frameright"><small><?=$pat['list']?> (<?=$pat['vc']?>)</small></td>

	<td class="frameleftright"><?php
	if ($pat['vc'] == 'Dalby') {
		echo "\t\t<a href='/pdf.pdf'>" . date('Y-m-d', strtotime($pat['dalby1'])) . "</a>";
	} else {
		echo "\t\t" . date('Y-m-d', strtotime($pat['dalby1']));
	}
?></td>

	<td class="frameleft"><?php
	$token = $pat['token'];
	echo "<a href='/crf/edit/$token'>Ny</a>";
	foreach ($pat['crfs'] as $crf) {
		$d = $crf['date'];
		echo "<br /><a href='/crf/edit/$token/$d'>$d</a>";
	}
?></td>

	<td class="frame"><tt>---</tt></td>
</tr>
<?php } ?>
</table>

<div class='pagination'>
<?php echo $CI->pagination->create_links(); ?>
</div>

<hr />

<address>
DALICO-admin v0.99<br />
&copy;&nbsp;2011,&nbsp;<a href="mailto:Christian.LD.Andersson@gmail.com">Christian Andersson</a>
</address>

<?php $this->load->view('footer'); ?>

