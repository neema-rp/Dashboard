<?php ?>
<div class="contents view">
<fieldset>
		<legend><?php echo $contents['Content']['pages']; ?></legend>
	<div class="action">
		<table width="100%">
			<tr>
				<td colspan="2"  style="padding-bottom:20px;"><span style="float:left;"><?php echo $this->Html->link('Back', array('prefix' => 'admin', 'admin' => true, 'controller' => 'contents', 'action' => 'index'), array('class' => 'new_button', 'escape' => false));?></span></td>
			</tr>
		</table>
	</div>
	
	<dl style="width:100%;"><?php $i = 0; $class = ' class="altrow"';?>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Content'); ?></dt>
		<dt colspan="2" style="font-weight: normal;"><?php echo $contents['Content']['contents']; ?></dt>
	</dl>
</fieldset>	
</div>
<?php echo $this->element('admin_left_menu'); ?>