<?php
ob_start();
App::import('Vendor','tcpdf');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8");

$pdf->SetCreator(PDF_CREATOR);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetHeaderMargin(-1);
$pdf->SetFooterMargin(-2);
$textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans'

$pdf->SetAuthor("Revenue Performance at www.myrevenuedashboard.net");
$pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);
$pdf->setHeaderFont(array($textfont,'',8));
$pdf->xheadercolor = array(150,0,0);
$pdf->xheadertext = 'Selected ';
$pdf->xfootertext = "Copyright &copy; Revenue Performance. All rights reserved.";

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set auto page breaks 
$pdf->SetAutoPageBreak(true);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

// add a page (required with recent versions of tcpdf)
$pdf->AddPage();
$pdf->SetAutoPageBreak(true);

$pdf->SetFillColorArray(array(255, 255, 255));
$pdf->SetTextColor(0, 0, 0);

if(!empty($hotel_data['Client']['logo']))
{
	$ext = pathinfo($hotel_data['Client']['logo'], PATHINFO_EXTENSION);
	if($ext == "png" || $ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "bmp")
	{
		$exts = findexts($hotel_data['Client']['logo']);	// Image example
		$imgPath = WWW_ROOT.'files'.DS.'clientlogos'.DS.$hotel_data['Client']['logo'];
		$pdf->Image($imgPath, 245,32, 40, 14, $exts, '', '', true, 150);
	}
}
$pdf->SetXY(125,15);
$pdf->writeHTML($hotel_data['Client']['hotelname'], true, false, true, false, '');
?>
<link rel="stylesheet" type="text/css" href="/css/styles.css" />
<style type="text/css">
table{ color: #6B6F6F; } 
input { font-size:100%; border:1px solid #ccc; }
input[readonly=readonly]{ background: #BDBDBD; border:1px solid #BDBDBD; }
table tr td{ background:none; font-size:12px; padding:3px; }
table tr:nth-child(2n) td{ background:none; }
table tr:nth-child(2n) td.content_head{ background:#BDBDBD; }
.bold{ font-weight: bold; }
.admin_left_pannel { float: left; padding: 3px; position: absolute; width: 15%; z-index: 1001; }
#GpsDiv{ width: 100%; border-left:none; }
</style>
<?php
$write_html = '<div class="Gps form" id="GpsDiv">';

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
$fsct_col_id = '63'; //For Occupied Rooms in Summary Tab
$fcst_rooms_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/'.$fsct_col_id.'/'.$financial_month.'/'.$year); 

$fcst_adr_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/65/'.$financial_month.'/'.$year); //For ADR in Summary Tab
$fcst_revpar_arr = $this->requestAction('/GpsPacks/get_webform_monthly_score/'.$client_id.'/70/'.$financial_month.'/'.$year); //For RevPAR in Summary Tab

$channels_tyd_actual_arr = array();
$prior_month = ($gps_month-'1') == '0' ? '12' : $gps_month-'1';
$year_for_prior = ($gps_month-'1') == '0' ? $year- '1' : $year;
$next2_month = ($gps_month+'2' >= '13') ? ($gps_month-'12') : $gps_month+'2';
$year_for_next2 = ($gps_month+'2' >= '13') ? $year+ '1' : $year;
$next_month = ($gps_month+'1' >= '13') ? ($gps_month-'12') : $gps_month+'1';
$year_for_next = ($gps_month+'1' >= '13') ? $year+ '1' : $year;

    
	$write_html .= '<fieldset>
 		<legend>View GPS Pack Report</legend>
                <fieldset>
 		<legend>Config</legend>
                    <div id="config_div">
                    <table>
                        <tr>
                            <td colspan="2" class="bold">Market Seg Names</td>
                            <td colspan="12" class="bold">Budget</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                               $write_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                            }
                        $write_html .= '</tr>';
                        
                        $segment_vals_total_arr = array(); $config_seg_vals = array();
                        foreach($marketsegments as $segment_key=>$segment_val){ 
                            $rn_arr = array(); $adr_arr = array();
                           
                        $write_html .= '<tr>
                            <td class="bold">'.$segment_val.'</td>
                            <td class="bold">RN</td>';
                            $cnt ='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            
                            $write_html .= '<td>';
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == $cnt)){
                                      $write_html .= $rn_arr[$month] = $step22_val['GpsData']['value'];
                                       $segment_vals_total_arr['RN'][$month][] = $step22_val['GpsData']['value'];
                                       $config_seg_vals[$segment_val]['Budget-RN'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               } 
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>';
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Budget') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       $write_html .= $adr_arr[$month] = $step22_val['GpsData']['value'];
                                       $segment_vals_total_arr['ADR'][$month][] = $step22_val['GpsData']['value'];
                                       $config_seg_vals[$segment_val]['Budget-ADR'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               } 
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>'.$segment_vals_total_arr['Rev'][$month][] = (@$rn_arr[$month] * @$adr_arr[$month]).'</td>';
                            }
                        $write_html .= '</tr>';
                        }
                        $write_html .= '<tr>
                            <td class="bold">Total</td>
                            <td class="bold">RN</td>';
                            $cnfig_rn_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>'.$cnfig_rn_arr1[$month] = array_sum($segment_vals_total_arr['RN'][$month]).'</td>';
                            }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnfig_adr_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>'.$cnfig_adr_arr1[$month] = round(array_sum($segment_vals_total_arr['Rev'][$month])/array_sum($segment_vals_total_arr['RN'][$month]),'2').'</td>';
                            }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            $rev_total_array = array(); $cnfig_revenue_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>'.$rev_total_array[$month] = round(array_sum($segment_vals_total_arr['Rev'][$month]),'2').'</td>';
                            $cnfig_revenue_arr1[$month] = $rev_total_array[$month];
                            }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Occ%</td>';
                            $cnfig_occ_arr1=array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                $days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            $write_html .= '<td>'.$cnfig_occ_arr1[$month] = round(array_sum($segment_vals_total_arr['RN'][$month])/($number_of_rooms * $days_in_each_month)*100,'2').'</td>';
                            }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">RevPAR</td>';
                            $cnfig_revpar_arr1 = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                $days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                $write_html .= '<td>'.$cnfig_revpar_arr1[$month] = round($rev_total_array[$month]/($number_of_rooms * $days_in_each_month),'2').'</td>';
                            }
                        $write_html .= '</tr>
                </table>
                <table>
                        <tr>
                            <td colspan="2" class="bold">Market Seg Names</td>
                            <td colspan="12" class="bold">Last Year</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                            }
                        $write_html .= '</tr>';

                        $segment_vals_total_arr = array();
                        foreach($marketsegments as $segment_key=>$segment_val){
                            $rn_arr = array(); $adr_arr = array();
                        
                        $write_html .= '<tr>
                            <td class="bold">'.$segment_val.'</td>
                            <td class="bold">RN</td>';
                            $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>';
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Last Year') && ($step22_val['GpsData']['question'] == $cnt)){
                                       $write_html .=  $rn_arr[$month]= $step22_val['GpsData']['value'];
                                       $config_seg_vals[$segment_val]['LastYear-RN'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               }
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>';
                               foreach($step22Data as $step22_key=>$step22_val){
                                   if(($step22_val['GpsData']['text'] == $segment_val) && ($step22_val['GpsData']['sub_text'] == 'Last Year') && ($step22_val['GpsData']['question'] == ($cnt + '12'))){
                                       $write_html .= $adr_arr[$month] = $step22_val['GpsData']['value'];
                                       $config_seg_vals[$segment_val]['LastYear-ADR'][$month] = $step22_val['GpsData']['value'];
                                       unset($step22Data[$step22_key]);
                                   }
                               }
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>'.$segment_vals_total_arr['Rev'][$month][] = (@$rn_arr[$month] * @$adr_arr[$month]).'</td>';
                            }
                        $write_html .= '</tr>';
                        }
                        $write_html .= '<tr>
                            <td class="bold">Total</td>
                            <td class="bold">RN</td>';
                            $rn_config_total =array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>'.$rn_config_total[$month] =  array_sum($segment_vals_total_arr['RN'][$month]).'</td>';
                            }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $config_adr_total=array ();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>'.$config_adr_total[$month] = round(array_sum($segment_vals_total_arr['Rev'][$month])/array_sum($segment_vals_total_arr['RN'][$month]),'2').'</td>';
                            }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            $rev_total_array = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>'.$rev_total_array[$month] = array_sum($segment_vals_total_arr['Rev'][$month]).'</td>';
                            }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Occ%</td>';
                            $config_occ_total = array();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                $days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                $write_html .= '<td>'.$config_occ_total[$month] = round(array_sum($segment_vals_total_arr['RN'][$month])/($number_of_rooms * $days_in_each_month)*100,'2').'</td>';
                            }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">RevPAR</td>';
                            $config_revpar_total=array ();
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                $days_in_each_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                $write_html .= '<td>'.$config_revpar_total[$month] = round($rev_total_array[$month]/($number_of_rooms * $days_in_each_month),'2').'</td>';
                            }
                        $write_html .= '</tr>
                </table>
                </div> 
                </fieldset>

                <fieldset>
 		<legend>GM Summary</legend>
                <div id="gmsummary_div">';
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
                $write_html .= '<table>
                    <tr><td class="bold">How has your hotel performed against the competitor set/market set in STR?</td></tr>
                    <tr><td>'.$answer1.'</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">Where is your opportunity to improve performance in the month ahead?</td></tr>
                    <tr><td>&nbsp;'.$answer2.'</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">What is your synopsis of your market segmentation performance?</td></tr>
                    <tr><td>&nbsp;'.$answer3.'</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">What channel performance objectives do you have for the month ahead?</td></tr>
                    <tr><td>&nbsp;'.$answer4.'</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td class="bold">Which three Sales Accounts are presenting the largest opportunity for you?</td></tr>
                    <tr><td>&nbsp;'.$answer5.'</td></tr>
                </table>
                </div>
                </fieldset>

                <fieldset>
 		<legend>Summary</legend>
                <div id="summary_div">
                        <table>
                            <tr>
                                <td>&nbsp;</td>';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                                }
                                $write_html .= '<td class="bold">Full Year</td>
                            </tr>
                            <tr><td colspan="14">&nbsp;</td></tr>
                            <tr>
                                <td class="bold">Rooms in Hotel</td>';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td>'.$number_of_rooms.'</td>';
                                }
                                $write_html .= '<td>'.$full_rooms = $number_of_rooms * $days_in_month.'</td>
                            </tr>
                            <tr>
                                <td class="bold">Occupied Rooms</td>';
                                
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td>&nbsp;'.@$fcst_rooms_arr[$month].'</td>';
                                }
                                $write_html .= '<td>'.$occupied_sum = array_sum(@$fcst_rooms_arr).'</td>
                            </tr>
                            <tr>
                                <td class="bold">Occupancy%</td>';
                                $occ_per=array(); for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td>&nbsp;'.$occ_per[$month] = round($fcst_rooms_arr[$month]/( $number_of_rooms * $days_in_month),'2').'</td>';
                                }
                                $write_html .= '<td>'.round(array_sum($occ_per)/$full_rooms,'2').'</td>
                            </tr>
                            <tr>
                                <td class="bold">Revenue</td>';
                                $rev_arr=array();
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td>&nbsp;'.$rev_arr[$month] = @$fcst_rooms_arr[$month] * @$fcst_adr_arr[$month].'</td>';
                                }
                                $write_html .= '<td>'.$revenue_sum = array_sum($rev_arr).'</td>
                            </tr>
                            <tr>
                                <td class="bold">ADR</td>';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td>&nbsp;'.@$fcst_adr_arr[$month].'</td>';
                                }
                                $write_html .= '<td>'.round(@$revenue_sum/@$occupied_sum,'2').'</td>
                            </tr>
                            <tr>
                                <td class="bold">RevPAR</td>';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td>&nbsp;'.round(@$fcst_revpar_arr[$month],'2').'</td>';
                                }
                                $write_html .= '<td>'.round($revenue_sum/$full_rooms,'2').'</td>
                            </tr>
                            <tr>
                                <td class="bold">Number of Guests</td>';
                                $num_guest = array(); $total_guest = '0'; $cnt ='1';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td>';
                                   foreach($step2Data as $step2_key=>$step2_val){
                                       if(($step2_val['GpsData']['text'] == 'Summary') && ($step2_val['GpsData']['question'] == $cnt)){
                                           $write_html .= $num_guest[$month] = $step2_val['GpsData']['value'];
                                           $total_guest = $total_guest + $step2_val['GpsData']['value'];
                                           unset($step2Data[$step2_key]);
                                       }
                                   }
                                    $write_html .= '</td>';
                                $cnt++; }
                                    $write_html .= '<td>'.$total_guest.'</td>
                            </tr>
                            <tr><td colspan="14">&nbsp;</td></tr>
                            <tr><td colspan="14" class="bold">Market Performance</td></tr>
                            <tr><td colspan="14" class="bold">Sandton and Surroundings - Upscale & Upper Mid</td></tr>';

                            $market_perf_arr[] = 'MPI';
                            $market_perf_arr[] = 'ARI';
                            $market_perf_arr[] = 'RGI';
                            $sum = '0';
                            foreach($market_perf_arr as $mark_key => $market_per){
                                 $write_html .= '<tr>
                                    <td class="bold">'.$market_per.'</td>';
                                    $cnt='1';
                                    for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td>';
                                       foreach($step2Data as $step2_key=>$step2_val){
                                           if(($step2_val['GpsData']['text'] == 'Market Performance') && ($step2_val['GpsData']['sub_text'] == $market_per) && ($step2_val['GpsData']['question'] == ($cnt + ($mark_key * '12')))){
                                               $write_html .= $step2_val['GpsData']['value'];
                                               $sum = $sum +$step2_val['GpsData']['value'];
                                               unset($step2Data[$step2_key]);
                                           }
                                       }
                                        $write_html .= '</td>';
                                        $cnt++; }
                                        $write_html .= '<td>'.$sum.'</td>
                                </tr>';
                            }
                            $write_html .= '<tr>
                                <td class="bold">SpendPAR</td>';
                                $spendPAR = array(); for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td>&nbsp;'. $spendPAR[$month] = round(@$rev_arr[$month]/($number_of_rooms * $days_in_month),'2').' </td>';
                                }
                                $write_html .= '<td>'.array_sum($spendPAR).'</td>
                            </tr>
                            <tr>
                                <td class="bold">Spend/Guest</td>';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td>&nbsp;'.$spend[$month] = round(@$rev_arr[$month]/@$num_guest[$month],'2').'</td>';
                                }
                                $write_html .= '<td>'.array_sum($spend).'</td>
                            </tr>
                            <tr><td colspan="14">&nbsp;</td></tr>
                            <tr><td colspan="14"  class="bold">Reputation Management</td></tr>
                            <tr><td colspan="14">&nbsp;</td></tr>';

                            $summary_arr[] = 'Occupied Rooms';
                            $summary_arr[] = 'Occupancy%';
                            $summary_arr[] = 'ADR';
                            $summary_arr[] = 'RevPAR';
                            $summary_arr[] = 'Revenue';
                           
                            $write_html .= '<tr>
                                <td  class="bold">Var. to Budget</td>';
                               for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                               $write_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                               }
                                $write_html .= '<td class="bold"><!--Full Year--></td>
                            </tr>';
                                
                            foreach($summary_arr as $summary_val){
                                $summary_val_sum_arr = array();
                                $write_html .= '<tr>
                                    <td class="bold">'.$summary_val.'</td>';
                                    for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                        $write_html .= '<td>';
                                        if($summary_val == 'Occupied Rooms'){
                                             //Occ Rooms - ConfigD75
                                            $write_html .= $summary_val_sum_arr[] = $fcst_rooms_arr[$month] - $cnfig_rn_arr1[$month];
                                        }elseif($summary_val == 'Occupancy%'){
                                            // Occ%- ConfigD78
                                           $write_html .= $summary_val_sum_arr[] = $fcst_rooms_arr[$month] - $cnfig_occ_arr1[$month];
                                        }elseif($summary_val == 'ADR'){
                                            // ADR - ConfigD76
                                            $write_html .= $summary_val_sum_arr[] = $fcst_adr_arr[$month] - $cnfig_adr_arr1[$month];
                                        }elseif($summary_val == 'RevPAR'){
                                            // RevPar- ConfigD79
                                            $write_html .= $summary_val_sum_arr[] = round(@$fcst_revpar_arr[$month],'2') -$cnfig_revpar_arr1[$month];
                                        }elseif($summary_val == 'Revenue'){
                                            //Revenue - ConfigD77
                                            $write_html .= $summary_val_sum_arr[] = $rev_arr[$month] - $cnfig_revenue_arr1[$month];
                                        }
                                        $write_html .= '</td>';
                                    }
                                    $write_html .= '<td></td>
                                </tr>';
                            }
                            $write_html .= '<tr><td colspan="14">&nbsp;</td></tr>
                            
                            <tr>
                                <td  class="bold">Var. to LY</td>';
                               for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                               $write_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                                }
                                $write_html .= '<td class="bold"><!--Full Year--></td>
                            </tr>';
                            
                                foreach($summary_arr as $summary_val){ 
                                $summary_val_sum_arr = array();
                                $write_html .= '<tr>
                                    <td class="bold">'.$summary_val.'</td>';
                                    for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                        $write_html .= '<td>';
                                        if($summary_val == 'Occupied Rooms'){
                                            //Occ Rooms - ConfigD170
                                            $write_html .= $fcst_rooms_arr[$month] - $rn_config_total[$month];
                                        }elseif($summary_val == 'Occupancy%'){
                                            // Occ%- ConfigD173
                                           $write_html .= $fcst_rooms_arr[$month] - $config_occ_total[$month];
                                        }elseif($summary_val == 'ADR'){
                                            // ADR - ConfigD171
                                            $write_html .= $fcst_adr_arr[$month] - $config_adr_total[$month];
                                        }elseif($summary_val == 'RevPAR'){
                                            // RevPar- ConfigD174
                                            $write_html .= round(@$fcst_revpar_arr[$month],'2') -$config_revpar_total[$month];
                                        }elseif($summary_val == 'Revenue'){
                                            //Revenue - ConfigD172
                                            $write_html .= $rev_arr[$month] - $rev_total_array[$month];
                                        }
                                        $write_html .= '</td>';
                                    }
                                    $write_html .= '<td></td>
                                </tr>';
                            }
                        $write_html .= '</table>
                </div>
                </fieldset>

                <fieldset>
 		<legend>Market</legend>
                <div id="market_con_div">
                    <table>
                        <tr><td class="bold">Market Conditions - '.date("F", mktime(0, 0, 0, $gps_month, 1)).'</td></tr>
                        <tr><td>';

                               foreach($step3Data as $step3_val){
                                   if($step3_val['GpsData']['question'] == '1'){
                                       $write_html .= $step3_val['GpsData']['value'];
                               }
                               }
                            $write_html .= '</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Market Conditions - '.date("F", mktime(0, 0, 0, ($gps_month+1), 1)).'</td></tr>
                        <tr><td>';

                               foreach($step3Data as $step3_val){
                                   if($step3_val['GpsData']['question'] == '2'){
                                       $write_html .= $step3_val['GpsData']['value'];
                               }
                               }
                            $write_html .= '</td></tr>
                    </table>
                    </div>
                </fieldset>

                <fieldset>
 		<legend>Competition</legend>
                <div id="Competition_div">
                    <table>
                        <tr><td class="bold">Competitor Activity - '.date("F", mktime(0, 0, 0, $gps_month, 1)).'</td></tr>
                        <tr><td>';

                               foreach($step4Data as $step4_val){
                                   if($step4_val['GpsData']['question'] == '1'){
                                       $write_html .= $step4_val['GpsData']['value'];
                                   }
                               }
                            $write_html .= '</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Competitor Activity - '.date("F", mktime(0, 0, 0, ($gps_month+1), 1)).'</td></tr>
                        <tr><td>';
                            
                               foreach($step4Data as $step4_val){
                                   if($step4_val['GpsData']['question'] == '2'){
                                       $write_html .= $step4_val['GpsData']['value'];
                               }
                               }
                            $write_html .= '</td></tr>
                    </table>
                </div>
                </fieldset>

                <fieldset>
 		<legend>Activity&nbsp;'.date("F", mktime(0, 0, 0, $gps_month, 1)).'</legend>
                <div id="Activity1_div">
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                            foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                            foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                            foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'City Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                        }
                    $write_html .= '</table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                            foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                       }
                    $write_html .= '</table>
                    <table>
                        <tr><td class="bold">Planned Activity</td><td class="bold">Fcst RN</td><td class="bold">Fcst ADR</td><td class="bold">Revenue</td><td class="bold">Actual RN</td><td class="bold">Actual ADR</td><td class="bold">Actual Revenue</td><td class="bold">Var. Revenue</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                        foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Planned Activity'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                            foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                       }
                    $write_html .= '</table>

                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                        foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step5_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                        }
                    $write_html .= '</table>
                    
                    <table>
                        <tr><td class="bold">Comments</td></tr>
                        <tr><td>';
                        foreach($step5Data as $step5_key=>$step5_val){
                                       if(($step5_val['GpsData']['text'] == 'Comments')){
                                               $write_html .= $step5_val['GpsData']['value'];
                                               unset($step5Data[$step5_key]);
                                       }
                                   }
                                   $write_html .= '</td></tr>
                    </table>
                </div>
                </fieldset>
                
                <fieldset>
 		<legend>Activity '.date("F", mktime(0, 0, 0, ($gps_month+1), 1)).'</legend>
                <div id="Activity2_div">
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                        foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'City Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                       }
                    $write_html .= '</table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                        foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   } 
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                        }
                    $write_html .= '</table>
                    <table>
                        <tr><td class="bold">Planned Activity</td><td class="bold">Fcst RN</td><td class="bold">Fcst ADR</td><td class="bold">Revenue</td><td class="bold">Actual RN</td><td class="bold">Actual ADR</td><td class="bold">Actual Revenue</td><td class="bold">Var. Revenue</td></tr>';
                       for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                        foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Planned Activity'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                        }
                    $write_html .= '</table>
                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                        foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step6_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                       }
                    $write_html .= '</table>
                    <table>
                        <tr><td class="bold">Comments</td></tr>
                        <tr><td>';
                    
                    foreach($step6Data as $step6_key=>$step6_val){
                                       if(($step6_val['GpsData']['text'] == 'Comments')){
                                               $write_html .= $step6_val['GpsData']['value'];
                                               unset($step6Data[$step6_key]);
                                       }
                                   }
                                   $write_html .= '</td></tr>
                    </table>    
               </div>
                </fieldset>
                
                <fieldset>
 		<legend>Activity '.date("F", mktime(0, 0, 0, ($next2_month), 1)).'</legend>
                <div id="Activity3_div">
                    
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                         foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'City Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                        }
                        
                    $write_html .= '</table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                            foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                        }
                    $write_html .= '</table>
                    <table>
                        <tr><td class="bold">Planned Activity</td><td class="bold">Fcst RN</td><td class="bold">Fcst ADR</td><td class="bold">Revenue</td><td class="bold">Actual RN</td><td class="bold">Actual ADR</td><td class="bold">Actual Revenue</td><td class="bold">Var. Revenue</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                        foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Planned Activity'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                        }
                    $write_html .= '</table>

                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                        foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   } 
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step7_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                        }
                    $write_html .= '</table>
                    <table>
                        <tr><td class="bold">Comments</td></tr>
                        <tr><td>';
                     foreach($step7Data as $step7_key=>$step7_val){
                                       if(($step7_val['GpsData']['text'] == 'Comments')){
                                               $write_html .= $step7_val['GpsData']['value'];
                                               unset($step7Data[$step7_key]);
                                       }
                                   } 
                                   $write_html .= '</td></tr>
                    </table>    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend>Activity '.date("F", mktime(0, 0, 0, ($gps_month+3), 1)).'</legend>
                <div id="Activity4_div">
                   
                    <table>
                        <tr><td colspan="5" class="bold">City Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                            foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'City Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                        }
                        
                    $write_html .= '</table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                         foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                       }
                    $write_html .= '</table>
                    <table>
                        <tr><td class="bold">Planned Activity</td><td class="bold">Fcst RN</td><td class="bold">Fcst ADR</td><td class="bold">Revenue</td><td class="bold">Actual RN</td><td class="bold">Actual ADR</td><td class="bold">Actual Revenue</td><td class="bold">Var. Revenue</td></tr>';
                        for($count=1;$count <= 5; $count++){ 
                        $write_html .= '<tr>
                            <td>';
                        foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Planned Activity'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Fcst RN'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Fcst ADR'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Actual RN'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Actual ADR'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Actual Revenue'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Var. Revenue'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                        </tr>';
                       }
                    $write_html .= '</table>

                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td class="bold">Event Name</td><td class="bold">Description</td><td class="bold">Impact</td><td class="bold">Revenue Opportunity / Target</td><td class="bold">Market / Source</td></tr>';
                        for($count=1;$count <= 5; $count++){
                        $write_html .= '<tr>
                            <td>';
                                foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Event Name'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Description'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   }
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Impact'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } 
                                   $write_html .= '</td>
                            <td>';
                                   foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Revenue Opportunity / Target'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } 
                                   $write_html .= '</td>
                            <td>';
                            foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Competition Hotel Events')){
                                           if(($step8_val['GpsData']['sub_text'] == 'Market / Source'.$count)){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                           }
                                       }
                                   } 
                                   $write_html .= '</td>
                        </tr>';
                        }
                    $write_html .= '</table>
                    
                    <table>
                        <tr><td class="bold">Comments</td></tr>
                        <tr><td>';
                     foreach($step8Data as $step8_key=>$step8_val){
                                       if(($step8_val['GpsData']['text'] == 'Comments')){
                                               $write_html .= $step8_val['GpsData']['value'];
                                               unset($step8Data[$step8_key]);
                                       }
                                   } 
                                   $write_html .= '</td></tr>
                    </table>    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend>Top Producers</legend>
                <div id="Producers_div">
                    <table>
                        <tr><td colspan="6" class="bold">Corporate</td></tr>
                        <tr><td>&nbsp;</td><td class="bold">Name</td><td class="bold"> Room Nights</td><td class="bold">ADR</td><td class="bold">Av. Spend</td><td class="bold">Total Revenue</td></tr>';
                        for($count=1;$count <= 10; $count++){ 
                        $write_html .= '<tr>
                            <td>'.$count.'</td>
                        <td>';
                        foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('1' + ('5' * ($count-1))))){
                                           $write_html .= $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('2' + ('5' * ($count-1))))){
                                           $write_html .= $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('3' + ('5' * ($count-1))))){
                                           $write_html .= $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('4' + ('5' * ($count-1))))){
                                           $write_html .= $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Corporate')){
                                       if(($step9_val['GpsData']['question'] == ('5' + ('5' * ($count-1))))){
                                           $write_html .= $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               }
                               $write_html .= '</td>
                        </tr>';
                        }
                    $write_html .= '</table>
                    
                    <table>
                        <tr><td colspan="6" class="bold">Travel Agents</td></tr>
                        <tr><td>&nbsp;</td><td class="bold">Name</td><td class="bold">Room Nights</td><td class="bold">ADR</td><td class="bold">Av. Spend</td><td class="bold">Total Revenue</td></tr>';
                        for($count=1;$count <= 10; $count++){
                        $write_html .= '<tr>
                            <td>'.$count.'</td>
                        <td>';
                        foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('1' + ('5' * ($count-1))))){
                                           $write_html .= $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               }
                               $write_html .= '</td>
                            <td>';
                               foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('2' + ('5' * ($count-1))))){
                                           $write_html .= $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               }
                               $write_html .= '</td>
                            <td>';
                               foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('3' + ('5' * ($count-1))))){
                                           $write_html .= $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               }
                               $write_html .= '</td>
                            <td>';
                               foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('4' + ('5' * ($count-1))))){
                                           $write_html .= $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step9Data as $step9_key=>$step9_val){
                                   if(($step9_val['GpsData']['text'] == 'Travel Agents')){
                                       if(($step9_val['GpsData']['question'] == ('5' + ('5' * ($count-1))))){
                                           $write_html .= $step9_val['GpsData']['value'];
                                           unset($step9Data[$step9_key]);
                                           break;
                                       }
                                   }
                               }
                               $write_html .= '</td>
                        </tr>';
                        }
                    $write_html .= '</table>
                    
                  </div>
                </fieldset>
                
                <fieldset>
 		<legend>Market Segmentation - '.date("F", mktime(0, 0, 0, $gps_month, 1)).'</legend>
                <div id="market_seg_div">';
                    
                    if(!empty($marketsegments)){
                        $write_html .= '<table>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td class="bold">Prior Month</td>
                                    <td class="bold">Present Month</td>
                                    <td class="bold">Budget</td>
                                    <td class="bold">Last Year</td>
                                </tr>';
                                
                                $bob_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/62');
                                $adr_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/64');
                                
                                $bob_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$gps_month.'/'.$year.'/62');
                                $adr_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$gps_month.'/'.$year.'/64');
                                
                                foreach($marketsegments as $seg_id=>$segments){
                                    $write_html .= '<tr>
                                        <td>'.$segments.'</td>
                                        <td>RN</td>
                                        <td>'.@$bob_prior_month[$seg_id].'</td>
                                        <td>'.@$bob_present_month[$seg_id].'</td>
                                        <td>'.$config_seg_vals[$segments]['Budget-RN'][$gps_month].'</td>
                                        <td>'.$config_seg_vals[$segments]['LastYear-RN'][$gps_month].'</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>ADR</td>
                                        <td>'.@$adr_prior_month[$seg_id].'</td>
                                        <td>'.@$adr_present_month[$seg_id].'</td>
                                        <td>'.$config_seg_vals[$segments]['Budget-ADR'][$gps_month].'</td>
                                        <td>'.$config_seg_vals[$segments]['LastYear-ADR'][$gps_month].'</td>
                                    </tr>';
                                }
                        $write_html .= '</table>';
                    }
                    
                    $write_html .= '</div>
                </fieldset>
                
                <fieldset>
 		<legend>BOB - '.date("F", mktime(0, 0, 0, ($next_month), 1)).'</legend>
                <div id="bob1_div">';
                    if(!empty($marketsegments)){
                        $write_html .= '<table>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td class="bold">Prior Month</td>
                                    <td class="bold">Present Month</td>
                                    <td class="bold">Budget</td>
                                    <td class="bold">Last Year</td>
                                </tr>';
                                $bob_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$gps_month.'/'.$year.'/62');
                                $adr_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$gps_month.'/'.$year.'/64');
                                
                                $bob_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next_month.'/'.$year_for_next.'/62');
                                $adr_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next_month.'/'.$year_for_next.'/64');
                                
                                foreach($marketsegments as $seg_id=>$segments){
                                    $write_html .= '<tr>
                                        <td>'.$segments.'</td>
                                        <td>RN</td>
                                        <td>'.@$bob_prior_month[$seg_id].'</td>
                                        <td>'.@$bob_present_month[$seg_id].'</td>
                                        <td>'.$config_seg_vals[$segments]['Budget-RN'][$gps_month+1].'</td>
                                        <td>'.$config_seg_vals[$segments]['LastYear-RN'][$gps_month+1].'</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>ADR</td>
                                        <td>'.@$adr_prior_month[$seg_id].'</td>
                                        <td>'.@$adr_present_month[$seg_id].'</td>
                                        <td>'.$config_seg_vals[$segments]['Budget-ADR'][$gps_month+1].'</td>
                                        <td>'.$config_seg_vals[$segments]['LastYear-ADR'][$gps_month+1].'</td>
                                    </tr>';
                                }
                        $write_html .= '</table>';
                    }
                $write_html .= '</div>
                </fieldset>
                
                <fieldset>
 		<legend>BOB - '.date("F", mktime(0, 0, 0, ($gps_month+2), 1)).'</legend>
                <div id="bob2_div">';
                    if(!empty($marketsegments)){
                        
                        $bob_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next_month.'/'.$year_for_next.'/62');
                        $adr_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next_month.'/'.$year_for_next.'/64');

                        $bob_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next2_month.'/'.$year_for_next2.'/62');
                        $adr_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$next2_month.'/'.$year_for_next2.'/64');
                        
                        $write_html .= '<table>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td class="bold">Prior Month</td>
                                    <td class="bold">Present Month</td>
                                    <td class="bold">Budget</td>
                                    <td class="bold">Last Year</td>
                                </tr>';
                                foreach($marketsegments as $seg_id=>$segments){
                                    $write_html .= '<tr>
                                        <td>'.$segments.'</td>
                                        <td>RN</td>
                                        <td>'.@$bob_prior_month[$seg_id].'</td>
                                        <td>'.@$bob_present_month[$seg_id].'</td>
                                        <td>'.$config_seg_vals[$segments]['Budget-RN'][$gps_month+2].'</td>
                                        <td>'.$config_seg_vals[$segments]['LastYear-RN'][$gps_month+2].'</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>ADR</td>
                                        <td>'.@$adr_prior_month[$seg_id].'</td>
                                        <td>'.@$adr_present_month[$seg_id].'</td>
                                        <td>'.$config_seg_vals[$segments]['Budget-ADR'][$gps_month+2].'</td>
                                        <td>'.$config_seg_vals[$segments]['LastYear-ADR'][$gps_month+2].'</td>
                                    </tr>';
                                }
                        $write_html .= '</table>';
                    }
                    $write_html .= '</div>
                </fieldset>
                
                <fieldset>
 		<legend>BOB - '.date("F", mktime(0, 0, 0, ($gps_month+3), 1)).'</legend>
                  <div id="bob3_div">';
                    if(!empty($marketsegments)){ 
                        
                        $present_month = $gps_month+'3' >= '13' ? ($gps_month-'12') : $gps_month;
                        $year_for_present = $gps_month+'3' >= '13' ? $year+ '1' : $year;

                        $prior_month = $gps_month+'2' >= '13' ? ($gps_month-'12') : $gps_month;
                        $year_for_prior = $gps_month+'2' >= '13' ? $year+ '1' : $year;

                        $bob_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/62');
                        $adr_prior_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/64');

                        $bob_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$present_month.'/'.$year_for_present.'/62');
                        $adr_present_month = $this->requestAction('/GpsPacks/get_total_segment_vals/'.$client_id.'/'.$present_month.'/'.$year_for_present.'/64');
                    
                        $write_html .= '<table>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td class="bold">Prior Month</td>
                                    <td class="bold">Present Month</td>
                                    <td class="bold">Budget</td>
                                    <td class="bold">Last Year</td>
                                </tr>';
                                foreach($marketsegments as $seg_id=>$segments){
                                    $write_html .= '<tr>
                                        <td>'.$segments.'</td>
                                        <td>RN</td>
                                        <td>'.@$bob_prior_month[$seg_id].'</td>
                                        <td>'.@$bob_present_month[$seg_id].'</td>
                                        <td>'.$config_seg_vals[$segments]['Budget-RN'][$gps_month+3].'</td>
                                        <td>'.$config_seg_vals[$segments]['LastYear-RN'][$gps_month+3].'</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>ADR</td>
                                        <td>'.@$adr_prior_month[$seg_id].'</td>
                                        <td>'.@$adr_present_month[$seg_id].'</td>
                                        <td>'.$config_seg_vals[$segments]['Budget-ADR'][$gps_month+3].'</td>
                                        <td>'.$config_seg_vals[$segments]['LastYear-ADR'][$gps_month+3].'</td>
                                    </tr>';
                                }
                        $write_html .= '</table>';
                    }
                    $write_html .= '</div>
                </fieldset>
                
            <fieldset>
 		<legend>Channels Year</legend>
                <div id="Channel_yr_div">
                    <table>
                        <tr>
                            <td colspan="2" class="bold">GDS</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                               $write_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                            }
                        $write_html .= '</tr>';
                        
                        $gds_arr['1'] = 'Sabre';
                        $gds_arr['2'] = 'Amadeus';
                        $gds_arr['3'] = 'Galileo';
                        $gds_arr['4'] = 'Worldspan';
                        
                        $loop_count = '0';
                        foreach($gds_arr as $gd_key=>$gd_val){
                            $rev_arr = array();
                        
                            $write_html .= '<tr>
                            <td class="bold">'.$gd_val.'</td>
                            <td class="bold">RN</td>';
                            $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>';
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'GDS') && ($step15_val['GpsData']['sub_text'] == $gd_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       $write_html .= $step15_val['GpsData']['value'];
                                       $channels_tyd_actual_arr[$gd_val]['RN'][] = $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               }
                               $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>';
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'GDS') && ($step15_val['GpsData']['sub_text'] == $gd_val) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       $write_html .= $step15_val['GpsData']['value'];
                                       $channels_tyd_actual_arr[$gd_val]['ADR'][] = $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               }
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>'.(@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']).'</td>';
                            }
                        $write_html .= '</tr>';
                        $loop_count++; }
                        
                        $write_html .= '<tr><td colspan="14">&nbsp;</td></tr>
                        <tr><td colspan="14"><b>Online</b></td></tr>';
                        
                        $online_arr['1'] = 'Website';
                        $online_arr['2'] = 'OTA';
                        
                        $loop_count ='0';
                        foreach($online_arr as $online_arr_key=>$online_arr_val){
                            $rev_arr = array();
                          
                        $write_html .= '<tr>
                            <td class="bold">'.$online_arr_val.'</td>
                            <td class="bold">RN</td>';
                            $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>';
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Online') && ($step15_val['GpsData']['sub_text'] == $online_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       $write_html .= $step15_val['GpsData']['value'];
                                       $channels_tyd_actual_arr[$online_arr_val]['RN'][] = $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               }
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            
                                $write_html .= '<td>';
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Online') && ($step15_val['GpsData']['sub_text'] == $online_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       $write_html .= $step15_val['GpsData']['value'];
                                       $channels_tyd_actual_arr[$online_arr_val]['ADR'][] = $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               } 
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                             $write_html .= '<td>'.(@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']).'</td>';
                            }
                        $write_html .= '</tr>';
                        $loop_count++; }
                        
                        $write_html .= '<tr><td colspan="14">&nbsp;</td></tr>
                        <tr><td colspan="14"><b>Direct</b></td></tr>';

                        $direct_arr['1'] = 'Phone';
                        $direct_arr['2'] = 'Email/Fax';
                        $direct_arr['3'] = 'Walkin';
                        $loop_count = '0';
                        foreach($direct_arr as $direct_arr_key=>$direct_arr_val){ 
                            $rev_arr = array();
                        $write_html .= '<tr>
                            <td class="bold">'.$direct_arr_val.'</td>
                            <td class="bold">RN</td>';

                            $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>';
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Direct') && ($step15_val['GpsData']['sub_text'] == $direct_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + ($loop_count*24)))){
                                       $write_html .= $step15_val['GpsData']['value'];
                                       $channels_tyd_actual_arr[$direct_arr_val]['RN'][] = $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               }
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            $write_html .= '<td>';
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'Direct') && ($step15_val['GpsData']['sub_text'] == $direct_arr_val) && ($step15_val['GpsData']['question'] == ($cnt + 12 + ($loop_count*24)))){
                                       $write_html .= $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step15_val['GpsData']['value'];
                                       $channels_tyd_actual_arr[$direct_arr_val]['ADR'][] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               } 
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                 $write_html .= '<td>'.(@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']).'</td>';
                            }
                        $write_html .= '</tr>';
                        $loop_count++; }
                        $rev_arr = array();
                        
                        $write_html .= '<tr>
                            <td class="bold">CRO</td>
                            <td class="bold">RN</td>';
                            $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            
                            $write_html .= '<td>';
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'CRO')  && ($step15_val['GpsData']['question'] == $cnt)){
                                       $write_html .= $step15_val['GpsData']['value'];
                                       $channels_tyd_actual_arr['CRO']['RN'][] = $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               } 
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            $write_html .= '<td>';
                               foreach($step15Data as $step15_key=>$step15_val){
                                   if(($step15_val['GpsData']['text'] == 'CRO')  && ($step15_val['GpsData']['question'] == ($cnt + '12'))){
                                       $write_html .= $step15_val['GpsData']['value'];
                                       $channels_tyd_actual_arr['CRO']['ADR'][] = $step15_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step15_val['GpsData']['value'];
                                       unset($step15Data[$step15_key]);
                                   }
                               }
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                           for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                               $write_html .= '<td>'.(@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']).'</td>';
                            }
                        $write_html .= '</tr>
                    </table>
                </div>
                </fieldset>
                
                <fieldset>
 		<legend>Channels - '.date("F", mktime(0, 0, 0, $gps_month, 1)) .'</legend>
                <div id="Channels_div">
                    <table>
                        <tr>
                            <td colspan="2" class="bold">GDS</td>
                            <td class="bold">Prior Month</td>
                            <td class="bold">Month</td>
                            <td class="bold">Budget</td>
                            <td class="bold">Last Year</td>
                            <td class="bold">YTD Actual</td>
                            <td class="bold">YTD Budget</td>
                        </tr>';
                        
                        $gds_arr['1'] = 'Sabre';
                        $gds_arr['2'] = 'Amadeus';
                        $gds_arr['3'] = 'Galileo';
                        $gds_arr['4'] = 'Worldspan';
                        
                        $channels_vals['Sabre'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Sabre/GDS/14');
                        $channels_vals['Amadeus'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Amadeus/GDS/14');
                        $channels_vals['Galileo'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Galileo/GDS/14');
                        $channels_vals['Worldspan'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Worldspan/GDS/14');
                        
                        $prior_month = $month-'1' == '0' ? '12' : $month-'1';
                        $year_for_prior = $month-'1' == '0' ? $year- '1' : $year;
                        $loop_count = '0';
                        foreach($gds_arr as $gd_key=>$gd_val){
                        $write_html .= '<tr>
                            <td class="bold">'.$gd_val.'</td>
                            <td class="bold">RN</td>
                            <td>'.@$channels_vals[$gd_val]['1' + ($loop_count*8)].'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('1' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('2' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('3' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                            $write_html .= '</td>
                            <td>'.array_sum($channels_tyd_actual_arr[$gd_val]['RN']).'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('4' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <td>'.@$channels_vals[$gd_val]['5' + ($loop_count*8)].'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('5' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('6' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('7' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>'.array_sum($channels_tyd_actual_arr[$gd_val]['ADR']).'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'GDS') && ($step14_val['GpsData']['sub_text'] == $gd_val) && ($step14_val['GpsData']['question'] == ('8' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                                $write_html .= '</td>
                        </tr>';
                        $loop_count++; }
                        
                       $write_html .= '<tr><td colspan="8">&nbsp;</td></tr>
                        <tr><td colspan="8" class="bold">Online</td></tr>';
                        $online_arr['1'] = 'Website';
                        $online_arr['2'] = 'OTA';
                        $channels_vals['Website'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Website/Online/14');
                        $channels_vals['OTA'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/OTA/Online/14');
                        $loop_count = '0';
                        foreach($online_arr as $online_arr_key=>$online_arr_val){ 
                        
                        $write_html .= '<tr>
                            <td class="bold">'.$online_arr_val.'</td>
                            <td class="bold">RN</td>
                            <td>'.@$channels_vals[$online_arr_val]['1' + ($loop_count*8)].'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('1' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('2' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('3' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>'.array_sum($channels_tyd_actual_arr[$online_arr_val]['RN']).'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('4' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <td>'.@$channels_vals[$online_arr_val]['5' + ($loop_count*8)].'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('5' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('6' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('7' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>'.array_sum($channels_tyd_actual_arr[$online_arr_val]['ADR']).'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Online') && ($step14_val['GpsData']['sub_text'] == $online_arr_val) && ($step14_val['GpsData']['question'] == ('8' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                        </tr>';
                        $loop_count++; }
                        
                        $write_html .= '<tr><td colspan="8">&nbsp;</td></tr>
                        <tr><td colspan="8" class="bold">Direct</td></tr>';

                        $direct_arr['1'] = 'Phone';
                        $direct_arr['2'] = 'Email/Fax';
                        $direct_arr['3'] = 'Walkin';
                        $channels_vals['Phone'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Phone/Direct/14');
                        $channels_vals['Email/Fax'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Email_Fax/Direct/14');
                        $channels_vals['Walkin'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/Walkin/Direct/14');                        
                        
                        $loop_count = '0';
                        foreach($direct_arr as $direct_arr_key=>$direct_arr_val){
                           $write_html .= '<tr>
                            <td class="bold">'.$direct_arr_val.'</td>
                            <td class="bold">RN</td>
                            <td>'.@$channels_vals[$direct_arr_val]['1' + ($loop_count*8)].'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('1' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('2' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('3' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>'.array_sum($channels_tyd_actual_arr[$direct_arr_val]['RN']).'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('4' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <td>'.@$channels_vals[$direct_arr_val]['5' + ($loop_count*8)].'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('5' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('6' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('7' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>'.array_sum($channels_tyd_actual_arr[$direct_arr_val]['ADR']).'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'Direct') && ($step14_val['GpsData']['sub_text'] == $direct_arr_val) && ($step14_val['GpsData']['question'] == ('8' + ($loop_count*8)))){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                        </tr>';
                        $loop_count++; }
                        $channels_vals['CRO'] = $this->requestAction('/GpsPacks/get_channels_vals/'.$client_id.'/'.$prior_month.'/'.$year_for_prior.'/0/CRO/14');

                           $write_html .= '<tr>
                            <td class="bold">CRO</td>
                            <td class="bold">RN</td>
                            <td>'.@$channels_vals['CRO']['1'].'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '1')){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '2')){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '3')){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>'.array_sum($channels_tyd_actual_arr['CRO']['RN']).'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '4')){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <td>'.@$channels_vals['CRO']['5'].'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '5')){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '6')){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '7')){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>'.array_sum($channels_tyd_actual_arr['CRO']['ADR']).'</td>
                            <td>';
                               foreach($step14Data as $step14_key=>$step14_val){
                                   if(($step14_val['GpsData']['text'] == 'CRO') && ($step14_val['GpsData']['question'] == '8')){
                                       $write_html .= $step14_val['GpsData']['value'];
                                       unset($step14Data[$step14_key]);
                                   }
                               } 
                               $write_html .= '</td>
                        </tr>
                    </table>
                </div>
                </fieldset>
                
                <fieldset>
 		<legend>Geo Year</legend>
                <div id="geo_yr_div">
                    <table>
                        <tr>
                            <td colspan="2">&nbsp;</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                               $write_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                              }
                        $write_html .= '</tr>';
                        
                        $country_arr = array();
                        $country_arr = explode(',',$gps_settings['GpsSetting']['countries']);
                        
                        foreach($country_arr as $country_key=>$country_val){
                            $rev_arr = array();
                        
                        $write_html .= '<tr>
                            <td class="bold">'.$country_val.'</td>
                            <td class="bold">RN</td>';
                            $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            $write_html .= '<td>';
                               foreach($step16Data as $step16_key=>$step16_val){
                                   if(($step16_val['GpsData']['text'] == $country_val)  && ($step16_val['GpsData']['question'] == $cnt)){
                                       $write_html .= $step16_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step16_val['GpsData']['value'];
                                       unset($step16Data[$step16_key]);
                                   }
                               }
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>';
                               foreach($step16Data as $step16_key=>$step16_val){
                                   if(($step16_val['GpsData']['text'] == $country_val)  && ($step16_val['GpsData']['question'] == ($cnt + '12'))){
                                       $write_html .= $step16_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step16_val['GpsData']['value'];
                                       unset($step16Data[$step16_key]);
                                   }
                               }
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                            $write_html .= '<td>'.(@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']).'</td>';
                            }
                        $write_html .= '</tr>';
                        }
                $write_html .= '</table>
                </div>
                </fieldset>
                
                <fieldset>
 		<legend>Prov Year</legend>
                <div id="prov_yr_div">
                    <table>
                        <tr>
                            <td colspan="2">&nbsp;</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                               $write_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                                }
                        $write_html .= '</tr>';
                        
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
                        
                        foreach($country_arr as $country_key=>$country_val){ 
                            $rev_arr = array();
                        $write_html .= '<tr>
                            <td class="bold">'.$country_val.'</td>
                            <td class="bold">RN</td>';
                            $cnt='1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            $write_html .= '<td>';
                               foreach($step17Data as $step17_key=>$step17_val){
                                   if(($step17_val['GpsData']['text'] == $country_val)  && ($step17_val['GpsData']['question'] == $cnt)){
                                       $write_html .= $step17_val['GpsData']['value'];
                                       $rev_arr[$month]['RN'] = $step17_val['GpsData']['value'];
                                       unset($step17Data[$step17_key]);
                                   }
                               }
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            $cnt = '1';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            $write_html .= '<td>';
                               foreach($step17Data as $step17_key=>$step17_val){
                                   if(($step17_val['GpsData']['text'] == $country_val)  && ($step17_val['GpsData']['question'] == ($cnt + '12'))){
                                       $write_html .= $step17_val['GpsData']['value'];
                                       $rev_arr[$month]['ADR'] = $step17_val['GpsData']['value'];
                                       unset($step17Data[$step17_key]);
                                   }
                               } 
                               $write_html .= '</td>';
                            $cnt++; }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                              $write_html .= '<td>'.(@$rev_arr[$month]['RN'] * @$rev_arr[$month]['ADR']).'</td>';
                            }
                        $write_html .= '</tr>';
                        }
                $write_html .= '</table>
                                            
                </div>
                </fieldset>
                
                <fieldset>
 		<legend>RoomTypes</legend>
                <div id="roomtype_div">';
                    $roomType_arr[$room_name[0]] = 'Standard';
                    $roomType_arr[$room_name[1]] = 'Executive';
                    $roomType_arr[$room_name[2]] = 'Deluxe';
                    $roomType_arr[$room_name[3]] = 'Suite';
                    $roomType_arr[$room_name[4]] = 'Other';
                    $write_html .= '<table>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>Actual</td><td>Prior Month</td><td>Prior Year</td><td>BAR A</td><td>Potential</td><td>Utilisation</td></tr>';
                         foreach($roomType_arr as $roomType_key=>$roomType_arr_val){
                            $adr_val = ''; $bar_val = '';
                            $roomTypeData = $this->requestAction('/GpsPacks/get_roomtype_values/'.$prior_month.'/'.$roomType_arr_val.'/'.$client_id.'/'.$gps_pack_id.'/'.$year_for_prior);

                        $write_html .= '<tr>
                            <td class="bold">'.$roomType_key.'</td>
                            <td class="bold">RN</td>
                            <td>';
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '1')){
                                       $write_html .= $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>'.@$roomTypeData['RN'][$prior_month].'</td>
                            <td>';
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '2')){
                                       $write_html .= $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               } 
                               $write_html .= '</td>
                            <td>';
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '3')){
                                       $write_html .= $bar_val = $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>
                            <td>';
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '4')){
                                       $write_html .= $adr_val = $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>'.@$roomTypeData['ADR'][$prior_month].'</td>
                            <td>';
                               foreach($step18Data as $step18_key=>$step18_val){
                                   if(($step18_val['GpsData']['text'] == $roomType_arr_val) && ($step18_val['GpsData']['question'] == '5')){
                                       $write_html .= $step18_val['GpsData']['value'];
                                       unset($step18Data[$step18_key]);
                                   }
                               }
                               $write_html .= '</td>
                            <td>&nbsp;</td>
                            <td>'.(($adr_val/$bar_val)* 100).'%</td>
                            <td>'.$bar_val/($roomValues[$roomType_arr_val] * $days_in_month).'</td>
                        </tr>';
                        }
                $write_html .= '</table>
                    
                </div>
                </fieldset>
                
                <fieldset>
 		<legend>Room Type Year</legend>
                <div id="roomtype_yr_div">
                    <table>
                        <tr>
                            <td>&nbsp;</td><td>&nbsp;</td>';
                             for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                             $write_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                             }
                        $write_html .= '</tr>';
                        
                        foreach($roomType_arr as $roomType_key=>$roomType_arr_val){
                            $roomTypeData = $this->requestAction('/GpsPacks/get_roomtype_values/year/'.$roomType_arr_val.'/'.$client_id.'/'.$gps_pack_id.'/'.$year);
                            $write_html .= '<tr>
                                <td class="bold">'.$roomType_key.'</td>
                                <td class="bold">RN</td>';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $use_month =($month > '12')?$month -'12':$month;
                                    $write_html .= '<td>&nbsp;'.@$roomTypeData['RN'][$use_month].'</td>';
                                }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">ADR</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                $use_month =($month > '12')?$month -'12':$month;
                                $write_html .= '<td>&nbsp;'.@$roomTypeData['ADR'][$use_month].'</td>';
                            }
                        $write_html .= '</tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold">Rev</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                $use_month =($month > '12')?$month -'12':$month;
                              $write_html .= '<td>&nbsp;'.(@$roomTypeData['RN'][$use_month] * @$roomTypeData['ADR'][$use_month]).'</td>';
                            }
                        $write_html .= '</tr>';
                        }
                $write_html .= '</table>
                </div>
                </fieldset>
                <fieldset>
 		<legend>Future Activity</legend>
                <div id="future_div">
                    <table>
                        <tr><td class="bold">Expected Market Conditions</td></tr>
                        <tr><td>';
                         foreach ($step20Data as $step20_data) {
                            if($step20_data['GpsData']['question'] == '1'){
                                $write_html .= $step20_data['GpsData']['value'];
                            }
                        }
                        $write_html .= '</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="bold">Planned Activity, by Segment</td></tr>
                        <tr><td>';
                        foreach ($step20Data as $step20_data) {
                            if($step20_data['GpsData']['question'] == '2'){
                                $write_html .= $step20_data['GpsData']['value'];
                            }
                        }
                        $write_html .= '</td></tr>
                    </table>
               </div>
                </fieldset>
                <fieldset>
 		<legend>Reputation</legend>
                <div id="Reputation_div">
                    <table>
                        <tr>
                            <td>&nbsp;</td>';
                            for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                               $write_html .= '<td class="bold">'.date("M", mktime(0, 0, 0, $month, 1)).'</td>';
                            }
                        $write_html .= '</tr>';
                       
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
                        $prev_key = '';
                        foreach($reputation_arr as $reputation_key=>$reputation_val){ 
                            if($prev_key != $reputation_key){
                            $write_html .= '<tr><td colspan="12" >&nbsp;</td></tr>
                                <tr><td colspan="12" class="bold">'.$reputation_key.'</td></tr>';
                           }
                            $loop_count = '0';
                            foreach($reputation_val as $rep_val){
                            $write_html .= '<tr>
                                <td>'.$rep_val.'</td>';
                                $cnt = '1';
                                for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                    $write_html .= '<td>';
                               foreach($step21Data as $step21_key=>$step21_val){
                                   if(($step21_val['GpsData']['text'] == $reputation_key) && ($step21_val['GpsData']['sub_text'] == $rep_val) && ($step21_val['GpsData']['question'] == ($cnt + ($loop_count*12)))){
                                       $write_html .= $step21_val['GpsData']['value'];
                                       unset($step21Data[$step21_key]);
                                   }
                               }
                               $write_html .= '</td>';
                                $cnt++; }
                            $write_html .= '</tr>';
                        $loop_count++; }
                        $prev_key = $reputation_key;
                        }
                $write_html .= '</table>
                </div>
                </fieldset>              
	</fieldset>
</div>';

echo $write_html; exit;
                
$pdf->SetXY(5,50);
$pdf->writeHTML($write_html, true, false, true, false, '');
ob_end_clean();
//Close and output PDF document
$pdf->Output("Report_".date('d-M-Y').".pdf", 'D');
exit; 
?>