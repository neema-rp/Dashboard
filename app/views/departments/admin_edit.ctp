<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#DepartmentAdminEditForm").validationEngine();
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Edit Department</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Edit Department</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
	<?php echo $this->Form->create('Department', array('type' => 'file', 'url'=> "/admin/departments/edit/{$depatmentId}/{$client_id}",'id'=>'DepartmentAdminEditForm'));?>
		
			<legend><?php __('Admin: Edit Department'); ?></legend>
		<?php
                    echo $this->Form->input('id');
                    echo $this->Form->input('name',array('id'=>'name','class'=>'validate[required,length[0,30]] span6','placeholder'=>'Name','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));

                    echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                    echo "&nbsp;&nbsp;";
                    echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                    echo $this->Form->end();
                ?>
</div></div></div></div>
</div>