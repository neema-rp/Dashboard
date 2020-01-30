<?php
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />

<script src="/js/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="/css/chosen.css" />

<style>
.result_column{
    clear: both;
    font-size: 13px;
    height: 115px;
    padding: 2px;
    vertical-align: text-bottom;
    width:20%;
}
.tr_operator{
    width:20px;
}
.AdvancedSheets li{
list-style-type: none;
}
.AdvancedSheets ul {
	padding:0px;
	margin: 0px;
}
.AdvancedSheets #response {
	padding:10px;
	background-color:#9F9;
	border:2px solid #396;
	margin-bottom:20px;
}
.AdvancedSheets li {
	margin: 0 auto 3px;
	padding:8px;
	background-color:#333;
	color:#fff;
	list-style: none;
	width: 700px;
}
.admin_left_pannel {
    float: left;
    width: 17%;
}
</style>
<script>
$(document).ready(function(){
    $("#AdvancedSheetAdminEditForm").validationEngine();
    });
</script>
<script type="text/javascript" language="javascript">
$(document).ready(function(){ 	
	  function slideout(){
	setTimeout(function(){
		$("#response").slideUp("slow", function () {
      });
    
}, 2000);}

$("#cell_click").click(function() {
	if($("#Formula").val() != '')
	{
		if(confirm("Existing formula will no longer be available. Do you want to proceed?")){
			$("#Formula").val("");
			$("#Column").val("");
		}else{
			return false;
		}
	}

	$("#cell_area").show();
	$("#cell_area1").show();
	$("#column_area").hide();
	$("#column_area1").hide();
});
$("#column_click").click(function() {
	if($("#Formula").val() != '')
	{
		if(confirm("Existing formula will no longer be available. Do you want to proceed?")){
			$("#Formula").val("");
			$("#Column").val("");
			$("#Rows").val("");
		}else{
			return false;
		}
	}

	$("#cell_area").hide();
	$("#cell_area1").hide();
	$("#column_area").show();
	$("#column_area1").show();
});

    $("#response").hide();
	$(function() {
	$(".AdvancedSheets ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
			
			var order = $(this).sortable("serialize");
			var AdvancedSheet_id = $("#AdvancedSheetId").val();
			
			$.post("/AdvancedSheets/updateOrder/"+AdvancedSheet_id, order, function(theResponse){
				$("#response").html(theResponse);
				$("#response").slideDown('slow');
				slideout();
			}); 	
				
		}
		});
	});
        
         $(".chzn-select").chosen();
});	

</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Edit Advanced Sheet</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Advanced Sheet</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                        <div class="row-fluid">
                            <?php echo $this->Form->create('AdvancedSheet');
                            echo $this->Form->input('AdvancedSheet.id', array('value' => $data['AdvancedSheet']['id']));
                            echo $this->Form->input('name',array('value' => $data['AdvancedSheet']['name'],'class'=>'validate[required] span6'));
                            echo $this->Form->input('Column', array('div' => false,'name'=>'data[Column][Column]', 'label' => 'Select Columns for Template','value'=>$selected_columns,'class'=>'validate[required] chzn-select span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                            echo $this->Form->input('Column', array('multiple' => "multiple",'name'=>'data[Row][Row]','options'=>$columns,'value'=>$selected_rows,'id'=>'RowRow','class'=>'chzn-select span6', 'div' => false, 'label' => 'Select Result Column','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));

                            echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                            echo $this->Form->end();?>	
                        </div>
                     </div>
                </div>
        </div>
</div>
