<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>RP Survey</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="RP Survey">
	<meta name="author" content="">
	
	<link href="/survey/bootstrap.css" rel="stylesheet">
        <link href="/survey/bootstrap-responsive.css" rel="stylesheet">
        <link href="/survey/checkbox_new.css" rel="stylesheet">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<style>
		.rf { color: red; font-weight: bold; padding-right: .25em; }
	</style>	
	
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="../javascripts/html5.js"></script>
	<![endif]-->

	<!-- Fav and touch icons -->
	<link rel="shortcut icon" href="http://www.revenue-performance.com/favicon.ico">
        
<style type="text/css">
    #statement{
     font-weight:bold;   
    }
    p{ padding-left:10px; }
    fieldset{ padding-left:10px; }
    .span8 { padding-left:10px; }
</style>
</head>

<body style="background-color:#78BDDE;font-family: 'Trebuchet MS',sans-serif;">
    	<div class="container" style="background-color:#fff;margin-top:30px;margin-bottom:30px;border-radius:36px;">
			
		<img style="display:block; margin:2em auto 1.5em auto;width:200px;" src="/files/clientlogos/<?php echo $clienImage; ?>">
			
			<div class="row">
				<div class="span8 offset2" style="direction:ltr; margin-bottom:1em;">
					<p>Thank you for taking the time to complete our Customer Satisfaction Survey, we value your feedback.</p><p>This survey provides a set of statements that describe different aspects of your stay at our establishment. To complete the survey, please indicate the extent to which you agree or disagree with each of the below statements.<br><br>Thank you for your participation.</p>
				</div>
			</div>
						
			<form name="form1" id="form1" action="" method="post" format="html">
                            
                            <input type="hidden" name="data[SurveyAnswer][survey_user_id]" value="<?php echo $surveyUserDetails['SurveyUser']['id']; ?>" />
                            
				<div class="row" style="direction:ltr;">
					<div class="span4 offset2">
						<fieldset>
							<div class="control-group">
								<label class="control-label" for="name">Guest Name:</label>
								<div class="controls">
									<input type="text" class="span4" name="name" id="name" style="margin-bottom:1em;" value="<?php echo $surveyUserDetails['SurveyUser']['name']; ?>" disabled="disabled">
								</div>
							</div>
						</fieldset>
					</div>
					<div class="span4">
						<fieldset>
							<div class="control-group">
								<label class="control-label" for="eml">Guest Email Address:</label>
								<div class="controls">
									<input type="text" class="span4" name="eml" id="eml" style="margin-bottom:1em;" value="<?php echo $surveyUserDetails['SurveyUser']['email']; ?>" disabled="disabled">
								</div>
							</div>
						</fieldset>
					</div>
				</div>
                            
                        <div class="row">
                                <div class="span8 offset2" style="border-bottom:1px dashed #ccc;margin-bottom:0.75em;"></div>        
                        </div>
                            
                             <?php if(!empty ($questions)){
                                foreach($questions as $key => $que){ ?>
                            
                                        <input type="hidden" name="data[SurveyAnswer][survey_question_id][<?php echo $key; ?>]" value="<?php echo $que['SurveyQuestion']['id']; ?>" />                                                                                
                            
                                        <div class="gform_wrapper row" style="direction:ltr;">    
                                                <div class="gf_likert span8 offset2">

                                                    <table style="direction:ltr; border-collapse:collapse; border-spacing:0; text-align:left; width:100%;">
                                                            <tbody><tr><td>
                                                                <div id="statement" style="margin-bottom:0.5%; direction:ltr;">
                                                                         <i style="font-size:1.5em" class="fa fa-angle-double-right"></i>&nbsp;&nbsp;<?php echo $que['SurveyQuestion']['title']; ?>
                                                                 </div>
                                                             </td></tr></tbody>
                                                    </table>
                                        
                                        <?php if($que['SurveyQuestion']['type'] == 'Scores'){ ?>

                                                    <table style="direction:ltr; border-collapse:collapse; border-spacing:0; width:100%; padding:0;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:45%; font-size:0.7em; text-align:left; vertical-align:bottom; line-height:normal;">
                                                                <i style="font-size:1.5em" class="fa fa-thumbs-down"></i>&nbsp;&nbsp;&nbsp;Strongly Disagree
                                                            </td>
                                                            <td style="width:10%;"></td>
                                                            <td style="width:45%; font-size:0.7em; text-align:right; vertical-align:bottom; line-height:normal;">
                                                                 Strongly Agree&nbsp;&nbsp;&nbsp;<i style="font-size:1.5em" class="fa fa-thumbs-up"></i>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    </table>

                                                    <table style="width:100%;">
                                                    <tr class="gfield_radio" id="input_3_2">
                                                        <?php for($i=1;$i<=10;$i++){ ?>
                                                        <td class="gchoice_2_<?php echo $i; ?>">
                                                            <div class="inline_div">
                                                                <input name="data[SurveyAnswer][score][<?php echo $key; ?>]" type="radio" value="<?php echo $i; ?>" id="choice_2_<?php echo $i; ?>" tabindex="6" onclick="gf_apply_rules(3,[6]);" class="likert-choice" />
                                                                <div class="likert-label"><label for="choice_2_<?php echo $i; ?>"><?php echo $i; ?></label></div>
                                                                </div>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                        </table>
                                        <?php }else{ ?>
                                              <div class="controls">
                                                    <textarea class="span8" name="data[SurveyAnswer][contents][<?php echo $key; ?>]" id="GM_GuestComm" rows="5"></textarea><div class="charleft originalDisplayInfo" style="width: 756px;">1500 Characters Left</div>
                                            </div>
                                        <?php } ?>
                                                    
                                            </div>
                                   </div>
                                        
                                    <div class="row">
                                            <div class="span8 offset2" style="border-bottom:1px dashed #ccc;margin-bottom:0.75em;margin-top:0.75em;"></div>        
                                    </div>
                            <?php }
                            } ?>

                <div class="row" style="margin-bottom:0.75em;">
                    <div class="span8 offset2"></div>
                </div>

                <div class="row">
				<div class="row">
					<div id="subm" class="span2 offset5" style="text-align:center; visibility:visible; margin-top:2em;">
						<button type="submit" name="submitbutton" value="Submit" class="btn btn-primary">Submit Survey</button>
					</div>
				</div>
                    </div>
                            
			</form>
</div>
	
    <div style="text-align:center;font-size:10px;margin:10px;">&copy;Revenue Performance. All Rights Reserved</div>

<!-- javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/js/jquery-1.6.min.js"></script>
<script src="/survey/bootstrap.min.js"></script>
<script src="/survey/jquery.textareaCounter.plugin.js" type="text/javascript"></script>

<script src="/survey/gf_likert_new.js"></script>

<script type="text/javascript">
	var info;
	$(document).ready(function(){
		var options = { 
			'maxCharacterSize': 1500,
			'originalStyle': 'originalDisplayInfo',
			'warningStyle': 'warningDisplayInfo',
			'warningNumber': 50,
			'displayFormat': '#left Characters Left'					
		};
		$('#GM_GuestComm').textareaCount(options);
		
		var options1 = { 
			'maxCharacterSize': 300,
			'originalStyle': 'originalDisplayInfo',
			'warningStyle': 'warningDisplayInfo',
			'warningNumber': 20,
			'displayFormat': '#left Characters Left'					
		};
		
		$width = $('#container').width();
		$('#container #logo').css({
			'max-width' : $width , 'height' : 'auto'
		});

		$('form').submit(function(){
		    $('input[type=submit]', this).attr('disabled', 'disabled');
		});
	});
	
	function showHide(divId) {
		var ele = $('#'+divId);
		ele.toggle();
	}		
	
</script>
</body></html>