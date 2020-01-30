<div class="clients form">
<?php echo $this->Form->create('Client');?>
<?php echo $this->Form->end();?>
</div>
<div class="actions">
	<h2>User Dashboard</h2>
	<ul>
		<li><?php echo $this->Html->link(__('Dashboard', true), array('controller' => 'users', 'action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('Profile', true), array('controller' => 'users', 'action' => 'profile')); ?> </li>
		<!--li><?php //echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li-->
		<!--li><?php //echo $this->Html->link(__('Edit Profile', true), array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'edit', $this->Session->read('Auth.Admin.id'))); ?></li-->
		<li><?php echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')); ?></li>
	</ul>
</div>

