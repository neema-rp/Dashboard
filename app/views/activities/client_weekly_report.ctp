<?php ?>

<script>
function view_report(client_id){
    var report_link = '<a href="/client/sheets/weekly_report/'+client_id+'" target="_blank"><button class="btn btn-info">View Report<i class="icon-print  bigger-125 icon-on-right"></i></button></a>';
    $('#report_link_div').html(report_link);
}
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotel <small><i class="icon-double-angle-right"></i> Weekly Report</small></h1>
        </div><!--/.page-header-->
    
        <div class="widget-box">
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
                                 <option value="<?php echo $chld['Client']['id']; ?>"><?php echo $chld['Client']['hotelname']; ?></option>            
                            <?php } ?>
                           </select>
                            <div id="report_link_div" style="font-weight:bold;text-align:center;"></div>
                             </div>
                    </div>
                 </div>
                </div>
        </div>

        <br/><br/>
</div>

<script>
$(document).ready(function(){
$('#weekly_report_hotel > :nth-child(2)').prop('selected', true);
$('#weekly_report_hotel').trigger('change');
});
</script>