<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />

<script src="/js/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="/css/chosen.css" />
<script type="text/javascript" src="/js/ajax-chosen.js"></script>

<script>
$(document).ready(function(){
        $(".chzn-select").chosen();
	$("#GpAdminAddForm").validationEngine();
        
        $("#ajaxSegments").ajaxChosen({
                    type: 'GET',
                    url: '/GpsPacks/get_market_segments',
                    dataType: 'json'
            }, function (data) {
                    var terms = {};
                    $.each(data.segments, function (i, val) {
                            terms[i] = val;
                    });
                    //$(".chzn-results>.result-selected").remove();
                    return terms;
            });
        
});
</script>

<?php
if(empty($gps_settings['GpsSetting']['channels_gds']) || ($gps_settings['GpsSetting']['channels_gds'] == 'null')){
    $gps_settings['GpsSetting']['channels_gds'] = '{"Sabre":"Sabre","Amadeus":"Amadeus","Galileo":"Galileo","Worldspan":"Worldspan"}';
}
if(empty($gps_settings['GpsSetting']['channels_online']) || ($gps_settings['GpsSetting']['channels_online'] == 'null')){
    $gps_settings['GpsSetting']['channels_online'] = '{"Website":"Website","OTA":"OTA"}';
}
if(empty($gps_settings['GpsSetting']['channels_direct']) || ($gps_settings['GpsSetting']['channels_direct'] == 'null')){
    $gps_settings['GpsSetting']['channels_direct'] = '{"Phone":"Phone","Email\/Fax":"Email\/Fax","Walkin":"Walkin"}';
}
if(empty($gps_settings['GpsSetting']['access_steps'])){
    $gps_settings['GpsSetting']['access_steps'] = 'ALL';
}
if(empty($gps_settings['GpsSetting']['roomtypes'])){
    $gps_settings['GpsSetting']['roomtypes'] = 'Standard|Executive|Deluxe|Suites';
}
if(empty($gps_settings['GpsSetting']['summary_mp_label'])){
    $gps_settings['GpsSetting']['summary_mp_label'] = 'Pretoria and Surroundings - Upscale & Upper Mid';
}
?>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> GPS Pack</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Add Settings</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
    
            <?php echo $this->Form->create('GpsPack', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'settings')));?>
            <?php echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id)); ?>
    
        <div class="input text required">
		<label for="GpsPackMonthMonth"><b>Financial Month Start</b></label>
		<?php
			echo $this->Form->month('financial_month_start', null, array('empty' => 'Select Month', 'class'=>'validate[required] span6','value'=>@$gps_settings['GpsSetting']['financial_month_start']));
			echo $this->Form->error('month');
		?>
	</div>
	<div class="input text required">
        <label for="GpsPackMonthMonth"><b>Financial Month End</b></label>
		<?php
			echo $this->Form->month('financial_month_end', null, array('empty' => 'Select Month', 'class'=>'validate[required] span6','value'=>@$gps_settings['GpsSetting']['financial_month_end']));
			echo $this->Form->error('month');
		?>
	</div>

         <?php $room_name = explode('|',$gps_settings['GpsSetting']['roomtypes']); ?>
        <div class="input">
            <table>
                <tr><td>#</td><td><b>RoomType</b></td><td><b>Number of Rooms</b></td></tr>
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
                <tr>
                    <td>5</td>
                    <td><?php echo $this->Form->input('GpsPack.roomtypes.4', array('type' => 'text', 'label'=>false,'value'=>$room_name[4])); ?></td>
                    <td><?php echo $this->Form->input('GpsPack.other_rooms', array('type' => 'text', 'label'=>false,'value'=>@$gps_settings['GpsSetting']['other_rooms'])); ?></td>
                </tr>
            </table>
	</div>
                    
        <div class="input">
		<label for="GpsPacksummary_mp_label"><b>Summary Tab Market Performance Label</b></label>
		<?php echo $this->Form->input('GpsPack.summary_mp_label', array('type' => 'text', 'label'=>false,'value'=>@$gps_settings['GpsSetting']['summary_mp_label'],'class'=>'span6')); ?>
	</div>

        <div class="input">
		<?php
                    echo $this->Form->input('MarketSegment', array('multiple' => "multiple",'style' => "height:200px", 'div' => false, 'label' => '<b>Select MarketSegment</b> (Type to find and add the Market Segments)','class'=>'validate[required] span6','id'=>'ajaxSegments','options'=>$marketsegments,'value'=>$market_seg_ids));
		?>
	</div>
                    
                    
         <div class="input">
            <p><i>*Please add upto 65 countries only.</i></p>
		<?php
                $countries = array();
                if(!empty($gps_settings['GpsSetting']['countries'])){
                    $countries = explode(',',$gps_settings['GpsSetting']['countries']);
                }
                    echo $this->Form->input('Country', array('multiple' => "multiple",'style' => "height:500px", 'div' => false, 'label' => '<b>Select Countries</b>','class'=>'validate[required] span6 chzn-select','options'=>$country_array,'value'=>$countries));
		?>
	</div>

                        
        <div class="input">
		<?php
                //$geo_list = 'Eastern Cape,Free State,Gauteng,Kwa-Zulu Natal,Limpopo,Mpumalanga,Northern Cape,North West,Western Cape,Other';
                //$geoLists = explode(',',$geo_list);
                
                $geoLists['Eastern Cape'] = 'Eastern Cape';
                $geoLists['Free State'] = 'Free State';
                $geoLists['Gauteng'] = 'Gauteng';
                $geoLists['Kwa-Zulu Natal'] = 'Kwa-Zulu Natal';
                $geoLists['Limpopo'] = 'Limpopo';
                $geoLists['Mpumalanga'] = 'Mpumalanga';
                $geoLists['Northern Cape'] = 'Northern Cape';
                $geoLists['North West'] = 'North West';
                $geoLists['Western Cape'] = 'Western Cape';
                $geoLists['Other'] = 'Other';
                
                $geoLists['Antigua'] = 'Antigua';
                $geoLists['Australia'] = 'Australia';
                $geoLists['Austria'] = 'Austria';
                $geoLists['Barbados'] = 'Barbados';
                $geoLists['Belgium'] = 'Belgium';
                $geoLists['Bermuda'] = 'Bermuda';
                $geoLists['Brazil'] = 'Brazil';
                $geoLists['Canada'] = 'Canada';
                $geoLists['China'] = 'China';
                $geoLists['Denmark'] = 'Denmark';
                $geoLists['Finland'] = 'Finland';
                $geoLists['France'] = 'France';
                $geoLists['Germany'] = 'Germany';
                $geoLists['Greece'] = 'Greece';
                $geoLists['Grt Britain'] = 'Grt Britain';
                $geoLists['Guernsey'] = 'Guernsey';
                $geoLists['Hungary'] = 'Hungary';
                $geoLists['Hong Kong'] = 'Hong Kong';
                $geoLists['Ireland'] = 'Ireland';
                $geoLists['Italy'] = 'Italy';
                $geoLists['Japan'] = 'Japan';
                $geoLists['Jamaica'] = 'Jamaica';
                $geoLists['Jersey'] = 'Jersey';
                $geoLists['Monaco'] = 'Monaco';
                $geoLists['Netherlands'] = 'Netherlands';
                $geoLists['Poland'] = 'Poland';
                $geoLists['Portugal'] = 'Portugal';
                $geoLists['Russia'] = 'Russia';
                $geoLists['Saint Martin'] = 'Saint Martin';
                $geoLists['Spain'] = 'Spain';
                $geoLists['Sweden'] = 'Sweden';
                $geoLists['South Africa'] = 'South Africa';
                $geoLists['Switzerland'] = 'Switzerland';
                $geoLists['Trinidad'] = 'Trinidad';
                $geoLists['USA'] = 'USA';
                $geoLists['Virgin Islds'] = 'Virgin Islds';
                $geoLists['West Indies'] = 'West Indies';
                $geoLists['Yugoslavia'] = 'Yugoslavia';
                
                $geo_list_ids = array();
                if(!empty($gps_settings['GpsSetting']['geo_list'])){
                    $geo_list_ids = explode(',',$gps_settings['GpsSetting']['geo_list']);
                }
                    echo $this->Form->input('province_list', array('multiple' => "multiple",'style' => "height:200px", 'div' => false, 'label' => '<b>Select Prov Year Provinces</b>','class'=>'validate[required] span6 chzn-select','options'=>$geoLists,'value'=>$geo_list_ids));
		?>
	</div>
        <div class="input">
            <?php 
                $gps_steps = array();
                $gps_steps['1'] = 'GM Summary';
                $gps_steps['2'] = 'Summary';
                $gps_steps['3'] = 'Market';
                $gps_steps['4'] = 'Competition';
                $gps_steps['5-8'] = 'Activity';
                $gps_steps['9'] = 'Top Producers';
                $gps_steps['10-13'] = 'Market Segmentation/BOB';
                $gps_steps['14-15'] = 'Channels';
                $gps_steps['16'] = 'Geo Year';
                $gps_steps['17'] = 'Prov Year';
                $gps_steps['18-19'] = 'RoomTypes';
                $gps_steps['20'] = 'Future Activity';
                $gps_steps['21'] = 'Reputation';
                $gps_steps['22'] = 'Config';
                
                $gps_steps_ids = array();
                
                if(!empty($gps_settings['GpsSetting']['access_steps'])){
                    if($gps_settings['GpsSetting']['access_steps'] == 'ALL'){
                        $gps_steps_ids = array_keys($gps_steps);
                    }else{
                        $gps_steps_ids = explode(',',$gps_settings['GpsSetting']['access_steps']);
                    }
                }
                echo $this->Form->input('gps_steps', array('multiple' => "multiple",'style' => "height:200px", 'div' => false, 'label' => '<b>Select Steps</b>','class'=>'validate[required] span6 chzn-select','options'=>$gps_steps,'value'=>$gps_steps_ids));
            ?>
        </div>

                    
        <div class="input">
             <label> <b>Channels GDS</b></label>
            <?php
                $channel_gds_Array = json_decode($gps_settings['GpsSetting']['channels_gds']);
                foreach($channel_gds_Array as $gds_array_key => $gds_array_val){
                    echo $this->Form->input('channels_gds_ar.'.$gds_array_key, array('div' => false, 'label' =>false,'class'=>'validate[required]','value'=>$gds_array_val));
                    echo '&nbsp;&nbsp;';
                }
            ?>
        </div>

         <div class="input">
             <label> <b>Channels Online</b></label>
            <?php 
                $channel_online_Array = json_decode($gps_settings['GpsSetting']['channels_online']);
                foreach($channel_online_Array as $gds_array_key => $gds_array_val){
                    echo $this->Form->input('channels_online_ar.'.$gds_array_key, array('div' => false, 'label' =>false,'class'=>'validate[required]','value'=>$gds_array_val));
                    echo '&nbsp;&nbsp;';
                }
            ?>
        </div>
                    
        <div class="input">
             <label> <b>Channels Direct</b></label>
            <?php 
                $channel_direct_Array = json_decode($gps_settings['GpsSetting']['channels_direct']);
                foreach($channel_direct_Array as $gds_array_key => $gds_array_val){
                    echo $this->Form->input('channels_direct_ar.'.$gds_array_key, array('div' => false, 'label' =>false,'class'=>'validate[required]','value'=>$gds_array_val));
                    echo '&nbsp;&nbsp;';
                }
            ?>
        </div>
<?php
echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
echo $this->Form->end();
?>
</div></div></div></div>
</div>


<style>
div.input{ margin-top:10px; }
</style>