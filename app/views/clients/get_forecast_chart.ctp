<?php
//
//echo '<pre>';
//print_R($all_sheets);
//echo '</pre>';

//$sheet_id = $all_sheets[0]['Sheet']['id'];


?>

<a style="font-weight:bold;" target="_blank" href='/client/sheets/webform/<?php echo $sheet_id; ?>'><button class="btn btn-info btn-small">View Webform</button></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a style="font-weight:bold;" onClick="get_chart('<?php echo $client_id; ?>');" href='javascript:void(0);'><button class="btn btn-info btn-small">View Daily Chart</button></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a style="font-weight:bold;" onClick="get_combined_chart('<?php echo $client_id; ?>');" href='javascript:void(0);'><button class="btn btn-info btn-small">View Combined Chart</button></a>
<br/>
<br>

<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Webform - Rooms Department ( <?php echo $hotelname; ?> )'
            },
            subtitle: {
                text: 'Daily Update'
            },
            xAxis: [{
                //categories: ['1', '2', '3', '4', '5', '6','7', '8', '9', '10', '11', '12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30']
                categories : <?php echo $date_arr; ?>
            }],
            yAxis: [{ // Secondary yAxis
                title: {
                    text: 'ADR Fcst',
                    style: {
                        color: '#89A54E'
                    }
                },
                labels: {
                    format: '',
                    style: {
                        color: '#89A54E'
                        
                    }
                },
                opposite: true
            },{ // Primary yAxis
                labels: {
                    format: '',
                    style: {
                       color: '#4572A7'
                    }
                },
                title: {
                    text: 'Fcst Room',
                    style: {
                       color: '#4572A7'
                    }
                }
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 100,
                floating: true,
                backgroundColor: '#FFFFFF'
            },
            series: [{
                name: 'Fcst Room',
                color: '#4572A7',
                type: 'column',
                yAxis: 1,
               // data: [10.0,135.0,155.0,129.0,136.0,135.0,185.0,187.0,135.0,138.0, 114.0, 10.0,11.0,14.0,141.0,1100.0,163.0,165.0,135.0,158.0,126.0,131.0,123.0,198.0,18.0,162.0,167.0,814.0,817.0,17.0],
                data : <?php echo $bob_arr; ?>,
                tooltip: {
                    valueSuffix: ' Fcst Room'
                }
    
            }, {
                name: 'ADR Fcst',
                color: '#89A54E',
                type: 'spline',
                //data: [0.0, 35.0, 55.0, 29.0, 36.0, 35.0,85.0, 87.0, 35.0,38.0, 14.0, 0.0,1.0,4.0,41.0,100.0,63.0,65.0,35.0,58.0,26.0,31.0,23.0,98.0,8.0,62.0,67.0,84.0,87.0,7.0],
                data : <?php echo $adr_arr; ?>,
                tooltip: {
                    valueSuffix: ' ADR Fcst'
                }
            }]
        });
    });
    

		</script>
	</head>
	<body>

<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
