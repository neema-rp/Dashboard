<script type="text/javascript">
function validate_form()
{
  var fname = $("#ContactFirstname").val();
  var lname = $("#ContactLastname").val();
  var email = $("#ContactEmail").val();
  
  if(fname == "")
  {
    alert("First name cannot be empty.");
    $("#ContactFirstname").focus(); 
    return false;
  }	
  else
  {
    if(lname == "")
    {
      alert("Last name cannot be empty.");
      $("#ContactLastname").focus();
      return false;
    }
    else
    {
      if(email == "")
      {
	alert("Email cannot be empty.");
	$("#ContactEmail").focus();
	return false;
      }
      else
      {
	return true;
      }
    }
  }
  
}

</script>
<?php $controller = $this->params['controller'];?>
<div class="Clients form">	
	<fieldset>
		<legend><?php __('Add Contact'); ?></legend>
	<?php echo $this->Form->create('Contact', array('id' => 'contactID', 'onsubmit' => 'return validate_form();'));?>
		<table width="100%">
			<tbody>
				<tr>
					<td colspan="2" class="" style="padding-bottom:20px;">
                        <span style="float:left;"><?php echo $this->Html->link($this->Form->button('Back', array('div' => false, 'type' => 'button')), array('prefix' => 'admin', 'admin' => true,'controller' => $controller, 'action' => 'index'), array('style' => 'text-decoration:none; border:0;', 'escape' => false));?></span>
                    </td>
				</tr>
                 <tr>
					<td><label>Title</label></td>

					<td><?php echo $this->Form->input("Contact.title",array('class' => 'validate[required] inp_tit', 'label'=>false, 'div'=>false, 'options' => array('Mr' => 'Mr', 'Mrs' => 'Mrs', 'Miss' => 'Miss')));?>
				</tr>
                
                <tr>
					<td><label>First Name:</label></td>
					<td><?php echo $this->Form->input('Contact.firstname', array("label"=>false, 'div'=>false));?></td>
				</tr>
                <tr>
					<td><label>Last Name:</label></td>
					<td><?php echo $this->Form->input('Contact.lastname', array("label"=>false, 'div'=>false));?></td>
				</tr>
                
                <tr>
					<td><label>Email:</label></td>
					<td>
<input type="email" id="ContactEmail" name="data[Contact][email]" maxlength="100"/>
<!-- <?php echo $this->Form->input('Contact.email', array("label"=>false, 'div'=>false));?></td> -->
				</tr>
                <tr>
					<td><label>Contact:</label></td>
					<td><?php echo $this->Form->input('Contact.contact', array("label"=>false, 'div'=>false));?></td>
				</tr>
                <tr>
					<td><label>Comment:</label></td>
					<td><?php echo $this->Form->input('Contact.comment', array("label"=>false, 'div'=>false));?></td>
				</tr>
               
				<tr>
					<td>&nbsp;</td>
					<td align="left">
						<span><?php echo $this->Form->submit(__('Submit', true), array('div' => false)); echo $this->Form->end();?></span>
						<span style="margin-left:10px;"><?php echo $this->Html->link($this->Form->button('Cancel', array('div' => false, 'type' => 'button')), array('prefix' => 'admin', 'admin' => true, 'controller' => $controller, 'action' => 'index'), array('style' => 'text-decoration:none; border:0;', 'escape' => false));?></span>
					</td>
				</tr>
			</tbody>
			</table>		
</fieldset>
</div>
<?php echo $this->element('admins/admin_left_links');?>