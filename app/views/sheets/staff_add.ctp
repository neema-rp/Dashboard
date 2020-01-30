<div class="sheets form">
<?php echo $this->Form->create('Sheet', array('url' => array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'add' ,$userId)));?>
	<fieldset>
 		<legend><?php __($department .': Add Department Sheet'); ?></legend>
	<?php echo $this->Form->input('name'); ?>

	<div class="input text required">
		<label for="SheetMonthMonth">Month</label>
		<?php
			echo $this->Form->month('Sheet.departmentmonth', null, array('empty' => 'Select Month')); 
			echo $this->Form->error('month');
		?>
	</div>
	<div class="input text required">
		<label for="SheetYearYear">Year</label>
		<?php
			echo $this->Form->year('Sheet.departmentmonth', 2010, 2025, date('Y'), array('empty' => 'Select Year'));
			echo $this->Form->error('year');
		?>
	</div>

	<?php
		echo $this->Form->input('Column', array('style' => "height:200px", 'div' => false, 'label' => 'Select Columns for Department Sheet'));
	?>

	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Dashboard', true), array('prefix' => 'staff', 'staff' => false, 'controller' => 'admins', 'action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Clients', true), array('prefix' => 'staff', 'staff' => true, 'controller' => 'clients', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('Logout', true), array('prefix' => 'staff', 'staff' => false, 'controller' => 'admins', 'action' => 'logout')); ?></li>
	</ul>
</div>
