<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#UserAdminAddForm").validationEngine();
    <?php if(isset($this->data) && isset($this->data['User']) && isset($this->data['User']['client_id'])){ ?>
	getDepartmentList(<?php echo $this->data['User']['client_id']; ?>)
    <?php } ?>
});
</script>


<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Add User</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Add New User</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

<?php echo $this->Form->create('User', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'add', $clientId),'class'=>'form-horizontal'));?>
	
      <div class="input select required">
	<?php
		echo $this->Form->input('client_id',array('class'=>'validate[required]','value' => $clientId, 'onChange' => 'getDepartmentList(this.value)', 'label'=>'Hotel', 'empty'=>'Select Hotel','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
	?>
      </div>
	<div id="div_departments" class="control-group">
	<?php	
		echo $this->Form->input('department_name', array('options' => $departments,'id'=>'department_name','class'=>'validate[required] span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
	?>
	</div>
	<?php	
		echo $this->Form->input('username',array('id'=>'username','class'=>'validate[required] span6','placeholder'=>'Username','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('password',array('id'=>'password','class'=>'validate[required] password span6','placeholder'=>'Password','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('firstname',array('id'=>'firstname','class'=>'validate[required,length[0,20]] span6','placeholder'=>'Firstname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('lastname',array('id'=>'lastname','class'=>'span6','placeholder'=>'Lastname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('email',array('id'=>'email','class'=>'validate[required,custom[email]] span6','placeholder'=>'Email','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('phone',array('id'=>'phone','class'=>'validate[required,length[0,10]] span6','placeholder'=>'Phone','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
	
                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end(); 
                ?>
                 
        </div>
        </div>
        </div>
        </div>

</div>
<script type="text/javascript" language="javascript">
function getDepartmentList(client_id){
	//alert(client_id);
	jQuery.ajax({
		type: "GET",
		url:"/users/getDepartmentList/"+client_id,
		beforeSend:function(){
			document.getElementById('div_departments').innerHTML="Loading...";
		},
		success: function(rmsg){
			if(rmsg){
				document.getElementById('div_departments').innerHTML=rmsg;
				<?php if(isset($this->data) && isset($this->data['department_name']) && isset($this->data['department_name']['0'])){ ?>
				    var ele = document.getElementById('department_name');
				    ele.value = <?php echo $this->data['department_name']['0']; ?>
				<?php } ?>

			}
		}
	});
}

</script>
