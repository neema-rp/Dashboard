<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#UserProfileEdit").validationEngine();
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Add Guest</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Add New Guest</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
                    
    <?php echo $this->Form->create('SurveyUser', array('url'=>array('action'=>'add',$client_id),'type'=>'file','id' => 'UserProfileEdit','class'=>'form-horizontal'));?>
	<?php
		echo $this->Form->input('id');
                echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id));
                
		echo $this->Form->input('name',array('id'=>'name','class'=>'span6','placeholder'=>'Name','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('email',array('id','email','class'=>'span6','placeholder'=>'Email','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                ?>

                <hr/>OR<hr/>
                <span style="font-size:11px;">Please Import CSV file. Column 1 with name and Column 2 with email</span>
                <?php echo $this->Form->input('csv_file', array('div' => false, 'label' => false,'type'=>'file', 'class' => 'input-large','id'=>'csv_file')); ?>
                

                <hr/>OR<hr/>
                <span style="font-size:11px;">Excel Import. Column A(Title),Column B(Firstname),Column C(Lastname),Column E(Email)</span>
                <?php echo $this->Form->input('excel_file', array('div' => false, 'label' => false,'type'=>'file', 'class' => 'input-large','id'=>'excel_file')); ?>

                <br/>
                <?php echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'SurveyUsers', 'action' => 'index',$client_id), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end();
                ?>
                    
            </div></div></div></div>
</div>