<?php ?>
<style type="text/css">
table{ color: #6B6F6F; } 
input { font-size:100%; border:1px solid #ccc; }
input[readonly=readonly]{ background: #BDBDBD; border:1px solid #BDBDBD; }
table tr td{ background:none; font-size:12px; padding:3px; }
table tr:nth-child(2n) td{ background:none; }
table tr:nth-child(2n) td.content_head{ background:#BDBDBD; }
.bold{ font-weight: bold; }
.client_left_pannel { float: left; padding: 3px; position: absolute; width: 15%; z-index: 1001; }
#GpsDiv{ width: 100%; border-left:none; }
</style>

<a id="left_menu_link" href="javascript:void(0);"><img src="/img/menu-alt.png"></a>

<div class="Gps form" id="GpsDiv">

<?php
$room_name = explode('|',$gps_settings['GpsSetting']['roomtypes']);
//echo '<pre>'; print_r($gpsDatas); echo '</pre>';
$gps_pack_id = $GpsPack['GpsPack']['id'];
$client_id = $GpsPack['GpsPack']['client_id'];
$gps_month = $GpsPack['GpsPack']['month'];
$gps_month = sprintf("%02d", $gps_month);
$year = $GpsPack['GpsPack']['year'];

$roomValues['Standard'] = (!empty($gps_settings['GpsSetting']['standard_rooms'])) ? $gps_settings['GpsSetting']['standard_rooms'] : '0';
$roomValues['Executive'] = (!empty($gps_settings['GpsSetting']['executive_rooms'])) ? $gps_settings['GpsSetting']['executive_rooms'] : '0';
$roomValues['Deluxe'] = (!empty($gps_settings['GpsSetting']['deluxe_rooms'])) ? $gps_settings['GpsSetting']['deluxe_rooms'] : '0';
$roomValues['Suite'] = (!empty($gps_settings['GpsSetting']['suites_rooms'])) ? $gps_settings['GpsSetting']['suites_rooms'] : '0';

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $gps_month, $year);

$financial_month_start = (!empty($gps_settings['GpsSetting']['financial_month_start'])) ? $gps_settings['GpsSetting']['financial_month_start'] : '1';

$step1Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/1');
$step2Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/2'); 
$step3Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/3'); 
$step4Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/4'); 
$step5Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/5'); 
$step6Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/6'); 
$step7Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/7'); 
$step8Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/8'); 
$step9Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/9'); 
$step14Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/14'); 
$step15Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/15'); 
$step16Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/16'); 
$step17Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/17'); 
$step18Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/18'); 
$step20Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/20'); 
$step21Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/21'); 
$step22Data = $this->requestAction('/GpsPacks/get_gps_data/'.$gps_pack_id.'/22'); 

$financial_month = '1';

//$client_id = '69';
$fsct_col_id = '63'; //For Occupied Rooms in Summary Tab
$fcst_rooms_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/'.$fsct_col_id.'/'.$financial_month.'/'.$year); 

$fcst_adr_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/65/'.$financial_month.'/'.$year); //For ADR in Summary Tab
$fcst_revpar_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/70/'.$financial_month.'/'.$year); //For RevPAR in Summary Tab

//formula for Occupacy % = fcst rooms for month / (rooms in hotel * days in month)

//echo '<pre>'; print_r($fcst_rooms_arr); echo '</pre>';

?>
    
	<fieldset>
 		<legend><?php __('View GPS Pack Report'); ?></legend>

                
                
                <fieldset>
                    <?php //echo '<pre>'; print_r($step22Data); echo '</pre>'; ?>
 		<legend><?php __('Config'); ?></legend>
                    
                    <h2><a href="javascript:void(0);" onClick="$('#config_div').toggle();">View/Hide Config</a></h2>
                    <div id="config_div" style="display:none;">
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
                        
                        <?php $segment_vals_total_arr = array(); $config_seg_vals = array();
                        foreach($marketsegments as $segment_key=>$segment_val){ 
                            $rn_arr = array(); $adr_arr = array();
                            ?>
                        <tr>
                            <td class="bold"><?php echo $segment_val; ?></td>
                            <td class="bold">RN</td>
                            <?php $cnt ='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                             ?>
                            <td><?php 
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == $cnt)){
                                       echo $rn_arr[$month] = $step22_val['GpsData']['value'];
                                       $segment_vals_total_arr['RN'][$month][] = $step22_val['GpsData']['value'];
                                       $config_seg_vals[$segment_val]['Budget-RN'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $adr_arr[$month] = $step22_val['GpsData']['value'];
                                       $segment_vals_total_arr['ADR'][$month][] = $step22_val['GpsData']['value'];
                                       $config_seg_vals[$segment_val]['Budget-ADR'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php echo $segment_vals_total_arr['Rev'][$month][] = (@$rn_arr[$month] * @$adr_arr[$month]); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                        
                        <tr>
                            <td class="bold">Total</td>
                            <td class="bold">RN</td>
                            <?php $cnfig_rn_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php echo $cnfig_rn_arr1[$month] = array_sum($segment_vals_total_arr['RN'][$month]); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnfig_adr_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php echo $cnfig_adr_arr1[$month] = round(array_sum($segment_vals_total_arr['Rev'][$month])/array_sum($segment_vals_total_arr['RN'][$month]),'2'); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php  $rev_total_array = array(); $cnfig_revenue_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php echo $rev_total_array[$month] = round(array_sum($segment_vals_total_arr['Rev'][$month]),'2');
                            $cnfig_revenue_arr1[$month] = $rev_total_array[$month]; ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Occ%</td>
                            <?php $cnfig_occ_arr1=array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php $days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            echo $cnfig_occ_arr1[$month] = round(array_sum($segment_vals_total_arr['RN'][$month])/($number_of_rooms * $days_in_each_month)*100,'2'); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">RevPAR</td>
                            <?php $cnfig_revpar_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php $days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            echo $cnfig_revpar_arr1[$month] = round($rev_total_array[$month]/($number_of_rooms * $days_in_each_month),'2');  ?></td>
                            <?php } ?>
                        </tr>
                        
                        
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
                                       echo $rn_arr[$month]= $step22_val['GpsData']['value'];
                                       $config_seg_vals[$segment_val]['LastYear-RN'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                  ?>
                            <td><?php
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Last Year') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $adr_arr[$month] = $step22_val['GpsData']['value'];
                                       $config_seg_vals[$segment_val]['LastYear-ADR'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php echo $segment_vals_total_arr['Rev'][$month][] = (@$rn_arr[$month] * @$adr_arr[$month]); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="bold">Total</td>
                            <td class="bold">RN</td>
                            <?php $rn_config_total =array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php echo $rn_config_total[$month] =  array_sum($segment_vals_total_arr['RN'][$month]); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $config_adr_total=array ();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php echo $config_adr_total[$month] = round(array_sum($segment_vals_total_arr['Rev'][$month])/array_sum($segment_vals_total_arr['RN'][$month]),'2'); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php  $rev_total_array = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php echo $rev_total_array[$month] = array_sum($segment_vals_total_arr['Rev'][$month]); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Occ%</td>
                            <?php $config_occ_total = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php $days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            echo $config_occ_total[$month] = round(array_sum($segment_vals_total_arr['RN'][$month])/($number_of_rooms * $days_in_each_month)*100,'2'); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">RevPAR</td>
                            <?php $config_revpar_total=array ();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php $days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            echo $config_revpar_total[$month] = round($rev_total_array[$month]/($number_of_rooms * $days_in_each_month),'2');  ?></td>
                            <?php } ?>
                        </tr>
                </table>
                </div> 
                </fieldset>
                
                
                <fieldset>
 		<legend><?php __('GM Summary'); ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#gmsummary_div').toggle();">View/Hide GM Summary</a></h2>
                <div id="gmsummary_div" style="display:none;">
                <?php
                foreach ($step1Data as $step1_data) {
                    if($step1_data['GpsData']['question'] == '1'){
                        $answer1 = $step1_data['GpsData']['value'];
                    }elseif($step1_data['GpsData']['question'] == '2'){
                        $answer2 = $step1_data['GpsData']['value'];
                    }elseif($step1_data['GpsData']['question'] == '3'){
                        $answer3 = $step1_data['GpsData']['value'];
                    }elseif($step1_data['GpsData']['question'] == '4'){
                        $answer4 = $step1_data['GpsData']['value'];
                    }elseif($step1_data['GpsData']['question'] == '5'){
                        $answer5 = $step1_data['GpsData']['value'];
                    }
                }
                
                ?>
                <table>
                    <tr><td class="bold">How has your hotel performed against the competitor set/market set in STR?</td></tr>
                    <tr><td>&nbsp;<?php echo $answer1; ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">Where is your opportunity to improve performance in the month ahead?</td></tr>
                    <tr><td>&nbsp;<?php echo $answer2; ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">What is your synopsis of your market segmentation performance?</td></tr>
                    <tr><td>&nbsp;<?php echo $answer3; ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">What channel performance objectives do you have for the month ahead?</td></tr>
                    <tr><td>&nbsp;<?php echo $answer4; ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">Which three Sales Accounts are presenting the largest opportunity for you?</td></tr>
                    <tr><td>&nbsp;<?php echo $answer5; ?></td></tr>
                </table>
                
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Summary'); ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#summary_div').toggle();">View/Hide Summary</a></h2>
                <div id="summary_div" style="display:none;" >
                        <table>
                            <tr>
                                <td>&nbsp;</td>
                               <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                                <td class="bold">Full Year</td>
                            </tr>

                            <tr><td colspan="14">&nbsp;</td></tr>
                            <tr>
                                <td class="bold">Rooms in Hotel</td>
                                <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td><?php echo $number_of_rooms; ?></td>
                                <?php } ?>
                                <td><?php echo $full_rooms = $number_of_rooms * $days_in_month; ?></td>
                            </tr>
                            <tr>
                                <td class="bold">Occupied Rooms</td>
                                <?php
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>&nbsp;<?php echo @$fcst_rooms_arr[$month]; ?></td>
                                <?php } ?>
                                <td><?php echo $occupied_sum = array_sum(@$fcst_rooms_arr); ?></td>
                            </tr>
                            <tr>
                                <td class="bold">Occupancy%</td>
                                <?php $occ_per=array(); for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>&nbsp;<?php echo $occ_per[$month] = round($fcst_rooms_arr[$month]/( $number_of_rooms * $days_in_month),'2'); ?></td>
                                <?php } ?>
                                <td><?php echo round(array_sum($occ_per)/$full_rooms,'2'); ?></td>
                            </tr>
                            <tr>
                                <td class="bold">Revenue</td>
                                <?php $rev_arr=array();
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>&nbsp;<?php echo $rev_arr[$month] = @$fcst_rooms_arr[$month] * @$fcst_adr_arr[$month]; ?></td>
                                <?php } ?>
                                <td><?php echo $revenue_sum = array_sum($rev_arr); ?></td>
                            </tr>
                            <tr>
                                <td class="bold">ADR</td>
                                <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>&nbsp;<?php echo @$fcst_adr_arr[$month]; ?></td>
                                <?php } ?>
                                <td><?php echo round(@$revenue_sum/@$occupied_sum,'2'); ?></td>
                            </tr>
                            <tr>
                                <td class="bold">RevPAR</td>
                                <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>&nbsp;<?php echo round(@$fcst_revpar_arr[$month],'2'); ?></td>
                                <?php } ?>
                                <td><?php echo round($revenue_sum/$full_rooms,'2'); ?></td>
                            </tr>
                            
                            <tr>
                                <td class="bold">Number of Guests</td>
                                <?php $num_guest = array(); $total_guest = '0'; $cnt ='1';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>
                                    <?php
                                   foreach($step2Data as $step2_key=>$step2_val){
                                       if(($step2_val['GpsData']['text'] == 'Summary') && ($step2_val['GpsData']['question'] == $cnt)){
                                           echo $num_guest[$month] = $step2_val['GpsData']['value'];
                                           $total_guest = $total_guest + $step2_val['GpsData']['value'];
                                           unset($step2Data[$step2_key]);
                                       }
                                   }
                                    ?>
                                    </td>
                                <?php $cnt++; } ?>
                                    <td><?php echo $total_guest; ?></td>
                            </tr>
                            
                            <tr><td colspan="14">&nbsp;</td></tr>
                            <tr><td colspan="14" class="bold">Market Performance</td></tr>
                            <tr><td colspan="14" class="bold">Sandton and Surroundings - Upscale & Upper Mid</td></tr>

                            <?php
                            $market_perf_arr[] = 'MPI';
                            $market_perf_arr[] = 'ARI';
                            $market_perf_arr[] = 'RGI';
                            $sum = '0';
                            foreach($market_perf_arr as $mark_key => $market_per){ ?>
                                 <tr>
                                    <td class="bold"><?php echo $market_per; ?></td>
                                    <?php $cnt='1';
                                    for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>
                                        <?php
                                       foreach($step2Data as $step2_key=>$step2_val){
                                           if(($step2_val['GpsData']['text'] == 'Market Performance') && ($step2_val['GpsData']['sub_text'] == $market_per) && ($step2_val['GpsData']['question'] == ($cnt + ($mark_key * '12')))){
                                               echo $step2_val['GpsData']['value'];
                                               $sum = $sum +$step2_val['GpsData']['value'];
                                               unset($step2Data[$step2_key]);
                                           }
                                       } ?>
                                        </td>
                                    <?php $cnt++; } ?>
                                        <td><?php echo $sum; ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td class="bold">SpendPAR</td>
                                <?php $spendPAR = array(); for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>&nbsp;
                                    <!--  Revenue/(Rooms in Hotel * No of Days)-->
                                       <?php echo $spendPAR[$month] = round(@$rev_arr[$month]/($number_of_rooms * $days_in_month),'2'); ?>
                                    </td>
                                <?php } ?>
                                <td><?php echo array_sum($spendPAR); ?></td>
                            </tr>
                            <tr>
                                <td class="bold">Spend/Guest</td>
                                <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>&nbsp;
                                    <?php echo $spend[$month] = round(@$rev_arr[$month]/@$num_guest[$month],'2'); ?></td>
                                <?php } ?>
                                <td><?php echo array_sum($spend); ?></td>
                            </tr>
                            
                            
                            <tr><td colspan="14">&nbsp;</td></tr>
                            <tr><td colspan="14"  class="bold">Reputation Management</td></tr>
                            <tr><td colspan="14">&nbsp;</td></tr>

                            <?php
                            $summary_arr[] = 'Occupied Rooms';
                            $summary_arr[] = 'Occupancy%';
                            $summary_arr[] = 'ADR';
                            $summary_arr[] = 'RevPAR';
                            $summary_arr[] = 'Revenue';
                            ?>
                            <tr>
                                <td  class="bold">Var. to Budget</td>
                               <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                                <td class="bold"><!--Full Year--></td>
                            </tr>
                            
                            <?php foreach($summary_arr as $summary_val){
                                $summary_val_sum_arr = array();
                                ?>
                                <tr>
                                    <td class="bold"><?php echo $summary_val; ?></td>
                                    <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                        <td><?php 
                                        if($summary_val == 'Occupied Rooms'){
                                             //Occ Rooms - ConfigD75
                                            echo $summary_val_sum_arr[] = $fcst_rooms_arr[$month] - $cnfig_rn_arr1[$month];
                                        }elseif($summary_val == 'Occupancy%'){
                                            // Occ%- ConfigD78
                                           echo $summary_val_sum_arr[] = $fcst_rooms_arr[$month] - $cnfig_occ_arr1[$month];
                                        }elseif($summary_val == 'ADR'){
                                            // ADR - ConfigD76
                                            echo $summary_val_sum_arr[] = $fcst_adr_arr[$month] - $cnfig_adr_arr1[$month];
                                        }elseif($summary_val == 'RevPAR'){
                                            // RevPar- ConfigD79
                                            echo $summary_val_sum_arr[] = round(@$fcst_revpar_arr[$month],'2') -$cnfig_revpar_arr1[$month];
                                        }elseif($summary_val == 'Revenue'){
                                            //Revenue - ConfigD77
                                            echo $summary_val_sum_arr[] = $rev_arr[$month] - $cnfig_revenue_arr1[$month];
                                        }
                                        ?></td>
                                    <?php } ?>
                                    <td><?php //echo array_sum($summary_val_sum_arr); ?></td>
                                </tr>
                            <?php } ?>
                            <tr><td colspan="14">&nbsp;</td></tr>
                            
                            <tr>
                                <td  class="bold">Var. to LY</td>
                               <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                                <td class="bold"><!--Full Year--></td>
                            </tr>
                            
                            <?php foreach($summary_arr as $summary_val){ 
                                $summary_val_sum_arr = array();
                                ?>
                                <tr>
                                    <td class="bold"><?php echo $summary_val; ?></td>
                                    <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                        <td><?php 
                                        if($summary_val == 'Occupied Rooms'){
                                            //Occ Rooms - ConfigD170
                                            echo $fcst_rooms_arr[$month] - $rn_config_total[$month];
                                        }elseif($summary_val == 'Occupancy%'){
                                            // Occ%- ConfigD173
                                           echo $fcst_rooms_arr[$month] - $config_occ_total[$month];
                                        }elseif($summary_val == 'ADR'){
                                            // ADR - ConfigD171
                                            echo $fcst_adr_arr[$month] - $config_adr_total[$month];
                                        }elseif($summary_val == 'RevPAR'){
                                            // RevPar- ConfigD174
                                            echo round(@$fcst_revpar_arr[$month],'2') -$config_revpar_total[$month];
                                        }elseif($summary_val == 'Revenue'){
                                            //Revenue - ConfigD172
                                            echo $rev_arr[$month] - $rev_total_array[$month];
                                        }
                                        ?></td>
                                    <?php } ?>
                                    <td><?php //echo $full_rooms = $number_of_rooms * $days_in_month; ?></td>
                                </tr>
                            <?php } ?>
                            
                        </table>
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Market'); ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#market_con_div').toggle();">View/Hide Market</a></h2>
                <div id="market_con_div" style="display:none;">
                    <table>
                        <tr><td class="bold">Market Conditions - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></td></tr>
                        <tr><td>
                                <?php
                               foreach($step3Data as $step3_val){
                                   if($step3_val['GpsData']['question'] == '1'){
                                       echo $step3_val['GpsData']['value'];
                               }
                               } ?>
                            </td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Market Conditions - <?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></td></tr>
                        <tr><td>
                             <?php
                               foreach($step3Data as $step3_val){
                                   if($step3_val['GpsData']['question'] == '2'){
                                       echo $step3_val['GpsData']['value'];
                               }
                               } ?>
                            </td></tr>
                    </table>
                    </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Competition'); ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#Competition_div').toggle();">View/Hide Competition</a></h2>
                <div id="Competition_div" style="display:none;">
                    <table>
                        <tr><td class="bold">Competitor Activity - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></td></tr>
                        <tr><td>
                            <?php
                               foreach($step4Data as $step4_val){
                                   if($step4_val['GpsData']['question'] == '1'){
                                       echo $step4_val['GpsData']['value'];
                               }
                               } ?>
                            </td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Competitor Activity - <?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></td></tr>
                        <tr><td>
                               <?php
                               foreach($step4Data as $step4_val){
                                   if($step4_val['GpsData']['question'] == '2'){
                                       echo $step4_val['GpsData']['value'];
                               }
                               } ?>
                            </td></tr>
                    </table>
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Activity'); ?>&nbsp;<?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#Activity1_div').toggle();">View/Hide <?php __('Activity'); ?>&nbsp;<?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></a></h2>
                <div id="Activity1_div" style="display:none;">
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step5_val['GpsData']['value'];
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
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step5_val['GpsData']['value'];
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
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               echo $step5_val['GpsData']['value'];
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
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step5_val['GpsData']['value'];
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
                                               echo $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                       }
                                   } ?></td></tr>
                    </table>
                    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Activity '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#Activity2_div').toggle();">View/Hide <?php __('Activity'); ?>&nbsp;<?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></a></h2>
                <div id="Activity2_div" style="display:none;">
                    
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step6_val['GpsData']['value'];
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
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step6_val['GpsData']['value'];
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
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               echo $step6_val['GpsData']['value'];
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
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step6_val['GpsData']['value'];
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
                                               echo $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                       }
                                   } ?></td></tr>
                    </table>    
               </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Activity '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+2), 1));  ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#Activity3_div').toggle();">View/Hide <?php __('Activity'); ?>&nbsp;<?php echo date("F", mktime(0, 0, 0, ($gps_month+2), 1));  ?></a></h2>
                <div id="Activity3_div" style="display:none;">
                    
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step7_val['GpsData']['value'];
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
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step7_val['GpsData']['value'];
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
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               echo $step7_val['GpsData']['value'];
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
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step7_val['GpsData']['value'];
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
                                               echo $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                       }
                                   } ?></td></tr>
                    </table>    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Activity '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+3), 1));  ?></legend>
                    <h2><a href="javascript:void(0);" onClick="$('#Activity4_div').toggle();">View/Hide <?php __('Activity'); ?>&nbsp;<?php echo date("F", mktime(0, 0, 0, ($gps_month+3), 1));  ?></a></h2>
                <div id="Activity4_div" style="display:none;">
                   
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>
                        <?php for($count=1;$count <= 5; $count++){ ?>
                        <tr>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step8_val['GpsData']['value'];
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
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step8_val['GpsData']['value'];
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
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               echo $step8_val['GpsData']['value'];
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
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } ?></td>
                            <td><?php foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               echo $step8_val['GpsData']['value'];
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
                                               echo $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                       }
                                   } ?></td></tr>
                    </table>    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Top Producers'); ?></legend>
                    
                 <h2><a href="javascript:void(0);" onClick="$('#Producers_div').toggle();">View/Hide <?php __('Top Producers'); ?></a></h2>
                <div id="Producers_div" style="display:none;">
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
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('2' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('3' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('4' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('5' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
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
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('2' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('3' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('4' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('5' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                       }
                                   }
                               } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    
                  </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Market Segmentation - '); ?><?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></legend>
                    
                <h2><a href="javascript:void(0);" onClick="$('#market_seg_div').toggle();">View/Hide Market Segmentation - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></a></h2>
                <div id="market_seg_div" style="display:none;">
                    <!-- Autofill-->
                    <?php if(!empty($marketsegments)){ ?>
                        <table>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td class="bold">Prior Month</td>
                                    <td class="bold">Present Month</td>
                                    <td class="bold">Budget</td>
                                    <td class="bold">Last Year</td>
<!--                                    <td class="bold">YTD Actual</td>
                                    <td class="bold">YTD Budget</td>
                                    <td class="bold">Var.</td>-->
                                </tr>
                                <?php
                                $prior_month = $gps_month-'1' == '0' ? '12' : $gps_month-'1';
                                $year_for_prior = $gps_month-'1' == '0' ? $year- '1' : $year;
                                $bob_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/62');
                                $adr_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/64');
                                
                                $bob_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$gps_month.'/'.$year.'/62');
                                $adr_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$gps_month.'/'.$year.'/64');
                                
                                foreach($marketsegments as $seg_id=>$segments){ ?>
                                    <tr>
                                        <td><?php echo $segments; ?></td>
                                        <td>RN</td>
                                        <td><?php echo @$bob_prior_month[$seg_id]; ?></td>
                                        <td><?php echo @$bob_present_month[$seg_id]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['Budget-RN'][$gps_month]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['LastYear-RN'][$gps_month]; ?></td>
<!--                                        <td></td>
                                        <td></td>
                                        <td></td>-->
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>ADR</td>
                                        <td><?php echo @$adr_prior_month[$seg_id]; ?></td>
                                        <td><?php echo @$adr_present_month[$seg_id]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['Budget-ADR'][$gps_month]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['LastYear-ADR'][$gps_month]; ?></td>
<!--                                        <td></td>
                                        <td></td>
                                        <td></td>-->
                                    </tr>
                                <?php } ?>
                        </table>
                    <?php } ?>
                    <?php //echo '<pre>'; print_r($marketsegments); echo '</pre>'; ?>
                    </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('BOB - '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#bob1_div').toggle();">View/Hide BOB - <?php echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));  ?></a></h2>
                <div id="bob1_div" style="display:none;">
                    <!-- Autofill-->
                    <?php if(!empty($marketsegments)){ ?>
                        <table>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td class="bold">Prior Month</td>
                                    <td class="bold">Present Month</td>
                                    <td class="bold">Budget</td>
                                    <td class="bold">Last Year</td>
<!--                                    <td class="bold">YTD Actual</td>
                                    <td class="bold">YTD Budget</td>
                                    <td class="bold">Var.</td>-->
                                </tr>
                                <?php 
                                $present_month = $gps_month+'1' == '13' ? '1' : $gps_month;
                                $year_for_present = $gps_month+'1' == '13' ? $year+ '1' : $year;
                                
                                $bob_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$gps_month.'/'.$year.'/62');
                                $adr_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$gps_month.'/'.$year.'/64');
                                
                                $bob_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$present_month.'/'.$year_for_present.'/62');
                                $adr_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$present_month.'/'.$year_for_present.'/64');
                                
                                foreach($marketsegments as $seg_id=>$segments){ ?>
                                    <tr>
                                        <td><?php echo $segments; ?></td>
                                        <td>RN</td>
                                        <td><?php echo @$bob_prior_month[$seg_id]; ?></td>
                                        <td><?php echo @$bob_present_month[$seg_id]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['Budget-RN'][$gps_month+1]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['LastYear-RN'][$gps_month+1]; ?></td>
<!--                                        <td></td>
                                        <td></td>
                                        <td></td>-->
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>ADR</td>
                                        <td><?php echo @$adr_prior_month[$seg_id]; ?></td>
                                        <td><?php echo @$adr_present_month[$seg_id]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['Budget-ADR'][$gps_month+1]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['LastYear-ADR'][$gps_month+1]; ?></td>
<!--                                        <td></td>
                                        <td></td>
                                        <td></td>-->
                                    </tr>
                                <?php } ?>
                        </table>
                    <?php } ?>
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('BOB - '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+2), 1));  ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#bob2_div').toggle();">View/Hide  BOB - <?php echo date("F", mktime(0, 0, 0, ($gps_month+2), 1));  ?></a></h2>
                <div id="bob2_div" style="display:none;">
                    <!-- Autofill-->
                    <?php if(!empty($marketsegments)){
                        
                        $present_month = $gps_month+'2' >= '13' ? ($gps_month-'12') : $gps_month;
                        $year_for_present = $gps_month+'2' >= '13' ? $year+ '1' : $year;

                        $prior_month = $gps_month+'1' >= '13' ? ($gps_month-'12') : $gps_month;
                            $year_for_prior = $gps_month+'1' >= '13' ? $year+ '1' : $year;

                        $bob_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/62');
                        $adr_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/64');

                        $bob_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$present_month.'/'.$year_for_present.'/62');
                        $adr_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$present_month.'/'.$year_for_present.'/64');
                        
                        ?>
                        <table>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td class="bold">Prior Month</td>
                                    <td class="bold">Present Month</td>
                                    <td class="bold">Budget</td>
                                    <td class="bold">Last Year</td>
<!--                                    <td class="bold">YTD Actual</td>
                                    <td class="bold">YTD Budget</td>
                                    <td class="bold">Var.</td>-->
                                </tr>
                                <?php foreach($marketsegments as $seg_id=>$segments){ ?>
                                    <tr>
                                        <td><?php echo $segments; ?></td>
                                        <td>RN</td>
                                        <td><?php echo @$bob_prior_month[$seg_id]; ?></td>
                                        <td><?php echo @$bob_present_month[$seg_id]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['Budget-RN'][$gps_month+2]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['LastYear-RN'][$gps_month+2]; ?></td>
<!--                                        <td></td>
                                        <td></td>
                                        <td></td>-->
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>ADR</td>
                                        <td><?php echo @$adr_prior_month[$seg_id]; ?></td>
                                        <td><?php echo @$adr_present_month[$seg_id]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['Budget-ADR'][$gps_month+2]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['LastYear-ADR'][$gps_month+2]; ?></td>                                        <td></td>
<!--                                        <td></td>
                                        <td></td>
                                        <td></td>-->
                                    </tr>
                                <?php } ?>
                        </table>
                    <?php } ?>
                    </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('BOB - '); ?><?php echo date("F", mktime(0, 0, 0, ($gps_month+3), 1));  ?></legend>

                <h2><a href="javascript:void(0);" onClick="$('#bob3_div').toggle();">View/Hide  BOB - <?php echo date("F", mktime(0, 0, 0, ($gps_month+3), 1));  ?></a></h2>
                  <div id="bob3_div" style="display:none;">
                    <!-- Autofill-->
                    <?php if(!empty($marketsegments)){ 
                        
                        $present_month = $gps_month+'3' >= '13' ? ($gps_month-'12') : $gps_month;
                        $year_for_present = $gps_month+'3' >= '13' ? $year+ '1' : $year;

                        $prior_month = $gps_month+'2' >= '13' ? ($gps_month-'12') : $gps_month;
                        $year_for_prior = $gps_month+'2' >= '13' ? $year+ '1' : $year;

                        $bob_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/62');
                        $adr_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/64');

                        $bob_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$present_month.'/'.$year_for_present.'/62');
                        $adr_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$present_month.'/'.$year_for_present.'/64');
                        
                        
                        ?>
                        <table>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td class="bold">Prior Month</td>
                                    <td class="bold">Present Month</td>
                                    <td class="bold">Budget</td>
                                    <td class="bold">Last Year</td>
<!--                                    <td class="bold">YTD Actual</td>
                                    <td class="bold">YTD Budget</td>
                                    <td class="bold">Var.</td>-->
                                </tr>
                                <?php foreach($marketsegments as $seg_id=>$segments){ ?>
                                    <tr>
                                        <td><?php echo $segments; ?></td>
                                        <td>RN</td>
                                        <td><?php echo @$bob_prior_month[$seg_id]; ?></td>
                                        <td><?php echo @$bob_present_month[$seg_id]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['Budget-RN'][$gps_month+3]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['LastYear-RN'][$gps_month+3]; ?></td>
<!--                                        <td></td>
                                        <td></td>
                                        <td></td>-->
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>ADR</td>
                                        <td><?php echo @$adr_prior_month[$seg_id]; ?></td>
                                        <td><?php echo @$adr_present_month[$seg_id]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['Budget-ADR'][$gps_month+3]; ?></td>
                                        <td><?php echo $config_seg_vals[$segments]['LastYear-ADR'][$gps_month+3]; ?></td>
<!--                                        <td></td>
                                        <td></td>
                                        <td></td>-->
                                    </tr>
                                <?php } ?>
                        </table>
                    <?php } ?>
                    </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Channels - '); ?><?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#Channels_div').toggle();">View/Hide Channels - <?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></a></h2>
                <div id="Channels_div" style="display:none;">
                    <table>
                        <tr>
                            <td colspan="2" class="bold">GDS</td>
<!--                            <td class="bold">Prior Month</td>-->
                            <td class="bold">Month</td>
                            <td class="bold">Budget</td>
                            <td class="bold">Last Year</td>
                            <td class="bold">YTD Budget</td>
<!--                            <td class="bold">Var.</td>-->
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
<!--                            <td></td>-->
                            <td> <?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('1' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('2' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('3' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('4' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
<!--                            <td></td>-->
                        </tr>
                        
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
<!--                            <td></td>-->
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('5' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('6' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('7' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('8' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
<!--                            <td></td>-->
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
<!--                            <td></td>-->
                            <td> <?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('1' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('2' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('3' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('4' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
<!--                            <td></td>-->
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
<!--                            <td></td>-->
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('5' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('6' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('7' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('8' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
<!--                            <td></td>-->
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
<!--                            <td></td>-->
                            <td> <?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('1' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('2' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('3' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('4' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
<!--                            <td></td>-->
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
<!--                            <td></td>-->
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('5' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('6' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('7' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('8' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
<!--                            <td></td>-->
                        </tr>
                        <?php $loop_count++; } ?>
                        

                           <tr>
                            <td class="bold">CRO</td>
                            <td class="bold">RN</td>
<!--                            <td></td>-->
                            <td> <?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '1')){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '2')){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '3')){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '4')){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
<!--                            <td></td>-->
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
<!--                            <td></td>-->
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '5')){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '6')){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '7')){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '8')){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
<!--                            <td></td>-->
                        </tr>

                    </table>
                    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Channels Year'); ?></legend>

                <h2><a href="javascript:void(0);" onClick="$('#Channel_yr_div').toggle();">View/Hide Channels Year</a></h2>
                <div id="Channel_yr_div" style="display:none;">
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
                            $rev_arr = array();
                        ?>

                        <tr>
                            <td class="bold"><?php echo $gd_val; ?></td>
                            <td class="bold">RN</td>
                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            ?>
                            <td>
                                <?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'GDS') && ($step15_val['GpsData']['sub_text'] == $gd_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       echo $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'GDS') && ($step15_val['GpsData']['sub_text'] == $gd_val) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       echo $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                            <td>  <?php  echo (@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']); ?></td>
                            <?php } ?>
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
                            $rev_arr = array();
                            ?>
                        <tr>
                            <td class="bold"><?php echo $online_arr_val; ?></td>
                            <td class="bold">RN</td>
                            <?php  $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Online') && ($step15_val['GpsData']['sub_text'] == $online_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       echo $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step15_val['GpsData']['value'];
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
                                   if(($step15_val['GpsData']['text'] == 'Online') && ($step15_val['GpsData']['sub_text'] == $online_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       echo $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                             <td> <?php  echo (@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']); ?></td>
                            <?php } ?>
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
                            $rev_arr = array();
                            ?>
                        <tr>
                            <td class="bold"><?php echo $direct_arr_val; ?></td>
                            <td class="bold">RN</td>

                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
//($month + ($loop_count*24))
                                ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Direct') && ($step15_val['GpsData']['sub_text'] == $direct_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       echo $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step15_val['GpsData']['value'];
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
                                       echo $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                             <td><?php   echo (@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']); ?></td>
                            <?php } ?>
                        </tr>
                        <?php $loop_count++; }
                        $rev_arr = array();
                        ?>
                        
                        <tr>
                            <td class="bold">CRO</td>
                            <td class="bold">RN</td>
                            <?php $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'CRO')  && ($step15_val['GpsData']['question'] == $cnt)){
                                       echo $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step15_val['GpsData']['value'];
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
                                   if(($step15_val['GpsData']['text'] == 'CRO')  && ($step15_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                           <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td><?php echo (@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']); ?></td>
                            <?php } ?>
                        </tr>
                    </table>
                    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Geo Year'); ?></legend>

                <h2><a href="javascript:void(0);" onClick="$('#geo_yr_div').toggle();">View/Hide Geo Year</a></h2>
                <div id="geo_yr_div" style="display:none;">
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
                            $rev_arr = array();
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
                                       echo $step16_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step16_val['GpsData']['value'];
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
                                       echo $step16_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step16_val['GpsData']['value'];
                                       unset($step16Data[$step16_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                            <td><?php  echo (@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                </table>
                    
                    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Prov Year'); ?></legend>
                    
                <h2><a href="javascript:void(0);" onClick="$('#prov_yr_div').toggle();">View/Hide Prov Year</a></h2>
                <div id="prov_yr_div" style="display:none;">
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
                            $rev_arr = array();
                            ?>
                        <tr>
                            <td class="bold"><?php echo $country_val; ?></td>
                            <td class="bold">RN</td>
                            <?php $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step17Data as $step17_key=>$step17_val){
                                   if(($step17_val['GpsData']['text'] == $country_val)  && ($step17_val['GpsData']['question'] == $cnt)){
                                       echo $step17_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step17_val['GpsData']['value'];
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
                                       echo $step17_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step17_val['GpsData']['value'];
                                       unset($step17Data[$step17_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td><?php echo (@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                </table>
                                            
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('RoomTypes'); ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#roomtype_div').toggle();">View/Hide RoomTypes</a></h2>
                <div id="roomtype_div" style="display:none;">
                    <?php 
                    $roomType_arr[$room_name[0]] = 'Standard';
                    $roomType_arr[$room_name[1]] = 'Executive';
                    $roomType_arr[$room_name[2]] = 'Deluxe';
                    $roomType_arr[$room_name[3]] = 'Suite';
                    ?>
                    <table>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>Actual</td><td>Prior Month</td><td>Prior Year</td><td>BAR A</td><td>Potential</td><td>Utilisation</td></tr>
                        <?php foreach($roomType_arr as $roomType_key=>$roomType_arr_val){
                            $adr_val = ''; $bar_val = '';
                            $roomTypeData = $this->requestAction('/GpsPacks/get_roomtype_values/'.($gps_month-1).'/'.$roomType_arr_val.'/'.$client_id.'/'.$gps_pack_id);
                           // echo '<pre>'; print_r($roomTypeData); echo '</pre>';
                            ?>
                        <tr>
                            <td class="bold"><?php echo $roomType_key; ?></td>
                            <td class="bold">RN</td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '1')){
                                       echo $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                            <td><?php echo @$roomTypeData['RN'][$gps_month-1]; ?></td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '2')){
                                       echo $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '3')){
                                       echo $bar_val = $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '4')){
                                       echo $adr_val = $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                            <td><?php echo @$roomTypeData['ADR'][$gps_month-1]; ?></td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '5')){
                                       echo $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                            <td>&nbsp;</td>
                            <td><?php echo (($adr_val/$bar_val)* 100).'%'  ?></td>
                            <td><?php echo $bar_val/($roomValues[$roomType_arr_val] * $days_in_month); ?></td>
                        </tr>
                        <?php } ?>
                </table>
                    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Room Type Year'); ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#roomtype_yr_div').toggle();">View/Hide Room Type Year</a></h2>
                <div id="roomtype_yr_div" style="display:none;">
                    <!-- Autofill-->
                    <table>
                        <tr>
                            <td>&nbsp;</td><td>&nbsp;</td>
                             <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                             <td class="bold"><?php echo date("M", mktime(0, 0, 0, $month, 1)); ?></td>
                             <?php } ?>
                        </tr>
                        
                        <?php
                        foreach($roomType_arr as $roomType_key=>$roomType_arr_val){
                            $roomTypeData = $this->requestAction('/GpsPacks/get_roomtype_values/year/'.$roomType_arr_val.'/'.$client_id.'/'.$gps_pack_id);
                            //echo '<pre>'; print_r($roomTypeData); echo '</pre>';
                        ?>
                        <tr>
                            <td class="bold"><?php echo $roomType_key; ?></td>
                            <td class="bold">RN</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                            <td>&nbsp;<?php echo @$roomTypeData['RN'][$month]; ?></td>    
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                            <td>&nbsp;<?php echo @$roomTypeData['ADR'][$month]; ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                              <td>&nbsp;<?php  echo (@$roomTypeData['RN'][$month] * @$roomTypeData['ADR'][$month]); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                </table>
                    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Future Activity'); ?></legend>
                <h2><a href="javascript:void(0);" onClick="$('#future_div').toggle();">View/Hide Future Activity</a></h2>
                <div id="future_div" style="display:none;">
                    <table>
                        <tr><td class="bold">Expected Market Conditions</td></tr>
                        <tr><td><?php  foreach ($step20Data as $step20_data) {
                            if($step20_data['GpsData']['question'] == '1'){
                                echo $step20_data['GpsData']['value'];
                            }
                        } ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Planned Activity, by Segment</td></tr>
                        <tr><td><?php  foreach ($step20Data as $step20_data) {
                            if($step20_data['GpsData']['question'] == '2'){
                                echo $step20_data['GpsData']['value'];
                            }
                        } ?></td></tr>
                    </table>
               </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Reputation'); ?></legend>
                    
               <?php //echo '<pre>'; print_r($step21Data); echo '</pre>';  ?>
                
                <h2><a href="javascript:void(0);" onClick="$('#Reputation_div').toggle();">View/Hide Reputation</a></h2>
                <div id="Reputation_div" style="display:none;">
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
                                <?php  $cnt = '1';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                   ?>

                                <td><?php
                               foreach($step21Data as $step21_key=>$step21_val){
                                   if(($step21_val['GpsData']['text'] == $reputation_key) && ($step21_val['GpsData']['sub_text'] == $rep_val) && ($step21_val['GpsData']['question'] == ($cnt + ($loop_count*12)))){
                                       echo $step21_val['GpsData']['value'];
                                       unset($step21Data[$step21_key]);
                                   }
                               } ?></td>

                                <?php $cnt++; } ?>
                            </tr>
                        <?php $loop_count++; }
                        $prev_key = $reputation_key;
                        } ?>
                </table>
                </div>
                </fieldset>
                
	</fieldset>

<div style="float:left;margin-top:5px;height:40px;">
<?php //echo $this->Html->link('Cancel', array('prefix' => 'client', 'client' => true, 'controller' => 'Gps', 'action' => 'index'), array('class' => 'new_button', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;'));?>
</div>
</div>

<?php echo $this->element('user_left_menu'); ?>
<script>
$(document).ready(function(){

$('#left_menu').hide();

$('#left_menu_link').click(function() {
    if ($('#left_menu').is(':visible')){
      $('#left_menu').hide('500');
      $("#GpsDiv").css({"width":"100%"});
    }else{
        $('#left_menu').show('500');
        $("#GpsDiv").css({"width":"80%"});
    }
});
  
});
</script>