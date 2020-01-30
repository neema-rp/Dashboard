<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />


<script>
$(document).ready(function(){
    $("#AdminProfileEdit").validationEngine();
    });
</script>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Edit Profile</small></h1>
        </div>
    

            <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Update Details</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
                    
                <?php echo $this->Form->create('Admin', array('id' => 'AdminProfileEdit','class'=>'form-horizontal'));?>
                <?php
                        echo $this->Form->input('id');
                        
                        echo $this->Form->input('username',array('class'=>'validate[required,length[0,20]] span6','onkeypress'=>'return checkSpace(event)','placeholder'=>'Username','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('firstname',array('class'=>'validate[required] span6','placeholder'=>'Firstname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('lastname',array('class'=>'validate[required] span6','placeholder'=>'Lastname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('email',array('class'=>'validate[required,custom[email]] span6','placeholder'=>'Email','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('website',array('class'=>'validate[required] span6','placeholder'=>'Website','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('address',array('type'=>'textarea','class'=>'validate[required] span6','placeholder'=>'Address','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('city',array('class'=>'validate[required] span6','placeholder'=>'City','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('region',array('class'=>'validate[required] span6','placeholder'=>'Region','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('country',array('class'=>'validate[required] span6','placeholder'=>'Country','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('phone',array('class'=>'validate[required] span6','placeholder'=>'Phone','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                ?>
                    <?php echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                    echo "&nbsp;&nbsp;";
                    echo $this->Html->link('Cancel', array('controller' => 'clients', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                    echo $this->Form->end();?>
                </div>
             </div>
            </div>
        </div>
<script>
$(document).ready(function(){
    $("#AdminResetPass").validationEngine();
    });
</script>


    
        <div class="widget-box">
        <div class="widget-header widget-header-blue widget-header-flat">
                <h4 class="lighter">Change password</h4>
            </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
                    <?php echo $this->Form->create('Admin', array('id' => 'AdminResetPass','class'=>'form-horizontal'));
                            echo $this->Form->input('id');
                            echo $this->Form->hidden('username');
                            echo $this->Form->input('password', array('value' => '','id'=>'password','class'=>'validate[required] span6','placeholder'=>'Password','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                            echo $this->Form->input('confirm_password', array('type' => 'password', 'value' => '','id'=>'confirm_password','class'=>'validate[required,equals[password] span6','placeholder'=>'Confirm Password','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                    
                       echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                       echo "&nbsp;&nbsp;";
                       echo $this->Html->link('Cancel', array('controller' => 'clients', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                       echo $this->Form->end();
                       ?>
                    </div></div></div>
            </div>
        </div>
</div>

