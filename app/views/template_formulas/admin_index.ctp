<?php ?>
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
	margin: 0 0 3px;
	padding:8px;
	background-color:#333;
	color:#fff;
	list-style: none;
}
.highlight {
  border: 4px dashed #C30F0F;

}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>
<?php
	echo $this->Html->script('jquery.validationEngine-en');
	echo $this->Html->script('jquery.validationEngine');
	echo $this->Html->css('validationEngine.jquery');
?> 

<script type="text/javascript" language="javascript">
$(document).ready(function(){ 	
	  function slideout(){
	setTimeout(function(){
		$("#response").slideUp("slow", function () {
      });
    
}, 2000);}

$("#cell_click").click(function() {

	if($("#TemplateFormula").val() != '')
	{
		if(confirm("Existing TemplateFormula will no longer be available. Do you want to proceed?")){
			$("#TemplateFormula").val("");
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

	if($("#TemplateFormula").val() != '')
	{
		if(confirm("Existing TemplateFormula will no longer be available. Do you want to proceed?")){
			$("#TemplateFormula").val("");
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
	  document.getElementById('TemplateFormula').value += get_formatted_text(txt); 
	  operator_selected = false;
	  operand_selected = true;
	  $("#column_numeric").val("");
    }
else 
{
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
	  document.getElementById('TemplateFormula').value += get_formatted_text(txt); 
	  operator_selected = false;
	  operand_selected = true;
	  $("#column_numeric_cell").val("");
    }
else 
{
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
	$(".Templates ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
			
			var order = $(this).sortable("serialize");
			var Template_id = $("#TemplateId").val();
			/*$.ajax({
			  type: "POST",
			  url: "/TemplateFormulas/updateOrder",
			  data:{order:[order], Template_id:[Template_id]},
			  success:function(rmsg){
				    $("#response").html(rmsg);
				    $("#response").slideDown('slow');
				    slideout();
				  }
			});*/
			$.post("/TemplateFormulas/updateOrder/"+Template_id, order, function(theResponse){
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
	TemplateFormulaTxt = document.getElementById('TemplateFormula').value;
	if(TemplateFormulaTxt != ""){
		if(confirm("Existing TemplateFormula will no longer be available. Do you want to proceed? ")){
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
	    document.getElementById('TemplateFormula').value = get_formatted_text(txt)+" = ";
	    result_selected = true;
	}
	else
	{
	    document.getElementById('TemplateFormula').value = "";
	    result_selected = false;
	}
	operator_selected = true;
	operand_selected = false;
}

function set_rowresult(row){
	var txt = row.options[row.selectedIndex].text;
	if(!(txt == "Select row")){
		var indexofpipe = document.getElementById('TemplateFormula').value.indexOf("|");
		if(indexofpipe >=0)
		{
			if(confirm("Existing TemplateFormula will no longer be available. Do you want to proceed? ")){
			var previous_column_val = document.getElementById('TemplateFormula').value.split('|')[0];
			var new_TemplateFormula = previous_column_val+"| "+get_formatted_text(txt)+" = ";
		//	var previous_row_val = document.getElementById('TemplateFormula').value.split('|')[1];
		//	var new_TemplateFormula = document.getElementById('TemplateFormula').value.replace(previous_row_val," "+get_formatted_text(txt)+" = ");
			document.getElementById('TemplateFormula').value = new_TemplateFormula;
			}else{
				return false;
			}

		}
		else
		{
			document.getElementById('TemplateFormula').value += " | "+get_formatted_text(txt)+" = ";
		}
	    
	    row_selected = true;
	}
	else
	{
	    document.getElementById('TemplateFormula').value = "";
	    row_selected = false;
	}

	operator_selected = true;
	operand_selected = false;
}


function add_result_column1(column){
	TemplateFormulaTxt = document.getElementById('TemplateFormula').value;
	if(TemplateFormulaTxt != ""){
		if(confirm("Existing TemplateFormula will no longer be available. Do you want to proceed? ")){
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
	    document.getElementById('TemplateFormula').value = get_formatted_text(txt);
	    result_selected = true;
	}
	else
	{
	    document.getElementById('TemplateFormula').value = "";
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
		document.getElementById('TemplateFormula').value += get_formatted_text(txt);
		operand_selected = true;
		operator_selected = false;
	}
}

function add_operand_column1(column){
	if(!result_selected){
		alert("Please select result column first");
		return false;
	}
//	if(document.getElementById('Rows').value == "")
//	{
//		alert("Please select result row also");
//		return false;
//	}

	if(!operand_selected){
		var txt = column.options[column.selectedIndex].text;
		document.getElementById('TemplateFormula').value += "["+get_formatted_text(txt);
		operand_selected = true;
		operator_selected = false;
	}
}

function add_operand_row(row){
	if(!result_selected){
		alert("Please select result column first");
		return false;
	}
//	if(document.getElementById('Rows').value == "")
//	{
//		alert("Please select result row also");
//		return false;
//	}
	var last_character = document.getElementById('TemplateFormula').value.charAt(document.getElementById('TemplateFormula').value.length-1);
	var lastoperator = $("#TemplateFormula").val().substr($("#TemplateFormula").val().length - 2);

	if(lastoperator == '+ ' || lastoperator == '* ' || lastoperator == '/ ' || lastoperator == '- ' || lastoperator == '= '
|| lastoperator == '( ' || lastoperator == ') '){
		return false;
	}

	if(last_character != ']'){
		var txt = row.options[row.selectedIndex].text;
		document.getElementById('TemplateFormula').value += " | "+get_formatted_text(txt)+"]";
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

// 	if(arr_operator[operator.value] == " ( "){
// 
// 		//((operator_selected && (arr_valid_operator.indexOf(last_operator) > -1)))
// 		// alert(operator_selected);
// 		// alert(last_operator +" -- "+arr_valid_operator.indexOf(last_operator));
// 		if(!operator_selected || ((operator_selected && (arr_valid_operator.indexOf(last_operator) == -1)))){
// 			alert("Can not add this operator in TemplateFormula now.");
// 			return false;
// 		}else{
// 			document.getElementById('TemplateFormula').value += arr_operator[operator.value];
// 			last_operator = arr_operator[operator.value];
// 			operator_selected = true;
// 			operand_selected = false;
// 		}
// 	}
	
	if(arr_operator[operator.value] == " ( " || last_operator == " ) " || !operator_selected){
		document.getElementById('TemplateFormula').value += arr_operator[operator.value];
		last_operator = arr_operator[operator.value];
		operator_selected = true;
		operand_selected = false;
	}
	
}

function check_TemplateFormula(){
	TemplateFormula = document.getElementById('TemplateFormula').value;
	is_correct = true;
	if(operator_selected && last_operator != " ) "){
		is_correct = false;
	}

	if(operator_selected && last_operator != " ) "){
		is_correct = false;
	}
	
	openBracesCount = (TemplateFormula.split("(").length - 1);
	closeBracesCount = (TemplateFormula.split(")").length - 1);

	//alert(openBracesCount+" --- "+closeBracesCount);

	if(openBracesCount != closeBracesCount){
		alert("Count of opening and closing braces mismatched.")
		is_correct = false;
	}

	if(!is_correct){
		alert("Invalid TemplateFormula.");
		return false;
	}else{
		return true;
	}
}


function setPrimary(s_id,c_id,r_id)
{
  $.ajax({
    type: "POST",
    url: "/TemplateFormulas/updatePrimary",
    data: {Templateid:[s_id], columnid:[c_id], rowid:[r_id]},
    success: function(rmsg){
		alert("Primary Column updated successfully !");
      }
  });
}

function reset_TemplateFormula()
{
	if(confirm("Existing TemplateFormula will no longer be available. Do you want to proceed? ")){
		$("#TemplateFormula").val('');
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

<div class="Templates index">
<table>
<tr id="response"><td>updated</td></tr>
<?php
if(empty($all_TemplateFormulas)){
?>
    <th>No Previous TemplateFormulas saved</th>
<?php
}
else{

?>
    <th>Previous TemplateFormulas</th>
<?
}
?>
<tr>
<td>
<ul>
<?php
//echo '<pre>'; print_r($all_TemplateFormulas); echo "</pre>";

foreach($all_TemplateFormulas as $key=>$value)
{
      if($key != "type"){
		 $devide_and_rule = explode('_',$key);
      ?>
	    <li id="arrayorder_<?=$devide_and_rule[0]."#".$devide_and_rule[1]; ?>">
		<?=$value; ?>	  
		<input type="radio" name="setPrimary" onclick="setPrimary(<?=$TemplateId;?>,<?=$devide_and_rule[0];?>,<?=$devide_and_rule[1]; ?>)" value="<?=$key; ?>"
<?php if(isset($all_TemplateFormulas['type']) && !empty($all_TemplateFormulas['type']) && $key == $all_TemplateFormulas['type']){ echo "checked"; }?>/>
		  <a style="color: #fff; float:right;" href="javascript:void(0)" onclick="return removeTemplateFormula(<?=$TemplateFormula_ids[$key]; ?>)">Remove</a>


	    </li>
      <?php
	}
}
?>
</ul>
</td>
</tr>
</table>
<?php //echo $this->Form->create('TemplateFormula', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'TemplateFormulas', 'action' => 'create_TemplateFormula', $TemplateId))); 

echo $this->Form->create('TemplateFormula' , array('action' => 'add_TemplateFormula', "onSubmit"=>"return check_TemplateFormula()"));
?>
<table width="100%">
<tr>
	<td width="50%">
	<input type="radio" id="column_click" value="result_column" name="type_checked" checked="checked"/>&nbsp;Result Column
	</td>
	<td width="50%">
	<input type="radio" id="cell_click" name="type_checked" value="result_column"/>&nbsp;Result Cell
	</td>
</tr>

<tr>
	<td>
    <div id="column_area">        

        <?php echo $this->Form->input('MarketSegment.id', array('type'=>'select','empty'=>'Select Market Segment', 'options' => $marketsegments, 'div' => false, 'label' => false)); ?>

	<?php echo $this->Form->input('Column', array('type'=>'select','empty'=>'Select column', 'options' => $columns, 'div' => false, 'label' => false, 'onChange' => 'add_result_column(this)')); ?>

    </div>
	</td>
<td>
<div id ="add_value">
<input type="text" id="column_numeric" style="float: left; width: 80px; border: 1px solid #333;"/>&nbsp;
<input type="button" id="but_numeric" value="Add Value" style="width: 100px;" />
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

<table width="20%" id="operators_op">
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
				echo $this->Form->button($operators[$operatorindx], array('type'=>'button', 'div' => false, 'value' => $operatorindx, 'onclick' => 'add_operator(this)'));
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

<table width="100%" style="background:none">
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
		//echo $this->Form->create('TemplateFormula' , array('action' => 'add_TemplateFormula', "onSubmit"=>"return check_TemplateFormula()"));
		echo $this->Form->input('Template.id', array('value' => $TemplateId));
		echo $this->Form->input('TemplateFormula', array('type'=>'text', 'div' => false, 'label' => 'TemplateFormula', 'class'=>'input','readonly'=>'readonly'));
?>

<span>
<?php
echo $this->Form->submit(__('Submit', true), array('div' => false));
echo $this->Form->end();?>
<a href="javascript:void(0)" onclick="return reset_TemplateFormula()" class="new_button"/>Reset</a>
</span>
</div>
<?php echo $this->element('admin_left_menu'); ?>
<script>
function removeTemplateFormula(id){
 var res = confirm("Are you sure you want to remove TemplateFormula ?");
  if(res == true){
  window.location.href="/admin/TemplateFormulas/remove/"+id;
  }
else{
  return false;
}
}
</script>