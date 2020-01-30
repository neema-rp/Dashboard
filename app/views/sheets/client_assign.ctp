<?php ?>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Assign User For  <small><i class="icon-double-angle-right"></i> <?php echo $departmentname[0]['Department']['name']; ?></small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Asssign User</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
	
	<?php echo $this->Form->create('Sheet', array('url' => array('prefix' => 'client', 'client' => true, 'action' => 'client_assign',$clientId,$departmentname[0]['Department']['id'] ))); ?>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo "First Name";?> &nbsp;<?php echo "Last Name";?></th>
	</tr>
	</table>
        <?php
            foreach ($userdata as $sheet):
                 $option[$sheet['User']['id']] =  $sheet['User']['firstname']." ".$sheet['User']['lastname'] ;
            endforeach;

            echo $this->Form->input('User.id',array('div'=>false,'label'=>false,'type'=>'select', 'multiple' => true,'size' => 20,'options' =>$option,'selected'=>$userid,'class'=>'span6','style'=>'height:200px;'));

            echo $this->Form->input('User.department_name',array('value'=>$departmentname[0]['Department']['name'],'type'=>'hidden'));
        ?>
                    <br/>
        <?php echo $this->Form->submit(__('Save', true), array('div' => false,'class'=>'btn btn-info')); ?>
</div></div></div></div>	
</div>