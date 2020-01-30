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
<?php 
echo $this->Form->create('User', array('url' => array('prefix' => 'client', 'client' => true,'controller' => 'users' ,'action' => 'login')));
?>
	<fieldset>
	<?php
		echo $this->Form->input('username',array('id'=>'username','class'=>'validate[required] text-input'));
		echo $this->Form->input('password',array('id'=>'password','class'=>'validate[required] password'));
	?>
	</fieldset>
<?php echo $this->Form->submit(__('Login', true));?>
<?php echo $this->Html->link("Forget Password",array('controller' => 'users', 'action' => 'forget_password', 'client'=>false)); ?> &nbsp;| &nbsp;
<?php echo $this->Html->link("Login as Admin",array('controller' => 'clients', 'action' => 'login', 'client'=>false)); ?>
<?php echo $this->Form->end();?>
</div>
<div class="actions">
	<h2>Assigned User Login</h2>
        
        <br/><br/>
        <ul style="float:left;width:150px;">
                        <li><a href="/clients/login">Client Login</a></li>
<!--                <li><a href="/client/users/login">Assigned User Login</a></li>-->
                <li><a href="/users/login">User Login</a></li>
                <li><a href="/admins/login">Admin Login</a></li>
        </ul>
</div>

<!--<body onload="document.ClientLoginForm.data[Admin][username].focus();">-->

