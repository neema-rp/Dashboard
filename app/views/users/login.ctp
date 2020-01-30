 <?php 
// echo $this->Html->script('jquery.validationEngine-en');
// echo $this->Html->script('jquery.validationEngine');
// echo $this->Html->css('validationEngine.jquery');
?> 
<script>
$(document).ready(function(){
    $("#formID").validationEngine();
    });
</script>
 

<div class="clients form">
<?php echo $this->Form->create('User', array('id'=>'formID'));?>
	<fieldset>
	<?php
		echo $this->Form->input('username', array('id'=>'username','class'=>'validate[required] text-input'));
		echo $this->Form->input('password',array('id'=>'password','class'=>'validate[required] text-input'));
	?>
	</fieldset>
<?php echo $this->Form->submit(__('Login', true));?>
<?php echo $this->Html->link("Forget Password",array('controller' => 'users', 'action' => 'forget_password')); ?>
<?php echo $this->Form->end();?>
</div>
<div class="actions">
	<h2>User Login</h2>
        
        <br/><br/>
        <ul style="float:left;width:150px;">
                <li><a href="/clients/login">Hotel Admin</a></li>
                <li><a href="/staff/users/alllogin">Hotel User</a></li>
<!--                <li><a href="/client/users/login">Assigned User Login</a></li>-->
<!--                <li><a href="/users/login">User Login</a></li>-->
<!--                <li><a href="/admins/login">Admin Login</a></li>-->
        </ul>
</div>



