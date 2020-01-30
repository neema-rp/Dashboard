





<?php
//
//define('AREA', 'A');
//define('AREA_NAME', 'admin');
//
////Condition to check if request runs from command line or not
//if (PHP_SAPI != 'cli') {
//        throw new Exception('This script will run from the command line!');
//}
//
////Check 2 arguments are passed with cron or not and throw exception on missing arguments
//if (!empty($argv[1]) && !empty($argv[2])) {
//        throw new Exception('Missing Arguments');
//}
//
//require  dirname(__FILE__) . '/../prepare.php';
//require  dirname(__FILE__) . '/../init.php';
//
//
//$item_per_loop = '500'; //variable to select the item per batch
//
////Get count numbers of rows to run process in batch
////$cat_count = db_get_array("SELECT COUNT(*) as num FROM `cscart_promotions` WHERE conditions_hash like '%categories=%' AND to_date>UNIX_TIMESTAMP() AND status = 'A'or 'H'");
//$cat_count = db_get_array("SELECT COUNT(*) as num FROM `cscart_promotions` WHERE conditions_hash like '%categories=%' AND to_date>UNIX_TIMESTAMP() AND status = 'A'or 'H' AND category_id IS NULL");
//$itemcount = $cat_count['num'];
//
//
//
//$batches = $itemcount / $item_per_loop; // Number of loop calls
//for ($i = 0; $i <= $batches; $i++) {
//    $offset = $i * $item_per_loop; // MySQL Limit offset number
//  
//    //$cat_data=db_get_array("SELECT promotion_id  ,SUBSTRING_INDEX(SUBSTR(conditions_hash FROM (INSTR(conditions_hash, 'categories=') + 11)),';',1) as category_id FROM `cscart_promotions` WHERE conditions_hash like '%categories=%' AND to_date>UNIX_TIMESTAMP() AND status = 'A'or 'H' LIMIT $item_per_loop,$offset");
//    $cat_data=db_get_array("SELECT promotion_id  ,SUBSTRING_INDEX(SUBSTR(conditions_hash FROM (INSTR(conditions_hash, 'categories=') + 11)),';',1) as category_id FROM `cscart_promotions` WHERE conditions_hash like '%categories=%' AND to_date>UNIX_TIMESTAMP() AND status = 'A'or 'H' AND category_id IS NULL LIMIT $item_per_loop,$offset");
//    if(!empty($cat_data)){
//        foreach ($cat_data as $value){
//             db_query("UPDATE ?:promotions SET category_id=?s WHERE promotion_id = ?i", $value['category_id'], $value['promotion_id']);
//        }
//    }
//}


//fn_print_r($cat_data);


public function move_prod_cat_values($select_mode, $itemcount, $item_per_loop) {
        $batches = $itemcount / $item_per_loop; // Number of loop calls
        for ($loop = 0; $loop <= $batches; $loop++) {
            $offset = $loop * $item_per_loop; // MySQL Limit offset number
            
            if ($select_mode == 'p') {
                $qry1 = "SELECT bonuses from ?:promotions WHERE product_id IS NULL LIMIT " . $offset . "," . $item_per_loop . "";
            } else if ($select_mode == 'c') {
                $qry1 = "SELECT bonuses from ?:promotions WHERE category_id IS NULL LIMIT " . $offset . "," . $item_per_loop . "";
            }
            
            $prod_data = db_get_array($qry1);
            foreach ($prod_data as $key => $v) {
                $cp_id = '';
                $bon_data = unserialize($v['bonuses']);
                if ($bon_data) {
                    foreach ($bon_data as $k => $v) {
                        if ($select_mode == 'p') {
                            if ($v['bonus'] == 'discount_on_products') {
                                $cp_id = $v['value'];
                            }
                        } else if ($select_mode == 'c') {
                            if ($v['bonus'] == 'discount_on_categories') {
                                $cp_id = $v['value'];
                            }
                        }
                    }
                }
                if ($cp_id != '') {
                    $cat_pro = $select_mode == 'p' ? array('product_id' => $cp_id) : array('category_id' => $cp_id);
                    if ($select_mode == 'p') {
                        $qry2 = "select product_id from ?:promotions where product_id IS NULL AND promotion_id=?i";
                    } else if ($select_mode == 'c') {
                        $qry2 = "select product_id from ?:promotions where category_id IS NULL AND promotion_id=?i";
                    }
                    
                    $check = db_get_array($qry2, $value['promotion_id']);
                    if (count($check) == 0) {
                        $qry3 = "UPDATE ?:promotions SET ?u WHERE promotion_id = ?i";
                        db_query($qry3, $cat_pro, $value['promotion_id']);
                    }
                }
            }
        }
    }

    
    
    
    public function move_prod_cat_values($select_mode, $itemcount, $item_per_loop) {
        $batches = $itemcount / $item_per_loop; // Number of loop calls
        for ($loop = 0; $loop <= $batches; $loop++) { 
            $offset = $loop * $item_per_loop; // MySQL Limit offset number
            $qry1 = "SELECT bonuses from ?:promotions LIMIT " . $offset . "," . $item_per_loop . "";
            $prod_data = db_get_array($qry1);
            foreach ($prod_data as $key => $v) {
                $cp_id = '';
                $bon_data = unserialize($v['bonuses']);
                if ($bon_data) {
                    foreach ($bon_data as $k => $v) {
                        if ($select_mode == 'p') {
                            if ($v['bonus'] == 'discount_on_products') {
                                $cp_id = $v['value'];
                            }
                        } else if ($select_mode == 'c') {
                            if ($v['bonus'] == 'discount_on_categories') {
                                $cp_id = $v['value'];
                            }
                        }
                    }
                }
                if ($cp_id != '') {
                    $cat_pro = $select_mode == 'p' ? array('product_id' => $cp_id) : array('category_id' => $cp_id);
                    $qry2 = "select product_id from ?:promotions where promotion_id=?i";
                    $check = db_get_array($qry2, $value['promotion_id']);
                    if (count($check) == 0) {
                        $qry3 = "UPDATE ?:promotions SET ?u WHERE promotion_id = ?i";
                        db_query($qry3, $cat_pro, $value['promotion_id']);
                    }
                }
            }
        }
    }
 
//    if ($select_mode == 'p') {
//        $cat_pro = array('product_id' => $cp_id);
//        $qry2 = "select product_id from ?:promotions where promotion_id=?i";
//    } else if ($select_mode == 'c') {
//        $cat_pro = array('category_id' => $cp_id);
//        $qry2 = "select category_id from ?:promotions where promotion_id=?i";
//    }
    
?>







<?php ?>
<script>
$(document).ready(function(){
	$("#GpAdminAddForm").validationEngine();
});
</script>

<style type="text/css">
table{ color: #6B6F6F; } 
input { font-size:100%; border:1px solid #ccc; }
input[readonly=readonly]{ background: #BDBDBD; border:1px solid #BDBDBD; }
table tr td{ background:none; font-size:12px; padding:3px; }
table tr:nth-child(2n) td{ background:none; }
table tr:nth-child(2n) td.content_head{ background:#BDBDBD; }
.bold{ font-weight: bold; }
</style>

<?php
$gps_month = $this->data['GpsPack']['month'];
$gps_year = $this->data['GpsPack']['year'];


$financial_month_start = (!empty($gps_settings['GpsSetting']['financial_month_start'])) ? $gps_settings['GpsSetting']['financial_month_start'] : '1';

?>


<div class="Gps form">
    
<?php echo $this->Form->create('GpsPack', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'add')));?>
    
    <?php
    echo $this->Form->input('step',array('type'=>'hidden','value'=>$step));
    echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id));
    echo $this->Form->input('id',array('type'=>'hidden')); 
    ?>
    
	<fieldset>
 		<legend><?php __('Add GPS'); ?></legend>

                <?php if($step=='1'){ ?>

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
                
                <?php }elseif($step=='2'){ ?>
                        <table>
                            <tr>
                                <td>&nbsp;</td>
                                <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                            </tr>

                            <tr><td colspan="13">&nbsp;</td></tr>
                            
                            <tr>
                                <td class="bold">Number of Guests</td>
                                <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                    <?php 
                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Summary','name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Number of Guests','name'=>'data[GpsPack][sub_text][]'));
                                    //echo $this->Form->input('question[]',array('type'=>'hidden','value'=>$month)); 
                                    ?>
                                    <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'guest_'.$month,'label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                                <?php } ?>
                            </tr>
                            
                            <tr><td colspan="13">&nbsp;</td></tr>
                            <tr><td colspan="13" class="bold">Market Performance</td></tr>
                            <tr><td colspan="13" class="bold">Sandton and Surroundings - Upscale & Upper Mid</td></tr>

                            <?php
                            $market_perf_arr[] = 'MPI';
                            $market_perf_arr[] = 'ARI';
                            $market_perf_arr[] = 'RGI';

                            foreach($market_perf_arr as $market_per){ ?>
                                 <tr>
                                    <td class="bold"><?php echo $market_per; ?></td>
                                    <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Market Performance','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$market_per,'name'=>'data[GpsPack][sub_text][]'));
                                //echo $this->Form->input('question[]',array('type'=>'hidden','value'=>$month)); 
                                ?>
                                        <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'guest_'.$month,'label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            
                        </table>
                
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
                        <tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                                ?>

                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                                ?>

                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                    </table>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Planned Activity','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst RN','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst ADR','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual RN','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual ADR','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual Revenue','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Var. Revenue','name'=>'data[GpsPack][sub_text][]'));
                                ?>
                
                    <table>
                        <tr><td>Planned Activity</td><td>Fcst RN</td><td>Fcst ADR</td><td>Revenue</td><td>Actual RN</td><td>Actual ADR</td><td>Actual Revenue</td><td>Var. Revenue</td></tr>
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
                    </table>

                        <?php 
                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                        ?>
                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
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
                        <tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                                ?>

                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                                ?>

                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                    </table>


                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Planned Activity','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst RN','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst ADR','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual RN','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual ADR','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual Revenue','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Var. Revenue','name'=>'data[GpsPack][sub_text][]'));
                                ?>
                
                    <table>
                        <tr><td>Planned Activity</td><td>Fcst RN</td><td>Fcst ADR</td><td>Revenue</td><td>Actual RN</td><td>Actual ADR</td><td>Actual Revenue</td><td>Var. Revenue</td></tr>
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
                    </table>

                        <?php 
                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                        ?>
                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
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
                        <tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                                ?>

                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                                ?>

                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                    </table>


                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]' ));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Planned Activity','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst RN','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst ADR','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual RN','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual ADR','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual Revenue','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Var. Revenue','name'=>'data[GpsPack][sub_text][]'));
                                ?>
                
                    <table>
                        <tr><td>Planned Activity</td><td>Fcst RN</td><td>Fcst ADR</td><td>Revenue</td><td>Actual RN</td><td>Actual ADR</td><td>Actual Revenue</td><td>Var. Revenue</td></tr>
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
                    </table>

                        <?php 
                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                        ?>
                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
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
                        <tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'City Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                                ?>

                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                    </table>
                    <table>
                        <tr><td colspan="5" class="bold">Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>

                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                                ?>

                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
                    </table>


                                <?php 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Planned Activity','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst RN','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Fcst ADR','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual RN','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual ADR','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Actual Revenue','name'=>'data[GpsPack][sub_text][]'));

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Hotel Events','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Var. Revenue','name'=>'data[GpsPack][sub_text][]'));
                                ?>
                
                    <table>
                        <tr><td>Planned Activity</td><td>Fcst RN</td><td>Fcst ADR</td><td>Revenue</td><td>Actual RN</td><td>Actual ADR</td><td>Actual Revenue</td><td>Var. Revenue</td></tr>
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
                    </table>

                        <?php 
                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Event Name','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Description','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Impact','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Revenue Opportunity / Target','name'=>'data[GpsPack][sub_text][]'));

                        echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Competition Hotel Events','name'=>'data[GpsPack][text][]'));
                        echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Market / Source','name'=>'data[GpsPack][sub_text][]'));
                        ?>
                    <table>
                        <tr><td colspan="5" class="bold">Competition Hotel Events</td></tr>
                        <tr><td>Event Name</td><td>Description</td><td>Impact</td><td>Revenue Opportunity / Target</td><td>Market / Source</td></tr>
                        <tr>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                        </tr>
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
                        $gds_arr['3'] = 'Appollo';
                        $gds_arr['4'] = 'Galileo';
                        $gds_arr['5'] = 'Worldspan';
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
                    
                <?php }elseif($step=='15'){ ?>

                    
                    <h2>Channel Year</h2>
                    
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
                        
                        <?php foreach($gds_arr as $gd_key=>$gd_val){ ?>


                        <tr>
                            <td class="bold"><?php echo $gd_val; ?></td>
                            <td>RN</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){

                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_val,'name'=>'data[GpsPack][sub_text][]'));
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'GDS','name'=>'data[GpsPack][text][]'));
                                echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$gd_val,'name'=>'data[GpsPack][sub_text][]'));
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                        
                        <tr><td colspan="6">&nbsp;</td></tr>                        

                        <tr><td colspan="6"><b>Online</b></td></tr>
                        <?php 
                        $online_arr['1'] = 'Website';
                        $online_arr['2'] = 'OTA';
                        ?>
                        
                        <?php foreach($online_arr as $online_arr_key=>$online_arr_val){ ?>
                        <tr>
                            <td class="bold"><?php echo $online_arr_val; ?></td>
                            <td>RN</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_val,'name'=>'data[GpsPack][sub_text][]'));
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Online','name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$online_arr_val,'name'=>'data[GpsPack][sub_text][]'));    
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                        
                        <tr><td colspan="6">&nbsp;</td></tr>                        

                        <tr><td colspan="6"><b>Direct</b></td></tr>
                        <?php 
                        $direct_arr['1'] = 'Phone';
                        $direct_arr['2'] = 'Email/Fax';
                        $direct_arr['3'] = 'Walkin';
                        ?>
                        
                        <?php foreach($direct_arr as $direct_arr_key=>$direct_arr_val){ ?>
                        <tr>
                            <td class="bold"><?php echo $direct_arr_val; ?></td>
                            <td>RN</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_val,'name'=>'data[GpsPack][sub_text][]'));
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'Direct','name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$direct_arr_val,'name'=>'data[GpsPack][sub_text][]'));    
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                        
                        <tr>
                            <td class="bold">CRO</td>
                            <td>RN</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'CRO','name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>'CRO','name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));    
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                    </table>
                    
                <?php }elseif($step=='16'){ ?>
                    
                    <h2>Geo Year</h2>
                    <table>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ ?>
                               <td class="bold"> <?php echo date("M", mktime(0, 0, 0, $month, 1));  ?></td>
                                <?php } ?>
                        </tr>
                        
                        <?php 
                        $country_arr['1'] = 'Angola';
                        $country_arr['2'] = 'Benin';
                        $country_arr['3'] = 'Botswana';
                        $country_arr['4'] = 'Burkina Faso';
                        $country_arr['5'] = 'Burundi';
                        $country_arr['6'] = 'Cameroon';
                        $country_arr['7'] = 'Cape Verde';
                        $country_arr['8'] = 'Congo';
                        $country_arr['9'] = 'DRC';
                        $country_arr['10'] = 'Egypt';
                        ?>
                        
                        <?php foreach($country_arr as $country_key=>$country_val){ ?>
                        <tr>
                            <td class="bold"><?php echo $country_val; ?></td>
                            <td>RN</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));    
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                </table>
                    
                    
                <?php }elseif($step=='17'){ ?>
                    
                    <h2>Prov Year</h2>
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
                        
                        <?php foreach($country_arr as $country_key=>$country_val){ ?>
                        <tr>
                            <td class="bold"><?php echo $country_val; ?></td>
                            <td>RN</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                            echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$country_val,'name'=>'data[GpsPack][text][]'));
                            echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'','name'=>'data[GpsPack][sub_text][]'));    
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                </table>
                                            
                <?php }elseif($step=='18'){ ?>
                    <h2>RoomTypes</h2>
                    <?php 
                    $roomType_arr[] = 'Standard';
                    $roomType_arr[] = 'Executive';
                    $roomType_arr[] = 'Deluxe';
                    $roomType_arr[] = 'Suite';
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
                            <td class="bold"><?php echo $roomType_arr_val; ?></td>
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
                <?php }elseif($step=='21'){ ?>
                    
                    <h2>Reputation</h2>
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
                        
                        $reputation_arr['Google Analytics'][] = 'Main booking page';
                        $reputation_arr['Google Analytics'][] = 'Select a room';
                        $reputation_arr['Google Analytics'][] = 'Personal details';
                        $reputation_arr['Google Analytics'][] = 'Payment';
                        $reputation_arr['Google Analytics'][] = 'Find a reservation';

                        $reputation_arr['Tres Booking Funnel'][] = 'Search';
                        $reputation_arr['Tres Booking Funnel'][] = 'Pending';
                        $reputation_arr['Tres Booking Funnel'][] = 'Cancellations';
                        $reputation_arr['Tres Booking Funnel'][] = 'Removed';
                        $reputation_arr['Tres Booking Funnel'][] = 'Bookings';

                        ?>
                        
                        <?php $prev_key = '';
                        //echo '<pre>'; print_r($reputation_arr); echo '</pre>';
                        foreach($reputation_arr as $reputation_key=>$reputation_val){ 
                            if($prev_key != $reputation_key){ ?>
                            <tr><td colspan="12" >&nbsp;</td></tr>
                                <tr><td colspan="12" class="bold"><?php echo $reputation_key; ?></td></tr>
                           <?php  } ?>
                            <?php foreach($reputation_val as $rep_val){ ?>
                            <tr>
                                <td><?php echo $rep_val; ?></td>
                                <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){

                                    echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$reputation_key,'name'=>'data[GpsPack][text][]'));
                                    echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>$rep_val,'name'=>'data[GpsPack][sub_text][]'));
                                ?>

                                <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>

                                <?php } ?>
                            </tr>
                        <?php }
                        $prev_key = $reputation_key;
                        } ?>
                </table>
                    
                <?php }elseif($step=='22'){ ?>
                    
                    <h2>Config</h2>
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
                        
                        <?php foreach($marketsegments as $segment_key=>$segment_val){ ?>
                        <tr>
                            <td class="bold"><?php echo $segment_val; ?></td>
                            <td>RN</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){

                                  echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                  echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Budget','name'=>'data[GpsPack][sub_text][]'));
                             ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                  echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                  echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Budget','name'=>'data[GpsPack][sub_text][]'));
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
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
                            <td><?php echo $month ?></td>    
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
                        
                        <?php foreach($marketsegments as $segment_key=>$segment_val){ ?>
                        <tr>
                            <td class="bold"> <?php echo $segment_val; ?></td>
                            <td>RN</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){ 
                                  echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                  echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Last Year','name'=>'data[GpsPack][sub_text][]'));
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>ADR</td>
                            <?php for($month=$financial_month_start;$month <= ($financial_month_start + 11); $month++){
                                  echo $this->Form->input('text[]',array('type'=>'hidden','value'=>$segment_val,'name'=>'data[GpsPack][text][]'));
                                  echo $this->Form->input('sub_text[]',array('type'=>'hidden','value'=>'Last Year','name'=>'data[GpsPack][sub_text][]'));
                            ?>
                            <td><?php echo $this->Form->input('value[]',array('type'=>'text','id'=>'stp','label'=>false,'name'=>'data[GpsPack][value][]')); ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                </table>
                    
                <?php } ?>
                
	</fieldset>
<div style="float:left;width:110px;">
<?php
echo $this->Form->submit(__('Next', true), array('div' => false));
echo $this->Form->end();
?>
</div>

<div style="float:left;margin-top:5px;height:40px;">
<?php //echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'Gps', 'action' => 'index'), array('class' => 'new_button', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;'));?>
</div>
</div>

<?php echo $this->element('admin_left_menu'); ?>