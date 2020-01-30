<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
	$("#GpAdminAddForm").validationEngine();
});
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>GPS <small><i class="icon-double-angle-right"></i> Assign Steps to User</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Assign Steps to User</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">    
<?php echo $this->Form->create('GpsUser', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'assign')));?>
    
    <?php
    echo $this->Form->input('user_id',array('type'=>'hidden','value'=>$user_id));
    echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id));
    ?>

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
                    echo $this->Form->input('Steps', array('multiple' => "multiple",'style' => "height:250px", 'div' => false, 'label' => 'Select Steps','class'=>'validate[required] span6','options'=>$steps_list,'value'=>$selected_steps));
		?>
	</div>
<?php
echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
echo $this->Form->end();
?>
     </div></div></div></div>
</div>