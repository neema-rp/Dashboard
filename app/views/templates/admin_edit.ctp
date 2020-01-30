<?php ?>
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

.Templates li{
list-style-type: none;
}

.Templates ul {
	padding:0px;
	margin: 0px;
}
.Templates #response {
	padding:10px;
	background-color:#9F9;
	border:2px solid #396;
	margin-bottom:20px;
}
.Templates li {
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
    $("#TemplateAdminEditForm").validationEngine();
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
	$(".Templates ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
			
			var order = $(this).sortable("serialize");
			var Template_id = $("#TemplateId").val();
			
			$.post("/Templates/updateOrder/"+Template_id, order, function(theResponse){
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
                <h1>Admin <small><i class="icon-double-angle-right"></i> Edit Template</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Template</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                        <div class="row-fluid">

        <?php echo $this->Form->create('Template');?>
	
	<?php echo $this->Form->input('id',array('class'=>'validate[required] text-input'));
        echo $this->Form->input('name',array('class'=>'validate[required] text-input'));
        echo $this->Form->input('MarketSegment', array('multiple' => "multiple",'name'=>'data[MarketSegment][MarketSegment]','div' => false,'value'=>$selected_marketsegments, 'label' => 'Select MarketSegment','class'=>'validate[required] chzn-select span6','options'=>$marketsegments,'label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
        echo $this->Form->input('Column', array('div' => false,'value'=>$selected_columns,'class'=>'validate[required] chzn-select span6','label'=>array('class'=>'control-label','text' => 'Select Columns for Template'),'div'=>array('class'=>'control-group')));
        echo $this->Form->input('Column', array('multiple' => "multiple",'name'=>'data[Row][Row]','value'=>$selected_rows,'id'=>'RowRow','class'=>'chzn-select span6', 'div' => false,'label'=>array('class'=>'control-label','text' => 'Select Result Column'),'div'=>array('class'=>'control-group')));
        //echo $this->Form->input('Locked', array('multiple' => "multiple",'name'=>'data[Row][Locked]','value'=>$rowlocked,'id'=>'RowLocked','class'=>'chzn-select span6', 'div' => false, 'label' => 'Select Locked Result Column','options'=>$columns,'label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
        ?>
 
         <div class="control-group">
        <label for="TemplateMonthMonth" class="control-label">Locked Result Column for Template</label>
	<table cellspacing="6" cellpadding="6">
        <tr><th>Row Column</th><th>Locked</th></tr>
	<?php
		$indx = 0;
		foreach($columns as $key => $value){ 
                    if(in_array($key, $selected_rows)){
                ?>
		<tr>
                    <td><?php echo $value; ?></td>
                        <td>
				<?php
				$lockedid = "RowLocked".$key;
				$pos = array_search($key, $row_id);
				$is_locked = (is_int($pos))? $rowlocked[$pos] : '0';

				if($is_locked == '1'){
					echo $this->Form->checkbox($lockedid, array('value' => $key, 'checked' => true, 'name'=>'data[Row][Locked][]'));
				}else{
					echo $this->Form->checkbox($lockedid, array('value' => $key, 'name'=>'data[Row][Locked][]'));
				}
				?>
			</td>
		</tr>
	<?php $indx += 1; } } ?>

	</table>
        </div>
        
        <?php
        echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
        echo "&nbsp;&nbsp;";
        echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'Templates', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
        echo $this->Form->end();
        ?>

</div>
</div></div></div></div>

<style>
input[type=checkbox], input[type=radio]{
    margin-top:-6px;
}
</style>