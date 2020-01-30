<?php
	class SendemailComponent extends Object{

		/**
		* function : send()
		* params   : $recieverEmail : Reciver email address.
		* params   : $senderEmail : Sender email address.
		* params   : $subject : Subject line for email.
		* params   : $message : Actual contents to send to user.
		* description : This function is use to send mail to user.
		*/

		var $senderEmail = 'test';
		function not_send_____($recieverEmail, $senderEmail, $subject, $message,$path=null){
			require_once('vendors/phpmailer/class.phpmailer.php');

			$mail = new PHPMailer();
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->From = $senderEmail;
			$mail->FromName = $senderEmail;
			//$mail->Sender = $recieverEmail;
			$mail->AddAddress($recieverEmail);
			$mail->Subject = $subject;
			$mail->Body    = $message;
			$mail->AddAttachment($path);
			$mail->WordWrap = 100;
			$mail->IsHTML(true);

			if ($mail->Send()) {


				return true;

			}else {
				return false;
			}
		}



			/**
		* function : send()
		* params   : $recieverEmail : Reciver email address.
		* params   : $senderEmail : Sender email address.
		* params   : $subject : Subject line for email.
		* params   : $message : Actual contents to send to user.
		* description : This function is use to send mail to user.
		*/
	/*	function send($recieverEmail, $senderEmail, $subject, $message,$path=null){
			require_once('../../venders/phpmailer/class.phpmailer.php');
			
			$mail = new PHPMailer();
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->From = $senderEmail;
			$mail->FromName = $senderEmail;
			//$mail->Sender = $recieverEmail;
			$mail->AddAddress($recieverEmail);
			$mail->Subject = $subject;
			$mail->Body    = $message;
			$mail->AddAttachment($path);
			$mail->WordWrap = 100;
			$mail->IsHTML(true);

			if ($mail->Send()) {
				return true;
			}else {
				return false;
			}
		}*/

	function send($recieverEmail=null, $senderEmail=null, $subject=null, $message=null,$path=null){
			require_once('vendors/phpmailer/class.phpmailer.php');
			$mail = new PHPMailer();
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->From = $senderEmail;
			$mail->FromName = $senderEmail;
			//$mail->Sender = $recieverEmail;
			if(is_array($recieverEmail))
			{
				foreach($recieverEmail as $key => $reciver)
				{
					$mail->AddAddress($reciver);
				}

			}else{
					$mail->AddAddress($recieverEmail);
				 }
			//$mail->AddCC($addcc);
			$mail->Subject = $subject;
			$mail->Body    = $message;
			if(!empty($path))
			{
				$mail->AddAttachment($path);
			}
			$mail->WordWrap = 100;
			$mail->IsHTML(true);

			if ($mail->Send()) {
				return true;
			}else {
				return false;
			}
		}



#################################################
# Function Name:changePassword
# Parameter    :NA
# Return       :County
# D.O.M        :10-June-2010
#################################################
function changePassword($toemail,$fusername,$password)
{
	$to = $toemail;
	//$to = 'radheyy@smartdatainc.net';
	$from = 'admin@My Dashboard.com';
	$subject = 'Password Changed';
	$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
				<tr>
				<td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>My Dashboard
				</td>
				</tr>
				<tr>
				<td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'>Hi ".$fusername.",</div>
				<div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
				<table cellpadding='0' style='margin-top: 5px;'>
				<tr valign='top'>
				<td style='padding: 0px 3px 10px 0px;'>
					<FONT SIZE=2 FACE='Arial'>Your Password has been changed <br />
					Your new password is: ".$password."<br />
					</FONT></P>
					<P><FONT SIZE=2 FACE='Arial'></FONT>
				</td>
				</tr>
				</table>
				<br/>
				<br/>
				<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
				<br/>
				</div>
				<div style='margin: 0pt;'>Thanks & Regards,<br/>
				Mydashboard Team<br />
				<a href='http://www.Mydashboard.com'>www.Mydashboard.com</a>
				</div>
				</td>
				<td align='left' width='150' valign='top' style='padding-left: 15px;'>
				<table cellspacing='0' cellpadding='0' width='100%'>
				<tr>
				<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
				<div style='margin-bottom: 15px;'><b>Toccata Live</b><br /><a href='http://www.Toccata Live.com'>www.Toccata Live.com</a></div>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				<img style='border: 0pt none ; min-height: 1px; width: 1px;' alt=''/>";



	$ifsend = $this->send($to,$from,$subject,$message);
	if($ifsend == true){
		return true;
	}else{
	return false;
	}
}

#################################################
# Function Name:createAccount
# Parameter    :NA
# Return       :County
# D.O.M        :10-June-2010
#################################################
function createAccount($toemail,$username, $password,$url)
{
	$to = $toemail;
	$from = 'admin@sportsladder.com';
	$subject = 'New Account Created';
	$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
				<tr>
				<td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>Mydashboard
				</td>
				</tr>
				<tr>
				<td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'></div>
				<div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
				<table cellpadding='0' style='margin-top: 5px;'>
				<tr valign='top'>
				<td style='padding: 0px 3px 10px 0px;'>
					<FONT SIZE=2 FACE='Arial'>Your new account is created <br />
					Your Email is: ".$toemail."<br />
					Your Username is: ".$username."<br />
					Your password is: ".$password."<br />
					Please click the link to Activation: <a href = ".$url." alt='click'>Click Here</a><br />
					</FONT></P>
					<P><FONT SIZE=2 FACE='Arial'></FONT>
				</td>
				</tr>
				</table>
				<br/>
				<br/>
				<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
				<br/>
				</div>
				<div style='margin: 0pt;'>Thanks & Regards,<br/>
				Mydashboard Team<br />
				<a href='http://www.Mydashboard.com'>www.Mydashboard.com</a>
				</div>
				</td>
				<td align='left' width='150' valign='top' style='padding-left: 15px;'>
				<table cellspacing='0' cellpadding='0' width='100%'>
				<tr>
				<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
				<div style='margin-bottom: 15px;'><b>Mydashboard</b><br /><a href='http://www.Mydashboard.com'>www.Mydashboard.com</a></div>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				<img style='border: 0pt none ; min-height: 1px; width: 1px;' alt=''/>";


	$ifsend = $this->send($to,$from,$subject,$message);
	if($ifsend == true){
		return true;
	}else{
	return false;
	}
}



#################################################
# Function Name:createNewAccount
# Parameter    :NA
# Return       :County
# D.O.M        :10-June-2010
#################################################
function createNewAccount($toemail,$fusername,$password)
{
	$to = $toemail;
	$from = 'admin@My Dashboard.com';
	$subject = 'New Account Created';
	$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
				<tr>
				<td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>My Dashboard
				</td>
				</tr>
				<tr>
				<td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'>Hi ".$fusername.",</div>
				<div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
				<table cellpadding='0' style='margin-top: 5px;'>
				<tr valign='top'>
				<td style='padding: 0px 3px 10px 0px;'>
					<FONT SIZE=2 FACE='Arial'>Your new account is created <br />
					Your UserName is: ".$fusername."<br />
					Your Email is: ".$toemail."<br />
					Your password is: ".$password."<br />
					</FONT></P>
					<P><FONT SIZE=2 FACE='Arial'></FONT>
				</td>
				</tr>
				</table>
				<br/>
				<br/>
				<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
				<br/>
				</div>
				<div style='margin: 0pt;'>Thanks & Regards,<br/>
				Mydashboard Team<br />
				<a href='http://www.Mydashboard.com'>www.Mydashboard.com</a>
				</div>
				</td>
				<td align='left' width='150' valign='top' style='padding-left: 15px;'>
				<table cellspacing='0' cellpadding='0' width='100%'>
				<tr>
				<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
				<div style='margin-bottom: 15px;'><b>My Dashboard</b><br /><a href='http://www.My Dashboard.com'>www.My Dashboard.com</a></div>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				<img style='border: 0pt none ; min-height: 1px; width: 1px;' alt=''/>";



	$ifsend = $this->send($to,$from,$subject,$message);
	if($ifsend == true){
		return true;
	}else{
	return false;
	}
}


#################################################
# Function Name:createNewAccount
# Parameter    :NA
# Return       :County
# D.O.M        :10-June-2010
#################################################
function createNewBusiness($vendorEmail,$vendorName,$bussinessName)
{
	$to = $vendorEmail;
	$from = 'admin@My Dashboard.com';
	$subject = 'Business Created';
	$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
				<tr>
				<td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>My Dashboard
				</td>
				</tr>
				<tr>
				<td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'>Hi ".$vendorName.",</div>
				<div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
				<table cellpadding='0' style='margin-top: 5px;'>
				<tr valign='top'>
				<td style='padding: 0px 3px 10px 0px;'>
					<FONT SIZE=2 FACE='Arial'>Your new Business is created <br />
					Your Business is: ".$bussinessName."<br />
					</FONT></P>
					<P><FONT SIZE=2 FACE='Arial'></FONT>
				</td>
				</tr>
				</table>
				<br/>
				<br/>
				<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
				<br/>
				</div>
				<div style='margin: 0pt;'>Thanks & Regards,<br/>
				Mydashboard Team<br />
				<a href='http://www.Mydashboard.com'>www.Mydashboard.com</a>
				</div>
				</td>
				<td align='left' width='150' valign='top' style='padding-left: 15px;'>
				<table cellspacing='0' cellpadding='0' width='100%'>
				<tr>
				<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
				<div style='margin-bottom: 15px;'><b>My Dashboard</b><br /><a href='http://www.My Dashboard.com'>www.My Dashboard.com</a></div>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				<img style='border: 0pt none ; min-height: 1px; width: 1px;' alt=''/>";



	$ifsend = $this->send($to,$from,$subject,$message);
	if($ifsend == true){
		return true;
	}else{
	return false;
	}
}

#################################################
# Function Name:createNewAccount
# Parameter    :NA
# Return       :County
# D.O.M        :10-June-2010
#################################################
function bookAppointmentUser($userEmail,$userName,$vendorService,$venSessionDetail)
{
	$to = $userEmail;
	$from = 'admin@My Dashboard.com';
	$subject = 'Appointment Booked';
	$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
				<tr>
				<td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>My Dashboard
				</td>
				</tr>
				<tr>
				<td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'>Hi ".$userName.",</div>
				<div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
				<table cellpadding='0' style='margin-top: 5px;'>
				<tr valign='top'>
				<td style='padding: 0px 3px 10px 0px;'>
					<FONT SIZE=2 FACE='Arial'>
					Your have book an appointments with the following Details:<br />
					Service : ".$vendorService['VendorService']['service_item']."<br />
					Service Description :".$vendorService['VendorService']['description']."<br />
					Teacher Details:".$vendorService['VendorService']['teacher_info']."<br/>
					Start Date:".date('Y-m-d',strtotime($venSessionDetail['VendorSession']['shedule_date']))."<br/>
					Duration:".$vendorService['VendorService']['duration']."<br/>
					Start Time:".$venSessionDetail['VendorSession']['start_time']."<br/>
					End Time:".$venSessionDetail['VendorSession']['end_time']."
					</FONT></P>
					<P><FONT SIZE=2 FACE='Arial'></FONT>
				</td>
				</tr>
				</table>
				<br/>
				<br/>
				<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
				<br/>
				</div>
				<div style='margin: 0pt;'>Thanks & Regards,<br/>
				Mydashboard Team<br />
				<a href='http://www.Mydashboard.com'>www.Mydashboard.com</a>
				</div>
				</td>
				<td align='left' width='150' valign='top' style='padding-left: 15px;'>
				<table cellspacing='0' cellpadding='0' width='100%'>
				<tr>
				<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
				<div style='margin-bottom: 15px;'><b>My Dashboard</b><br /><a href='http://www.My Dashboard.com'>www.My Dashboard.com</a></div>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				<img style='border: 0pt none ; min-height: 1px; width: 1px;' alt=''/>";



	$ifsend = $this->send($to,$from,$subject,$message);
	if($ifsend == true){
		return true;
	}else{
	return false;
	}
}


#################################################
# Function Name:createNewAccount
# Parameter    :NA
# Return       :County
# D.O.M        :10-June-2010
#################################################
function bookAppointmentVendor($vendorEmail,$userName,$vendorService,$venSessionDetail)
{
	$to = $vendorEmail;
	$from = 'admin@My Dashboard.com';
	$subject = 'Appointment Booked';
	$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
				<tr>
				<td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>My Dashboard
				</td>
				</tr>
				<tr>
				<td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'>Hi ".$vendorService['Vendor']['company'].",</div>
				<div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
				<table cellpadding='0' style='margin-top: 5px;'>
				<tr valign='top'>
				<td style='padding: 0px 3px 10px 0px;'>
					<FONT SIZE=2 FACE='Arial'>
					A new appointments is booked with in your service :<br />
					User :".$userName."<br />
					Service : ".$vendorService['VendorService']['service_item']."<br />
					Service Description :".$vendorService['VendorService']['description']."<br />
					Teacher Details:".$vendorService['VendorService']['teacher_info']."<br/>
					Start Date:".date('Y-m-d',strtotime($venSessionDetail['VendorSession']['shedule_date']))."<br/>
					Duration:".$vendorService['VendorService']['duration']."<br/>
					Start Time:".$venSessionDetail['VendorSession']['start_time']."<br/>
					End Time:".$venSessionDetail['VendorSession']['end_time']."
					</FONT></P>
					<P><FONT SIZE=2 FACE='Arial'></FONT>
				</td>
				</tr>
				</table>
				<br/>
				<br/>
				<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
				<br/>
				</div>
				<div style='margin: 0pt;'>Thanks & Regards,<br/>
				Mydashboard Team<br />
				<a href='http://www.Mydashboard.com'>www.Mydashboard.com</a>
				</div>
				</td>
				<td align='left' width='150' valign='top' style='padding-left: 15px;'>
				<table cellspacing='0' cellpadding='0' width='100%'>
				<tr>
				<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
				<div style='margin-bottom: 15px;'><b>My Dashboard</b><br /><a href='http://www.My Dashboard.com'>www.My Dashboard.com</a></div>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				<img style='border: 0pt none ; min-height: 1px; width: 1px;' alt=''/>";



	$ifsend = $this->send($to,$from,$subject,$message);
	if($ifsend == true){
		return true;
	}else{
	return false;
	}
}

#################################################
# Function Name:createNewAccount
# Parameter    :NA
# Return       :County
# D.O.M        :10-June-2010
#################################################
function appoinmentNotice($userName,$email,$serviceItem,$status)
{
	if($status=='Op')
		{
			$appoint = 'Open';
		}
		else if($status=='Cl')
		{
			$appoint = 'Close';
		}
		else if($status=='Re')
		{
			$appoint = 'Rejected';
		}
		else if($status=='Co')
		{
			$appoint = 'Confirmed';
		}
		else if($status=='D')
		{
			$appoint = 'Deleted';
		}
		else{
			$appoint = 'N/A';
		}

	$to = $email;
	$from = 'admin@My Dashboard.com';
	$subject = 'Appointment Notification';
	$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
				<tr>
				<td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>My Dashboard
				</td>
				</tr>
				<tr>
				<td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'>Hi ".$userName.",</div>
				<div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
				<table cellpadding='0' style='margin-top: 5px;'>
				<tr valign='top'>
				<td style='padding: 0px 3px 10px 0px;'>
					<FONT SIZE=2 FACE='Arial'>
					Vendor Appointment with the '" . $serviceItem . "' Service have changed status to the :".$appoint."<br />
					</FONT></P>
					<P><FONT SIZE=2 FACE='Arial'></FONT>
				</td>
				</tr>
				</table>
				<br/>
				<br/>
				<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
				<br/>
				</div>
				<div style='margin: 0pt;'>Thanks & Regards,<br/>
				Mydashboard Team<br />
				<a href='http://www.Mydashboard.com'>www.Mydashboard.com</a>
				</div>
				</td>
				<td align='left' width='150' valign='top' style='padding-left: 15px;'>
				<table cellspacing='0' cellpadding='0' width='100%'>
				<tr>
				<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
				<div style='margin-bottom: 15px;'><b>My Dashboard</b><br /><a href='http://www.My Dashboard.com'>www.My Dashboard.com</a></div>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				<img style='border: 0pt none ; min-height: 1px; width: 1px;' alt=''/>";



	$ifsend = $this->send($to,$from,$subject,$message);
	if($ifsend == true){
		return true;
	}else{
	return false;
	}

}


#################################################
# Function Name:mailToClient
# Parameter    :NA
# Return       :County
# D.O.M        :10-June-2010
#################################################
	function mailToClient($userEmail,$vendorEmail,$subjects,$detail)
	{
		$to = $userEmail;
		$from = $vendorEmail;
		$subject = $subjects;
		$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
					<tr>
					<td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>My Dashboard
					</td>
					</tr>
					<tr>
					<td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'>".$detail."</div>
					<br/>
					<br/>
					</td>
					<td align='left' width='150' valign='top' style='padding-left: 15px;'>
					<table cellspacing='0' cellpadding='0' width='100%'>
					<tr>
					<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
					<div style='margin-bottom: 15px;'><b>My Dashboard</b><br /><a href='http://www.My Dashboard.com'>www.My Dashboard.com</a></div>
					</td>
					</tr>
					</table>
					</td>
					</tr>
					</table>
					<img style='border: 0pt none ; min-height: 1px; width: 1px;' alt=''/>";

			$ifsend = $this->send($to,$from,$subject,$message);
			if($ifsend == true){
				return true;
			}else{
			return false;
			}
	}

#################################################
# Function Name:mailToClient
# Parameter    :NA
# Return       :County
# D.O.M        :10-June-2010
#################################################
	function mailToVendor($userEmail,$vendorEmail,$subjects,$detail)
	{
		$to = $vendorEmail;
		$from = 'admin@My Dashboard.com';
		$subject = 'Client Communication Notification';
		$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
					<tr>
					<td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>My Dashboard
					</td>
					</tr>
					<tr>
					<td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'>Hi Vendor,</div>
					<div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
					<table cellpadding='0' style='margin-top: 5px;'>
					<tr valign='top'>
					<td style='padding: 0px 3px 10px 0px;'>
						<FONT SIZE=2 FACE='Arial'>
						You communication message with the subject - '".$subjects."' send to the '".$userEmail."'
						<br />
						</FONT></P>
						<P><FONT SIZE=2 FACE='Arial'></FONT>
					</td>
					</tr>
					</table>
					<br/>
					<br/>
					<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
					<br/>
					</div>
					<div style='margin: 0pt;'>Thanks & Regards,<br/>
					Mydashboard Team<br />
					<a href='http://www.Mydashboard.com'>www.Mydashboard.com</a>
					</div>
					</td>
					<td align='left' width='150' valign='top' style='padding-left: 15px;'>
					<table cellspacing='0' cellpadding='0' width='100%'>
					<tr>
					<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
					<div style='margin-bottom: 15px;'><b>My Dashboard</b><br /><a href='http://www.My Dashboard.com'>www.My Dashboard.com</a></div>
					</td>
					</tr>
					</table>
					</td>
					</tr>
					</table>
					<img style='border: 0pt none ; min-height: 1px; width: 1px;' alt=''/>";

			$ifsend = $this->send($to,$from,$subject,$message);
			if($ifsend == true){
				return true;
			}else{
			return false;
			}
	}
        
       
        
        function contactoadmin($toadmin, $fromuser, $sub, $adminfname ,$ffusername, $msg)
        {
	$to = $toadmin;
	$adminname = $adminfname;
        $username = $ffusername;
	$from = $fromuser;
	$subject = $sub;
        $usermessage = $msg;
	$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
				<tr>
				<td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>My Dashboard
				</td>
				</tr>
				<tr>
				<td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'>Hi ".$adminname.",</div>
				<div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
				<table cellpadding='0' style='margin-top: 5px;'>
				<tr valign='top'>
				<td style='padding: 0px 3px 10px 0px;'>
					<FONT SIZE=2 FACE='Arial'> <br />
					 ".$usermessage." <br /><br /><br />
					
					
					</FONT></P>
					<P><FONT SIZE=2 FACE='Arial'></FONT>
				</td>
				</tr>
				</table>
				<br/>
				<br/>
				<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
				<br/>
				</div>
				<div style='margin: 0pt;'>Thanks & Regards,<br/>
				".$username."<br />
				
				</div>
				</td>
				<td align='left' width='150' valign='top' style='padding-left: 15px;'>
				<table cellspacing='0' cellpadding='0' width='100%'>
				<tr>
				<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
				<div style='margin-bottom: 15px;'><b>My Dashboard</b><br /><a href='http://www.My Dashboard.com'>www.My Dashboard.com</a></div>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				<img style='border: 0pt none ; min-height: 1px; width: 1px;' alt=''/>";
	$ifsend = $this->send($to,$from, $subject, $message, $path=null);
	if($ifsend == true){
	return true;
	}else{
	return false;
	}
}


 function contactouser($fromadmin,$touser,$sub,$adminfname,$ffusername,$msg)
        {
	$to = $touser;
	$username = $ffusername;
	$from = $fromadmin;
        $adminname = $adminfname;
	$subject = $sub;
        $usermessage = $msg;
	$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
				<tr>
				<td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>My Dashboard
				</td>
				</tr>
				<tr>
				<td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'>Hi ".$adminname.",</div>
				<div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
				<table cellpadding='0' style='margin-top: 5px;'>
				<tr valign='top'>
				<td style='padding: 0px 3px 10px 0px;'>
					<FONT SIZE=2 FACE='Arial'>".$usermessage."<br />
					
					
					</FONT></P>
					<P><FONT SIZE=2 FACE='Arial'></FONT>
				</td>
				</tr>
				</table>
				<br/>
				<br/>
				<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
				<br/>
				</div>
				<div style='margin: 0pt;'>Thanks & Regards,<br/>
				".$username."<br />
				
				</div>
				<br/>
				<br/>
				<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
				<br />
                                <tr valign='top'>
				<td style='padding: 0px 3px 10px 0px;'>
					<FONT SIZE=2 FACE='Arial'>Hello ".$username." <br/> Thanx For contacting us, <br /> We will contact you soon<br />
					
					
					</FONT></P>
					<P><FONT SIZE=2 FACE='Arial'></FONT>
				</td>
				<div style='margin: 0pt;'>Thanks & Regards,<br/>
				Mydashboard Team<br />
				<a href='http://www.Mydashboard.com'>www.Mydashboard.com</a>
				</div>
				</td>
				<td align='left' width='150' valign='top' style='padding-left: 15px;'>
				<table cellspacing='0' cellpadding='0' width='100%'>
				<tr>
				<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
				<div style='margin-bottom: 15px;'><b>My Dashboard</b><br /><a href='http://www.My Dashboard.com'>www.My Dashboard.com</a></div>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				<img style='border: 0pt none ; min-height: 1px; width: 1px;' alt=''/>";
	$ifsend = $this->send($to, $from, $subject, $message, $path=null);
	if($ifsend == true){
	return true;
	}else{
	return false;
	}
}


#################################################
# Function Name:userforgotpassword
# Parameter    :NA
# Return       :County
# D.O.M        :23-Nov-2010
#################################################
 function userforgotpassword($toemail, $addcc, $logininformation)
 {
/*	print_r($logininformation); exit;*/
		$to = $toemail;
		$cc = $addcc;
		$from = "sdnfacebook@gmail.com";

		$subject = 'Forgot Password';
		$message = "<table cellspacing='0' cellpadding='0' border='0' width='620'>
                            <tr>
                            <td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>My Dashboard
                            </td>
                            </tr>
                            <tr>
                            <td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' width='470' valign='top' style='font-size: 12px;'><div style='margin-bottom: 15px; font-size: 13px;'>Hi ".$logininformation['username'].",</div>
                            <div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
                            <table cellpadding='0' style='margin-top: 5px;'>
                            <tr valign='top'>
                            <td style='padding: 0px 3px 10px 0px;'>
                             <FONT SIZE=2 FACE='Arial'>Your account details are below:<br />
                             <u>Account Details : </u><br /><br />Username :   ".$logininformation['username']."<br />The New Password :  ".$logininformation['password']."</FONT></P>
                             <P><FONT SIZE=2 FACE='Arial'>Thanks for being a member of My Dashboard</FONT>
                            </td>
                            </tr>
                            </table>
                            <br>
				<br>
				<div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
				<br>
				</div>
				<div style='margin: 0pt;'>Thanks &amp; Regards,<br>My Dashboard<br>
				<a href='#'>www.Mydashboard.com</a>
				</div>
				</td>
				<td align='left' width='150' valign='top' style='padding-left: 15px;'>
				<table cellspacing='0' cellpadding='0' width='100%'>
				<tbody><tr>
				<td style='border: 1px solid rgb(255, 226, 34); padding: 10px; background-color: rgb(255, 248, 204); color: rgb(51, 51, 51); font-size: 12px;'>
				<div style='margin-bottom: 15px;'><b>My Dashboard</b><br><a href='#'>www.mydashboard.com</a></div>
				</td>
				</tr>
				</tbody></table>
				</td>
				</tr>
				</tbody></table>
				<img alt='' style='border: 0pt none; min-height: 1px; width: 1px;'>
				</td></tr></tbody></table>";
					//echo $message; exit;
					$ifsend = $this->send($to, $from, $subject, $message, $path=null);
					if($ifsend == true){
					  return true;
					}else{
					  return false;
					}
  }
  
  
          function send_faircity($recieverEmail=null, $senderEmail=null, $subject=null, $message=null,$path=null,$password,$fromName){
                    require_once('vendors/phpmailer/class.phpmailer.php');
                    $mail = new PHPMailer();
                    $mail->IsSMTP(); // telling the class to use SMTP
                    
                    $mail->SMTPAuth = true;
                    
//                    $mail->Host = "smtp.gmail.com";
//                    $mail->Port = 587;
//                    $mail->Username = "neema.tembhurnikar@gmail.com";
//                    $mail->Password = "cancerian1";
                    
                    $mail->Host = "mail2.dotnetwork2.co.za";
                    $mail->Port = 587;
                    $mail->Username = $senderEmail;
                    //$mail->Password = "Grm9anTsw!";
                    $mail->Password = $password;
                    
                    //$senderEmail = 'grm.roodevallei@faircity.co.za';
                    
                    $mail->From = $senderEmail;
                    //$mail->FromName = $senderEmail;
                    $mail->FromName = $fromName;
                    
                    //$mail->Sender = $recieverEmail;
                    if(is_array($recieverEmail))
                    {
                            foreach($recieverEmail as $key => $reciver)
                            {
                                    $mail->AddAddress($reciver);
                            }

                    }else{
                                    $mail->AddAddress($recieverEmail);
                             }
                    //$mail->AddCC($addcc);
                    $mail->Subject = $subject;
                    $mail->Body    = $message;
                    if(!empty($path))
                    {
                            $mail->AddAttachment($path);
                    }
                    $mail->WordWrap = 100;
                    $mail->IsHTML(true);

                    //echo '<pre>'; print_r($mail);
                    
                    if ($mail->Send()) {
                       // echo 'sent';
                            return true;
                    }else {
                       // echo 'not sent';
                            return false;
                    }
            }
  
  
}
?>