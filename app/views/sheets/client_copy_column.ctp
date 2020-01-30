<?php  ?>

<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __($department .':'); ?> <small><i class="icon-double-angle-right"></i><?php echo date("F",mktime(0,0,0,$sdata['Sheet']['month'],1,2011)); ?>  Copy Column to another Sheet for same Month</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter"> Copy Column</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

<?php echo $this->Form->create('Sheet', array('url' => array('controller' => 'sheets', 'action' => 'client_copy_column' ,$sheetId)));?>

            <div style="font-size: 11px;">*It will overwrite the column values of selected department sheet.</div>
            
            <?php echo $form->input('column_from',array('empty' =>array('0' => 'Select Column'),'style'=>'width:200px;border:1px solid #ccc;','id'=>'select_column_from','options'=>$sheet_columns)); ?>
            
            <?php echo $form->input('department',array('empty' =>array('0' => 'Select'),'style'=>'width:200px;border:1px solid #ccc;','id'=>'select_department','options'=>$deparments)); ?>
            
            <?php echo $form->input('column_to',array('empty' =>array('0' => 'Select Column'),'style'=>'width:200px;border:1px solid #ccc;','id'=>'select_column_to','options'=>$columns)); ?>
            
            
            <input type="hidden" name="data[Sheet][sheet_id]" value="<?=$sdata['Sheet']['id']; ?>"/>
            <input type="hidden" name="data[Sheet][month]" value="<?=$sdata['Sheet']['month']; ?>"/>
            <input type="hidden" name="data[Sheet][year]" value="<?=$sdata['Sheet']['year']; ?>"/>
            <input type="hidden" name="data[Sheet][user_id]" value="<?=$sdata['Sheet']['user_id']; ?>"/>

 <?php
    echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
    echo "&nbsp;&nbsp;";
    echo $this->Html->link('Cancel', array('prefix' => 'client', 'client' => true, 'controller' => 'departments', 'action' => 'list'), array('class' => 'btn btn-success', 'escape' => false));
    echo $this->Form->end();
?>

    </div></div></div></div>
</div>