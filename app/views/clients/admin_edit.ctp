<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#ClientProfileEdit").validationEngine();
    });
</script>



<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Edit Hotel</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Edit Hotel Details</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
                <?php echo $this->Form->create('Client', array('type' => 'file', 'id' => 'ClientProfileEdit','class'=>'form-horizontal')); 
                if(isset($allhotels)){
                        $parent_hotels = array();
                  foreach($allhotels as $hotel)
                  {
                    $parent_hotels[$hotel['Client']['id']] = $hotel['Client']['hotelname'];
                  }
                }
		echo $this->Form->input('parent_id', array('type'=>'select','options'=>$parent_hotels,'empty'=>'-- Select Parent Hotel --','class'=>'span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('id');
		echo $this->Form->input('username',array('id'=>'username','class'=>'validate[required,length[0,20]] span6','placeholder'=>'Username','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('hotelname',array('id'=>'hotelname','class'=>'validate[required] span6','placeholder'=>'Hotelname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                echo $this->Form->input('hotel_profile',array('id'=>'hotel_profile','type'=>'textarea','class'=>'span6','placeholder'=>'Hotel Profile','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('firstname',array('id'=>'firstname','class'=>'validate[required,length[0,20]] span6','placeholder'=>'Firstname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('lastname',array('id'=>'lstname','class'=>'validate[required] span6','placeholder'=>'Lastname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('email',array('id'=>'email','class'=>'validate[required,custom[email]] span6','placeholder'=>'Email','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('phone',array('id'=>'phone','class'=>'validate[required,length[0,10]] span6','placeholder'=>'Phone','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                echo $this->Form->input('number_of_rooms',array('id'=>'number_of_rooms','class'=>'span6','placeholder'=>'Number Of Rooms','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                echo $this->Form->input('restaurant_open_hours',array('id'=>'restaurant_open_hours','class'=>'span6','placeholder'=>'Restaurant Open Hours','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                echo $this->Form->input('chairs_in_restaurant',array('id'=>'chairs_in_restaurant','class'=>'span6','placeholder'=>'Chairs In Restaurant','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                echo $this->Form->input('clientlogo', array('type' => 'file','placeholder'=>'Logo','class'=>'span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo "<div style='height:25px'></div>";
		echo $this->data['Client']['logo'] ? $this->Html->image('/files/clientlogos'. DS . $this->data['Client']['logo'] , array('width'=>200 , 'height'=>120)) : $this->Html->image('/img/pna.png', array('width'=>200 , 'height'=>120));

                echo "<br/>";
                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end();
                ?>
        </div>
     </div>
</div>
        </div>
<script>
$(document).ready(function(){
    $("#ClientResetPass").validationEngine();
    });
</script>

<div class="widget-box">
    <div class="widget-header widget-header-blue widget-header-flat">
            <h4 class="lighter">Change password</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="row-fluid">
                <?php
                    echo $this->Form->create('Client', array('id' => 'ClientResetPass','class'=>'form-horizontal'));
                    echo $this->Form->input('id');
                    echo $this->Form->input('password', array('value' => '','id'=>'password','class'=>'validate[required] span6','placeholder'=>'Password','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                    echo $this->Form->input('confirm_password', array('type' => 'password', 'value' => '','id'=>'confirm_password','class'=>'validate[required,equals[password] span6','placeholder'=>'Confirm Password','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));

                    echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                    echo "&nbsp;&nbsp;";
                    echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                    echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>

<br/><br/><br/>
<?php echo $this->Form->create('Client', array('id' => 'regional_email','controller'=>'clients','action'=>'update_regional_emails','class'=>'form-horizontal'));?>
<fieldset>
        <legend><?php __('Regional Email Notification'); ?></legend>
    <?php
        echo $this->Form->input('EmailSummarySheet.client_id',array('type'=>'hidden','value'=>$this->data['Client']['id']));
        echo $this->Form->input('Client.regional_email', array('div'=>false,'id'=>'Client_regional_email','type'=>'checkbox','label'=>false,'class'=>'form-field-checkbox')).'&nbsp;&nbsp;&nbsp;';
    ?>
        <?php 
            $display_prop = (isset($this->data['Client']['regional_email']) && ($this->data['Client']['regional_email'] == 1)) ? '':'style="display:none;"';
	?>
	<div class="input text" id="email_update" <?php echo $display_prop; ?>>
	
		<?php if(isset($this->data['EmailSummarySheet']) && (count($this->data['EmailSummarySheet']) > 0)){
			foreach($this->data['EmailSummarySheet'] as $key=>$EmailSheet){
			if($key!=0){ echo '<br/>'; }
                    	?>
			<div id="email<?php echo $key; ?>">	
			<?php if($key == 0){ ?>
			<label for="SheetImportImport" class="control-label" style="float:left;color:#5F5F5F;"><b>Email Address *: </b></label>&nbsp;&nbsp;&nbsp;		
			<?php } else {?>
			<label for="SheetImportImport" class="control-label" style="float:left;color:#5F5F5F;width:132px;">&nbsp;</label>&nbsp;&nbsp;&nbsp;	
			<?php } ?>
			<?php							
				echo $this->Form->input('EmailSummarySheet.'.$key.'.email',array('div'=>false,'label'=>false,'value'=>$EmailSheet['EmailSummarySheet']['email'],'style'=>'width:400px;','class'=>'validate[required,custom[email]]')); 
			?>
			
			<?php if($key == (count($this->data['EmailSummarySheet']) -1)){ ?>
			<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_location(<?php echo $key; ?>)">Add More</a>
			<?php } else { ?>
			<a style="padding-bottom:5px;*line-height: 28px;display:none;" class="new_button" href="javascript:void(0);"  onClick="add_location(<?php echo $key; ?>)">Add More</a>
			<?php } ?>
			
			<?php if($key != 0){?>
			<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="delete_location(<?php echo $key; ?>)">Delete</a>
			<?php } ?>
			<strong style="display:none;"><?php echo $key; ?></strong>
		</div>
		<?php } } else { ?>
			<div id="email0">	
				<label for="SheetImportImport" style="float:left;color:#5F5F5F;" class="control-label"><b>Email Address *: </b></label>&nbsp;&nbsp;&nbsp;
				<?php echo $this->Form->input('EmailSummarySheet.0.email',array('div'=>false,'label'=>false,'style'=>'width:400px;','class'=>'validate[required,custom[email]]')); ?>
				<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_location(0)">Add More</a>
				<strong style="display:none;">0</strong>
			</div>
		<?php } ?>
	</div>
	<br/>
	<?php echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
	echo $this->Form->end();?>
        
</fieldset>



<br/><br/><br/>
<?php echo $this->Form->create('Client', array('id' => 'monthly_detailed_email','controller'=>'clients','action'=>'update_regional_emails','class'=>'form-horizontal'));?>
<fieldset>
        <legend><?php __('3 Months Email Notification'); ?></legend>
    <?php
        echo $this->Form->input('EmailSummarySheet.client_id',array('type'=>'hidden','id'=>'summary_clientId','value'=>$this->data['Client']['id']));
        echo $this->Form->input('Client.monthly_detailed_email', array('div'=>false,'id'=>'Client_monthly_detailed_email','type'=>'checkbox','label'=>false,'class'=>'form-field-checkbox')).'&nbsp;&nbsp;&nbsp;';
    ?>
        
        <?php 
	$display_prop = (isset($this->data['Client']['monthly_detailed_email']) && ($this->data['Client']['monthly_detailed_email'] == 1)) ? '':'style="display:none;"';
	?>
	<div class="input text" id="summary_email_update" <?php echo $display_prop; ?>>
	
		<?php 
                 if(isset($summaryEmails) && (count($summaryEmails) > 0)){ 
		
			foreach($summaryEmails as $key=>$EmailSheet){
			
			if($key!=0){ echo '<br/>'; }
                    	?>
			<div id="summary_email<?php echo $key; ?>" style="padding:10px;">	
			<?php if($key == 0){ ?>
			<label for="SheetImportImport" style="float:left;color:#5F5F5F;" class="control-label"><b>Email Address *: </b></label>&nbsp;&nbsp;&nbsp;		
			<?php } else {?>
			<label for="SheetImportImport" style="float:left;color:#5F5F5F;width:132px;" class="control-label">&nbsp;</label>&nbsp;&nbsp;&nbsp;	
			<?php } ?>
			<?php							
			echo $this->Form->input('EmailSummarySheet.'.$key.'.email',array('div'=>false,'label'=>false,'value'=>$EmailSheet['EmailSummarySheet']['email'],'style'=>'width:400px;','class'=>'validate[required,custom[email]]')); 
			?>
			
			<?php if($key == (count($summaryEmails) -1)){ ?>
			<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="summary_add_location(<?php echo $key; ?>)">Add More</a>
			<?php } else { ?>
			<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="summary_add_location(<?php echo $key; ?>)">Add More</a>
			<?php } ?>
			
			<?php if($key != 0){?>
			<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="summary_delete_location(<?php echo $key; ?>)">Delete</a>
			<?php } ?>
			<row style="display:none;"><?php echo $key; ?></row>
		</div>
		<?php } } else { ?>
			<div id="summary_email0" style="padding:10px;">	
				<label for="SheetImportImport" style="float:left;color:#5F5F5F;" class="control-label"><b>Email Address *: </b></label>&nbsp;&nbsp;&nbsp;
				<?php echo $this->Form->input('EmailSummarySheet.0.email',array('div'=>false,'label'=>false,'style'=>'width:400px;','class'=>'validate[required,custom[email]]')); ?>
				<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="summary_add_location(0)">Add More</a>
				<row style="display:none;">0</row>
			</div>
		<?php } ?>
	</div>
        
	<br/><br/> 	
        <div style="float:left;width:110px;clear:both;">
	<?php echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
	echo $this->Form->end();?>
	</div>
        
</fieldset>



<br/><br/><br/>
<?php echo $this->Form->create('Client', array('id' => 'flash_email_form','controller'=>'clients','action'=>'update_flash_emails','class'=>'form-horizontal'));?>
<fieldset>
        <legend><?php __('Daily Flash Email'); ?></legend>
<?php
        echo $this->Form->input('FlashEmail.client_id',array('type'=>'hidden','id'=>'flash_clientId','value'=>$this->data['Client']['id']));
        echo $this->Form->input('Client.flash_email', array('div'=>false,'id'=>'Client_flash_email','type'=>'checkbox','label'=>false,'class'=>'form-field-checkbox')).'&nbsp;&nbsp;&nbsp;';
?>
        
        <?php 
	$display_prop_flash = (isset($this->data['Client']['flash_email']) && ($this->data['Client']['flash_email'] == 1)) ? '':'style="display:none;"';
	?>
	<div class="input text" id="flash_email_update" <?php echo $display_prop_flash; ?>>	
		<?php 
                 if(isset($flashEmailsList) && (count($flashEmailsList) > 0)){ 
		
			foreach($flashEmailsList as $key=>$flash_eml){
			
			if($key!=0){ echo '<br/>'; }
                    	?>
			<div id="div_flash_email<?php echo $key; ?>" style="padding:10px;">	
			<?php if($key == 0){ ?>
			<label for="SheetImportImport" style="float:left;color:#5F5F5F;" class="control-label"><b>Email Address *: </b></label>&nbsp;&nbsp;&nbsp;		
			<?php } else {?>
			<label for="SheetImportImport" style="float:left;color:#5F5F5F;" class="control-label" >&nbsp;</label>&nbsp;&nbsp;&nbsp;	
			<?php } ?>
			<?php							
			echo $this->Form->input('FlashEmail.'.$key.'.email',array('div'=>false,'label'=>false,'value'=>$flash_eml['FlashEmail']['email'],'style'=>'width:400px;','class'=>'validate[required,custom[email]]')); 
			?>
			<?php if($key == (count($flashEmails) -1)){ ?>
			<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_flash_email(<?php echo $key; ?>)">Add More</a>
			<?php } else { ?>
			<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_flash_email(<?php echo $key; ?>)">Add More</a>
			<?php } ?>
			
			<?php if($key != 0){?>
			<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="delete_flash_email(<?php echo $key; ?>)">Delete</a>
			<?php } ?>
			<row style="display:none;"><?php echo $key; ?></row>
		</div>
		<?php } } else { ?>
			<div id="div_flash_email0" style="padding:10px;">	
				<label for="SheetImportImport" style="float:left;color:#5F5F5F;" class="control-label"><b>Email Address *: </b></label>&nbsp;&nbsp;&nbsp;
				<?php echo $this->Form->input('FlashEmail.0.email',array('div'=>false,'label'=>false,'style'=>'width:400px;','class'=>'validate[required,custom[email]]')); ?>
				<a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_flash_email(0)">Add More</a>
				<row style="display:none;">0</row>
			</div>
		<?php } ?>
	</div>
        
	<br/><br/> 	
        <div style="float:left;width:110px;clear:both;">
	<?php echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
	echo $this->Form->end();?>
	</div>
        
</fieldset>


</div>

<script>
function add_location(add_id)
{
        var num = parseInt($("strong:last").text());        	
        var valid = get_validate(num);
        //console.log(num);
        if(valid){
		alert('Please enter email address');
        } else {
            // console.log('here');
            var newNum = new Number(num + 1);
            var newElem = $('#email' + 0).clone().attr('id', 'email' + newNum);
            $('#email' + num).after(newElem);                
            $('#email' + newNum+'>label').html('').width('132px');
            $('#email' + newNum+'>label').before('<br/>');
            $('#email' + newNum+'>input').attr('name', 'data[EmailSummarySheet]['+newNum+'][email]').attr('value', '').attr('id', 'EmailSummarySheet'+newNum+'email');
            $('#email' + num+'>a:eq(0)').hide();                              
            $('#email' + newNum+'>a').attr('onclick','add_location('+newNum+')').show();
            $('#email' + newNum+'>strong').remove();
            $('#email' + newNum+'>a').after('<strong style="display:none;">'+newNum+'</strong>');
	    $('#email' + newNum+'>a').after('<a onclick="delete_location('+newNum+');" href="javascript:void(0);" class="new_button">Delete</a>');
         }
}

function delete_location(remove_id){
         $('.EmailSummarySheet'+remove_id+'emailformError').remove();
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
function get_validate_summary(num){
	if($('#summary_email' + num+'>input').val() == ''){
		return true;
		
	} else {
		return false;		
	}

}

function summary_add_location(add_id)
{
       // var num = parseInt($("row:last").text()); 
       	 var num =add_id;
        var valid = get_validate_summary(num);
        if(valid){
		alert('Please enter email address');
        } else {
            var newNum = new Number(num + 1);
            var newElem = $('#summary_email' + 0).clone().attr('id', 'summary_email' + newNum);
            $('#summary_email' + num).after(newElem);                
            $('#summary_email' + newNum+'>row').html('').width('132px');
            $('#summary_email' + newNum+'>row').before('<br/>');
            $('#summary_email' + newNum+'>input').attr('name', 'data[EmailSummarySheet]['+newNum+'][email]').attr('value', '').attr('id', 'EmailSummarySheet'+newNum+'email');
            $('#summary_email' + num+'>a:eq(0)').hide();                              
            $('#summary_email' + newNum+'>a').attr('onclick','summary_add_location('+newNum+')').show();
            $('#summary_email' + newNum+'>row').remove();
            $('#summary_email' + newNum+'>a').after('<row style="display:none;">'+newNum+'</row>');
	    $('#summary_email' + newNum+'>a').after('<a onclick="summary_delete_location('+newNum+');" href="javascript:void(0);" class="new_button">Delete</a>');
         }
}

function summary_delete_location(remove_id){
         $('.EmailSummarySheet'+remove_id+'emailformError').remove();
        $("#summary_email"+remove_id).remove();
        var num = parseInt($("row:last").text());                     
        $('#summary_email' + num+'>a:eq(0)').show();
}




function get_validate_flash(num){
	if($('#div_flash_email' + num+'>input').val() == ''){
		return true;
		
	} else {
		return false;		
	}

}

function add_flash_email(add_id)
{
       // var num = parseInt($("row:last").text()); 
       	 var num =add_id;
        var valid = get_validate_summary(num);
        if(valid){
		alert('Please enter email address');
        } else {
            var newNum = new Number(num + 1);
            var newElem = $('#div_flash_email' + 0).clone().attr('id', 'div_flash_email' + newNum);
            $('#div_flash_email' + num).after(newElem);                
            $('#div_flash_email' + newNum+'>row').html('').width('132px');
            $('#div_flash_email' + newNum+'>row').before('<br/>');
            $('#div_flash_email' + newNum+'>input').attr('name', 'data[FlashEmail]['+newNum+'][email]').attr('value', '').attr('id', 'FlashEmail'+newNum+'email');
            $('#div_flash_email' + num+'>a:eq(0)').hide();                              
            $('#div_flash_email' + newNum+'>a').attr('onclick','add_flash_email('+newNum+')').show();
            $('#div_flash_email' + newNum+'>row').remove();
            $('#div_flash_email' + newNum+'>a').after('<row style="display:none;">'+newNum+'</row>');
	    $('#div_flash_email' + newNum+'>a').after('<a onclick="delete_flash_email('+newNum+');" href="javascript:void(0);" class="new_button">Delete</a>');
         }
}

function delete_flash_email(remove_id){
         $('.FlashEmail'+remove_id+'emailformError').remove();
        $("#div_flash_email"+remove_id).remove();
        var num = parseInt($("row:last").text());                     
        $('#div_flash_email' + num+'>a:eq(0)').show();
}

$(document).ready(function(){ 	
    
    $('#Client_regional_email').click(function() {
            if($(this).is(':checked')){
                    $('#email_update').show();
            }else{
                    $('#email_update').hide();
            }	
    });
    $('#Client_monthly_detailed_email').click(function() {
            if($(this).is(':checked')){
                    $('#summary_email_update').show();
            }else{
                    $('#summary_email_update').hide();
            }	
    });
   $('#Client_flash_email').click(function() {
            if($(this).is(':checked')){
                    $('#flash_email_update').show();
            }else{
                    $('#flash_email_update').hide();
            }	
    });
});

</script>
