<?php ?>
<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Yield Performance'
            },
            subtitle: {
                text: 'Hotelname: <?php echo $hotelname; ?>'
            },
            xAxis: [{
                categories : <?php echo $date_arr; ?>,
                //tickWidth: 0,
                //gridLineWidth: 1,
//                labels: {
//                    align: 'left',
//                    x: 3,
//                    y: -3
//                }
            }],
            yAxis: [{ // Secondary yAxis
                title: {
                    text: 'ADR',
                    style: {
                        color: '#434348'
                    }
                },
                labels: {
                    format: '',
                    style: {
                        color: '#434348'                        
                    }
                },
                opposite: true
            },{ // Primary yAxis
                labels: {
                    format: '',
                    style: {
                       color: '#7cb5ec'
                    }
                },
                title: {
                    text: 'BOB',
                    style: {
                       color: '#7cb5ec'
                    }
                }
            }],
            tooltip: {
                shared: true,
                crosshairs: true
            },
            legend: {
                align: 'left',
                verticalAlign: 'top',
                y: 20,
                floating: true,
                borderWidth: 0
            },
            series: [{
                name: 'BOB',
                //color: '#4572A7',
                type: 'spline',
                yAxis: 1,
                
                //lineWidth: 4,
                marker: {
                    radius: 4
                },
                
                data : <?php echo $bob_arr; ?>,
                tooltip: {
                    valueSuffix: ' BOB'
                }
            }, {
                name: 'ADR',
                //color: '#89A54E',
                type: 'spline',
                data : <?php echo $adr_arr; ?>,
                tooltip: {
                    valueSuffix: ' ADR'
                }
            }]
        });
    });	
</script>

<!--<script src="/js/highcharts.js"></script>
<script src="/js/modules/exporting.js"></script>-->

<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>