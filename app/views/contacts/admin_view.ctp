<div class="admins view">
	<fieldset>
			<legend><?php __('View Hotel'); ?></b></legend>
		<table width="100%">
		<tr>
			<td colspan="2" class="" style="padding-bottom:20px;"><span style="float:left;"><?php echo $this->Html->link('Back', array('prefix' => 'admin', 'admin' => true, 'controller' => 'contacts', 'action' => 'index'), array('style' => 'text-decoration:none; border:0;','class'=>'addbutton','escape' => false));?></b></span></td>
		</tr>
		<tr>
			<td><b><?php __('Id'); ?></b></td>
			<td><i><?php echo $contactus['Contact']['id']; ?></i>&nbsp;</td>
		</tr>
		<tr>	
			<td><b><?php __('Name'); ?></b></td>
			<td><i><?php echo $contactus['Contact']['title'].' '.$contactus['Contact']['name']; ?></i>&nbsp;</td>
		</tr>
        <tr>	
			<td><b><?php __('Email'); ?></b></td>
			<td><i><?php echo $contactus['Contact']['email']; ?></i>&nbsp;</td>
		</tr>
      		
		
		<tr>
			<td><b><?php __('Status'); ?></b></td>
			<td><i><?php echo ($contactus['Contact']['status']==0)?'Active':'Deactivated'; ?>&nbsp;</td>
		</tr>
        
		<tr>
			<td><b><?php __('Created'); ?></b></td>
			<td><i><?php echo date('d-M-Y h:i:s A', strtotime($contactus['Contact']['created'])); ?>&nbsp;</td>
		</tr>
		<tr>
			<td><b><?php __('Comments'); ?></b></td>
			<td><i><?php echo $contactus['Contact']['comment']; ?></i>&nbsp;</td>
		</tr>
	</table>
	</fieldset>	
</div>