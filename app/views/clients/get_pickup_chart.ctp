<a style="font-weight:bold;" target="_blank" href='/client/sheets/webform/<?php echo $sheet_id; ?>'><button class="btn btn-info btn-small">View Webform</button></a>
<script type="text/javascript">
    
$(function () {
        $('#container_pickup').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '<?php echo $hotelname; ?> Pickup chart'
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
                name: 'BOB Forecast',
                color: '#FA5858',
                data: <?php echo $bob_fcst_arr; ?>
            }, {
                name: 'BOB',
                color: '#2f7ed8',
                  data:  <?php echo $bob_arr; ?>
            }, {
                name: 'BOB Pickup',
                 color: '#8bbc21',
               data: <?php echo $bob_pickup_arr; ?>
            }]
        });
    });
    


		</script>
                
                <table style="border:1px solid #ccc;font-size:11px;">
                    <tr>
                        <td><b>Date</b></td>
                        <?php $date_arr = str_replace('[', "", $date_arr);
                        $date_arr = str_replace(']', "", $date_arr);
                        $date_arr = str_replace("'", "", $date_arr);
                        $dates = explode(',',$date_arr);
                        foreach($dates as $date){ ?>
                        <td><b><?php echo $date; ?></b></td>   
                        <?php } ?>
                    </tr>
               <tr>
                   <td><b>Pickup BOB</b></td>
                        <?php $bob_pickup_arr = str_replace('[', "", $bob_pickup_arr);
                        $bob_pickup_arr = str_replace(']', "", $bob_pickup_arr);
                        $bob_pickup_arr = str_replace("'", "", $bob_pickup_arr);
                        $bob_pickups = explode(',',$bob_pickup_arr);
                        foreach($bob_pickups as $bob_pickup){ ?>
                        <td>&nbsp;<?php echo $bob_pickup; ?></td>   
                        <?php } ?>
               </tr>
                <tr>
                   <td><b>BOB</b></td>
                        <?php $bob_arr = str_replace('[', "", $bob_arr);
                        $bob_arr = str_replace(']', "", $bob_arr);
                        $bob_arr = str_replace("'", "", $bob_arr);
                        $bobs = explode(',',$bob_arr);
                        foreach($bobs as $bob){ ?>
                        <td>&nbsp;<?php echo $bob; ?></td>   
                        <?php } ?>
               </tr>
                <tr>
                   <td><b>Forecast</b></td>
                        <?php $bob_fcst_arr = str_replace('[', "", $bob_fcst_arr);
                        $bob_fcst_arr = str_replace(']', "", $bob_fcst_arr);
                        $bob_fcst_arr = str_replace("'", "", $bob_fcst_arr);
                        $bob_fcsts = explode(',',$bob_fcst_arr);
                        foreach($bob_fcsts as $bob_fcst){ ?>
                        <td>&nbsp;<?php echo $bob_fcst; ?></td>   
                        <?php } ?>
               </tr>
                </table><br/>
                    

<div id="container_pickup" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
