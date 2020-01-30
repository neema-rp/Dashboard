<a style="font-weight:bold;" target="_blank" href='/client/sheets/webform/<?php echo $sheet_id; ?>'><button class="btn btn-info btn-small">View Webform</button></a>
<script type="text/javascript">
    
$(function () {
        $('#container_pickup_adr').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '<?php echo $hotelname; ?> ADR Pickup chart'
            },
            xAxis: {
               categories: <?php echo $date_arr; ?>
            },
           
            legend: {
                align: 'right',
                x: -70,
                verticalAlign: 'top',
                y: 20,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>';
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
             series: [{
                name: 'ADR Forecast',
                color: '#FA5858',
                data: <?php echo $adr_fcst_arr; ?>
            }, {
                name: 'ADR',
                color: '#2f7ed8',
                  data:  <?php echo $adr_arr; ?>
            }, {
                name: 'ADR Pickup',
                 color: '#8bbc21',
               data: <?php echo $adr_pickup_arr; ?>
            }]
        });
    });
    


		</script>
                <div><a onclick="$('#adr_pickup_table').toggle();" href="javascript:void(0);"><img src="/img/menu-alt.png"></a></div>

                <table class="table table-striped table-bordered table-hover" id="adr_pickup_table" style="display: none;">
                    <?php 
                        $date_arr = str_replace('[', "", $date_arr);
                        $date_arr = str_replace(']', "", $date_arr);
                        $date_arr = str_replace("'", "", $date_arr);
                        $dates = explode(',',$date_arr);
                    
                        $adr_pickup_arr = str_replace('[', "", $adr_pickup_arr);
                        $adr_pickup_arr = str_replace(']', "", $adr_pickup_arr);
                        $adr_pickup_arr = str_replace("'", "", $adr_pickup_arr);
                        $adr_pickups = explode(',',$adr_pickup_arr);
                        $pickup_total = '0';
                        
                        $adr_arr = str_replace('[', "", $adr_arr);
                        $adr_arr = str_replace(']', "", $adr_arr);
                        $adr_arr = str_replace("'", "", $adr_arr);
                        $adrs = explode(',',$adr_arr);
                        $adr_total = '0';
                        
                        $adr_fcst_arr = str_replace('[', "", $adr_fcst_arr);
                        $adr_fcst_arr = str_replace(']', "", $adr_fcst_arr);
                        $adr_fcst_arr = str_replace("'", "", $adr_fcst_arr);
                        $adr_fcsts = explode(',',$adr_fcst_arr);
                        $fcst_total = '0';
                    ?>
                    <tr>
                        <td><b>Date</b></td><td><b>Pickup ADR</b></td><td><b>ADR</b></td><td><b>Forecast</b></td>
                        <td>&nbsp;</td>
                        <td><b>Date</b></td><td><b>Pickup ADR</b></td><td><b>ADR</b></td><td><b>Forecast</b></td>
                        <td>&nbsp;</td>
                        <td><b>Date</b></td><td><b>Pickup ADR</b></td><td><b>ADR</b></td><td><b>Forecast</b></td>
                    </tr>
                    
                    <?php 
                    $count = '1';
                     //foreach($dates as $date){
                    $k = '0';
                    for($i='0';$i<=count($dates);$i=$i+3){
                       
                    if((($count%'3') == '0' || ($count%'3') == '1') || ($count=='1')){
                   ?>
                    <tr>
                    <?php } 
                    ?>
                        <td><b><?php echo $dates[$k]; ?></b></td><td><?php echo $adr_pickups[$k]; ?></td><td><?php echo $adrs[$k]; ?></td><td><?php echo $adr_fcsts[$k]; ?></td>
                        <td style="border-left:1px solid #ccc">&nbsp; <?php  //echo "MOD:".$count%'3'; ?> </td><?php $k++; ?>
                        <td><b><?php echo $dates[$k]; ?></b></td><td><?php echo $adr_pickups[$k]; ?></td><td><?php echo $adrs[$k]; ?></td><td><?php echo $adr_fcsts[$k]; ?></td>
                        <td style="border-left:1px solid #ccc">&nbsp;</td><?php $k++; ?>
                        <td><b><?php echo $dates[$k]; ?></b></td><td><?php echo $adr_pickups[$k]; ?></td><td><?php echo $adrs[$k]; ?></td><td><?php echo $adr_fcsts[$k]; ?></td>
                        <?php $k++; ?>
                    <?php if((($count%'3') == '0' || ($count%'3') == '1') || ($count=='1')){ ?>
                    </tr>
                    <?php }  $count++; 
                     } ?>
                        
                </table><br/>
                    

<div id="container_pickup_adr" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
