<?php ?>
<style type="text/css">
.main-content{ margin-left:0px; }
table{ color: #6B6F6F; } 
input { font-size:100%; border:1px solid #ccc; }
input[readonly=readonly]{ background: #BDBDBD; border:1px solid #BDBDBD; }
table tr td{ background:none; font-size:12px; padding:3px; }
table tr:nth-child(2n) td{ background:none; }
table tr:nth-child(2n) td.content_head{ background:#BDBDBD; }
.bold{ font-weight: bold; }
#GpsDiv{ width: 100%; border-left:none; }
.table { width: 96%; }
legend{ color:red; }
</style>

<div style="float:right;height:40px;margin-bottom:10px;">
    <a href="javascript:void(0);" onClick="print_report();"><button class="btn btn-app btn-light btn-mini">
    <i class="icon-print bigger-160"></i>
    Print Report
    </button></a>
</div>

<div class="Gps form" id="GpsDiv">
<?php
$room_name = explode('|',$gps_settings['GpsSetting']['roomtypes']);
$gps_pack_id = $GpsPack['GpsPack']['id'];
$client_id = $GpsPack['GpsPack']['client_id'];
$gps_month = $GpsPack['GpsPack']['month'];
$gps_month = sprintf("%02d", $gps_month);
$year = $GpsPack['GpsPack']['year'];

$roomValues['Standard'] = (!empty($gps_settings['GpsSetting']['standard_rooms'])) ? $gps_settings['GpsSetting']['standard_rooms'] : '0';
$roomValues['Executive'] = (!empty($gps_settings['GpsSetting']['executive_rooms'])) ? $gps_settings['GpsSetting']['executive_rooms'] : '0';
$roomValues['Deluxe'] = (!empty($gps_settings['GpsSetting']['deluxe_rooms'])) ? $gps_settings['GpsSetting']['deluxe_rooms'] : '0';
$roomValues['Suite'] = (!empty($gps_settings['GpsSetting']['suites_rooms'])) ? $gps_settings['GpsSetting']['suites_rooms'] : '0';
$roomValues['Other'] = (!empty($gps_settings['GpsSetting']['other_rooms'])) ? $gps_settings['GpsSetting']['other_rooms'] : '0';

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

if($client_id == '100'){ //cobblers
    $fsct_col_id = '62'; //For Occupied Rooms in Summary Tab
    $fcst_rooms_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/'.$fsct_col_id.'/'.$financial_month.'/'.$year); 
    $fcst_adr_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/64/'.$financial_month.'/'.$year); //For ADR in Summary Tab
    $fcst_revpar_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/71/'.$financial_month.'/'.$year); //For RevPAR in Summary Tab    
    

//    if($_SERVER['REMOTE_ADDR'] == '101.59.237.22'){
//       echo '<pre>'; print_r($fcst_rooms_arr);
//       echo '------------------';
//       print_r($fcst_adr_arr);
//       echo '</pre>';
//    }
    
}else{
    $fsct_col_id = '63'; //For Occupied Rooms in Summary Tab
    $fcst_rooms_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/'.$fsct_col_id.'/'.$financial_month.'/'.$year); 
    $fcst_adr_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/65/'.$financial_month.'/'.$year); //For ADR in Summary Tab
    $fcst_revpar_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/70/'.$financial_month.'/'.$year); //For RevPAR in Summary Tab
}

$channels_tyd_actual_arr = array();

$prior_month = ($gps_month-'1') == '0' ? '12' : $gps_month-'1';
//$next2_month = ($gps_month+'2' >= '13') ? ($gps_month-'12') : $gps_month+'2';

$next2_month = ($gps_month+'2' >= '13') ? (($gps_month+'2') - '12') : $gps_month+'2';
//$next_month = ($gps_month+'1' >= '13') ? ($gps_month-'12') : $gps_month+'1';
$next_month = ($gps_month+'1' >= '13') ? (($gps_month+'1')- '12') : $gps_month+'1';

$next2_month = sprintf("%02d", $next2_month);
$next_month = sprintf("%02d", $next_month);

$year_for_prior = ($gps_month-'1') == '0' ? $year- '1' : $year;
$year_for_next2 = ($gps_month+'2' >= '13') ? $year+ '1' : $year;
$year_for_next = ($gps_month+'1' >= '13') ? $year+ '1' : $year;

//$present_month = $gps_month+'3' >= '13' ? ('12' - ($gps_month+'3')) : $gps_month;

$config_html = '<div id="config_div">
                        <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td colspan="2" class="bold">Market Seg Names</td>
                            <td colspan="12" class="bold">Budget</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                               $config_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                              }
                        $config_html .= '</tr>';
                        
                       $segment_vals_total_arr = array(); $config_seg_vals = array();
                        foreach($marketsegments as $segment_key=>$segment_val){ 
                            $rn_arr = array(); $adr_arr = array();
                        $config_html .= '<tr>
                            <td class="bold">'.$segment_val.'</td>
                            <td class="bold">RN</td>';
                           $cnt ='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            
                            $config_html .= '<td>'; 
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == $cnt)){
                                       $config_html .= $rn_arr[$month] = $step22_val['GpsData']['value'];
                                       $segment_vals_total_arr['RN'][$month][] = $rn_arr[$month];
                                       $config_seg_vals[$segment_val]['Budget-RN'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               }
                               $config_html .= '</td>';
                            $cnt++; } 
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            
                            $config_html .= '<td>';
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       $config_html .= $adr_arr[$month] = $step22_val['GpsData']['value'];
                                       $segment_vals_total_arr['ADR'][$month][] = $step22_val['GpsData']['value'];
                                       $config_seg_vals[$segment_val]['Budget-ADR'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               }
                               $config_html .= '</td>';
                            $cnt++; }
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            
                            $config_html .= '<td>'.$segment_vals_total_arr['Rev'][$month][] = (@$rn_arr[$month] * @$adr_arr[$month]).'</td>';
                            }
                        $config_html .= '</tr>';
                        }
                        
                        $config_html .= '<tr>
                            <td class="bold">Total</td>
                            <td class="bold">RN</td>';
                            $cnfig_rn_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            $config_html .= '<td>'.$cnfig_rn_arr1[$month] = array_sum($segment_vals_total_arr['RN'][$month]).'</td>';
                            }
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnfig_adr_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            $config_html .= '<td>'.$cnfig_adr_arr1[$month] = round(array_sum($segment_vals_total_arr['Rev'][$month])/array_sum($segment_vals_total_arr['RN'][$month]),'2').'</td>';
                            }
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            $rev_total_array = array(); $cnfig_revenue_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            $config_html .= '<td>'.$rev_total_array[$month] = round(array_sum($segment_vals_total_arr['Rev'][$month]),'0').'</td>';
                            $cnfig_revenue_arr1[$month] = $rev_total_array[$month];
                            }
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Occ%</td>';
                            $cnfig_occ_arr1=array(); $days_month_arr = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $use_month =($month > '12')?$month -'12':$month;
                            $days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $use_month, $year);
                            $days_month_arr[$month] = $days_in_each_month;
                            $config_html .= '<td>'. $cnfig_occ_arr1[$month] = round(array_sum($segment_vals_total_arr['RN'][$month])/($number_of_rooms * $days_in_each_month)*100,'2').'</td>';
                            }
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">RevPAR</td>';
                            $cnfig_revpar_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                           //$days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                           $cal_var = $rev_total_array[$month]/($number_of_rooms * $days_month_arr[$month]);
                            $config_html .= '<td>'.number_format($cal_var,'2').'</td>';
                            $cnfig_revpar_arr1[$month] = round($cal_var,'2'); 
                            }
                        $config_html .= '</tr>
                </table>
                
                <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td colspan="2" class="bold">Market Seg Names</td>
                            <td colspan="12" class="bold">Last Year</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $config_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                            }
                        $config_html .= '</tr>';

                        $segment_vals_total_arr = array();
                        foreach($marketsegments as $segment_key=>$segment_val){
                            $rn_arr = array(); $adr_arr = array();
                        $config_html .= '<tr>
                            <td class="bold">'.$segment_val.'</td>
                            <td class="bold">RN</td>';
                        
                            $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            
                            $config_html .= '<td>';
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Last Year') && ($step22_val['GpsData']['question'] == $cnt)){
                                       $config_html .= $rn_arr[$month]= $step22_val['GpsData']['value'];
                                       $segment_vals_total_arr['RN'][$month][] = $rn_arr[$month];
                                       $config_seg_vals[$segment_val]['LastYear-RN'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               }
                               $config_html .= '</td>';
                            $cnt++; }
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                  
                            $config_html .= '<td>';
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Last Year') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       $config_html .= $adr_arr[$month] = $step22_val['GpsData']['value'];
                                       $config_seg_vals[$segment_val]['LastYear-ADR'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               }
                               $config_html .= '</td>';
                            $cnt++; }
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $config_html .= '<td>'.$segment_vals_total_arr['Rev'][$month][] = (@$rn_arr[$month] * @$adr_arr[$month]).'</td>';
                            }
                        $config_html .= '</tr>';
                        }
                        $config_html .= '<tr>
                            <td class="bold">Total</td>
                            <td class="bold">RN</td>';
                            $rn_config_total =array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            
                            $config_html .= '<td>'.$rn_config_total[$month] =  array_sum($segment_vals_total_arr['RN'][$month]).'</td>';
                            }
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $config_adr_total=array ();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            $config_html .= '<td>'.$config_adr_total[$month] = round(array_sum($segment_vals_total_arr['Rev'][$month])/array_sum($segment_vals_total_arr['RN'][$month]),'2').'</td>';
                            }
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            $rev_total_array = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            
                            $config_html .= '<td>'.$rev_total_array[$month] = round(array_sum($segment_vals_total_arr['Rev'][$month]),'0').'</td>';
                            }
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Occ%</td>';
                            $config_occ_total = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            //$days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            $config_html .= '<td>'.$config_occ_total[$month] = round(array_sum($segment_vals_total_arr['RN'][$month])/($number_of_rooms * $days_month_arr[$month])*100,'2').'</td>';
                            }
                        $config_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">RevPAR</td>';
                            $config_revpar_total=array ();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            //$days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            $cal_var = $rev_total_array[$month]/($number_of_rooms * $days_month_arr[$month]);
                            $config_html .= '<td>'.number_format($cal_var,'2').'</td>';
                            $config_revpar_total[$month] = round($cal_var,'2'); 
                            } 
                        $config_html .= '</tr>
                </table>
                </div>';

?>
    
 		<legend><?php __('GPS Pack Report'); ?></legend>

                <fieldset>
 		<legend><?php __('GM Summary'); ?></legend>
                <div id="gmsummary_div">
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
                <table class="table table-striped table-bordered table-hover">
                    <tr><td class="bold">How has your hotel performed against the competitor set/market set in STR?</td></tr>
                    <tr><td>&nbsp;<?php //echo $answer1;
                    echo "<pre><p style='font-family:Arial, Helvetica, sans-serif;font-size:12px;white-space: pre-wrap;width:85em;'>".$answer1."</p></pre>";
                    ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">Where is your opportunity to improve performance in the month ahead?</td></tr>
                    <tr><td>&nbsp;<?php //echo $answer2;
                    echo "<pre><p style='font-family:Arial, Helvetica, sans-serif;font-size:12px;white-space: pre-wrap;width:85em;'>".$answer2."</p></pre>";
                    ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">What is your synopsis of your market segmentation performance?</td></tr>
                    <tr><td>&nbsp;<?php //echo $answer3;
                    echo "<pre><p style='font-family:Arial, Helvetica, sans-serif;font-size:12px;white-space: pre-wrap;width:85em;'>".$answer3."</p></pre>";
                    ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">What channel performance objectives do you have for the month ahead?</td></tr>
                    <tr><td>&nbsp;<?php //echo $answer4; 
                    echo "<pre><p style='font-family:Arial, Helvetica, sans-serif;font-size:12px;white-space: pre-wrap;width:85em;'>".$answer4."</p></pre>";
                    ?></td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">Which three Sales Accounts are presenting the largest opportunity for you?</td></tr>
                    <tr><td>&nbsp;<?php //echo $answer5; 
                    echo "<pre><p style='font-family:Arial, Helvetica, sans-serif;font-size:12px;white-space: pre-wrap;width:85em;'>".$answer5."</p></pre>";
                    ?></td></tr>
                </table>
                
                </div>
                </fieldset>

                <fieldset>
 		<legend><?php __('Summary'); ?></legend>
                   <div id="summary_div" >
                        <table class="table table-striped table-bordered table-hover">
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
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $use_month =($month > '12')?$month -'12':$month; ?>
                                    <td>&nbsp;<?php echo number_format($fcst_rooms_arr[$use_month]); ?></td>
                                <?php } ?>
                                <td><?php echo $occupied_sum = array_sum(@$fcst_rooms_arr); ?></td>
                            </tr>
                            <tr>
                                <td class="bold">Occupancy%</td>
                                <?php $occ_per=array(); 
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                    $use_month =($month > '12')?$month -'12':$month;
                                    ?>
                                    <td>&nbsp;
                                    <?php 
                                    //echo '<pre>'; print_r($fcst_rooms_arr); print_r($days_month_arr); exit;
                                    
                                    echo $occ_per[$use_month] = round($fcst_rooms_arr[$use_month]/($number_of_rooms * $days_month_arr[$month]),'3') * '100'; ?>
                                    </td>
                                <?php } ?>
                                <td><?php echo $sum_occoupacy_per = round(array_sum($occ_per)/$full_rooms,'2'); ?></td>
                            </tr>
                            <tr>
                                <td class="bold">Revenue</td>
                                <?php $rev_arr=array();
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $use_month =($month > '12')?$month -'12':$month; ?>
                                    <td>&nbsp;<?php $rev_arr[$month] = @$fcst_rooms_arr[$use_month] * @$fcst_adr_arr[$use_month];
                                        echo number_format($rev_arr[$month]);
                                    ?></td>
                                <?php } ?>
                                <td><?php echo $revenue_sum = number_format(array_sum($rev_arr)); ?></td>
                            </tr>
                            <tr>
                                <td class="bold">ADR</td>
                                <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                    $use_month =($month > '12')?$month -'12':$month; ?>
                                    <td>&nbsp;<?php echo number_format(@$fcst_adr_arr[$use_month],'2'); ?></td>
                                <?php } ?>
                                <td><?php echo $sum_adr_per = number_format(@$revenue_sum/@$occupied_sum,'2'); ?></td>
                            </tr>
                            <tr>
                                <td class="bold">RevPAR</td>
                                <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                    $use_month =($month > '12')?$month -'12':$month; ?>
                                    <td>&nbsp;<?php echo number_format(@$fcst_revpar_arr[$use_month],'2'); ?></td>
                                <?php } ?>
                                <td><?php echo $revpar_summary_full_year = number_format($revenue_sum/$full_rooms,'2'); ?></td>
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
                                           break;
                                       }
                                   }
                                    ?>
                                    </td>
                                <?php $cnt++; } ?>
                                    <td><?php echo $total_guest; ?></td>
                            </tr>
                            
                            <tr><td colspan="14">&nbsp;</td></tr>
                            <tr><td colspan="14" class="bold">Market Performance</td></tr>
                            <tr><td colspan="14" class="bold"><?php echo $market_performance; ?></td></tr>

                            <?php
                            $market_perf_arr[] = 'MPI';
                            $market_perf_arr[] = 'ARI';
                            $market_perf_arr[] = 'RGI';
                            $sum = '0';
                            
                            if($_SERVER['REMOTE_ADDR'] == '101.59.237.22'){
                              // echo '<pre>'; print_r($rev_arr);
                               //echo '------------------';
                               //print_r($days_month_arr);
                               //print_r($num_guest);
                               //echo '</pre>';
                            }
                            
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
                                                break;
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
                                       <?php $spent_par_val = @$rev_arr[$month]/($number_of_rooms * $days_month_arr[$month]);
                                       echo number_format($spent_par_val,'2');
                                       $spendPAR[$month] = round($spent_par_val,'2'); ?>
                                    </td>
                                <?php } ?>
                                <td><?php echo number_format(array_sum($spendPAR),'2'); ?></td>
                            </tr>
                            <tr>
                                <td class="bold">Spend/Guest</td>
                                <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <td>&nbsp;
                                    <?php echo number_format(@$rev_arr[$month]/@$num_guest[$month],'2');
                                    $spend[$month] = round(@$rev_arr[$month]/@$num_guest[$month],'2'); ?></td>
                                <?php } ?>
                                <td><?php echo number_format(array_sum($spend),'2'); ?></td>
                            </tr>
                            
                            <tr><td colspan="14">&nbsp;</td></tr>
                            <tr><td colspan="14" class="bold"><!-- Reputation Management --></td></tr>
                            <tr><td colspan="14">&nbsp;</td></tr>

                            <?php
                            $summary_arr[] = 'Occupied Rooms';
                            $summary_arr[] = 'Occupancy%';
                            $summary_arr[] = 'Revenue';
                            $summary_arr[] = 'ADR';
                            $summary_arr[] = 'RevPAR';
                            ?>
                            <tr>
                               <td class="bold">Var. to Budget</td>
                               <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"><?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                               <?php } ?>
                               <td class="bold"><!-- Full Year--></td>
                            </tr>
                            <?php
                            if($_SERVER['REMOTE_ADDR'] == '101.59.82.169'){
                          //echo '<pre>'; print_r($fcst_rooms_arr); exit;
                        }
                            ?>
                            <?php foreach($summary_arr as $summary_val){
                                $summary_val_sum_arr = array(); ?>
                                <tr>
                                    <td class="bold"><?php echo $summary_val; ?></td>
                                    <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                        <td><?php 
                                        if($summary_val == 'Occupied Rooms'){
                                             //Occ Rooms - ConfigD75
                                            $use_month =($month > '12')?$month -'12':$month;
                                            echo $summary_val_sum_arr[] = $fcst_rooms_arr[$use_month] - $cnfig_rn_arr1[$month];
                                            
                                        }elseif($summary_val == 'Occupancy%'){
                                            // Occ%- ConfigD78
                                             
                                            $use_month =($month > '12')?$month -'12':$month;
                                            
                                            echo $summary_val_sum_arr[] = $occ_per[$use_month] - $cnfig_occ_arr1[$month]; 
                                             
//                                            if($client_id == '100'){
//                                                $use_month =($month > '12')?$month -'12':$month;
//                                                echo $summary_val_sum_arr[] = $occ_per[$use_month] - $cnfig_occ_arr1[$month];
//                                            }else{
//                                                $occ_per_summary = round($fcst_rooms_arr[$month]/($number_of_rooms * $days_month_arr[$month]),'2');
//                                               //echo $summary_val_sum_arr[] = $fcst_rooms_arr[$month] - $cnfig_occ_arr1[$month];
//                                                echo $summary_val_sum_arr[] = $occ_per_summary - $cnfig_occ_arr1[$month];
//                                            }
                                        }elseif($summary_val == 'ADR'){
                                            // ADR - ConfigD76
                                             $summary_val_sum_arr[] = $fcst_adr_arr[$month] - $cnfig_adr_arr1[$month];
                                             echo number_format(($fcst_adr_arr[$month] - $cnfig_adr_arr1[$month]),'2');
                                        }elseif($summary_val == 'RevPAR'){
                                            // RevPar- ConfigD79
                                             $summary_val_sum_arr[] = round(@$fcst_revpar_arr[$month],'2') -$cnfig_revpar_arr1[$month];
                                             echo number_format((round(@$fcst_revpar_arr[$month],'2') -$cnfig_revpar_arr1[$month]),'2');
                                        }elseif($summary_val == 'Revenue'){
                                            //Revenue - ConfigD77
                                            $rev_val_sum = $rev_arr[$month] - $cnfig_revenue_arr1[$month];
                                            echo $summary_val_sum_arr[] = number_format($rev_val_sum);
                                        }
                                        ?></td>
                                    <?php } ?>
                                  <td>
                                        <?php 
//                                        if($summary_val == 'Occupied Rooms'){
//                                           echo $occupacy_roms_varbud = array_sum($summary_val_sum_arr);
//                                        }elseif($summary_val == 'Occupancy%'){
//                                           echo round($occupacy_roms_varbud/$full_rooms,'2');
//                                        }elseif($summary_val == 'ADR'){
//                                            echo number_format($revenue_revbud/$occupacy_roms_varbud,'2');
//                                        }elseif($summary_val == 'RevPAR'){
//                                            echo round($revpar_summary_full_year-(array_sum($cnfig_revpar_arr1)/12),'2');
//                                        }elseif($summary_val == 'Revenue'){
//                                           echo $revenue_revbud = array_sum($summary_val_sum_arr);
//                                        }
                                        ?>
                                    <?php //echo array_sum($summary_val_sum_arr); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr><td colspan="14">&nbsp;</td></tr>
                            
                            <tr>
                                <td  class="bold">Var. to LY</td>
                               <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                                <td class="bold"><!-- Full Year--></td>
                            </tr>
                            
                            <?php foreach($summary_arr as $summary_val){ 
                                $summary_val_sum_arr = array();
                                ?>
                                <tr>
                                    <td class="bold"><?php echo $summary_val; ?></td>
                                    <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                        <td><?php 
                                        if($summary_val == 'Occupied Rooms'){
                                            //echo '<pre>'; print_r($fcst_rooms_arr); print_r($rn_config_total); exit;
                                            
                                            //Occ Rooms - ConfigD170
                                            $use_month =($month > '12')?$month -'12':$month;
                                            echo $fcst_rooms_arr[$use_month] - $rn_config_total[$month];
                                        }elseif($summary_val == 'Occupancy%'){
                                                                                        // Occ%- ConfigD173
                                            $use_month =($month > '12')?$month -'12':$month;
                                            echo $occ_per[$use_month] - $config_occ_total[$month];
                                          // echo $fcst_rooms_arr[$month] - $config_occ_total[$month];
                                             

//                                            if($client_id == '100'){
//                                                echo $occ_per[$use_month] - $config_occ_total[$month];
//                                            }else{
//                                                $occ_per_summary = round($fcst_rooms_arr[$use_month]/($number_of_rooms * $days_month_arr[$month]),'2');
//                                                echo $occ_per_summary - $config_occ_total[$month];                                                
//                                            }
                                        }elseif($summary_val == 'ADR'){
                                            // ADR - ConfigD171
                                            echo number_format($fcst_adr_arr[$month] - $config_adr_total[$month],'2');
                                        }elseif($summary_val == 'RevPAR'){
                                            // RevPar- ConfigD174
                                            echo number_format(round(@$fcst_revpar_arr[$month],'2') -$config_revpar_total[$month],'2');
                                        }elseif($summary_val == 'Revenue'){
                                            //Revenue - ConfigD172
                                             $rev_val_sum = $rev_arr[$month] - $rev_total_array[$month];
                                             echo number_format($rev_val_sum);
                                        }
                                        ?></td>
                                    <?php } ?>
                                    <td>
                                    <?php 
//                                        if($summary_val == 'Occupied Rooms'){
//                                           echo $occupacy_roms_varbud = array_sum($summary_val_sum_arr);
//                                        }elseif($summary_val == 'Occupancy%'){
//                                           echo $sum_occoupacy_per - (array_sum($config_occ_total)/12);
//                                        }elseif($summary_val == 'ADR'){
//                                             echo number_format($sum_adr_per - (array_sum($config_adr_total)/12));
//                                        }elseif($summary_val == 'RevPAR'){
//                                           echo $revpar_summary_full_year - (array_sum($config_revpar_total)/12);
//                                        }elseif($summary_val == 'Revenue'){
//                                           echo $revenue_sum - (array_sum($rev_total_array)/12);
//                                        }
                                        ?>
                                    <?php //echo $full_rooms = $number_of_rooms * $days_in_month; ?></td>
                                </tr>
                            <?php } ?>
                            
                        </table>
                </div>
               </fieldset>

                <fieldset>
 		<legend><?php __('Market'); ?></legend>
                <div id="market_con_div">
                    <table class="table table-striped table-bordered table-hover">
                        <tr><td class="bold">Market Conditions - <?php
                        
                        if($gps_pack_id == '26' || $gps_pack_id == '27'){
                            echo 'Jan - Feb 2016';
                        }elseif($gps_pack_id == '50'){
                            echo 'Mar-Apr 2016';
                        }else{
                            echo date("F", mktime(0, 0, 0, $gps_month, 1));
                        }
                        ?></td></tr>
                        <tr><td>
                                <?php
                               foreach($step3Data as $step3_val){
                                   if($step3_val['GpsData']['question'] == '1'){
                                       //echo $step3_val['GpsData']['value'];
                                       echo "<pre><p style='font-family:Arial, Helvetica, sans-serif;font-size:12px;white-space: pre-wrap;width:85em;'>".$step3_val['GpsData']['value']."</p></pre>";
                               }
                               } ?>
                            </td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Market Conditions - <?php 
                        if($gps_pack_id == '26' || $gps_pack_id == '27'){
                            echo 'Mar - Aug 2016';
                        }elseif($gps_pack_id == '50'){
                            echo 'May-Aug 2016';
                        }else{
                            echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));
                        }
                        ?></td></tr>
                        <tr><td>
                             <?php
                               foreach($step3Data as $step3_val){
                                   if($step3_val['GpsData']['question'] == '2'){
                                       //echo $step3_val['GpsData']['value'];
                                       echo "<pre><p style='font-family:Arial, Helvetica, sans-serif;font-size:12px;white-space: pre-wrap;width:85em;'>".$step3_val['GpsData']['value']."</p></pre>";
                               }
                               } ?>
                            </td></tr>
                    </table>
                    </div>
                </fieldset>

                <fieldset>
 		<legend><?php __('Competition'); ?></legend>
                <div id="Competition_div">
                    <table class="table table-striped table-bordered table-hover">
                        <tr><td class="bold">Competitor Activity - <?php 
                        if($gps_pack_id == '26' || $gps_pack_id == '27'){
                            echo 'Jan - Feb 2016';
                        }elseif($gps_pack_id == '50'){
                            echo 'Mar-Apr 2016';
                        }else{
                            echo date("F", mktime(0, 0, 0, $gps_month, 1));
                        }
                        ?></td></tr>
                        <tr><td>
                            <?php
                               foreach($step4Data as $step4_val){
                                   if($step4_val['GpsData']['question'] == '1'){
                                       //echo htmlentities($step4_val['GpsData']['value']);
                                       echo "<pre><p style='font-family:Arial, Helvetica, sans-serif;font-size:12px;white-space: pre-wrap;width:85em;'>".$step4_val['GpsData']['value']."</p></pre>";
                                       //echo nl2br($step4_val['GpsData']['value']);
                                   }
                               } ?>
                            </td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Competitor Activity - <?php 
                        if($gps_pack_id == '26' || $gps_pack_id == '27'){
                            echo 'Mar - Aug 2016';
                        }elseif($gps_pack_id == '50'){
                            echo 'May-Aug 2016';
                        }else{
                            echo date("F", mktime(0, 0, 0, ($gps_month+1), 1));
                        }
                        ?></td></tr>
                        <tr><td>
                               <?php
                               foreach($step4Data as $step4_val){
                                   if($step4_val['GpsData']['question'] == '2'){
                                      // echo nl2br($step4_val['GpsData']['value']);
                                       echo "<pre><p style='font-family:Arial, Helvetica, sans-serif;font-size:12px;white-space: pre-wrap;width:85em;'>".$step4_val['GpsData']['value']."</p></pre>";
                               }
                               } ?>
                            </td></tr>
                    </table>
                </div>
                </fieldset>

                <fieldset>
 		<legend><?php __('Activity'); ?>&nbsp;<?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></legend>
                <div id="Activity1_div">
                    <table class="table table-striped table-bordered table-hover">
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
                    <table class="table table-striped table-bordered table-hover">
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
                    <table class="table table-striped table-bordered table-hover">
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

                    <table class="table table-striped table-bordered table-hover">
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
                    
                    <table class="table table-striped table-bordered table-hover">
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
                <div id="Activity2_div">
                    <table class="table table-striped table-bordered table-hover">
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
                    <table class="table table-striped table-bordered table-hover">
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
                    <table class="table table-striped table-bordered table-hover">
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

                    <table class="table table-striped table-bordered table-hover">
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
                    
                    <table class="table table-striped table-bordered table-hover">
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
 		<legend><?php __('Activity '); ?><?php echo date("F", mktime(0, 0, 0, ($next2_month), 1));  ?></legend>
                <div id="Activity3_div">
                    
                    <table class="table table-striped table-bordered table-hover">
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
                    <table class="table table-striped table-bordered table-hover">
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
                    <table class="table table-striped table-bordered table-hover">
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

                    <table class="table table-striped table-bordered table-hover">
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
                    <table class="table table-striped table-bordered table-hover">
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
                <div id="Activity4_div">
                   
                    <table class="table table-striped table-bordered table-hover">
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
                    <table class="table table-striped table-bordered table-hover">
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
                    <table class="table table-striped table-bordered table-hover">
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

                    <table class="table table-striped table-bordered table-hover">
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
                    
                    <table class="table table-striped table-bordered table-hover">
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
                <div id="Producers_div">
                    <table class="table table-striped table-bordered table-hover">
                        <tr><td colspan="6" class="bold">
                                <?php if($client_id == '100'){
                                    echo 'Tour Operator';
                                }else{
                                    echo 'Corporate';
                                } ?>
                                
                            </td></tr>
                        <tr><td>&nbsp;</td>
                            <td class="bold">Name</td>
                            <td class="bold"> Room Nights</td>
                            <td class="bold">ADR</td>
                            <td class="bold">Av. Spend</td>
                            <td class="bold">Total Revenue</td>
                        </tr>
                        <?php for($count=1;$count <= 10; $count++){ 
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                        <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('1' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('2' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('3' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('4' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('5' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    
                    <table class="table table-striped table-bordered table-hover">
                        <tr><td colspan="6" class="bold">Travel Agents</td></tr>
                        <tr><td>&nbsp;</td>
                            <td class="bold">Name</td>
                            <td class="bold">Room Nights</td>
                            <td class="bold">ADR</td>
                            <td class="bold">Av. Spend</td>
                            <td class="bold">Total Revenue</td>
                        </tr>
                        <?php
                        for($count=1;$count <= 10; $count++){ 
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                        <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('1' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('2' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('3' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('4' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } ?></td>
                            <td><?php foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('5' + ('5' * ($count-1))))){
                                           echo $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
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
                <div id="market_seg_div">
                    <!-- Autofill-->
                    <?php if(!empty($marketsegments)){ ?>
                        <table class="table table-striped table-bordered table-hover">
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
 		<legend><?php __('BOB - '); ?><?php echo date("F", mktime(0, 0, 0, ($next_month), 1));  ?></legend>
                <div id="bob1_div">
                    <!-- Autofill-->
                    <?php if(!empty($marketsegments)){ ?>
                        <table class="table table-striped table-bordered table-hover">
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
                                $bob_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$gps_month.'/'.$year.'/62');
                                $adr_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$gps_month.'/'.$year.'/64');
                                
                                $bob_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next_month.'/'.$year_for_next.'/62');
                                $adr_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next_month.'/'.$year_for_next.'/64');
                                
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
                <div id="bob2_div">
                    <!-- Autofill-->
                    <?php if(!empty($marketsegments)){
                        
                        $bob_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next_month.'/'.$year_for_next.'/62');
                        $adr_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next_month.'/'.$year_for_next.'/64');

                        if($_SERVER['REMOTE_ADDR'] == '101.57.71.225'){
                           // echo '/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next2_month.'/'.$year_for_next2.'/62';
                        }
                        
                        $bob_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next2_month.'/'.$year_for_next2.'/62');
                        $adr_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next2_month.'/'.$year_for_next2.'/64');
                        
                        ?>
                        <table class="table table-striped table-bordered table-hover">
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
                <div id="bob3_div">
                    <!-- Autofill-->
                    <?php if(!empty($marketsegments)){ 
                        
                        //$present_month = $gps_month+'3' >= '13' ? ($gps_month-'12') : $gps_month;
                        $present_month = $gps_month+'3' >= '13' ? (($gps_month+'3') - '12') : $gps_month+'3';
                        
                        $year_for_present = $gps_month+'3' >= '13' ? $year+ '1' : $year;

                        //$prior_month = $gps_month+'2' >= '13' ? ($gps_month-'12') : $gps_month;
                        $prior_month = $gps_month+'2' >= '13' ? (($gps_month+'2') - '12') : $gps_month+'2';
                        $year_for_prior = $gps_month+'2' >= '13' ? $year+ '1' : $year;

                        //$next2_month = ($gps_month+'2' >= '13') ? ($gps_month-'12') : $gps_month+'2';
                        
                        $prior_month = sprintf("%02d", $prior_month);
                        $present_month = sprintf("%02d", $present_month);
                        
                        $bob_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/62');
                        $adr_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/64');

                        $bob_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$present_month.'/'.$year_for_present.'/62');
                        $adr_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$present_month.'/'.$year_for_present.'/64');
                        
                       // if($_SERVER['REMOTE_ADDR'] == '101.57.0.174'){
//                            echo 'GPS Month'.$gps_month.'<br/>';
//                         echo '/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$present_month.'/'.$year_for_present.'/62<br/>';
                          // echo 'Year:Month for Present'.$present_month.':'.$year_for_present.'<br/>';
////                        print_R($bob_present_month); print_r($adr_present_month);
                      //  }
                        
                        ?>
                        <table class="table table-striped table-bordered table-hover">
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
 		<legend><?php __('Channels Year'); ?></legend>
                <div id="Channel_yr_div">
                    <table class="table table-striped table-bordered table-hover">
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
                                       $channels_tyd_actual_arr[$gd_val]['RN'][] = $step15_val['GpsData']['value'];
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
                                       echo round($step15_val['GpsData']['value'],'2');
                                       $channels_tyd_actual_arr[$gd_val]['ADR'][] = $step15_val['GpsData']['value'];
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
                            <td>  <?php  echo round(@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR'],'2'); ?></td>
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
                                       $channels_tyd_actual_arr[$online_arr_val]['RN'][] = $step15_val['GpsData']['value'];
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
                                       echo round($step15_val['GpsData']['value'],'2');
                                       $channels_tyd_actual_arr[$online_arr_val]['ADR'][] = $step15_val['GpsData']['value'];
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
                             <td> <?php  echo round(@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR'],'2'); ?></td>
                            <?php } ?>
                        </tr>
                        <?php $loop_count++; } ?>
                        
                        <tr><td colspan="14">&nbsp;</td></tr>                        

                        <tr><td colspan="14"><b>
                                <?php if($client_id == '100'){ ?>
                                   Source of Business
                               <?php }else{ ?>
                                   Direct
                               <?php } ?>
                                </b></td></tr>
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
                            <td class="bold">
                            <?php if($client_id == '100' && ($direct_arr_val == 'Walkin')){ ?>
                                   Walkin/VO/FO
                               <?php }else{
                                   echo $direct_arr_val;
                                } ?>
                            
                            <?php //echo $direct_arr_val; ?></td>
                            <td class="bold">RN</td>

                            <?php $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
//($month + ($loop_count*24))
                                ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Direct') && ($step15_val['GpsData']['sub_text'] == $direct_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       echo $step15_val['GpsData']['value'];
                                       $channels_tyd_actual_arr[$direct_arr_val]['RN'][] = $step15_val['GpsData']['value'];
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
                                       echo round($step15_val['GpsData']['value'],'2');
                                       $rev_arr[$month]['ADR'] = $step15_val['GpsData']['value'];
                                       $channels_tyd_actual_arr[$direct_arr_val]['ADR'][] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                             <td><?php   echo round(@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR'],'2'); ?></td>
                            <?php } ?>
                        </tr>
                        <?php $loop_count++; }
                        $rev_arr = array();
                        ?>
                        
                        <tr>
                            <?php if($client_id == '100'){ ?>
                                   <td class="bold">CRS</td>
                               <?php }else{ ?>
                                   <td class="bold">CRO</td>
                               <?php } ?>
                                   
                            <td class="bold">RN</td>
                            <?php $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            ?>
                            <td><?php
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'CRO')  && ($step15_val['GpsData']['question'] == $cnt)){
                                       echo $step15_val['GpsData']['value'];
                                       $channels_tyd_actual_arr['CRO']['RN'][] = $step15_val['GpsData']['value'];
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
                                       $channels_tyd_actual_arr['CRO']['ADR'][] = $step15_val['GpsData']['value'];
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
 		<legend><?php __('Channels - '); ?><?php echo date("F", mktime(0, 0, 0, $gps_month, 1));  ?></legend>
                <div id="Channels_div">
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td colspan="2" class="bold">GDS</td>
                            <td class="bold">Prior Month</td>
                            <td class="bold">Month</td>
                            <td class="bold">Budget</td>
                            <td class="bold">Last Year</td>
                            <td class="bold">YTD Actual</td>
                            <td class="bold">YTD Budget</td>
<!--                            <td class="bold">Var.</td>-->
                        </tr>
                        
                        <?php
                        $gds_arr['1'] = 'Sabre';
                        $gds_arr['2'] = 'Amadeus';
                        $gds_arr['3'] = 'Galileo';
                        $gds_arr['4'] = 'Worldspan';
                        
                        $channels_vals['Sabre'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Sabre/GDS/14');
                        $channels_vals['Amadeus'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Amadeus/GDS/14');
                        //$channels_vals['Appollo'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Appollo/GDS/14');
                        $channels_vals['Galileo'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Galileo/GDS/14');
                        $channels_vals['Worldspan'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Worldspan/GDS/14');
                        
                        ?>
                        
                        <?php 
                        $prior_month = ($gps_month-'1') == '0' ? '12' : $gps_month-'1';
                        //$prior_month = $month-'1' == '0' ? '12' : $month-'1';
                        $year_for_prior = $month-'1' == '0' ? $year- '1' : $year;
                        $loop_count = '0';
                        foreach($gds_arr as $gd_key=>$gd_val){
                            
                        ?>
                        <tr>
                            <td class="bold"><?php echo $gd_val; ?></td>
                            <td class="bold">RN</td>
                            <td><?php echo @$channels_vals[$gd_val]['1' + ($loop_count*8)]; ?></td>
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
                            <td><?php echo array_sum($channels_tyd_actual_arr[$gd_val]['RN']); ?></td>
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
                            <td><?php echo @$channels_vals[$gd_val]['5' + ($loop_count*8)]; ?></td>
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
                            <td><?php echo array_sum($channels_tyd_actual_arr[$gd_val]['ADR']); ?></td>
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
                        
                        $channels_vals['Website'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Website/Online/14');
                        $channels_vals['OTA'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/OTA/Online/14');
                        
                        ?>
                        
                        <?php  $loop_count = '0';
                        foreach($online_arr as $online_arr_key=>$online_arr_val){ 
                             
                        ?>
                        <tr>
                            <td class="bold"><?php echo $online_arr_val; ?></td>
                            <td class="bold">RN</td>
                            <td><?php echo @$channels_vals[$online_arr_val]['1' + ($loop_count*8)]; ?></td>
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
                            <td><?php echo array_sum($channels_tyd_actual_arr[$online_arr_val]['RN']); ?></td>
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
                            <td><?php echo @$channels_vals[$online_arr_val]['5' + ($loop_count*8)]; ?></td>
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
                            <td><?php echo array_sum($channels_tyd_actual_arr[$online_arr_val]['ADR']); ?></td>
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

                        <tr><td colspan="8" class="bold">
                                
                                <?php if($client_id == '100'){ ?>
                                   Source of Business
                               <?php }else{ ?>
                                   Direct
                               <?php } ?>
                                
                            </td></tr>
                        <?php 
                        $direct_arr['1'] = 'Phone';
                        $direct_arr['2'] = 'Email/Fax';
                        $direct_arr['3'] = 'Walkin';
                        
                        $channels_vals['Phone'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Phone/Direct/14');
                        $channels_vals['Email/Fax'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Email_Fax/Direct/14');
                        $channels_vals['Walkin'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Walkin/Direct/14');                        
                        ?>
                        
                        <?php $loop_count = '0';
                        foreach($direct_arr as $direct_arr_key=>$direct_arr_val){ 

                        ?>
                           <tr>
                            <td class="bold">
                            <?php 
                                if($client_id == '100' && ($direct_arr_val == 'Walkin')){ ?>
                                   Walkin/VO/FO
                               <?php }else{
                                   echo $direct_arr_val;
                                } ?>
                            </td>
                            <td class="bold">RN</td>
                            <td><?php echo @$channels_vals[$direct_arr_val]['1' + ($loop_count*8)]; ?></td>
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
                            <td><?php echo array_sum($channels_tyd_actual_arr[$direct_arr_val]['RN']); ?></td>
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
                            <td><?php echo @$channels_vals[$direct_arr_val]['5' + ($loop_count*8)]; ?></td>
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
                                       echo round($step14_val['GpsData']['value'],'2');
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
                            <td><?php echo round(array_sum($channels_tyd_actual_arr[$direct_arr_val]['ADR']),'2'); ?></td>
                            <td><?php
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('8' + ($loop_count*8)))){
                                       echo $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } ?></td>
<!--                            <td></td>-->
                        </tr>
                        <?php $loop_count++; } 
                        
                        $channels_vals['CRO'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/0/CRO/14');
                        ?>
                        

                           <tr>
                            <?php if($client_id == '100'){ ?>
                                   <td class="bold">CRS</td>
                               <?php }else{ ?>
                                   <td class="bold">CRO</td>
                               <?php } ?>
                                   
                            <td class="bold">RN</td>
                            <td><?php echo @$channels_vals['CRO']['1']; ?></td>
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
                            <td><?php echo array_sum($channels_tyd_actual_arr['CRO']['RN']); ?></td>
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
                            <td><?php echo @$channels_vals['CRO']['5']; ?></td>
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
                            <td><?php echo array_sum($channels_tyd_actual_arr['CRO']['ADR']); ?></td>
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
 		<legend>
                <?php if($client_id == '100'){ ?>
                  Country of Origin
               <?php }else{
                 __('Geo Year'); 
                } ?> 
                </legend>
                <div id="geo_yr_div">
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                               <td class="bold">Total</td>
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
                            <?php $cnt = '1';$sum_total_rn = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            ?>
                            <td><?php 
                               foreach($step16Data as $step16_key=>$step16_val){
                                   if(($step16_val['GpsData']['text'] == $country_val)  && ($step16_val['GpsData']['question'] == $cnt)){
                                       echo $sum_total_rn[$month] = $step16_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step16_val['GpsData']['value'];
                                       unset($step16Data[$step16_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                            <td><?php array_walk( $sum_total_rn, function( &$el) { $el = str_replace( ',', '', $el); });
                            echo array_sum($sum_total_rn); ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php $cnt = '1';$sum_total_adr = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            ?>
                            <td><?php 
                               foreach($step16Data as $step16_key=>$step16_val){
                                   if(($step16_val['GpsData']['text'] == $country_val)  && ($step16_val['GpsData']['question'] == ($cnt + '12'))){
                                       echo $sum_total_adr[$month] = $step16_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step16_val['GpsData']['value'];
                                       unset($step16Data[$step16_key]);
                                   }
                               } ?></td>
                            <?php $cnt++; } ?>
                            <td><?php array_walk( $sum_total_adr, function( &$el) { $el = str_replace( ',', '', $el); });
                            echo array_sum($sum_total_adr); ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php $sum_total_rev = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                            <td><?php  echo $sum_total_rev[$month] = (str_replace( ',', '', @$rev_arr[$month]['RN']) * str_replace( ',', '', @$rev_arr[$month]['ADR'])); ?></td>
                            <?php } ?>
                            <td><?php  array_walk( $sum_total_rev, function( &$el) { $el = str_replace( ',', '', $el); });
                            echo array_sum($sum_total_rev); ?></td>
                        </tr>
                        <?php } ?>
                        
<!--                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Total</td>
                            <?php $sum_total_total = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                            <td><?php  echo $sum_total_total[] = array_sum($sum_total_rn[$month]) + array_sum($sum_total_adr[$month]) + array_sum($sum_total_rev[$month]); ?></td>
                            <?php } ?>
                            <td><?php echo array_sum($sum_total_total); ?></td>
                        </tr>-->
                        
                </table>
                    
                    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend>
                    <?php if($client_id == '100'){ ?>
                           Prev Year
                       <?php }else{
                           echo 'Prov Year';
                        } ?>       
                        <?php //__('Prov Year'); ?>
                </legend>
                <div id="prov_yr_div">
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                        </tr>
                        <?php 
//                        $country_arr = array();
//                        $country_arr['1'] = 'Eastern Cape';
//                        $country_arr['2'] = 'Free State';
//                        $country_arr['3'] = 'Gauteng';
//                        $country_arr['4'] = 'Kwa-Zulu Natal';
//                        $country_arr['5'] = 'Limpopo';
//                        $country_arr['6'] = 'Mpumalanga';
//                        $country_arr['7'] = 'Northern Cape';
//                        $country_arr['8'] = 'North West';
//                        $country_arr['9'] = 'Western Cape';
//                        $country_arr['10'] = 'Other';
                        $country_arr = array();
                        $country_arr = explode(',',$gps_settings['GpsSetting']['geo_list']);
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
                                       break;
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
                                       break;
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
               <div id="roomtype_div">
                    <?php 
                    $roomType_arr[$room_name[0]] = 'Standard';
                    $roomType_arr[$room_name[1]] = 'Executive';
                    $roomType_arr[$room_name[2]] = 'Deluxe';
                    $roomType_arr[$room_name[3]] = 'Suite';
                    $roomType_arr[$room_name[4]] = 'Other';
                    ?>
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>Actual</td>
                            <td>Prior Month</td>
                            <td>Prior Year</td>
                            <td>
                                <?php if($client_id == '100'){
                                    echo 'Average RACK';
                                }else{
                                    echo 'BAR A';
                                } ?>
                            </td>
                            <td>Potential</td>
                            <td>Utilisation</td>
                        </tr>
                        <?php foreach($roomType_arr as $roomType_key=>$roomType_arr_val){
                            $adr_val = ''; $bar_val = ''; $bara_val = '';
                            
                            $roomTypeData = $this->requestAction('/GpsPacks/get_roomtype_values/'.$prior_month.'/'.$roomType_arr_val.'/'.$client_id.'/'.$gps_pack_id.'/'.$year_for_prior);
                            $prior_month = ($gps_month-'1') == '0' ? '12' : $gps_month-'1';
                            if($_SERVER['REMOTE_ADDR'] == '123.239.174.11'){
                               // echo '/GpsPacks/get_roomtype_values/'.$prior_month.'/'.$roomType_arr_val.'/'.$client_id.'/'.$gps_pack_id.'/'.$year_for_prior;
                            }
                           // echo '<pre>'; print_r($roomTypeData); echo '</pre>';
                            ?>
                        <tr>
                            <td class="bold"><?php echo $roomType_key; ?></td>
                            <td class="bold">RN</td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '1')){
                                       echo $bar_val = $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                            <td><?php echo @$roomTypeData['RN'][$prior_month]; ?></td>
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
                                       echo $bara_val = $step18_val['GpsData']['value'];
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
                            <td><?php echo @$roomTypeData['ADR'][$prior_month]; ?></td>
                            <td><?php
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '5')){
                                       echo $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               } ?></td>
                            <td>&nbsp;</td>
                            <td><?php  
                            $adr_val = str_replace(',','',$adr_val);
                            $bara_val = str_replace(',','',$bara_val);
                            $bar_val = str_replace(',','',$bar_val);
                            
                            echo round((($adr_val/$bara_val)* 100),'0').'%';  ?></td>
                            <td><?php echo round($bar_val/($roomValues[$roomType_arr_val] * $days_in_month),'2')*'100'.'%'; ?></td>
                        </tr>
                        <?php } ?>
                </table>
                    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Room Type Year'); ?></legend>
                <div id="roomtype_yr_div">
                    <!-- Autofill-->
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td>&nbsp;</td><td>&nbsp;</td>
                             <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                             <td class="bold"><?php echo date("M", mktime(0, 0, 0, $month, 1)); ?></td>
                             <?php } ?>
                        </tr>
                        
                        <?php
                        foreach($roomType_arr as $roomType_key=>$roomType_arr_val){
                            $roomTypeData = $this->requestAction('/GpsPacks/get_roomtype_values/year/'.$roomType_arr_val.'/'.$client_id.'/'.$gps_pack_id.'/'.$year);
                            //echo '<pre>'; print_r($roomTypeData); echo '</pre>';
                            
                        ?>
                        
                        <tr>
                            <td class="bold"><?php echo $roomType_key; ?></td>
                            <td class="bold">RN</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                $use_month =($month > '12')?$month -'12':$month;
                                ?>
                            <td>&nbsp;<?php echo @$roomTypeData['RN'][$use_month]; ?></td>    
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                $use_month =($month > '12')?$month -'12':$month; ?>
                            <td>&nbsp;<?php echo @$roomTypeData['ADR'][$use_month]; ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                $use_month =($month > '12')?$month -'12':$month; ?>
                              <td>&nbsp;<?php  echo (@$roomTypeData['RN'][$use_month] * @$roomTypeData['ADR'][$use_month]); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                </table>
                    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Future Activity'); ?></legend>
                <div id="future_div">
                    <table class="table table-striped table-bordered table-hover">
                        <tr><td class="bold">Expected Market Conditions</td></tr>
                        <tr><td><?php  foreach ($step20Data as $step20_data) {
                            if($step20_data['GpsData']['question'] == '1'){
                                //echo $step20_data['GpsData']['value'];
                                echo "<pre><p style='font-family:Arial, Helvetica, sans-serif;font-size:12px;white-space: pre-wrap;width:85em;'>".$step20_data['GpsData']['value']."</p></pre>";
                            }
                        } ?></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Planned Activity, by Segment</td></tr>
                        <tr><td><?php  foreach ($step20Data as $step20_data) {
                            if($step20_data['GpsData']['question'] == '2'){
                                //echo $step20_data['GpsData']['value'];
                                echo "<pre><p style='font-family:Arial, Helvetica, sans-serif;font-size:12px;white-space: pre-wrap;width:85em;'>".$step20_data['GpsData']['value']."</p></pre>";
                            }
                        } ?></td></tr>
                    </table>
               </div>
                </fieldset>
                
                <fieldset>
 		<legend><?php __('Reputation'); ?></legend>
                <div id="Reputation_div">
                    <table class="table table-striped table-bordered table-hover">
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
                        $reputation_arr['Expedia Ranking'][] = 'Commission Paid';
                        
                        if($client_id != '100'){
                        $reputation_arr['Safarinow.com'][] = 'Room Nights';
                        $reputation_arr['Safarinow.com'][] = 'Rev (Exc.)';
                        $reputation_arr['Safarinow.com'][] = 'Rev (Inc.)';
                        $reputation_arr['Safarinow.com'][] = 'Commission %';
                        $reputation_arr['Safarinow.com'][] = 'Commission Paid';
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
                            $reputation_arr['Travel Ground'][] = 'Commission Paid';
                        }
                        
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
                
                <fieldset>
                    <?php //echo '<pre>'; print_r($step22Data); echo '</pre>'; ?>
 		<legend><?php __('Config'); ?></legend>
                    <?php echo $config_html; ?>
                </fieldset>

</div>
<script>
function print_report(){
    var prtContent = document.getElementById("GpsDiv");
    var WinPrint = window.open('', '', 'left=0,top=0,width=1000,height=1000,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write('<link rel="stylesheet" type="text/css" href="http://beta.myrevenuedashboard.net/css/bootstrap.min.css" />');
    WinPrint.document.write('<link rel="stylesheet" type="text/css" href="http://beta.myrevenuedashboard.net/css/ace.min.css" />');
    
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}
</script>