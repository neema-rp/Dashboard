<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#ColumnAdminEditForm").validationEngine();
    });
</script>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Edit Column</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Column</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                         <div class="row-fluid">
                                <?php echo $this->Form->create('Column',array('class'=>'form-horizontal'));
                		echo $this->Form->input('name',array('id'=>'name','class'=>'validate[required,custom[onlyLetter]] span6','placeholder'=>'Column Name','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                                echo "&nbsp;&nbsp;";
                                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'columns', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                                echo $this->Form->end();
                        	?>
</div>
</div></div></div></div>