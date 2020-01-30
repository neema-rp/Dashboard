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
$promo_id = $Promo['Promo']['id'];
$client_id = $Promo['Promo']['client_id'];
$year = $Promo['Promo']['year'];
    ?>
	<fieldset>
 		<legend><?php __('Promotions Calendar'); ?> -  <?php echo $year;  ?></legend>
                <table>
                <?php
                $months = array(
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July ',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December',
                );
                ?>
                <tr>
                    <td>&nbsp;</td>
                <?php foreach($months as $mnth=>$month){
                    $promo_month = sprintf("%02d", ($mnth + 1));
                    $mondays[$month] = $this->requestAction('/Promos/getMondays/'.$year.'/'.$promo_month);
                    $monthData[$month] = $this->requestAction('/Promos/get_promo_data/'.$promo_id.'/'.$promo_month); ?>
                    <td colspan="<?php echo count($mondays[$month]); ?>"><b><?php echo $month ?></b></td>
               <?php } ?>
                </tr>
                
                    <tr>
                        <td>Week Of (Mon. Start)</td>
                        <?php foreach($months as $mnth=>$month){
                         foreach($mondays[$month] as $mon){ ?>
                            <td><b><?php echo $mon ?></b></td>
                        <?php } 
                        } ?>
                    </tr>
                    
                    <?php 
//                    $general_cat_array = array();
//                    $general_cat_array['School Holidays UK'] = 'School Holidays UK';
//                    $general_cat_array['School Holidays US'] = 'School Holidays US';
//                    $general_cat_array['Public Holidays'] = 'Public Holidays';
//                    $general_cat_array['Key Events'] = 'Key Events';
//                    $general_cat_array['Key Sporting Events'] = 'Key Sporting Events';
//                    $general_cat_array['Key Sporting Events'] = 'Key Sporting Events';
//                    $general_cat_array['Key Dates'] = 'Key Dates';
                    $general_cat_array = explode('|',$Promo['Promo']['general_categories']);
                    foreach($general_cat_array as $category){
                        ?>
                        <tr>
                            <td><b><?php echo $category; ?></b></td>
                            <?php foreach($months as $mnth=>$month){
                                foreach($mondays[$month] as $mon){ ?>
                                <td>
                                    <?php
                                    if(!empty($monthData[$month])){
                                    foreach($monthData[$month] as $promoData){
                                         if($promoData['PromoData']['category']=='general' && $promoData['PromoData']['category_column']==$category && $promoData['PromoData']['start_date']==date('Y-m-d',strtotime($mon))){
                                                echo $promoData['PromoData']['value'];
                                         }
                                    }
                                    }else{
                                       echo '&nbsp;';
                                    }
                                    ?>
                                </td>
                            <?php }
                            } ?>
                        </tr>
                        <?php } ?>

                    <tr>
                            <td colspan="100">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td><b>OTA Sales and Promo</b></td>
                        <?php foreach($months as $mnth=>$month){
                         foreach($mondays[$month] as $mon){ ?>
                            <td>&nbsp;</td>
                        <?php } 
                        } ?>
                    </tr>
                    
                    <?php 
//                    $ota_cat_array = array();
//                    $ota_cat_array["OTA's & IBE"] = "OTA's & IBE" ;
//                    $ota_cat_array["OTA's & IBE - 2"] = "OTA's & IBE" ;
//                    $ota_cat_array['Booking.com'] = 'Booking.com';
//                    $ota_cat_array['Expedia'] = 'Expedia';
                    $ota_cat_array = explode('|',$Promo['Promo']['ota_categories']);
                    foreach($ota_cat_array as $category){
                    ?>
                    <tr>
                        <td><b><?php echo $category; ?></b></td>
                        <?php foreach($months as $mnth=>$month){
                        foreach($mondays[$month] as $mon){ ?>
                            <td>
                                <?php
                                if(!empty($monthData[$month])){
                                    foreach($monthData[$month] as $promoData){
                                         if($promoData['PromoData']['category']=='OTA' && $promoData['PromoData']['category_column']==$category && $promoData['PromoData']['start_date']==date('Y-m-d',strtotime($mon))){
                                                echo $promoData['PromoData']['value'];
                                         }
                                    }
                                    }else{
                                        echo '&nbsp;';
                                } 
                                ?>
                            </td>
                        <?php }
                        } ?>
                    </tr>
                    <?php } ?>
                    
                    <tr>
                            <td colspan="100">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td><b>Offers</b></td>
                        <?php foreach($months as $mnth=>$month){
                         foreach($mondays[$month] as $mon){ ?>
                            <td>&nbsp;</td>
                        <?php } 
                        } ?>
                    </tr>
                    
                    <?php 
//                    $offers_cat_array = array();
//                    $offers_cat_array["Offers"] = "Offers" ;
//                    $offers_cat_array["Offers1"] = "Offers" ;
//                    $offers_cat_array['Offers2'] = 'Offers';
//                    foreach($offers_cat_array as $cat_key=>$category){
                    for($day=1;$day <= $Promo['Promo']['offers_list']; $day++){
                    ?>
                    <tr>
                        <td><b>Offer <?php echo $day; ?></b></td>
                        <?php foreach($months as $mnth=>$month){
                        foreach($mondays[$month] as $mon){ ?>
                            <td>
                                <?php
                                if(!empty($monthData[$month])){
                                    foreach($monthData[$month] as $promoData){
                                         if($promoData['PromoData']['category']=='Offers' && $promoData['PromoData']['category_column']==$day && $promoData['PromoData']['start_date']==date('Y-m-d',strtotime($mon))){
                                                echo $promoData['PromoData']['value'];
                                         }
                                    }
                                    }else{
                                        echo '&nbsp;';
                                } 
                                ?>
                            </td>
                        <?php }
                        } ?>
                    </tr>
                    <?php } ?>
                    
                </table>
                
	</fieldset>

<div style="float:left;width:110px;">

</div>
    
</div>

<?php echo $this->element('admin_left_menu'); ?>