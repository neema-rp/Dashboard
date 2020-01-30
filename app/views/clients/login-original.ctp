<?php 
// echo $this->Html->script('jquery.validationEngine-en');
// echo $this->Html->script('jquery.validationEngine');
// echo $this->Html->css('validationEngine.jquery');
?> 
<script>
$(document).ready(function(){
    $("#ClientLoginForm").validationEngine();
    });
</script>
<div class="clients form">
<?php echo $this->Form->create('Client');?>
	<fieldset>
	<?php
		echo $this->Form->input('username',array('id'=>'username','class'=>'validate[required] text-input'));
		echo $this->Form->input('password',array('id'=>'password','class'=>'validate[required] password'));
	?>
	</fieldset>
<?php echo $this->Form->submit(__('Login', true));?>
<?php echo $this->Html->link("Forgot Password",array('controller' => 'clients', 'action' => 'forget_password')); ?> 
<!--    &nbsp;| &nbsp;-->
<?php //echo $this->Html->link("Login as User",array('controller' => 'users', 'action' => 'login', 'client'=>true)); ?>
<?php echo $this->Form->end();?>

<br/>
<i style="font-size:12px">Best Viewed in Firefox</i>

</div>
<div class="actions">
	<h2>Hotel Admin Login</h2>
        
        <br/><br/>
        <ul style="float:left;width:150px;">
<!--                <li><a href="/clients/login">Client Login</a></li>-->
<!--                <li><a href="/client/users/login">Assigned User Login</a></li>
                <li><a href="/users/login">User Login</a></li>
                <li><a href="/admins/login">Admin Login</a></li>-->
                <li><a href="/staff/users/alllogin">Hotel User</a></li>
        </ul>
        
</div>

<!--<body onload="document.ClientLoginForm.data[Admin][username].focus();">-->

