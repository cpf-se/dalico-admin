<?php $this->load->view('header'); ?>

<p>Nedan dokumenteras brister i detta system <em>som är kända</em>. Dessa 
	behöver alltså inte rapporteras. Du kan lita på att
<em>alla tillgängliga resurser</em> för närvarande ägnas åt att åtgärda de 
brister som återstår.</p>

<p>Om du finner <em>andra problem</em> än de som återges här, är du mer än välkommen att
rapportera dessa, <em>företrädesvis</em> genom att klicka på <a href="bugs/add">den här länken</a>,
men det går också bra att använda email på vanligt sätt till <em>du vet vem</em>.</p>

<?php
$blink = 0;
foreach ($bugs as $bug) {
?>
	<div class="<?php echo (($blink++ % 2 == 0) ? 'bugeven' : 'bugodd');?>">
<p><strong><?=$bug['title']?></strong></p>
<p><?=$bug['description']?></p>
</div>
<?php
}
?>

<?php $this->load->view('footer'); ?>

