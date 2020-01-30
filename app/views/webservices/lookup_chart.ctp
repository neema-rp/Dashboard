<?php ?>
<script type="text/javascript">
$(function () {
        $('#container_lookup').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Trends Chart'
            },
            subtitle: {
                text: 'Hotelname: <?php echo $hotelname; ?>'
            },
            xAxis: [{
                categories : <?php echo $date_arr; ?>
            }],
            yAxis: [{ // Secondary yAxis
                title: {
                    text: 'ADR and ADR Present Year',
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
                    text: 'BOB and BOB Present Year',
                    style: {
                       color: '#7cb5ec'
                    }
                }
            }],
            tooltip: {
                shared: true
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
                color: '#2f7ed8',
                type: 'spline',
                yAxis: 1,
                marker: {
                    radius: 4
                },
                data : <?php echo $bob_arr; ?>,
                tooltip: {
                    valueSuffix: ' BOB'
                }
            }, {
                name: 'ADR',
                color: '#0d233a',
                type: 'spline',
                data : <?php echo $adr_arr; ?>,
                tooltip: {
                    valueSuffix: ' ADR'
                }
            },
            {
                name: 'BOB Present Year',
                color: '#2f7ed8',
                type: 'column',
                yAxis: 1,
                marker: {
                    radius: 4
                },
                data : <?php echo $bob_present_arr; ?>,
                tooltip: {
                    valueSuffix: ' BOB Present Year'
                }
            }, {
                name: 'ADR Present Year',
                color: '#0d233a',
                type: 'column',
                data : <?php echo $adr_present_arr; ?>,
                tooltip: {
                    valueSuffix: ' ADR Present Year'
                }
            }]
        });
    });	
</script>
<div id="container_lookup" style="min-width: 400px; height: 400px; margin: 0 auto"></div>