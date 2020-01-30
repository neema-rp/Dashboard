<?php ?>
<style type="text/css">
table{ color: #6B6F6F; } 
input, textarea { font-size:100%; border:1px solid #ccc; }
input[readonly=readonly]{ background: #BDBDBD; border:1px solid #BDBDBD; }
table tr td{ background:none; font-size:12px; padding:3px; border: 1px solid #ccc; }
table tr:nth-child(2n) td{ background:none; }
table tr:nth-child(2n) td.content_head{ background:#BDBDBD; }
.bold{ font-weight: bold; }
th{ font-size:80%; }
</style>


<div class="control-group">
	<div class="page-header position-relative">
                <h1>Promotions Calendar <small><i class="icon-double-angle-right"></i> <?php echo $year;  ?></small></h1>
        </div>

<?php
$promo_id = $Promo['Promo']['id'];
$client_id = $Promo['Promo']['client_id'];
$year = $Promo['Promo']['year'];
?>
	<fieldset id="print_page">
                 
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
                <?php foreach($months as $mnth=>$month){ 
                    $promo_month = sprintf("%02d", ($mnth + 1));
                ?>
                
                <fieldset>
 		<legend><?php echo date("M", mktime(0, 0, 0, $promo_month, 1)).'&nbsp;'.$year;  ?></legend>
                
                <?php
                $mondays = $this->requestAction('/Promos/getMondays/'.$year.'/'.$promo_month);
                $monthData = $this->requestAction('/Promos/get_promo_data/'.$promo_id.'/'.$promo_month);
                ?>
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>Week Of (Mon. Start)</th>
                        <?php foreach($mondays as $mon){ ?>
                            <th style="text-align:center;"><b><?php echo $mon ?></b></th>
                        <?php } ?>
                    </tr>
                    
                    <?php 
                    $general_cat_array = explode('|',$Promo['Promo']['general_categories']);
                    
                    $i = '0';
                    
                    foreach($general_cat_array as $category){
                        ?>
                        <tr>
                            <td><b><?php echo $category; ?></b></td>
                            <?php foreach($mondays as $mon){ ?>
                                <td>
                                     <?php
                                    if(!empty($monthData)){
                                    foreach($monthData as $promoData){
                                         if($promoData['PromoData']['category']=='general' && $promoData['PromoData']['category_column']==$category && $promoData['PromoData']['start_date']==date('Y-m-d',strtotime($mon))){
                                                echo $promoData['PromoData']['value'];
                                         }
                                    }
                                    }else{
                                       echo '&nbsp;';
                                    }
                                    ?>
                                </td>
                            <?php $i++; } ?>
                        </tr>
                        <?php } ?>

                    <tr>
                            <td colspan="7">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <th><b>OTA Sales and Promo</b></th>
                        <?php foreach($mondays as $mon){ ?>
                            <th>&nbsp;</th>
                        <?php } ?>
                    </tr>
                    
                    <?php
                    $ota_cat_array = explode('|',$Promo['Promo']['ota_categories']);
                    
                    foreach($ota_cat_array as $category){
                    ?>
                    <tr>
                        <td><b><?php echo $category; ?></b></td>
                        <?php foreach($mondays as $mon){ ?>
                            <td>
                                <?php
                                if(!empty($monthData)){
                                    foreach($monthData as $promoData){
                                         if($promoData['PromoData']['category']=='OTA' && $promoData['PromoData']['category_column']==$category && $promoData['PromoData']['start_date']==date('Y-m-d',strtotime($mon))){
                                                echo $promoData['PromoData']['value'];
                                         }
                                    }
                                    }else{
                                        echo '&nbsp;';
                                } 
                                ?>
                            </td>
                        <?php $i++;  } ?>
                    </tr>
                    <?php } ?>
                    
                    <tr>
                            <td colspan="7">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <th><b>Offers</b></th>
                        <?php foreach($mondays as $mon){ ?>
                            <th>&nbsp;</th>
                        <?php } ?>
                    </tr>
                    
                    <?php
                    for($day=1;$day <= $Promo['Promo']['offers_list']; $day++){
                    ?>
                    <tr>
                        <td><b>Offer <?php echo $day; ?></b></td>
                        <?php foreach($mondays as $mon){ ?>
                            <td>
                                <?php
                                if(!empty($monthData)){
                                    foreach($monthData as $promoData){
                                         if($promoData['PromoData']['category']=='Offers' && $promoData['PromoData']['category_column']==$day && $promoData['PromoData']['start_date']==date('Y-m-d',strtotime($mon))){
                                                echo $promoData['PromoData']['value'];
                                         }
                                    }
                                    }else{
                                        echo '&nbsp;';
                                } ?>
                            </td>
                        <?php $i++;  } ?>
                    </tr>
                    <?php } ?>
                </table>
                </fieldset>
                <?php } ?>
	</fieldset>

    <div>
        &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link("Close", array('prefix' => 'admin', 'admin' => true, 'controller' => 'Promos', 'action' => 'index', $client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
        &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="print_report();" class="btn btn-info">Print Report</a>
    </div>
</div>


<script>
function print_report(){
    var prtContent = document.getElementById("print_page");
    var WinPrint = window.open('', '', 'left=0,top=0,width=1000,height=1000,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write('<link rel="stylesheet" type="text/css" href="http://myrevenuedashboard.net/css/styles.css" />')
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}
</script>