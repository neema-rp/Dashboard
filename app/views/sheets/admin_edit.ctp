<?php
?>

<style>
.result_column{ clear: both; font-size: 13px;height: 115px; padding: 2px; vertical-align: text-bottom;width:20%; }
.tr_operator{ width:20px; }
.sheets li{ list-style-type: none; }
.sheets ul { padding:0px; margin: 0px; }
.sheets #response { padding:10px; background-color:#9F9; border:2px solid #396;margin-bottom:20px; }
.sheets li { margin: 0 auto 3px; padding:8px; background-color:#333; color:#fff; list-style: none; width: 700px; }
input[type=checkbox], input[type=radio]{ position:relative; }
</style>
<script>
$(document).ready(function(){
    $("#SheetAdminEditForm").validationEngine();
    });
</script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>
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
	$(".sheets ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
			
			var order = $(this).sortable("serialize");
			var sheet_id = $("#SheetId").val();
			
			$.post("/sheets/updateOrder/"+sheet_id, order, function(theResponse){
				$("#response").html(theResponse);
				$("#response").slideDown('slow');
				slideout();
			}); 	
				
		}
		});
	});
	
	
	$('#SheetIsEmail').click(function() {
		if($(this).is(':checked')){
			$('#email').show();
			$('#SheetEmail').addClass('validate[required,custom[email]]');
		}else{
			$('#email').hide();
			$('#SheetEmail').val('').removeClass('validate[required,custom[email]]');
			$('.SheetEmailformError').remove();
		}	
	});

});	

</script>

<script>
function add_location(add_id)
{
        var num = parseInt($("strong:last").text());
        var valid = get_validate(num);
        if(valid){
		alert('Please enter email address');
        } else {
                var newNum = new Number(num + 1);
                var newElem = $('#email' + 0).clone().attr('id', 'email' + newNum);
                $('#email' + num).after(newElem);
		$('#email' + newNum+'>label').html('').width('132px');
		$('#email' + newNum+'>label').before('<br/>');
                $('#email' + newNum+'>input').attr('name', 'data[EmailSheet]['+newNum+'][email]').attr('value', '').attr('id', 'EmailSheet'+newNum+'email');
                $('#email' + num+'>a:eq(0)').hide();                              
                $('#email' + newNum+'>a').attr('onclick','add_location('+newNum+')').show();
                $('#email' + newNum+'>strong').remove();
		$('#email' + newNum+'>a').after('<strong style="display:none;">'+newNum+'</strong>');
                $('#email' + newNum+'>a').after('<a onclick="delete_location('+newNum+');" href="javascript:void(0);" class="new_button">Delete</a>');
        }
}

function delete_location(remove_id){
    $('.EmailSheet'+remove_id+'emailformError').remove();
    $("#email"+remove_id).remove();
    var num = parseInt($("strong:last").text());
    $('#email' + num+'>a:eq(0)').show();
}

function get_validate(num){
    if($('#email' + num+'>input').val() == ''){
            return true;
    } else {
            return false;		
    }
}
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __($department .':'); ?> <small><i class="icon-double-angle-right"></i>  Email Notification</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter"> Email Notification</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

<?php echo $this->Form->create('Sheet');?>
	<div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Email Notification : </label>&nbsp;&nbsp;&nbsp;
		<?php
			echo $this->Form->input('Sheet.is_email', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:super;')).'&nbsp;&nbsp;&nbsp;';
		?>
		<input type="hidden" name="data[Sheet][department_id]" value="<?=$this->params['pass'][1]; ?>"/>
	</div>
	
	<?php 
	$display_prop = (isset($this->data['Sheet']['is_email']) && ($this->data['Sheet']['is_email'] == 1)) ? '':'style="display:none;"';
	?>
	<div class="input text" id="email" <?php echo $display_prop; ?>>
	
		<?php if(isset($this->data['EmailSheet']) && (count($this->data['EmailSheet']) > 0)){ 
			foreach($this->data['EmailSheet'] as $key=>$EmailSheet){
			if($key!=0){ echo '<br/>'; }
                	?>
			<div id="email<?php echo $key; ?>">	
			<?php if($key == 0){ ?>
			<label for="SheetImportImport" style="float:left;color:#5F5F5F;"><b>Email Address *: </b></label>&nbsp;&nbsp;&nbsp;		
			<?php } else {?>
			<label for="SheetImportImport" style="float:left;color:#5F5F5F;width:132px;">&nbsp;</label>&nbsp;&nbsp;&nbsp;	
			<?php } ?>
			<?php							
				echo $this->Form->input('EmailSheet.'.$key.'.email',array('div'=>false,'label'=>false,'style'=>'width:400px;','class'=>'validate[required,custom[email]]')); 
			?>
			
			
			<?php if($key == (count($this->data['EmailSheet']) -1)){?>
			<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_location(<?php echo $key; ?>)">Add More</a>
			<?php } else { ?>
			<a style="padding-bottom:5px;*line-height: 28px;display:none;" class="new_button" href="javascript:void(0);"  onClick="add_location(<?php echo $key; ?>)">Add More</a>
			<?php } ?>
			
                        &nbsp;
			<?php if($key != 0){?>
			<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="delete_location(<?php echo $key; ?>)">Delete</a>
			<?php } ?>
			<strong style="display:none;"><?php echo $key; ?></strong>
		</div>
		
		<?php } } else { ?>
			<div id="email0">	
				<label for="SheetImportImport" style="float:left;color:#5F5F5F;"><b>Email Address *: </b></label>&nbsp;&nbsp;&nbsp;
				<?php							
					echo $this->Form->input('EmailSheet.0.email',array('div'=>false,'label'=>false,'style'=>'width:400px;','class'=>'validate[required,custom[email]]')); 
				?>
                                &nbsp;
				<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_location(0)">Add More</a>
				<strong style="display:none;">0</strong>
			</div>
		<?php } ?>
	</div>
	
	<br/><br/> 	
	
	<?php
            echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
            echo "&nbsp;&nbsp;";
            echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'index',$userId,$this->params['pass'][1]), array('class' => 'btn btn-success', 'escape' => false));
            echo $this->Form->end();
        ?>

                    </div></div></div></div>
</div>



    

    <div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __($department .':'); ?> <small><i class="icon-double-angle-right"></i>  Edit Department Sheet</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter"> Edit Department Sheet</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

<?php echo $this->Form->create('Sheet');?>
	
	<?php echo $this->Form->input('name',array('class'=>'validate[required] text-input')); ?>

	<div class="input text required">
		<label for="SheetMonthMonth">Month</label>
		<?php
			$selectMonth = isset($this->data['Sheet']['departmentmonth']['month']) ? $this->data['Sheet']['departmentmonth']['month'] : $this->data['Sheet']['month'];
			echo $this->Form->month('Sheet.departmentmonth', null, array('empty' => 'Select Month', 'value' => $selectMonth,'class'=>'validate[required]'));
			echo $this->Form->error('month');
		?>
	</div>
	<div class="input text required">
		<label for="SheetYearYear">Year</label>
		<?php
			$selectYear = isset($this->data['Sheet']['departmentmonth']['year']) ? $this->data['Sheet']['departmentmonth']['year'] : $this->data['Sheet']['year'];
			echo $this->Form->year('Sheet.departmentmonth', 2010, 2025, $selectYear, array('empty' => 'Select Year', 'value' => $selectYear,'class'=>'validate[required]'));
			echo $this->Form->error('year');
		?>
	</div>
	<input type="hidden" name="data[Sheet][department_id]" value="<?=$this->params['pass'][1]; ?>"/>
	<label for="SheetMonthMonth" style="margin-top:40px">Select Columns for Department Sheet</label>
	<table class="table table-striped table-bordered table-hover">
		<tr><th style="text-align:center;">Column Name</th><th style="text-align:center;">Locked</th><th>Is Decimal</th></tr>
<tr id="response" style="display: none;"><td>All saved! refresh the page to see the changes</td></tr>
<tr>
<td class="sheets index" colspan="3">
<ul>
	<?php 
		echo $this->Form->input('Sheet.id', array('value' => $id));
	?>
	<?php 
		$indx = 0;


$new_columns = array();
$rest_columns = array();
  foreach($selected_columns as $scols){
    foreach($columns as $key=>$value){
	if($scols == $key){
	    $new_columns[$key] = $value;
	    unset($columns[$key]);
	}
    }
  }
$rest_columns = $columns;
foreach($rest_columns as $key=>$value){
  $new_columns[$key] = $value;
}
unset($columns);
$columns = $new_columns;


		foreach($columns as $key => $value){ ?>
		<!--<tr>
			<td>-->
<li id="arrayorder_<?=$key; ?>#0">
				<?php
				$columnid = "ColumnColumn".$key;
				if(in_array($key, $selected_columns)){
					echo $this->Form->checkbox($columnid, array('value' => $key, 'checked' => true, 'name'=>'data[Column][Column][]'));
					foreach($total_columns as $cols){
					    if($cols['ColumnsSheet']['column_id'] == $key){
						echo '<input type="hidden" value="'.$cols['ColumnsSheet']['order'].'" name="data[Column][Order][]"/>';
					    }
					}
				}else{
					echo $this->Form->checkbox($columnid, array('value' => $key, 'name'=>'data[Column][Column][]','class'=>'validate[required]'));
				}
				echo $value;
				?>
			<!--</td>

			<td>-->
				<?php
				
				$decimalid = "ColumnIsDecimal".$key;
				$pos = array_search($key, $col_id);
				$decimal = (is_int($pos))? $is_decimal[$pos] : '0';
				//echo $key." -- ".$pos." -- ".$is_locked;
				if($decimal == '1'){
					echo $this->Form->checkbox($decimalid, array('value' => $key, 'checked' => true, 'name'=>'data[Column][Is_decimal][]','style'=>'float:right; margin-right: 70px;'));
				}else{
					echo $this->Form->checkbox($decimalid, array('value' => $key, 'name'=>'data[Column][Is_decimal][]','style'=>'float:right; margin-right: 70px;'));
				}
				?>
		<!--	</td>

			<td>-->
				<?php
				$lockedid = "ColumnLocked".$key;
				$pos = array_search($key, $col_id);
				$is_locked = (is_int($pos))? $locked[$pos] : '0';
				//echo $key." -- ".$pos." -- ".$is_locked;
				if($is_locked == '1'){
					echo $this->Form->checkbox($lockedid, array('value' => $key, 'checked' => true, 'name'=>'data[Column][Locked][]','style'=>'float:right; margin-right: 230px;'));
				}else{
					echo $this->Form->checkbox($lockedid, array('value' => $key, 'name'=>'data[Column][Locked][]','style'=>'float:right; margin-right: 230px;'));
				}
				?>
		<!--	</td>
  
			
		</tr>-->
</li>
	<?php $indx += 1; } ?>
</ul>
</td></tr>
	</table>

	<label for="SheetMonthMonth" style="margin-top:40px">Select Rows for Department Sheet</label>
	<table class="table table-striped table-bordered table-hover">
		<tr><th>Row Name</th><th>Locked</th></tr>
	<?php
		$indx = 0;
		foreach($rows as $key => $value){ ?>
		<tr>
			<td>
				<?php
				$rowid = "RowRow".$key;
				if(in_array($key, $selected_rows)){
					echo $this->Form->checkbox($rowid, array('value' => $key, 'checked' => true, 'name'=>'data[Row][Row][]'));
				}else{
					echo $this->Form->checkbox($rowid, array('value' => $key, 'name'=>'data[Row][Row][]' ,'class'=>'validate[required]'));
				}
				echo $value;
				?>
			</td>

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

				//echo $value;
				?>
			</td>
		</tr>
	<?php $indx += 1; } ?>

	</table>
	
	<br/><br/>
	<div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import Excel : </label>
		<?php
			echo $this->Form->input('Sheet.import_excel', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/>
        <div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import Protel : </label>
		<?php
			echo $this->Form->input('Sheet.import_protel', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
	<br/>
	<div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import Text File: </label>
		<?php
			echo $this->Form->input('Sheet.import_txt', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/>
	<div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import Opera excl Comps: </label>
		<?php
			echo $this->Form->input('Sheet.import_opera_txt_grand', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/>
	<div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import Protel (Grunerbaum): </label>
		<?php
			echo $this->Form->input('Sheet.import_protel_grunerbaum', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/>
        <div>
                <label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import RDP (Barbados): </label>
                <?php
                        echo $this->Form->input('Sheet.import_excel_barbados', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
                ?>
        </div>
      <br/>
        <div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import PMS CSV: </label>
		<?php
			echo $this->Form->input('Sheet.pms_csv_import', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (4C Hotel): </label>
		<?php
			echo $this->Form->input('Sheet.4c_import', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (4C Edgeware Hotel): </label>
		<?php
			echo $this->Form->input('Sheet.4c_cie_import', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (Raithwaite): </label>
		<?php
			echo $this->Form->input('Sheet.import_raithwaite', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (Lucknam Park): </label>
		<?php
			echo $this->Form->input('Sheet.lucknam_import', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (Simola): </label>
		<?php
			echo $this->Form->input('Sheet.import_simola', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>

	<br/>
	<div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (Ocean View): </label>
		<?php
			echo $this->Form->input('Sheet.import_oceanview', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        
        <br/>
	<div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (Sanbona): </label>
		<?php
			echo $this->Form->input('Sheet.import_sanbona', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/>

	<?php
            echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
            echo "&nbsp;&nbsp;";
            echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'index',$userId,$this->params['pass'][1]), array('class' => 'btn btn-success', 'escape' => false));
            echo $this->Form->end();
        ?>
	
                    </div></div></div></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />