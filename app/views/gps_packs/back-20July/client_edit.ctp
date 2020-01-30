<?php ?>
<style type="text/css">
table{ color: #6B6F6F; } 
input, textarea { font-size:100%; border:1px solid #ccc; }
input[readonly=readonly]{ background: #BDBDBD; border:1px solid #BDBDBD; }
table tr td{ background:none; font-size:12px; padding:3px; }
table tr:nth-child(2n) td{ background:none; }
table tr:nth-child(2n) td.content_head{ background:#BDBDBD; }
.bold{ font-weight: bold; }
</style>


<div class="Gps form" >

<?php
$room_name = explode('|',$gps_settings['GpsSetting']['roomtypes']);

//echo '<pre>'; print_r($gpsDatas); echo '</pre>';
$gps_pack_id = $GpsPack['GpsPack']['id'];
$client_id = $GpsPack['GpsPack']['client_id'];
$gps_month = $GpsPack['GpsPack']['month'];
$gps_month = sprintf("%02d", $gps_month);
$gps_year = $GpsPack['GpsPack']['year'];

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $gps_month, $year);

$financial_month_start = (!empty($gps_settings['GpsSetting']['financial_month_start'])) ? $gps_settings['GpsSetting']['financial_month_start'] : '1';

?>
<?php echo $this->Form->create('GpsPack', array('url' => array('prefix' => 'client', 'client' => true, 'controller' => 'GpsPacks', 'action' => 'edit')));?>
    
    <?php
    echo $this->Form->input('step',array('type'=>'hidden','value'=>$step));
    echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id));
    echo $this->Form->input('pack_id',array('type'=>'hidden','value'=>$gps_pack_id)); 
    ?>    
	<fieldset>
 		<legend><?php __('Edit GPS Pack'); ?></legend>
                
                <?php if($step=='1'){ 
                    $step1Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/1');
                    if(empty($step1Data)){
                        header('Location: /client/GpsPacks/add/1/'.$gps_pack_id);
                    }
                    
                ?>
                <fieldset>
 		<legend><?php __('GM Summary'); ?></legend>
                <?php
                foreach ($step1Data as $step1_data) {
                    if($step1_data['GpsData']['question'] == '1'){
                        $answer1 = $step1_data['GpsData']['value']; $id1 = $step1_data['GpsData']['id'];
                    }elseif($step1_data['GpsData']['question'] == '2'){
                        $answer2 = $step1_data['GpsData']['value'];  $id2 = $step1_data['GpsData']['id'];
                    }elseif($step1_data['GpsData']['question'] == '3'){
                        $answer3 = $step1_data['GpsData']['value'];  $id3 = $step1_data['GpsData']['id'];
                    }elseif($step1_data['GpsData']['question'] == '4'){
                        $answer4 = $step1_data['GpsData']['value'];  $id4 = $step1_data['GpsData']['id'];
                    }elseif($step1_data['GpsData']['question'] == '5'){
                        $answer5 = $step1_data['GpsData']['value'];  $id5 = $step1_data['GpsData']['id'];
                    }
                }
                echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$id1,'name'=>'data[GpsPack][id][]'));
                echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$id2,'name'=>'data[GpsPack][id][]'));
                echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$id3,'name'=>'data[GpsPack][id][]'));
                echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$id4,'name'=>'data[GpsPack][id][]'));
                echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$id5,'name'=>'data[GpsPack][id][]'));
                ?>
                <table>
                    <tr><td class="bold">How has your hotel performed against the competitor set/market set in STR?</td></tr>
                    <tr><td>&nbsp;<?php echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$answer1,'label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">Where is your opportunity to improve performance in the month ahead?</td></tr>
                    <tr><td>&nbsp;<?php echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$answer2,'label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">What is your synopsis of your market segmentation performance?</td></tr>
                    <tr><td>&nbsp;<?php echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$answer3,'label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">What channel performance objectives do you have for the month ahead?</td></tr>
                    <tr><td>&nbsp;<?php echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$answer4,'label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">Which three Sales Accounts are presenting the largest opportunity for you?</td></tr>
                    <tr><td>&nbsp;<?php echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$answer5,'label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                </table>
                </fieldset>
                
                <?php }elseif($step=='2'){ 
                    $step2Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/2'); 
                    if(empty($step2Data)){
                        header('Location: /client/GpsPacks/add/2/'.$gps_pack_id);
                    }
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
                                   foreach($step2Data as $step2_key=>$step2_val){
                                       if(($step2_val['GpsData']['text'] == 'Summary') && ($step2_val['GpsData']['question'] == $cnt)){
                                        echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step2_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                        echo $this->Form->input('value[]',array('type'=>'text','id'=>'guest_'.$month,'label'=>false,'value'=>$step2_val['GpsData']['value'], 'name'=>'data[GpsPack][value][]'));
                                           unset($step2Data[$step2_key]);
                                       }
                                   }
                                    ?>
                                    </td>
                                <?php $cnt++; } ?>
                            </tr>
                            
                            <tr><td colspan="14">&nbsp;</td></tr>
                            <tr><td colspan="14" class="bold">Market Performance</td></tr>
                            <tr><td colspan="14" class="bold">Sandton and Surroundings - Upscale & Upper Mid</td></tr>

                            <?php
                            $market_perf_arr[] = 'MPI';
                            $market_perf_arr[] = 'ARI';
                            $market_perf_arr[] = 'RGI';
                            
                            foreach($market_perf_arr as $mark_key => $market_per){ ?>
                                 <tr>
                                    <td class="bold"><?php echo $market_per; ?></td>
                                    <?php $cnt ='1';
                                    for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>
                                        <?php
                                       foreach($step2Data as $step2_key=>$step2_val){
                                           if(($step2_val['GpsData']['text'] == 'Market Performance') && ($step2_val['GpsData']['sub_text'] == $market_per) && ($step2_val['GpsData']['question'] == ($cnt + ($mark_key * '12')))){

                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step2_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','id'=>'guest_'.$month,'value'=>$step2_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step2Data[$step2_key]);
                                           }
                                       } ?>
                                        </td>
                                    <?php $cnt++; } ?>
                                </tr>
                            <?php } ?>
                        </table>
                </fieldset>
                <?php }elseif($step=='3'){ 
                    $step3Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/3'); 
                    if(empty($step3Data)){
                        header('Location: /client/GpsPacks/add/3/'.$gps_pack_id);
                    }
                    ?>
                <fieldset>
 		<legend><?php __('Market'); ?></legend>
                    <table>
                        <tr><td class="bold">Market Conditions - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></td></tr>
                        <tr><td>
                                <?php
                               foreach($step3Data as $step3_val){
                                   if($step3_val['GpsData']['question'] == '1'){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step3_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_3_1','value'=>$step3_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                               } ?>
                            </td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Market Conditions - <?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></td></tr>
                        <tr><td>
                             <?php
                               foreach($step3Data as $step3_val){
                                   if($step3_val['GpsData']['question'] == '2'){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step3_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'stp','value'=>$step3_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                               } ?>
                            </td></tr>
                    </table>
                </fieldset>
                <?php }elseif($step=='4'){ 
                    $step4Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/4');
                    if(empty($step4Data)){
                        header('Location: /client/GpsPacks/add/4/'.$gps_pack_id);
                    }
                    ?>
                <fieldset>
 		<legend><?php __('Competition'); ?></legend>
                    <table>
                        <tr><td class="bold">Competitor Activity - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></td></tr>
                        <tr><td>
                            <?php
                               foreach($step4Data as $step4_val){
                                   if($step4_val['GpsData']['question'] == '1'){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step4_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step4_val['GpsData']['value'],'id'=>'step_3_1','label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                               } ?>
                            </td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Competitor Activity - <?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></td></tr>
                        <tr><td>
                               <?php
                               foreach($step4Data as $step4_val){
                                   if($step4_val['GpsData']['question'] == '2'){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step4_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step4_val['GpsData']['value'],'id'=>'step_3_1','label'=>false,'name'=>'data[GpsPack][value][]'));
                               }
                               } ?>
                            </td></tr>
                    </table>
                </fieldset>
                <?php }elseif($step=='5'){ 
                    $step5Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/5'); 
                    if(empty($step5Data)){
                        header('Location: /client/GpsPacks/add/5/'.$gps_pack_id);
                    }
                    ?>
                <fieldset>
 		<legend><?php __('Activity'); ?>&nbsp;<?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></legend>
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        
                         <?php for($count=1;$count <= 5; $count++){ ?>
                        
                        <tr>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        
                        <?php } ?>
                        
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                          if(($step5_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td class="bold">Planned Activity</td><td class="bold">Fcst RN</td><td class="bold">Fcst ADR</td><td class="bold">Revenue</td><td class="bold">Actual RN</td><td class="bold">Actual ADR</td><td class="bold">Actual Revenue</td><td class="bold">Var. Revenue</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Planned Activity'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>

                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step5_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    
                    <table>
                        <tr><td class="bold">Comments</td></tr>
                        <tr><td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Comments')){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step5_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step5_val['GpsData']['value'],'id'=>'step_3_1','label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step5Data[$step5_key]);
                                       }
                                   } ?></td></tr>
                    </table>
                </fieldset>
                <?php }elseif($step=='6'){ 
                    $step6Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/6');
                    if(empty($step6Data)){
                        header('Location: /client/GpsPacks/add/6/'.$gps_pack_id);
                    }
                    ?>
                <fieldset>
 		<legend><?php __('Activity '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></legend>
                
                <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        
                         <?php for($count=1;$count <= 5; $count++){ ?>
                        
                        <tr>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        
                        <?php } ?>
                        
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                          if(($step6_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td class="bold">Planned Activity</td><td class="bold">Fcst RN</td><td class="bold">Fcst ADR</td><td class="bold">Revenue</td><td class="bold">Actual RN</td><td class="bold">Actual ADR</td><td class="bold">Actual Revenue</td><td class="bold">Var. Revenue</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Planned Activity'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>

                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                
                    <table>
                        <tr><td class="bold">Comments</td></tr>
                        <tr><td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Comments')){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step6_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step6_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step6Data[$step6_key]);
                                       }
                                   } ?></td></tr>
                    </table>
                </fieldset>
                <?php }elseif($step=='7'){ 
                    $step7Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/7');
                    if(empty($step7Data)){
                        header('Location: /client/GpsPacks/add/7/'.$gps_pack_id);
                    }
                    ?>
                <fieldset>
 		<legend><?php __('Activity '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+2), 1));  ?></legend>
                    
                <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        
                         <?php for($count=1;$count <= 5; $count++){ ?>
                        
                        <tr>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        
                        <?php } ?>
                        
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                          if(($step7_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td class="bold">Planned Activity</td><td class="bold">Fcst RN</td><td class="bold">Fcst ADR</td><td class="bold">Revenue</td><td class="bold">Actual RN</td><td class="bold">Actual ADR</td><td class="bold">Actual Revenue</td><td class="bold">Var. Revenue</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Planned Activity'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>

                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    
                    <table>
                        <tr><td class="bold">Comments</td></tr>
                        <tr><td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Comments')){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step7_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step7_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step7Data[$step7_key]);
                                       }
                                   } ?></td></tr>
                    </table>
                </fieldset>
                <?php }elseif($step=='8'){ 
                    $step8Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/8'); 
                    if(empty($step8Data)){
                        header('Location: /client/GpsPacks/add/8/'.$gps_pack_id);
                    }
                    ?>
                <fieldset>
 		<legend><?php __('Activity '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+3), 1));  ?></legend>
                 
                <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        
                         <?php for($count=1;$count <= 5; $count++){ ?>
                        
                        <tr>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        
                        <?php } ?>
                        
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                          if(($step8_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td class="bold">Planned Activity</td><td class="bold">Fcst RN</td><td class="bold">Fcst ADR</td><td class="bold">Revenue</td><td class="bold">Actual RN</td><td class="bold">Actual ADR</td><td class="bold">Actual Revenue</td><td class="bold">Var. Revenue</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Planned Activity'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>

                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                
                
                <table>
                        <tr><td class="bold">Comments</td></tr>
                        <tr><td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Comments')){
                                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step8_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                               echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step8_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step8Data[$step8_key]);
                                       }
                                   } ?></td></tr>
                    </table>    
                </fieldset>
                <?php }elseif($step=='9'){ 
                    $step9Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/9'); 
                    if(empty($step9Data)){
                        header('Location: /client/GpsPacks/add/9/'.$gps_pack_id);
                    }
                    ?>
                <fieldset>
 		<legend><?php __('Top Producers'); ?></legend>
                    <table>
                        <tr><td colspan="6" class="bold">Corporate</td></tr>
                        <tr><td>&nbsp;</td><td class="bold">Name</td><td class="bold"> Room Nights</td><td class="bold">ADR</td><td class="bold">Av. Spend</td><td class="bold">Total Revenue</td></tr>
                        <?php for($count=1;$count <= 10; $count++){ 
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                        <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('1' + ('5' * ($count-1))))){
                                           echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step9_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step9_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('2' + ('5' * ($count-1))))){
                                           echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step9_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step9_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('3' + ('5' * ($count-1))))){
                                          echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step9_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step9_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('4' + ('5' * ($count-1))))){
                                           echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step9_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step9_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('5' + ('5' * ($count-1))))){
                                           echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step9_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step9_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    
                    <table>
                        <tr><td colspan="6" class="bold">Travel Agents</td></tr>
                        <tr><td>&nbsp;</td><td class="bold">Name</td><td class="bold">Room Nights</td><td class="bold">ADR</td><td class="bold">Av. Spend</td><td class="bold">Total Revenue</td></tr>
                        <?php for($count=1;$count <= 10; $count++){ 
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                        <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('1' + ('5' * ($count-1))))){
                                           echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step9_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step9_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('2' + ('5' * ($count-1))))){
                                           echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step9_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step9_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('3' + ('5' * ($count-1))))){
                                           echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step9_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step9_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('4' + ('5' * ($count-1))))){
                                           echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step9_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step9_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('5' + ('5' * ($count-1))))){
                                           echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step9_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step9_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </fieldset>
                <?php }elseif($step=='10'){ ?>
                <fieldset>
 		<legend><?php __('Market Segmentation - '); ?><?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></legend>
                </fieldset>
                <?php }elseif($step=='11'){ ?>
                <fieldset>
 		<legend><?php __('BOB - '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></legend>
                </fieldset>
                <?php }elseif($step=='12'){ ?>
                <fieldset>
 		<legend><?php __('BOB - '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+2), 1));  ?></legend>
                </fieldset>
                <?php }elseif($step=='13'){ ?>
                <fieldset>
 		<legend><?php __('BOB - '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+3), 1));  ?></legend>
                </fieldset>
                <?php }elseif($step=='14'){ 
                    $step14Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/14');
                    if(empty($step14Data)){
                        header('Location: /client/GpsPacks/add/14/'.$gps_pack_id);
                    }
                    ?>
                <fieldset>
 		<legend><?php __('Channels - '); ?><?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></legend>
                    <table>
                        <tr>
                            <td colspan="2" class="bold">GDS</td>
                            <td class="bold">Month</td>
                            <td class="bold">Budget</td>
                            <td class="bold">Last Year</td>
                            <td class="bold">YTD Budget</td>
                        </tr>
                        
                        <?php
                        $gds_arr['1'] = 'Sabre';
                        $gds_arr['2'] = 'Amadeus';
                        $gds_arr['3'] = 'Appollo';
                        $gds_arr['4'] = 'Galileo';
                        $gds_arr['5'] = 'Worldspan';
                        ?>
                        
                        <?php $loop_count = '0';
                        foreach($gds_arr as $gd_key=>$gd_val){ 
                        ?>
                        <tr>
                            <td class="bold"><?php echo $gd_val; ?></td>
                            <td class="bold">RN</td>
                            <td> <?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('1' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('2' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('3' + ($loop_count*8)))){
                                      echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('4' + ($loop_count*8)))){
                                      echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                        </tr>
                        
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('5' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('6' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('7' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('8' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                        </tr>
                        <?php $loop_count++; } ?>
                        
                        <tr><td colspan="8">&nbsp;</td></tr>                        

                        <tr><td colspan="8" class="bold">Online</td></tr>
                        <?php 
                        $online_arr['1'] = 'Website';
                        $online_arr['2'] = 'OTA';
                        ?>
                        
                        <?php  $loop_count = '0';
                        foreach($online_arr as $online_arr_key=>$online_arr_val){ 
                             
                        ?>
                        <tr>
                            <td class="bold"><?php echo $online_arr_val; ?></td>
                            <td class="bold">RN</td>
                            <td> <?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('1' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('2' + ($loop_count*8)))){
                                      echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('3' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('4' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('5' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('6' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('7' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('8' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                        </tr>
                        <?php $loop_count++; } ?>
                        
                        <tr><td colspan="8">&nbsp;</td></tr>                        

                        <tr><td colspan="8" class="bold">Direct</td></tr>
                        <?php 
                        $direct_arr['1'] = 'Phone';
                        $direct_arr['2'] = 'Email/Fax';
                        $direct_arr['3'] = 'Walkin';
                        ?>
                        
                        <?php $loop_count = '0';
                        foreach($direct_arr as $direct_arr_key=>$direct_arr_val){ 

                        ?>
                           <tr>
                            <td class="bold"><?php echo $direct_arr_val; ?></td>
                            <td class="bold">RN</td>
                            <td> <?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('1' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('2' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('3' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('4' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('5' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('6' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('7' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('8' + ($loop_count*8)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                        </tr>
                        <?php $loop_count++; } ?>
                        

                           <tr>
                            <td class="bold">CRO</td>
                            <td class="bold">RN</td>
                            <td> <?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '1')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '2')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '3')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '4')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '5')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '6')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '7')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '8')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step14_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step14_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                        </tr>

                    </table>
                </fieldset>
                <?php }elseif($step=='15'){ 
                    $step15Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/15'); 
                    if(empty($step15Data)){
                        header('Location: /client/GpsPacks/add/15/'.$gps_pack_id);
                    }
                    
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
                        $gds_arr['1'] = 'Sabre';
                        $gds_arr['2'] = 'Amadeus';
                        $gds_arr['3'] = 'Appollo';
                        $gds_arr['4'] = 'Galileo';
                        $gds_arr['5'] = 'Worldspan';
                        ?>
                        
                        <?php $loop_count = '0';
                        foreach($gds_arr as $gd_key=>$gd_val){
                        ?>

                        <tr>
                            <td class="bold"><?php echo $gd_val; ?></td>
                            <td class="bold">RN</td>
                            <?php $cnt ='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            ?>
                            <td>
                                <?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'GDS') && ($step15_val['GpsData']['sub_text'] == $gd_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step15_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'GDS') && ($step15_val['GpsData']['sub_text'] == $gd_val) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step15_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php $loop_count++; } ?>
                        
                        <tr><td colspan="14">&nbsp;</td></tr>                        

                        <tr><td colspan="14"><b>Online</b></td></tr>
                        <?php 
                        $online_arr['1'] = 'Website';
                        $online_arr['2'] = 'OTA';
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
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Online') && ($step15_val['GpsData']['sub_text'] == $online_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step15_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt ='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Online') && ($step15_val['GpsData']['sub_text'] == $online_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step15_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php $loop_count++; } ?>
                        
                        <tr><td colspan="14">&nbsp;</td></tr>                        

                        <tr><td colspan="14"><b>Direct</b></td></tr>
                        <?php 
                        $direct_arr['1'] = 'Phone';
                        $direct_arr['2'] = 'Email/Fax';
                        $direct_arr['3'] = 'Walkin';
                        ?>
                        
                        <?php $loop_count = '0';
                        foreach($direct_arr as $direct_arr_key=>$direct_arr_val){ 
                            ?>
                        <tr>
                            <td class="bold"><?php echo $direct_arr_val; ?></td>
                            <td class="bold">RN</td>

                            <?php $cnt= '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Direct') && ($step15_val['GpsData']['sub_text'] == $direct_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step15_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Direct') && ($step15_val['GpsData']['sub_text'] == $direct_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step15_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php $loop_count++; }
                        ?>
                        
                        <tr>
                            <td class="bold">CRO</td>
                            <td class="bold">RN</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'CRO')  && ($step15_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step15_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt ='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'CRO')  && ($step15_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step15_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                    </table>
                </fieldset>
                <?php }elseif($step=='16'){ 
                    $step16Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/16'); 
                    if(empty($step16Data)){
                        header('Location: /client/GpsPacks/add/16/'.$gps_pack_id);
                    }
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
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step16Data as $step16_key=>$step16_val){
                                   if(($step16_val['GpsData']['text'] == $country_val)  && ($step16_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step16_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step16_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step16Data[$step16_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step16Data as $step16_key=>$step16_val){
                                   if(($step16_val['GpsData']['text'] == $country_val)  && ($step16_val['GpsData']['question'] == ($cnt + '12'))){
                                        echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step16_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step16_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step16Data[$step16_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php } ?>
                </table>
                
                </fieldset>
                <?php }elseif($step=='17'){ 
                    $step17Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/17'); 
                    if(empty($step17Data)){
                        header('Location: /client/GpsPacks/add/17/'.$gps_pack_id);
                    }
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
                        $country_arr['1'] = 'Eastern Cape';
                        $country_arr['2'] = 'Free State';
                        $country_arr['3'] = 'Gauteng';
                        $country_arr['4'] = 'Kwa-Zulu Natal';
                        $country_arr['5'] = 'Limpopo';
                        $country_arr['6'] = 'Mpumalanga';
                        $country_arr['7'] = 'Northern Cape';
                        $country_arr['8'] = 'North West';
                        $country_arr['9'] = 'Western Cape';
                        $country_arr['10'] = 'Other';
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
                               foreach($step17Data as $step17_key=>$step17_val){
                                   if(($step17_val['GpsData']['text'] == $country_val)  && ($step17_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step17_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step17_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step17Data[$step17_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step17Data as $step17_key=>$step17_val){
                                   if(($step17_val['GpsData']['text'] == $country_val)  && ($step17_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step17_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step17_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step17Data[$step17_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php } ?>
                </table>
                </fieldset>
                <?php }elseif($step=='18'){ 
                    $step18Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/18'); 
                    if(empty($step18Data)){
                        header('Location: /client/GpsPacks/add/18/'.$gps_pack_id);
                    }
                    ?>
                <fieldset>
 		<legend><?php __('RoomTypes'); ?></legend>
                    <?php 
                    $roomType_arr[$room_name[0]] = 'Standard';
                    $roomType_arr[$room_name[1]] = 'Executive';
                    $roomType_arr[$room_name[2]] = 'Deluxe';
                    $roomType_arr[$room_name[3]] = 'Suite';
                    ?>
                    <table>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>Actual</td><td>Prior Year</td><td>BAR A</td></tr>
                        <?php foreach($roomType_arr as $roomType_key=>$roomType_arr_val){
                            ?>
                        <tr>
                            <td class="bold"><?php echo $roomType_key; ?></td>
                            <td class="bold">RN</td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '1')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step18_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step18_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '2')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step18_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step18_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '3')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step18_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step18_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '4')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step18_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step18_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '5')){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step18_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step18_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php } ?>
                </table>
                </fieldset>
                <?php }elseif($step=='19'){ ?>
                <fieldset>
 		<legend><?php __('Room Type Year'); ?></legend>
                </fieldset>
                <?php }elseif($step=='20'){
                    $step20Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/20'); 
                    if(empty($step20Data)){
                        header('Location: /client/GpsPacks/add/20/'.$gps_pack_id);
                    }
                    ?>
                <fieldset>
 		<legend><?php __('Future Activity'); ?></legend>
                    <table>
                        <tr><td class="bold">Expected Market Conditions</td></tr>
                        <tr><td><?php  foreach ($step20Data as $step20_val) {
                            if($step20_val['GpsData']['question'] == '1'){
                               echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step20_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                               echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step20_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                        } ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Planned Activity, by Segment</td></tr>
                        <tr><td><?php  foreach ($step20Data as $step20_val) {
                            if($step20_val['GpsData']['question'] == '2'){
                                echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step20_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step20_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                        } ?></td></tr>
                    </table>
                </fieldset>
                
                <?php }elseif($step=='21'){
                    $step21Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/21');
                    if(empty($step21Data)){
                        header('Location: /client/GpsPacks/add/21/'.$gps_pack_id);
                    }
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
                        $reputation_arr['Agoda'][] = 'Ranking';
                        $reputation_arr['Agoda'][] = 'Room Nights';
                        $reputation_arr['Agoda'][] = 'Rev (Exc.)';
                        $reputation_arr['Agoda'][] = 'Rev (Inc.)';
                        $reputation_arr['Agoda'][] = 'Commission %';
                        $reputation_arr['Agoda'][] = 'Commision paid';
                        $reputation_arr['Agoda'][] = 'Reviews';
                        $reputation_arr['Agoda'][] = 'Review Score';
                        
                        $reputation_arr['Booking.com'][] = 'Ranking';
                        $reputation_arr['Booking.com'][] = 'Room Nights';
                        $reputation_arr['Booking.com'][] = 'Rev (Exc.)';
                        $reputation_arr['Booking.com'][] = 'Rev (Inc.)';
                        $reputation_arr['Booking.com'][] = 'Commission %';
                        $reputation_arr['Booking.com'][] = 'Commision paid';
                        $reputation_arr['Booking.com'][] = 'Reviews';
                        $reputation_arr['Booking.com'][] = 'Review Score';
                        
                        $reputation_arr['Expedia Ranking'][] = 'Room Nights';
                        $reputation_arr['Expedia Ranking'][] = 'Rev (Exc.)';
                        $reputation_arr['Expedia Ranking'][] = 'Rev (Inc.)';
                        $reputation_arr['Expedia Ranking'][] = 'Commission %';
                        $reputation_arr['Expedia Ranking'][] = 'Commission Paid';
                        
                        $reputation_arr['Safarinow.com'][] = 'Room Nights';
                        $reputation_arr['Safarinow.com'][] = 'Rev (Exc.)';
                        $reputation_arr['Safarinow.com'][] = 'Rev (Inc.)';
                        $reputation_arr['Safarinow.com'][] = 'Commission %';
                        $reputation_arr['Safarinow.com'][] = 'Commission Paid';
                        
                        $reputation_arr['Tripadvisor Ranking'][] = 'Ranking';
                        $reputation_arr['Tripadvisor Ranking'][] = 'Reviews';
                        
                        $reputation_arr['Facebook'][] = 'Likes';
                        
                        $reputation_arr['Twitter'][] = 'Followers';
                        
                        $reputation_arr['Search Engine Optimisation'][] = 'Visits';
                        $reputation_arr['Search Engine Optimisation'][] = 'Bounce Rate';
                        $reputation_arr['Search Engine Optimisation'][] = 'Page Views';
                        $reputation_arr['Search Engine Optimisation'][] = 'Visit Duration';
                        $reputation_arr['Search Engine Optimisation'][] = 'New visitors (30 days)';
                        
//                        $reputation_arr['Google Analytics'][] = 'Main booking page';
//                        $reputation_arr['Google Analytics'][] = 'Select a room';
//                        $reputation_arr['Google Analytics'][] = 'Personal details';
//                        $reputation_arr['Google Analytics'][] = 'Payment';
//                        $reputation_arr['Google Analytics'][] = 'Find a reservation';
//
//                        $reputation_arr['Tres Booking Funnel'][] = 'Search';
//                        $reputation_arr['Tres Booking Funnel'][] = 'Pending';
//                        $reputation_arr['Tres Booking Funnel'][] = 'Cancellations';
//                        $reputation_arr['Tres Booking Funnel'][] = 'Removed';
//                        $reputation_arr['Tres Booking Funnel'][] = 'Bookings';

                        ?>
                        
                        <?php $prev_key = '';
                        //echo '<pre>'; print_r($reputation_arr); echo '</pre>';
                        foreach($reputation_arr as $reputation_key=>$reputation_val){ 
                            if($prev_key != $reputation_key){ ?>
                            <tr><td colspan="12" >&nbsp;</td></tr>
                                <tr><td colspan="12" class="bold"><?php echo $reputation_key; ?></td></tr>
                           <?php  } ?>
                            <?php $loop_count = '0';
                            foreach($reputation_val as $rep_val){ ?>
                            <tr>
                                <td><?php echo $rep_val; ?></td>
                                <?php $cnt = '1';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                ?>

                                <td><?php
                               foreach($step21Data as $step21_key=>$step21_val){
                                   if(($step21_val['GpsData']['text'] == $reputation_key) && ($step21_val['GpsData']['sub_text'] == $rep_val) && ($step21_val['GpsData']['question'] == ($cnt + ($loop_count*12)))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step21_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       if($rep_val == 'Ranking'){
                                            echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step21_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]','style'=>'height:30px;'));   
                                       }else{
                                            echo $this->Form->input('value[]',array('type'=>'text','value'=>$step21_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));   
                                       }
                                       unset($step21Data[$step21_key]);
                                   }
                               } ?></td>

                                <?php $cnt++; } ?>
                            </tr>
                        <?php $loop_count++; }
                        $prev_key = $reputation_key;
                        } ?>
                </table>
                </fieldset>
             
              <?php }elseif($step=='22'){ 
                  $step22Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/22');
                  if(empty($step22Data)){
                        header('Location: /client/GpsPacks/add/22/'.$gps_pack_id);
                    }
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
                        
                        <?php 
//                        $segment_arr['1'] = 'Rack';
//                        $segment_arr['2'] = 'Discount 2';
//                        $segment_arr['3'] = 'Discount 3';
//                        $segment_arr['4'] = 'Discount 4';
//                        $segment_arr['5'] = 'Discount 5';
//                        $segment_arr['6'] = 'Promotions';
//                        $segment_arr['7'] = 'Packages';
//                        $segment_arr['8'] = 'Public Corp';
//                        $segment_arr['9'] = 'Corp 1';
//                        $segment_arr['10'] = 'Corp 2';
//                        $segment_arr['11'] = 'Corp 3';
//                        $segment_arr['12'] = 'STO 1';
//                        $segment_arr['13'] = 'STO 2';
//                        $segment_arr['14'] = 'STO 3';
//                        $segment_arr['15'] = 'Groups Leisure';
//                        $segment_arr['16'] = 'Groups Corp';
//                        $segment_arr['17'] = 'Comp';
                        ?>
                        
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
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step22_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step22_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                   }
                               } ?></td>
                            <?php } ?>
                        </tr>
                        <?php $cnt++; } ?>
                       
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
                        
                        <?php 
//                        $segment_arr['1'] = 'Rack';
//                        $segment_arr['2'] = 'CORP';
//                        $segment_arr['3'] = 'COR 1';
//                        $segment_arr['4'] = 'COR 2';
//                        $segment_arr['5'] = 'COR 3';
//                        $segment_arr['6'] = 'COR 4';
//                        $segment_arr['7'] = 'COR 5';
//                        $segment_arr['8'] = 'COR 6';
//                        $segment_arr['9'] = 'BAR 1';
//                        $segment_arr['10'] = 'BAR 2';
//                        $segment_arr['11'] = 'BAR 3';
//                        $segment_arr['12'] = 'BAR 4';
//                        $segment_arr['13'] = 'BAR 5';
//                        $segment_arr['14'] = 'BAR 6';
//                        $segment_arr['15'] = 'Promotions';
//                        $segment_arr['16'] = 'Packages';
//                        $segment_arr['17'] = 'MASS, NEG1-4 IND';
//                        $segment_arr['18'] = 'SHUL';
//                        $segment_arr['19'] = 'WEEKEND';
//                        $segment_arr['20'] = 'STO';
//                        $segment_arr['21'] = 'AFOP';
//                        $segment_arr['22'] = 'ECCO';
//                        $segment_arr['23'] = 'Groups Leisure';
//                        $segment_arr['24'] = 'Groups Corp';
//                        $segment_arr['25'] = 'Comp';
//                        $segment_arr['26'] = 'NO SHOW';
                        ?>
                        
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
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Last Year') && ($step22_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step22_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                  ?>
                            <td><?php
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Last Year') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('id[]',array('type'=>'hidden','value'=>$step22_val['GpsData']['id'],'name'=>'data[GpsPack][id][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php } ?>
                </table>
                </fieldset>
                <?php } ?>
          
	</fieldset>

<div style="float:left;width:110px;">
<?php
echo $this->Form->submit(__('Next', true), array('div' => false));
echo $this->Form->end();
?>
</div>
    
</div>

<?php echo $this->element('client_left_menu'); ?>