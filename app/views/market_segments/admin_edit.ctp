<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#MarketSegmentAdminEditForm").validationEngine();
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Edit MarketSegment</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Edit MarketSegment</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                        <div class="row-fluid">
                        <?php echo $this->Form->create('MarketSegment');
                	echo $this->Form->input('name',array('id'=>'name','class'=>'validate[required,custom[onlyLetter]] span6','placeholder'=>'MarketSegment Name','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                        echo "&nbsp;&nbsp;";
                        echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'MarketSegments', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                        echo $this->Form->end();
                    	?>
                        </div>
                     </div>
                </div>
        </div>
</div>