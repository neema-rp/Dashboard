<?php ?>
<script type="text/javascript" src="/js/jquery-1.6.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />

<script src="/js/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="/css/chosen.css" />

<script>
$(document).ready(function(){
    $(".chzn-select").chosen();
    $("#SubadminAdd").validationEngine();
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Add Assign Hotel to Sub-Admin</small></h1>
        </div>    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Assign Hotels</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
                <?php echo $this->Form->create('Subadmin', array('id' => 'SubadminAdd','class'=>'form-horizontal'));
                echo $this->Form->input('SubadminClient.client_id', array('options' => $all_hotels,'multiple'=>'multiple', 'value'=>$allclients,'class'=>'chzn-select span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'Subadmins', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end();
                ?>
                </div>
            </div>
        </div>
    </div>
</div>