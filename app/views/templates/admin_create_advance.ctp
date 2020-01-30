<?php ?>


<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Add Segmentation Sheet</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Segmentation Sheet</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                        <div class="row-fluid">
<?php echo $this->Form->create('Sheet', array('url' => array('class'=>'form-horizontal','prefix' => 'admin', 'admin' => true, 'controller' => 'Templates', 'action' => 'create_advance' ,$userId,$this->params['pass'][1])));?>

        <div class="control-group">
                <label class="control-label" for="form-field-1">Template</label>
                <div class="controls">
                       <?php
                               echo $this->Form->input('Template.id',array('empty' =>'Select Template','options' => $templates,'class'=>'validate[required] span6'));
                        ?>
                </div>
        </div>
                
	<?php echo $this->Form->input('name',array('class'=>'validate[required] span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group'))); ?>
                    
        <div class="control-group">
                <label class="control-label" for="form-field-1">Month</label>
                <div class="controls">
                       <?php
                                echo $this->Form->month('Sheet.departmentmonth', null, array('empty' => false,'multiple' => true,'size' => 10, 'class'=>'validate[required]'));
                                echo $this->Form->error('month');
                        ?>
                </div>
        </div>

        <div class="control-group">
                <label class="control-label" for="form-field-1">Year</label>
                <div class="controls">
                       <?php
                                echo $this->Form->year('Sheet.departmentmonth', 2010, 2025, date('Y'), array('empty' => 'Select Year', 'class'=>'validate[required]','style'=>'border:1px solid #666;'));
                                echo $this->Form->error('year');
                        ?>
                </div>
        </div>
                            
	
	<input type="hidden" name="data[Sheet][department_id]" value="<?=$this->params['pass'][1]; ?>"/>

<?php
echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
echo "&nbsp;&nbsp;";
echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'advancedSheets', 'action' => 'index',$userId,$this->params['pass'][1]), array('class' => 'btn btn-success', 'escape' => false));
echo $this->Form->end();
?>
    
</div></div></div></div></div>
