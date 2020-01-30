<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#SubadminProfileEdit").validationEngine();
    });
</script>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Edit Sub-Admin</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Sub-Admin Profile</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                        <div class="row-fluid">
                        <?php echo $this->Form->create('Subadmin', array('type' => 'file', 'id' => 'SubadminProfileEdit','class'=>'form-horizontal'));
                        echo $this->Form->input('id');
                        echo $this->Form->input('username',array('id'=>'username','class'=>'validate[required,length[0,20]] span6','placeholder'=>'Username','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('firstname',array('id'=>'firstname','class'=>'validate[required,length[0,20]] span6','placeholder'=>'Firstname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('lastname',array('id'=>'lstname','class'=>'validate[required] span6','placeholder'=>'Lastname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('email',array('id'=>'email','class'=>'validate[required,custom[email]] span6','placeholder'=>'Email','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('phone',array('id'=>'phone','class'=>'validate[required,length[0,10]] span6','placeholder'=>'Phone','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                        ?>
                        </div>
                    </div>
                </div>
        </div>

<script>
$(document).ready(function(){
    $("#SubadminResetPass").validationEngine();
    });
</script>

        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Change Password</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                        <div class="row-fluid"> 
                        <?php echo $this->Form->create('Subadmin', array('id' => 'SubadminResetPass','class'=>'form-horizontal'));
                        echo $this->Form->input('id');
                        echo $this->Form->input('password', array('value' => '','id'=>'password','class'=>'validate[required] span6','placeholder'=>'Password','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('confirm_password', array('type' => 'password', 'value' => '','id'=>'confirm_password','class'=>'validate[required,equals[password] span6','placeholder'=>'Confirm Password','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));

                        echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                        echo $this->Form->end(); ?>

                        </div>
                    </div>
                </div>
    </div>

</div>

