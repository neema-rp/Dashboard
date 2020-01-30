<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#UserProfileEdit").validationEngine();
	$("#UserResetPass").validationEngine();
    });
</script>


<div class="control-group">
	<div class="page-header position-relative">
                <h1>User <small><i class="icon-double-angle-right"></i> Edit Profile</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Edit Profile</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

<?php echo $this->Form->create('User', array('id' => 'UserProfileEdit'));?>
	
	<dl><?php $i = 0; $class = ' class="altrow"';?>
	
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Username'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Form->text('username',array('class'=>'validate[required,length[0,20]]'));?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Firstname'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Form->text('firstname',array('class'=>'validate[required,length[0,20]]'));?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Lastname'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Form->text('lastname',array('class'=>'validate[required] text-input'));?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Form->text('email',array('class'=>'validate[required,custom[email]]'));?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Phone'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Form->text('phone',array('class'=>'validate[required,length[0,10]]'));?>
			&nbsp;
		</dd>
	</dl>

<?php 
echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
echo "&nbsp;&nbsp;";
//echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
echo $this->Form->end(); 
?>
         
                    </div>
        </div>
        </div>
        </div>


    <div class="widget-box">
    <div class="widget-header widget-header-blue widget-header-flat">
            <h4 class="lighter">Change password</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="row-fluid">
	<?php echo $this->Form->create('User', array('id' => 'UserResetPass'));?>
		<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('username');
		echo $this->Form->input('password', array('value' => '','id'=>'password','class'=>'validate[required] text-input'));
		echo $this->Form->input('confirm_password', array('type' => 'password', 'value' => '','id'=>'confirm_password', 'class'=>'validate[required,equals[password]]'));
		echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                ?>

            </div>
        </div>
    
    </div>
</div>

</div>