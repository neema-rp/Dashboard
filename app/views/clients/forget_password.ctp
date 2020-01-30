<script>
$(document).ready(function(){
	// binds form submission and fields to the validation engine
    $("#forgotpassword").validationEngine();
    });
</script>

<div class="clients form">
<?php echo $this->Form->create('Client', array('action'=>'forget_password', 'id' =>'forgotpassword'));?>
	<fieldset>
	<?php
		echo $this->Form->input('email',array('id'=>'email','class'=>'validate[required,custom[email]]'));
	?>
	</fieldset>
<?php echo $this->Form->submit(__('Submit', true));?>
<?php echo $this->Form->end();?>
</div>
<div class="actions">
</div>


