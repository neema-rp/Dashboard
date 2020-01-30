<?php ?><script>
$(document).ready(function(){
    
    });
</script>

<div class="sheets form">
<?php echo $this->Form->create('Sheet', array('url' => array('controller' => 'sheets', 'action' => 'copy_column' ,$sheetId)));?>
<fieldset>
    <legend><?php __($department. '-' .date("F",mktime(0,0,0,$sdata['Sheet']['month'],1,2011)) . ' :  Copy Column to another Sheet for same Month'); ?></legend>
            <div style="font-size: 11px;">*It will overwrite the column values of selected department sheet.</div>
            
            <?php // echo '<pre>'; print_R($sdata); echo "</pre>";
            //echo "Client Id:".$client_id;
            //print_r($deparments); ?>
            <?php echo $form->input('column_from',array('empty' =>array('0' => 'Select Column'),'style'=>'width:200px;border:1px solid #ccc;','id'=>'select_column_from','options'=>$sheet_columns)); ?>
            
            <?php echo $form->input('department',array('empty' =>array('0' => 'Select'),'style'=>'width:200px;border:1px solid #ccc;','id'=>'select_department','options'=>$deparments)); ?>
            
            <?php echo $form->input('column_to',array('empty' =>array('0' => 'Select Column'),'style'=>'width:200px;border:1px solid #ccc;','id'=>'select_column_to','options'=>$columns)); ?>
            
            
            <input type="hidden" name="data[Sheet][sheet_id]" value="<?=$sdata['Sheet']['id']; ?>"/>
            <input type="hidden" name="data[Sheet][month]" value="<?=$sdata['Sheet']['month']; ?>"/>
            <input type="hidden" name="data[Sheet][year]" value="<?=$sdata['Sheet']['year']; ?>"/>
            <input type="hidden" name="data[Sheet][user_id]" value="<?=$sdata['Sheet']['user_id']; ?>"/>
            
            
</fieldset>
<div style="float:left;width:110px;">
<?php echo $this->Form->submit(__('Submit', true), array('div' => false));
echo $this->Form->end();?>
</div>
<div style="float:left;margin-top:5px;height:40px;">
<?php echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'index' ,$userId,$this->params['pass'][2]), array('class' => 'new_button', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;'));?>
</div>
</div>
<div class="admin_left_pannel">
	<div class="actions">
		<h3><?php __('Actions'); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('Dashboard', true), array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List Hotels', true), array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('List Users', true), array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('Logout', true), array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'logout')); ?></li>
		</ul>
	</div>
</div>