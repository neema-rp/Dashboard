<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#UserClientEditForm").validationEngine();
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotel <small><i class="icon-double-angle-right"></i> Edit User</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Edit User</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

<?php echo $this->Form->create('User', array('url' => array('prefix' => 'client', 'client' => true, 'controller' => 'users', 'action' => 'edit',$depatmentId ,$client_id,)));?>

	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('DepartmentsUser.department_name', array('options' => $departments,'multiple'=>'multiple', 'value'=>$depts, 'style'=>'width:250px'));
		echo $this->Form->input('username',array('class'=>'validate[required,length[0,20]]'));
		echo $this->Form->input('firstname',array('class'=>'validate[required] text-input'));
		echo $this->Form->input('lastname',array('class'=>'validate[required] text-input'));
		echo $this->Form->input('email',array('class'=>'validate[required,custom[email]]'));
		echo $this->Form->input('phone',array('class'=>'validate[required,length[0,10]]'));
                
                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'client', 'client' => true, 'controller' => 'users', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
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
            <?php echo $this->Form->create('User', array('id' => 'userResetPass'));?>
            <?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('username');
		echo $this->Form->input('password', array('value' => '','id'=>'password','class'=>'validate[required] text-input'));
		echo $this->Form->input('confirm_password', array('type' => 'password', 'value' => '','id'=>'confirm_password','class'=>'validate[required,equals[password]]'));
	
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