<?php
?>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Assign User For  <small><i class="icon-double-angle-right"></i> <?php echo $client_name; ?></small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Assign User</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
	
	<?php if(!empty($users)){ ?>
	<?php echo $this->Form->create('Client', array('url' => array('prefix' => 'admin', 'admin' => true,'controller' => 'clients' ,'action' => 'admin_assign',$clientId))); ?>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo "First Name";?> &nbsp;<?php echo "Last Name";?></th>
	</tr>
	</table>
		<?php 
			echo $this->Form->input('ClientUser.user_id',array('div'=>false,'label'=>false, 'multiple' => true,'size' => 10,'options' =>$users,'class'=>'span6','style'=>'height:200px;'));
		?>
            <br/>
            <?php echo $this->Form->submit(__('Save', true), array('div' => false,'class'=>'btn btn-info')); ?>
             
	<?php } else { ?>
		<b> No Users Found! </b>
	<?php } ?>
</div></div></div></div>	
</div>