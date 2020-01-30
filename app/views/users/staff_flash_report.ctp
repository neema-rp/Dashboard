
<?php ?>
<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __('Daily Flash Report'); ?> <small><i class="icon-double-angle-right"></i><?php echo date('d F Y',strtotime($flashData['DailyFlash']['date'])); ?></small></h1>
        </div>

                <div class="row-fluid">
                <h3 class="header smaller lighter green"><?php __('Daily Operations'); ?></h3>
 		    <table>
                        <tr><td style="width:30%;"><b>Arrivals</b></td><td><?php echo $flashData['DailyFlash']['total_arrival']; ?></td></tr>
                        <tr><td><b>Departures</b></td><td><?php echo $flashData['DailyFlash']['total_departure']; ?></td></tr>
                        <tr><td><b>Group Arrivals</b></td><td><?php echo $flashData['DailyFlash']['group_arrival']; ?></td></tr>
                        <tr><td><b>Group Departures</b></td><td><?php echo $flashData['DailyFlash']['group_departure']; ?></td></tr>
                    </table>

                    <table>
                        <tr><td class="thead_title"><b><?php __('Guest Comments'); ?></b></td></tr>
                        <tr><td><?php echo $flashData['DailyFlash']['comments']; ?></td></tr>
                    </table>

                    <table>
                        <tr><td class="thead_title"><b><?php __('Site Inspection Rooms'); ?></b></td></tr>
                        <tr><td><?php echo $flashData['DailyFlash']['inspection_comments']; ?></td></tr>
                    </table>

                    <table>
                        <tr><td class="thead_title"><b><?php __('Maintenance Rooms'); ?></b></td></tr>
                        <tr><td><?php echo $flashData['DailyFlash']['maintainance_comments']; ?></td></tr>
                    </table>
                </div>
                
                
                <div class="row-fluid">
                <h3 class="header smaller lighter green"><?php __('Reservations Summary'); ?></h3>
 		    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td colspan="4" class="thead_title"><b>Current Month</b></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td><td><b>Yesterday</b></td>
                            <td><b>Month to Date</b></td>
                            <td><b>Daily Ave.</b></td>
                        </tr>
                        <?php $flash_date = strtotime($flashData['DailyFlash']['date']);
                            $day = date('d',$flash_date);
                            $prev_day = $day - 1;
                            ?>

                        <tr>
                            <td><b>New Bookings</b></td>
                            <td><?php echo $flashData['DailyFlash']['reservation']; ?></td>
                            <td><?php echo $monthToDateArr[0][0]['reservation']; ?></td>
                            <td><?php echo $d12 = round($monthToDateArr[0][0]['reservation']/$prev_day,2); ?></td>
                        </tr>
                        <tr>
                            <td><b>New Room Nights</b></td>
                            <td><?php echo $flashData['DailyFlash']['room_night']; ?></td>
                            <td><?php echo $monthToDateArr[0][0]['room_night']; ?></td>
                            <td><?php echo $d13 = round($monthToDateArr[0][0]['room_night']/$prev_day,2); ?></td>
                        </tr>
                        <tr>
                            <td><b>ADR</b></td>
                            <td><?php echo number_format($flashData['DailyFlash']['rooms_revenue']/$flashData['DailyFlash']['room_night'],2); ?></td>
                            <td><?php echo number_format($monthToDateArr[0][0]['rooms_revenue']/$monthToDateArr[0][0]['room_night'],2); ?></td>
                            <td><?php echo  number_format(($monthToDateArr[0][0]['rooms_revenue']/$prev_day)/$d13,2); ?></td>
                        </tr>
                        <tr>
                            <td><b>Revenue</b></td>
                            <td><?php echo number_format($flashData['DailyFlash']['rooms_revenue'],0); ?></td>
                            <td><?php echo number_format($monthToDateArr[0][0]['rooms_revenue'],0); ?></td>
                            <td><?php echo number_format($monthToDateArr[0][0]['rooms_revenue']/$prev_day,0); ?></td>
                        </tr>
                    </table>
                
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td colspan="4" class="thead_title"><b>Following Month</b></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td><td><b>Yesterday</b></td>
                            <td><b>Month to Date</b></td>
                            <td><b>Daily Ave.</b></td>
                        </tr>
                        <?php $flash_date = strtotime($flashData['DailyFlash']['date']);
                            $day = date('d',$flash_date);
                            $prev_day = $day - 1;
                            ?>

                        <tr>
                            <td><b>New Bookings</b></td>
                            <td><?php echo $flashData['DailyFlash']['reservation_next']; ?></td>
                            <td><?php echo $monthToDateArr[0][0]['reservation_next']; ?></td>
                            <td><?php echo $d12 = round($monthToDateArr[0][0]['reservation_next']/$prev_day,2); ?></td>
                        </tr>
                        <tr>
                            <td><b>New Room Nights</b></td>
                            <td><?php echo $flashData['DailyFlash']['room_night_next']; ?></td>
                            <td><?php echo $monthToDateArr[0][0]['room_night_next']; ?></td>
                            <td><?php echo $d13 = round($monthToDateArr[0][0]['room_night_next']/$prev_day,2); ?></td>
                        </tr>
                        <tr>
                            <td><b>ADR</b></td>
                            <td><?php echo number_format($flashData['DailyFlash']['rooms_revenue_next']/$flashData['DailyFlash']['room_night_next'],2); ?></td>
                            <td><?php echo number_format($monthToDateArr[0][0]['rooms_revenue_next']/$monthToDateArr[0][0]['room_night_next'],2); ?></td>
                            <td><?php echo  number_format(($monthToDateArr[0][0]['rooms_revenue_next']/$prev_day)/$d13,2); ?></td>
                        </tr>
                        <tr>
                            <td><b>Revenue</b></td>
                            <td><?php echo number_format($flashData['DailyFlash']['rooms_revenue_next'],0); ?></td>
                            <td><?php echo number_format($monthToDateArr[0][0]['rooms_revenue_next'],0); ?></td>
                            <td><?php echo number_format($monthToDateArr[0][0]['rooms_revenue_next']/$prev_day,0); ?></td>
                        </tr>
                    </table>
                
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td colspan="4" class="thead_title"><b>Future Month</b></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td><td><b>Yesterday</b></td>
                            <td><b>Month to Date</b></td>
                            <td><b>Daily Ave.</b></td>
                        </tr>
                        <?php $flash_date = strtotime($flashData['DailyFlash']['date']);
                            $day = date('d',$flash_date);
                            $prev_day = $day - 1;
                            ?>
                        <tr>
                            <td><b>New Bookings</b></td>
                            <td><?php echo $flashData['DailyFlash']['reservation_future']; ?></td>
                            <td><?php echo $monthToDateArr[0][0]['reservation_future']; ?></td>
                            <td><?php echo $d12 = round($monthToDateArr[0][0]['reservation_future']/$prev_day,2); ?></td>
                        </tr>
                        <tr>
                            <td><b>New Room Nights</b></td>
                            <td><?php echo $flashData['DailyFlash']['room_night_future']; ?></td>
                            <td><?php echo $monthToDateArr[0][0]['room_night_future']; ?></td>
                            <td><?php echo $d13 = round($monthToDateArr[0][0]['room_night_future']/$prev_day,2); ?></td>
                        </tr>
                        <tr>
                            <td><b>ADR</b></td>
                            <td><?php echo number_format($flashData['DailyFlash']['rooms_revenue_future']/$flashData['DailyFlash']['room_night_future'],2); ?></td>
                            <td><?php echo number_format($monthToDateArr[0][0]['rooms_revenue_future']/$monthToDateArr[0][0]['room_night_future'],2); ?></td>
                            <td><?php echo  number_format(($monthToDateArr[0][0]['rooms_revenue_future']/$prev_day)/$d13,2); ?></td>
                        </tr>
                        <tr>
                            <td><b>Revenue</b></td>
                            <td><?php echo number_format($flashData['DailyFlash']['rooms_revenue_future'],0); ?></td>
                            <td><?php echo number_format($monthToDateArr[0][0]['rooms_revenue_future'],0); ?></td>
                            <td><?php echo number_format($monthToDateArr[0][0]['rooms_revenue_future']/$prev_day,0); ?></td>
                        </tr>
                    </table>
                    
                    <table class="table table-striped table-bordered table-hover">
                        <tr><td colspan="5" class="thead_title"><b><?php __('Market Segmentation'); ?></b></td></tr>
                        <tr><td>&nbsp;</td><td colspan="2" style="text-align:center;"><b>Last Night</b></td><td colspan="2" style="text-align:center;"><b>Month to Date</b></td></tr>
                        <tr><td>&nbsp;</td><td><b>Room Nights</b></td><td><b>ADR</b></td><td><b>Room Nights</b></td><td><b>ADR</b></td></tr>
                        <?php if(!empty($marketsegments)){
                                foreach($marketsegments as $seg_key => $seg_val){ ?>
                            <tr>
                                <td><b><?php echo $seg_val; ?></b></td>
                                <td><?php echo $bob_segments[$seg_key]; ?></td>
                                <td><?php echo $adr_segments[$seg_key]; ?></td>
                                <td><?php echo $month_bob_segments[$seg_key]; ?></td>
                                <td><?php echo $month_adr_segments[$seg_key]; ?></td>
                            </tr>
                        <?php }
                        }
                        ?>
                    </table>
                
                        <table class="table table-striped table-bordered table-hover">
                            <tr><td colspan="8" class="thead_title"><b><?php __('7-Day Summary (forecast)'); ?></b></td></tr>
                            <tr>
                                <td>&nbsp;</td>
                                <?php $flash_date = strtotime($flashData['DailyFlash']['date']);
                                for($i=1;$i<=7;$i++){ ?>
                                    <td><b><?php //$date = date('Y-m-'.$i);
                                    $date = date('Y-m-d', strtotime("+".$i." day", $flash_date));
                                    echo date('D',strtotime($date));
                                    echo " - ".date('d/m',strtotime($date));
                                    ?></b></td>
                                <?php }
                                ?>
                            </tr>
                              <tr>
                                <td><b>Occupied</b></td>
                                <?php for($i=1;$i<=7;$i++){
                                $date = date('Y-m-d', strtotime("+".$i." day", $flash_date)); ?>
                                <td><?php echo $bob_value[ltrim(date('d',strtotime($date)),'0')]; ?></td>
                                <?php } ?>
                            </tr>
                              <tr>
                                <td><b>ADR</b></td>
                                <?php for($i=1;$i<=7;$i++){
                                $date = date('Y-m-d', strtotime("+".$i." day", $flash_date));  ?>
                                    <td><?php echo number_format($adr_value[ltrim(date('d',strtotime($date)),'0')],2); ?></td>
                                <?php } ?>
                            </tr>
                              <tr>
                                <td><b>Note</b></td>
                                <?php for($i=1;$i<=7;$i++){
                                $date = date('Y-m-d', strtotime("+".$i." day", $flash_date));  ?>
                                    <td><?php echo $notes_value[ltrim(date('d',strtotime($date)),'0')]; ?></td>
                                <?php } ?>
                            </tr>
                        </table>
                </div>
                
                
                <div class="row-fluid">
                <h3 class="header smaller lighter green"><?php __('Financial Summary'); ?></h3>
 		     <?php
                    $number_of_rooms = $flashData['Client']['number_of_rooms'] == '' ? '1' : $flashData['Client']['number_of_rooms'];
                    $restaurant_open_hours = $flashData['Client']['restaurant_open_hours'] == '' ? '6' : $flashData['Client']['restaurant_open_hours'];
                    $chairs_in_restaurant = $flashData['Client']['chairs_in_restaurant'] == '' ? '1' : $flashData['Client']['chairs_in_restaurant'];
                    $number_of_adult = $flashData['DailyFlash']['number_of_adults']; 
                    $number_of_children = $flashData['DailyFlash']['number_of_childrens'];
                    ?>
               
                        <table class="table table-striped table-bordered table-hover">
                            <tr><td colspan="4" class="thead_title"><b><?php __('Rooms'); ?></b></td></tr>
                            <tr><td>&nbsp;</td><td><b>Last Night</b></td><td><b>Month to Date</b></td><td><b>Target</b></td></tr>
                            <tr>
                                <td><b>Occupied</b></td>
                                <td><?php echo $flashData['DailyFlash']['occupied']; ?></td>
                                <td><?php echo $monthToDateArr[0][0]['occupied']; ?></td>
                                <td><?php echo $total_field_value['63']; ?></td>
                            </tr>
                            <tr>
                                <td><b>Ave Daily Rate</b></td>
                                <td><?php echo number_format($flashData['DailyFlash']['revenue']/$flashData['DailyFlash']['occupied'],2); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['revenue']/$monthToDateArr[0][0]['occupied'],2); ?></td>
                                <td><?php echo number_format($total_field_value['69']/$total_field_value['63'],2); ?></td>
                            </tr>
                            <tr>
                                <td><b>Revenue</b></td>
                                <td><?php echo number_format($flashData['DailyFlash']['revenue'],0); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['revenue'],0); ?></td>
                                <td><?php echo number_format($total_field_value['69'],0); ?></td>
                            </tr>
                            <tr>
                                <td><b>RevPAR</b></td>
                                <td><?php echo number_format($flashData['DailyFlash']['revenue']/$number_of_rooms,2); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['revenue']/$number_of_rooms,2); ?></td>
                                <td><?php echo number_format($total_field_value['70'],2); ?></td>
                            </tr>

                            <?php if($flashData['DailyFlash']['breakfast_included'] == '1'){ ?>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><td colspan="2"><b>Adults in house</b></td><td colspan="2"><b><?php echo  $number_of_adult; ?> (Breakfast Deduction : <?php echo $flashData['DailyFlash']['deduction']; ?>)</b></td></tr>
                             <tr><td colspan="2"><b>Children in house</b></td><td colspan="2"><b><?php echo  $number_of_children; ?> (Breakfast Deduction : <?php echo $flashData['DailyFlash']['child_deduction']; ?>)</b></td></tr>
                             <?php } ?>
                        </table>

                        <table class="table table-striped table-bordered table-hover">
                            <tr><td colspan="4" class="thead_title"><b><?php __('Restaurant'); ?></b></td></tr>
                            <tr><td>&nbsp;</td><td><b>Last Night</b></td><td><b>Month to Date</b></td><td><b>Forecast</b></td></tr>
                            <tr>
                                <?php $total_rev = (float)$flashData['DailyFlash']['food_revenue'] + (float)$flashData['DailyFlash']['bev_revenue'];
                                $monthTodate_rev = $monthToDateArr[0][0]['food_revenue'] + $monthToDateArr[0][0]['bev_revenue'];
                                ?>
                                <td><b>Covers</b></td>
                                <td><?php echo $flashData['DailyFlash']['covers']; ?></td>
                                <td><?php echo $monthToDateArr[0][0]['covers']; ?></td>
                                <td><?php echo $total_restaurant['93']; ?></td>
                            </tr>
                            <tr>
                                <td><b>Ave Spend</b></td>
                                <td><?php echo number_format($total_rev/$flashData['DailyFlash']['covers'],2); ?></td>
                                <td><?php echo number_format($monthTodate_rev/$monthToDateArr[0][0]['covers'],2); ?></td>
                                <td><?php echo number_format($total_restaurant['85'],2); ?></td>
                            </tr>
                            <tr>
                                <td><b>Revenue</b></td>
                                <td><?php echo number_format($total_rev,0); ?></td>
                                <td><?php echo number_format($monthTodate_rev,0); ?></td>
                                <td><?php echo number_format($total_restaurant['69'],2); ?></td>
                            </tr>
                            <tr>
                                <td><b>RevPASH</b></td>
                                <td><?php echo number_format($total_rev/($restaurant_open_hours * $chairs_in_restaurant),2); ?></td>
                                <td><?php echo number_format($monthTodate_rev/($restaurant_open_hours * $chairs_in_restaurant),2); ?></td>
                                <td><?php echo number_format($total_restaurant['82'],2); ?></td>
                            </tr>
                        </table>
               
                        <table class="table table-striped table-bordered table-hover">
                            <tr><td colspan="6" class="thead_title"><b><?php __('Other Revenues'); ?></b></td></tr>
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
                                <td><?php echo $flashData['DailyFlash']['golf_people']; ?></td>
                                <td><?php echo $monthToDateArr[0][0]['golf_people']; ?></td>
                                <td><?php echo number_format($flashData['DailyFlash']['golf_rev'],0); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['golf_rev'],0); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['golf_rev']/$monthToDateArr[0][0]['golf_people'],2); ?></td>
                            </tr>
                            <tr>
                                <td><b>Weddings/Events</b></td>
                                <td><?php echo $flashData['DailyFlash']['event_people']; ?></td>
                                <td><?php echo $monthToDateArr[0][0]['event_people']; ?></td>
                                <td><?php echo number_format($flashData['DailyFlash']['event_rev'],0); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['event_rev'],0); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['event_rev']/$monthToDateArr[0][0]['event_people'],2); ?></td>
                            </tr>
                            <tr>
                                <td><b>Conference</b></td>
                                <td><?php echo $flashData['DailyFlash']['conference_people']; ?></td>
                                <td><?php echo $monthToDateArr[0][0]['conference_people']; ?></td>
                                <td><?php echo number_format($flashData['DailyFlash']['conference_rev'],0); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['conference_rev'],0); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['conference_rev']/$monthToDateArr[0][0]['conference_people'],2); ?></td>
                            </tr>
                            <tr>
                                <td><b>Watersports</b></td>
                                <td><?php echo $flashData['DailyFlash']['sports_people']; ?></td>
                                <td><?php echo $monthToDateArr[0][0]['sports_people']; ?></td>
                                <td><?php echo number_format($flashData['DailyFlash']['sports_rev'],0); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['sports_rev'],0); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['sports_rev']/$monthToDateArr[0][0]['sports_people'],2); ?></td>
                            </tr>
                            <tr>
                                <td><b>Other</b></td>
                                <td><?php echo $flashData['DailyFlash']['other_people']; ?></td>
                                <td><?php echo $monthToDateArr[0][0]['other_people']; ?></td>
                                <td><?php echo number_format($flashData['DailyFlash']['other_rev'],0); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['other_rev'],0); ?></td>
                                <td><?php echo number_format($monthToDateArr[0][0]['other_rev']/$monthToDateArr[0][0]['other_people'],2); ?></td>
                            </tr>
                        </table>
                
                
                        <table class="table table-striped table-bordered table-hover">
                            <tr><td colspan="4" class="thead_title"><b><?php __('Banking'); ?></b></td></tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><b>Cash</b></td>
                            <td><b>Credit</b></td>
                            <td><b>EFT</b></td> 
                        </tr>
                        <?php
                        $cash_total = $financeData['FlashFinance']['rooms_cash'] + $financeData['FlashFinance']['restaurant_cash'] + $financeData['FlashFinance']['bar_cash'] + $financeData['FlashFinance']['advance_cash'];
                        $advance_total = $financeData['FlashFinance']['rooms_credit'] + $financeData['FlashFinance']['restaurant_credit'] + $financeData['FlashFinance']['bar_credit'] + $financeData['FlashFinance']['advance_credit'];
                        $eft_total = $financeData['FlashFinance']['rooms_eft'] + $financeData['FlashFinance']['restaurant_eft'] + $financeData['FlashFinance']['bar_eft'] + $financeData['FlashFinance']['advance_eft'];
                        ?>
                        <tr>
                            <td><b>Rooms</b></td>
                            <td><?php echo $financeData['FlashFinance']['rooms_cash']; ?></td>
                            <td><?php echo $financeData['FlashFinance']['rooms_credit']; ?></td>
                            <td><?php echo $financeData['FlashFinance']['rooms_eft']; ?></td>
                        </tr>
                        <tr>
                            <td><b>Restaurant</b></td>
                            <td><?php echo $financeData['FlashFinance']['restaurant_cash']; ?></td>
                            <td><?php echo $financeData['FlashFinance']['restaurant_credit']; ?></td>
                            <td><?php echo $financeData['FlashFinance']['restaurant_eft']; ?></td>
                        </tr>
                        <tr>
                            <td><b>Bar</b></td>
                            <td><?php echo $financeData['FlashFinance']['bar_cash']; ?></td>
                            <td><?php echo $financeData['FlashFinance']['bar_credit']; ?></td>
                            <td><?php echo $financeData['FlashFinance']['bar_eft']; ?></td>
                        </tr>
                        <tr>
                            <td><b>Advance Deposits</b></td>
                            <td><?php echo $financeData['FlashFinance']['advance_cash']; ?></td>
                            <td><?php echo $financeData['FlashFinance']['advance_credit']; ?></td>
                            <td><?php echo $financeData['FlashFinance']['advance_eft']; ?></td>
                        </tr>
                        <tr>
                            <td><b>Sub Total</b></td>
                            <td><b><?php echo $cash_total; ?></b></td>
                            <td><b><?php echo $advance_total; ?></b></td>
                            <td><b><?php echo $eft_total; ?></b></td>
                        </tr>
                        <tr><td colspan="4">&nbsp;</td></tr>
                        <tr><td><b>Grand Total</b></td><td colspan="3"><b> <?php echo $cash_total + $advance_total + $eft_total; ?></b> </td></tr>
                    </table>
                
                
                <?php 
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
                    } ?>

                    <table class="table table-striped table-bordered table-hover">
                        <tr><td colspan="2" class="thead_title"><b><?php echo __('Cash on Hand'); ?></b></td></tr>
                        <tr>
                            <td>Rooms</td><td><?php echo $hand_rooms; ?></td>
                        </tr>
                        <tr>
                            <td style="width:30%;">Cash Tin</td><td><?php echo $hand_cash_tin; ?></td>
                        </tr>
                        <tr>
                            <td>Rest. Tin</td><td><?php echo $hand_restuarant_tin; ?></td>
                        </tr>
                        <tr>
                            <td>Bar Tin</td><td><?php echo $hand_bar_tin; ?></td>
                        </tr>
                        <tr>
                            <td>Floats</td><td><?php echo $hand_floats; ?></td>
                        </tr>
                        <tr>
                            <td>Data</td><td><?php echo $hand_data; ?></td>
                        </tr>
                        <tr>
                            <td>Advance deposits</td><td><?php echo $hand_adv_deposit; ?></td>
                        </tr>
                        <tr>
                            <td>Miscellaneous</td><td><?php echo $hand_miscellaneous; ?></td>
                        </tr>
                        <tr>
                            <td><b>Opening Balance</b></td><td><b><?php echo $opening_balance; ?></b></td>
                        </tr>
                    </table>
                    <br/>
                    
                    <table class="table table-striped table-bordered table-hover">
                        <tr><td colspan="2" class="thead_title"><b><?php echo __('Monies Paid-out'); ?></b></td></tr>
                        <tr>
                            <td>Tips</td><td><?php echo $monies_tips; ?></td>
                        </tr>
                        <tr>
                            <td style="width:30%;">Wood</td><td><?php echo $monies_wood; ?></td>
                        </tr>
                        <tr>
                            <td>Bread</td><td><?php echo $monies_bread; ?></td>
                        </tr>
                        <tr>
                            <td>Yogurt</td><td><?php echo $monies_youghurt; ?></td>
                        </tr>
                        <tr>
                            <td>Casual Wages</td><td><?php echo $monies_casual_wages; ?></td>
                        </tr>
                        <tr>
                            <td>Banked</td><td><?php echo $monies_banked; ?></td>
                        </tr>
                        <tr>
                            <td><b>Total</b></td><td><b><?php echo $total_monies; ?></b></td>
                        </tr>
                    </table>
                    <br/>
                    
                    <table class="table table-striped table-bordered table-hover">
                        <tr><td colspan="2" class="thead_title"><b><?php echo __('Cash Received'); ?></b></td></tr>
                        <tr>
                            <td style="width:30%;">Restaurant</td><td><?php echo $received_restaurant; ?></td>
                        </tr>
                        <tr>
                            <td>BAR</td><td><?php echo $received_bar; ?></td>
                        </tr>
                        <tr>
                            <td>Accommodation</td><td><?php echo $received_accomodation; ?></td>
                        </tr>
                        <tr>
                            <td>Account</td><td><?php echo $received_account; ?></td>
                        </tr>
                        <tr>
                            <td>Data</td><td><?php echo $received_data; ?></td>
                        </tr>
                        <tr>
                            <td>Cash Paid into Tin</td><td><?php echo $received_paid_in_tin; ?></td>
                        </tr>
                    </table><br/>                    
                    <table class="table table-striped table-bordered table-hover">
                        <tr><td colspan="2" class="thead_title"><b><?php echo __('Cash Payments'); ?></b></td></tr>
                        <tr>
                            <td style="width:30%;">#1</td><td><?php echo $pay_1; ?></td>
                        </tr>
                        <tr>
                            <td>#2</td><td><?php echo $pay_2; ?></td>
                        </tr>
                        <tr>
                            <td>#3</td><td><?php echo $pay_3; ?></td>
                        </tr>
                        <tr>
                            <td><b>Cash Banked</b></td><td><b><?php echo $cash_banked; ?></b></td>
                        </tr>
                    </table>
                
                </div>

<div style="float:left;">
    <?php echo $this->Html->link('Close', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'index'), array('class' => 'btn btn-danger', 'escape' => false));?>
    <?php echo $this->Html->link('Download PDF', array('prefix' => 'staff', 'staff' => false, 'controller' => 'clients', 'action' => 'flash_pdf',$flashData['DailyFlash']['client_id'],$flashData['DailyFlash']['id']), array('class' => 'btn btn-primary', 'escape' => false));?>
    <?php //echo $this->Html->link('Verify & Submit', array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'flash_verified',$flashData['DailyFlash']['client_id'],$flashData['DailyFlash']['id']), array('class' => 'new_button', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;'));?>
</div>
</div>