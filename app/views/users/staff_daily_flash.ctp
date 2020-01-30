<?php 
?>
<script>
$(document).ready(function(){
    $("#ClientAdd").validationEngine();
    
        $('#breakfast_included').click(function(){
            $("#breakfast_options").toggle();
        });
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __('Input Daily Flash : '); ?> <small><i class="icon-double-angle-right"></i><?php echo date('d F Y',strtotime($date)); ?></small></h1>
        </div>
    
<?php echo $this->Form->create('DailyFlash', array('url'=>array('controller'=>'users', 'action'=>'staff_daily_flash')));?>
   
                <?php echo $this->Form->input('id',array('type'=>'hidden')); ?>
              <?php echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id));
              echo $this->Form->input('date',array('type'=>'hidden','value'=>$date));
              ?>

            <div class="row-fluid">
                <h3 class="header smaller lighter green"><?php __('Summary'); ?></h3>
                <table>
                    <tr>
                        <td>How many Bedrooms Occupied</td>
                        <td><?php echo $this->Form->input('occupied',array('type'=>'text','id'=>'occupied','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Enter Rooms Revenue</td>
                        <td><?php echo $this->Form->input('revenue',array('type'=>'text','id'=>'revenue','label'=>false)); ?></td>
                    </tr>
                    
                    
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr><td colspan="2"><b>Reservation Summary Present Month</b></td></tr>
                    <tr>
                        <td>How many new Reservations were entered yesterday?</td>
                        <td><?php echo $this->Form->input('reservation',array('type'=>'text','id'=>'reservation','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>How many room nights in todays new reservations?</td>
                        <td><?php echo $this->Form->input('room_night',array('type'=>'text','id'=>'room_night','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Rooms Revenue </td>
                        <td><?php echo $this->Form->input('rooms_revenue',array('type'=>'text','id'=>'rooms_revenue','label'=>false)); ?></td>
                    </tr>
                    
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr><td colspan="2"><b>Reservation Summary Following Month</b></td></tr>
                    <tr>
                        <td>How many new Reservations were entered yesterday?</td>
                        <td><?php echo $this->Form->input('reservation_next',array('type'=>'text','id'=>'reservation_next','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>How many room nights in todays new reservations?</td>
                        <td><?php echo $this->Form->input('room_night_next',array('type'=>'text','id'=>'room_night_next','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Rooms Revenue </td>
                        <td><?php echo $this->Form->input('rooms_revenue_next',array('type'=>'text','id'=>'rooms_revenue_next','label'=>false)); ?></td>
                    </tr>
                    
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr><td colspan="2"><b>Reservation Summary Future Month</b></td></tr>
                    <tr>
                        <td>How many new Reservations were entered yesterday?</td>
                        <td><?php echo $this->Form->input('reservation_future',array('type'=>'text','id'=>'reservation_future','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>How many room nights in todays new reservations?</td>
                        <td><?php echo $this->Form->input('room_night_future',array('type'=>'text','id'=>'room_night_future','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Rooms Revenue </td>
                        <td><?php echo $this->Form->input('rooms_revenue_future',array('type'=>'text','id'=>'rooms_revenue_future','label'=>false)); ?></td>
                    </tr>
                    
                    <tr><td colspan="2">&nbsp;</td></tr>
                    
                    <tr>
                        <td style="width:50%;">Is breakfast included in the Rooms Revenue that is being reported? </td>
                        <td><?php echo $this->Form->input('breakfast_included',array('type'=>'checkbox','id'=>'breakfast_included','label'=>false)); ?></td>
                    </tr>
                    </table>
            
                    <?php $display_breakfastOption = (!empty($this->data['DailyFlash']['breakfast_included']) && ($this->data['DailyFlash']['breakfast_included'] == '1') ) ? 'width:100%;margin:0px;' : 'display:none;width:100%;margin:0px;'; ?>
            
                    <div style="<?php echo $display_breakfastOption; ?>" id="breakfast_options">
                        <table>
                        <tr>
                            <td style="width:50%;">Enter Cost of Breakfast per Adult</td>
                            <td><?php echo $this->Form->input('deduction',array('type'=>'text','id'=>'deduction','label'=>false)); ?></td>
                        </tr>
                        <tr>
                            <td>Enter Cost of Breakfast per Child</td>
                            <td><?php echo $this->Form->input('child_deduction',array('type'=>'text','id'=>'child_deduction','label'=>false)); ?></td>
                        </tr>
                        <tr>
                            <td>How many adults in house?</td>
                            <td><?php echo $this->Form->input('number_of_adults',array('type'=>'text','id'=>'number_of_adults','label'=>false)); ?></td>
                        </tr>
                        <tr>
                            <td>How many children in house? </td>
                            <td><?php echo $this->Form->input('number_of_childrens',array('type'=>'text','id'=>'number_of_childrens','label'=>false)); ?></td>
                        </tr>                        
                    </table>
                    </div>
                </div>

                <div class="row-fluid">
                <h3 class="header smaller lighter green"><?php __('Restaurant'); ?></h3>
                <table>
                    <tr>
                        <td>Enter how many covers for mealperiod </td>
                        <td><?php echo $this->Form->input('covers',array('type'=>'text','id'=>'covers','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Enter Food Revenue </td>
                        <td><?php echo $this->Form->input('food_revenue',array('type'=>'text','id'=>'food_revenue','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Enter Beverage Revenue</td>
                        <td><?php echo $this->Form->input('bev_revenue',array('type'=>'text','id'=>'bev_revenue','label'=>false)); ?></td>
                    </tr>
                </table>
                </div>
                
            
            <div class="row-fluid" <?php if($client_id == '104'){ echo 'style="display:none;"'; } ?>>
                <h3 class="header smaller lighter green"><?php __('Other Revenues'); ?></h3>
                <table>
                    <tr><td>&nbsp;</td><td style="text-align:center;">Number of People</td><td style="text-align:center;">Revenue</td></tr>
                    <tr>
                        <td>Golf</td>
                        <td><?php echo $this->Form->input('golf_people',array('type'=>'text','id'=>'golf_people','label'=>false)); ?></td>
                        <td><?php echo $this->Form->input('golf_rev',array('type'=>'text','id'=>'golf_rev','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Weddings/Events</td>
                        <td><?php echo $this->Form->input('event_people',array('type'=>'text','id'=>'event_people','label'=>false)); ?></td>
                        <td><?php echo $this->Form->input('event_rev',array('type'=>'text','id'=>'event_rev','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Conference</td>
                        <td><?php echo $this->Form->input('conference_people',array('type'=>'text','id'=>'conference_people','label'=>false)); ?></td>
                        <td><?php echo $this->Form->input('conference_rev',array('type'=>'text','id'=>'conference_rev','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Watersports</td>
                        <td><?php echo $this->Form->input('sports_people',array('type'=>'text','id'=>'sports_people','label'=>false)); ?></td>
                        <td><?php echo $this->Form->input('sports_rev',array('type'=>'text','id'=>'sports_rev','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Other</td>
                        <td><?php echo $this->Form->input('other_people',array('type'=>'text','id'=>'other_people','label'=>false)); ?></td>
                        <td><?php echo $this->Form->input('other_rev',array('type'=>'text','id'=>'other_rev','label'=>false)); ?></td>
                    </tr>
                </table>
           </div>


            <div class="row-fluid" <?php if($client_id == '104'){ echo 'style="display:none;"'; } ?>>
                <h3 class="header smaller lighter green"><?php __('Financial Management'); ?></h3>
                    <table>
                        <tr>
                            <td>&nbsp;</td>
                            <td style="text-align:center;">Cash</td>
                            <td style="text-align:center;">Credit</td>
                            <td style="text-align:center;">EFT</td>
                        </tr>
                        <?php $this->data['FlashFinance'] = $this->data['FlashFinance']['FlashFinance'];
                        $rooms_cash = (!empty($this->data['FlashFinance']['rooms_cash'])) ? $this->data['FlashFinance']['rooms_cash'] : '0';
                        $rooms_credit = (!empty($this->data['FlashFinance']['rooms_credit'])) ? $this->data['FlashFinance']['rooms_credit'] : '0';
                        $rooms_eft = (!empty($this->data['FlashFinance']['rooms_eft'])) ? $this->data['FlashFinance']['rooms_eft'] : '0';
                        
                        $restaurant_cash = (!empty($this->data['FlashFinance']['restaurant_cash'])) ? $this->data['FlashFinance']['restaurant_cash'] : '0';
                        $restaurant_credit = (!empty($this->data['FlashFinance']['restaurant_credit'])) ? $this->data['FlashFinance']['restaurant_credit'] : '0';
                        $restaurant_eft = (!empty($this->data['FlashFinance']['restaurant_eft'])) ? $this->data['FlashFinance']['restaurant_eft'] : '0';
                        
                        $bar_cash = (!empty($this->data['FlashFinance']['bar_cash'])) ? $this->data['FlashFinance']['bar_cash'] : '0';
                        $bar_credit = (!empty($this->data['FlashFinance']['bar_credit'])) ? $this->data['FlashFinance']['bar_credit'] : '0';
                        $bar_eft = (!empty($this->data['FlashFinance']['bar_eft'])) ? $this->data['FlashFinance']['bar_eft'] : '0';
                        
                        $advance_cash = (!empty($this->data['FlashFinance']['advance_cash'])) ? $this->data['FlashFinance']['advance_cash'] : '0';
                        $advance_credit = (!empty($this->data['FlashFinance']['advance_credit'])) ? $this->data['FlashFinance']['advance_credit'] : '0';
                        $advance_eft = (!empty($this->data['FlashFinance']['advance_eft'])) ? $this->data['FlashFinance']['advance_eft'] : '0';
                        ?>
                        <tr>
                            <td>Rooms</td>
                            <td><?php echo $this->Form->input('FlashFinance.rooms_cash',array('type'=>'text','id'=>'rooms_cash','label'=>false,'value'=>$rooms_cash)); ?></td>
                            <td><?php echo $this->Form->input('FlashFinance.rooms_credit',array('type'=>'text','id'=>'rooms_credit','label'=>false,'value'=>$rooms_credit)); ?></td>
                            <td><?php echo $this->Form->input('FlashFinance.rooms_eft',array('type'=>'text','id'=>'rooms_eft','label'=>false,'value'=>$rooms_eft)); ?></td>
                        </tr>
                        <tr>
                            <td>Restaurant</td>
                            <td><?php echo $this->Form->input('FlashFinance.restaurant_cash',array('type'=>'text','id'=>'restaurant_cash','label'=>false,'value'=>$restaurant_cash)); ?></td>
                            <td><?php echo $this->Form->input('FlashFinance.restaurant_credit',array('type'=>'text','id'=>'restaurant_credit','label'=>false,'value'=>$restaurant_credit)); ?></td>
                            <td><?php echo $this->Form->input('FlashFinance.restaurant_eft',array('type'=>'text','id'=>'restaurant_eft','label'=>false,'value'=>$restaurant_eft)); ?></td>
                        </tr>
                        <tr>
                            <td>Bar</td>
                            <td><?php echo $this->Form->input('FlashFinance.bar_cash',array('type'=>'text','id'=>'bar_cash','label'=>false,'value'=>$bar_cash)); ?></td>
                            <td><?php echo $this->Form->input('FlashFinance.bar_credit',array('type'=>'text','id'=>'bar_credit','label'=>false,'value'=>$bar_credit)); ?></td>
                            <td><?php echo $this->Form->input('FlashFinance.bar_eft',array('type'=>'text','id'=>'bar_eft','label'=>false,'value'=>$bar_eft)); ?></td>
                        </tr>
                        <tr>
                            <td>Advance Deposits</td>
                            <td><?php echo $this->Form->input('FlashFinance.advance_cash',array('type'=>'text','id'=>'advance_cash','label'=>false,'value'=>$advance_cash)); ?></td>
                            <td><?php echo $this->Form->input('FlashFinance.advance_credit',array('type'=>'text','id'=>'advance_credit','label'=>false,'value'=>$advance_credit)); ?></td>
                            <td><?php echo $this->Form->input('FlashFinance.advance_eft',array('type'=>'text','id'=>'advance_eft','label'=>false,'value'=>$advance_eft)); ?></td>
                        </tr>
                    </table>
                    <br/>
                    
                    <?php $hand_cash_tin =''; $hand_restuarant_tin = ''; $hand_bar_tin = ''; $hand_floats = ''; $hand_miscellaneous = ''; $hand_adv_deposit = ''; $hand_data = ''; $hand_rooms ='';
                    $received_restaurant = ''; $received_bar=''; $received_accomodation = ''; $received_account = ''; $received_data=''; $received_paid_in_tin='';
                    $pay_1 =''; $pay_2 =''; $pay_3 = '';
                    $monies_tips = ''; $monies_wood = ''; $monies_bread = ''; $monies_youghurt = ''; $monies_casual_wages = ''; $monies_banked = '';
                    if(!empty($this->data['FlashCash'])){
                        foreach($this->data['FlashCash'] as $clash_flash){
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
                                //$hand.'_'.$clash_flash['FlashCash']['name'] = $clash_flash['FlashCash']['value'];
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
                            }
                        }
                    } ?>
                    
                    <legend>Cash On Hand</legend><br/>
                    <table>
                        <tr>
                            <td>Rooms</td>
                            <td><?php echo $this->Form->input('FlashCash.Hand.rooms',array('type'=>'text','id'=>'rooms','label'=>false,'value'=>$hand_rooms)); ?></td>
                        </tr>
                        <tr>
                            <td>Cash Tin</td>
                            <td><?php echo $this->Form->input('FlashCash.Hand.cash_tin',array('type'=>'text','id'=>'cash_tin','label'=>false,'value'=>$hand_cash_tin)); ?></td>
                        </tr>
                        <tr>
                            <td>Rest. Tin</td>
                            <td><?php echo $this->Form->input('FlashCash.Hand.restuarant_tin',array('type'=>'text','id'=>'restuarant_tin','label'=>false,'value'=>$hand_restuarant_tin)); ?></td>
                        </tr>
                        <tr>
                            <td>Bar Tin</td>
                            <td><?php echo $this->Form->input('FlashCash.Hand.bar_tin',array('type'=>'text','id'=>'bar_tin','label'=>false,'value'=>$hand_bar_tin)); ?></td>
                        </tr>
                        <tr>
                            <td>Floats</td>
                            <td><?php echo $this->Form->input('FlashCash.Hand.floats',array('type'=>'text','id'=>'floats','label'=>false,'value'=>$hand_floats)); ?></td>
                        </tr>
                        <tr>
                            <td>Data</td>
                            <td><?php echo $this->Form->input('FlashCash.Hand.data',array('type'=>'text','id'=>'data','label'=>false,'value'=>$hand_data)); ?></td>
                        </tr>
                        <tr>
                            <td>Advance deposits</td>
                            <td><?php echo $this->Form->input('FlashCash.Hand.adv_deposit',array('type'=>'text','id'=>'adv_deposit','label'=>false,'value'=>$hand_adv_deposit)); ?></td>
                        </tr>
                        <tr>
                            <td>Miscellaneous</td>
                            <td><?php echo $this->Form->input('FlashCash.Hand.miscellaneous',array('type'=>'text','id'=>'miscellaneous','label'=>false,'value'=>$hand_miscellaneous)); ?></td>
                        </tr>
                    </table>
                    <br/>
                    
                    <legend>Monies Paid-out</legend><br/>
                    <table>
                        <tr>
                            <td>Tips</td>
                            <td><?php echo $this->Form->input('FlashCash.Monies.tips',array('type'=>'text','id'=>'tips','label'=>false,'value'=>$monies_tips)); ?></td>
                        </tr>
                        <tr>
                            <td>Wood</td>
                            <td><?php echo $this->Form->input('FlashCash.Monies.wood',array('type'=>'text','id'=>'wood','label'=>false,'value'=>$monies_wood)); ?></td>
                        </tr>
                        <tr>
                            <td>Bread</td>
                            <td><?php echo $this->Form->input('FlashCash.Monies.bread',array('type'=>'text','id'=>'bread','label'=>false,'value'=>$monies_bread)); ?></td>
                        </tr>
                        <tr>
                            <td>Yogurt</td>
                            <td><?php echo $this->Form->input('FlashCash.Monies.youghurt',array('type'=>'text','id'=>'youghurt','label'=>false,'value'=>$monies_youghurt)); ?></td>
                        </tr>
                        <tr>
                            <td>Casual Wages</td>
                            <td><?php echo $this->Form->input('FlashCash.Monies.casual_wages',array('type'=>'text','id'=>'casual','label'=>false,'value'=>$monies_casual_wages)); ?></td>
                        </tr>
                        <tr>
                            <td>Banked</td>
                            <td><?php echo $this->Form->input('FlashCash.Monies.banked',array('type'=>'text','id'=>'banked','label'=>false,'value'=>$monies_banked)); ?></td>
                        </tr>
                    </table>
                    <br/>                    
                    <legend>Cash Received</legend><br/>
                    <table>
                        <tr>
                            <td>Restaurant</td>
                            <td><?php echo $this->Form->input('FlashCash.Received.restaurant',array('type'=>'text','id'=>'restaurant','label'=>false,'value'=>$received_restaurant)); ?></td>
                        </tr>
                        <tr>
                            <td>BAR</td>
                            <td><?php echo $this->Form->input('FlashCash.Received.bar',array('type'=>'text','id'=>'bar','label'=>false,'value'=>$received_bar)); ?></td>
                        </tr>
                        <tr>
                            <td>Accommodation</td>
                            <td><?php echo $this->Form->input('FlashCash.Received.accomodation',array('type'=>'text','id'=>'accomodation','label'=>false,'value'=>$received_accomodation)); ?></td>
                        </tr>
                        <tr>
                            <td>Account</td>
                            <td><?php echo $this->Form->input('FlashCash.Received.account',array('type'=>'text','id'=>'account','label'=>false,'value'=>$received_account)); ?></td>
                        </tr>
                        <tr>
                            <td>Data</td>
                            <td><?php echo $this->Form->input('FlashCash.Received.data',array('type'=>'text','id'=>'data','label'=>false,'value'=>$received_data)); ?></td>
                        </tr>
                        <tr>
                            <td>Cash Paid into Tin</td>
                            <td><?php echo $this->Form->input('FlashCash.Received.paid_in_tin',array('type'=>'text','id'=>'paid_in_tin','label'=>false,'value'=>$received_paid_in_tin)); ?></td>
                        </tr>
                    </table><br/>
                    
                    <legend>Cash Payments</legend><br/>
                    <table>
                        <tr>
                            <td>#1</td>
                            <td><?php echo $this->Form->input('FlashCash.Payment.1',array('type'=>'text','id'=>'pay1','label'=>false,'value'=>$pay_1)); ?></td>
                        </tr>
                        <tr>
                            <td>#2</td>
                            <td><?php echo $this->Form->input('FlashCash.Payment.2',array('type'=>'text','id'=>'pay2','label'=>false,'value'=>$pay_2)); ?></td>
                        </tr>
                        <tr>
                            <td>#3</td>
                            <td><?php echo $this->Form->input('FlashCash.Payment.3',array('type'=>'text','id'=>'pay3','label'=>false,'value'=>$pay_3)); ?></td>
                        </tr>
                    </table>
                    
                    
                </div>

                
                <div class="row-fluid">
                    <h3 class="header smaller lighter green"><?php __('Operations'); ?></h3>
                <table>
                    <tr>
                        <td>Enter number of bedroom arrivals for today</td>
                        <td><?php echo $this->Form->input('total_arrival',array('type'=>'text','id'=>'total_arrival','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Enter number of bedroom departures for today </td>
                        <td><?php echo $this->Form->input('total_departure',array('type'=>'text','id'=>'total_departure','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Enter number of group arrivals</td>
                        <td><?php echo $this->Form->input('group_arrival',array('type'=>'text','id'=>'group_arrival','label'=>false)); ?></td>
                    </tr>
                    <tr>
                        <td>Enter number of group departures</td>
                        <td><?php echo $this->Form->input('group_departure',array('type'=>'text','id'=>'group_departure','label'=>false)); ?></td>
                    </tr>
                </table>
                </div>


                <div class="row-fluid">
                    <h3 class="header smaller lighter green"><?php __('Comments'); ?></h3>
                <div><?php echo $this->Form->input('comments',array('type'=>'textarea','id'=>'comments','label'=>false)); ?></div>
                </div>
                
                <div class="row-fluid">
                    <h3 class="header smaller lighter green"><?php __('Site Inspection Rooms (room ready for display)'); ?></h3>
 		<div><?php echo $this->Form->input('inspection_comments',array('type'=>'textarea','id'=>'inspection_comments','label'=>false)); ?></div>
                </div>
                
                <div class="row-fluid">
                    <h3 class="header smaller lighter green"><?php __('Maintenance Rooms'); ?></h3>
                <div><?php echo $this->Form->input('maintainance_comments',array('type'=>'textarea','id'=>'maintainance_comments','label'=>false)); ?></div>
                </div>
<div>
<?php 
    echo $this->Form->submit(__('Save Draft', true), array('div' => false,'name'=>'data[DailyFlash][submit]','class'=>'btn btn-success'));
    //echo '&nbsp;&nbsp;';
    //echo $this->Form->submit(__('Verify & Submit', true), array('div' => false,'name'=>'data[DailyFlash][submit]','class'=>'btn btn-primary'));
    echo $this->Form->end();
    echo '&nbsp;&nbsp;';
    echo $this->Html->link('Cancel', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'index'), array('class' => 'btn btn-danger', 'escape' => false));
    
    
?>
</div>
</div>
