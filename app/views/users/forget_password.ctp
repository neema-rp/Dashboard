
<script>
$(document).ready(function(){
    $("#forgotpassword").validationEngine();
    });
</script>
<div class="clients form">
<?php echo $this->Form->create('User', array('action'=>'forget_password', 'id' => 'forgotpassword'));?>
	<fieldset>
	<?php
		echo $this->Form->input('email',array('id'=>'email','class'=>'validate[required,custom[email]]'));
	?>
	</fieldset>
<?php echo $this->Form->submit(__('Submit', true));?>
<?php echo $this->Form->end();?>
</div>
<div class="actions">
	<h2></h2>
</div>


