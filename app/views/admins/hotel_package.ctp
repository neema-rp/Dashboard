<?php ?>
<script src="/js/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="/css/chosen.css" />

<script>
$(document).ready(function(){
    $(".chzn-select").chosen();
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Add Hotel with Advanced Package</small></h1>
        </div>    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Hotels with Advanced Package</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
                <?php echo $this->Form->create('Admin', array('id' => 'AdminAdd','class'=>'form-horizontal'));
                
                echo $this->Form->input('client_id', array('options' => $all_hotels,'multiple'=>'multiple', 'value'=>$selected_hotes,'class'=>'chzn-select span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                
                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'Admins', 'action' => 'hotel_package'), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end();
                ?>
                </div>
            </div>
        </div>
    </div>
</div>