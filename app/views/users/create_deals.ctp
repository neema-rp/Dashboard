<?php ?>

<!--<link media="screen" rel="stylesheet" href="/app/webroot/css/colorbox.css" />
<script src="/app/webroot/js/jquery.colorbox.js"></script>-->
<script src="/app/webroot/js/jquery.colorbox.js"></script>
<link rel="stylesheet" href="/app/webroot/css/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script> -->
<script type="text/javascript" src="/app/webroot/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<!-- <script type="text/javascript" src="/js/fancybox/jquery.easing-1.4.pack.js"></script> -->
<script type="text/javascript" src="/app/webroot/js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>

<script>	

$(document).ready(function() {

//$(".example5").colorbox();



$("#DealCreateDealsForm").validationEngine()
/***call function if checkbox is alredy checked***/
autopushfun(document.getElementById("autopushchk"));
minpushfun(document.getElementById("minpushchk"));
/*********************************************/
});


$(function() {
	var dates = $( "#DealDateFrom, #DealDateTill" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 2,
		onClose: function () {
			$("#DealDateFrom").addClass("validate[required]");
			$("#DealDateTill").addClass("validate[required]");
		},
		
		beforeShow: function (input) {
			$("#DealDateFrom").removeClass("validate[required]");
			$("#DealDateTill").removeClass("validate[required]");
		},
		onSelect: function( selectedDate ) {
			//$("#DealDateFrom").removeClass("validate[required]");
			var option = this.id == "DealDateFrom" ? "minDate" : "maxDate",
				instance = $( this ).data( "datepicker" ),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
				selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
		}
	});

	var dates1 = $( "#DealTravelDateFrom, #DealTravelDateTo" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 2,
		onClose: function () {
			$("#DealTravelDateFrom").addClass("validate[required]");
			$("#DealTravelDateTo").addClass("validate[required]");
		},
		beforeShow: function (input) {
			$("#DealTravelDateFrom").removeClass("validate[required]");
			$("#DealTravelDateTo").removeClass("validate[required]");
		},
		onSelect: function( selectedDate ) {
			var option = this.id == "DealTravelDateFrom" ? "minDate" : "maxDate",
				instance = $( this ).data( "datepicker" ),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
			dates1.not( this ).datepicker( "option", option, date );
		}
	});
});
	


function addMore() {
	
	var j = parseInt(document.getElementById("hid").value) + 1;
	document.getElementById("hid").value = j;
	var i = parseInt(document.getElementById("hid").value)
	str = '<div id="frmRow_'+j+'" class="clear"><label class="lbl"><?php echo __('Upload Photo') ?>:</label>&nbsp;<input type="file" id="photo'+j+'" name="data[Deal][photo]['+i+']">&nbsp;&nbsp;<a href="javascript:void(0);" onClick="removeElement('+j+')">Remove</a><br><label class="lbl">Image Caption:</label>&nbsp;<input type="text" id="caption1" name="data[Deal][caption]['+i+']" class="inpt"></div>';	
	$(str).appendTo("div#photo_addblock");
}

function removeElement(val) {
	if((val-1) == 0) {
		alert('At least one record is required.');
		return false;
	}

	$('div#frmRow_'+val).remove();
}

function  validateInvite() {
	return false;
}

function getpropertylist(val)
{
	if(val != '')
	{
		$.ajax({
			type: "GET",
			url:"/deals/getpropertylist/"+val,
			beforeSend:function(){
				document.getElementById("property_loader").innerHTML = "<img src='/img/ajax_loader.gif'></img>";
			},
			success: function(rmsg){
				if(rmsg){
					document.getElementById('propdiv').innerHTML=rmsg;
				}
			}
		});
	}
}

function getOfferingList(val, offid, dtype)
{	
	if(val != '')
	{
		$.ajax({
			type: "GET",
			url:"/deals/getOfferingList/"+val+"/0/"+dtype,
			beforeSend:function(){
				document.getElementById("offering_loader").innerHTML = "<img src='/img/ajax_loader.gif'></img>";
			},
			success: function(rmsg){
				if(rmsg){
					document.getElementById('offeringdiv').innerHTML=rmsg;
				}
			}
		});
	}
}

function getprice(val)
{	
	if(val != '')
	{
		$.ajax({
			type: "GET",
			url:"/deals/getprice/"+val,
			success: function(rmsg){
				if(rmsg){

				}
			}
		});
	}
}

function gotoAddPage(val)
{
	var typeid = document.getElementById('DealDealType').value;
	if(val == "pro")
	{
		window.location="<?php echo $this->webroot."deals/addProperty/"?>" + typeid;
	}else{
		window.location="<?php echo $this->webroot."deals/addOfferings/"?>" + val;
	}
}


function checkoption(val){
	if(val=='video')
	{
		document.getElementById('v1').checked = true;
	}else{
		document.getElementById('v2').checked = true;	
	}
}

function getOfferingId(){
	return document.getElementById("offering_id").value;
}

function addDealPricing(deal_id){
	var deal_type = document.getElementById('DealDealType').value;
	var offering_id = document.getElementById('offering_id').value;
	
	if(deal_type == 2) {
		
		if(offering_id){
			$.fancybox(
				'<iframe src="http://<?php echo $_SERVER['HTTP_HOST']."/deals/addDealPricing/"?>'+offering_id+'" width="100%" height="100%"><p>Your browser does not support iframes.</p></iframe>',
				{
					'autoDimensions'	: false,
					'width'         	: '700',
					'height'        	: '550',
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'autoScale'		: false,
					'scrolling'             : 'no'
				}
			);

			//window.open ("http://<?php echo $_SERVER['HTTP_HOST']."/deals/addDealPricing/"?>"+offering_id,"mywindow","menubar=1,resizable=1,width=650,height=650");
		}else{
			alert("Please select offering first.");
		}
	} else if(deal_type == 3) {
		if(offering_id){
			$.fancybox(
				'<iframe src="http://<?php echo $_SERVER['HTTP_HOST']."/deals/addCarDealPricing/"?>'+offering_id+'" width="100%" height="100%"><p>Your browser does not support iframes.</p></iframe>',
				{
					'autoDimensions'	: false,
					'width'         	: '700',
					'height'        	: '550',
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'autoScale'		: false,
					'scrolling'             : 'no'
				}
			);
			//window.open ("http://<?php echo $_SERVER['HTTP_HOST']."/deals/addCarDealPricing/"?>"+offering_id,"mywindow","menubar=1,resizable=1,width=650,height=650");
		}else{
			alert("Please select offering first.");
		}
	}else if(deal_type == 1) {
		if(offering_id){
			$.fancybox(
				'<iframe src="http://<?php echo $_SERVER['HTTP_HOST']."/deals/addActDealPricing/"?>'+offering_id+'" width="100%" height="100%"><p>Your browser does not support iframes.</p></iframe>',
				{
					'autoDimensions'	: false,
					'width'         	: '700',
					'height'        	: '550',
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'autoScale'		: false,
					'scrolling'             : 'no'
				}
			);
			//window.open ("http://<?php echo $_SERVER['HTTP_HOST']."/deals/addActDealPricing/"?>"+offering_id,"mywindow","menubar=1, scrollbar=1, resizable=1,width=650,height=650");
		}else{
			alert("Please select offering first.");
		}
	}else{
		if(offering_id){
			$.fancybox(
				'<iframe src="http://<?php echo $_SERVER['HTTP_HOST']."/deals/addEventDealPricing/"?>'+offering_id+'" width="100%" height="100%"><p>Your browser does not support iframes.</p></iframe>',
				{
					'autoDimensions'	: false,
					'width'         	: '700',
					'height'        	: '550',
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'autoScale'		: false,
					'scrolling'             : 'no'
				}
			);
			//window.open ("http://<?php echo $_SERVER['HTTP_HOST']."/deals/addEventDealPricing/"?>"+offering_id,"mywindow","menubar=1, scrollbar=1, resizable=1,width=650,height=650");
		}else{
			alert("Please select offering first.");
		}
	}
}

function form_reset(){
	$("#cke_contents_DealDescription iframe").contents().find("body").text('');
	$("#cke_contents_DealTermsConditions iframe").contents().find("body").text('');
}
/****Function to hide/show and validate/unvalidate Auto Push an last minute push Section****/

function autopushfun(autopush){
    if(autopush.checked==true){
	 
	  
	  $('#autoPushDiv').show();
	  $('#AutoPushSettingMatchrank').addClass('validate[required]');
	  $('#AutoPushSettingNumber').addClass('validate[required]');
	  $('#AutoPushSettingPeriod').addClass('validate[required]');
	  $('#AutoPushSettingAction').addClass('validate[required]');
	  
    }else{
	  
	  if ($('.AutoPushSettingMatchrankformError').length>0) {
	       $('.AutoPushSettingMatchrankformError').remove(); 
	  }
	  if ($('.AutoPushSettingNumberformError').length>0) {
	       $('.AutoPushSettingNumberformError').remove(); 
	  }
	   if ($('.AutoPushSettingPeriodformError').length>0) {
	       $('.AutoPushSettingPeriodformError').remove(); 
	  }
	   if ($('.AutoPushSettingActionformError').length>0) {
	       $('.AutoPushSettingActionformError').remove(); 
	  }
	  $('#AutoPushSettingMatchrank').removeClass('validate[required]');
	  $('#AutoPushSettingNumber').removeClass('validate[required]');
	  $('#AutoPushSettingPeriod').removeClass('validate[required]');
	  $('#AutoPushSettingAction').removeClass('validate[required]');
	  $('#autoPushDiv').hide();
	  
    }
}
function minpushfun(minpush){
    if(minpush.checked==true){
	 
	  $('#minPushDiv').show();
	  $('#LastMinuteDealSettingMatchrank').addClass('validate[required]');
	  $('#LastMinuteDealSettingRadius').addClass('validate[required]');
	  
    }else{
	  
	  if ($('.LastMinuteDealSettingMatchrankformError').length>0) {
	       $('.LastMinuteDealSettingMatchrankformError').remove(); 
	  }
	  if ($('.LastMinuteDealSettingRadiusformError').length>0) {
	       $('.LastMinuteDealSettingRadiusformError').remove(); 
	  }
	  $('#LastMinuteDealSettingMatchrank').removeClass('validate[required]');
	  $('#LastMinuteDealSettingRadius').removeClass('validate[required]');
	
	  $('#minPushDiv').hide();
	  
    }
}

/***********************************************************************/
</script>
<?php echo $this->element('alert_message'); ?>
<section class="forSections">
<p>&nbsp;</p>
<h3><?php echo __('Create Deal');?></h3>

<ul id="maintabs" class="shadetabs tabs">
	<li><a href="/deals/dashboard/" ><span><?php echo __('View');?></span></a></li>
	<li><a href="/deals/push_deals/"><span><?php echo __('Push'). "(" . $count_deal_pending . ")";?></span></a></li>
	<li><a href="/purchases/pendingOffers/"><span><?php echo __('Pending') . "(" . $count_pending . ")";?></span></a></li>
	<li><a href="/deals/create_deals/"  class="selected"><span><?php echo __('Create');?></span></a></li>
	<li><a href="/my_accounts/view_coupon/" ><span><?php __('Coupons'); ?></span></a></li>
</ul>
<div class="sublinks"><?php echo $html->link('Manage Properties', array('controller' => 'deals', 'action' => 'propertyList')); ?>&nbsp;|&nbsp;<?php echo $html->link('Manage Offerings', array('controller' => 'deals', 'action' => 'offeringList')); ?></div>
	<!-- tabular contents starts -->
	<p class="clear"></p>
<!--Create Deal section start-->

	<!-- form starts here -->

	<div class="">
		<div class="boxTop"><span><?php echo $this->Html->image('spacer.gif', array('border' => '0', 'alt' => ''))?></span></div>
		<div class="boxBdy">
			<div class="boxBdy_div frmPadd">
				
				<?php	echo $form->create('Deal', array('method' => 'post', 'enctype' => 'multipart/form-data', 'onreset' => 'form_reset();')); ?>

				<?php echo $form->hidden('row', array('id' => 'hid', 'value' => '1')); ?>

				<label class="lbl"><?php echo __('Offer Name:');?>*</label>
				<?php	echo $form->input('deal_title', array('type' => 'text', 'class' => 'inpt validate[required] text-input', 'label' => false, 'div' => false));?><br />

				<label class="lbl"><?php echo __('Offer Type:');?>*</label>
				<?php echo $this->Form->select('deal_type', $DealList, null, array('class'=>'inpt validate[required] text-input',  'label'=>false, 'div' => false, 'onchange' => 'getpropertylist(this.value)', 'empty'=>'Select')); ?></span><br />

				<div id="propdiv">
				<label class="lbl"><?php echo __('Property:');?>*</label>
				<?php echo $this->Form->select('property_id', '', null, array('class'=>'inpt validate[required] text-input',  'label'=>false, 'div' => false, 'onchange' => 'getOfferingList(this.value)', 'empty'=>'Select')); ?>
				<span style="display:inline-block;padding-left:5px;width:20px;" id="property_loader"></span>
				&nbsp;<?php echo $html->link('Add Property', 'javascript:void(0);', array('id' => 'button1', 'onclick' => "gotoAddPage('pro')")); ?>&nbsp;|&nbsp;<?php echo $html->link('Properties List', 'propertyList', array('id' => 'button1', 'onclick' => "gotoAddPage('pro')")); ?>
				<br /></div>
				
				<div id="offeringdiv">
				<label class="lbl"><?php echo __('Offerings:');?>*</label>
				<?php echo $this->Form->select('offering_id', '', null, array('id' =>'offering_id' , 'class'=>'inpt validate[required] text-input',  'label'=>false, 'div' => false, 'onchange' => 'getprice(this.value)', 'empty'=>'Select')); ?>
				<span style="display:inline-block;padding-left:5px;width:20px;" id="offering_loader"></span>
				&nbsp;<?php echo $html->link('Add Offering', 'javascript:void(0);', array('id' => 'button2', 'onclick' => "gotoAddPage('0')")); ?>
				<br /></div>

				
				<label class="lbl"><?php echo __('Offer Price:($)');?></label>
				<?php //echo $form->input('price', array('type' => 'text', 'class' => 'inpt text-input', 'label' => false, 'div' => false));?>&nbsp;&nbsp;<?php echo $html->link('Add Deal Pricing', 'javascript:void(0);', array('id' => 'dealPricing', 'onclick' => "addDealPricing()")); ?>
				<?php //echo $html->link('Add Deal Pricing', '/deals/addDealPricing/', array('id' => 'dealPricing', 'class'=>"example5")); ?>
				<br />
				
				<div>
					<div id="DealDate" class="left">
					<label class="lbl"><?php echo __('Start Date:');?>*</label>
					<?php	echo $form->input('date_from', array('type' => 'text', 'class' => 'smallinpt validate[required] text-input', 'label' => false, 'div' => false));?>&nbsp;&nbsp;To&nbsp;&nbsp;
					</div>
					<div >					
					<?php	echo $form->input('date_till', array('type' => 'text', 'class' => 'smallinpt validate[required] text-input', 'label' => false, 'div' => false));?><br />
					</div>
				</div>
				
				<div>
					<div id="DealDate1" class="left">
					<label class="lbl"><?php echo __('Travel Date:');?>*</label>
					<?php	echo $form->input('travel_date_from', array('type' => 'text', 'class' => 'smallinpt validate[required] text-input', 'label' => false, 'div' => false));?>&nbsp;&nbsp;To&nbsp;&nbsp;
					</div>
					<div >					
					<?php	echo $form->input('travel_date_to', array('type' => 'text', 'class' => 'smallinpt validate[required] text-input', 'label' => false, 'div' => false));?><br />
					</div>
				</div>

				<label class="lbl"><?php echo __('Expire After:');?>*</label>
				<?php	
				$exp = array('days','hours');
				echo $form->input('expire', array('type' => 'text', 'class' => 'smallinpt validate[required] text-input', 'label' => false, 'div' => false));?>&nbsp;&nbsp;<?php echo $this->Form->select('exp_type', $exp, null, array('class'=>'smallinpt validate[required] text-input',  'label'=>false, 'div' => false)); ?><br />

				<div id="photo_addblock">
					<div id="frmRow_1">
						<label class="lbl"><?php echo __('Upload Photo:');?></label>
						<input type="file" id="photo1" name="data[Deal][photo][1]">&nbsp;&nbsp;<?php echo $html->link('Add', 'javascript:void(0);', array('id' => 'button1', 'onclick' => 'addMore()')); ?><br />
						<label class="lbl"><?php echo __('Image Caption:');?></label>
						<input type="text" id="caption1" name="data[Deal][caption][1]" class="inpt">
						
					</div>
				</div><br />
				
				<label class="lbl"><strong><?php echo __('Add Video:');?></strong></label>
				<div>
					<div id="DealVideo" class="left">
					<input type="radio" value="v" name="data[Deal][video_type]" id="v1" checked="checked">
					<label class="lbl2"><?php echo __('Upload Video:');?></label>
					<?php	echo $form->input('video', array('type' => 'file', 'id' => 'video', 'label' => false, 'div' => false, 'class' => '', 'onclick' => 'checkoption(this.id);'));?><br />
					
					<input type="radio" value="e" name="data[Deal][video_type]" id="v2">
					<label class="lbl2"><?php echo __('Embed Video:');?></label>
					<?php	echo $form->input('video_embeded', array('type' => 'text', 'class' => 'inpt', 'label' => false, 'div' => false, 'onclick' => 'checkoption(this.id);'));?>
					</div>
				</div><br />

				<div class="clear"></div>
				<label class="lbl"><?php echo __('Is Public');?></label>
				<?php	echo $form->input('is_public', array('type' => 'checkbox', 'class' => '', 'label' => false, 'div' => false, 'value' => '1'));?><br />

				<div class="clear"></div>
				<label class="lbl"><?php echo __('Description:');?></label>
				<?php	echo $form->input('description', array('type' => 'textarea', 'cols' => '30', 'rows' => '4', 'class' => 'ckeditor', 'label' => false, 'div' => false));?><br /><br />
				
				<div class="clear"></div>
				<label class="lbl"><?php echo __('Terms & Conditions:');?></label>
				<?php	echo $form->input('terms_conditions', array('type' => 'textarea', 'cols' => '30', 'rows' => '4', 'class' => 'ckeditor', 'label' => false, 'div' => false));?><br /><br />

				<label class="lbl"><strong><?php echo __('Auto Push Settings');?></strong></label>
				<?php	echo $form->input('autopushchk', array('type' => 'checkbox', 'class' => '','id'=>'autopushchk','onClick'=>'javascript: autopushfun(this);', 'label' => false, 'div' => false, 'value' => '1'));?><br />
				
				<div id="autoPushDiv" style="display:none;" >
					<label class="lbl"><?php echo __('Match Rank:');?></label>
					<?php echo $this->Form->select('AutoPushSetting.matchrank', $rank, null, array('class'=>'inpt text-input',  'label'=>false, 'div' => false, 'empty'=>'Select')); ?><br />
	
					<label class="lbl"><?php echo __('Push Period:');?></label>
					<?php	
					$exp = array('days','hours');
					echo $form->input('AutoPushSetting.number', array('type' => 'text', 'class' => 'smallinpt text-input', 'label' => false, 'div' => false));?>&nbsp;&nbsp;<?php echo $this->Form->select('AutoPushSetting.period', $exp, null, array('class'=>'smallinpt text-input',  'label'=>false, 'div' => false, 'empty'=>'Select')); ?><br />
					
					<label class="lbl"><?php echo __('Push Action:');?></label>
					<?php 
					$opt = array('before departure','after arrival', 'upon arrival');
					echo $this->Form->select('AutoPushSetting.action', $opt, null, array('class'=>'inpt text-input',  'label'=>false, 'div' => false, 'empty'=>'Select')); ?><br />
				</div>

				<label class="lbl"><strong><?php echo __('Settings for Last Minute Pushing:');?></strong></label>
				<?php	echo $form->input('minpushchk', array('type' => 'checkbox', 'class' => '','id'=>'minpushchk','onClick'=>'javascript: minpushfun(this);', 'label' => false, 'div' => false, 'value' => '1'));?><br />
				<div id="minPushDiv" style="display:none;" >
					<label class="lbl"><?php echo __('Match Rank:');?></label>
					<?php echo $this->Form->select('LastMinuteDealSetting.matchrank', $rank, null, array('class'=>'inpt text-input',  'label'=>false, 'div' => false, 'empty'=>'Select')); ?><br />
	
					<label class="lbl"><?php echo __('Radius (in miles):');?></label>
					<?php	
					//$exp = array('days','hours');
					echo $form->input('LastMinuteDealSetting.radius', array('type' => 'text', 'class' => 'smallinpt text-input', 'label' => false, 'div' => false));?>
					&nbsp;&nbsp;<label class="lbl"><?php //echo __('hours before deal.');?></label><br />
					
					<!--<label class="lbl"><?php //echo __('Push Action:');?></label>
					<?php 
					//$opt = array('before departure','after arrival', 'upon arrival');
					//echo $this->Form->select('AutoPushSetting.action', $opt, null, array('class'=>'inpt text-input',  'label'=>false, 'div' => false, 'empty'=>'Select')); ?><br />-->
				</div>

				<label class="lbl">&nbsp;</label><span class="goButt">
				    <div id="submit_button_div">
					<?php if(isset($_SESSION["deal_pricing"])){ ?>					  		
					      <?php echo $form->submit('Create', array('class' => 'logInpt', 'id' =>'btn_create')); ?>					  
					<?php } else { ?>
					      <?php echo $form->submit('Create', array('class' => 'logInpt', 'id' =>'btn_create')); ?>	
					      <?php //echo $form->button('Create', array('type' => 'button', 'id' =>'btn_create', 'class' => 'logInpt', 'onclick'=>"alert('Please select pricing first. Click on Add Deal Pricing link above.'); scroll(0,150);")); ?>
					<?php } ?>
				      </div>
				    </span>				
				<span class="goButt">
				<?php echo $form->button('Reset', array('type' => 'reset', 'class' => 'logInpt')); ?>
				</span>
			</div>
		</div>
		<div class="boxBot"><span><?php echo $this->Html->image('spacer.gif', array('border' => '0', 'alt' => ''))?></span></div>
	</div>
				<?php echo $form->end(); ?>
	<!-- form ends here -->
	
	<div class="spacer1"><?php echo $this->Html->image('spacer.gif', array('border' => '0', 'height' => '1', 'width' => '1','alt' => ''))?></div>

</section>
<!--Create Deal section end-->