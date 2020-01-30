<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />

<style>
.result_column{ clear: both;font-size: 13px;height: 115px;padding: 2px;vertical-align: text-bottom;width:20%; }
.tr_operator{ width:20px; }
.sheets li{ list-style-type: none; }
.sheets ul { padding:0px;margin: 0px; }
.sheets #response { padding:10px;background-color:#9F9;border:2px solid #396;margin-bottom:20px; }
.sheets li { margin: 0 0 3px;padding:8px;background-color:#333;color:#fff;list-style: none; }
.highlight { border: 4px dashed #C30F0F; }
</style>
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
	$("#add_value_cell").show();
	$("#column_area").hide();
	$("#column_area1").hide();
	$("#add_value").hide();
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
	$("#add_value_cell").hide();
	$("#column_area").show();
	$("#column_area1").show();
	$("#add_value").show();
});
//by raman to add numeric values .....
$("#but_numeric").click(function(){
    if(!result_selected){
            alert("Please select result column first");
            return false;
    }
    if(!operand_selected){
	  var txt = $("#column_numeric").val();
          if(txt == '' || txt == "" || isNaN(txt)){
            alert("cannot enter empty or non-numeric value");
            return false;
          }
	  document.getElementById('Formula').value += get_formatted_text(txt); 
	  operator_selected = false;
	  operand_selected = true;
	  $("#column_numeric").val("");
    } else {
      alert("Please select operator");    
	$('#operators_op').addClass('highlight');
	setTimeout(
	    function() { $('#operators_op').removeClass('highlight'); },
	    3000
	);
    }
});


//by raman to add numeric values .....
$("#but_numeric_cell").click(function(){
	if(!result_selected){
		alert("Please select result column first");
		return false;
	}
	if(!row_selected){
		alert("Please select result row first");
		return false;
	}
    if(!operand_selected){
	var txt = $("#column_numeric_cell").val();
        if(txt == '' || txt == "" || isNaN(txt)){
          alert("cannot enter empty or non-numeric value");
          return false;
        }
	document.getElementById('Formula').value += get_formatted_text(txt); 
	operator_selected = false;
	operand_selected = true;
	$("#column_numeric_cell").val("");
    }else{
      alert("Please select operator");    
	$('#operators_op').addClass('highlight');
	setTimeout(
	    function() { $('#operators_op').removeClass('highlight'); },
	    3000
	);
    }
});

    $("#response").hide();
	$(function() {
	$(".sheets ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
			
			var order = $(this).sortable("serialize");
			var sheet_id = $("#SheetId").val();
			/*$.ajax({
			  type: "POST",
			  url: "/formulas/updateOrder",
			  data:{order:[order], sheet_id:[sheet_id]},
			  success:function(rmsg){
				    $("#response").html(rmsg);
				    $("#response").slideDown('slow');
				    slideout();
				  }
			});*/
			$.post("/formulas/updateOrder/"+sheet_id, order, function(theResponse){
				$("#response").html(theResponse);
				$("#response").slideDown('slow');
				slideout();
			}); 	
				/*$("#response").slideDown('slow');
				slideout();*/ 
		}
		});
	});

});	


//some important flags
var operator_selected = false;
var operand_selected = false;
var result_selected = false;
var row_selected = false;
var equal_clicked = false;
var last_operator = "";
var arr_operator = new Array(" + ", " - ", " * ", " / ", " ( ", " ) ");
var arr_valid_operator = new Array(" + ", " - ", " * ", " / ", " ( ");

function add_result_column(column){
	formulaTxt = document.getElementById('Formula').value;
	if(formulaTxt != ""){
		if(confirm("Existing formula will no longer be available. Do you want to proceed? ")){
			set_result(column)
		}else{
			return false;
		}
	}else{
		set_result(column)
	}
}


function add_result_row(row){
	if(!result_selected){
		alert("Please select result column first");
		document.getElementById('Rows').value = "";
		return false;
	}else
	{
		set_rowresult(row);
	}
}


function get_formatted_text(txt){
	return txt.split(" ").join("_");
}

function set_result(column){
	var txt = column.options[column.selectedIndex].text;
// 	alert(column.value+" -- "+txt);
	if(!(txt == "Select column")){
	    document.getElementById('Formula').value = get_formatted_text(txt)+" = ";
	    result_selected = true;
	}
	else
	{
	    document.getElementById('Formula').value = "";
	    result_selected = false;
	}
	operator_selected = true;
	operand_selected = false;
}

function set_rowresult(row){
	var txt = row.options[row.selectedIndex].text;
	if(!(txt == "Select row")){
		var indexofpipe = document.getElementById('Formula').value.indexOf("|");
		if(indexofpipe >=0)
		{
			if(confirm("Existing formula will no longer be available. Do you want to proceed? ")){
			var previous_column_val = document.getElementById('Formula').value.split('|')[0];
			var new_formula = previous_column_val+"| "+get_formatted_text(txt)+" = ";
		//	var previous_row_val = document.getElementById('Formula').value.split('|')[1];
		//	var new_formula = document.getElementById('Formula').value.replace(previous_row_val," "+get_formatted_text(txt)+" = ");
			document.getElementById('Formula').value = new_formula;
			}else{
				return false;
			}

		}
		else
		{
			document.getElementById('Formula').value += " | "+get_formatted_text(txt)+" = ";
		}
	    
	    row_selected = true;
	}
	else
	{
	    document.getElementById('Formula').value = "";
	    row_selected = false;
	}

	operator_selected = true;
	operand_selected = false;
}


function add_result_column1(column){
	formulaTxt = document.getElementById('Formula').value;
	if(formulaTxt != ""){
		if(confirm("Existing formula will no longer be available. Do you want to proceed? ")){
			document.getElementById('Rows').value = "";
			set_columnresult(column)
		}else{
			return false;
		}
	}else{
		set_columnresult(column)
	}
}


function set_columnresult(column){
	var txt = column.options[column.selectedIndex].text;
// 	alert(column.value+" -- "+txt);
	if(!(txt == "Select column")){
	    document.getElementById('Formula').value = get_formatted_text(txt);
	    result_selected = true;
	}
	else
	{
	    document.getElementById('Formula').value = "";
	    result_selected = false;
	}

	operator_selected = true;
	operand_selected = false;
}

function add_operand_column(column){
	if(!result_selected){
		alert("Please select result column first");
		return false;
	}
	if(!operand_selected){
		var txt = column.options[column.selectedIndex].text;
		//alert(column.value+" -- "+txt);
		document.getElementById('Formula').value += get_formatted_text(txt);
		operand_selected = true;
		operator_selected = false;
	}
}

function add_operand_column1(column){
	if(!result_selected){
		alert("Please select result column first");
		return false;
	}
	if(document.getElementById('Rows').value == "")
	{
		alert("Please select result row also");
		return false;
	}

	if(!operand_selected){
		var txt = column.options[column.selectedIndex].text;
		document.getElementById('Formula').value += "["+get_formatted_text(txt);
		operand_selected = true;
		operator_selected = false;
	}
}

function add_operand_row(row){
	if(!result_selected){
		alert("Please select result column first");
		return false;
	}
	if(document.getElementById('Rows').value == "")
	{
		alert("Please select result row also");
		return false;
	}
	var last_character = document.getElementById('Formula').value.charAt(document.getElementById('Formula').value.length-1);
	var lastoperator = $("#Formula").val().substr($("#Formula").val().length - 2);

	if(lastoperator == '+ ' || lastoperator == '* ' || lastoperator == '/ ' || lastoperator == '- ' || lastoperator == '= '
|| lastoperator == '( ' || lastoperator == ') '){
		return false;
	}

	if(last_character != ']'){
		var txt = row.options[row.selectedIndex].text;
		document.getElementById('Formula').value += " | "+get_formatted_text(txt)+"]";
		operand_selected = true;
		operator_selected = false;
	}
	
}


function add_operator(operator){
	//alert(operator.value);
	if(!result_selected){
		alert("Please select result column first");
		return false;
	}

	if(arr_operator[operator.value] == " ( " || last_operator == " ) " || !operator_selected){
		document.getElementById('Formula').value += arr_operator[operator.value];
		last_operator = arr_operator[operator.value];
		operator_selected = true;
		operand_selected = false;
	}
	
}

function check_formula(){
	formula = document.getElementById('Formula').value;
	is_correct = true;
	if(operator_selected && last_operator != " ) "){
		is_correct = false;
	}

	if(operator_selected && last_operator != " ) "){
		is_correct = false;
	}
	
	openBracesCount = (formula.split("(").length - 1);
	closeBracesCount = (formula.split(")").length - 1);

	//alert(openBracesCount+" --- "+closeBracesCount);

	if(openBracesCount != closeBracesCount){
		alert("Count of opening and closing braces mismatched.")
		is_correct = false;
	}

	if(!is_correct){
		alert("Invalid formula.");
		return false;
	}else{
		return true;
	}
}


function setPrimary(s_id,c_id,r_id)
{
  $.ajax({
    type: "POST",
    url: "/formulas/updatePrimary",
    data: {sheetid:[s_id], columnid:[c_id], rowid:[r_id]},
    success: function(rmsg){
		alert("Primary Column updated successfully !");
      }
  });
}

function reset_formula()
{
	if(confirm("Existing formula will no longer be available. Do you want to proceed? ")){
		$("#Formula").val('');
		var selObj =document.getElementsByName('data[Column]');
		var rows = document.getElementById('Rows');
for(i in selObj)
{
  selObj[i].selectedIndex = "";
}

		rows.selectedIndex = "";
	}else{
		return false;
	}
}


</script>

<?php echo $this->Session->flash(); ?>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i>Webform Formula</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Manage Formula</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                        <div class="row-fluid sheets">
<table class="table table-striped table-bordered table-hover">
    
<tr id="response"><td>updated</td></tr>
<?php
if(empty($all_formulas)){
?>
    <th>No Previous Formulas saved</th>
<?php } else{ ?>
    <th>Previous Formulas</th>
<?php } ?>
<tr>
<td>
<ul>
<?php

foreach($all_formulas as $key=>$value)
{      
      if($key != "type"){
		 $devide_and_rule = explode('_',$key);
      ?>
	    <li id="arrayorder_<?=$devide_and_rule[0]."#".$devide_and_rule[1]; ?>">
                    <input type="radio" name="setPrimary" onclick="setPrimary(<?=$sheetId;?>,<?=$devide_and_rule[0];?>,<?=$devide_and_rule[1]; ?>)" value="<?=$key; ?>"
                    <?php if(isset($all_formulas['type']) && !empty($all_formulas['type']) && $key == $all_formulas['type']){ echo "checked"; }?>/>
                    &nbsp;&nbsp;&nbsp;&nbsp;
		<?=$value; ?>	  
		  <a style="color: #fff; float:right;" href="javascript:void(0)" onclick="return removeFormula(<?=$formula_ids[$key]; ?>)">Remove</a>
	    </li>
      <?php
	}
}
?>
</ul>
</td>
</tr>
</table>
<?php //echo $this->Form->create('Formula', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'formulas', 'action' => 'create_formula', $sheetId))); ?>
<table class="table table-striped table-bordered table-hover">
<tr>
	<td width="50%">
	<input type="radio" id="column_click" value="result_column" name="type_checked" checked="checked"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Result Column
	</td>
	<td width="50%">
	<input type="radio" id="cell_click" name="type_checked" value="result_column"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Result Cell
	</td>
</tr>

<tr>
	<td>
    <div id="column_area">
	<?php echo $this->Form->input('Column', array('type'=>'select','empty'=>'Select column', 'options' => $columns, 'div' => false, 'label' => false, 'onChange' => 'add_result_column(this)')); ?>

	</div>
	</td>
<td>
<div id ="add_value">
<input type="text" id="column_numeric" style="float: left; width: 80px; border: 1px solid #333;"/>&nbsp;
<input type="button" id="but_numeric" value="Add Value" class="btn btn-small btn-danger" style="width: 100px;" />
</div>
&nbsp;</td>
	<td style="padding: 0;">
	<div id="cell_area" style="display:none">
	<?php echo $this->Form->input('Column', array('type'=>'select','empty'=>'Select column', 'options' => $columns, 'div' => 	false, 'label' => false, 'onChange' => 'add_result_column1(this)' , 'style'=>'width:183px; border: 1px solid #333; margin: 5px; padding: 2px;')); ?>
	<?php echo $this->Form->input('Rows', array('type'=>'select','empty'=>'Select row', 'options' => $rows, 'div' => 	false, 'label' => false, 'onChange' => 'add_result_row(this)','style'=>'width:183px; border: 1px solid #333; margin: 5px; padding: 2px;')); ?>
	</div>

	<div id ="add_value_cell" style="display:none">
		<input type="text" id="column_numeric_cell" style="float: left; width: 80px; border: 1px solid #333;"/>&nbsp;
		<input type="button" id="but_numeric_cell" value="Add Value" style="width: 100px;" />
	</div>

	</td>
</tr>
</table>

<table width="20%" id="operators_op" >
<?php
	$operatorindx = 0;
	$trcount = (count($operators)% 2 == 0)? (count($operators)/ 2) : ((count($operators)/ 2)+1);
	for($trindx = 1; $trindx<=$trcount; $trindx++){
?>
		<tr>
<?php
		for($tdindx=1; $tdindx <= 2; $tdindx++){
?>
			<td>
<?php			if(isset($operators[$operatorindx])){
				echo $this->Form->button($operators[$operatorindx], array('type'=>'button', 'div' => false, 'value' => $operatorindx,"class"=>"btn btn-warning btn-mini", 'onclick' => 'add_operator(this)'));
			}
?>
			</td>
<?php
			$operatorindx++;
		}
?>
		</tr>
	<?php }	 ?>
</table>

<table width="100%" style="background:none" class="table table-striped table-bordered table-hover">
<tr>
<td style="background:none" width="50%">
	<div id="column_area1">
	<?php echo $this->Form->input('Column', array('type'=>'select', 'multiple'=>true, 'options' => $unlock_columns, 'div' => false, 'label' => 'Operand Column:', 'class'=>'result_column', 'ondblclick'=>'add_operand_column(this)'));?>
	</div>
</td>
<td style="background:none" width="50%">
	<div id="cell_area1" style="display:none">
	<?php echo $this->Form->input('Column', array('type'=>'select', 'multiple'=>true, 'options' => $unlock_columns, 'div' => false, 'label' => 'Operand Cell:', 'class'=>'result_column', 'ondblclick'=>'add_operand_column1(this)', 'style'=>'width:153px'));?>
	<?php echo $this->Form->input('Rows', array('type'=>'select','multiple'=>true, 'options' => $bottom_rows, 'div' => false, 'label' => false, 'ondblclick'=>'add_operand_row(this)', 'style'=>'width:153px')); ?>
	</div>
</td>
</tr>
</table>
<?php 
		echo $this->Form->create('Formula' , array('action' => 'add_formula', "onSubmit"=>"return check_formula()"));
		echo $this->Form->input('Sheet.id', array('value' => $sheetId));
		echo $this->Form->input('Formula', array('type'=>'text', 'div' => false, 'label' => 'Formula', 'class'=>'input','readonly'=>'readonly','style'=>'width:80%;'));
?>
<input type="hidden" name="data[Department][id]" value="<?=$this->params['pass'][1] ; ?>"/>


<span>
<?php
echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
echo "&nbsp;&nbsp;";
echo $this->Form->end();?>
<a href="javascript:void(0)" onclick="return reset_formula()" class="btn btn-success"/>Reset</a>
</span>
</div></div></div></div></div>
                         
                         
                         
                         
<script>
function removeFormula(id){
 var res = confirm("Are you sure you want to remove Formula ?");
  if(res == true){
  window.location.href="/admin/formulas/remove/"+id;
  }
else{
  return false;
}
}
</script>