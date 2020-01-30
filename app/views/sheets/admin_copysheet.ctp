<?php ?><script>
$(document).ready(function(){
    //$("#SheetAdminAddForm").validationEngine();
    
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
        
        <?php if(isset($clientId)){ ?>
            getDepartmentList(<?php echo $clientId; ?>)
        <?php } ?>
    
    });
    
    
    function getDepartmentList(client_id){
	jQuery.ajax({
		type: "GET",
		url:"/users/getDepartmentList/"+client_id,
		beforeSend:function(){
			document.getElementById('div_departments').innerHTML="Loading...";
		},
		success: function(rmsg){
			if(rmsg){
				document.getElementById('div_departments').innerHTML=rmsg;
                                
				<?php if(isset($this->params['pass'][2])){ ?>
				    var ele = document.getElementById('department_name');
				    ele.value = <?php echo $this->params['pass'][2]; ?>
				<?php } ?>
                                    
                                    $("#department_name").removeAttr( "multiple" );
                                    $("#div_departments").css( "margin-left",'15px');
			}
		}
	});
}

    
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
                                              
                $('#email' + newNum+'>a').attr('onclick','add_location('+newNum+')').show();
                
                $('#email' + newNum+'>strong').remove();
		$('#email' + newNum+'>a').after('<strong style="display:none;">'+newNum+'</strong>');
		
                                 
		$('#email' + num+'>a:eq(0)').hide();
                    
                
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
                <h1>Copy <?php echo $all_hotels[$clientId]; ?> - <?php __($department .':'); ?> <small><i class="icon-double-angle-right"></i>  Department Sheet</small></h1>
        </div>
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter"> Create Department Sheet</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

<?php echo $this->Form->create('Sheet', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'copysheet' ,$userId,$this->params['pass'][1], $this->params['pass'][2])));?>
	
 		
                <div style="font-size: 11px;">*It will copy the last available sheet within this department<br/>
                *Rows, Columns and Formula will be same<br/>
                *Data values will not be copied, it will be set as 0
                </div>
                    
        <div class="input select required">
	<?php
            echo $this->Form->input('client_id',array('class'=>'validate[required]','options'=>$all_hotels,'value' => $clientId, 'onChange' => 'getDepartmentList(this.value)', 'label'=>'Hotel', 'empty'=>'Select Hotel','label'=>array('class'=>'control-label','text'=>'Copy Sheets to Hotel'),'div'=>array('class'=>'control-group')));
	?>
      </div>
	<div id="div_departments" class="control-group">
	<?php	
		echo $this->Form->input('department_name', array('options' => '','id'=>'department_name','class'=>'validate[required] span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group','text'=>'Copy Sheets to Hotel Department')));
	?>
	</div>
                    
                    
	<?php echo $this->Form->input('name',array('class'=>'validate[required] text-input')); ?>

	<div class="input text required">
		<label for="SheetMonthMonth">Month</label>
		<?php
			echo $this->Form->month('Sheet.departmentmonth', null, array('empty' => false,'multiple' => true,'size' => 10, 'class'=>'validate[required]'));
			echo $this->Form->error('month');
		?>
	</div>
	<div class="input text required">
		<label for="SheetYearYear">Year</label>
		<?php
			echo $this->Form->year('Sheet.departmentmonth', 2010, 2025, date('Y'), array('empty' => 'Select Year', 'class'=>'validate[required]','style'=>'border:1px solid #666;'));
			echo $this->Form->error('year');
		?>
	</div>
        
        <div>
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Import Excel : </label>
		<?php
			echo $this->Form->input('Sheet.import_excel', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:sub;'));
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
		<label for="SheetImportImport" style="float:left;color:#5F5F5F;">Email Notification : </label>&nbsp;&nbsp;&nbsp;
		<?php
                    echo $this->Form->input('Sheet.is_email', array('div'=>false,'label'=>false,'style'=>'float:none;vertical-align:super;')).'&nbsp;&nbsp;&nbsp;';
		?>
	</div>
        <div class="input text" id="email" <?php echo $display_prop; ?>>
        <div id="email0">	
                <label for="SheetImportImport" style="float:left;color:#5F5F5F;"><b>Email Address *: </b></label>&nbsp;&nbsp;&nbsp;
                <?php							
                        echo $this->Form->input('EmailSheet.0.email',array('div'=>false,'label'=>false,'style'=>'width:400px;','class'=>'validate[required,custom[email]]')); 
                ?>

                <a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_location(0)">Add More</a>

                <strong style="display:none;">0</strong>
        </div>
	</div>
	<br/><br/>
	<input type="hidden" name="data[Sheet][department_id]" value="<?=$this->params['pass'][2]; ?>"/>

        <?php
            echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
            echo "&nbsp;&nbsp;";
            echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'index',$userId,$this->params['pass'][2]), array('class' => 'btn btn-success', 'escape' => false));
            echo $this->Form->end();
        ?>
        </div></div></div></div>
</div>