<div class="manageContents index">
<fieldset><legend><?php __('Manage Contents'); ?></legend>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th>#</th>
		<th><?php echo $this->Paginator->sort('pages');?></th>
		<th><?php echo $this->Paginator->sort('contents');?></th>
		<th style="text-align:center;"><?php __('View');?></th>
		<th style="text-align:center;"><?php __('Edit');?></th>
	</tr>
<?php 
	if(!empty($contents))
	{
		$i = 0;
		foreach ($contents as $contents):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		
	if(strlen($contents['Content']['contents'])>80)
	{ 
		$newcontents = substr($contents['Content']['contents'],0,80) ."...";
	}else{
			$newcontents=$contents['Content']['contents'];
		 }
	?>
	<tr<?php echo $class;?> class="altrow">
		<td><?php echo $i; ?>&nbsp;</td>
		<td><?php echo $contents['Content']['pages']; ?>&nbsp;</td>
		<td>
					<?php echo $this->Html->link(__($newcontents, true), array('controller'=>'contents', 'action' => 'edit/'.$contents['Content']['id'])); ?>
		&nbsp;</td>
		<td class="action" style="text-align:center;">
			<?php echo $this->Html->link(__("View", true), array('action' => 'view/'.$contents['Content']['id']),  array( 'escape' => false, 'title' => 'View')) ?>
		</td>
		<td class="action" style="text-align:center;">
			<?php echo $this->Html->link(__("Edit", true), array('action' => 'edit/'.$contents['Content']['id']),  array( 'escape' => false, 'title' => 'Edit')) ?>
		</td>
	</tr>
<?php endforeach; 

	}else{?>			
					<tr>
						<th colspan="5" width="100%"  style="text-align:center;">::Records Not found::</th>
					</tr>
			<?php }?>
			
		</tbody>
		</table>
		<!-- pagination code start-->
		<?php echo $this->element('adminpagination');?>
		<!-- pagination code end-->
	</fieldset>
</div>
<?php echo $this->element('admin_left_menu'); ?>