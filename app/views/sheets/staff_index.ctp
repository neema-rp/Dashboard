<?php ?>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="/js/highcharts.js"></script>
<script src="/js/modules/exporting.js"></script>


<script type="text/javascript">
function make_blank()
{
 document.getElementById('search').value ="";
}

function showtext()
{
 document.getElementById('search').value ="search by sheet name";
}

function toggle_dept(month_id,year_id) {
      var ele = document.getElementById("department_div_"+month_id+"_"+year_id);
      var text = document.getElementById("DeptText_"+month_id+"_"+year_id);
      if(ele.style.display == "") {
      ele.style.display = "none";
      text.innerHTML = '<i class="icon-plus"></i>';
      } else {
      ele.style.display = "";
      text.innerHTML = '<i class="icon-minus"></i>';
      }
}

function get_adr_pickup_chart(){

        var client_id = $('#pickup_adr_hotel_id').val();
        var pickup_date = $('#adr_pickup').val();
        var month = $('#adr_hotel_month_pickup').val();
        var year = $('#adr_hotel_year_pickup').val();

        if(pickup_date == ''){
        alert('Please Enter ADR Pickup date');
        return false;
        }

        $('#adr_pickup_chart_area').html('<div style="text-align:center;">Loading Please wait....</div>');
        $('#new_adr_pickup_graph_chart').remove();
        $.post( 
             "/clients/get_staff_adr_pickup_chart/"+client_id+"/"+pickup_date+"/"+month+"/"+year,
             { name: "Zara" },
             function(data) {
                var data_new = '<div id="new_adr_pickup_graph_chart">'+data+'</div>';
                $('#adr_pickup_chart_area').html(data_new);
             }
          );
}



function view_report(client_id){
    var report_link = '<a href="/staff/sheets/weekly_report/'+client_id+'" target="_blank"><button class="btn btn-info">View Report<i class="icon-print  bigger-125 icon-on-right"></i></button></a>';
    $('#report_link_div').html(report_link);
}


function get_pickup_chart(){

        var client_id = $('#pickup_hotel_id').val();
        var pickup_date = $('#pickup').val();

        var month = $('#hotel_month_pickup').val();

        var year = $('#hotel_year_pickup').val();

        if(pickup_date == ''){
        alert('Please Enter Pickup date');
        return false;
        }

       $('#pickup_chart_area').html('<div style="text-align:center;">Loading Please wait....</div>');
        $('#new_pickup_graph_chart').remove();
        $.post( 
             "/clients/get_pickup_chart_new/"+client_id+"/"+pickup_date+"/"+month+"/"+year,
             { name: "Zara" },
             function(data) {
                var data_new = '<div id="new_pickup_graph_chart">'+data+'</div>';
                $('#pickup_chart_area').html(data_new);
             }

          );
        
}


    
    function get_chart(client_id){
        var hotel_year = $('#hotel_year').val();
        var hotel_month = $('#hotel_month').val();
        var departmentId = $('#departmentId').val();

        if(hotel_year == 'undefined'){ hotel_year = '0'; }
        if(hotel_month == 'undefined'){ hotel_month = '0'; }
        if(departmentId == 'undefined' || departmentId == '' || departmentId == null){
                
                departmentId = '0';
                $.post( 
                 "/admins/get_hotel_department_list/"+client_id,
                 { },
                 function(data) {
                    $('#departmentId').html(data);
                 }
              );
        }

        //$("#hotel_id").attr("onChange","get_chart(this.value);");
        //$("#hotel_year").attr("onChange","get_chart($('#hotel_id').val());");
        //$("#hotel_month").attr("onChange","get_chart($('#hotel_id').val());");

        $('#chart_area').html('<div style="text-align:center;">Loading Please wait....</div>');
        $('#new_graph_chart').remove();
        $.post( 
             "/sheets/get_staff_chart/"+client_id+"/"+hotel_month+"/"+hotel_year+"/"+departmentId,
             { name: "Zara" },
             function(data) {
                var data_new = '<div id="new_graph_chart">'+data+'</div>';
                $('#chart_area').html(data_new);
             }
          );        
    }
    
function get_fcst_chart(client_id){
       
        var hotel_year = $('#hotel_year').val();
        var hotel_month = $('#hotel_month').val();

        $("#hotel_id").attr("onChange","get_fcst_chart($('#hotel_id').val());");
        $("#hotel_year").attr("onChange","get_fcst_chart($('#hotel_id').val());");
        $("#hotel_month").attr("onChange","get_fcst_chart($('#hotel_id').val());");

        $('#chart_area').html('<div style="text-align:center;">Loading Please wait....</div>');
        $('#new_graph_chart').remove();
        $.post( 
             "/sheets/get_staff_forecast_chart/"+client_id+"/"+hotel_month+"/"+hotel_year,
             { name: "Zara" },
             function(data) {
                var data_new = '<div id="new_graph_chart">'+data+'</div>';
                $('#chart_area').html(data_new);
             }
          );        
}

function get_combined_chart(client_id){

        var hotel_year = $('#hotel_year').val();
        var hotel_month = $('#hotel_month').val();

        $("#hotel_id").attr("onChange","get_combined_chart($('#hotel_id').val());");
        $("#hotel_year").attr("onChange","get_combined_chart($('#hotel_id').val());");
        $("#hotel_month").attr("onChange","get_combined_chart($('#hotel_id').val());");

       $('#chart_area').html('<div style="text-align:center;">Loading Please wait....</div>');
        $('#new_graph_chart').remove();
        $.post( 
             "/sheets/get_staff_combined_chart/"+client_id+"/"+hotel_month+"/"+hotel_year,
             { name: "Zara" },
             function(data) {
                var data_new = '<div id="new_graph_chart">'+data+'</div>';
                $('#chart_area').html(data_new);
             }
          );        
}


</script>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotel Dashboard <small><i class="icon-double-angle-right"></i> overview</small></h1>
        </div><!--/.page-header-->
        
        <div class="widget-box" style="display: none;">
                <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Weekly Report</h4>
                </div>
                <div class="widget-body">
                 <div class="widget-main">
                    <div class="row-fluid">
                            <div style="width: 100%;">
                            <select name="weekly_report_hotel" id="weekly_report_hotel" onChange="view_report(this.value);" data-placeholder="Choose a Hotel">
                            <option>Select Hotel</option>
                            <?php foreach($child_data as $chld){ ?>
                                 <option value="<?php echo $chld['Client']['id']; ?>" <?php if($clientId == $chld['Client']['id']){ echo "selected='selected'"; } ?>><?php echo $chld['Client']['hotelname']; ?></option>            
                            <?php } ?>
                           </select>
                            <div id="report_link_div" style="font-weight:bold;text-align:center;">
                                <a href="/staff/sheets/weekly_report/<?php echo $clientId; ?>" target="_blank"><button class="btn btn-info">View Report<i class="icon-print  bigger-125 icon-on-right"></i></button></a>
                            </div>
                            </div>
                    </div>
                 </div>
                </div>
        </div>
        
        <input type="hidden" id="default_hotel" value="<?php echo $clientId; ?>" />
        
        
        
    <div class="widget-box">
    <div class="widget-header widget-header-blue widget-header-flat">
        <h4 class="lighter graph_chart_name">Department Graph</h4>
    </div>
    <div class="widget-body">
     <div class="widget-main">
        <div class="row-fluid">
            <div style="width: 100%;">
            <select style="border:1px solid #ccc;" name="hotel_id" id="hotel_id" onChange="get_chart(this.value);">
                <option>Select Hotel</option>
        <?php foreach($child_data as $chld){ ?>
             <option value="<?php echo $chld['Client']['id']; ?>" <?php if($clientId == $chld['Client']['id']){ echo "selected='selected'"; } ?>><?php echo $chld['Client']['hotelname']; ?></option>            
        <?php } ?>
           </select>
                
                <select name="departmentId" id="departmentId" onChange="get_chart($('#hotel_id').val());" style="border-radius: 4px;background-image: linear-gradient(#ffffff 20%, #f6f6f6 50%, #eeeeee 52%, #f4f4f4 100%);vertical-align: middle;margin-top: 2px;height: 27px;">
                        <option value="">Select Department</option>
                </select>
    
            <select style="border:1px solid #ccc;" name="hotel_month" id="hotel_month" onChange="get_chart($('#hotel_id').val());">
                <option value="0">Select Month</option>
                <option value="1">January</option>            
                 <option value="2">February</option>            
                <option value="3">March</option>
                <option value="4">April</option>            
                 <option value="5">May</option>            
                <option value="6">June</option>
                <option value="7">July</option>            
                 <option value="8">August</option>            
                <option value="9">September</option>
                <option value="10">October</option>            
                 <option value="11">November</option>            
                <option value="12">December</option>
           </select>

        <select style="border:1px solid #ccc;" name="hotel_year" id="hotel_year" onChange="get_chart($('#hotel_id').val());">
            <option value="0">Select Year</option>
            <option value="<?php echo date('Y',strtotime('-1 Year')); ?>"><?php echo date('Y',strtotime('-1 Year')); ?></option>            
             <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>            
            <option value="<?php echo date('Y',strtotime('+1 Year')); ?>"><?php echo date('Y',strtotime('+1 Year')); ?></option>
       </select>

    <br/>
    <div id="chart_area"></div>
    </div>
     </div>
    </div>
</div>
   </div> 
    
    <br/><br/><br/>
   
    
<div class="widget-box">
    <div class="widget-header widget-header-blue widget-header-flat">
        <h4 class="lighter">Rooms Department Pickup Report</h4>
    </div>
    <div class="widget-body">
     <div class="widget-main">
        <div class="row-fluid">
            <div style="width: 100%;">   
    
    View Rooms Department Pickup Report<br/>
            <select style="border:1px solid #ccc;" name="hotel_id" id="pickup_hotel_id">
                <option>Select Hotel</option>
        <?php foreach($child_data as $chld){ ?>
             <option value="<?php echo $chld['Client']['id']; ?>" <?php if($clientId == $chld['Client']['id']){ echo "selected='selected'"; } ?>><?php echo $chld['Client']['hotelname']; ?></option>            
        <?php } ?>
           </select>
        
       
        <select style="border:1px solid #ccc;" name="hotel_month_pickup" id="hotel_month_pickup" >
            <option value="0">Select Month</option>
            <option value="<?php echo date('m',strtotime(date('m/1/Y').' -2 Month')); ?>"><?php echo date('F',strtotime(date('m/1/Y').' -2 Month')); ?></option>
            <option value="<?php echo date('m',strtotime(date('m/1/Y').' -1 Month')); ?>"><?php echo date('F',strtotime(date('m/1/Y').' -1 Month')); ?></option>
            <option value="<?php echo date('m'); ?>" selected="selected"><?php echo date('F'); ?></option>            
            <option value="<?php echo date('m',strtotime(date('m/1/Y').' +1 Month')); ?>"><?php echo date('F',strtotime(date('m/1/Y').' +1 Month')); ?></option>
            <option value="<?php echo date('m',strtotime(date('m/1/Y').' +2 Month')); ?>"><?php echo date('F',strtotime(date('m/1/Y').' +2 Month')); ?></option>
           </select>

        <select style="border:1px solid #ccc;" name="hotel_year_pickup" id="hotel_year_pickup" >
            <option value="0">Select Year</option>
            <option value="<?php echo date('Y'); ?>" selected="selected"><?php echo date('Y'); ?></option>            
            <option value="<?php echo date('Y',strtotime('+1 Year')); ?>"><?php echo date('Y',strtotime('+1 Year')); ?></option>
       </select>
        

        <select style="border:1px solid #ccc;" name="pickup" id="pickup" >
                <option value="0">Pickup Date</option>
                <?php for($day=1;$day <= 31; $day++){ ?>
                     <option value="<?php echo $day; ?>" <?php if(date('d',strtotime('-1 Day')) == $day){ echo 'selected'; } ?>><?php echo $day; ?></option>            
                <?php } ?>
        </select>


<input type="button" onClick="get_pickup_chart();" class="btn btn-info btn-small" value="GO">
        <br/>
    <div id="pickup_chart_area"></div>
    
    </div>
     </div>
    </div>
</div>
    </div>
    
    <br/><br/>
    
         <div class="widget-box">
                <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Rooms Department ADR Pickup Report</h4>
                </div>
                <div class="widget-body">
                 <div class="widget-main">
                    <div class="row-fluid">
                        <div style="width: 100%;">

            <select style="border:1px solid #ccc;" name="pickup_adr_hotel_id" id="pickup_adr_hotel_id">
                <option>Select Hotel</option>
         <?php foreach($child_data as $chld){ ?>
             <option value="<?php echo $chld['Client']['id']; ?>" <?php if($clientId == $chld['Client']['id']){ echo "selected='selected'"; } ?>><?php echo $chld['Client']['hotelname']; ?></option>            
        <?php } ?>
           </select>
       
        <select style="border:1px solid #ccc;" name="adr_hotel_month_pickup" id="adr_hotel_month_pickup" >
            <option value="0">Select Month</option>
            <option value="<?php echo date('m',strtotime(date('m/1/Y').' -2 Month')); ?>"><?php echo date('F',strtotime(date('m/1/Y').' -2 Month')); ?></option>
            <option value="<?php echo date('m',strtotime(date('m/1/Y').' -1 Month')); ?>"><?php echo date('F',strtotime(date('m/1/Y').' -1 Month')); ?></option>
            <option value="<?php echo date('m'); ?>" selected="selected"><?php echo date('F'); ?></option>            
            <option value="<?php echo date('m',strtotime(date('m/1/Y').' +1 Month')); ?>"><?php echo date('F',strtotime(date('m/1/Y').' +1 Month')); ?></option>
            <option value="<?php echo date('m',strtotime(date('m/1/Y').' +2 Month')); ?>"><?php echo date('F',strtotime(date('m/1/Y').' +2 Month')); ?></option>
           </select>

        <select style="border:1px solid #ccc;" name="adr_hotel_year_pickup" id="adr_hotel_year_pickup" >
            <option value="0">Select Year</option>
            <option value="<?php echo date('Y'); ?>" selected="selected"><?php echo date('Y'); ?></option>            
            <option value="<?php echo date('Y',strtotime('+1 Year')); ?>"><?php echo date('Y',strtotime('+1 Year')); ?></option>
       </select>

        <select style="border:1px solid #ccc;" name="adr_pickup" id="adr_pickup" >
                <option value="0">Pickup Date</option>
                <?php for($day=1;$day <= 31; $day++){ ?>
                     <option value="<?php echo $day; ?>" <?php if(date('d',strtotime('-1 Day')) == $day){ echo 'selected'; } ?>><?php echo $day; ?></option>            
                <?php } ?>
        </select>

        <input type="button" onClick="get_adr_pickup_chart();" class="btn btn-info btn-small" value="GO">
        <br/>
    <div id="adr_pickup_chart_area"></div>
    <br/><br/><br/>
</div>
         </div>
        </div>
</div>
</div>        
</div>



<script>
$(document).ready(function(){

//var default_hotel = $("#default_hotel").val();

//$('#hotel_id > :nth-child(2)').prop('selected', true);
$('#hotel_id').trigger('change');
//$('#pickup_hotel_id > :nth-child(2)').prop('selected', true);
//$('#pickup_adr_hotel_id > :nth-child(2)').prop('selected', true);

setTimeout(function() {
get_pickup_chart();
get_adr_pickup_chart();
}, 1000);

});

</script>