<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#UserProfileEdit").validationEngine();
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Edit User</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Edit User</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

        <?php echo $this->Form->create('User', array('url'=>array('action'=>'edit',$this->params['pass'][1]),'id' => 'UserProfileEdit','class'=>'form-horizontal'));
		echo $this->Form->input('id');
		echo $this->Form->input('DepartmentsUser.department_name', array('options' => $total_depts,'multiple'=>'multiple', 'value'=>$depts,'class'=>'span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('username',array('id'=>'username','class'=>'validate[required] span6','placeholder'=>'Username','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('firstname',array('id'=>'firstname','class'=>'validate[required,length[0,20]] span6','placeholder'=>'Firstname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('lastname',array('id'=>'lastname','validate[required] span6','placeholder'=>'Lastname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('email',array('id','email','class'=>'validate[required,custom[email]] span6','placeholder'=>'Email','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('phone',array('id'=>'phone','class'=>'validate[required,length[0,10]] span6','placeholder'=>'Phone','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));

                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end(); 
    ?>
        </div>
        </div>
        </div>
        </div>

                    
<script>
$(document).ready(function(){
    $("#userResetPass").validationEngine();
    });
</script>


<div class="widget-box">
    <div class="widget-header widget-header-blue widget-header-flat">
            <h4 class="lighter">Change password</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="row-fluid">
                <?php echo $this->Form->create('User', array('id' => 'userResetPass','class'=>'form-horizontal'));
		echo $this->Form->input('id');
		echo $this->Form->hidden('username');
                
		echo $this->Form->input('password', array('value' => '','id'=>'password','class'=>'validate[required] span6','placeholder'=>'Password','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('confirm_password', array('type' => 'password', 'value' => '','id'=>'confirm_password','class'=>'validate[required,equals[password]] span6','placeholder'=>'Confirm Password','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                
                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end(); 
                ?>
            </div>
        </div>
    
    </div>
</div>

</div>