<?php
class WebservicesController extends AppController {

	//var $name = '';
	var $helpers = array();
	var $components = array('Export','Sendemail','Email');
        var $uses = array();

	function beforeFilter() {
            Configure::write('debug',0);
		//parent::beforeFilter();
		$this->Auth->allow('update_last_year_row','copy_rooms_column','getDateForSpecificDayBetweenDates','get_days','lookup_chart','update_sheet_formula','performance_chart','get_message_template','check_sheet_update','save_notes','inine_edit','test_email','get_hotel_details','save_wizard','get_wizard_values','save_hotel_details','get_bar_levels');
	}

        //calling from Sheet webform to show th popup for notes
        public function inine_edit($sheet_id='1'){
            $this->layout = false;
            $this->SheetNote = ClassRegistry::init('SheetNote');
            $conditions = 'sheet_id = "'.$sheet_id.'"';
            $notes = $this->SheetNote->find('all', array('conditions' => $conditions,'order' => array('SheetNote.created DESC')));
            $this->set('records',$notes);
            $this->set('sheet_id',$sheet_id);
        }

        //calling from Sheet webform to save notes
        public function save_notes(){
            if(!empty($_POST)){
                $this->SheetNote = ClassRegistry::init('SheetNote');
                
                $action = $_POST['action'];
                //$_POST['sheet_id'] = '1';
                if($action == "save"){		
			$id = '1';
                        $escapedPost = array_map('mysql_real_escape_string', $_POST);
			$escapedPost = array_map('htmlentities', $escapedPost);
				
			$res = $this->SheetNote->save($escapedPost);
			
			if($res){
				$escapedPost["success"] = "1";
				$escapedPost["id"] = $this->SheetNote->getLastInsertID();
				echo json_encode($escapedPost);
			}
			else
				echo "save error";
                        
		}else if($action == "del"){
			
                        $id = $_POST['rid'];
			$res = $this->SheetNote->delete($id);
			if($res)
				echo json_encode(array("success" => "1","id" => $id));	
			else
				echo $obj->error("delete");
		}
		else if($action == "update"){
			$escapedPost = array_map('mysql_real_escape_string', $_POST);
			$escapedPost = array_map('htmlentities', $escapedPost);

                        $id = $_POST['rid'];
                        $escapedPost['SheetNote']['id'] = $_POST['rid'];
                        $escapedPost['SheetNote']['notes'] = $_POST['notes'];
                        
			$save = $this->SheetNote->save($escapedPost);
			if($save)
				echo json_encode(array_merge(array("success" => "1","id" => $id),$escapedPost));	
			else
				echo $obj->error("update");
		}
		else if($action == "updatetd"){
		}
                //echo json_encode(array("success" => "1","id" => $id));	
                exit;
            }
        }

        function test_email(){
            Configure::write('debug',2);
            
            $to = array();
            $to[] = 'neema.tembhurnikar@gmail.com';
            $to[] = 'neema@revenue-performance.com';
            $addcc = '';
            $message = 'test';
            $subject = 'test subject';
            $from = 'support@revenue-performance.com';
            $result = $this->Sendemail->send($to, $from, $subject, $message,'');
            if($result){
                echo 'mail sent';
            }else{
                echo 'mail not sent';
            }
            exit;
        }

        function get_hotel_details($hotel_id=null){
		$this->layout = '';
                $this->autoRender = false;
                //hotel_name, hotel_logo
                $this->Client = ClassRegistry::init('Client');
                $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$hotel_id),'fields'=>array('Client.hotelname','Client.logo')));
                $hotelname = $client_name['Client']['hotelname'];
                $logo = $client_name['Client']['logo'];
                $hotel_arr['hotel_name'] = $hotelname;
                $hotel_arr['hotel_logo'] = 'http://myrevenuedashboard.net/files/clientlogos/'.$logo;
                return json_encode($hotel_arr);
                exit;
        }

        function save_wizard($hotel_id=null,$type=null,$value=null){
            	$this->layout = '';
                $this->autoRender = false;
        }

	function get_wizard_values($hotel_id=null,$type=null){
	        $this->layout = '';
                $this->autoRender = false;
	}

        function save_hotel_details($hotel_id=null,$values=null){            
		$this->layout = '';
                $this->autoRender = false;
                //hotel_name, hotel_logo, default_currency , band_length
        }

        function hotel_login($username=null,$password=null){
                $this->layout = '';
                $this->autoRender = false;
        }

        public function update_last_year_row($sheet_id=null){
            $this->layout = false;
            $this->autoRender = false;

            $reUrl = $this->referer();
            
            $this->Sheet = ClassRegistry::init('Sheet');
            $sheetData = $this->Sheet->find('first',array('conditions'=>array('Sheet.id'=>$sheet_id),'fields'=>array('Sheet.month','Sheet.year','Sheet.department_id'),'recursive'=>'0'));
            
            $month = $sheetData['Sheet']['month']; 
            $year = $sheetData['Sheet']['year'];
            $department_id = $sheetData['Sheet']['department_id'];
                    
            $last_year = date('Y') - 1; //get last year

            //find last year sheet for specific month
            $this->Sheet = ClassRegistry::init('Sheet');
            $lastYearSheet = $this->Sheet->find('first',array('conditions'=>array('Sheet.month'=>$month,'Sheet.year'=>$last_year,'Sheet.department_id'=>$department_id),'fields'=>array('Sheet.id'),'recursive'=>'0'));
            if(!empty($lastYearSheet)){
                $lastYearSheetId = $lastYearSheet['Sheet']['id'];
                $sheetData = $this->Sheet->getData($lastYearSheetId);
                //echo '<pre>'; print_r($sheetData); exit;
                
                $new_data = array();
                foreach($sheetData as $sdata){
                    if($sdata['Date'] == 'Total'){
                        //echo '<pre>'; print_r($sdata); exit;
                        $new_data = $sdata;
                        $new_data['BOB'] = str_replace(',','',$sdata['BOB']);
                        $new_data['ADR'] = str_replace(',','',$sdata['ADR']);
                        $new_data['Fcst Rooms'] = str_replace(',','',$sdata['BOB']);
                        $new_data['ADR Fcst'] = str_replace(',','',$sdata['ADR']);
                        $new_data['Date'] = 'LY Actual';
                        $new_data['sheetId'] = $sheet_id;
                    }
                }
                
                if($this->Sheet->saveData($sheet_id, $new_data)){
                    //Update Last Year row with data from last year sheet
                    $this->Session->setFlash(__('LY Actual Updated Successfully', true));
                    $this->redirect($reUrl); 
                }
            }else{
                //Last Year sheet not available
                $this->Session->setFlash(__('No Last Sheet Found for this month', true));
                $this->redirect($reUrl); 
            }
            
        }
        
        
        public function get_bar_levels($client_id=null,$sheet_id=null){
            //Configure::write('debug',2);
            $this->layout = false;
            $this->autoRender = false;
            
            $this->Sheet = ClassRegistry::init('Sheet');
            $sheetData = $this->Sheet->find('first',array('conditions'=>array('Sheet.id'=>$sheet_id),'fields'=>array('Sheet.month','Sheet.year'),'recursive'=>'0'));
            //echo '<pre>'; print_r($sheetData); exit;

            $month = $sheetData['Sheet']['month']; 
            $year = $sheetData['Sheet']['year'];
            $array = file_get_contents('http://mypricingwizard.net/get_data.php?hotel_id='.$client_id.'&month='.$month.'&year='.$year);
            $wizard_data = json_decode($array);
            
            $band_length = $wizard_data->User[0]->band_length;
            $default_bar_level = $wizard_data->User[0]->bar_level;
            
            $reUrl = $this->referer();
            
            if(empty($wizard_data->Wizard[0])){
               $this->Session->setFlash(__('No Wizard Found for this month.', true));
               $this->redirect($reUrl); 
            }
            
            $lateral_increment_room1 = $wizard_data->Wizard[0]->lateral_increment_room1;
            //echo $lateral_increment_promotion1 = $wizard_data->Wizard[0]->lateral_increment_promotion1;
            $normal_value = $wizard_data->Wizard[0]->normal_value;

            $bar_level_array = array();
            if($band_length == '5'){
                $bar_level_array['A'] = $normal_value + ($lateral_increment_room1 * 2);
                $bar_level_array['B'] = $normal_value + ($lateral_increment_room1 * 1);
                $bar_level_array['C'] = $normal_value;
                $bar_level_array['D'] = $normal_value - ($lateral_increment_room1 * 1);
                $bar_level_array['E'] = $normal_value - ($lateral_increment_room1 * 2);
            }else if($band_length == '7'){
                $bar_level_array['A'] = $normal_value + ($lateral_increment_room1 * 3);
                $bar_level_array['B'] = $normal_value + ($lateral_increment_room1 * 2);
                $bar_level_array['C'] = $normal_value + ($lateral_increment_room1 * 1);
                $bar_level_array['D'] = $normal_value;
                $bar_level_array['E'] = $normal_value - ($lateral_increment_room1 * 1);
                $bar_level_array['F'] = $normal_value - ($lateral_increment_room1 * 2);
                $bar_level_array['G'] = $normal_value - ($lateral_increment_room1 * 3);
            }else{
                $bar_level_array['A'] = $normal_value + ($lateral_increment_room1 * 4);
                $bar_level_array['B'] = $normal_value + ($lateral_increment_room1 * 3);
                $bar_level_array['C'] = $normal_value + ($lateral_increment_room1 * 2);
                $bar_level_array['D'] = $normal_value + ($lateral_increment_room1 * 1);
                $bar_level_array['E'] = $normal_value;
                $bar_level_array['F'] = $normal_value - ($lateral_increment_room1 * 1);
                $bar_level_array['G'] = $normal_value - ($lateral_increment_room1 * 2);
                $bar_level_array['H'] = $normal_value - ($lateral_increment_room1 * 3);
                $bar_level_array['I'] = $normal_value - ($lateral_increment_room1 * 4);
            }
            
            //echo '<pre>'; print_r($bar_level_array); exit;

            $sheet_data_values = $this->Sheet->getData($sheet_id);
            //echo '<pre>'; print_r($sheet_data_values); exit;
            //$today = date('d/m/y');
            $today = date('Y-m-d');
            if(!empty($bar_level_array)){
             foreach ($sheet_data_values as $sheetVal){
                if(preg_match('#[\d]#',$sheetVal['Date'])){
                    
                 $exp_date = explode('/',$sheetVal['Date']);
                 $sheetDate = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
                 $sheet_date = strtotime(date('Y-m-d',strtotime($sheetDate)));
                 if(strtotime($today) <= $sheet_date){
                    
                    $new_data = array();
                    //$new_data['id'] = $sheetVal['id'];
                    foreach ($bar_level_array as $key=>$i) {
                        $smallest[$key] = abs($i - $sheetVal['Sell Rate']);
                    }
                    asort($smallest);
                    $bar_level = key($smallest);
                    //$new_data['Sell Rate'] = $sheetVal['Sell Rate'];
                    //$new_data['BAR Level'] = $bar_level;
                    
                    if($default_bar_level == 'Numbers'){
                        
                        if($bar_level == 'A'){
                            $bar_level = '1';
                        }elseif($bar_level == 'B'){
                            $bar_level = '2';
                        }elseif($bar_level == 'C'){
                            $bar_level = '3';
                        }elseif($bar_level == 'D'){
                            $bar_level = '4';
                        }elseif($bar_level == 'E'){
                            $bar_level = '5';
                        }elseif($bar_level == 'F'){
                            $bar_level = '6';
                        }elseif($bar_level == 'G'){
                            $bar_level = '7';
                        }elseif($bar_level == 'H'){
                            $bar_level = '8';
                        }elseif($bar_level == 'I'){
                            $bar_level = '9';
                        }
                    }
                    

                    $datas_obj = ClassRegistry::init('Datum');
                    //$datas_obj->updateAll(array('Datum.value' => "'".$bar_level."'"), array('Datum.column_id' => '118','Datum.date' => $sheetVal['id'],'Datum.sheet_id' => $sheet_id));
                    $conditions = array('Datum.column_id' => '118','Datum.date' => $sheetVal['id'],'Datum.sheet_id' => $sheet_id);
                    $sheet_data_check = $datas_obj->find('first', array('conditions' => $conditions, 'fields' => 'id'));
                    if(!empty($sheet_data_check)){
                        $update = $datas_obj->updateAll(array('Datum.value' => "'".$bar_level."'"), $conditions);
                    }else{
                        $saveData['Datum']['id'] = '';
                        $saveData['Datum']['column_id'] = '118';
                        $saveData['Datum']['date'] = $sheetVal['id'];
                        $saveData['Datum']['sheet_id'] = $sheet_id;
                        $saveData['Datum']['value'] = $bar_level;
                        $saveData['Datum']['row_id'] = '0';
                        $datas_obj->save($saveData);
                    }
                 }
                }
             }//end foreach
                $this->Session->setFlash(__('Bar Level Updated successfully.', true));             
            }else{
                $this->Session->setFlash(__('Bar Level Not Available', true));
            }
           $this->redirect($reUrl);            
        }

        //Alert #1,#2 = sends auto email if webform for current month plus next two months is not updated by 10am,12pm local time.
        public function check_sheet_update(){
            //check Rooms Departments for now for all Hotels
            
            echo 'No Longer Needed';
            exit;
            
            
            Configure::write('debug',0);
            
            $this->layout = false;
            $this->autoRender = false;
    
            $alert_clients = array('67','68','69','70','80','81');
            $month = date('m');
            $year = date('Y');
            $today = date('Y-m-d');
            
            foreach($alert_clients as $client_id){
            
                App::import('Model', 'Department');
                $this->Department = new Department();
                App::import('Model', 'Client');
                $this->Client = new Client();
                $this->Sheet = ClassRegistry::init('Sheet');

                $to = array(); unset($to);
                $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2,'Client.id'=>$client_id), 'fields' => array('hotelname','email')));
                $hotelname = $hotels_data['Client']['hotelname'];
                $client_email = $hotels_data['Client']['email'];
                
                $to[] = $client_email;
                if($client_id == '67'){
                    //Mapungubwe
                    $to[] = 'monique@faircity.co.za';
                    $to[] = 'frontoffice.mapungubwe@faircity.co.za';
                    $to[] = 'gm.mapungubwe@faircity.co.za';
                }elseif($client_id == '68'){
                    //Faircity
                    $to[] = 'monique@faircity.co.za';
                }elseif($client_id == '69'){
                    //Quatermain
                    $to[] = 'monique@faircity.co.za';
                    $to[] = 'reservations.quatermain@faircity.co.za';
                    $to[] = 'gm.quatermain@faircity.co.za';
                }elseif($client_id == '70'){
                    //GrosvenorGardens
                    $to[] = 'monique@faircity.co.za';
                    $to[] = 'reservations.grosvenorgardens@faircity.co.za';
                    $to[] = 'gm.roodevallei@faircity.co.za';
                }elseif($client_id == '80'){
                    //falstaff
                    $to[] = 'monique@faircity.co.za';
                    $to[] = 'reservations.falstaff@faircity.co.za';
                    $to[] = 'gm.quatermain@faircity.co.za';
                }elseif($client_id == '81'){
                    //RoodeVallei
                    $to[] = 'monique@faircity.co.za';
                    $to[] = 'gm.quatermain@faircity.co.za';
                    $to[] = 'gm.roodevallei@faircity.co.za';
                }
                $to = array_unique($to);
               
                
                $logo_url = "http://academy.revenue-performance.com/img/RP%20Square.jpg";
                
                $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
                
                if(!empty($dept_ids)){
                
                    $next_1_month = $month + '1';
                    $next_2_month = $month + '2';
                    $year1= $year; $year2= $year;
                    if($next_1_month > '12'){ $next_1_month = $next_1_month - '12';  $year1= $year + '1'; }
                    if($next_2_month > '12'){ $next_2_month = $next_2_month - '12'; $year2= $year + '1'; }

                    $condition1 = array('Sheet.status' => 1, 'Sheet.year' =>$year,'Sheet.month' =>$month,'Sheet.department_id'=>$dept_ids);
                    $condition2 = array('Sheet.status' => 1, 'Sheet.year' =>$year1,'Sheet.month' =>$next_1_month,'Sheet.department_id'=>$dept_ids);
                    $condition3 = array('Sheet.status' => 1, 'Sheet.year' =>$year2,'Sheet.month' =>$next_2_month,'Sheet.department_id'=>$dept_ids);

                    $sheet_data = $this->Sheet->find('first', array('conditions' => $condition1, 'fields' => array('Sheet.id','Sheet.month'), 'recursive' => '0'));
                    $next_1_sheets = $this->Sheet->find('first', array('conditions' => $condition2, 'fields' => array('Sheet.id','Sheet.month'), 'recursive' => '0'));
                    $next_2_sheets = $this->Sheet->find('first', array('conditions' => $condition3, 'fields' => array('Sheet.id','Sheet.user_id', 'Sheet.month'), 'recursive' => '0'));

                    $sheet_ids[$sheet_data['Sheet']['month']] = $sheet_data['Sheet']['id'];
                    $sheet_ids[$next_1_sheets['Sheet']['month']] = $next_1_sheets['Sheet']['id'];
                    $sheet_ids[$next_2_sheets['Sheet']['month']] = $next_2_sheets['Sheet']['id'];

                    $sheet_ids = array_filter($sheet_ids);

                    $datas_obj = ClassRegistry::init('Datum');
                    $datas_obj->recursive = -1;
                    $datas_data = $datas_obj->find('all', array('conditions' => array('sheet_id' => $sheet_ids),'fields' => array('MAX(DATE(Datum.modified)) AS last_date','sheet_id'),'group'=>'sheet_id'));

                    $not_updated_sheets = array();
                    foreach($datas_data as $sheets_data){
                        if(strtotime($sheets_data[0]['last_date']) < strtotime($today)){
                            $not_updated_sheets[array_search($sheets_data['Datum']['sheet_id'], $sheet_ids)] = $sheets_data['Datum']['sheet_id'];
                        }
                    }

                    if(!empty($not_updated_sheets)){
                        $month_str = array();
                        foreach($not_updated_sheets as $mnth=>$sheet_val){
                            $mnth = date('Y').'-'.$mnth.'-01';
                            $month_str[$mnth] = date('M',strtotime($mnth));
                        }
                        
                        //$to = 'neema.tembhurnikar@gmail.com';
                        $addcc = '';
                        //echo date('H:i:s');
                        if(date('H') < '11'){
                            $email_text = 'Dear '.$hotelname.' Admin,<br>Your data import for the months '.implode(' ,',$month_str).'  has not been completed yet. <br>Please login and complete the import and forecasting functions.';
                            $subject = 'MyDashBoard - Please Update Your Webform';
                        }else{
                            $email_text = 'Dear '.$hotelname.' Admin,<br>SECOND REMINDER. You have yet to complete the import and forecasting for the months of '.implode(' ,',$month_str).'. Your daily sell strategy is outdated and you are missing revenue opportunities. Please login and complete the process.';
                            $subject = 'MyDashBoard - Please Update Your Webform (SECOND REMINDER)';
                        }
                        $message = $this->requestAction('/webservices/get_message_template/'.$hotelname.'/'.htmlentities($email_text).'/'.urlencode($logo_url));
                        
                        $from = 'support@revenue-performance.com';
                        $result = $this->Sendemail->send($to, $from, $subject, $message,'',$addcc);
                        if($result){
                            echo 'mail sent';
                        }else{
                            echo 'mail not sent';
                        }                
                    }

                    }
            }
             exit;
        }
        
        function get_message_template($hotelname,$summary_table,$logo_url,$from_name='Revenue Performance'){
            
            $this->layout = '';
            $this->autoRender = false;
            
            $logo_url = urldecode($logo_url);
            
           $summary_table = html_entity_decode($summary_table);
           
            $email_message = "<table cellspacing='0' cellpadding='0' border='0' >
                <tr>
                <td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>".$hotelname."
                </td>
                </tr>
                <tr>
                <td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' valign='top' style='font-size: 12px;'>
                <div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br>
                <table cellpadding='0' style='margin-top: 5px;border:0;'>
                " . $summary_table . "
                </table>
                <br>
                        <br>
                        <div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
                        <br>
                        </div>
                        <div style='margin: 0pt;'>Thanks &amp; Regards,<br>".$from_name."<br>
                        
                        </div>
                        </td>
                        <td align='left' width='30%' valign='top' style='padding-left: 15px;'>
                        <table cellspacing='0' cellpadding='0' width='100%'>
                        <tbody><tr>
                        <td style='padding: 10px'>
                        <div style='margin-bottom: 15px;'>
                        <a target='blank' href='http://www.revenue-performance.com'>
                                <img src='".$logo_url."' alt='' style='border:0px;' width='100%'>
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
            
            return $email_message;
            
        }

        /******************************************
         * Function : performance_chart 
         * Description : TO create the BOB and ADR ratio chart between 2 selected dates
         * Return: chart BOB and ADR values
         * ************************************************/
        function performance_chart($client_id='69',$startdate='2015-11-01',$enddate='2015-12-15'){
                $this->layout = false;

                App::import('Model','Client');
                $this->Client = new Client();

                $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname'));
                $hotelname = $client_name['Client']['hotelname'];
                $this->set('client_id',$client_id);

                $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);

                App::import('Model','Sheet');
                $this->Sheet = new Sheet();

                $time   = strtotime($startdate);
                $last   = date('m-Y', strtotime($enddate));
                $bob_value_arr =array(); $adr_value_arr=array();
                
                do {
                    $month_year = date('m-Y', $time);
                    $month = date('m', $time);
                    $year = date('Y', $time);

                    if($month_year == $last){
                        if(date('m-Y',strtotime($startdate)) == date('m-Y',strtotime($enddate))){
                            $startDate = date('d', strtotime($startdate));
                        }else{
                            $startDate = '01';
                        }
                        $endDate = date('d', strtotime($enddate));
                    }else{
                        $startDate = date('d', strtotime($startdate));
                        if(date('m-Y',strtotime($startdate)) != date('m-Y',strtotime($enddate))){
                            if($time != strtotime($startdate)){
                               $startDate = '01';
                            }
                        }
                        $endDate = cal_days_in_month (CAL_GREGORIAN,$month, $year);
                    }
                    
                    $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
                  
                    $bob_condition = array('Datum.column_id'=>'62','Datum.sheet_id'=>$all_sheets[0]['Sheet']['id'],'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate);
                    $adr_condition = array('Datum.column_id'=>'64','Datum.sheet_id'=>$all_sheets[0]['Sheet']['id'],'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate);

                    $bob_value_arr[] = $this->Sheet->Datum->find('all',array('conditions'=>$bob_condition,'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
                    $adr_value_arr[] = $this->Sheet->Datum->find('all',array('conditions'=>$adr_condition,'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));

                    $time = strtotime('+1 month', $time);
                } while ($month_year != $last);

                $bob_value = array(); $adr_value = array();
                foreach ($bob_value_arr as $keyB=>$bob_array) {
                    $bob_value = array_merge($bob_value, $bob_array);
                    $adr_value = array_merge($adr_value, $adr_value_arr[$keyB]);
                }
                //echo '<pre>'; print_r($bob_value); print_r($adr_value); 
                
                $bob_arr = ''; $adr_arr = ''; $date_arr = '';
                $bob_count = 0; $date_count = 0; $adr_count = 0;
                foreach($bob_value as $key=>$bob){
                    $adr_value[$key]['Datum']['value'] = str_replace(',','',$adr_value[$key]['Datum']['value']);
                    $adr_val = round($adr_value[$key]['Datum']['value'],2);
                    $bob_main_arr[$date_count] = $bob['Datum']['value'];
                    $adr_main_arr[$date_count] = $adr_val;
                    $date_count++;
                }

                asort($bob_main_arr);
                
                $adr_cnt = '1';
                foreach($bob_main_arr as $bkey=>$bobVal){
                    if($adr_cnt == '1'){
                        $adr_arr = $adr_main_arr[$bkey];
                    }else{
                        $adr_arr = $adr_arr.",".$adr_main_arr[$bkey];  
                    }
                    $adr_cnt++;
                }
               
                $date_arr = '';
                for($i=1;$i<=count($adr_value);$i++){
                    if($i == '1'){
                        $date_arr = "'".$i."'";
                    }else{
                        $date_arr = $date_arr.",'".$i."'";  
                    }
                }
 
                $bob_arr = "[".implode(",",$bob_main_arr)."]"; 
                $date_arr =  '['.$date_arr.']';
                $adr_arr =  '['.$adr_arr.']';

                $this->set('bob_arr',$bob_arr);
                $this->set('adr_arr',$adr_arr);
                $this->set('date_arr',$date_arr);
                $this->set('hotelname',$hotelname);
        }
        
        
        function getDateForSpecificDayBetweenDates($startDate,$endDate,$day_number){
            $this->layout = false;
            $this->autoRender = false;
            $endDate = strtotime($endDate);
            $days=array('1'=>'Monday','2' => 'Tuesday','3' => 'Wednesday','4'=>'Thursday','5' =>'Friday','6' => 'Saturday','7'=>'Sunday');
            for($i = strtotime($days[$day_number], strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i)){
                    $date_array[]=date('d',$i);
            }
            return $date_array;
        }


        
        function get_days($month,$year,$addDays){
            $this->layout = false;
            $this->autoRender = false;
            
            //$addDays = '0'; //0 for monday....6 for sunday
            $days[] = date('d', mktime(0, 0, 0, $month, 1 + $addDays, $year)); 

            $nextMonth = mktime(0, 0, 0, $month + 1, 1, $year); 

            # Just add 7 days per iteration to get the date of the subsequent week 
            for ($week = 1, $time = mktime(0, 0, 0, $month, 1 + $addDays + $week * 7, $year); 
                $time < $nextMonth; 
                ++$week, $time = mktime(0, 0, 0, $month, 1 + $addDays + $week * 7, $year)) 
            { 
                $days[] = date('d', $time); 
            } 
            return $days;
        }
        
        /******************************************
         * Function : lookup_chart 
         * Description : To create the BOB and ADR average ration weekdays chart from historical date 
         * Return: chart BOB and ADR values
         * ************************************************/
        function lookup_chart($client_id=null,$startdate=null,$enddate=null,$startdate_present=null,$enddate_present=null){
                $this->layout = false;

                App::import('Model','Client');
                $this->Client = new Client();

                $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname'));
                $hotelname = $client_name['Client']['hotelname'];
                $this->set('startdate',$startdate);
                $this->set('enddate',$enddate);

                $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);

                $time   = strtotime($startdate);
                $last   = date('m-Y', strtotime($enddate));
                
                $bob_value_arr =array(); $adr_value_arr=array();
                
                App::import('Model','Sheet');
                $this->Sheet = new Sheet();
                
                do {
                    $month_year = date('m-Y', $time);
                    $month = date('m', $time);
                    $year = date('Y', $time);

                    if($month_year == $last){
                        if(date('m-Y',strtotime($startdate)) == date('m-Y',strtotime($enddate))){
                            $startDateRange = date('d', strtotime($startdate));
                        }else{
                            $startDateRange = '01';
                        }
                        $endDateRange = date('d', strtotime($enddate));
                    }else{
                        $startDateRange = date('d', strtotime($startdate));
                        if(date('m-Y',strtotime($startdate)) != date('m-Y',strtotime($enddate))){
                            if($time != strtotime($startdate)){
                               $startDateRange = '01';
                            }
                        }
                        $endDateRange = cal_days_in_month (CAL_GREGORIAN,$month, $year);
                    }
                    $startDate = $year.'-'.$month.'-'.$startDateRange;
                    $endDate = $year.'-'.$month.'-'.$endDateRange;
                    $days = array();
                    $days['Monday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/1');
                    $days['Tuesday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/2');
                    $days['Wednesday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/3');
                    $days['Thursday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/4');
                    $days['Friday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/5');
                    $days['Saturday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/6');
                    $days['Sunday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/7');
                    
                    $date_arr = array();
                    foreach($days as $day_key=>$day) {
                        $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));

                        //62 - BOB, 68 - Revenue
                        $bob_condition = array('Datum.column_id'=>'62','Datum.sheet_id'=>$all_sheets[0]['Sheet']['id'],'Datum.date !='=>'0','Datum.date'=>$day);
                        //$rev_condition = array('Datum.column_id'=>'68','Datum.sheet_id'=>$all_sheets[0]['Sheet']['id'],'Datum.date !='=>'0','Datum.date'=>$day);
                        $adr_condition = array('Datum.column_id'=>'64','Datum.sheet_id'=>$all_sheets[0]['Sheet']['id'],'Datum.date !='=>'0','Datum.date'=>$day);
                        $date_arr[] = $day_key;
                        $bob_value = $this->Sheet->Datum->find('all',array('conditions'=>$bob_condition,'fields'=> array('sum(Datum.value) AS ctotal'),'order'=>'Datum.date ASC'));
                        //$rev_value = $this->Sheet->Datum->find('all',array('conditions'=>$rev_condition,'fields'=> array('sum(Datum.value) AS ctotal'),'order'=>'Datum.date ASC'));
                        $adr_value = $this->Sheet->Datum->find('all',array('conditions'=>$adr_condition,'fields'=> array('sum(replace(Datum.value,",","")) AS ctotal'),'order'=>'Datum.date ASC'));

                        $bob_value_arr[$day_key][] = $bob_value[0][0]['ctotal']/count($day);
                        $adr_value_arr[$day_key][] = $adr_value[0][0]['ctotal']/count($day);
                        //$adr_value_arr[$day_key][] = round(($rev_value[0][0]['ctotal']/$bob_value[0][0]['ctotal'])/count($day),'2');
                    }
                    
                    
                    $time = strtotime('+1 month', $time);
                } while ($month_year != $last);

                $date_arr = array_unique($date_arr);
                
                
                //Code to get values for Present Month
                $time   = strtotime($startdate_present);
                $last   = date('m-Y', strtotime($enddate_present));
                
                $bob_value_arrp =array(); $adr_value_arrp=array();
                
                App::import('Model','Sheet');
                $this->Sheet = new Sheet();
                
                do {
                    $month_year = date('m-Y', $time);
                    $month = date('m', $time);
                    $year = date('Y', $time);

                    if($month_year == $last){
                        if(date('m-Y',strtotime($startdate)) == date('m-Y',strtotime($enddate))){
                            $startDateRange = date('d', strtotime($startdate));
                        }else{
                            $startDateRange = '01';
                        }
                        $endDateRange = date('d', strtotime($enddate));
                    }else{
                        $startDateRange = date('d', strtotime($startdate));
                        if(date('m-Y',strtotime($startdate)) != date('m-Y',strtotime($enddate))){
                            if($time != strtotime($startdate)){
                               $startDateRange = '01';
                            }
                        }
                        $endDateRange = cal_days_in_month (CAL_GREGORIAN,$month, $year);
                    }
                    $startDate = $year.'-'.$month.'-'.$startDateRange;
                    $endDate = $year.'-'.$month.'-'.$endDateRange;
                    $days = array();
                    $days['Monday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/1');
                    $days['Tuesday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/2');
                    $days['Wednesday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/3');
                    $days['Thursday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/4');
                    $days['Friday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/5');
                    $days['Saturday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/6');
                    $days['Sunday'] = $this->requestAction('/Webservices/getDateForSpecificDayBetweenDates/'.$startDate.'/'.$endDate.'/7');
                    
                    foreach($days as $day_key=>$day) {
                        $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));

                        //62 - BOB, 68 - Revenue
                        $bob_condition = array('Datum.column_id'=>'62','Datum.sheet_id'=>$all_sheets[0]['Sheet']['id'],'Datum.date !='=>'0','Datum.date'=>$day);
                        //$rev_condition = array('Datum.column_id'=>'68','Datum.sheet_id'=>$all_sheets[0]['Sheet']['id'],'Datum.date !='=>'0','Datum.date'=>$day);
                        $adr_condition = array('Datum.column_id'=>'64','Datum.sheet_id'=>$all_sheets[0]['Sheet']['id'],'Datum.date !='=>'0','Datum.date'=>$day);
                        $bob_value = $this->Sheet->Datum->find('all',array('conditions'=>$bob_condition,'fields'=> array('sum(Datum.value) AS ctotal'),'order'=>'Datum.date ASC'));
                        //$rev_value = $this->Sheet->Datum->find('all',array('conditions'=>$rev_condition,'fields'=> array('sum(Datum.value) AS ctotal'),'order'=>'Datum.date ASC'));
                        $adr_value = $this->Sheet->Datum->find('all',array('conditions'=>$adr_condition,'fields'=> array('sum(replace(Datum.value,",","")) AS ctotal'),'order'=>'Datum.date ASC'));
                        $bob_value_arrp[$day_key][] = $bob_value[0][0]['ctotal']/count($day);
                        $adr_value_arrp[$day_key][] = $adr_value[0][0]['ctotal']/count($day);
                        //$adr_value_arrp[$day_key][] = round(($rev_value[0][0]['ctotal']/$bob_value[0][0]['ctotal'])/count($day),'2');
                    }
                    $time = strtotime('+1 month', $time);
                } while ($month_year != $last);

                
                $bob_value_arr_final = array(); $adr_value_arr_final = array();
                $bob_value_arr_final = array(); $adr_value_arr_final = array();
                foreach($date_arr as $days){
                    $bob_value_arr_final[] = round(array_sum($bob_value_arr[$days])/count($bob_value_arr[$days]),'2');
                    $adr_value_arr_final[] = round(array_sum($adr_value_arr[$days])/count($adr_value_arr[$days]),'2');
                    $bob_value_arr_finalp[] = round(array_sum($bob_value_arrp[$days])/count($bob_value_arrp[$days]),'2');
                    $adr_value_arr_finalp[] = round(array_sum($adr_value_arrp[$days])/count($adr_value_arrp[$days]),'2');
                }
                $bob_arr = "[".implode(",",$bob_value_arr_final)."]"; 
                $adr_arr = "[".implode(",",$adr_value_arr_final)."]";
                $bob_present_arr = "[".implode(",",$bob_value_arr_finalp)."]"; 
                $adr_present_arr = "[".implode(",",$adr_value_arr_finalp)."]";
                
                
                $date_arr = "['".implode("','",$date_arr)."']"; 

                $this->set('bob_arr',$bob_arr);
                $this->set('adr_arr',$adr_arr);
                $this->set('bob_present_arr',$bob_present_arr);
                $this->set('adr_present_arr',$adr_present_arr);
                $this->set('date_arr',$date_arr);
                $this->set('hotelname',$hotelname);
        }
        
        //function to change the number of days in the formula as per the month
        function update_sheet_formula(){
            //not working as needed
            Configure::write('debug',2);
            $this->layout = false;
            $this->autoRender = false;
            
            $this->Formula = ClassRegistry::init('Formula');
            //$this->Sheet = ClassRegistry::init('Sheet');
            //$this->Sheet->contain(array('Formula'));
            
            $sheetData = $this->Formula->find('all',array('conditions'=>
                array('OR' => array(array('Formula.formula LIKE' => '% 31 %'),array('Formula.formula LIKE' => '% 30 %'),array('Formula.formula LIKE' => '% 29 %')),'Sheet.status'=>'1',array('OR'=>array(array('Formula.row_id' => '19'),array('Formula.row_id' => 'Total')))),
                'fields'=>array('Sheet.id','Sheet.month','Sheet.year','Formula.formula','Formula.id'),
                'recursive'=>'0'));

            echo '<pre>'; 
            print_r($sheetData); exit;
            $count = '1';
            foreach($sheetData as $sheets){
                $days_in_month = cal_days_in_month(CAL_GREGORIAN, $sheets['Sheet']['month'], $sheets['Sheet']['year']);
                
                $replace_array = array('* 31 )','* 30 )','* 29 )','( 31 *','( 30 *','( 29 *');
                
                $replace1 = '* '.$days_in_month.' )';
                $replace2 = '* '.$days_in_month.' )';
                $replace3 = '* '.$days_in_month.' )';
                $replace4 = '( '.$days_in_month.' *';
                $replace5 = '( '.$days_in_month.' *';
                $replace6 = '( '.$days_in_month.' *';
                $new_array = array($replace1,$replace2,$replace3,$replace4,$replace5,$replace6);
                $updated_formula = str_replace($replace_array, $new_array, $sheets['Formula']['formula']);
                
                //$this->Formula->create();
                if($sheets['Formula']['formula'] != $updated_formula){
                    $formula['Formula']['old_formula'] = $sheets['Formula']['formula'];
                    $formula['Formula']['formula'] = $updated_formula;
                    $formula['Formula']['id'] = $sheets['Formula']['id'];
                    //$this->Formula->save($formula['Formula']);
                    print_r($formula);
                    $count++;
                }
                            }
            echo 'Count:'.$count;
            exit;
        }
        
        
        public function copy_rooms_column(){
            Configure::write('debug',2);
            $this->layout = false;
            $this->autoRender = false;

            //$client_id = array('141','144'); //Lucknam Park & Raithwaite
            $client_id = array('141');
            //$client_id = '144';
            $client_lists = Classregistry::init('Client')->find('list',array('fields' => array('id', 'id'),'conditions'=>array('Client.id'=>$client_id,'Client.status'<>'2'),'recursive'=>'-1'));
            
            foreach($client_lists  as $clientIds){
                $this->Department = ClassRegistry::init('Department');
                $department_arr = $this->Department->find('list', array('fields' => array('id', 'name'), 'conditions' => array('Department.client_id' => $clientIds,'Department.status'=>'1')));
                
                $flag = '0'; $copyDeptId = array();
                if(count($department_arr) > '1'){
                    foreach($department_arr as $departmentId => $department){
                        if (strstr($department,'Room')){
                           $roomDepartmentId = $departmentId;
                           $flag = '1';
                        }elseif (strstr($department,'Spa')){
                            $spaDepartmentId = $departmentId; 
                            $copyDeptId[] = $departmentId;
                        }elseif (strstr($department,'Restaurant')){
                            //echo 'Restaurant';
                            $restaurantDepartmentId = $departmentId; 
                            $copyDeptId[] = $departmentId;
                        }elseif (strstr($department,'Banqueting')){
                            $restaurantDepartmentId = $departmentId; 
                            $copyDeptId[] = $departmentId;
                        }elseif (strstr($department,'Afternoon Tea')){
                            $restaurantDepartmentId = $departmentId; 
                            $copyDeptId[] = $departmentId;
                        }
                    }
                }
                
                //$copyDeptId = array('300');
                //echo '<pre>'; print_r($copyDeptId); exit;
                
                if($flag == '1'){
                    $fcst_col_id = '63';
                    $month = date('m');
                    //$month = '07';
                    $year = date('Y');
                    if(!empty($copyDeptId)){
                        $this->Sheet = ClassRegistry::init('Sheet');
                        $conditions = array('Sheet.department_id'=>$roomDepartmentId,'Sheet.month'=>$month,'Sheet.year'=>$year,'Sheet.status'=>'1');
                        $userSheets = $this->Sheet->find('first', array('conditions' => $conditions,'fields' => array('Sheet.id'), 'recursive' => -1)); 
                        if(!empty($userSheets)){
                            $sheet_id = $userSheets['Sheet']['id'];
                            $datas_obj = ClassRegistry::init('Datum');
                            $datas_data = $datas_obj->find('all', array('conditions' => array('sheet_id' => $sheet_id,'column_id'=>$fcst_col_id,'Datum.date !='=>'0'),'fields'=>array('Datum.date','Datum.value'),'order'=>array('Datum.date ASC')));

                            foreach($copyDeptId as $deptIds){
                                $conditions = array('Sheet.department_id'=>$deptIds,'Sheet.month'=>$month,'Sheet.year'=>$year,'Sheet.status'=>'1');
                                $copySheets = $this->Sheet->find('first', array('conditions' => $conditions,'fields' => array('Sheet.id'), 'recursive' => -1)); 

                                if(!empty($copySheets)){
                                    $this->Column = ClassRegistry::init('Column');
                                    $sheet_columns = $this->Column->find('list', array('conditions' => array('Column.status !=' => 2,'id'=>$fcst_col_id),'fields'=>array('Column.id','Column.name'),'recursive'=>'0'));
                                    $col_name = $sheet_columns[$fcst_col_id];

                                    $new_sheetId = $copySheets['Sheet']['id'];

                                     $i = '1';
                                     $new_arr= array();
                                     $sdata = $this->Sheet->findById($new_sheetId);
                                     $columns = Set::extract('/name', $sdata['Column']);
                                     foreach($datas_data as $data_val){
                                         unset($new_arr);
                                         $new_arr[$i]['id'] = $data_val['Datum']['date'];
                                         $new_arr[$i]['Date'] = $data_val['Datum']['date'].'/'.$month.'/'.date("y");
                                         $new_arr[$i]['sheetId'] = $new_sheetId;

                                         foreach ($columns as $ckey => $col) {
                                             $new_arr[$i][$col] = '0';
                                            foreach ($sdata['Datum'] as $dkey => $datum) {
                                                if (($datum['sheet_id'] == $new_sheetId) && ($datum['column_id'] == $sdata['Column'][$ckey]['id']) 
                                                        && ($datum['date'] == $data_val['Datum']['date']) && ($datum['row_id'] == 0)) {
                                                    $new_arr[$i][$col] = $datum['value'];
                                                    break;
                                                }
                                            }
                                         }
                                         $new_arr[$i][$col_name] = $data_val['Datum']['value'];
                                         
                                         echo '<pre>'; print_r($new_arr[$i]); 
                                         
                                         $this->Sheet->importWebform($new_sheetId, $new_arr[$i]);
                                         $i++;
                                    }
                                    $this->Sheet->updateRowsTotal($new_sheetId);
                                    //exit;
                                }//not empty copy sheets for selected dept
                            }//end foreach for copy sheet
                        }//not empty Rooms Dept sheet
                    }//not empty copy Departments
                    
                }
            }
            exit;
        }
        
}//end class