<?php  ?>
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
                <h1>client <small><i class="icon-double-angle-right"></i> Add Question</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Add New Survey Question</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
                    
    <?php echo $this->Form->create('SurveyQuestion', array('url'=>array('action'=>'add',$client_id),'id' => 'UserProfileEdit'));?>
	<?php
		echo $this->Form->input('id');
                echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id));
		echo $this->Form->input('title',array('id'=>'title','class'=>'validate[required] span9','placeholder'=>'Title','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
	?>
                <br/>
            <select  name="data[SurveyQuestion][type]" class="validate[required] span6">
                <option value="0">Select Type</option>
                <option value="Scores">Scores</option>
                <option value="Input Area">Input Area</option>
           </select>
                <br/>
                <?php
                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'client', 'client' => true, 'controller' => 'SurveyQuestions', 'action' => 'index',$client_id), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end();
                ?>
                
    </div></div></div></div>
</div>