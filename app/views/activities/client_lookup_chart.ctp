<?php ?>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

<script src="/js/highcharts.js"></script>
<script src="/js/modules/exporting.js"></script>

<script>
    $(document).ready(function() {
	$("#inputField").datepicker({ dateFormat: 'yy-mm-dd' });
	$("#inputField1").datepicker({ dateFormat: 'yy-mm-dd' });
        
        $("#inputField2").datepicker({ dateFormat: 'yy-mm-dd' });
	$("#inputField3").datepicker({ dateFormat: 'yy-mm-dd' });
 });
    
 function get_lookup_chart(){

        var client_id = $('#client_id').val();
        var start_date = $('#inputField').val();
        var end_date = $('#inputField1').val();
        
        var start_date_present = $('#inputField2').val();
        var end_date_present = $('#inputField3').val();
        
        if(client_id == '' || start_date == '' || end_date ==''){
            alert('Please Select All Details.');
            return false;
        }
        $('#lookup_chart_area').html('<div style="text-align:center;">Loading Please wait....</div>');
        $('#new_lookup_graph_chart').remove();
        $.post( 
             "/webservices/lookup_chart/"+client_id+"/"+start_date+"/"+end_date+"/"+start_date_present+"/"+end_date_present,
             { },
             function(data) {
                var data_new = '<div id="new_lookup_graph_chart">'+data+'</div>';
                $('#lookup_chart_area').html(data_new);
             }
          );
}
 
</script>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotel <small><i class="icon-double-angle-right"></i> Trends Chart</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Trends Chart</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
        <div style="text-align:left;background:#fff;">
            <table cellspacing="0" cellpadding="0" border="0" style="width:75%;" id="searchBox">
                    <tr>
                        <td>Select Date from Last Year</td>
                        <?php $date = date('Y-m-d');?>
                        <td style="border-bottom:none;">
                            From:(YYYY-mm-dd)<br/><input  id="inputField" type="text" style="width: 230px;border:1px solid #ccc;" />
                        </td>
                        <td style="border-bottom:none;">
                            To:(YYYY-mm-dd)<br/><input  id="inputField1" type="text" style="width: 220px;border:1px solid #ccc;" />
                        </td>
                    </tr>
                    <tr>
                        <td>Select Date from Present Year to compare</td>
                        <td style="border-bottom:none;">
                            From:(YYYY-mm-dd)<br/><input  id="inputField2" type="text" style="width: 230px;border:1px solid #ccc;" />
                        </td>
                        <td style="border-bottom:none;">
                            To:(YYYY-mm-dd)<br/><input  id="inputField3" type="text" style="width: 220px;border:1px solid #ccc;" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-bottom:none;">
                            Select Hotel
                            <?php
                            echo $form->select('client_id',$clients_list,null, array('empty' => '-- Select Hotel --','style' => 'width:200px;border:1px solid #ccc;'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td  colspan="3" >
                            <a href="javascript:void(0);" onClick="get_lookup_chart();" class="btn btn-info">Submit</a>
                        </td>
                    </tr>
            </table>
        </div>
        <?php echo $form->end(); ?>
            
	<div id="lookup_chart_area"></div>

	</div></div></div></div>
</div>