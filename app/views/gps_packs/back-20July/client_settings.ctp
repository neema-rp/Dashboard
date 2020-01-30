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
    
<?php echo $this->Form->create('GpsPack', array('url' => array('prefix' => 'client', 'client' => true, 'controller' => 'GpsPacks', 'action' => 'settings')));?>
    
    <?php
    echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id));
    ?>
    
	<fieldset>
 		<legend><?php __('Add GPS'); ?></legend>

        <div class="input text required">
		<label for="GpsPackMonthMonth">Financial Month Start</label>
		<?php
			echo $this->Form->month('financial_month_start', null, array('empty' => 'Select Month', 'class'=>'validate[required]','value'=>@$gps_settings['GpsSetting']['financial_month_start']));
			echo $this->Form->error('month');
		?>
	</div>
	<div class="input text required">
        <label for="GpsPackMonthMonth">Financial Month End</label>
		<?php
			echo $this->Form->month('financial_month_end', null, array('empty' => 'Select Month', 'class'=>'validate[required]','value'=>@$gps_settings['GpsSetting']['financial_month_end']));
			echo $this->Form->error('month');
		?>
	</div>

         <?php $room_name = explode('|',$gps_settings['GpsSetting']['roomtypes']); ?>
        <div class="input">
            <table>
                <tr><td>#</td><td>RoomType</td><td>Number of Rooms</td></tr>
                <tr>
                    <td>1</td>
                    <td><?php echo $this->Form->input('GpsPack.roomtypes.0', array('type' => 'text', 'label'=>false,'value'=>$room_name[0])); ?></td>
                    <td><?php echo $this->Form->input('GpsPack.standard_rooms', array('type' => 'text', 'label'=>false,'value'=>@$gps_settings['GpsSetting']['standard_rooms'])); ?></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><?php echo $this->Form->input('GpsPack.roomtypes.1', array('type' => 'text', 'label'=>false,'value'=>$room_name[1])); ?></td>
                    <td><?php echo $this->Form->input('GpsPack.executive_rooms', array('type' => 'text', 'label'=>false,'value'=>@$gps_settings['GpsSetting']['executive_rooms'])); ?></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><?php echo $this->Form->input('GpsPack.roomtypes.2', array('type' => 'text', 'label'=>false,'value'=>$room_name[2])); ?></td>
                    <td><?php echo $this->Form->input('GpsPack.deluxe_rooms', array('type' => 'text', 'label'=>false,'value'=>@$gps_settings['GpsSetting']['deluxe_rooms'])); ?></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td><?php echo $this->Form->input('GpsPack.roomtypes.3', array('type' => 'text', 'label'=>false,'value'=>$room_name[3])); ?></td>
                    <td><?php echo $this->Form->input('GpsPack.suites_rooms', array('type' => 'text', 'label'=>false,'value'=>@$gps_settings['GpsSetting']['suites_rooms'])); ?></td>
                </tr>
            </table>
	</div>
                

        <div class="input">
		<?php
                $market_seg_ids = array();
                if(!empty($gps_settings['GpsSetting']['market_segments'])){
                    $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
                }
                    echo $this->Form->input('MarketSegment', array('multiple' => "multiple",'style' => "height:200px", 'div' => false, 'label' => 'Select MarketSegment','class'=>'validate[required]','options'=>$marketsegments,'value'=>$market_seg_ids));
		?>
	</div>

        <div class="input">
		<?php
                $countries = array();
                if(!empty($gps_settings['GpsSetting']['countries'])){
                    $countries = explode(',',$gps_settings['GpsSetting']['countries']);
                }
                    echo $this->Form->input('Country', array('multiple' => "multiple",'style' => "height:500px", 'div' => false, 'label' => 'Select Countries','class'=>'validate[required]','options'=>$country_array,'value'=>$countries));
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