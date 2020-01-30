<?php
ob_start();
App::import('Vendor','tcpdf');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8");
	
$date = date('Y-m-d');
$htms = '';
$htms.='<table border="">';
$htms.='<tr><td>Hotel Name : '.$hotelname.'</td></tr>';
$htms.='<tr><td>Report For   : '.date('d F Y',strtotime($flashData['DailyFlash']['date'])).'</td></tr>';
$htms.='<tr><td>Downloaded Date : '.date('m-d-Y').'</td></tr>';
$htms.='</table>';

$pdf->SetCreator(PDF_CREATOR);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetHeaderMargin(-1);
$pdf->SetFooterMargin(-2);
$textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans'

$pdf->SetAuthor("Revenue Performance at www.myrevenuedashboard.net");
$pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);
$pdf->setHeaderFont(array($textfont,'',8));
$pdf->SetFont($textfont, '', 10);
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


if(!empty($clienImage))
{
	$ext = pathinfo($clienImage, PATHINFO_EXTENSION);
	if($ext == "png" || $ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "bmp")
	{
		$exts = findexts($clienImage);	// Image example
		$imgPath = WWW_ROOT.'files'.DS.'clientlogos'.DS.$clienImage;
		$pdf->Image($imgPath, 245,32, 40, 14, $exts, '', '', true, 150);
	}
}

//$pdf->setPageMark();

$pdf->SetXY(5, 25);
$pdf->writeHTML($htms, true, false, true, false, '');


                $number_of_rooms = $flashData['Client']['number_of_rooms'] == '' ? '1' : $flashData['Client']['number_of_rooms'];
                $restaurant_open_hours = $flashData['Client']['restaurant_open_hours'] == '' ? '6' : $flashData['Client']['restaurant_open_hours'];
                $chairs_in_restaurant = $flashData['Client']['chairs_in_restaurant'] == '' ? '1' : $flashData['Client']['chairs_in_restaurant'];
                
                $number_of_adult = $flashData['DailyFlash']['number_of_adults']; 
                $number_of_children = $flashData['DailyFlash']['number_of_childrens']; 
                
                $adult_ded = $flashData['DailyFlash']['deduction'];
                $child_ded = $flashData['DailyFlash']['child_deduction'];
                
            $html234 = "\n".'<hr/>';
            $html234 .= '<h2>Daily Operations</h2>';
            $html234 .= '<table border="1" cellpadding="2" cellspacing="1">
                        <tr><td><b>Arrivals</b></td><td>'.$flashData['DailyFlash']['total_arrival'].'</td></tr>
                        <tr><td><b>Departures</b></td><td>'.$flashData['DailyFlash']['total_departure'].'</td></tr>
                        <tr><td><b>Group Arrivals</b></td><td>'.$flashData['DailyFlash']['group_arrival'].'</td></tr>
                        <tr><td><b>Group Departures</b></td><td>'.$flashData['DailyFlash']['group_departure'].'</td></tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Guest Comments</b></td></tr>
                        <tr><td>'.$flashData['DailyFlash']['comments'].'</td></tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Site Inspection Rooms</b></td></tr>
                        <tr><td>'.$flashData['DailyFlash']['inspection_comments'].'</td></tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Maintenance Rooms</b></td></tr>
                        <tr><td>'.$flashData['DailyFlash']['maintainance_comments'].'</td></tr>
                    </table>
                    <p></p><hr/>
 		<h2>Reservations Summary</h2>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr>
                            <td style="color:#E32E32;background-color:#DEDEDE;"><b>Current Month</b></td>
                            <td style="background-color:#DEDEDE;">&nbsp;</td>
                            <td style="background-color:#DEDEDE;">&nbsp;</td>
                            <td style="background-color:#DEDEDE;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><b>Yesterday</b></td>
                            <td><b>Month to Date</b></td>
                            <td><b>Daily Ave.</b></td>
                        </tr>';
                        $flash_date = strtotime($flashData['DailyFlash']['date']);
                            $day = date('d',$flash_date);
                            $prev_day = $day - 1;
                            $d12 = round($monthToDateArr[0][0]['reservation']/$prev_day,2);
                            $d13 = round($monthToDateArr[0][0]['room_night']/$prev_day,2);
                $html234 .= '<tr>
                            <td><b>New Bookings</b></td>
                            <td>'.$flashData['DailyFlash']['reservation'].'</td>
                            <td>'.$monthToDateArr[0][0]['reservation'].'</td>
                            <td>'.$d12.'</td>
                        </tr>
                        <tr>
                            <td><b>New Room Nights</b></td>
                            <td>'.$flashData['DailyFlash']['room_night'].'</td>
                            <td>'.$monthToDateArr[0][0]['room_night'].'</td>
                            <td>'.$d13.'</td>
                        </tr>
                        <tr>
                            <td><b>ADR</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue']/$flashData['DailyFlash']['room_night'],2).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue']/$monthToDateArr[0][0]['room_night'],2).'</td>
                            <td>'.number_format(($monthToDateArr[0][0]['rooms_revenue']/$prev_day)/$d13,2).'</td>
                        </tr>
                        <tr>
                            <td><b>Revenue</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue']/$prev_day,0).'</td>
                        </tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr>
                            <td style="color:#E32E32;background-color:#DEDEDE;"><b>Following Month</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td><td><b>Yesterday</b></td>
                            <td><b>Month to Date</b></td>
                            <td><b>Daily Ave.</b></td>
                        </tr>';
                        $flash_date = strtotime($flashData['DailyFlash']['date']);
                            $day = date('d',$flash_date);
                            $prev_day = $day - 1;
                            $d12 = round($monthToDateArr[0][0]['reservation_next']/$prev_day,2);
                            $d13 = round($monthToDateArr[0][0]['room_night_next']/$prev_day,2);
                       $html234 .= '<tr>
                            <td><b>New Bookings</b></td>
                            <td>'.$flashData['DailyFlash']['reservation_next'].'</td>
                            <td>'.$monthToDateArr[0][0]['reservation_next'].'</td>
                            <td>'.$d12.'</td>
                        </tr>
                        <tr>
                            <td><b>New Room Nights</b></td>
                            <td>'.$flashData['DailyFlash']['room_night_next'].'</td>
                            <td>'.$monthToDateArr[0][0]['room_night_next'].'</td>
                            <td>'.$d13.'</td>
                        </tr>
                        <tr>
                            <td><b>ADR</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue_next']/$flashData['DailyFlash']['room_night_next'],2).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_next']/$monthToDateArr[0][0]['room_night_next'],2).'</td>
                            <td>'.number_format(($monthToDateArr[0][0]['rooms_revenue_next']/$prev_day)/$d13,2).'</td>
                        </tr>
                        <tr>
                            <td><b>Revenue</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue_next'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_next'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_next']/$prev_day,0).'</td>
                        </tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr>
                            <td style="color:#E32E32;background-color:#DEDEDE;"><b>Future Month</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td><td><b>Yesterday</b></td>
                            <td><b>Month to Date</b></td>
                            <td><b>Daily Ave.</b></td>
                        </tr>';
                        $flash_date = strtotime($flashData['DailyFlash']['date']);
                            $day = date('d',$flash_date);
                            $prev_day = $day - 1;
                        
                            $d12 = round($monthToDateArr[0][0]['reservation_future']/$prev_day,2);
                            $d13 = round($monthToDateArr[0][0]['room_night_future']/$prev_day,2);
                        $html234 .= '<tr>
                            <td><b>New Bookings</b></td>
                            <td>'.$flashData['DailyFlash']['reservation_future'].'</td>
                            <td>'.$monthToDateArr[0][0]['reservation_future'].'</td>
                            <td>'.$d12.'</td>
                        </tr>
                        <tr>
                            <td><b>New Room Nights</b></td>
                            <td>'.$flashData['DailyFlash']['room_night_future'].'</td>
                            <td>'.$monthToDateArr[0][0]['room_night_future'].'</td>
                            <td>'.$d13.'</td>
                        </tr>
                        <tr>
                            <td><b>ADR</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue_future']/$flashData['DailyFlash']['room_night_future'],2).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_future']/$monthToDateArr[0][0]['room_night_future'],2).'</td>
                            <td>'.number_format(($monthToDateArr[0][0]['rooms_revenue_future']/$prev_day)/$d13,2).'</td>
                        </tr>
                        <tr>
                            <td><b>Revenue</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue_future'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_future'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_future']/$prev_day,0).'</td>
                        </tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Market Segmentation</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td style="text-align:center;"><b>Last Night</b></td><td>&nbsp;</td><td style="text-align:center;"><b>Month to Date</b></td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td><b>Room Nights</b></td><td><b>ADR</b></td><td><b>Room Nights</b></td><td><b>ADR</b></td></tr>';

                        if(!empty($marketsegments)){
                                foreach($marketsegments as $seg_key => $seg_val){
                            $html234 .= '<tr>
                                <td><b>'.$seg_val.'</b></td>
                                <td>'.$bob_segments[$seg_key].'</td>
                                <td>'.$adr_segments[$seg_key].'</td>
                                <td>'.$month_bob_segments[$seg_key].'</td>
                                <td>'.$month_adr_segments[$seg_key].'</td>
                            </tr>';
                            }
                        }
                    $html234 .= '</table><p></p>
                                <table border="1" cellpadding="2" cellspacing="1">
                            <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>7-Day Summary (forecast)</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                            <tr>
                                <td>&nbsp;</td>';
                                $flash_date = strtotime($flashData['DailyFlash']['date']);
                                for($i=1;$i<=7;$i++){
                        $html234 .= '<td><b>';
                                    $date = date('Y-m-d', strtotime("+".$i." day", $flash_date));
                         $html234 .= date('D',strtotime($date));
                        $html234 .=  " - ".date('d/m',strtotime($date));
                                    $html234 .= '</b></td>';
                                }
                                
                            $html234 .= '</tr>
                              <tr>
                                <td><b>Occupied</b></td>';
                                for($i=1;$i<=7;$i++){
                                $date = date('Y-m-d', strtotime("+".$i." day", $flash_date)); 
                                $html234 .= '<td>'.$bob_value[ltrim(date('d',strtotime($date)),'0')].'</td>';
                                }
                            $html234 .= '</tr>
                              <tr>
                                <td><b>ADR</b></td>';
                                for($i=1;$i<=7;$i++){
                                $date = date('Y-m-d', strtotime("+".$i." day", $flash_date));
                                    $html234 .= '<td>'.number_format($adr_value[ltrim(date('d',strtotime($date)),'0')],2).'</td>';
                                }
                            $html234 .= '</tr>
                              <tr>
                                <td><b>Note</b></td>';
                                for($i=1;$i<=7;$i++){
                                $date = date('Y-m-d', strtotime("+".$i." day", $flash_date));
                                    $html234 .= '<td>'.$notes_value[ltrim(date('d',strtotime($date)),'0')].'</td>';
                                }
                            $html234 .= '</tr>
                        </table><p></p><hr/>
 		<h2>Financial Summary</h2>';
                     
                    $number_of_rooms = $flashData['Client']['number_of_rooms'] == '' ? '1' : $flashData['Client']['number_of_rooms'];
                    $restaurant_open_hours = $flashData['Client']['restaurant_open_hours'] == '' ? '6' : $flashData['Client']['restaurant_open_hours'];
                    $chairs_in_restaurant = $flashData['Client']['chairs_in_restaurant'] == '' ? '1' : $flashData['Client']['chairs_in_restaurant'];
                    $number_of_adult = $flashData['DailyFlash']['number_of_adults']; 
                    $number_of_children = $flashData['DailyFlash']['number_of_childrens'];
                    
                $html234 .= '<table border="1" cellpadding="2" cellspacing="1">
                            <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Rooms</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                            <tr><td>&nbsp;</td><td><b>Last Night</b></td><td><b>Month to Date</b></td><td><b>Target</b></td></tr>
                            <tr>
                                <td><b>Occupied</b></td>
                                <td>'.$flashData['DailyFlash']['occupied'].'</td>
                                <td>'.$monthToDateArr[0][0]['occupied'].'</td>
                                <td>'.$total_field_value['63'].'</td>
                            </tr>
                            <tr>
                                <td><b>Ave Daily Rate</b></td>
                                <td>'.number_format($flashData['DailyFlash']['revenue']/$flashData['DailyFlash']['occupied'],2).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['revenue']/$monthToDateArr[0][0]['occupied'],2).'</td>
                                <td>'.number_format($total_field_value['69']/$total_field_value['63'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Revenue</b></td>
                                <td>'.number_format($flashData['DailyFlash']['revenue'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['revenue'],0).'</td>
                                <td>'.number_format($total_field_value['69'],0).'</td>
                            </tr>
                            <tr>
                                <td><b>RevPAR</b></td>
                                <td>'.number_format($flashData['DailyFlash']['revenue']/$number_of_rooms,2).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['revenue']/$number_of_rooms,2).'</td>
                                <td>'.number_format($total_field_value['70'],2).'</td>
                            </tr>';

                            if($flashData['DailyFlash']['breakfast_included'] == '1'){
                            $html234 .= '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                            <tr><td><b>Adults in house</b></td><td>&nbsp;</td><td ><b>'.$number_of_adult.'(Breakfast Deduction : '.$flashData['DailyFlash']['deduction'].')</b></td><td>&nbsp;</td></tr>
                             <tr><td><b>Children in house</b></td><td>&nbsp;</td><td><b>'.$number_of_children.' (Breakfast Deduction : '.$flashData['DailyFlash']['child_deduction'].')</b></td><td>&nbsp;</td></tr>';
                             }
                             
                        $html234 .= '</table><p></p>
                        <table border="1" cellpadding="2" cellspacing="1">
                            <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Restaurant</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                            <tr><td>&nbsp;</td><td><b>Last Night</b></td><td><b>Month to Date</b></td><td><b>Forecast</b></td></tr>
                            <tr>';
                               $total_rev = (float)$flashData['DailyFlash']['food_revenue'] + (float)$flashData['DailyFlash']['bev_revenue'];
                                $monthTodate_rev = $monthToDateArr[0][0]['food_revenue'] + $monthToDateArr[0][0]['bev_revenue'];
                               
                                $html234 .= '<td><b>Covers</b></td>
                                <td>'.$flashData['DailyFlash']['covers'].'</td>
                                <td>'.$monthToDateArr[0][0]['covers'].'</td>
                                <td>'.$total_restaurant['93'].'</td>
                            </tr>
                            <tr>
                                <td><b>Ave Spend</b></td>
                                <td>'.number_format($total_rev/$flashData['DailyFlash']['covers'],2).'</td>
                                <td>'.number_format($monthTodate_rev/$monthToDateArr[0][0]['covers'],2).'</td>
                                <td>'.number_format($total_restaurant['85'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Revenue</b></td>
                                <td>'.number_format($total_rev,0).'</td>
                                <td>'.number_format($monthTodate_rev,0).'</td>
                                <td>'.number_format($total_restaurant['69'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>RevPASH</b></td>
                                <td>'.number_format($total_rev/($restaurant_open_hours * $chairs_in_restaurant),2).'</td>
                                <td>'.number_format($monthTodate_rev/($restaurant_open_hours * $chairs_in_restaurant),2).'</td>
                                <td>'.number_format($total_restaurant['82'],2).'</td>
                            </tr>
                        </table><p></p>
                        <table border="1" cellpadding="2" cellspacing="1">
                            <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Other Revenues</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><b>#People</b></td>
                                <td><b>Month To Date</b></td>
                                <td><b>Revenue</b></td>
                                <td><b>Month To Date</b></td>
                                <td><b>Average Spent/Person</b></td>
                            </tr>
                            <tr>
                                <td><b>Golf</b></td>
                                <td>'.$flashData['DailyFlash']['golf_people'].'</td>
                                <td>'.$monthToDateArr[0][0]['golf_people'].'</td>
                                <td>'.number_format($flashData['DailyFlash']['golf_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['golf_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['golf_rev']/$monthToDateArr[0][0]['golf_people'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Weddings/Events</b></td>
                                <td>'.$flashData['DailyFlash']['event_people'].'</td>
                                <td>'.$monthToDateArr[0][0]['event_people'].'</td>
                                <td>'.number_format($flashData['DailyFlash']['event_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['event_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['event_rev']/$monthToDateArr[0][0]['event_people'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Conference</b></td>
                                <td>'.$flashData['DailyFlash']['conference_people'].'</td>
                                <td>'.$monthToDateArr[0][0]['conference_people'].'</td>
                                <td>'.number_format($flashData['DailyFlash']['conference_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['conference_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['conference_rev']/$monthToDateArr[0][0]['conference_people'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Watersports</b></td>
                                <td>'.$flashData['DailyFlash']['sports_people'].'</td>
                                <td>'.$monthToDateArr[0][0]['sports_people'].'</td>
                                <td>'.number_format($flashData['DailyFlash']['sports_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['sports_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['sports_rev']/$monthToDateArr[0][0]['sports_people'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Other</b></td>
                                <td>'.$flashData['DailyFlash']['other_people'].'</td>
                                <td>'.$monthToDateArr[0][0]['other_people'].'</td>
                                <td>'.number_format($flashData['DailyFlash']['other_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['other_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['other_rev']/$monthToDateArr[0][0]['other_people'],2).'</td>
                            </tr>
                        </table><p></p>
                        <table border="1" cellpadding="2" cellspacing="1">
                            <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Banking</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><b>Cash</b></td>
                            <td><b>Credit</b></td>
                            <td><b>EFT</b></td> 
                        </tr>';
                                
                        $cash_total = $financeData['FlashFinance']['rooms_cash'] + $financeData['FlashFinance']['restaurant_cash'] + $financeData['FlashFinance']['bar_cash'] + $financeData['FlashFinance']['advance_cash'];
                        $advance_total = $financeData['FlashFinance']['rooms_credit'] + $financeData['FlashFinance']['restaurant_credit'] + $financeData['FlashFinance']['bar_credit'] + $financeData['FlashFinance']['advance_credit'];
                        $eft_total = $financeData['FlashFinance']['rooms_eft'] + $financeData['FlashFinance']['restaurant_eft'] + $financeData['FlashFinance']['bar_eft'] + $financeData['FlashFinance']['advance_eft'];
                       $grand_total = $cash_total + $advance_total + $eft_total;
                        $html234 .= '<tr>
                            <td><b>Rooms</b></td>
                            <td>'.$financeData['FlashFinance']['rooms_cash'].'</td>
                            <td>'.$financeData['FlashFinance']['rooms_credit'].'</td>
                            <td>'.$financeData['FlashFinance']['rooms_eft'].'</td>
                        </tr>
                        <tr>
                            <td><b>Restaurant</b></td>
                            <td>'.$financeData['FlashFinance']['restaurant_cash'].'</td>
                            <td>'.$financeData['FlashFinance']['restaurant_credit'].'</td>
                            <td>'.$financeData['FlashFinance']['restaurant_eft'].'</td>
                        </tr>
                        <tr>
                            <td><b>Bar</b></td>
                            <td>'.$financeData['FlashFinance']['bar_cash'].'</td>
                            <td>'.$financeData['FlashFinance']['bar_credit'].'</td>
                            <td>'.$financeData['FlashFinance']['bar_eft'].'</td>
                        </tr>
                        <tr>
                            <td><b>Advance Deposits</b></td>
                            <td>'.$financeData['FlashFinance']['advance_cash'].'</td>
                            <td>'.$financeData['FlashFinance']['advance_credit'].'</td>
                            <td>'.$financeData['FlashFinance']['advance_eft'].'</td>
                        </tr>
                        <tr>
                            <td><b>Sub Total</b></td>
                            <td><b>'.$cash_total.'</b></td>
                            <td><b>'.$advance_total.'</b></td>
                            <td><b>'.$eft_total.'</b></td>
                        </tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td><b>Grand Total</b></td><td><b>'.$grand_total.'</b></td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    </table><p></p>';
                  
                    $opening_balance = '0';  $cash_banked = '0'; $total_monies = '';
                    $hand_cash_tin =''; $hand_restuarant_tin = ''; $hand_bar_tin = ''; $hand_floats = ''; $hand_miscellaneous = ''; $hand_adv_deposit = ''; $hand_data = ''; $hand_rooms ='';
                    $received_restaurant = ''; $received_bar=''; $received_accomodation = ''; $received_account = ''; $received_data=''; $received_paid_in_tin='';
                    $pay_1 =''; $pay_2 =''; $pay_3 = '';
                    $monies_tips = ''; $monies_wood = ''; $monies_bread = ''; $monies_youghurt = ''; $monies_casual_wages = ''; $monies_banked = '';
                    if(!empty($cashData)){
                        foreach($cashData as $clash_flash){
                            if($clash_flash['FlashCash']['cash_type'] == 'Hand'){
                               if($clash_flash['FlashCash']['name'] == 'rooms'){
                                    $hand_rooms = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'data'){
                                    $hand_data = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'adv_deposit'){
                                    $hand_adv_deposit = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'miscellaneous'){
                                    $hand_miscellaneous = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'cash_tin'){
                                    $hand_cash_tin = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'restuarant_tin'){
                                    $hand_restuarant_tin = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'bar_tin'){
                                    $hand_bar_tin = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'floats'){
                                    $hand_floats = $clash_flash['FlashCash']['value'];
                                }
                                $opening_balance = $opening_balance + $clash_flash['FlashCash']['value'];
                            }elseif($clash_flash['FlashCash']['cash_type'] == 'Received'){
                                if($clash_flash['FlashCash']['name'] == 'restaurant'){
                                    $received_restaurant = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'bar'){
                                    $received_bar = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'accomodation'){
                                    $received_accomodation = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'account'){
                                    $received_account = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'data'){
                                    $received_data = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'paid_in_tin'){
                                    $received_paid_in_tin = $clash_flash['FlashCash']['value'];
                                }
                            }elseif($clash_flash['FlashCash']['cash_type'] == 'Payment'){
                                if($clash_flash['FlashCash']['name'] == '1'){
                                    $pay_1 = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == '2'){
                                    $pay_2 = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == '3'){
                                    $pay_3 = $clash_flash['FlashCash']['value'];
                                }
                                $cash_banked = $cash_banked + $clash_flash['FlashCash']['value'];
                            }elseif($clash_flash['FlashCash']['cash_type'] == 'Monies'){
                                if($clash_flash['FlashCash']['name'] == 'tips'){
                                    $monies_tips = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'wood'){
                                    $monies_wood = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'bread'){
                                    $monies_bread = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'youghurt'){
                                    $monies_youghurt = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'casual_wages'){
                                    $monies_casual_wages = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'banked'){
                                    $monies_banked = $clash_flash['FlashCash']['value'];
                                }
                                $total_monies = $total_monies + $clash_flash['FlashCash']['value'];
                            }
                        }
                    }
                    
                    $html234 .= '<table  border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Cash on Hand</b></td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr>
                            <td>Rooms</td><td>'.$hand_rooms.'</td>
                        </tr>
                        <tr>
                            <td>Cash Tin</td><td>'.$hand_cash_tin.'</td>
                        </tr>
                        <tr>
                            <td>Rest. Tin</td><td>'.$hand_restuarant_tin.'</td>
                        </tr>
                        <tr>
                            <td>Bar Tin</td><td>'.$hand_bar_tin.'</td>
                        </tr>
                        <tr>
                            <td>Floats</td><td>'.$hand_floats.'</td>
                        </tr>
                        <tr>
                            <td>Data</td><td>'.$hand_data.'</td>
                        </tr>
                        <tr>
                            <td>Advance deposits</td><td>'.$hand_adv_deposit.'</td>
                        </tr>
                        <tr>
                            <td>Miscellaneous</td><td>'.$hand_miscellaneous.'</td>
                        </tr>
                        <tr>
                            <td><b>Opening Balance</b></td><td><b>'.$opening_balance.'</b></td>
                        </tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Monies Paid-out</b></td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr>
                            <td>Tips</td><td>'.$monies_tips.'</td>
                        </tr>
                        <tr>
                            <td>Wood</td><td>'.$monies_wood.'</td>
                        </tr>
                        <tr>
                            <td>Bread</td><td>'.$monies_bread.'</td>
                        </tr>
                        <tr>
                            <td>Yogurt</td><td>'.$monies_youghurt.'</td>
                        </tr>
                        <tr>
                            <td>Casual Wages</td><td>'.$monies_casual_wages.'</td>
                        </tr>
                        <tr>
                            <td>Banked</td><td>'.$monies_banked.'</td>
                        </tr>
                        <tr>
                            <td><b>Total</b></td><td><b>'.$total_monies.'</b></td>
                        </tr>
                    </table>
                    <p></p>
                    <table  border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Cash Received</b></td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr>
                            <td>Restaurant</td><td>'.$received_restaurant.'</td>
                        </tr>
                        <tr>
                            <td>BAR</td><td>'.$received_bar.'</td>
                        </tr>
                        <tr>
                            <td>Accommodation</td><td>'.$received_accomodation.'</td>
                        </tr>
                        <tr>
                            <td>Account</td><td>'.$received_account.'</td>
                        </tr>
                        <tr>
                            <td>Data</td><td>'.$received_data.'</td>
                        </tr>
                        <tr>
                            <td>Cash Paid into Tin</td><td>'.$received_paid_in_tin.'</td>
                        </tr>
                    </table><p></p>
                    <table  border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Cash Payments</b></td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr>
                            <td>#1</td><td>'.$pay_1.'</td>
                        </tr>
                        <tr>
                            <td>#2</td><td>'.$pay_2.'</td>
                        </tr>
                        <tr>
                            <td>#3</td><td>'.$pay_3.'</td>
                        </tr>
                        <tr>
                            <td><b>Cash Banked</b></td><td><b>'.$cash_banked.'</b></td>
                        </tr>
                    </table>';

//echo $html234; exit;
                
                
//$pdf->SetXY(125,15);

$pdf->SetXY(125,15);
$pdf->writeHTML($hotelname.' Flash Report', true, false, true, false, '');
                    
$pdf->SetXY(10,50);
$pdf->writeHTML($html234, true, false, true, false, '');

ob_end_clean();
//Close and output PDF document
$pdf->Output("FlashReport_".date('d-M-Y').".pdf", 'D');

exit;


function findexts ($filename) 
 { 
	$filename = strtolower($filename) ;
	$exts = split("[/\\.]", $filename) ;
	$n = count($exts)-1;
	$exts = $exts[$n];
	return $exts;
 } 
                    
?>