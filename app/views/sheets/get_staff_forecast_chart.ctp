<?php if($_SERVER['HTTP_REFERER'] == 'https://myrevenuedashboard.net/staff/sheets'){ ?>
   <a style="font-weight:bold;" onClick="window.open('/staff/sheets/webform/<?php echo $sheet_id; ?>'); return false;" href='javascript:void(0);'><button class="btn btn-info btn-small">View Webform</button></a>
<?php } ?>

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
                data : <?php echo $bob_arr; ?>,
                tooltip: {
                    valueSuffix: ' Fcst Room'
                }
    
            }, {
                name: 'ADR Fcst',
                color: '#89A54E',
                type: 'spline',
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
