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

<?php
$promo_id = $Promo['Promo']['id'];
$client_id = $Promo['Promo']['client_id'];
$promo_month = sprintf("%02d", $month);
$year = $Promo['Promo']['year'];
?>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Edit Promotions Calendar <small><i class="icon-double-angle-right"></i> 
                    <?php echo date("M", mktime(0, 0, 0, $promo_month, 1)).'&nbsp;'.$year;  ?></small></h1>
        </div>


<?php echo $this->Form->create('Promo', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'Promos', 'action' => 'edit')));

    echo $this->Form->input('month',array('type'=>'hidden','value'=>$promo_month));
    echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id));
    echo $this->Form->input('promo_id',array('type'=>'hidden','value'=>$promo_id)); 
    echo $this->Form->input('id',array('type'=>'hidden','value'=>$promo_id));
    ?>          <?php
                $mondays = $this->requestAction('/Promos/getMondays/'.$year.'/'.$promo_month);

                $monthData = $this->requestAction('/Promos/get_promo_data/'.$promo_id.'/'.$promo_month);
                ?>
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <td>Week Of (Mon. Start)</td>
                        <?php foreach($mondays as $mon){ ?>
                            <td><b><?php echo $mon ?></b></td>
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
                                                echo $this->Form->textarea('value',array('name'=>'data[PromoData]['.$i.'][value]','value'=>$promoData['PromoData']['value'],'label'=>false));
                                                echo $this->Form->input('id',array('name'=>'data[PromoData]['.$i.'][id]','value'=>$promoData['PromoData']['id'],'type'=>'hidden'));
                                         }
                                    }
                                    }else{
                                        echo $this->Form->textarea('value',array('name'=>'data[PromoData]['.$i.'][value]','value'=>'','label'=>false));
                                        echo $this->Form->input('category',array('name'=>'data[PromoData]['.$i.'][category]','value'=>'general','type'=>'hidden'));
                                        echo $this->Form->input('category_column',array('name'=>'data[PromoData]['.$i.'][category_column]','value'=>$category,'type'=>'hidden'));
                                        echo $this->Form->input('month',array('name'=>'data[PromoData]['.$i.'][month]','value'=>$promo_month,'type'=>'hidden'));
                                        echo $this->Form->input('promo_id',array('name'=>'data[PromoData]['.$i.'][promo_id]','value'=>$promo_id,'type'=>'hidden'));
                                        echo $this->Form->input('id',array('name'=>'data[PromoData]['.$i.'][id]','value'=>'','type'=>'hidden'));
                                        echo $this->Form->input('start_date',array('name'=>'data[PromoData]['.$i.'][start_date]','type'=>'hidden','value'=>date('Y-m-d',strtotime($mon))));
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
                        <td><b>OTA Sales and Promo</b></td>
                        <?php foreach($mondays as $mon){ ?>
                            <td>&nbsp;</td>
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
                                                echo $this->Form->textarea('value',array('name'=>'data[PromoData]['.$i.'][value]','value'=>$promoData['PromoData']['value'],'label'=>false));
                                                echo $this->Form->input('id',array('name'=>'data[PromoData]['.$i.'][id]','value'=>$promoData['PromoData']['id'],'type'=>'hidden'));
                                         }
                                    }
                                    }else{
                                        echo $this->Form->textarea('value',array('name'=>'data[PromoData]['.$i.'][value]','value'=>'','label'=>false));

                                        echo $this->Form->input('category',array('name'=>'data[PromoData]['.$i.'][category]','value'=>'OTA','type'=>'hidden'));
                                        echo $this->Form->input('category_column',array('name'=>'data[PromoData]['.$i.'][category_column]','value'=>$category,'type'=>'hidden'));
                                        echo $this->Form->input('month',array('name'=>'data[PromoData]['.$i.'][month]','value'=>$promo_month,'type'=>'hidden'));
                                        echo $this->Form->input('promo_id',array('name'=>'data[PromoData]['.$i.'][promo_id]','value'=>$promo_id,'type'=>'hidden'));
                                        echo $this->Form->input('id',array('name'=>'data[PromoData]['.$i.'][id]','value'=>'','type'=>'hidden'));
                                        echo $this->Form->input('start_date',array('name'=>'data[PromoData]['.$i.'][start_date]','type'=>'hidden','value'=>date('Y-m-d',strtotime($mon))));
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
                        <td><b>Events</b></td>
                        <?php foreach($mondays as $mon){ ?>
                            <td>&nbsp;</td>
                        <?php } ?>
                    </tr>
                    
                    <?php 
                    for($day=1;$day <= $Promo['Promo']['offers_list']; $day++){
                    ?>
                    <tr>
                        <td><b>Event <?php echo $day; ?></b></td>
                        <?php foreach($mondays as $mon){ ?>
                            <td>
                                <?php
                                $match = '0';
                                //echo '<pre>'; print_r($monthData); exit;
                                if(!empty($monthData)){
                                    foreach($monthData as $promoData){
                                         if($promoData['PromoData']['category']=='Offers' &&
                                                 $promoData['PromoData']['category_column']==$day &&
                                                 $promoData['PromoData']['start_date']== date('Y-m-d',strtotime($mon))
                                         ){
                                                echo $this->Form->textarea('value',array('name'=>'data[PromoData]['.$i.'][value]','value'=>$promoData['PromoData']['value'],'label'=>false));
                                                echo $this->Form->input('id',array('name'=>'data[PromoData]['.$i.'][id]','value'=>$promoData['PromoData']['id'],'type'=>'hidden'));
                                         
                                                $match = '1'; break;
                                         }
                                    }
                                }
                                
                                    if($match == '0'){
                                        echo $this->Form->textarea('value',array('name'=>'data[PromoData]['.$i.'][value]','value'=>'','label'=>false));
                                        echo $this->Form->input('category',array('name'=>'data[PromoData]['.$i.'][category]','value'=>'Offers','type'=>'hidden'));
                                        echo $this->Form->input('category_column',array('name'=>'data[PromoData]['.$i.'][category_column]','value'=>$day,'type'=>'hidden'));
                                        echo $this->Form->input('month',array('name'=>'data[PromoData]['.$i.'][month]','value'=>$promo_month,'type'=>'hidden'));
                                        echo $this->Form->input('promo_id',array('name'=>'data[PromoData]['.$i.'][promo_id]','value'=>$promo_id,'type'=>'hidden'));
                                        echo $this->Form->input('id',array('name'=>'data[PromoData]['.$i.'][id]','value'=>'','type'=>'hidden'));
                                        echo $this->Form->input('start_date',array('name'=>'data[PromoData]['.$i.'][start_date]','type'=>'hidden','value'=>date('Y-m-d',strtotime($mon))));
                                } 
                                ?>
                            </td>
                        <?php $i++;  } ?>
                    </tr>
                    <?php } ?>
                </table>
   
        <?php
        echo $this->Form->submit(__('Save', true), array('div' => false,'class'=>'btn btn-info'));
        echo "&nbsp;&nbsp;";
        echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'Promos', 'action' => 'edit_steps',$promo_id), array('class' => 'btn btn-success', 'escape' => false));
        echo $this->Form->end();
        ?>
    
</div>