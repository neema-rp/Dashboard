<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />

<script>
$(document).ready(function(){
	$("#SheetAdminAddForm").validationEngine();
    
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
                <h1><?php __($department .':'); ?> <small><i class="icon-double-angle-right"></i>  Add Department Sheet</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter"> Add Department Sheet</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">


<?php echo $this->Form->create('Sheet', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'add' ,$userId,$this->params['pass'][1])));?>
 		
	<?php echo $this->Form->input('name',array('class'=>'validate[required] text-input')); ?>

	<div class="input text required">
		<label for="SheetMonthMonth">Month</label>
		<?php
			echo $this->Form->month('Sheet.departmentmonth', null, array('empty' => 'Select Month', 'class'=>'validate[required]'));
			echo $this->Form->error('month');
		?>
	</div>
	<div class="input text required">
		<label for="SheetYearYear">Year</label>
		<?php
			echo $this->Form->year('Sheet.departmentmonth', 2010, 2025, date('Y'), array('empty' => 'Select Year', 'class'=>'validate[required]'));
			echo $this->Form->error('year');
		?>
	</div>

	<?php
		echo $this->Form->input('Column', array('style' => "height:200px", 'div' => false, 'label' => 'Select Columns for Department Sheet','class'=>'validate[required]'));
	?>
	<?php
		echo $this->Form->input('Row', array('multiple' => "multiple",'name'=>'data[Row][Row]','id'=>'RowRow', 'div' => false, 'label' => 'Select Rows for Department Sheet'));
	?>
	
	<br/><br/>
	<div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Email Notification : </label>&nbsp;&nbsp;&nbsp;
		<?php
			echo $this->Form->input('Sheet.is_email', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:super;')).'&nbsp;&nbsp;&nbsp;';
			
		?>
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
	<div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import Excel : </label>
		<?php
			echo $this->Form->input('Sheet.import_excel', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/><br/>
        <div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import Protel : </label>
		<?php
			echo $this->Form->input('Sheet.import_protel', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
	<br/><br/>
	<div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import Text File: </label>
		<?php
			echo $this->Form->input('Sheet.import_txt', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/><br/>
	<div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import Opera excl Comps: </label>
		<?php
			echo $this->Form->input('Sheet.import_opera_txt_grand', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/><br/>
	<div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import Protel (Grunerbaum): </label>
		<?php
			echo $this->Form->input('Sheet.import_protel_grunerbaum', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/><br/>
        <div>
                <label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import RDP (Barbados): </label>
                <?php
                        echo $this->Form->input('Sheet.import_excel_barbados', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
                ?>
        </div>
      <br/><br/>
        <div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import PMS CSV: </label>
		<?php
			echo $this->Form->input('Sheet.pms_csv_import', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/><br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (4C Hotel): </label>
		<?php
			echo $this->Form->input('Sheet.4c_import', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/><br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (4C Edgeware Hotel): </label>
		<?php
			echo $this->Form->input('Sheet.4c_cie_import', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/><br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (Raithwaite): </label>
		<?php
			echo $this->Form->input('Sheet.import_raithwaite', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/><br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (Lucknam Park): </label>
		<?php
			echo $this->Form->input('Sheet.lucknam_import', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
	<br/><br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (Simola): </label>
		<?php
			echo $this->Form->input('Sheet.import_simola', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
	<br/><br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (Ocean View): </label>
		<?php
			echo $this->Form->input('Sheet.import_oceanview', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
        <br/><br/>
        <div class="input text">
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import CSV (Sanbona): </label>
		<?php
			echo $this->Form->input('Sheet.import_sanbona', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
		?>
	</div>
	<br/><br/>
	<input type="hidden" name="data[Sheet][department_id]" value="<?=$this->params['pass'][1]; ?>"/>
            <?php
                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'index',$userId,$this->params['pass'][1]), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end();
            ?>

        </div></div></div></div>
</div>
