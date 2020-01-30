<script>
$(document).ready(function(){
    $("#MarketSegmentAdminAddForm").validationEngine();
    });
</script>
<div class="users form">
<?php echo $this->Form->create('MarketSegment');?>
	<fieldset>
 		<legend><?php __('Admin Add MarketSegment Range for Hotel '); ?><?php echo $hotelname; ?></legend>
              <table>
                  <tr><td><b>MarketSegment Name</b></td><td><b>Low</b></td><td><b>Moderate</b></td><td><b>Busy</b></td></tr>
                  
                  <?php $i = '0';
                  foreach($MarketSegments as $MarketSegment){ 
                      
                      $MarketSegment_check = $this->requestAction('/MarketSegments/check_MarketSegment/'.$MarketSegment['MarketSegment']['id'].'/'.$client_id);
                      
                      ?>
                  <tr>
                      <td>
                      <?php echo $this->Form->input('MarketSegmentRange.'.$i.'.client_id',array('id'=>'client_id','type'=>'hidden','value'=>$client_id)); ?>
                      <?php echo $this->Form->input('MarketSegmentRange.'.$i.'.id',array('id'=>'id','type'=>'hidden','value'=>@$MarketSegment_check['MarketSegmentRange']['id'])); ?>
                      <?php echo $this->Form->input('MarketSegmentRange.'.$i.'.MarketSegment_id',array('id'=>'MarketSegment_id','type'=>'hidden','value'=>$MarketSegment['MarketSegment']['id'])); ?>
                      <?php echo $this->Form->input('MarketSegmentRange.'.$i.'.MarketSegment_name',array('id'=>'MarketSegment_name','type'=>'hidden','value'=>$MarketSegment['MarketSegment']['name'])); ?>
                      <?php echo $MarketSegment['MarketSegment']['name']; ?>
                      </td>
                      <td><?php echo $this->Form->input('MarketSegmentRange.'.$i.'.low_value',array('id'=>'low_value','class'=>'','label'=>false,'style'=>'border:1px solid #cccccc;','value'=>@$MarketSegment_check['MarketSegmentRange']['low_value'])); ?></td>
                      <td><?php echo $this->Form->input('MarketSegmentRange.'.$i.'.moderate_value',array('id'=>'moderate_value','class'=>'','label'=>false,'style'=>'border:1px solid #cccccc;','value'=>@$MarketSegment_check['MarketSegmentRange']['moderate_value'])); ?></td>
                      <td><?php echo $this->Form->input('MarketSegmentRange.'.$i.'.busy_value',array('id'=>'busy_value','class'=>'','label'=>false,'style'=>'border:1px solid #cccccc;','value'=>@$MarketSegment_check['MarketSegmentRange']['busy_value'])); ?></td>
                  </tr>
                  <?php $i++; } ?>
                  
              </table>
                
	</fieldset>
<div style="float:left;width:110px;">
<?php echo $this->Form->submit(__('Submit', true), array('div' => false));
echo $this->Form->end();?>
</div>
<div style="float:left;margin-top:5px;height:40px;">
<?php echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'index'), array('class' => 'new_button', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;'));?>
</div>
</div>
<div class="admin_left_pannel">
	<div class="actions">
		<h3><?php __('Actions'); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('Dashboard', true), array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List Clients', true), array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('List Users', true), array('prefix' => 'admin', 'admin' => true, 'action' => 'index'));?></li>
			<!--li><?php //echo $this->Html->link(__('New Client', true), array('controller' => 'clients', 'action' => 'add')); ?> </li-->
			<li><?php echo $this->Html->link(__('Logout', true), array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'logout')); ?></li>
		</ul>
	</div>
</div>