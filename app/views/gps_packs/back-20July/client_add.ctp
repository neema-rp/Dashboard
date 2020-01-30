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

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $gps_month, $gps_year);

$financial_month_start = (!empty($gps_settings['GpsSetting']['financial_month_start'])) ? $gps_settings['GpsSetting']['financial_month_start'] : '1';

?>
<?php echo $this->Form->create('GpsPack', array('url' => array('prefix' => 'client', 'client' => true, 'controller' => 'GpsPacks', 'action' => 'add')));?>
    
    <?php
    echo $this->Form->input('step',array('type'=>'hidden','value'=>$step));
    echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id));
    echo $this->Form->input('id',array('type'=>'hidden','value'=>$gps_pack_id)); 
    ?>    
	<fieldset>
 		<legend><?php __('Edit GPS Pack'); ?></legend>
                
                <?php if($step=='1'){ ?>
                <fieldset>
 		<legend><?php __('GM Summary'); ?></legend>
                   <table>
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
                            <tr><td colspan="14" class="bold">Sandton and Surroundings - Upscale & Upper Mid</td></tr>

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
                    <table>
                        <tr><td>Market Conditions - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_3_1','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>Market Conditions - <?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    </table>
                <?php }elseif($step=='4'){ ?>
                    <table>
                        <tr><td>Competitor Activity - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_3_1','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>Competitor Activity - <?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></td></tr>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    </table>
                
                <?php }elseif($step=='5'){ ?>
                    <h2><?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></h2>
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>

                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>

                        <?php for($count=1;$count <= 5; $count++){ ?>
                        
                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>

                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>
                    
                    
                <table>
                        <tr><td>Planned Activity</td><td>Fcst RN</td><td>Fcst ADR</td><td>Revenue</td><td>Actual RN</td><td>Actual ADR</td><td>Actual Revenue</td><td>Var. Revenue</td></tr>
                
                        <?php for($count=1;$count <= 5; $count++){ ?>
                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Planned Activity'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst RN'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst ADR'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual RN'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual ADR'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Var. Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>
                
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <?php } ?>
                    </table>


                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        
                        <?php 
                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                        ?>
                        
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>
                    
                    <table>
                        <tr><td>Comments</td></tr>
                        <?php echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Comments','name'=>'data[GpsPack][text][]')); ?>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_1_1','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    </table>
                    
                <?php }elseif($step=='6'){ ?>
                    <h2><?php __('Activity '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></h2>
                                            <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>

                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>

                        <?php for($count=1;$count <= 5; $count++){ ?>
                        
                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>

                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>
                    
                    
                <table>
                        <tr><td>Planned Activity</td><td>Fcst RN</td><td>Fcst ADR</td><td>Revenue</td><td>Actual RN</td><td>Actual ADR</td><td>Actual Revenue</td><td>Var. Revenue</td></tr>
                
                        <?php for($count=1;$count <= 5; $count++){ ?>
                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Planned Activity'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst RN'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst ADR'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual RN'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual ADR'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Var. Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>
                
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <?php } ?>
                    </table>


                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        
                        <?php 
                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                        ?>
                        
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td>Comments</td></tr>
                        <?php echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Comments','name'=>'data[GpsPack][text][]')); ?>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_1_1','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    </table>
                    
                <?php }elseif($step=='7'){ ?>
                    
                    <h2><?php __('Activity '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+2), 1));  ?></h2>
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>

                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>

                        <?php for($count=1;$count <= 5; $count++){ ?>
                        
                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>

                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>
                    
                    
                <table>
                        <tr><td>Planned Activity</td><td>Fcst RN</td><td>Fcst ADR</td><td>Revenue</td><td>Actual RN</td><td>Actual ADR</td><td>Actual Revenue</td><td>Var. Revenue</td></tr>
                
                        <?php for($count=1;$count <= 5; $count++){ ?>
                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Planned Activity'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst RN'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst ADR'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual RN'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual ADR'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Var. Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>
                
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <?php } ?>
                    </table>


                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        
                        <?php 
                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                        ?>
                        
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>
                    
                    <table>
                        <tr><td>Comments</td></tr>
                        <?php echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Comments','name'=>'data[GpsPack][text][]')); ?>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_1_1','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    </table>                    
                <?php }elseif($step=='8'){ ?>
                    
                    <h2><?php __('Activity '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+3), 1));  ?></h2>
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>

                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>

                        <?php for($count=1;$count <= 5; $count++){ ?>
                        
                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>

                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>
                    
                    
                <table>
                        <tr><td>Planned Activity</td><td>Fcst RN</td><td>Fcst ADR</td><td>Revenue</td><td>Actual RN</td><td>Actual ADR</td><td>Actual Revenue</td><td>Var. Revenue</td></tr>
                
                        <?php for($count=1;$count <= 5; $count++){ ?>
                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Planned Activity'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst RN'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst ADR'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual RN'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual ADR'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Var. Revenue'.$count,'name'=>'data[GpsPack][sub_text][]'));
                                ?>
                
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <?php } ?>
                    </table>


                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        
                        <?php 
                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target'.$count,'name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source'.$count,'name'=>'data[GpsPack][sub_text][]'));
                        ?>
                        
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </table>                    
                    <table>
                        <tr><td>Comments</td></tr>
                        <?php echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Comments','name'=>'data[GpsPack][text][]')); ?>
                        <tr><td><?php echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'step_1_1','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td></tr>
                    </table>

                <?php }elseif($step=='9'){ ?>
                    
                    <h2>Top Producers</h2>
                    <table>
                        <tr><td colspan="6" class="bold">Corporate</td></tr>
                        <tr><td>&nbsp;</td><td>Name</td><td>Room Nights</td><td>ADR</td><td>Av. Spend</td><td>Total Revenue</td></tr>
                        <?php for($count=1;$count <= 10; $count++){ 

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Corporate','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Name','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Corporate','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Room Nights','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Corporate','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'ADR','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Corporate','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Av. Spend','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Corporate','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Total Revenue','name'=>'data[GpsPack][sub_text][]'));

                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    
                    <table>
                        <tr><td colspan="6" class="bold">Travel Agents</td></tr>
                        <tr><td>&nbsp;</td><td>Name</td><td>Room Nights</td><td>ADR</td><td>Av. Spend</td><td>Total Revenue</td></tr>
                        <?php for($count=1;$count <= 10; $count++){ 

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Travel Agents','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Name','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Travel Agents','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Room Nights','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Travel Agents','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'ADR','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Travel Agents','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Av. Spend','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Travel Agents','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Total Revenue','name'=>'data[GpsPack][sub_text][]'));

                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    
                <?php }elseif($step=='10'){ ?>
                    <h2>Market Segmentation - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></h2>
                    <!-- Autofill-->
                    
                <?php }elseif($step=='11'){ ?>
                    <h2>BOB - <?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></h2>
                    <!-- Autofill-->
                <?php }elseif($step=='12'){ ?>
                    <h2>BOB - <?php echo date("F", mktime(0, 0, 0, ($gps_month+2), 1));  ?></h2>
                    <!-- Autofill-->
                <?php }elseif($step=='13'){ ?>
                    <h2>BOB - <?php echo date("F", mktime(0, 0, 0, ($gps_month+3), 1));  ?></h2>
                    <!-- Autofill-->
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
                        $gds_arr['1'] = 'Sabre';
                        $gds_arr['2'] = 'Amadeus';
                        $gds_arr['3'] = 'Galileo';
                        $gds_arr['4'] = 'Worldspan';
                        ?>
                        
                        <?php foreach($gds_arr as $gd_key=>$gd_val){ 

                             for($loop=1;$loop <= 8; $loop++){
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_val,'name'=>'data[GpsPack][sub_text][]'));
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
                        $online_arr['1'] = 'Website';
                        $online_arr['2'] = 'OTA';
                        ?>
                        
                        <?php foreach($online_arr as $online_arr_key=>$online_arr_val){ 

                        for($loop=1;$loop <= 8; $loop++){
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_val,'name'=>'data[GpsPack][sub_text][]'));
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
                        $direct_arr['1'] = 'Phone';
                        $direct_arr['2'] = 'Email/Fax';
                        $direct_arr['3'] = 'Walkin';
                        ?>
                        
                        <?php foreach($direct_arr as $direct_arr_key=>$direct_arr_val){ 

                        for($loop=1;$loop <= 8; $loop++){
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_val,'name'=>'data[GpsPack][sub_text][]'));
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
                            <td>CRO</td>
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
                    
                    
//                    echo 'here';
//                    echo '<pre>';print_r($step15Data); echo '</pre>';
//                    
                    
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
                        $gds_arr['3'] = 'Galileo';
                        $gds_arr['4'] = 'Worldspan';
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
                                       if(($step15_val['GpsData']['text'] == 'GDS') && ($step15_val['GpsData']['sub_text'] == $gd_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                           echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                           echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_val,'name'=>'data[GpsPack][sub_text][]'));
                                           echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                           unset($step15Data[$step15_key]);
                                       }
                                   }
                                }else{
                                 echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                 echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_val,'name'=>'data[GpsPack][sub_text][]'));
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
                                           if(($step15_val['GpsData']['text'] == 'GDS') && ($step15_val['GpsData']['sub_text'] == $gd_val) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                               echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                               echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_val,'name'=>'data[GpsPack][sub_text][]'));
                                               echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               unset($step15Data[$step15_key]);
                                           }
                                       } 
                               }else{
                                   echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                   echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_val,'name'=>'data[GpsPack][sub_text][]'));
                                   echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                                }
                             ?></td>
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
                            if(!empty($step15Data)){
                                foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Online') && ($step15_val['GpsData']['sub_text'] == $online_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_val,'name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               }
                            }else{
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_val,'name'=>'data[GpsPack][sub_text][]'));
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
                                   if(($step15_val['GpsData']['text'] == 'Online') && ($step15_val['GpsData']['sub_text'] == $online_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_val,'name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               } 
                            }else{
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_val,'name'=>'data[GpsPack][sub_text][]'));
                                    echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                             ?></td>
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

                            <?php $cnt ='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                ?>
                            <td><?php
                            if(!empty($step15Data)){
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Direct') && ($step15_val['GpsData']['sub_text'] == $direct_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_val,'name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               } 
                            }else{
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_val,'name'=>'data[GpsPack][sub_text][]'));
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
                                   if(($step15_val['GpsData']['text'] == 'Direct') && ($step15_val['GpsData']['sub_text'] == $direct_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_val,'name'=>'data[GpsPack][sub_text][]'));    
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step15_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step15Data[$step15_key]);
                                   }
                               }
                            }else{
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_val,'name'=>'data[GpsPack][sub_text][]'));
                                echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                            }
                            ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <?php $loop_count++; }
                        ?>
                        
                        <tr>
                            <td class="bold">CRO</td>
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
                               foreach($step16Data as $step16_key=>$step16_val){
                                   if(($step16_val['GpsData']['text'] == $country_val)  && ($step16_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step16_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step16Data[$step16_key]);
                                   }
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
                               foreach($step16Data as $step16_key=>$step16_val){
                                   if(($step16_val['GpsData']['text'] == $country_val)  && ($step16_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));    
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step16_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step16Data[$step16_key]);
                                   }
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
                            if(!empty($step17Data)){
                               foreach($step17Data as $step17_key=>$step17_val){
                                   if(($step17_val['GpsData']['text'] == $country_val)  && ($step17_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step17_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step17Data[$step17_key]);
                                   }
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
                               foreach($step17Data as $step17_key=>$step17_val){
                                   if(($step17_val['GpsData']['text'] == $country_val)  && ($step17_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));    
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step17_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step17Data[$step17_key]);
                                   }
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
                <?php }elseif($step=='20'){ ?>
                    <table>
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
                                if(!empty($step21Data)){
                                        foreach($step21Data as $step21_key=>$step21_val){
                                           if(($step21_val['GpsData']['text'] == $reputation_key) && ($step21_val['GpsData']['sub_text'] == $rep_val) && ($step21_val['GpsData']['question'] == ($cnt + ($loop_count*12)))){
                                               echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$reputation_key,'name'=>'data[GpsPack][text][]'));
                                               echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$rep_val,'name'=>'data[GpsPack][sub_text][]'));
                                               
                                               if($rep_val == 'Ranking'){
                                                    echo $this->Form->input('value[]',array('type'=>'textarea','value'=>$step21_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]','style'=>'height:30px;'));
                                               }else{
                                                    echo $this->Form->input('value[]',array('type'=>'text','value'=>$step21_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                               }
                                               
                                               unset($step21Data[$step21_key]);
                                           }
                                       }
                                }else{
                                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$reputation_key,'name'=>'data[GpsPack][text][]'));
                                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$rep_val,'name'=>'data[GpsPack][sub_text][]'));
                                        
                                        if($rep_val == 'Ranking'){
                                            echo $this->Form->input('value[]',array('type'=>'textarea','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]','style'=>'height:30px;'));
                                       }else{
                                            echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]'));
                                       }
                                }
                                ?></td>

                                <?php $cnt++; } ?>
                            </tr>
                        <?php $loop_count++; }
                        $prev_key = $reputation_key;
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
                            if(!empty($step22Data)){
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                  echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Budget','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                   }
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
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                  echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Budget','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                   }
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
                            if(!empty($step22Data)){
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Last Year') && ($step22_val['GpsData']['question'] == $cnt)){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Last Year','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                   }
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
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Last Year') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                       echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Last Year','name'=>'data[GpsPack][sub_text][]'));
                                       echo $this->Form->input('value[]',array('type'=>'text','value'=>$step22_val['GpsData']['value'],'label'=>false,'name'=>'data[GpsPack][value][]'));
                                       unset($step22Data[$step22_key]);
                                   }
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
          
	</fieldset>

<div style="float:left;width:110px;">
<?php
echo $this->Form->submit(__('Next', true), array('div' => false));
echo $this->Form->end();
?>
</div>
    
</div>

<?php echo $this->element('client_left_menu'); ?>