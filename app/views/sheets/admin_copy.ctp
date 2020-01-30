<?php ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />

<script>
$(document).ready(function(){
    $("#SheetAdminAddForm").validationEngine();
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __($department .':'); ?> <small><i class="icon-double-angle-right"></i>  Copy Department Sheet</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter"> Copy Department Sheet</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

<?php echo $this->Form->create('Sheet', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'copy' ,$userId,$this->params['pass'][1], $this->params['pass'][2])));?>
	
	<?php echo $this->Form->input('name',array('class'=>'validate[required] text-input')); ?>

	<div class="input text required">
		<label for="SheetMonthMonth">Month</label>
		<?php
			echo $this->Form->month('Sheet.departmentmonth', null, array('empty' => 'Select Month', 'class'=>'validate[required]'));
			echo $this->Form->error('month');
		?>
	</div>
	<div class="input text required">
		<label for="SheetYearYear">Year</label>
		<?php
			echo $this->Form->year('Sheet.departmentmonth', 2010, 2025, date('Y'), array('empty' => 'Select Year', 'class'=>'validate[required]'));
			echo $this->Form->error('year');
		?>
	</div>

	
	<input type="hidden" name="data[Sheet][department_id]" value="<?=$this->params['pass'][2]; ?>"/>
            <?php
                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'index',$userId,$this->params['pass'][2]), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end();
            ?>

        </div></div></div></div>
</div>
