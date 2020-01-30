<div class="admins form">
<?php echo $this->Form->create('Admin');?>
	<fieldset>
 		<legend><?php __('Add Admin'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('firstname');
		echo $this->Form->input('lastname');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Admins', true), array('action' => 'index'));?></li>
	</ul>
</div>