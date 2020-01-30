<?php
class SurveyUsersController extends AppController {

	var $name = 'SurveyUsers';
        //var $components = array('RequestHandler', 'Sendemail','Session','Email','Cookie','Export');
        var $components = array('Export','Sendemail');

	function beforeFilter() {
		parent::beforeFilter();
                $this->Auth->allow('view_survey');
                $this->Auth->allow('survey');
                $this->Auth->allow('thanks','completed_survey','getUserScores','download_detail');
        }
        
	function admin_index($client_id='83',$access_survey='0') {
                //Configure::write('debug',2);
		$this->SurveyUser->recursive = 0;
                if ($this->data['SurveyUser']['value']) {
                        $search = trim($this->data['SurveyUser']['value']);
                        $this->set('search',$search);
                        $condition = array(
                            'OR'  => array('SurveyUser.name LIKE' => "%$search%",'SurveyUser.email LIKE' => "%$search%"), 
                            'AND' => array('SurveyUser.client_id' => $client_id,'SurveyUser.access_survey'=>$access_survey,'SurveyUser.is_deleted'=>'0')
                      );
                } else {
                    $condition = array('SurveyUser.client_id' => $client_id,'SurveyUser.access_survey'=>$access_survey,'SurveyUser.is_deleted'=>'0');
                }
                $this->SurveyUser->recursive = 0;
		$this->paginate['conditions'] = $condition;
		$users = $this->paginate();
                
                $this->set('access_survey', $access_survey);
                $this->set('client_id', $client_id);
		$this->set('users', $users);
	}
        
        function admin_add($client_id=null,$id=null){
            if (!empty($this->data)) {
                    //echo '<pre>'; print_r($this->data); exit;
                
                if(empty($this->data['SurveyUser']['csv_file']['name']) && empty($this->data['SurveyUser']['excel_file']['name']) && empty($this->data['SurveyUser']['email'])){
                    $this->Session->setFlash(__('Please add guest details',true));
                    $this->redirect(array('action' => 'add',$this->data['SurveyUser']['client_id']));
                }
                
                
                if(empty($this->data['SurveyUser']['csv_file']['name']) && empty($this->data['SurveyUser']['excel_file']['name'])){
                          $this->SurveyUser->recursive = 0;
                          
//                          $checkEmailExists = $this->SurveyUser->find('first', array('conditions' => array('SurveyUser.email'=>$this->data['SurveyUser']['email'],'SurveyUser.client_id'=>$this->data['SurveyUser']['client_id'],'SurveyUser.access_survey'=>'0','SurveyUser.is_deleted'=>'0')));
//                          if(!empty($checkEmailExists)){
//                              $this->Session->setFlash(__('Guest Already Exists in this list',true));
//                              $this->redirect(array('action' => 'add',$this->data['SurveyUser']['client_id']));
//                           }
                        
                        if ($this->SurveyUser->save($this->data)) {
                            $this->Session->setFlash(__('Guest Addded successfully',true));
                            $this->redirect(array('action' => 'add',$this->data['SurveyUser']['client_id']));
                        } else {
                            $this->Session->setFlash(__('Problem saving data',true));
                        }
                }elseif(!empty($this->data['SurveyUser']['csv_file']['name'])){
                        //import csv file
                        
                        $path_parts = pathinfo($this->data['SurveyUser']['csv_file']["name"]);
                        $extension = $path_parts['extension'];
                        if ($extension != 'csv') {
                            $this->Session->setFlash(__('Please uploaded csv file!', true));
                            $this->redirect(array('action' => 'add',$this->data['SurveyUser']['client_id']));
                        }
                        $handle = fopen($this->data['SurveyUser']['csv_file']['tmp_name'], 'r');
                        if (!$handle) {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'add',$this->data['SurveyUser']['client_id']));
                        }
                       
                        $emailData = array();
                        while (($filedata = fgetcsv($handle, 1000, ",")) !== FALSE) {
                         
                          if(!empty($filedata[1])){
                              unset($emailData);
                              $emailData['SurveyUser']['id'] = '';
                              $emailData['SurveyUser']['name'] = @$filedata[0];
                              $emailData['SurveyUser']['email'] = $filedata[1];
                              $emailData['SurveyUser']['client_id'] = $this->data['SurveyUser']['client_id'];
                              
                              $this->SurveyUser->recursive = 0;
//                              $checkEmailExists = $this->SurveyUser->find('first', array('conditions' => array('SurveyUser.email'=>$filedata[1],'SurveyUser.client_id'=>$this->data['SurveyUser']['client_id'],'SurveyUser.access_survey'=>'0','SurveyUser.is_deleted'=>'0')));
//                              if(empty($checkEmailExists)){
//                                  $this->SurveyUser->save($emailData);
//                              }
                               $this->SurveyUser->save($emailData);
                          }
                        }
                        //exit;
                        $this->Session->setFlash(__('Guests Added successfully',true));
                        $this->redirect(array('action' => 'index',$this->data['SurveyUser']['client_id']));
                    }elseif(!empty($this->data['SurveyUser']['excel_file']['name'])){
                        
                        App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                        $wdata = new Spreadsheet_Excel_Reader($this->data['SurveyUser']['excel_file']['tmp_name'], true);
                        $ndata = $wdata->sheets;
                        
                        unset($ndata[0]['cells'][1]);
                        
                        foreach ($ndata[0]['cells'] as $data) {
                            if(!empty($data[5]) && (strstr($data[5],'@'))){
                              unset($emailData);
                              $emailData['SurveyUser']['id'] = '';
                              $emailData['SurveyUser']['name'] = @$data[1].' '.$data[2].' '.$data[3];
                              $emailData['SurveyUser']['email'] = $data[5];
                              $emailData['SurveyUser']['client_id'] = $this->data['SurveyUser']['client_id'];
                              
                              $this->SurveyUser->recursive = 0;
//                              $checkEmailExists = $this->SurveyUser->find('first', array('conditions' => array('SurveyUser.email'=>$data[12],'SurveyUser.client_id'=>$this->data['SurveyUser']['client_id'],'SurveyUser.access_survey'=>'0','SurveyUser.is_deleted'=>'0')));
//                              if(empty($checkEmailExists)){
//                                  $this->SurveyUser->save($emailData);
//                              }
                              $this->SurveyUser->save($emailData);
                          }
                        }
                        
                        $this->Session->setFlash(__('Guests Added successfully',true));
                        $this->redirect(array('action' => 'index',$this->data['SurveyUser']['client_id']));
                        
                    }
                
            }else{
                $this->set('client_id',$client_id);
                if(!empty($id)){
                    $this->data = $this->SurveyUser->read(null, $id);
                }else{
                    $this->data = array();
                }
                
            }
        }
        
        function admin_sent($id=null){
            $this->autoRender = false;
            $this->layout = '';
            
            $userDetails = $this->SurveyUser->read(null, $id);
            
            $guest_name = $userDetails['SurveyUser']['name'];
            $email = $userDetails['SurveyUser']['email'];
            $userId = $userDetails['SurveyUser']['id'];
            $clientId = $userDetails['SurveyUser']['client_id'];
            
            $this->Client = ClassRegistry::init('Client');
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$clientId),'fields'=>'Client.hotelname,Client.logo'));
            $hotelname = $client_name['Client']['hotelname'];
            $clienImage = $client_name['Client']['logo'];
            
            if(!empty ($clienImage)){
                $logo_url = "http://myrevenuedashboard.net/files/clientlogos/".$clienImage;   
            }else{
                $logo_url = "http://academy.revenue-performance.com/img/RP%20Square.jpg";
            }
            
            $email_from = "duncan.bramwell@revenue-performance.com"; // The email you are sending from (example)
            $email_subject = "Thank You for Staying ".$hotelname;
            
            $summary_table = "<p>Dear ".$guest_name."</p>";
            $summary_table .= "<p>Thank you for giving us the opportunity to host you at ".$hotelname."
                        We consistently strive to improve our personal service to you . We would be grateful if you would take a moment to complete this survey. Your response is valuable to us, and will receive immediate attention. 
                        </p>";
            $summary_table .= "<p><a href='http://myrevenuedashboard.net/SurveyUsers/survey/".$userId."' target='_blank'>Please click here to take our customer satisfaction survey.</a></p>";
            
            
            $email_message = "<table cellspacing='0' cellpadding='0' border='0' >
                <tr>
                <td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>".$hotelname."
                </td>
                </tr>
                <tr>
                <td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' valign='top' style='font-size: 12px;'>
                <div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
                <table cellpadding='0' style='margin-top: 5px;border:0;'>
                " . $summary_table . "
                </table>
                <br>
                        <br>
                        <div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
                        <br>
                        </div>
                        <div style='margin: 0pt;'>Thanks &amp; Regards,<br>".$hotelname."<br>
                        
                        </div>
                        </td>
                        <td align='left' width='150' valign='top' style='padding-left: 15px;'>
                        <table cellspacing='0' cellpadding='0' width='100%'>
                        <tbody><tr>
                        <td style='padding: 10px'>
                        <div style='margin-bottom: 15px;'>
                        <a target='blank' href='http://www.revenue-performance.com'>
                                <img src='".$logo_url."' alt='' style='border:0px;'>
                        </a>
                        </div>
                        </td>
                        </tr>
                        </tbody></table>
                        </td>
                        </tr>
                        </tbody></table>
                        <img alt='' style='border: 0pt none; min-height: 1px; width: 1px;'>
                        </td></tr></tbody></table>";
            
//            $headers = 'From: Revenue Performance<support@revenue-performance.com>' . "\r\n" .
//                                    'X-Mailer: PHP/' . phpversion();
//            //$headers = "From: $email_from"; // Who the email is from (example)
//            $headers .= "MIME-Version: 1.0" . "\r\n";
//            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                 
            if($clientId == '68' || $clientId == '67' || $clientId == '69' || $clientId == '70' || $clientId=='80' || $clientId =='81'){
                
                $from = 'grm.roodevallei@faircity.co.za';
                $password = 'Grm9anTsw!';
                $fromName = 'grm.roodevallei@faircity.co.za';
                
                if($clientId == '69' || $clientId == '80'){
                    $from = 'grm.quatermain@faircity.co.za';
                    $fromName = $from;
                }elseif($clientId == '67'){
                    $from = 'gr.mapungubwe@faircity.co.za';
                    $fromName = $from;
                }elseif($clientId == '81'){
                    $from = 'admin.roodevallei@faircity.co.za';
                    $password = 'FC9anTsw!70';
                    $fromName = 'Cristel Snyman';
                }elseif($clientId == '70'){
                    $from = 'admin.grosvenorgardens@faircity.co.za';
                    $password = 'FC9anTsw!75';
                    $fromName = 'Yolandie Jansen van Vuuren';
                }
                
                $addcc = '';
                $message = 'test';
                $subject = 'test subject';
                $result = $this->Sendemail->send_faircity($email, $from, $email_subject, $email_message,'',$password,$fromName);
                //echo '<pre>'; print_r($this->Sendemail); exit;
                
            }else{
                 $from = 'support@revenue-performance.com';
                 $result = $this->Sendemail->send($email, $from, $email_subject, $email_message,'');
            }
            
            
            if ($result) {
            //if (mail($email, $email_subject, $email_message, $headers)) {
                
                //$saveData['SurveyUser']['access_survey'] = '1';
                $saveData['SurveyUser']['id'] = $id;
                $saveData['SurveyUser']['survey_sent'] = '1';
                $saveData['SurveyUser']['survey_sent_on'] = date('Y-m-d H:i:s');
                $this->SurveyUser->save($saveData);
                
                $this->Session->setFlash(__('Survey Sent Successfully', true));
                
            } else {
                $this->Session->setFlash(__('Unable to Sent Survey. Please try again later', true));
            }
            $this->redirect(array('action' => 'index',$userDetails['SurveyUser']['client_id']));
            
        }

        
        public function survey($survey_user_id){
            //Configure::write('debug',2);
        
            $this->layout = false;
            
            $surveyUserDetails = $this->SurveyUser->read(null, $survey_user_id);
            
            if($surveyUserDetails['SurveyUser']['access_survey'] == '1'){
                $this->redirect(array('action' => 'thanks',$survey_user_id));
            }
            
            $guest_name = $surveyUserDetails['SurveyUser']['name'];
            $guest_email = $surveyUserDetails['SurveyUser']['email'];
            //$this->set('guest_name',$guest_name);
            $this->set('surveyUserDetails',$surveyUserDetails);
            
            $client_id = $surveyUserDetails['SurveyUser']['client_id'];
            $this->set('client_id',$client_id);
            
            $this->Client = ClassRegistry::init('Client');
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname,Client.logo'));
            $hotelname = $client_name['Client']['hotelname'];
            $clienImage = $client_name['Client']['logo'];
            $this->set('hotelname',$hotelname);
            $this->set('clienImage',$clienImage);

            
            $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
            $questions = $this->SurveyQuestion->find('all',array('conditions'=>array('SurveyQuestion.client_id'=>$client_id)));
            $this->set('questions',$questions);
            
            if(!empty($this->data)){
                //echo '<pre>'; print_r($this->data); exit;
                
                 foreach ($this->data['SurveyAnswer']['survey_question_id'] as $key => $survey_que){
                     $answers['SurveyAnswer']['id'] = '';
                     $answers['SurveyAnswer']['survey_user_id'] = $this->data['SurveyAnswer']['survey_user_id'];
                     $answers['SurveyAnswer']['survey_question_id'] = $survey_que;
                     $answers['SurveyAnswer']['score'] = @$this->data['SurveyAnswer']['score'][$key];
                     $answers['SurveyAnswer']['contents'] = @$this->data['SurveyAnswer']['contents'][$key];
                     
                     $this->SurveyAnswer = ClassRegistry::init('SurveyAnswer');
                     $this->SurveyAnswer->save($answers);
                 }
                 
                $saveData['SurveyUser']['access_survey'] = $this->data['SurveyAnswer']['survey_user_id'];
                $saveData['SurveyUser']['id'] = $this->data['SurveyAnswer']['survey_user_id'];
                $saveData['SurveyUser']['survey_completed_on'] = date('Y-m-d H:i:s');
                $this->SurveyUser->save($saveData);
                
                $this->redirect(array('action' => 'thanks',$this->data['SurveyAnswer']['survey_user_id']));
            }
        }
        
        public function view_survey($client_id){
            $this->layout = false;
            $this->set('client_id',$client_id);
            
            $this->Client = ClassRegistry::init('Client');
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname,Client.logo'));
            $hotelname = $client_name['Client']['hotelname'];
            $clienImage = $client_name['Client']['logo'];
            $this->set('hotelname',$hotelname);
            $this->set('clienImage',$clienImage);
            
            $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
            $questions = $this->SurveyQuestion->find('all',array('conditions'=>array('SurveyQuestion.client_id'=>$client_id)));
            $this->set('questions',$questions);
            
        }
        
        public function thanks($survey_user_id){
            $this->layout = false;
            
            $surveyUserDetails = $this->SurveyUser->read(null, $survey_user_id);
            $client_id = $surveyUserDetails['SurveyUser']['client_id'];
            
            $this->Client = ClassRegistry::init('Client');
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname,Client.logo'));
            $hotelname = $client_name['Client']['hotelname'];
            $clienImage = $client_name['Client']['logo'];
            $this->set('hotelname',$hotelname);
            $this->set('clienImage',$clienImage);
            $this->set('client_id',$client_id);
            
        }
        
          public function completed_survey($survey_user_id){
        
            $this->layout = false;
            
            $surveyUserDetails = $this->SurveyUser->read(null, $survey_user_id);
            $guest_name = $surveyUserDetails['SurveyUser']['name'];
            $guest_email = $surveyUserDetails['SurveyUser']['email'];
            $this->set('surveyUserDetails',$surveyUserDetails);
            
            $client_id = $surveyUserDetails['SurveyUser']['client_id'];
            $this->set('client_id',$client_id);
            
            $this->Client = ClassRegistry::init('Client');
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname,Client.logo'));
            $hotelname = $client_name['Client']['hotelname'];
            $clienImage = $client_name['Client']['logo'];
            $this->set('hotelname',$hotelname);
            $this->set('clienImage',$clienImage);
            
            $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
            $questions = $this->SurveyQuestion->find('all',array('conditions'=>array('SurveyQuestion.client_id'=>$client_id)));
            $this->set('questions',$questions);
            
            $this->SurveyAnswer = ClassRegistry::init('SurveyAnswer');
            $answers = $this->SurveyAnswer->find('all',array('conditions'=>array('SurveyAnswer.survey_user_id'=>$survey_user_id)));
            $this->set('answers',$answers);
         
        }
  
        function admin_delete($id = null) {
                $refUrl = $this->referer();
                
                $surveyUser['SurveyUser']['id'] = $id;
                $surveyUser['SurveyUser']['is_deleted'] = '1';
                
                if ($this->SurveyUser->save($surveyUser)) {
		//if ($this->SurveyUser->delete($id)) {
			$this->Session->setFlash(__('Guest deleted', true));
                }else{
                    $this->Session->setFlash(__('Guest was not deleted', true));
                }
                $this->redirect($refUrl);
	}
        
        
        function admin_sentall($clientId){
            $this->autoRender = false;
            $this->layout = '';

            $this->Client = ClassRegistry::init('Client');
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$clientId),'fields'=>'Client.hotelname,Client.logo'));
            $hotelname = $client_name['Client']['hotelname'];
            $clienImage = $client_name['Client']['logo'];

            if(!empty ($clienImage)){
                $logo_url = "http://myrevenuedashboard.net/files/clientlogos/".$clienImage;
            }else{
                $logo_url = "http://academy.revenue-performance.com/img/RP%20Square.jpg";
            }

            $email_from = "duncan.bramwell@revenue-performance.com"; // The email you are sending from (example)
            $email_subject = "Thank You for Staying ".$hotelname;
            $email_message1 = "<table cellspacing='0' cellpadding='0' border='0' >
                <tr>
                <td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>".$hotelname."
                </td>
                </tr>
                <tr>
                <td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' valign='top' style='font-size: 12px;'>
                <div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
                <table cellpadding='0' style='margin-top: 5px;border:0;'>";
            $email_message2 = "</table>
                        <br><br>
                        <div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
                        <br></div>
                        <div style='margin: 0pt;'>Thanks &amp; Regards,<br>".$hotelname."<br>
                        </div>
                        </td>
                        <td align='left' width='150' valign='top' style='padding-left: 15px;'>
                        <table cellspacing='0' cellpadding='0' width='100%'>
                        <tbody><tr>
                        <td style='padding: 10px'>
                        <div style='margin-bottom: 15px;'>
                        <a target='blank' href='http://www.revenue-performance.com'>
                                <img src='".$logo_url."' alt='' style='border:0px;'>
                        </a>
                        </div>
                        </td>
                        </tr>
                        </tbody></table>
                        </td>
                        </tr>
                        </tbody></table>
                        <img alt='' style='border: 0pt none; min-height: 1px; width: 1px;'>
                        </td></tr></tbody></table>";

//            $headers = 'From: Revenue Performance<support@revenue-performance.com>' . "\r\n" .
//                                    'X-Mailer: PHP/' . phpversion();
//            $headers .= "MIME-Version: 1.0" . "\r\n";
//            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

            //$all_guests = $this->SurveyUser->find('all', array('conditions' => array('SurveyUser.client_id'=>$clientId,'SurveyUser.access_survey'=>'0','SurveyUser.survey_sent'=>'0','SurveyUser.is_deleted'=>'0')));
            $all_guests = $this->SurveyUser->find('all', array('conditions' => array('SurveyUser.client_id'=>$clientId,'SurveyUser.access_survey'=>'0','SurveyUser.is_deleted'=>'0')));

            foreach($all_guests as $userDetails){
                //$userDetails = $this->SurveyUser->read(null, $id);

                $guest_name = $userDetails['SurveyUser']['name'];
                $email = $userDetails['SurveyUser']['email'];
                $userId = $userDetails['SurveyUser']['id'];
                //$clientId = $userDetails['SurveyUser']['client_id'];

                $summary_table = "<p>Dear ".$guest_name."</p>";
                $summary_table .= "<p>Thank you for giving us the opportunity to host you at ".$hotelname."
                            We consistently strive to improve our personal service to you . We would be grateful if you would take a moment to complete this survey. Your response is valuable to us, and will receive immediate attention. 
                            </p>";
                $summary_table .= "<p><a href='http://myrevenuedashboard.net/SurveyUsers/survey/".$userId."' target='_blank'>Please click here to take our customer satisfaction survey.</a></p>";

                $email_message =  $email_message1. $summary_table . $email_message2;

                if($clientId == '68' || $clientId == '67' || $clientId == '69' || $clientId == '70' || $clientId=='80' || $clientId =='81'){
                
                    $from = 'grm.roodevallei@faircity.co.za';
                    if($clientId == '69' || $clientId == '80'){
                        $from = 'grm.quatermain@faircity.co.za';
                    }elseif($clientId == '67'){
                        $from = 'gr.mapungubwe@faircity.co.za';
                    }elseif($clientId == '81'){
                        $from = 'grm.roodevallei@faircity.co.za';
                    }

                    $addcc = '';
                    $message = 'test';
                    $subject = 'test subject';
                    $result = $this->Sendemail->send_faircity($email, $from, $email_subject, $email_message,'');
                    //echo '<pre>'; print_r($this->Sendemail); exit;

                }else{
                     $from = 'support@revenue-performance.com';
                     $result = $this->Sendemail->send($email, $from, $email_subject, $email_message,'');
                }
            
            
             if ($result) {
                //if(mail($email, $email_subject, $email_message, $headers)){
                    //$saveData['SurveyUser']['access_survey'] = '1';
                    $saveData['SurveyUser']['id'] = $userId;
                    $saveData['SurveyUser']['survey_sent'] = '1';
                    $saveData['SurveyUser']['survey_sent_on'] = date('Y-m-d H:i:s');
                    
                    $this->SurveyUser->save($saveData);
                }
            }
            //exit;

            $this->Session->setFlash(__('Survey Sent Successfully', true));
            $this->redirect(array('action' => 'index',$clientId));            
        }

        
        function admin_reports($client_id){
            $this->set('client_id',$client_id);
            if(!empty($this->data)){
                //echo '<pre>'; print_r($this->data); exit;
                $this->redirect('/SurveyUsers/download_detail/'.$this->data['SurveyUser']['client_id'].'/'.$this->data['SurveyUser']['field1'].'/'.$this->data['SurveyUser']['value1']);
                
            }
        }
         function client_reports($client_id){
            $this->set('client_id',$client_id);
            if(!empty($this->data)){
                //echo '<pre>'; print_r($this->data); exit;
                $this->redirect('/SurveyUsers/download_detail/'.$this->data['SurveyUser']['client_id'].'/'.$this->data['SurveyUser']['field1'].'/'.$this->data['SurveyUser']['value1']);
                
            }
        }
        
        function admin_download($client_id,$start_date,$end_date){
            $all_guests = $this->SurveyUser->find('all', array('conditions' => array('SurveyUser.client_id'=>$clientId,'SurveyUser.access_survey'=>'1','SurveyUser.survey_sent'=>'1','SurveyUser.is_deleted'=>'0')));
            
            $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
            $questions = $this->SurveyQuestion->find('all',array('conditions'=>array('SurveyQuestion.client_id'=>$client_id)));
            
            foreach($all_guests as $userDetails){
                    
            }
        }
        
        function download_detail($client_id,$start_date=null,$end_date=null){
            //Configure::write('debug',2);
            //$this->autoRender = false;
            //$this->layout = '';
            
            //$all_guests = $this->SurveyUser->find('all', array('conditions' => array('SurveyUser.client_id'=>$client_id,'SurveyUser.access_survey'=>'1','SurveyUser.survey_sent'=>'1','SurveyUser.is_deleted'=>'0')));
            if(!empty ($start_date) && !empty($end_date)){
                $new_start_date = $start_date. ' 00:00:00';
                $new_end_date = $end_date. ' 23:00:00';
                $all_guests = $this->SurveyUser->find('all', array('conditions' => array(
                    'SurveyUser.client_id'=>$client_id,
                    'SurveyUser.access_survey'=>'1',
                    'SurveyUser.is_deleted'=>'0',
                    'SurveyUser.survey_completed_on BETWEEN ? AND ?' => array($new_start_date, $new_end_date)
                    )));
            }else{
                $all_guests = $this->SurveyUser->find('all', array('conditions' => array(
                    'SurveyUser.client_id'=>$client_id,
                    'SurveyUser.access_survey'=>'1',
                    'SurveyUser.is_deleted'=>'0'
                    )));
            }
            //echo '<pre>'; print_r($all_guests); exit;
            
            $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
            $questions = $this->SurveyQuestion->find('list',array('conditions'=>array('SurveyQuestion.client_id'=>$client_id),'fields' => array('SurveyQuestion.id', 'SurveyQuestion.title')));
            //$this->set('questions',$questions);
            
            //echo '<pre>'; 
            //print_r($questions); 
            //print_r($all_guests);
            
            $final_columns = array();
            $final_columns[0] = "Guest Name";
            $final_columns[1] = "Guest Email";
            $final_columns[2] = "Question Title";
            $final_columns[3] = "Score";
            $final_columns[4] = "Contents";
            
            $rest_values = array();
            $rest_values[0] = $final_columns;
            
            
            //$this->set('all_guests',$all_guests);
            $i = '1';
            $question_score_arr = array();
            foreach($all_guests as $userDetails){
                $this->SurveyAnswer = ClassRegistry::init('SurveyAnswer');
                $survey_user_id = $userDetails['SurveyUser']['id'];
                $answers = $this->SurveyAnswer->find('all',array('conditions'=>array('SurveyAnswer.survey_user_id'=>$survey_user_id)));
                
                $rest_values[$i][] = $userDetails['SurveyUser']['name'];
                $rest_values[$i][] = $userDetails['SurveyUser']['email'];
                $rest_values[$i][] = '';
                $rest_values[$i][] = '';
                $rest_values[$i][] = '';
                $i++;
                
                foreach($answers as $ans){
                    $rest_values[$i][] = '';
                    $rest_values[$i][] = '';
                    $rest_values[$i][] = $questions[$ans['SurveyAnswer']['survey_question_id']];
                    $rest_values[$i][] = $ans['SurveyAnswer']['score'];
                    $rest_values[$i][] = $ans['SurveyAnswer']['contents'];
                    $i++;
                    if(!empty($ans['SurveyAnswer']['score'])){
                        $question_score_arr[$questions[$ans['SurveyAnswer']['survey_question_id']]][] = $ans['SurveyAnswer']['score'];
                    }
                }
                
                $rest_values[$i][] = '';
                $rest_values[$i][] = '';
                $rest_values[$i][] = '';
                $rest_values[$i][] = '';
                $rest_values[$i][] = '';
                $i++;
            }
            
                $rest_values[$i][] = 'Average';
                $rest_values[$i][] = '';
                $rest_values[$i][] = '';
                $rest_values[$i][] = '';
                $rest_values[$i][] = '';
                $i++;
                foreach($question_score_arr as $que_key => $que_avg_score){
                    $rest_values[$i][] = '';
                    $rest_values[$i][] = '';
                    $rest_values[$i][] = $que_key;
                    $rest_values[$i][] = round(array_sum($que_avg_score)/count($que_avg_score),'2');
                    $rest_values[$i][] = '';
                    $i++;
                }
                
                
            
            $this->Export->download1($rest_values, 'csv');
            
            //$this->set('answers',$answers);
            
        }
        
        function client_index($client_id=null,$access_survey='0') {
                //Configure::write('debug',2);
		$this->SurveyUser->recursive = 0;
                if ($this->data['SurveyUser']['value']) {
                        $search = trim($this->data['SurveyUser']['value']);
                        $this->set('search',$search);
                        $condition = array(
                            'OR'  => array('SurveyUser.name LIKE' => "%$search%",'SurveyUser.email LIKE' => "%$search%"), 
                            'AND' => array('SurveyUser.client_id' => $client_id,'SurveyUser.access_survey'=>$access_survey,'SurveyUser.is_deleted'=>'0')
                      );
                } else {
                    $condition = array('SurveyUser.client_id' => $client_id,'SurveyUser.access_survey'=>$access_survey,'SurveyUser.is_deleted'=>'0');
                }
                $this->SurveyUser->recursive = 0;
		$this->paginate['conditions'] = $condition;
		$users = $this->paginate();
                
                $this->set('access_survey', $access_survey);
                $this->set('client_id', $client_id);
		$this->set('users', $users);
	}
        
        function client_add($client_id=null,$id=null){
            if (!empty($this->data)) {
        
                if(empty($this->data['SurveyUser']['csv_file']['name']) && empty($this->data['SurveyUser']['excel_file']['name']) && empty($this->data['SurveyUser']['email'])){
                    $this->Session->setFlash(__('Please add guest details',true));
                    $this->redirect(array('action' => 'add',$this->data['SurveyUser']['client_id']));
                }
                
                if(empty($this->data['SurveyUser']['csv_file']['name']) && empty($this->data['SurveyUser']['excel_file']['name'])){
                          $this->SurveyUser->recursive = 0;
//                          $checkEmailExists = $this->SurveyUser->find('first', array('conditions' => array('SurveyUser.email'=>$this->data['SurveyUser']['email'],'SurveyUser.client_id'=>$this->data['SurveyUser']['client_id'],'SurveyUser.access_survey'=>'0','SurveyUser.is_deleted'=>'0')));
//                          if(!empty($checkEmailExists)){
//                              $this->Session->setFlash(__('Guest Already Exists in this list',true));
//                              $this->redirect(array('action' => 'add',$this->data['SurveyUser']['client_id']));
//                           }
                        if ($this->SurveyUser->save($this->data)) {
                            $this->Session->setFlash(__('Guest Addded successfully',true));
                            $this->redirect(array('action' => 'add',$this->data['SurveyUser']['client_id']));
                        } else {
                            $this->Session->setFlash(__('Problem saving data',true));
                        }
                }elseif(!empty($this->data['SurveyUser']['csv_file']['name'])){
                        //import csv file
                        
                        $path_parts = pathinfo($this->data['SurveyUser']['csv_file']["name"]);
                        $extension = $path_parts['extension'];
                        if ($extension != 'csv') {
                            $this->Session->setFlash(__('Please uploaded csv file!', true));
                            $this->redirect(array('action' => 'add',$this->data['SurveyUser']['client_id']));
                        }
                        $handle = fopen($this->data['SurveyUser']['csv_file']['tmp_name'], 'r');
                        if (!$handle) {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'add',$this->data['SurveyUser']['client_id']));
                        }
                       
                        $emailData = array();
                        while (($filedata = fgetcsv($handle, 1000, ",")) !== FALSE) {
                         
                          if(!empty($filedata[1])){
                              unset($emailData);
                              $emailData['SurveyUser']['id'] = '';
                              $emailData['SurveyUser']['name'] = @$filedata[0];
                              $emailData['SurveyUser']['email'] = $filedata[1];
                              $emailData['SurveyUser']['client_id'] = $this->data['SurveyUser']['client_id'];
                              
                              $this->SurveyUser->recursive = 0;
//                              $checkEmailExists = $this->SurveyUser->find('first', array('conditions' => array('SurveyUser.email'=>$filedata[1],'SurveyUser.client_id'=>$this->data['SurveyUser']['client_id'],'SurveyUser.access_survey'=>'0','SurveyUser.is_deleted'=>'0')));
//                              if(empty($checkEmailExists)){
//                                  $this->SurveyUser->save($emailData);
//                              }
                              $this->SurveyUser->save($emailData);
                          }
                        }
                        //exit;
                        $this->Session->setFlash(__('Guests Added successfully',true));
                        $this->redirect(array('action' => 'index',$this->data['SurveyUser']['client_id']));
                    }elseif(!empty($this->data['SurveyUser']['excel_file']['name'])){
                        
                        App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                        $wdata = new Spreadsheet_Excel_Reader($this->data['SurveyUser']['excel_file']['tmp_name'], true);
                        $ndata = $wdata->sheets;
                        //echo '<pre>'; print_r($ndata); exit;
                        
                        unset($ndata[0]['cells'][1]);
                        
                        foreach ($ndata[0]['cells'] as $data) {
                            if(!empty($data[5]) && (strstr($data[5],'@'))){
                              unset($emailData);
                              $emailData['SurveyUser']['id'] = '';
                              $emailData['SurveyUser']['name'] = @$data[1].' '.$data[2].' '.$data[3];
                              $emailData['SurveyUser']['email'] = $data[5];
                              $emailData['SurveyUser']['client_id'] = $this->data['SurveyUser']['client_id'];
                              
                              $this->SurveyUser->recursive = 0;
//                              $checkEmailExists = $this->SurveyUser->find('first', array('conditions' => array('SurveyUser.email'=>$data[12],'SurveyUser.client_id'=>$this->data['SurveyUser']['client_id'],'SurveyUser.access_survey'=>'0','SurveyUser.is_deleted'=>'0')));
//                              if(empty($checkEmailExists)){
//                                  $this->SurveyUser->save($emailData);
//                              }
                              $this->SurveyUser->save($emailData);
                          }
                        }
                        
                        $this->Session->setFlash(__('Guests Added successfully',true));
                        $this->redirect(array('action' => 'index',$this->data['SurveyUser']['client_id']));
                        
                    }
                
            }else{
                $this->set('client_id',$client_id);
                if(!empty($id)){
                    $this->data = $this->SurveyUser->read(null, $id);
                }else{
                    $this->data = array();
                }
            }
        }
        
        function client_sent($id=null){
            $this->autoRender = false;
            $this->layout = '';
            
            $userDetails = $this->SurveyUser->read(null, $id);
            
            $guest_name = $userDetails['SurveyUser']['name'];
            $email = $userDetails['SurveyUser']['email'];
            $userId = $userDetails['SurveyUser']['id'];
            $clientId = $userDetails['SurveyUser']['client_id'];
            
            $this->Client = ClassRegistry::init('Client');
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$clientId),'fields'=>'Client.hotelname,Client.logo'));
            $hotelname = $client_name['Client']['hotelname'];
            $clienImage = $client_name['Client']['logo'];
            
            if(!empty ($clienImage)){
                $logo_url = "http://myrevenuedashboard.net/files/clientlogos/".$clienImage;   
            }else{
                $logo_url = "http://academy.revenue-performance.com/img/RP%20Square.jpg";
            }
            
            $email_from = "duncan.bramwell@revenue-performance.com"; // The email you are sending from (example)
            $email_subject = "Thank You for Staying ".$hotelname;
            
            $summary_table = "<p>Dear ".$guest_name."</p>";
            $summary_table .= "<p>Thank you for giving us the opportunity to host you at ".$hotelname."
                        We consistently strive to improve our personal service to you . We would be grateful if you would take a moment to complete this survey. Your response is valuable to us, and will receive immediate attention. 
                        </p>";
            $summary_table .= "<p><a href='http://myrevenuedashboard.net/SurveyUsers/survey/".$userId."' target='_blank'>Please click here to take our customer satisfaction survey.</a></p>";
            
            
            $email_message = "<table cellspacing='0' cellpadding='0' border='0' >
                <tr>
                <td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>".$hotelname."
                </td>
                </tr>
                <tr>
                <td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' valign='top' style='font-size: 12px;'>
                <div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
                <table cellpadding='0' style='margin-top: 5px;border:0;'>
                " . $summary_table . "
                </table>
                <br>
                        <br>
                        <div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
                        <br>
                        </div>
                        <div style='margin: 0pt;'>Thanks &amp; Regards,<br>".$hotelname."<br>
                        
                        </div>
                        </td>
                        <td align='left' width='150' valign='top' style='padding-left: 15px;'>
                        <table cellspacing='0' cellpadding='0' width='100%'>
                        <tbody><tr>
                        <td style='padding: 10px'>
                        <div style='margin-bottom: 15px;'>
                        <a target='blank' href='http://www.revenue-performance.com'>
                                <img src='".$logo_url."' alt='' style='border:0px;'>
                        </a>
                        </div>
                        </td>
                        </tr>
                        </tbody></table>
                        </td>
                        </tr>
                        </tbody></table>
                        <img alt='' style='border: 0pt none; min-height: 1px; width: 1px;'>
                        </td></tr></tbody></table>";
            
//            $headers = 'From: Revenue Performance<support@revenue-performance.com>' . "\r\n" .
//                                    'X-Mailer: PHP/' . phpversion();
//            //$headers = "From: $email_from"; // Who the email is from (example)
//            $headers .= "MIME-Version: 1.0" . "\r\n";
//            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
              
            
            if($clientId == '68' || $clientId == '67' || $clientId == '69' || $clientId == '70' || $clientId=='80' || $clientId =='81'){
                
                $from = 'grm.roodevallei@faircity.co.za';
                if($clientId == '69' || $clientId == '80'){
                    $from = 'grm.quatermain@faircity.co.za';
                }elseif($clientId == '67'){
                    $from = 'gr.mapungubwe@faircity.co.za';
                }elseif($clientId == '81'){
                    $from = 'grm.roodevallei@faircity.co.za';
                }
                
                $addcc = '';
                $message = 'test';
                $subject = 'test subject';
                $result = $this->Sendemail->send_faircity($email, $from, $email_subject, $email_message,'');
                //echo '<pre>'; print_r($this->Sendemail); exit;
                
            }else{
                 $from = 'support@revenue-performance.com';
                 $result = $this->Sendemail->send($email, $from, $email_subject, $email_message,'');
            }
            
            
            if ($result) {
            //if (mail($email, $email_subject, $email_message, $headers)) {
                
                //$saveData['SurveyUser']['access_survey'] = '1';
                $saveData['SurveyUser']['id'] = $id;
                $saveData['SurveyUser']['survey_sent'] = '1';
                $saveData['SurveyUser']['survey_sent_on'] = date('Y-m-d H:i:s');
                $this->SurveyUser->save($saveData);
                
                $this->Session->setFlash(__('Survey Sent Successfully', true));
                
            } else {
                $this->Session->setFlash(__('Unable to Sent Survey. Please try again later', true));
            }
            $this->redirect(array('action' => 'index',$userDetails['SurveyUser']['client_id']));
            
        }
        
        function client_delete($id = null) {
                $refUrl = $this->referer();
                
                $surveyUser['SurveyUser']['id'] = $id;
                $surveyUser['SurveyUser']['is_deleted'] = '1';
                
                if ($this->SurveyUser->save($surveyUser)) {
		//if ($this->SurveyUser->delete($id)) {
			$this->Session->setFlash(__('Guest deleted', true));
                }else{
                    $this->Session->setFlash(__('Guest was not deleted', true));
                }
                $this->redirect($refUrl);
	}
        
        
        function client_sentall($clientId){
            $this->autoRender = false;
            $this->layout = '';

            $this->Client = ClassRegistry::init('Client');
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$clientId),'fields'=>'Client.hotelname,Client.logo'));
            $hotelname = $client_name['Client']['hotelname'];
            $clienImage = $client_name['Client']['logo'];

            if(!empty ($clienImage)){
                $logo_url = "http://myrevenuedashboard.net/files/clientlogos/".$clienImage;
            }else{
                $logo_url = "http://academy.revenue-performance.com/img/RP%20Square.jpg";
            }

            $email_from = "duncan.bramwell@revenue-performance.com"; // The email you are sending from (example)
            $email_subject = "Thank You for Staying ".$hotelname;
            $email_message1 = "<table cellspacing='0' cellpadding='0' border='0' >
                <tr>
                <td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>".$hotelname."
                </td>
                </tr>
                <tr>
                <td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' valign='top' style='font-size: 12px;'>
                <div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
                <table cellpadding='0' style='margin-top: 5px;border:0;'>";
            $email_message2 = "</table>
                        <br><br>
                        <div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
                        <br></div>
                        <div style='margin: 0pt;'>Thanks &amp; Regards,<br>".$hotelname."<br>
                        </div>
                        </td>
                        <td align='left' width='150' valign='top' style='padding-left: 15px;'>
                        <table cellspacing='0' cellpadding='0' width='100%'>
                        <tbody><tr>
                        <td style='padding: 10px'>
                        <div style='margin-bottom: 15px;'>
                        <a target='blank' href='http://www.revenue-performance.com'>
                                <img src='".$logo_url."' alt='' style='border:0px;'>
                        </a>
                        </div>
                        </td>
                        </tr>
                        </tbody></table>
                        </td>
                        </tr>
                        </tbody></table>
                        <img alt='' style='border: 0pt none; min-height: 1px; width: 1px;'>
                        </td></tr></tbody></table>";

//            $headers = 'From: Revenue Performance<support@revenue-performance.com>' . "\r\n" .
//                                    'X-Mailer: PHP/' . phpversion();
//            $headers .= "MIME-Version: 1.0" . "\r\n";
//            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

            //$all_guests = $this->SurveyUser->find('all', array('conditions' => array('SurveyUser.client_id'=>$clientId,'SurveyUser.access_survey'=>'0','SurveyUser.survey_sent'=>'0','SurveyUser.is_deleted'=>'0')));
            $all_guests = $this->SurveyUser->find('all', array('conditions' => array('SurveyUser.client_id'=>$clientId,'SurveyUser.access_survey'=>'0','SurveyUser.is_deleted'=>'0')));
            
            foreach($all_guests as $userDetails){
                //$userDetails = $this->SurveyUser->read(null, $id);

                $guest_name = $userDetails['SurveyUser']['name'];
                $email = $userDetails['SurveyUser']['email'];
                $userId = $userDetails['SurveyUser']['id'];
                //$clientId = $userDetails['SurveyUser']['client_id'];

                $summary_table = "<p>Dear ".$guest_name."</p>";
                $summary_table .= "<p>Thank you for giving us the opportunity to host you at ".$hotelname."
                            We consistently strive to improve our personal service to you . We would be grateful if you would take a moment to complete this survey. Your response is valuable to us, and will receive immediate attention. 
                            </p>";
                $summary_table .= "<p><a href='http://myrevenuedashboard.net/SurveyUsers/survey/".$userId."' target='_blank'>Please click here to take our customer satisfaction survey.</a></p>";

                $email_message =  $email_message1. $summary_table . $email_message2;

                if($clientId == '68' || $clientId == '67' || $clientId == '69' || $clientId == '70' || $clientId=='80' || $clientId =='81'){
                
                $from = 'grm.roodevallei@faircity.co.za';
                if($clientId == '69' || $clientId == '80'){
                    $from = 'grm.quatermain@faircity.co.za';
                }elseif($clientId == '67'){
                    $from = 'gr.mapungubwe@faircity.co.za';
                }elseif($clientId == '81'){
                    $from = 'grm.roodevallei@faircity.co.za';
                }
                
                $addcc = '';
                $message = 'test';
                $subject = 'test subject';
                $result = $this->Sendemail->send_faircity($email, $from, $email_subject, $email_message,'');
                //echo '<pre>'; print_r($this->Sendemail); exit;
                
            }else{
                 $from = 'support@revenue-performance.com';
                 $result = $this->Sendemail->send($email, $from, $email_subject, $email_message,'');
            }
            
            
            if ($result) {
                //if(mail($email, $email_subject, $email_message, $headers)){
                    //$saveData['SurveyUser']['access_survey'] = '1';
                    $saveData['SurveyUser']['id'] = $userId;
                    $saveData['SurveyUser']['survey_sent'] = '1';
                    $saveData['SurveyUser']['survey_sent_on'] = date('Y-m-d H:i:s');
                    
                    $this->SurveyUser->save($saveData);
                }
            }
            //exit;

            $this->Session->setFlash(__('Survey Sent Successfully', true));
            $this->redirect(array('action' => 'index',$clientId));            
        }

        
        function getUserScores($survey_user_id){
            $this->autoRender = false;
            $this->layout = false;

            $surveyUserDetails = $this->SurveyUser->read(null, $survey_user_id);
            
            if($surveyUserDetails['SurveyUser']['access_survey'] != '1'){
                $answer_sum_score = 'NA';
            }
            $this->SurveyAnswer = ClassRegistry::init('SurveyAnswer');
            $survey_answer = $this->SurveyAnswer->find('all', array('fields' => array('sum(SurveyAnswer.score ) AS ctotal'), 'conditions'=>array('SurveyAnswer.survey_user_id'=>$survey_user_id),'recursive'=>'0'));
            $answer_sum_score = $survey_answer['0']['0']['ctotal'];
            return $answer_sum_score;
        }
        
        
}
?>