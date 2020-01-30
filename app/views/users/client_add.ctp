<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#UserClientAddForm").validationEngine();
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotel <small><i class="icon-double-angle-right"></i> Add User</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Add New User</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">


<?php echo $this->Form->create('User', array('url' => array('prefix' => 'client', 'client' => true, 'controller' => 'users', 'action' => 'add', $clientId)));?>
	
	<?php
		echo $this->Form->input('department_name', array('options' => $departments,'id'=>'department_name','class'=>'validate[required]','multiple'=>'multiple','style'=>'width:250px'));
		echo $this->Form->input('username',array('id'=>'username','class'=>'validate[required,length[0,20]]'));
		echo $this->Form->input('password',array('id'=>'password','class'=>'validate[required] text-input'));
		echo $this->Form->input('firstname',array('id'=>'firstname','class'=>'validate[required] text-input'));
		echo $this->Form->input('lastname');
		echo $this->Form->input('email',array('id'=>'email','class'=>'validate[required,custom[email]]'));
		echo $this->Form->input('phone',array('id'=>'phone','class'=>'validate[required,length[0,10]]'));
	
                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'client', 'client' => true, 'controller' => 'users', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end();
                
        ?>
	
        </div>
        </div>
        </div>
        </div>

</div>