<?php
$CI = &get_instance();
$CI->load->library('pagination');
$config['base_url'] = site_url('main/view');
$config['total_rows'] = $total;
$config['per_page'] = $per_page;
$CI->pagination->initialize($config);
?>

<?php $this->load->view('header', array('title' => 'Dalico :: Main')); ?>

<div class='pagination'>
<?php echo $CI->pagination->create_links(); ?>
</div>

<table>
<tr class="grey">
<th>Behörighetskod</th>
<th>ID-lista</th>
<th>Dalby&nbsp;1</th>
<!--th>Dalby&nbsp;2</th-->
<!--th>Dalby&nbsp;3</th-->
<th>CRF</th>
<th>IVP</th>
<th>6WT</th>
</tr>
<?php
$blink = 0;
foreach ($patients as $pat) {
?>
	<tr class="<?php echo (($blink++ % 2 == 0) ? 'light' : 'dark'); ?>">
	<td class="frame"><tt><b><?=$pat['token']?></b></tt></td>		<!-- Behörighetskod -->
	<td class="frameright"><?=$pat['list']?> (<?=$pat['vc']?>)</td>		<!-- ID-lista -->
	<td class="frameleftright"><?php
	if ($pat['vc'] == 'Dalby') {
		echo "\t\t<a href='/pdf.pdf'>" . date('Y-m-d', strtotime($pat['dalby1'])) . "</a>";
	} else {
		echo "\t\t" . date('Y-m-d', strtotime($pat['dalby1']));
	}
?></td>
	<!--td><tt>YYYY-mm-dd</tt></td-->					<!-- Dalby 2 -->
	<!--td class="right"><tt>YYYY-mm-dd</tt></td-->				<!-- Dalby 3 -->
	<td class="frameleft"><?php
	$token = $pat['token'];
	echo "<a href='/crf/edit/$token'>Ny</a>";
	foreach ($pat['crfs'] as $crf) {
		$d = $crf['date'];
		echo "<br /><a href='/crf/edit/$token/$d'>$d</a>";
	}
?></td>										<!-- CRF -->
	<td class="frame"><tt>YYYY-mm-dd</tt><br /><tt>YYYY-mm-dd</tt></td>	<!-- IVP -->
	<td class="frame"><tt>YYYY-mm-dd</tt></td>				<!-- 6WT -->
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

