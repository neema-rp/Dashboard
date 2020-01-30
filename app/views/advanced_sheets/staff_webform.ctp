<?php
?>
<style type="text/css">
table{ font-size:90%;  margin-bottom: 0px; color: #6B6F6F; } 
input { width:40px; height:15px; font-size:100%; border:1px solid #F5F5F5; }
input[readonly=readonly]{ background: #BDBDBD; border:1px solid #BDBDBD; }
table tr td{ background:none; font-size:11px; padding:3px; }
.content_head{ font-weight:bold;background:#BDBDBD; }
table tr:nth-child(2n) td{ background:none; }
table tr:nth-child(2n) td.content_head{ background:#BDBDBD; }
.normal { background-color: #fff; }
.highlight { background-color: #E1E2E1;  }
table tr{ background:#fff; }
table th{ color:#6B6F6F;  border-bottom:#6B6F6F; }
.scrollingtable {
	box-sizing: border-box; display: inline-block; vertical-align: middle; 
        overflow: hidden;
	width: 100%; /*set table width here if using fixed value*/
	/*min-width: 100%;*/ /*set table width here if using %*/
	height: 460px; /*set table height here; can be fixed value or %*/
	/*min-height: 104px;*/ /*if using % height, make this at least large enough to fit scrollbar arrows + captions + thead*/
	line-height: 20px;
        text-align: left; border: 1px solid #9BC3DF; border-radius: 4px;
}
.scrollingtable * {box-sizing: border-box;}
.scrollingtable > div {
	position: relative; height: 100%; 
/*        padding-top: 20px; this determines column header height*/
}
.scrollingtable > div:before { top: 0;  background: #9BC3DF; }
.scrollingtable > div:before,
.scrollingtable > div > div:after {
	content: ""; position: absolute; z-index: -1; width: 100%; height: 50%; left: 0;
}
.scrollingtable > div > div {
	max-height: 100%;
        overflow-x: hidden;
	overflow: scroll; /*set to auto if using fixed or % width; else scroll*/
}
.scrollingtable > div > div:after {background: white;} /*match page background color*/
.scrollingtable > div > div > table {
	width: 100%; border-spacing: 0;
/*	margin-top: -20px; inverse of column header height*/
}
.scrollingtable > div > div > table > * > tr > * {padding: 0;}
.scrollingtable > div > div > table > * > tr > td {padding-left: 5px;}
.scrollingtable > div > div > table > thead {
/*	vertical-align: bottom; white-space: nowrap; text-align: center;*/
}

input[readonly="readonly"] {
     width: 100%; 
     box-sizing: border-box;
     -webkit-box-sizing:border-box;
     -moz-box-sizing: border-box;
}
.market_head{
    font-weight: bold;
    background-color: #9BC3DF;
    border-right:1px solid #ccc;
}
</style>

    
<!--<div id="main_container" class="index" >-->
<!--       <fieldset>  
           <legend><?php echo $sheet_name; ?> for <?php echo $dept_name; ?> Department</legend>-->
        <div class="center_content_pages" style="margin:auto;padding:10px;float: right;overflow: scroll;">
            <div class="scrollingtable">
            <input type="hidden" id="sheetId" value="<?php echo $sheetId; ?>" />
                <div><div>
                <table border="0" cellspacing="0" cellpadding="4">
                  
                    <tr>
                        <td style="width:10%;" class="market_head">&nbsp;</td>
                        <td class="market_head">Date</td>
                        <?php if(!empty($marketSegments)){
                                foreach($marketSegments as $market){ ?>
                                    <td class="market_head"><?php echo $market; ?></td>
                            <?php  }
                          } ?>
                          <td style="width:10%;" class="market_head">Total</td>
                    </tr>
                  
                    <tbody>
                      <?php if(!empty($final_array)){
                            $i = '1';
                            $segmentBOB = array(); $segmentADR = array();
                            foreach($final_array as $day => $colsArray){
                                $daystart = '1';
                                $bob_array = array(); $adr_array = array();
                                $bob_total = '0';
                                foreach($colsArray as $col => $resultArray){
                                $row_total = '0';
                                if($daystart == '1'){
                                    $style1='style="border-top:2px solid #888181;"';
                                }else{
                                    $style1='style=""';
                                }
                                $weekday= date("w", strtotime(str_replace('/','-',$day)) ); //0-sunday & 6-saturday
                                if($weekday == '5' || $weekday == '6' || $weekday == '0'){
                                    if($daystart == '1'){
                                        $style1 = 'style="border-top:2px solid #888181;background:#E3F5EA;"';
                                    }else{
                                        $style1 = 'style="background:#E3F5EA;"';
                                    }
                                } ?>
                                <tr <?php echo $style1; ?> onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                                    <td><?php echo $col; ?></td>
                                    <td><?php echo $day; ?></td>
                                     <?php foreach($resultArray as $seg_key=>$segment_vals){
                                        $row_total = $row_total + $segment_vals; 
                                        if($col == 'BOB'){
                                            $bob_array[$seg_key] = $segment_vals;
                                            $segmentBOB[$seg_key][$day] = $segment_vals;
                                        }
                                        if($col == 'ADR'){
                                            $adr_array[$seg_key] = $segment_vals;
                                            $segmentADR[$seg_key][$day] = $segment_vals;
                                        }
                                        ?>
                                            <td>
                                                <input data-date="<?php echo $i; ?>" data-segment="<?php echo $seg_key; ?>" data-column="<?php echo $col; ?>"  class="row_<?php echo str_replace(' ','-',$col); ?>_<?php echo $i; ?> col_<?php echo str_replace(' ','-',$col); ?>_<?php echo $seg_key; ?>" type="text" value="<?php echo $segment_vals; ?>">
                                            </td>
                                     <?php } ?>
                                      <?php $revenue_total = '0';
                                      if(!empty($bob_array) && !empty($adr_array)){
                                          foreach($bob_array as $bobkey => $bobval){
                                              $revenue_total = $revenue_total + ($bobval * $adr_array[$bobkey]);
                                          }
                                      }
                                      if($col == 'BOB'){
                                          $bob_total = $row_total;
                                      }
                                      if($col == 'ADR'){
                                          $row_total = $revenue_total/$bob_total;
                                         $row_total = number_format($row_total, 2);
                                      } ?>    
                                     <td class="content_head">
                                            <input readonly="readonly" id="total_row_<?php echo str_replace(' ','-',$col); ?>_<?php echo $i; ?>" type="text" value="<?php echo $row_total; ?>" />
                                    </td>
                                </tr>
                            <?php $daystart = '0'; }
                            $i++; }
                      } ?>
                    <?php if(!empty($final_array_total)){
                            foreach($final_array_total as $day => $colsArray){
                                 $bobFinal = '0';
                                foreach($colsArray as $col => $resultArray){
                                    $row_total = '0'; ?>
                               <tr>
                                    <td class="content_head"><?php echo $col; ?></td>
                                    <td class="content_head"><?php echo $day; ?></td>
                                     <?php foreach($resultArray as $seg_key=>$segment_vals){
                                          if($col == 'ADR'){
                                              $bobFinal = $colsArray['BOB'][$seg_key];
                                              $revenueFinal = '0';
                                              if(!empty($segmentBOB[$seg_key]) && !empty($segmentADR[$seg_key])){
                                                  foreach($segmentBOB[$seg_key] as $bobkey => $bobval){
                                                       $revenueFinal = $revenueFinal + ($bobval * $segmentADR[$seg_key][$bobkey]);
                                                  }
                                              }                                    
                                              $segment_vals = $revenueFinal/$bobFinal;
                                              $segment_vals = number_format($segment_vals, 2);
                                          }else{
                                              //$segment_vals = '';
                                              $segment_vals = number_format($segment_vals, 0);
                                          }
                                          $row_total = $row_total + str_replace(',','',$segment_vals);
                                         if($col == 'Fcst Rooms' || $col == 'BOB'){
                                              $row_total = $row_total;
                                          }else{
                                              $row_total = number_format($row_total, 2);
                                          }
                                          
                                          ?>
                                        <td class="content_head" style="padding-left:0px;">
                                                <input class="total_<?php echo str_replace(' ','-',$col); ?>" readonly="readonly" id="total_col_<?php echo str_replace(' ','-',$col); ?>_<?php echo $seg_key; ?>" type="text" value="<?php echo $segment_vals; ?>">
                                        </td>
                                     <?php } ?>
                                     <td class="content_head">
                                        <input id="final_<?php echo str_replace(' ','-',$col); ?>" readonly="readonly" type="text" value="<?php echo $row_total; ?>"  />
                                    </td>
                                </tr>
                            <?php }
                             }
                      } ?>     
                      <?php if(!empty($total_rows_array)){
                            foreach($total_rows_array as $day => $colsArray){
                                foreach($colsArray as $col => $resultArray){ 
                                    //print_r($colsArray); exit;
                                    
                                $row_total = '0'; $readonly = '';
                                if(in_array($col, $lockedIds)){
                                    $readonly = "readonly='readonly'";
                                }
                               
                                $col = str_replace("'",'',$col);
                                ?>
                               <tr>
                                    <td class="content_head"><?php echo $col; ?></td>
                                    <td class="content_head">&nbsp;</td>
                                     <?php foreach($resultArray as $seg_key=>$segment_vals){
                                         if($col == 'Revenue' || $col == 'Rev Fcst' || $col == 'Pickup Req'){
                                                $segment_vals = (int)$segment_vals;
                                                $segment_vals = number_format($segment_vals, 0);
                                           }
                                           if($col == 'Sell Rate'){
                                               $segment_vals = number_format($segment_vals, 2);
                                           }
                                         $main_val = str_replace(',','',$segment_vals);
                                         $row_total = $row_total + $main_val;
                                        ?>
                                        <td class="content_head">
                                                <input <?php echo $readonly; ?> id="<?php echo str_replace(' ','-',$col); ?>_<?php echo $seg_key; ?>" data-date="0" data-segment="<?php echo $seg_key; ?>" data-column="<?php echo $col; ?>" class="result_row_col_<?php echo str_replace(' ','-',$col); ?>_<?php echo $seg_key; ?> result_column_<?php echo str_replace(' ','-',$col); ?>" type="text" value="<?php echo $segment_vals; ?>" />
                                        </td>
                                     <?php }  
                                     if($col == 'Revenue' || $col == 'Rev Fcst' || $col == 'Pickup Req'){
                                         $row_total = number_format($row_total, 0);
                                     }else{
                                         $row_total = number_format($row_total, 2);
                                     }
                                     ?>
                                     <td class="content_head">
                                         <input class="final_result_seg_<?php echo str_replace(' ','-',$col); ?>" id="final_result_<?php echo str_replace(' ','-',$col); ?>" readonly="readonly" type="text" value="<?php echo $row_total; ?>" />                                         
                                     </td>
                                </tr>
                            <?php }
                             }
                      } ?>
                </tbody>
                </table>
                </div></div>
            </div>
            <div class="clear"></div>
        </div>

<!--    </div>-->

<script>
$(document).ready(function(){
    
    /*
     *1. BOB and Fcst Rooms, NO decimal points, but please add thousands separator
     *2. ADR and ADR forecast should have 2 decimals, and thousands separator
     *3. Pickup required is no decimal, but thousands separator
     *3. Sell Rate is 2 decimals, and thousand separator
     *4. Revenue and Revenue Forecast should be no decimals, and thousands separator
     **/
    
    setTimeout(function() {
        var rev_fcst_total = $('#final_result_Rev-Fcst').val().replace(/,/g, "");
        var fcst_room_total = $('#final_Fcst-Rooms').val().replace(/,/g, "");
        var revenue_total = $('#final_result_Revenue').val().replace(/,/g, "");
        var pickup_total = $('#final_result_Pickup-Req').val().replace(/,/g, "");

        var adr_fcst_total = parseFloat(rev_fcst_total)/parseFloat(fcst_room_total);
        var sell_rate_total = parseFloat(parseFloat(rev_fcst_total) - parseFloat(revenue_total))/parseFloat(pickup_total);
        adr_fcst_total = parseFloat(adr_fcst_total.toFixed(2));
        sell_rate_total = parseFloat(sell_rate_total.toFixed(2));        
        console.log(adr_fcst_total);
        $("#final_result_ADR-Fcst").val(adr_fcst_total);
        console.log(sell_rate_total);
        $('#final_result_Sell-Rate').val(sell_rate_total);
        
        var adrTotal = parseFloat($('#final_result_Revenue').val().replace(/,/g, ""))/parseFloat($('#final_BOB').val().replace(/,/g, ""));
        $('#final_ADR').val(adrTotal.toFixed(2));
        
    }, 4000);
});

$("input").keyup(function () {
    var updated_val = $(this).val().replace(/,/g, "");
    var sheetId = $('#sheetId').val();
    var column_ori = $(this).attr("data-column");
    var column = column_ori.replace(/ /g, "-");
    var day = $(this).attr("data-date");
    var segment = $(this).attr("data-segment");
    if(day != '0'){
        var row_class = 'row_'+column+'_'+day;
        var col_class = 'col_'+column+'_'+segment;
        var row_total = '0';
        $('.'+row_class).each(function() {
             row_total = parseInt(row_total) + parseInt($(this).val().replace(/,/g, ""));
        });
//        console.log(row_total);
//        console.log(row_class);
        var col_total = '0';
        $('.'+col_class).each(function() {
             col_total = parseInt(col_total) + parseInt($(this).val().replace(/,/g, ""));
        });
        if(column_ori == 'ADR' || column_ori == 'BOB'){
            var bob_total = $('#total_row_BOB_'+day).val().replace(/,/g, "");
            var revenue_total = '0';
            $('.row_BOB_'+day).each(function() {
                  var bob_segment = $(this).attr("data-segment");
                  var adrval = $('input[data-date="' + day + '"][data-segment="' + bob_segment + '"][data-column="ADR"]').val().replace(/,/g, "");
                  revenue_total = parseInt(revenue_total) + parseInt(parseInt($(this).val().replace(/,/g, "")) * parseInt(adrval));
            });
            
           var bob_final_total = $('#total_col_BOB_'+segment).val().replace(/,/g, "");
           var revenue_final_total = '0';
            $('.col_BOB_'+segment).each(function() {
                  var bob_date = $(this).attr("data-date");
                  var adrfval = $('input[data-date="' + bob_date + '"][data-segment="' + segment + '"][data-column="ADR"]').val().replace(/,/g, "");
                  var adrLineTotal = parseInt($(this).val().replace(/,/g, "")) * parseInt(adrfval);
                  if(isNaN(adrLineTotal)){ adrLineTotal = '0'; }
                  revenue_final_total = parseInt(revenue_final_total) + parseInt(adrLineTotal);
            });
            console.log('Revenue Final Totla : '+revenue_final_total);
            console.log('row_class : '+row_class);
            console.log('col_class : '+col_class);
           
           if(column_ori == 'BOB'){
              var adr_total = parseInt(revenue_total) / parseInt(row_total);
              $('#total_row_ADR_'+day).val(parseFloat(adr_total.toFixed(2)));
              var adr_final_total = parseInt(revenue_final_total) / parseInt(col_total);
              $('#total_col_ADR_'+segment).val((parseFloat(adr_final_total.toFixed(2))) || 0);
           }else{
                var row_total = parseInt(revenue_total) / parseInt(bob_total); //ADR = Revenue/BOB;
                row_total = parseFloat(row_total.toFixed(2));
                var col_total = parseInt(revenue_final_total) / parseInt(bob_final_total); //ADR = Revenue/BOB;
                col_total = parseFloat(col_total.toFixed(2));
           }
        }
        
        console.log('col_total : '+col_total);
        console.log('row_total : '+row_total);
        
        $('#total_'+col_class).val(col_total);
        $('#total_'+row_class).val(row_total);
        
        var final_total = '0';
        $('.total_'+column).each(function() {
             final_total = parseFloat(final_total) + parseFloat($(this).val().replace(/,/g, ""));
        });
        if(column == 'ADR'){
            $('#final_'+column).val(final_total.toFixed(2));
        }else{
            $('#final_'+column).val(final_total);
        }
//        if(column == 'BOB'){
//            var adrTotal = '0';
//            $('.total_ADR').each(function() {
//             adrTotal = parseFloat(adrTotal) + parseFloat($(this).val().replace(/,/g, ""));
//             });
//             $('#final_ADR').val(adrTotal.toFixed(2));
//        }
        
        //update to DB
        $.post( 
         "/admin/advanced_sheets/update_data/"+sheetId+"/"+day+"/"+column_ori+"/"+segment+"/"+updated_val,
         { name: "" },
         function(data) {
          }
        );
         
         $.post(
         "/admin/advanced_sheets/update_total/"+sheetId+"/"+column_ori+"/"+segment+"/"+col_total,
         { name: "" },
         function(data) {
             console.log(data);
             $.each($.parseJSON(data), function(idx, obj) {
                   $("#"+obj.id).val(obj.value);
            });
            
            var final_totalRev = '0';
            $('.final_result_seg_Revenue').each(function() {
                 var checkVal = $(this).val().replace(/,/g, "");
                final_totalRev = parseFloat(final_totalRev) + parseFloat(checkVal);
                //console.log(final_total);
            });
            $('#final_result_Revenue').val(final_totalRev);
            
            
             var adrTotal = parseFloat(final_totalRev)/parseFloat($('#final_BOB').val().replace(/,/g, ""));
            $('#final_ADR').val(adrTotal.toFixed(2));
            
          }
        );
    } else if(day == '0'){
        //update to DB
        $.post(
         "/admin/advanced_sheets/update_result/"+sheetId+"/"+column_ori+"/"+segment+"/"+updated_val,
         { name: "" },
         function(data) {
             //console.log(data);
             $.each($.parseJSON(data), function(idx, obj) {
                   $("#"+obj.id).val(obj.value);
                   
                     var final_total = '0';
                     $('.result_row_col_'+obj.id).each(function() {
                        final_total = parseFloat(final_total) + parseFloat($(this).val().replace(/,/g, ""));
                    });
                    $('#final_result_seg_'+obj.id).val(final_total);
                   
            });
            
            var final_total = '0';
             $('.result_column_'+column).each(function() {
                 var checkVal = $(this).val().replace(/,/g, "");
                final_total = parseFloat(final_total) + parseFloat(checkVal);
                //console.log(final_total);
            });
            $('#final_result_'+column).val(final_total);
            
            var rev_fcst_total = $('#final_result_Rev-Fcst').val().replace(/,/g, "");
            var fcst_room_total = $('#final_Fcst-Rooms').val().replace(/,/g, "");
            var revenue_total = $('#final_result_Revenue').val().replace(/,/g, "");
            var pickup_total = $('#final_result_Pickup-Req').val().replace(/,/g, "");
            
            var adr_fcst_total = parseFloat(rev_fcst_total)/parseFloat(fcst_room_total);
            var sell_rate_total = (parseFloat(rev_fcst_total) - parseFloat(revenue_total))/parseFloat(pickup_total);
            adr_fcst_total = parseFloat(adr_fcst_total.toFixed(2));
            sell_rate_total = parseFloat(sell_rate_total.toFixed(2));
            $("#final_result_ADR-Fcst").val(adr_fcst_total);
            $('#final_result_Sell-Rate').val(sell_rate_total);
            //ADR FCST = Rev F’CST/Fcst Rooms |||| 65 = 69/63
            //Sell Rate = (Revenue Fcst - Revenue)/Pickup Required |||| 67 = (69-68)/66
            
          }
        );
    }
});

</script>