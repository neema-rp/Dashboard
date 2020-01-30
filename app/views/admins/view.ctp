<div class="admins view">
<h2><?php  __('Admin');?></h2>

	<table><tr><td class="actions" style="border:0;float:left; margin: 10px 0 5px -10px;"><a href="/admins/">Back</a></td></tr></table>


	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Username'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['username']; ?>
			&nbsp;
		</dd>
		<!--dt<?php //if ($i % 2 == 0) echo $class;?>><?php //__('Password'); ?></dt>
		<dd<?php //if ($i++ % 2 == 0) echo $class;?>>
			<?php //echo $admin['Admin']['password']; ?>
			&nbsp;
		</dd-->
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Firstname'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['firstname']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Lastname'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['lastname']; ?>
			&nbsp;
		</dd>

		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['email']; ?>
			&nbsp;
		</dd>

	      <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Website'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['website']; ?>
			&nbsp;
		</dd>


	      <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Address'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['address']; ?>
			&nbsp;
		</dd>

	      <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('City'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['city']; ?>
			&nbsp;
		</dd>
  
	      <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Region'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['region']; ?>
			&nbsp;
		</dd>

		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Country'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['country']; ?>
			&nbsp;
		</dd>


	      <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Phone'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['phone']; ?>
			&nbsp;
		</dd>


		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $admin['Admin']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>

<?php echo $this->element('admin_left_menu'); ?>
