<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script src="/js/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="/css/chosen.css" />

<script>
$(document).ready(function(){
        $(".chzn-select").chosen();
});
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotel <small><i class="icon-double-angle-right"></i> Add MarketSegment</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">MarketSegment</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                        <div class="row-fluid">
                            <?php echo $this->Form->create('MarketSegment', array('url' => array('prefix' => 'staff', 'staff' => true, 'controller' => 'MarketSegments', 'action' => 'list')));
                            
                             echo $this->Form->input('client_id', array('type' => "hidden",'value'=>$client_id));
                            echo $this->Form->input('Segments', array('multiple' => "multiple",'div' => false, 'label' => 'Select MarketSegment','class'=>'validate[required] chzn-select span6','options'=>$marketsegments,'value'=>$client_segments,'label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));

                            echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                            echo $this->Form->end();                
                            ?>
                        </div>
                     </div>
                </div>
        </div>
</div>