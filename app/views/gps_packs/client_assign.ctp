<?php ?>
<script>
$(document).ready(function(){
	$("#GpclientAddForm").validationEngine();
});
</script>

<style type="text/css">
input { font-size:100%; border:1px solid #ccc; }
</style>

<div class="Gps form">
    
<?php echo $this->Form->create('GpsUser', array('url' => array('prefix' => 'client', 'client' => true, 'controller' => 'GpsPacks', 'action' => 'assign')));?>
    
    <?php
    echo $this->Form->input('user_id',array('type'=>'hidden','value'=>$user_id));
    echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id));
    ?>
    
	<fieldset>
 		<legend><?php __('Assign Steps to User'); ?></legend>

        <div class="input">
		<?php
                
                $steps_list['GM Summary'] = 'GM Summary';
                $steps_list['Summary'] = 'Summary';
                $steps_list['Market Conditions'] = 'Market Conditions';
                $steps_list['Competitor Activity'] = 'Competitor Activity';
                $steps_list['Activity'] = 'Activity';
                $steps_list['Top Producers'] = 'Top Producers';
                $steps_list['Channels'] = 'Channels';
                $steps_list['Channels Year'] = 'Channels Year';
                $steps_list['Geo Year'] = 'Geo Year';
                $steps_list['Prov Year'] = 'Prov Year';
                $steps_list['RoomTypes'] = 'RoomTypes';
                $steps_list['Future Activity'] = 'Future Activity';
                $steps_list['Reputation'] = 'Reputation';
                $steps_list['Config'] = 'Config';

                
                $selected_steps = array();
                if(!empty($gps_user_data['GpsUser']['step_module'])){
                    $selected_steps = explode(',',$gps_user_data['GpsUser']['step_module']);
                }
                //print_r($selected_steps);
                    echo $this->Form->input('Steps', array('multiple' => "multiple",'style' => "height:250px", 'div' => false, 'label' => 'Select Steps','class'=>'validate[required]','options'=>$steps_list,'value'=>$selected_steps));
		?>
	</div>
                
                
                
	</fieldset>
<div style="float:left;width:110px;">
<?php
echo $this->Form->submit(__('Submit', true), array('div' => false));
echo $this->Form->end();
?>
</div>

<div style="float:left;margin-top:5px;height:40px;">
<?php //echo $this->Html->link('Cancel', array('prefix' => 'client', 'client' => true, 'controller' => 'Gps', 'action' => 'index'), array('class' => 'new_button', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;'));?>
</div>
</div>

<?php echo $this->element('client_left_menu'); ?>