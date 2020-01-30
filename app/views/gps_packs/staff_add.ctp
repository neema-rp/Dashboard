<?php ?>
<style type="text/css">
table{ color: #6B6F6F;width:100%; } 
input, textarea { font-size:100%; border:1px solid #ccc; }
input[type=text],textarea{ width:80%; }
input[readonly=readonly]{ background: #BDBDBD; border:1px solid #BDBDBD; }
table tr td{ background:none; font-size:12px; padding:3px; }
table tr:nth-child(2n) td{ background:none; }
table tr:nth-child(2n) td.content_head{ background:#BDBDBD; }
.bold{ font-weight: bold; }
</style>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Add <small><i class="icon-double-angle-right"></i> GPS Pack</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Add GPS Pack</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

<?php

$room_name = explode('|',$gps_settings['GpsSetting']['roomtypes']);

$gps_pack_id = $GpsPack['GpsPack']['id'];
$client_id = $GpsPack['GpsPack']['client_id'];
$gps_month = $GpsPack['GpsPack']['month'];
$gps_month = sprintf("%02d", $gps_month);
$gps_year = $GpsPack['GpsPack']['year'];

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $gps_month, $gps_year);

$financial_month_start = (!empty($gps_settings['GpsSetting']['financial_month_start'])) ? $gps_settings['GpsSetting']['financial_month_start'] : '1';

?>
<?php echo $this->Form->create('GpsPack', array('url' => array('prefix' => 'staff', 'staff' => true, 'controller' => 'GpsPacks', 'action' => 'add')));?>
    
    <?php
    echo $this->Form->input('step',array('type'=>'hidden','value'=>$step));
    echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id,'id'=>'client_id'));
    echo $this->Form->input('id',array('type'=>'hidden','value'=>$gps_pack_id)); 
    
    
    $stepOldData = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/'.$step); 
    if(!empty($stepOldData)){
        header('Location: /staff/GpsPacks/edit/'.$step.'/'.$gps_pack_id);
    }
    
    ?>    
	        <?php if($step=='1'){ ?>
                <fieldset>
 		<legend><?php __('GM Summary'); ?></legend>
                   <table style="width:100%">
                        <tr><td>How has your hotel performed against the competitor set/market set in STR?</td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_1_1','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>Where is your opportunity to improve performance in the month ahead?</td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_1_2','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>What is your synopsis of your market segmentation performance?</td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_1_3','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>What channel performance objectives do you have for the month ahead?</td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_1_4','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>Which three Sales Accounts are presenting the largest opportunity for you?</td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_1_5','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    </table>
                </fieldset>
                
                <?php }elseif($step=='2'){ 
                    $step2Data = $this->requestAction('/GpsPacks/get_last_gps_data/'.$gps_pack_id.'/2'); 
                ?>
                <fieldset>
 		<legend><?php __('Summary'); ?></legend>
                        <table>
                            <tr>
                                <td>&nbsp;</td>
                               <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                            </tr>

                            <tr><td colspan="14">&nbsp;</td></tr>
                            <tr>
                                <td class="bold">Number of Guests</td>
                                <?php $cnt='1';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>
                                    <?php
                                    if(!empty($step2Data)){
                                       foreach($step2Data as $step2_key=>$step2_val){
                                           if(($step2_val['GpsData']['text'] == 'Summary') && ($step2_val['GpsData']['question'] == $cnt)){

                                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Summary','name'=>'data[GpsPack][text][]'));
                                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Number of Guests','name'=>'data[GpsPack][sub_text][]'));
                                    
                                            echo $this->Form->input('value[]',array('type'=>'text','id'=>'guest_'.$month,'label'=>false,'value'=>$step2_val['GpsData']['value'], 'name'=>'data[GpsPack][value][]'));
                                               unset($step2Data[$step2_key]);
                                                break;
                                           }
                                       }
                                    }else{
                                        
                                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Summary','name'=>'data[GpsPack][text][]'));
                                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Number of Guests','name'=>'data[GpsPack][sub_text][]'));
                                        echo $this->Form->input('value[]',array('type'=>'text','id'=>'guest_'.$month,'label'=>false,'name'=>'data[GpsPack][value][]'));
                                    }
                                    ?>
                                    </td>
                                <?php $cnt++; } ?>
                            </tr>
                            
                            <tr><td colspan="14">&nbsp;</td></tr>
                            <tr><td colspan="14" class="bold">Market Performance</td></tr>
                            <tr><td colspan="14" class="bold"><?php echo $market_performance; ?></td></tr>

                            <?php
                            $market_perf_arr[] = 'MPI';
                            $market_perf_arr[] = 'ARI';
                            $market_perf_arr[] = 'RGI';
                            
                            foreach($market_perf_arr as $mark_key => $market_per){ ?>
                                 <tr>
                                    <td class="bold"><?php echo $market_per; ?></td>
                                    <?php $cnt = '1';
                                    for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>
                                        <?php
                                        if(!empty($step2Data)){
                                           foreach($step2Data as $step2_key=>$step2_val){
                                               if(($step2_val['GpsData']['text'] == 'Market Performance') && ($step2_val['GpsData']['sub_text'] == $market_per) && ($step2_val['GpsData']['question'] == ($cnt + ($mark_key * '12')))){
                                                   
                                                  echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Market Performance','name'=>'data[GpsPack][text][]'));
                                                  echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$market_per,'name'=>'data[GpsPack][sub_text][]'));

                                                   echo $this->Form->input('value[]',array('type'=>'text','id'=>'guest_'.$month,'value'=>$step2_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                                   unset($step2Data[$step2_key]);
                                                    break;
                                               }
                                           }
                                        }else{
                                           echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Market Performance','name'=>'data[GpsPack][text][]'));
                                           echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$market_per,'name'=>'data[GpsPack][sub_text][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','id'=>'guest_'.$month,'label'=>false,'name'=>'data[GpsPack][value][]'));
                                        } ?>
                                        </td>
                                    <?php $cnt++; } ?>
                                </tr>
                            <?php } ?>
                        </table>
                </fieldset>
                <?php }elseif($step=='3'){ ?>
                    <table style="width:100%">
                        <tr><td>Market Conditions - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_3_1','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>Market Conditions - <?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    </table>
                <?php }elseif($step=='4'){ ?>
                    <table style="width:100%">
                        <tr><td>Competitor Activity - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_3_1','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>Competitor Activity - <?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    </table>
                
                <?php }elseif($step=='5' || $step=='6' || $step=='7' || $step=='8'){ ?>
                    <h2><?php $activity_month = $gps_month + ($step - '5');
                        echo date("F", mktime(0, 0, 0, $activity_month, 1));
                    ?></h2>
                    
                   <?php $stepActData = $this->requestAction('/GpsPacks/get_activity_data/'.$gps_pack_id.'/'.$activity_month); ?>
                    
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>                       
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                                <?php $city_event_arr = array();
                                unset($city_event_arr);
                                $city_event_arr[] = 'Event Name'.$count;
                                $city_event_arr[] = 'Description'.$count;
                                $city_event_arr[] = 'Impact'.$count;
                                $city_event_arr[] = 'Revenue Opportunity / Target'.$count;
                                $city_event_arr[] = 'Market / Source'.$count;
                                foreach($city_event_arr as $city_evt){
                                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$city_evt,'name'=>'data[GpsPack][sub_text][]')); 
                                        if(!empty($stepActData)){
                                            foreach($stepActData as $step5_key=>$step5_val){
                                                if(($step5_val['GpsData']['text'] == 'City Events')){
                                                   if(($step5_val['GpsData']['sub_text'] == $city_evt)){ ?>
                                                      <td><?php  echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                                                      <?php unset($stepActData[$step5_key]);
                                                       break;
                                                   }
                                               }
                                            }
                                        }else{ ?>
                                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                                        <?php } ?>
                               <?php }
                                ?>
                        </tr>
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                           <tr> 
                            <?php $hotel_event_arr = array();
                                unset($hotel_event_arr);
                                $hotel_event_arr[] = 'Event Name'.$count;
                                $hotel_event_arr[] = 'Description'.$count;
                                $hotel_event_arr[] = 'Impact'.$count;
                                $hotel_event_arr[] = 'Revenue Opportunity / Target'.$count;
                                $hotel_event_arr[] = 'Market / Source'.$count;
                                foreach($hotel_event_arr as $hotel_evt){
                                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$hotel_evt,'name'=>'data[GpsPack][sub_text][]'));
                                       if(!empty($stepActData)){
                                           foreach($stepActData as $step5_key=>$step5_val){
                                                if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                                   if(($step5_val['GpsData']['sub_text'] == $hotel_evt)){ ?>
                                                      <td><?php  echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                                                      <?php unset($stepActData[$step5_key]);
                                                       break;
                                                   }
                                               }
                                           }
                                        }else{ ?>
                                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                                        <?php } ?>
                               <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td>Planned Activity</td><td>Fcst RN</td><td>Fcst ADR</td><td>Revenue</td><td>Actual RN</td><td>Actual ADR</td><td>Actual Revenue</td><td>Var. Revenue</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                         <tr>
                                <?php $hotel_event_arr = array();
                                unset($hotel_event_arr);
                                $hotel_event_arr[] = 'Planned Activity'.$count;
                                $hotel_event_arr[] = 'Fcst RN'.$count;
                                $hotel_event_arr[] = 'Fcst ADR'.$count;
                                $hotel_event_arr[] = 'Revenue'.$count;
                                $hotel_event_arr[] = 'Actual RN'.$count;
                                $hotel_event_arr[] = 'Actual ADR'.$count;
                                $hotel_event_arr[] = 'Actual Revenue'.$count;
                                $hotel_event_arr[] = 'Var. Revenue'.$count;
                                foreach($hotel_event_arr as $hotel_evt){
                                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$hotel_evt,'name'=>'data[GpsPack][sub_text][]'));
                             
                                        if(!empty($stepActData)){
                                            foreach($stepActData as $step5_key=>$step5_val){
                                                if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                                   if(($step5_val['GpsData']['sub_text'] == $hotel_evt)){ ?>
                                                      <td><?php  echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                                                      <?php unset($stepActData[$step5_key]);
                                                       break;
                                                   }
                                               }
                                           }
                                        }else{ ?>
                                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                                        <?php } ?>
                               <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                                <?php $comp_event_arr = array();
                                    unset($hotel_event_arr);
                                    $comp_event_arr[] = 'Event Name'.$count;
                                    $comp_event_arr[] = 'Description'.$count;
                                    $comp_event_arr[] = 'Impact'.$count;
                                    $comp_event_arr[] = 'Revenue Opportunity / Target'.$count;
                                    $comp_event_arr[] = 'Market / Source'.$count;
                                    foreach($comp_event_arr as $comp_evt){
                                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$comp_evt,'name'=>'data[GpsPack][sub_text][]')); 
                                            if(!empty($stepActData)){
                                              foreach($stepActData as $step5_key=>$step5_val){
                                                if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                                   if(($step5_val['GpsData']['sub_text'] == $comp_evt)){ ?>
                                                      <td><?php  echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                                                      <?php unset($stepActData[$step5_key]);
                                                       break;
                                                   }
                                               }
                                              }
                                        }else{ ?>
                                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                                        <?php } ?>
                                   <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                    
                    <table>
                        <tr><td>Comments</td></tr>
                        <?php echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Comments','name'=>'data[GpsPack][text][]')); ?>
                        <tr><td>
                        <?php 
                        if(!empty($stepActData)){
                            foreach($stepActData as $step5_key=>$step5_val){
                               if(($step5_val['GpsData']['text'] == 'Comments')){
                                       echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($stepActData[$step5_key]);
                                        break;
                               }
                           }
                        }else{
                            echo $this->Form->input('value[]',array('type'=>'textarea','label'=>false,'name'=>'data[GpsPack][value][]'));   
                        }
                        ?>
                        </td></tr>
                    </table>
                    
                <?php }elseif($step=='9'){ ?>
                    
                    <h2>Top Producers</h2>
                    <table>
                        <tr><td colspan="6" class="bold">
                                <?php if($client_id == '100'){
                                    echo 'Tour Operator';
                                }else{
                                    echo 'Corporate';
                                } ?>
                            </td></tr>
                        <tr><td>&nbsp;</td>
                            <td>Name</td>
                            <td>Room Nights</td>
                            <td>ADR</td>
<!--                            <td>Av. Spend</td>-->
                            <td>Total Revenue</td>
                        </tr>
                        <?php
                        $search  = array(' ', '.');
                        $replace = array('', '');
                        for($count=1;$count <= 10; $count++){ ?>
                        <tr>
                            <td><?php echo $count; ?></td>

                            <?php $top_pro_arr = array();
                            unset($top_pro_arr);
                            $top_pro_arr[] = 'Name';
                            $top_pro_arr[] = 'Room Nights';
                            $top_pro_arr[] = 'ADR';
                            //$top_pro_arr[] = 'Av. Spend';
                            $top_pro_arr[] = 'Total Revenue';
                            foreach($top_pro_arr as $top_pro){
                                
                                $field_id = str_replace($search, $replace, $top_pro);
                                
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Corporate','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$top_pro,'name'=>'data[GpsPack][sub_text][]','class'=>$field_id,'data-count'=>$count,'data-top_type'=>'Corporate','id'=>$field_id.$count.'Corporate'));
                                ?>
                                <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td colspan="6" class="bold">Travel Agents</td></tr>
                        <tr><td>&nbsp;</td>
                            <td>Name</td>
                            <td>Room Nights</td>
                            <td>ADR</td>
<!--                            <td>Av. Spend</td>-->
                            <td>Total Revenue</td>
                        </tr>
                        
                        <?php 
                        $search  = array(' ', '.');
                       $replace = array('', '');
                        for($count=1;$count <= 10; $count++){ ?>
                        <tr>
                            <td><?php echo $count; ?></td>

                            <?php $travel_agent_arr = array();
                            unset($travel_agent_arr);
                            $travel_agent_arr[] = 'Name';
                            $travel_agent_arr[] = 'Room Nights';
                            $travel_agent_arr[] = 'ADR';
                            //$travel_agent_arr[] = 'Av. Spend';
                            $travel_agent_arr[] = 'Total Revenue';
                            foreach($travel_agent_arr as $travel_agent){ 
                                
                                $field_id = str_replace($search, $replace, $travel_agent);
                                
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Travel Agents','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$travel_agent,'name'=>'data[GpsPack][sub_text][]'));
                                ?>
                                <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]','class'=>$field_id,'data-count'=>$count,'data-top_type'=>'Travel','id'=>$field_id.$count.'Travel')); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                    
                <?php }elseif($step=='10'){ ?>
                    <h2>Market Segmentation - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></h2>
                    <!-- Autofill-->
                   <?php header('Location: /staff/GpsPacks/add/14/'.$gps_pack_id); ?>
                    
                <?php }elseif($step=='11'){ ?>
                    <h2>BOB - <?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></h2>
                    <!-- Autofill-->
                    <?php header('Location: /staff/GpsPacks/add/14/'.$gps_pack_id); ?>
                <?php }elseif($step=='12'){ ?>
                    <h2>BOB - <?php echo date("F", mktime(0, 0, 0, ($gps_month+2), 1));  ?></h2>
                    <!-- Autofill-->
                    <?php header('Location: /staff/GpsPacks/add/14/'.$gps_pack_id); ?>
                <?php }elseif($step=='13'){ ?>
                    <h2>BOB - <?php echo date("F", mktime(0, 0, 0, ($gps_month+3), 1));  ?></h2>
                    <!-- Autofill-->
                    <?php header('Location: /staff/GpsPacks/add/14/'.$gps_pack_id); ?>
                <?php }elseif($step=='14'){ ?>
                    <h2>Channels - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></h2>
                    <table>
                        <tr>
                            <td colspan="2" class="bold">GDS</td>
                            <td>Month</td>
                            <td>Budget</td>
                            <td>Last Year</td>
                            <td>YTD Budget</td>
                        </tr>
                        
                        <?php
                        $gds_arr = json_decode($gps_settings['GpsSetting']['channels_gds']);
//                        $gds_arr['1'] = 'Sabre';
//                        $gds_arr['2'] = 'Amadeus';
//                        $gds_arr['3'] = 'Galileo';
//                        $gds_arr['4'] = 'Worldspan';
                        ?>
                        
                        <?php foreach($gds_arr as $gd_key=>$gd_val){ 

                             for($loop=1;$loop <= 8; $loop++){
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_key,'name'=>'data[GpsPack][sub_text][]'));
                             }
                        ?>
                        <tr>
                            <td class="bold"><?php echo $gd_val; ?></td>
                            <td>RN</td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <?php } ?>
                        
                        <tr><td colspan="6">&nbsp;</td></tr>                        

                        <tr><td colspan="6"><b>Online</b></td></tr>
                        <?php 
                        $online_arr = json_decode($gps_settings['GpsSetting']['channels_online']);
//                        $online_arr['1'] = 'Website';
//                        $online_arr['2'] = 'OTA';
                        ?>
                        
                        <?php foreach($online_arr as $online_arr_key=>$online_arr_val){ 

                        for($loop=1;$loop <= 8; $loop++){
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_key,'name'=>'data[GpsPack][sub_text][]'));
                         }
                             
                        ?>
                        <tr>
                            <td class="bold"><?php echo $online_arr_val; ?></td>
                            <td>RN</td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <?php } ?>
                        
                        <tr><td colspan="6">&nbsp;</td></tr>                        

                        <tr><td colspan="6"><b>Direct</b></td></tr>
                        <?php 
                        $direct_arr = json_decode($gps_settings['GpsSetting']['channels_direct']);
//                        $direct_arr['1'] = 'Phone';
//                        $direct_arr['2'] = 'Email/Fax';
//                        $direct_arr['3'] = 'Walkin';
                        ?>
                        
                        <?php foreach($direct_arr as $direct_arr_key=>$direct_arr_val){ 

                        for($loop=1;$loop <= 8; $loop++){
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_key,'name'=>'data[GpsPack][sub_text][]'));
                         }
                    
                        ?>
                        <tr>
                            <td class="bold"><?php echo $direct_arr_val; ?></td>
                            <td>RN</td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <?php } ?>
                        
                       <?php
                        for($loop=1;$loop <= 8; $loop++){
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'CRO','name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                         }
                        
                        ?>

                        <tr>
                            <?php if($client_id == '100'){ ?>
                               <td>CRS</td>
                           <?php }else{ ?>
                               <td>CRO</td>
                           <?php } ?>
                                   
                            <td>RN</td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                    </table>
                    
                <?php }elseif($step=='15'){ 
                    $step15Data = $this->requestAction('/GpsPacks/get_last_gps_data/'.$gps_pack_id.'/15'); 
                    
                    ?>
                <fieldset>
 		<legend><?php __('Channels Year'); ?></legend>
                    <table>
                        <tr>
                            <td colspan="2" class="bold">GDS</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                            <?php } ?>
                        </tr>
                        
                        <?php 
//                        $gds_arr['1'] = 'Sabre';
//                        $gds_arr['2'] = 'Amadeus';
//                        $gds_arr['3'] = 'Galileo';
//                        $gds_arr['4'] = 'Worldspan';
                        
                        $gds_arr = json_decode($gps_settings['GpsSetting']['channels_gds']);
                        ?>
                        
                        <?php $loop_count = '0';
                        foreach($gds_arr as $gd_key=>$gd_val){
                        ?>

                        <tr>
                            <td class="bold"><?php echo $gd_val; ?></td>
                            <td class="bold">RN</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            ?>
                            <td>
                                <?php
                                if(!empty($step15Data)){
                                    foreach($step15Data as $step15_key=>$step15_val){
                                       if(($step15_val['GpsData']['text'] == 'GDS') && ($step15_val['GpsData']['sub_text'] == $gd_key) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                           echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                           echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_key,'name'=>'data[GpsPack][sub_text][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step15Data[$step15_key]);
                                            break;
                                       }
                                   }
                                }else{
                                 echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                 echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_key,'name'=>'data[GpsPack][sub_text][]'));
                                 echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); 
                                }
                                ?>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                            <td><?php
                                if(!empty($step15Data)){
                                    
                                    foreach($step15Data as $step15_key=>$step15_val){
                                           if(($step15_val['GpsData']['text'] == 'GDS') && ($step15_val['GpsData']['sub_text'] == $gd_key) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                               echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                               echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_key,'name'=>'data[GpsPack][sub_text][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step15Data[$step15_key]);
                                                break;
                                           }
                                       } 
                               }else{
                                   echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                   echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_key,'name'=>'data[GpsPack][sub_text][]'));
                                   echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                                }
                             ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php $loop_count++; } ?>
                        
                        <tr><td colspan="14">&nbsp;</td></tr>                        

                        <tr><td colspan="14"><b>Online</b></td></tr>
                        <?php 
//                        $online_arr['1'] = 'Website';
//                        $online_arr['2'] = 'OTA';
                        $online_arr = json_decode($gps_settings['GpsSetting']['channels_online']);
                        ?>
                        
                        <?php $loop_count ='0';
                        foreach($online_arr as $online_arr_key=>$online_arr_val){
                            ?>
                        <tr>
                            <td class="bold"><?php echo $online_arr_val; ?></td>
                            <td class="bold">RN</td>
                            <?php $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                            if(!empty($step15Data)){
                                foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Online') && ($step15_val['GpsData']['sub_text'] == $online_arr_key) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_key,'name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                        break;
                                   }
                               }
                            }else{
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_key,'name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                            if(!empty($step15Data)){
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Online') && ($step15_val['GpsData']['sub_text'] == $online_arr_key) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_key,'name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                        break;
                                   }
                               } 
                            }else{
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_key,'name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                             ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php $loop_count++; } ?>
                        
                        <tr><td colspan="14">&nbsp;</td></tr>                        

                        <tr><td colspan="14"><b>Direct</b></td></tr>
                        <?php 
                        $direct_arr = json_decode($gps_settings['GpsSetting']['channels_direct']);
//                        $direct_arr['1'] = 'Phone';
//                        $direct_arr['2'] = 'Email/Fax';
//                        $direct_arr['3'] = 'Walkin';
                        ?>
                        
                        <?php $loop_count = '0';
                        foreach($direct_arr as $direct_arr_key=>$direct_arr_val){ 
                            ?>
                        <tr>
                            <td class="bold"><?php echo $direct_arr_val; ?></td>
                            <td class="bold">RN</td>

                            <?php $cnt ='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                ?>
                            <td><?php
                            if(!empty($step15Data)){
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Direct') && ($step15_val['GpsData']['sub_text'] == $direct_arr_key) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_key,'name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                        break;
                                   }
                               } 
                            }else{
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_key,'name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                            if(!empty($step15Data)){
                                foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Direct') && ($step15_val['GpsData']['sub_text'] == $direct_arr_key) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_key,'name'=>'data[GpsPack][sub_text][]'));    
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                        break;
                                   }
                               }
                            }else{
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_key,'name'=>'data[GpsPack][sub_text][]'));
                                echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                            ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php $loop_count++; }
                        ?>
                        
                        <tr>
                            <?php if($client_id == '100'){ ?>
                                   <td class="bold">CRS</td>
                               <?php }else{ ?>
                                   <td class="bold">CRO</td>
                               <?php } ?>
                            <td class="bold">RN</td>
                            <?php $cnt ='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                            if(!empty($step15Data)){
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'CRO')  && ($step15_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'CRO','name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                        break;
                                   }
                               } 
                            }else{
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'CRO','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                            if(!empty($step15Data)){
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'CRO')  && ($step15_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'CRO','name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));    
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                        break;
                                   }
                               } 
                            }else{
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'CRO','name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                    </table>
                </fieldset>
                <?php }elseif($step=='16'){ 
                    $step16Data = $this->requestAction('/GpsPacks/get_last_gps_data/'.$gps_pack_id.'/16'); 
                    ?>
                <fieldset>
 		<legend><?php __('Geo Year'); ?></legend>
                    <table>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                        </tr>
                        
                        <?php $country_arr = array();
                        $country_arr = explode(',',$gps_settings['GpsSetting']['countries']);
                        ?>
                        
                        <?php foreach($country_arr as $country_key=>$country_val){
                            ?>
                        <tr>
                            <td class="bold"><?php echo $country_val; ?></td>
                            <td class="bold">RN</td>
                            <?php $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            ?>
                            <td><?php
                            if(!empty($step16Data)){
                                $match = '0';
                               foreach($step16Data as $step16_key=>$step16_val){
                                   if(($step16_val['GpsData']['text'] == $country_val)  && ($step16_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step16_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step16Data[$step16_key]);
                                       $match = '1';
                                        break;
                                   }
                               }
                               if($match == '0'){
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                            }else{
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                            if(!empty($step16Data)){
                                $match = '0';
                               foreach($step16Data as $step16_key=>$step16_val){
                                   if(($step16_val['GpsData']['text'] == $country_val)  && ($step16_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));    
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step16_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step16Data[$step16_key]);
                                       $match = '1';
                                        break;
                                   }
                               }
                               if($match == '0'){
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                            }else{
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php } ?>
                </table>
                
                </fieldset>
                <?php }elseif($step=='17'){ 
                    $step17Data = $this->requestAction('/GpsPacks/get_last_gps_data/'.$gps_pack_id.'/17'); 
                    ?>
                <fieldset>
 		<legend><?php __('Prov Year'); ?></legend>
                
                    <table>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                        </tr>
                        
                        <?php 
                        $country_arr = array();
                        $country_arr = explode(',',$gps_settings['GpsSetting']['geo_list']);
                        ?>
                        
                        <?php foreach($country_arr as $country_key=>$country_val){
                            ?>
                        <tr>
                            <td class="bold"><?php echo $country_val; ?></td>
                            <td class="bold">RN</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                            if(!empty($step17Data)){
                                $match = '0';
                               foreach($step17Data as $step17_key=>$step17_val){
                                   if(($step17_val['GpsData']['text'] == $country_val)  && ($step17_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step17_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step17Data[$step17_key]);
                                       $match = '1';
                                        break;
                                   }
                               } 
                               if($match == '0'){
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                            }else{
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                            if(!empty($step17Data)){
                                $match = '0';
                               foreach($step17Data as $step17_key=>$step17_val){
                                   if(($step17_val['GpsData']['text'] == $country_val)  && ($step17_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));    
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step17_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step17Data[$step17_key]);
                                       $match = '1';
                                        break;
                                   }
                               } 
                               if($match == '0'){
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                            }else{
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php } ?>
                </table>
                </fieldset>
                <?php }elseif($step=='18'){ ?>
                    <h2>RoomTypes</h2>
                    <?php 
                    $roomType_arr[$room_name[0]] = 'Standard';
                    $roomType_arr[$room_name[1]] = 'Executive';
                    $roomType_arr[$room_name[2]] = 'Deluxe';
                    $roomType_arr[$room_name[3]] = 'Suite';
                    $roomType_arr[$room_name[4]] = 'Other';
                    ?>
                    <table>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>Actual</td><td>Prior Year</td><td>BAR A</td></tr>
                        <?php foreach($roomType_arr as $roomType_key=>$roomType_arr_val){ 
                            
                            //for($loop=1;$loop <= 5; $loop++){
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$roomType_arr_val,'name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'RN','name'=>'data[GpsPack][sub_text][]'));
                                 echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$roomType_arr_val,'name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'RN','name'=>'data[GpsPack][sub_text][]'));
                                 echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$roomType_arr_val,'name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'RN','name'=>'data[GpsPack][sub_text][]'));
                                
                                 echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$roomType_arr_val,'name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'ADR','name'=>'data[GpsPack][sub_text][]'));
                                 echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$roomType_arr_val,'name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'ADR','name'=>'data[GpsPack][sub_text][]'));
                             //}
                        ?>
                        <tr>
                            <td class="bold"><?php echo $roomType_key; ?></td>
                            <td>RN</td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php } ?>
                </table>
                    
                <?php }elseif($step=='19'){ ?>
                    <h2>Room Type Year</h2>
                    <!-- Autofill-->
                    <?php header('Location: /staff/GpsPacks/add/20/'.$gps_pack_id); ?>
                <?php }elseif($step=='20'){ ?>
                    <table style="width:100%">
                        <tr><td>Expected Market Conditions</td></tr>
                        <tr><td><?php echo $this->Form->input('step[3][1]',array('type'=>'textarea','id'=>'step_3_1','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>Planned Activity, by Segment</td></tr>
                        <tr><td><?php echo $this->Form->input('step[3][2]',array('type'=>'textarea','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    </table>
                <?php }elseif($step=='21'){
                    $step21Data = $this->requestAction('/GpsPacks/get_last_gps_data/'.$gps_pack_id.'/21');
                    ?>
                <fieldset>
 		<legend><?php __('Reputation'); ?></legend>
                    <table>
                        <tr>
                            <td>&nbsp;</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                        </tr>
                        
                        <?php 
                        if($client_id != '100'){
                        $reputation_arr['Agoda'][] = 'Ranking';
                        $reputation_arr['Agoda'][] = 'Room Nights';
                        $reputation_arr['Agoda'][] = 'Rev (Exc.)';
                        $reputation_arr['Agoda'][] = 'Rev (Inc.)';
                        $reputation_arr['Agoda'][] = 'Commission %';
                        $reputation_arr['Agoda'][] = 'Commision paid';
                        $reputation_arr['Agoda'][] = 'Reviews';
                        $reputation_arr['Agoda'][] = 'Review Score';
                        }
                        
                        $reputation_arr['Booking.com'][] = 'Ranking';
                        $reputation_arr['Booking.com'][] = 'Room Nights';
                        $reputation_arr['Booking.com'][] = 'Rev (Exc.)';
                        $reputation_arr['Booking.com'][] = 'Rev (Inc.)';
                        $reputation_arr['Booking.com'][] = 'Commission %';
                        $reputation_arr['Booking.com'][] = 'Commision paid';
                        $reputation_arr['Booking.com'][] = 'Reviews';
                        $reputation_arr['Booking.com'][] = 'Review Score';
                        
                        if($client_id == '100'){
                            $reputation_arr['Expedia Ranking'][] = 'Ranking';
                        }
                        $reputation_arr['Expedia Ranking'][] = 'Room Nights';
                        $reputation_arr['Expedia Ranking'][] = 'Rev (Exc.)';
                        $reputation_arr['Expedia Ranking'][] = 'Rev (Inc.)';
                        $reputation_arr['Expedia Ranking'][] = 'Commission %';
                        $reputation_arr['Expedia Ranking'][] = 'Commision paid';
                        
                        if($client_id != '100'){
                        $reputation_arr['Safarinow.com'][] = 'Room Nights';
                        $reputation_arr['Safarinow.com'][] = 'Rev (Exc.)';
                        $reputation_arr['Safarinow.com'][] = 'Rev (Inc.)';
                        $reputation_arr['Safarinow.com'][] = 'Commission %';
                        $reputation_arr['Safarinow.com'][] = 'Commision paid';
                        }
                        
                        $reputation_arr['Tripadvisor Ranking'][] = 'Ranking';
                        $reputation_arr['Tripadvisor Ranking'][] = 'Reviews';
                        
                        $reputation_arr['Facebook'][] = 'Likes';
                        
                        $reputation_arr['Twitter'][] = 'Followers';
                        
                        if($client_id == '100'){
                            $reputation_arr['Instagram'][] = 'Followers';
                        }
                        
                        $reputation_arr['Search Engine Optimisation'][] = 'Visits';
                        $reputation_arr['Search Engine Optimisation'][] = 'Bounce Rate';
                        $reputation_arr['Search Engine Optimisation'][] = 'Page Views';
                        $reputation_arr['Search Engine Optimisation'][] = 'Visit Duration';
                        $reputation_arr['Search Engine Optimisation'][] = 'New visitors (30 days)';
                        
                        
                        if($client_id == '67' || $client_id == '68' || $client_id == '69' || $client_id == '70' || $client_id == '80' || $client_id == '81'){
                            $reputation_arr['Travel Ground'][] = 'Room Nights';
                            $reputation_arr['Travel Ground'][] = 'Rev (Exc.)';
                            $reputation_arr['Travel Ground'][] = 'Rev (Inc.)';
                            $reputation_arr['Travel Ground'][] = 'Commission %';
                            $reputation_arr['Travel Ground'][] = 'Commision paid';
                        }
                        
                        ?>
                        
                        <?php $prev_key = '';
                        //echo '<pre>'; print_r($reputation_arr); echo '</pre>';
                        $reputation_count = '0';
                        foreach($reputation_arr as $reputation_key=>$reputation_val){
                            if($prev_key != $reputation_key){ ?>
                            <tr><td colspan="12" >&nbsp;</td></tr>
                                <tr><td colspan="12" class="bold"><?php echo $reputation_key; ?></td></tr>
                           <?php  } ?>
                            <?php $loop_count = '0';
                            foreach($reputation_val as $rep_val){                               
                               $search  = array(' ', '.', '(', ')');
                               $replace = array('', '', '', '', '_');
                               $field_id = str_replace($search, $replace, $rep_val);
                                ?>
                            <tr>
                                <td><?php echo $rep_val; ?></td>
                                <?php $cnt = '1';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                   ?>

                                <td><?php
                                if(!empty($step21Data)){
                                        $match = '0';
                                        foreach($step21Data as $step21_key=>$step21_val){
                                           if(($step21_val['GpsData']['text'] == $reputation_key) && ($step21_val['GpsData']['sub_text'] == $rep_val) && ($step21_val['GpsData']['question'] == ($cnt + ($loop_count*12)))){
                                               
                                               echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$reputation_key,'name'=>'data[GpsPack][text][]'));
                                               echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$rep_val,'name'=>'data[GpsPack][sub_text][]'));
                                               
                                               if($rep_val == 'Ranking'){
                                                    echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step21_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]','style'=>'height:30px;'));
                                               }else{
                                                    echo $this->Form->input('value[]',array('type'=>'text','value'=>$step21_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]','id'=>$field_id.$month.$reputation_count,'data-month'=>$month,'class'=>$field_id,'data-reputation'=>$reputation_count,'data-rep_sector'=>$reputation_key));
                                               }
                                               unset($step21Data[$step21_key]);
                                               $match = '1';
                                                break;
                                           }
                                           if($match == '0')
                                           {
                                               echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$reputation_key,'name'=>'data[GpsPack][text][]'));
                                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$rep_val,'name'=>'data[GpsPack][sub_text][]'));
                                               if($rep_val == 'Ranking'){
                                                    echo $this->Form->input('value[]',array('type'=>'textarea','label'=>false,'name'=>'data[GpsPack][value][]','style'=>'height:30px;'));
                                               }else{
                                                    echo $this->Form->input('value[]',array('type'=>'text','label'=>false,'name'=>'data[GpsPack][value][]','id'=>$field_id.$month.$reputation_count,'data-month'=>$month,'class'=>$field_id,'data-reputation'=>$reputation_count,'data-rep_sector'=>$reputation_key));
                                               }
                                               break;
                                           }
                                       }
                                }else{
                                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$reputation_key,'name'=>'data[GpsPack][text][]'));
                                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$rep_val,'name'=>'data[GpsPack][sub_text][]'));                                        
                                       if($rep_val == 'Ranking'){
                                            echo $this->Form->input('value[]',array('type'=>'textarea','label'=>false,'name'=>'data[GpsPack][value][]','style'=>'height:30px;'));
                                       }else{
                                            echo $this->Form->input('value[]',array('type'=>'text','label'=>false,'name'=>'data[GpsPack][value][]','id'=>$field_id.$month.$reputation_count,'data-month'=>$month,'class'=>$field_id,'data-reputation'=>$reputation_count,'data-rep_sector'=>$reputation_key));
                                       }
                                }
                                ?></td>

                                <?php $cnt++; } ?>
                            </tr>
                        <?php $loop_count++; }
                        $prev_key = $reputation_key;
                        $reputation_count++;
                        } ?>
                </table>
                </fieldset>
             
              <?php }elseif($step=='22'){ 
                  $step22Data = $this->requestAction('/GpsPacks/get_last_gps_data/'.$gps_pack_id.'/22');
                  ?>
                <fieldset>
 		<legend><?php __('Config'); ?></legend>
                    <table>
                        <tr>
                            <td colspan="2" class="bold">Market Seg Names</td>
                            <td colspan="12" class="bold">Budget</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                        </tr>
                        
                        <?php $segment_vals_total_arr = array();
                        foreach($marketsegments as $segment_key=>$segment_val){ 
                            $rn_arr = array(); $adr_arr = array();
                            ?>
                        <tr>
                            <td class="bold"><?php echo $segment_val; ?></td>
                            <td class="bold">RN</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                             ?>
                            <td><?php 
                            if(!empty($step22Data)){
                                $match = '0';
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Budget','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                       $match = '1';
                                        break;
                                   }
                               }
                               if($match == '0'){
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Budget','name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                            }else{
                                  echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                  echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Budget','name'=>'data[GpsPack][sub_text][]'));
                                  echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            ?>
                            <td><?php
                            if(!empty($step22Data)){
                                $match = '0';
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                         echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Budget','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                       $match = '1';
                                        break;
                                   }
                               } 
                               if($match == '0'){
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Budget','name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                            }else{
                                  echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                  echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Budget','name'=>'data[GpsPack][sub_text][]'));
                                  echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php } ?>
                       
                </table>
                    
                <table>
                        <tr>
                            <td colspan="2" class="bold">Market Seg Names</td>
                            <td colspan="12" class="bold">Last Year</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                            <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                            <?php } ?>
                        </tr>
                        
                        <?php  $segment_vals_total_arr = array();
                        foreach($marketsegments as $segment_key=>$segment_val){
                            $rn_arr = array(); $adr_arr = array();
                            ?>
                        <tr>
                            <td class="bold"> <?php echo $segment_val; ?></td>
                            <td class="bold">RN</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                  ?>
                            <td><?php
                            if(!empty($step22Data)){
                                $match = '0';
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Last Year') && ($step22_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Last Year','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                       $match = '1';
                                        break;
                                   }
                               } 
                              if($match == '0'){
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Last Year','name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                            }else{
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Last Year','name'=>'data[GpsPack][sub_text][]'));
                                echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                  ?>
                            <td><?php
                            if(!empty($step22Data)){
                                $match = '0';
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Last Year') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Last Year','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                       $match = '1';
                                        break;
                                   }
                               } 
                              if($match == '0'){
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Last Year','name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                            }else{
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Last Year','name'=>'data[GpsPack][sub_text][]'));
                                echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                                ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php } ?>
                </table>
                </fieldset>
                <?php } ?>
          
<div style="float:left;width:110px;">
<?php
echo $this->Form->submit(__('Next', true), array('div' => false,'class'=>'btn btn-info'));
echo $this->Form->end();
?>
</div>
    
</div>

<script>
    $(document).ready(function(){
        
        $('.Commisionpaid').attr('readonly', true);
        $('.Commisionpaid').prop('readonly', true);
        
        $('.RevExc').attr('readonly', true);
        $('.RevExc').prop('readonly', true);
        $('.RevExc,.Commisionpaid,.TotalRevenue').css({'background-color' : '#BDBDBD'});
        
        $('.TotalRevenue').attr('readonly', true);
        $('.TotalRevenue').prop('readonly', true);
        $(".RoomNights,.ADR").keyup(function () {
            var top_count = $(this).attr('data-count');
            var top_type = $(this).attr('data-top_type');
            
            var rev_value = ($('#RoomNights'+top_count+top_type).val()) * ($('#ADR'+top_count+top_type).val());
            $('#TotalRevenue'+top_count+top_type).val(rev_value);
        });
        
            //$('.Rev (Inc.)').each(function() {
            $(".RevInc").keyup(function () {
                var month = $(this).attr('data-month');
                var reputation_count = $(this).attr('data-reputation');
                //alert(month);
                // var exc_val = $(this).val() - '1.14';
               if($("#client_id").val() == '100'){
                    if($(this).attr('data-rep_sector') == 'Expedia Ranking'){
                        var exc_val = $(this).val()/'1.2';
                    }else{
                        var exc_val = $(this).val()/'1.15';
                    }
                }else{
                    var exc_val = $(this).val()/'1.14';
                }
                $('#RevExc'+month+reputation_count).val(exc_val.toFixed(2));
                
                var commisionpaid = $(this).val() - parseFloat(exc_val.toFixed(2));
                $('#Commisionpaid'+month+reputation_count).val(commisionpaid.toFixed(2));
                
            });
    });
</script>