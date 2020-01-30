<?php ?>

<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>

<script src="/js/highcharts.js"></script>
<script src="/js/modules/exporting.js"></script>

<script>

function get_pickup_chart(){

        var client_id = $('#pickup_hotel_id').val();
        var pickup_date = $('#pickup').val();
        var month = $('#hotel_month_pickup').val();
        var year = $('#hotel_year_pickup').val();

       $('#pickup_chart_area').html('<div style="text-align:center;">Loading Please wait....</div>');
        $('#new_pickup_graph_chart').remove();
        $.post( 
             "/admins/get_pickup_chart_weekly/"+client_id+"/"+pickup_date+"/"+month+"/"+year,
             { name: "Zara" },
             function(data) {
                var data_new = '<div id="new_pickup_graph_chart">'+data+'</div>';
                $('#pickup_chart_area').html(data_new);
             }

          );        
}

function get_adr_pickup_chart(){

        var client_id = $('#pickup_hotel_id').val();
        var pickup_date = $('#pickup').val();
        var month = $('#hotel_month_pickup').val();
        var year = $('#hotel_year_pickup').val();

       $('#adr_pickup_chart_area').html('<div style="text-align:center;">Loading Please wait....</div>');
        $('#new_adr_pickup_graph_chart').remove();
        $.post( 
             "/admins/get_adr_pickup_chart_weekly/"+client_id+"/"+pickup_date+"/"+month+"/"+year,
             { name: "" },
             function(data) {
                var data_new = '<div id="new_adr_pickup_graph_chart">'+data+'</div>';
                $('#adr_pickup_chart_area').html(data_new);
             }

          );        
}

    function get_chart(client_id){
        
       var hotel_year = '0';
       var hotel_month = '0';

       $('#chart_area').html('<div style="text-align:center;">Loading Please wait....</div>');
        $('#new_graph_chart').remove();
        $.post( 
             "/admins/get_chart/"+client_id+"/"+hotel_month+"/"+hotel_year,
             { name: "Zara" },
             function(data) {
                var data_new = '<div id="new_graph_chart">'+data+'</div>';
                $('#chart_area').html(data_new);
             }

          );
        
    }
    function get_fcst_chart(client_id){
       
        var hotel_year = '0';
        var hotel_month = '0';

       $('#chart_area').html('<div style="text-align:center;">Loading Please Wait...</div>');
        $('#new_graph_chart').remove();
        $.post( 
             "/admins/get_forecast_chart/"+client_id+"/"+hotel_month+"/"+hotel_year,
             { name: "Zara" },
             function(data) {
                var data_new = '<div id="new_graph_chart">'+data+'</div>';
                $('#chart_area').html(data_new);
             }
          );
    }

     function get_combined_chart(client_id){

        var hotel_year = '0';
        var hotel_month = '0';

       $('#chart_area').html('<div style="text-align:center;">Loading Please Wait....</div>');
        $('#new_graph_chart').remove();
        $.post( 
             "/admins/get_combined_chart/"+client_id+"/"+hotel_month+"/"+hotel_year,
             { name: "Zara" },
             function(data) {
                var data_new = '<div id="new_graph_chart">'+data+'</div>';
                $('#chart_area').html(data_new);
             }
          );
    }
</script>


            <p style="float:right;">
                    <a href="javascript:void(0);" onClick="print_report();"><button class="btn btn-app btn-light btn-mini">
                    <i class="icon-print bigger-160"></i>
                    Print
                    </button></a>
                </p>
            <br/>
<div class="admins index" style="border-left:0 none;width:100%;" id="print_page_div">
	
	<legend><?php __('Weekly Revenue Summary - Week Ending');  echo ' '.date('d M');?></legend>
        
        View Hotel Rooms Department Graph
        <input type="hidden" id="hotel_id" value="<?php echo $client_id ?>">
        <br/>
    <div id="chart_area"></div>
    <br/><br/><br/>
        

        View Rooms Department Pickup Report
        <br/>
            <input type="hidden" value="<?php echo $client_id; ?>" id="pickup_hotel_id">
            <input type="hidden" value="<?php echo date('m'); ?>" id="hotel_month_pickup">
            <input type="hidden" value="<?php echo date('Y'); ?>" id="hotel_year_pickup">
            <input type="hidden" value="<?php echo date('d',strtotime('-1 Day')); ?>" id="pickup">
        
        <br/>
    <div id="pickup_chart_area"></div>

    <br/>
    View Rooms Department ADR Pickup Report
    <br/>
        <div id="adr_pickup_chart_area"></div>
    <br/><br/><br/>
    

<table class="table table-striped table-bordered table-hover">
    <tr>
    <td width="40%"><b>FORECAST</b></td>
    <td width="30%"><b>This Week</b></td>
    <td width="30%"><b>Last Week</b></td>
    </tr>
    <tr>
    <td><b>Occ</b></td>
        <?php                   
//            $pickup_occ = str_replace(",", "", $Sheetdata_today["Fcst Rooms"]) - str_replace(",", "", $SheetdataDetails["Fcst Rooms"]);
//            $pickup_adr = str_replace(",", "", $Sheetdata_today["ADR F'cst"]) - str_replace(",", "", $SheetdataDetails["ADR F'cst"]);
//            $pickup_revenue = str_replace(",", "", $Sheetdata_today["Revenue"]) -  str_replace(",", "", $SheetdataDetails["Revenue"]);

            $pickup_occ = str_replace(",", "", $Sheetdata_today["BOB"]) - str_replace(",", "", $SheetdataDetails["BOB"]);
            $pickup_revenue = str_replace(",", "", $Sheetdata_today["Revenue"]) -  str_replace(",", "", $SheetdataDetails["Revenue"]);
            $pickup_adr = number_format(($pickup_revenue/$pickup_occ),2);
            
            
            $req_pickup_occ = str_replace(",", "", $Sheetdata_today["Fcst Rooms"]) - str_replace(",", "", $Sheetdata_today["BOB"]);
            
            $req_pickup_revenue = str_replace(",", "", $Sheetdata_today["Rev Fcst"]) -  str_replace(",", "", $Sheetdata_today["Revenue"]);
            
            //"Required ADR" = "Required Revenue" / "Required Occ RN"
            $req_pickup_adr = number_format(($req_pickup_revenue/$req_pickup_occ),2);
            
            ?>
                <td><?php echo $Sheetdata_today['Fcst Rooms']; ?></td>
                <td><?php echo $SheetdataDetails['Fcst Rooms']; ?></td>
                </tr>
                <tr>
                <td><b>ADR</b></td>
                <td><?php echo $Sheetdata_today["ADR Fcst"]; ?></td>
                <td><?php echo $SheetdataDetails["ADR Fcst"]; ?></td>
                </tr>
                <tr>
                <td><b>RevPAR</b></td>
                <td><?php echo $Sheetdata_today['RevPAR Fcst'] ?></td>
                <td><?php echo $SheetdataDetails['RevPAR Fcst'] ?></td>
                </tr>
                <tr>
                <td><b>Rev</b></td>
                <td><?php echo  $Sheetdata_today['Rev Fcst'] ?></td>
                <td><?php echo $SheetdataDetails['Rev Fcst'] ?></td>
                </tr>
                </table><br/>

<!--                Pickup Table -->
                <table class="table table-striped table-bordered table-hover">
                <tr>
                <td width="40%"><b>PICKUP</b></td>
                <td width="30%"><b>This Week</b></td>
                <td width="30%"><b>Required</b></td>
                </tr>
                <tr>
                <td><b>Occ RN</b></td>
                <td><?php echo $pickup_occ ?></td>
                <td><?php echo $req_pickup_occ ?></td>
                </tr>
                <tr>
                <td><b>ADR</b></td>
                <td><?php echo $pickup_adr ?></td>
                <td><?php echo $req_pickup_adr ?></td>
                </tr>
                <tr>
                <td><b>Revenue</b></td>
                <td><?php echo number_format($pickup_revenue,2); ?></td>
                <td><?php echo number_format($req_pickup_revenue,2); ?></td>
                </tr>
                </table>


<br/>

</div>
            
<p><b>Notes :</b> <textarea style="width:90%;height:80px;" id="notes_text"></textarea></p>

<div style="display:none;" id="hidden_notes"></div>



<?php //echo $this->element('admin_left_menu'); ?>

<script>
$(document).ready(function(){    

setTimeout(function() {
get_chart($('#hotel_id').val());
get_pickup_chart();
get_adr_pickup_chart();
}, 1000);

$( "#notes_text" ).mouseout(function() {
    var notes_text = $( "#notes_text" ).val();
    var final_notes = notes_text.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '<br />');
  $('#hidden_notes').html(final_notes);
});

});

</script>


<script>
function print_report(){
    var prtContent = document.getElementById("print_page_div");
    var notesContent = document.getElementById("hidden_notes");
    //var finalContent = prtContent+'Notes'+notesContent
    var WinPrint = window.open('', '', 'left=0,top=0,width=500,height=600,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write('<html><head>');
    WinPrint.document.write('<link rel="stylesheet" href="https://myrevenuedashboard.net/css/ace.min.css">');
    WinPrint.document.write('<link rel="stylesheet" href="https://myrevenuedashboard.net/css/bootstrap.min.css">');
    WinPrint.document.write('</head><body onload="print();close();">');
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.write('<br/><b>Notes: </b>');
    WinPrint.document.write(notesContent.innerHTML);
    WinPrint.document.write('</body></html>');
    WinPrint.document.close();
    WinPrint.focus();
}
</script>