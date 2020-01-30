<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />

<script>
$(document).ready(function(){
    $("#ColumnAdminAddForm").validationEngine();
});
</script>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin Add Column Range for Hotel <small><i class="icon-double-angle-right"></i> <?php echo $hotelname;  ?></small></h1>
        </div>
        <?php echo $this->Form->create('Column'); ?>
        <table class="table table-striped table-bordered table-hover">
          <tr><td><b>Column Name</b></td><td><b>Low</b></td><td><b>Moderate</b></td><td><b>Busy</b></td></tr>
          <?php $i = '0';
          foreach($columns as $column){
              $column_check = $this->requestAction('/columns/check_column/'.$column['Column']['id'].'/'.$client_id); ?>
              <tr>
                  <td>
                  <?php echo $this->Form->input('ColumnRange.'.$i.'.client_id',array('id'=>'client_id','type'=>'hidden','value'=>$client_id)); ?>
                  <?php echo $this->Form->input('ColumnRange.'.$i.'.id',array('id'=>'id','type'=>'hidden','value'=>@$column_check['ColumnRange']['id'])); ?>
                  <?php echo $this->Form->input('ColumnRange.'.$i.'.column_id',array('id'=>'column_id','type'=>'hidden','value'=>$column['Column']['id'])); ?>
                  <?php echo $this->Form->input('ColumnRange.'.$i.'.column_name',array('id'=>'column_name','type'=>'hidden','value'=>$column['Column']['name'])); ?>
                  <?php echo $column['Column']['name']; ?>
                  </td>
                  <td><?php echo $this->Form->input('ColumnRange.'.$i.'.low_value',array('id'=>'low_value','class'=>'','label'=>false,'style'=>'border:1px solid #cccccc;','value'=>@$column_check['ColumnRange']['low_value'])); ?></td>
                  <td><?php echo $this->Form->input('ColumnRange.'.$i.'.moderate_value',array('id'=>'moderate_value','class'=>'','label'=>false,'style'=>'border:1px solid #cccccc;','value'=>@$column_check['ColumnRange']['moderate_value'])); ?></td>
                  <td><?php echo $this->Form->input('ColumnRange.'.$i.'.busy_value',array('id'=>'busy_value','class'=>'','label'=>false,'style'=>'border:1px solid #cccccc;','value'=>@$column_check['ColumnRange']['busy_value'])); ?></td>
              </tr>
          <?php $i++; } ?>
        </table>
        <?php
        echo $this->Form->submit(__('Save', true), array('div' => false,'class'=>'btn btn-info'));
        echo "&nbsp;&nbsp;";
        echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
        echo $this->Form->end();
        ?>
</div>