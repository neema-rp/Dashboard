<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />

<script src="/js/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="/css/chosen.css" />

<script>
$(document).ready(function(){
    
        $(".chzn-select").chosen();

	$("#TemplateAdminAddForm").validationEngine();
});
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Add Template</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Template</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                        <div class="row-fluid">
                            <?php echo $this->Form->create('Template', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'Templates', 'action' => 'add')));
                            echo $this->Form->input('name',array('class'=>'validate[required] span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                            echo $this->Form->input('MarketSegment', array('multiple' => "multiple",'div' => false, 'label' => 'Select MarketSegment','class'=>'validate[required] chzn-select span6','options'=>$marketsegments,'label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                            echo $this->Form->input('Column', array('div' => false,'class'=>'validate[required] chzn-select span6','label'=>array('class'=>'control-label', 'text' => 'Select Columns for Template'),'div'=>array('class'=>'control-group')));
                            echo $this->Form->input('Column', array('multiple' => "multiple",'name'=>'data[Row][Row]','id'=>'RowRow','class'=>'chzn-select span6', 'div' => false,'label'=>array('class'=>'control-label', 'text' => 'Select Result Column'),'div'=>array('class'=>'control-group')));

                            echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                            echo "&nbsp;&nbsp;";
                            echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'Templates', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                            echo $this->Form->end();                
                            ?>
                        </div>
                     </div>
                </div>
        </div>
</div>
