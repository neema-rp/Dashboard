<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Edit Segmentation</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Segmentation</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                        <div class="row-fluid">
        <?php echo $this->Form->create('AdvancedSheet', array('class'=>'form-horizontal','url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'AdvancedSheets', 'action' => 'edit' ,$this->data['AdvancedSheet']['id'])));
        echo $this->Form->input('id',array('type'=>'hidden'));
        echo $this->Form->input('user_id',array('type'=>'hidden'));
        echo $this->Form->input('department_id',array('type'=>'hidden'));
        echo $this->Form->input('name',array('class'=>'validate[required] span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group'))); 
        ?>
                            
        <div class="control-group">
                <label class="control-label" for="form-field-1">Month</label>
                <div class="controls">
                       <?php
                                $selectMonth = isset($this->data['AdvancedSheet']['month']) ? $this->data['AdvancedSheet']['month'] : date('m');
                                echo $this->Form->month('AdvancedSheet.month', null, array('empty' => false,'value'=>$selectMonth,'class'=>'validate[required] span3'));
                                echo $this->Form->error('month');
                        ?>
                </div>
        </div>

        <div class="control-group">
                <label class="control-label" for="form-field-1">Year</label>
                <div class="controls">
                       <?php
                                $selectYear = isset($this->data['AdvancedSheet']['year']) ? $this->data['AdvancedSheet']['year'] : date('Y');
                                echo $this->Form->year('AdvancedSheet.year', 2010, 2025, $selectYear, array('empty' => 'Select Year','value'=>$selectYear, 'class'=>'validate[required] span3'));
                                echo $this->Form->error('year');
                        ?>
                </div>
        </div>

         <label>
            <?php echo $this->Form->input('AdvancedSheet.import_grunerbaum', array('div'=>false,'label'=>false)); ?>
            <span class="lbl"> Import Grunerbaum Excel</span>
        </label>
         <label>
            <?php echo $this->Form->input('AdvancedSheet.import_simola', array('div'=>false,'label'=>false)); ?>
            <span class="lbl"> Import CSV (Simola) </span>
        </label>

    <br/>    
    <?php
    echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
    echo "&nbsp;&nbsp;";
    echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'advancedSheets', 'action' => 'index',$this->data['AdvancedSheet']['user_id'],$this->data['AdvancedSheet']['department_id']), array('class' => 'btn btn-success', 'escape' => false));
    echo $this->Form->end();
    ?>
    
</div></div></div></div></div>