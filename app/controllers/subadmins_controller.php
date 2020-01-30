<?php
class SubadminsController extends AppController {

	var $name = 'Subadmins';
	var $helpers = array('Html', 'Javascript', 'Session');
	var $components = array('RequestHandler', 'Sendemail','Session','Email','Cookie');

        function beforeFilter() {
           	parent::beforeFilter();
                $this->Auth->allow('email_webform_summary');
                $this->Auth->allow('email_webform_summary_test');
	}


	function index() {
		//$this->set('Subadmin', $this->Subadmin->read(null, $id));
                $this->layout = 'login_layout';
		// Display error message on failed authentication
		if (!empty($this->data) && !$this->Auth->_loggedIn) {
			$this->Session->setFlash($this->Auth->loginError);
		}

		// Redirect the logged in user to respective pages
		$this->setLoginRedirects();
	}
        
	function admin_index() {
                if ($this->data['Subadmin']['value']) {
			$search = trim($this->data['Subadmin']['value']);
			$this->set('search',$search);
			$conditions = array(
                            'OR'  => array('Subadmin.username LIKE' => "%$search%", 'Subadmin.firstname LIKE' => "%$search%", 'Subadmin.lastname LIKE' => "%$search%"), 
                            'AND' => array('Subadmin.status !=' => 2)
                      );
		} else {
			$conditions = array('Subadmin.status !=' => 2);
		}

		$this->Subadmin->recursive = 0;
		$this->paginate['conditions'] = $conditions;
		$Subadmins = $this->paginate();
            	
		$this->set('Subadmins', $Subadmins);
         }

        function admin_assignhotel($subadmin_id=null) {
           // Configure::write('debug',2);
	        $this->Client = ClassRegistry::init('Client');
                $all_hotels = $this->Client->find('list',array('conditions'=>array('Client.status !=' => 2),'fields'=>'id,hotelname'));
                $this->set('all_hotels', $all_hotels);
                
                if(!empty($this->data)){
                    
                    $this->SubadminClient = ClassRegistry::init('SubadminClient');
                    
                        if(!empty($this->data['SubadminClient']['client_id'])){
                              $this->SubadminClient->deleteAll(array('SubadminClient.subadmin_id'=>$subadmin_id));
                              foreach($this->data['SubadminClient']['client_id'] as $client_id)
                              {  
                                $subadmin_data['subadmin_id'] = $subadmin_id;
                                $subadmin_data['client_id'] = $client_id;
                                $subadmin_data['id'] = '';

                                $this->SubadminClient->saveAll($subadmin_data);
                              }
                       }
                        $this->Session->setFlash(__('Hotel Assigned Successfully', true));
			$this->redirect(array('action' => 'index'));
                }else{
                    $this->data = $this->Subadmin->read(null, $subadmin_id);
                    $allclients = array();
                    foreach($this->data['SubadminClient'] as $clients)
                    {
                            array_push($allclients,$clients['client_id']);
                    }
                    $this->set('allclients',$allclients);
                    
                }
	}

        
	/**
	 * Login action for admin
	 */
	function login() {
            $this->layout = 'login_layout';
		// Display error message on failed authentication
		if (!empty($this->data) && !$this->Auth->_loggedIn) {
			$this->Session->setFlash($this->Auth->loginError);
		}

		// Redirect the logged in user to respective pages
		$this->setLoginRedirects();
	}//end login()


	/**
	 * Logout action for admin
	 */
	function logout() {
		$this->Session->setFlash(__('You are successfully Logged out.', true));
		$this->redirect($this->Auth->logout());
	}//end logout()


	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Subadmin', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('subadmin', $this->Subadmin->read(null, $id));
	}

        function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Subadmin', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('subadmin', $this->Subadmin->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Subadmin->create();
			if ($this->Subadmin->save($this->data)) {
				$this->Session->setFlash(__('The Sub-Admin has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Sub-Admin could not be saved. Please, try again.', true));
			}
		}
	}


	function admin_editprofile() {
		$id = $this->Auth->user('id');
	
		if (!empty($this->data)) {
                    
                    $users_data['Subadmin'] = $this->data['Subadmin'];
                    $user_id = $this->data['Subadmin']['id'];
                    
                    /*check for unique username and email*/
		    $prev_info = $this->Subadmin->findById($user_id);
		    $prev_username = $prev_info['Subadmin']['username'];
		    $prev_email = $prev_info['Subadmin']['email'];

		    if($users_data['Subadmin']['username'] == $prev_username){
			unset($users_data['Subadmin']['username']);  
		    }
		    if($users_data['Subadmin']['email'] == $prev_email){
			unset($users_data['Subadmin']['email']);
		    }
                    
                    	if ($this->Subadmin->save($users_data)) {
				$this->Session->setFlash(__('The Sub-Admin has been saved', true));
                        } else {
				$this->Session->setFlash(__('The Sub-Admin could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data) || isset($this->data['Subadmin']['password'])) {
			$this->data = $this->Subadmin->read(null, $id);
		}
	}

        function admin_edit($id=null) {
		
		if (!empty($this->data)) {
                    
                    $users_data['Subadmin'] = $this->data['Subadmin'];
                    $user_id = $this->data['Subadmin']['id'];
                    
                    /*check for unique username and email*/
		    $prev_info = $this->Subadmin->findById($user_id);
		    $prev_username = $prev_info['Subadmin']['username'];
		    $prev_email = $prev_info['Subadmin']['email'];

		    if($users_data['Subadmin']['username'] == $prev_username){
			unset($users_data['Subadmin']['username']);  
		    }
		    if($users_data['Subadmin']['email'] == $prev_email){
			unset($users_data['Subadmin']['email']);
		    }
                    
			if ($this->Subadmin->save($users_data)) {                            
				$this->Session->setFlash(__('The Sub-Admin has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Sub-Admin could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data) || isset($this->data['Subadmin']['password'])) {
			$this->data = $this->Subadmin->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Sub-Admin', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Subadmin->softDelete($id)) {
			$this->Session->setFlash(__('Sub-Admin deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Admin was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
        
        
    function email_webform_summary() {
        $this->layout = false;
        $this->autoRender = false;

        //Configure::write('debug',2);
        
        $alert_clients = ClassRegistry::init('EmailSummarySheet')->find('list', array('conditions'=>array('EmailSummarySheet.type'=>'summary','client_id !='=>'152'),'fields' => array('client_id'),'group'=>'client_id'));
        
        //$alert_clients = array('100');
        
        foreach($alert_clients as $client_id){
            
        App::import('Model', 'Department');
        $this->Department = new Department();
        App::import('Model', 'Client');
        $this->Client = new Client();
        
       $this->Sheet = ClassRegistry::init('Sheet');
        
        $month = date('m');
        $year = date('Y');
        
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2,'Client.id'=>$client_id), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];
        
        $this->Client->Department->recursive = -1;
        $condition = array('Department.client_id' => $client_id, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
        $dept_data = $this->Client->Department->find('all', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));

        $dept_ids = array();
        unset($dept_ids);
        foreach ($dept_data as $dept) {
            $dept_ids[] = $dept['Department']['id'];
        }
       
        $monthName = date("F", mktime(0, 0, 0, $month, 10));
        $email_main_subject = "Daily Update - ".$hotelname." (3 Months Summary )";
        
        $sheet_data = $this->Sheet->find('first', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' =>$year,'Sheet.month' =>$month,'Sheet.department_id'=>$dept_ids), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.name', 'Sheet.email', 'Sheet.user_id', 'Sheet.month'), 'recursive' => '0'));
        $next_1_month = $month + '1';
        $next_2_month = $month + '2';
        $year1= $year; $year2= $year;
        if($next_1_month > '12'){ $next_1_month = $next_1_month - '12';  $year1= $year + '1'; }
        if($next_2_month > '12'){ $next_2_month = $next_2_month - '12'; $year2= $year + '1'; }

        $next_1_sheets = $this->Sheet->find('first', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' =>$year1,'Sheet.month' =>$next_1_month,'Sheet.department_id'=>$dept_ids), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.name', 'Sheet.email', 'Sheet.user_id', 'Sheet.month'), 'recursive' => '0'));
        $next_2_sheets = $this->Sheet->find('first', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' =>$year2,'Sheet.month' =>$next_2_month,'Sheet.department_id'=>$dept_ids), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.name', 'Sheet.email', 'Sheet.user_id', 'Sheet.month'), 'recursive' => '0'));

           if (!empty($sheet_data)) {
                  
                    //echo '<pre>'; print_r($sheet_data); exit;
                   
                    $emails = array();
                    $sheetId = $sheet_data['Sheet']['id'];
                    $sheet_name = $sheet_data['Sheet']['name'];
                    
                    $department_id = $sheet_data['Sheet']['department_id'];
                    
                    $user_id = $sheet_data['Sheet']['user_id'];
                    if (!empty($sheetId)) {

                            $user_id = trim($user_id);
                            $user_data = $this->Sheet->User->findById($user_id);

                            if (!empty($user_data)) {

                                $next1MonthData = array(); $next2MonthData = array();
                                if(!empty($next_1_sheets['Sheet']['id'])){
                                     $next1MonthData = $this->Sheet->getData($next_1_sheets['Sheet']['id']);
                                }
                                if(!empty($next_2_sheets['Sheet']['id'])){
                                     $next2MonthData = $this->Sheet->getData($next_2_sheets['Sheet']['id']);
                                }
                                
                                $data = $this->Sheet->getData($sheetId);
                                $headers = array();
                                foreach ($data[0] as $key => $value) {
                                    if ($key != "sheetId") {
                                        array_push($headers, $key);
                                    }
                                }

                                $rest_values = array();
                                $rest_values[0] = $headers;
                                for ($i = 0; $i < count($data); $i++) {
                                    foreach ($data[$i] as $key => $values) {
                                        if ($key != "sheetId") {
                                            $rest_values[$i + 1][] = $values;
                                        }
                                    }
                                }

                                $budget_array = array(); $lastYear_array = array();
                                $total_array = array();
                                foreach ($rest_values as $key => $rest_value) {
                                    if (in_array('Total', $rest_value)) {
                                        $total_array = $rest_value;
                                    }else if (in_array('Budget', $rest_value)) {
                                        $budget_array = $rest_value;
                                    }else if (in_array('LY Actual', $rest_value)) {
                                        $lastYear_array = $rest_value;
                                    }
                                }

                                $total_keys = array(); $lastYear_today = array();
                                unset($rest_values[0][0], $rest_values[0][1]);
                                foreach ($rest_values[0] as $key => $rest_value) {
                                    $total_keys[$rest_value] = $total_array[$key];
                                    $budget_today[$rest_value] = $budget_array[$key];
                                    $lastYear_today[$rest_value] = $lastYear_array[$key];
                                }
                                 $yesterday_date = date('Y-m-d',strtotime('-1 day'));
 
                                $SheetdataDetails1 = array(); $Sheetdata_1Month = array(); $budget1_today = array();
                                $lastYear1_today = array();
                                if(!empty($next1MonthData)){
                                    foreach ($next1MonthData as $values) {
                                        if ($values['Date'] == 'Total') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $Sheetdata_1Month[$tkey] = $total_key;
                                                }
                                            }
                                        }elseif ($values['Date'] == 'Budget') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $budget1_today[$tkey] = $total_key;
                                                }
                                            }
                                        }elseif ($values['Date'] == 'LY Actual') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $lastYear1_today[$tkey] = $total_key;
                                                }
                                            }
                                        }
                                    }
                                    
                                    //Get Yesterday date and data first
                                            $yest_date_data1 = ClassRegistry::init('SheetHistory')->find('first', array('conditions' => array('SheetHistory.sheet_id' => $next_1_sheets['Sheet']['id'], 'SheetHistory.date' => $yesterday_date), 'field' => 'date', 'order' => 'SheetHistory.date DESC'));
                                            if (!empty($yest_date_data1)) {
                                                $yest_date = $yest_date_data1['SheetHistory']['date'];
                                                $SheetdataDetails1 = ClassRegistry::init('SheetHistory')->find('list', array('conditions' => array('SheetHistory.sheet_id' => $next_1_sheets['Sheet']['id'], 'SheetHistory.date' => $yest_date), 'fields' => array('SheetHistory.type', 'SheetHistory.total')));
                                            }

                                            //save data once get yesterday data
                                            $SheetdataDate1 = ClassRegistry::init('SheetHistory')->find('count', array('conditions' => array('SheetHistory.sheet_id' => $next_1_sheets['Sheet']['id'], 'SheetHistory.date' => date('Y-m-d'))));
                                            if ($SheetdataDate1 == 0) {
                                                $t = 0;
                                                foreach ($Sheetdata_1Month as $tkey => $total_key) {
                                                    $Sheetdata1[$t]['sheet_id'] = $next_1_sheets['Sheet']['id'];
                                                    $Sheetdata1[$t]['date'] = date('Y-m-d');
                                                    $Sheetdata1[$t]['type'] = $tkey;
                                                    $Sheetdata1[$t]['total'] = $total_key;
                                                    $t++;
                                                }
                                                ClassRegistry::init('SheetHistory')->saveAll($Sheetdata1); //plz uncomment
                                            }
                                }
                                
                                $SheetdataDetails2 = array(); $Sheetdata_2Month = array(); $budget2_today = array();
                                $lastYear2_today = array();
                                if(!empty($next2MonthData)){
                                    foreach ($next2MonthData as $values) {
                                        if ($values['Date'] == 'Total') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $Sheetdata_2Month[$tkey] = $total_key;
                                                }
                                            }
                                        }elseif ($values['Date'] == 'Budget') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $budget2_today[$tkey] = $total_key;
                                                }
                                            }
                                        }elseif ($values['Date'] == 'LY Actual') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $lastYear2_today[$tkey] = $total_key;
                                                }
                                            }
                                        }
                                    }
                                    
                                    //Get Yesterday date and data first
                                        $yest_date_data2 = ClassRegistry::init('SheetHistory')->find('first', array('conditions' => array('SheetHistory.sheet_id' => $next_2_sheets['Sheet']['id'], 'SheetHistory.date' => $yesterday_date), 'field' => 'date', 'order' => 'SheetHistory.date DESC'));
                                        if (!empty($yest_date_data2)) {
                                            $yest_date = $yest_date_data2['SheetHistory']['date'];
                                            $SheetdataDetails2 = ClassRegistry::init('SheetHistory')->find('list', array('conditions' => array('SheetHistory.sheet_id' => $next_2_sheets['Sheet']['id'], 'SheetHistory.date' => $yest_date), 'fields' => array('SheetHistory.type', 'SheetHistory.total')));
                                        }

                                        //save data once get yesterday data
                                        $SheetdataDate2 = ClassRegistry::init('SheetHistory')->find('count', array('conditions' => array('SheetHistory.sheet_id' => $next_2_sheets['Sheet']['id'], 'SheetHistory.date' => date('Y-m-d'))));
                                        if ($SheetdataDate2 == 0) {
                                            $t = 0;
                                            foreach ($Sheetdata_2Month as $tkey => $total_key) {
                                                $Sheetdata2[$t]['sheet_id'] = $next_2_sheets['Sheet']['id'];
                                                $Sheetdata2[$t]['date'] = date('Y-m-d');
                                                $Sheetdata2[$t]['type'] = $tkey;
                                                $Sheetdata2[$t]['total'] = $total_key;
                                                $t++;
                                            }
                                            ClassRegistry::init('SheetHistory')->saveAll($Sheetdata2); //plz uncomment
                                        }
                                    
                                }
                             
                                //Get Yesterday date and data first
                                $yest_date_data = ClassRegistry::init('SheetHistory')->find('first', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => $yesterday_date), 'field' => 'date', 'order' => 'SheetHistory.date DESC'));
                                $SheetdataDetails = array();
                                if (!empty($yest_date_data)) {
                                    $yest_date = $yest_date_data['SheetHistory']['date'];
                                    $SheetdataDetails = ClassRegistry::init('SheetHistory')->find('list', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => $yest_date), 'fields' => array('SheetHistory.type', 'SheetHistory.total')));
                                }

                                //save data once get yesterday data
                                $SheetdataDate = ClassRegistry::init('SheetHistory')->find('count', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => date('Y-m-d'))));
                                if ($SheetdataDate == 0) {
                                    $t = 0;
                                    foreach ($total_keys as $tkey => $total_key) {
                                        $Sheetdata[$t]['sheet_id'] = $sheetId;
                                        $Sheetdata[$t]['date'] = date('Y-m-d');
                                        $Sheetdata[$t]['type'] = $tkey;
                                        $Sheetdata[$t]['total'] = $total_key;
                                        $t++;
                                    }
                                    ClassRegistry::init('SheetHistory')->saveAll($Sheetdata); //plz uncomment
                                }

                                $emails = ClassRegistry::init('EmailSummarySheet')->find('list', array('conditions'=>array('client_id'=>$client_id,'EmailSummarySheet.type'=>'summary'),'fields' => array('email')));
                                        
                                if (!empty($emails)) {
                                        set_time_limit(40);                                        
                                        $email_from = "duncan.bramwell@revenue-performance.com"; // The email you are sending from (example)
                                        $email_subject = $email_main_subject;
                                        $summary_table = "<tr valign='top'>
						<td style='padding: 0px 3px 10px 0px;'>
							<table cellpadding='0' border='0' style='font-family: verdana,arial,sans-serif;font-size:11px;	color:#333333;border-width: 1px;border-color: #666666;border-collapse: collapse;line-height:20px;'>
                                                        <tr>
                                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>&nbsp;</td>
                                                        <td colspan='2' style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>".$monthName."</td>";

                                                        if(!empty($Sheetdata_1Month)){ 
                                                            $summary_table .= "<td colspan='2' style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>".date('F', mktime(0, 0, 0, $next_1_sheets['Sheet']['month'], 10))."</td>";
                                                        }
                                                        if(!empty($Sheetdata_2Month)){ 
                                                           $summary_table .= "<td colspan='2' style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>".date('F', mktime(0, 0, 0, $next_2_sheets['Sheet']['month'], 10))."</td>";
                                                        }
                                                        
                                                        $summary_table .= "</tr>
							<tr>
								<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>&nbsp;</td>
								<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Today</td>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Yesterday</td>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Today</td>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Yesterday</td>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Today</td>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Yesterday</td>";
                                        
                                        $summary_table .= "</tr>";
                                        foreach ($total_keys as $key => $total_key) {

                                            if (($key != 'TripAdvisor') && ($key != 'BAR Level') && ($key != 'Notes')) {
                                                $total_key_new = (str_replace(".00", "", $total_key));
                                                $summary_table .= "<tr>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total " . $key . "</td>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $total_key_new . "</td>";

                                                if (count($SheetdataDetails) > 0) {
                                                    $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails[$key]));
                                                    $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                                }else{
                                                    $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                                }
                                                
                                                //Next 1 month
                                                $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_1Month[$key] . "</td>";
                                                if (count($SheetdataDetails1) > 0) {
                                                    $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails1[$key]));
                                                    $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                                }else{
                                                    $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                                }
                                                //Next 2 month
                                                $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_2Month[$key] . "</td>";
                                                if (count($SheetdataDetails2) > 0) {
                                                    $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails2[$key]));
                                                    $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                                }else{
                                                    $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                                }
                                                

                                                $summary_table .= "</tr>";
                                            }//enf if key value check
                                        }//End Foreach
                                        
                                        
                                        if(!empty($lastYear_today)){
                                             $summary_table .= "<tr>
                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total LY Actual</td>
                                                    <td colspan='2' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $lastYear_today['Revenue'] . "</td>
                                                    <td colspan='2' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $lastYear1_today['Revenue'] . "</td>
                                                    <td colspan='2' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $lastYear2_today['Revenue'] . "</td>
                                                    </tr>";
                                        }
                                        
                                        if(!empty($budget_today)){
                                            
                                            $variance = str_replace(',','',$total_keys['Revenue']) - str_replace(',','',$budget_today['Revenue']);
                                            $variance = number_format($variance,'2');
                                            
                                            $variance1 = str_replace(',','',$Sheetdata_1Month['Revenue']) - str_replace(',','',$budget1_today['Revenue']);
                                            $variance1 = number_format($variance1,'2');

                                            $variance2 = str_replace(',','',$Sheetdata_2Month['Revenue']) - str_replace(',','',$budget2_today['Revenue']);
                                            $variance2 = number_format($variance2,'2');

                                            $summary_table .= "<tr>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total Budget</td>
                                                                <td colspan='2' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $budget_today['Revenue'] . "</td>
                                                                <td colspan='2' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $budget1_today['Revenue'] . "</td>
                                                                <td colspan='2' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $budget2_today['Revenue'] . "</td>
                                                                </tr>";
                                            $summary_table .= "<tr>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total Variance</td>
                                                                <td colspan='2' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $variance . "</td>
                                                                <td colspan='2' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $variance1 . "</td>
                                                                <td colspan='2' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $variance2 . "</td>
                                                                </tr>";

                                        }
                                        
                                        $summary_table .= "</table>
						</td>
						</tr>";
                                      
                                        $email_txt = "<table cellspacing='0' cellpadding='0' border='0' >
                                        <tr>
                                        <td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>MyDashboard
                                        </td>
                                        </tr>
                                        <tr>
                                        <td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' valign='top' style='font-size: 12px;'>
                                        <div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br>
                                        <table cellpadding='0' style='margin-top: 5px;border:0;'>
                                        <tr valign='top'>
                                        <td style='padding: 0px 3px 10px 0px;'>
                                        <FONT SIZE=2 FACE='Arial'>Please find below the Summary of your revenue forecast.</FONT>
                                        </td>
                                        </tr>" . $summary_table . "
                                        </table>
                                        <br><br>
                                                <div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
                                                <br>
                                                </div>
                                                <div style='margin: 0pt;'>Thanks &amp; Regards,<br>MyDashboard<br>
                                                <a href='http://www.myrevenuedashboard.net'>www.myrevenuedashboard.net</a>
                                                </div>
                                                </td>
                                                <td align='left' width='150' valign='top' style='padding-left: 15px;'>
                                                <table cellspacing='0' cellpadding='0' width='100%'>
                                                <tbody><tr>
                                                <td style='padding: 10px'>
                                                <div style='margin-bottom: 15px;'>
                                                <a target='blank' href='http://www.revenue-performance.com'>
                                                        <img src='http://" . $_SERVER['HTTP_HOST'] . "/img/RP-logo.png' alt='' style='border:0px;'>
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
                                              
                                        //echo $email_txt; exit;
                                        
                                        $semi_rand = md5(time());
                                        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
                                        $headers = "From: $email_from"; // Who the email is from (example)
                                        $headers .= "\nMIME-Version: 1.0\n" .
                                                "Content-Type: multipart/mixed;\n" .
                                                " boundary=\"{$mime_boundary}\"";
                                        @$email_message .= "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type:text/html;charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $email_txt;
                                        $email_message .= "\n\n";
                                        
                                        $data = chunk_split(base64_encode($data));
                                        
                                        $email_message .= "--{$mime_boundary}\n" .
                                                "Content-Type: text/html;charset=iso-8859-1;\n" .
                                                "Content-Transfer-Encoding: base64\n\n" .
                                                $data . "\n\n" .
                                                "--{$mime_boundary}--\n";
     
                                        foreach ($emails as $emls) {
                                            $email_to = $emls; // The email you are sending to (example)
                                            if(!empty($email_to)){
                                                if (mail($email_to, $email_subject, $email_message, $headers)) {
                                                    echo 'Mail Send <br>';
                                                } else {
                                                    echo 'Mail Not Send <br>';
                                                }
                                            }
                                    }
                                }

                                unlink($path);
                                rmdir($file_path);
                            }//end foreach
                       
                    }//end if statement
               
            }
        }
        $this->requestAction('/subadmins/email_webform_summary_test');
        exit;
    }
    
    
    
       
    function email_webform_summary_test() {
        
        //echo 'here'; exit;
        $this->layout = false;
        $this->autoRender = false;

        //$alert_clients = ClassRegistry::init('EmailSummarySheet')->find('list', array('conditions'=>array('EmailSummarySheet.type'=>'summary'),'fields' => array('client_id'),'group'=>'client_id'));
        
        $alert_clients = array();
        $alert_clients['152'] = '152';
      
        foreach($alert_clients as $client_id){
           // $client_id = '151';
            
        App::import('Model', 'Department');
        $this->Department = new Department();
        App::import('Model', 'Client');
        $this->Client = new Client();
        
       $this->Sheet = ClassRegistry::init('Sheet');
        
        $month = date('m');
        $year = date('Y');
        
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2,'Client.id'=>$client_id), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];
        
        $this->Client->Department->recursive = -1;
        $condition = array('Department.client_id' => $client_id, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
        $dept_data = $this->Client->Department->find('all', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));

        $dept_ids = array();
        unset($dept_ids);
        foreach ($dept_data as $dept) {
            $dept_ids[] = $dept['Department']['id'];
        }
       
        $monthName = date("F", mktime(0, 0, 0, $month, 10));
        $email_main_subject = "Daily Update - ".$hotelname." (3 Months Summary )";
        
        $sheet_data = $this->Sheet->find('first', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' =>$year,'Sheet.month' =>$month,'Sheet.department_id'=>$dept_ids), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.name', 'Sheet.email', 'Sheet.user_id', 'Sheet.month'), 'recursive' => '0'));
        $next_1_month = $month + '1';
        $next_2_month = $month + '2';
        $year1= $year; $year2= $year;
        if($next_1_month > '12'){ $next_1_month = $next_1_month - '12';  $year1= $year + '1'; }
        if($next_2_month > '12'){ $next_2_month = $next_2_month - '12'; $year2= $year + '1'; }

        $next_1_sheets = $this->Sheet->find('first', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' =>$year1,'Sheet.month' =>$next_1_month,'Sheet.department_id'=>$dept_ids), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.name', 'Sheet.email', 'Sheet.user_id', 'Sheet.month'), 'recursive' => '0'));
        $next_2_sheets = $this->Sheet->find('first', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' =>$year2,'Sheet.month' =>$next_2_month,'Sheet.department_id'=>$dept_ids), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.name', 'Sheet.email', 'Sheet.user_id', 'Sheet.month'), 'recursive' => '0'));

           if (!empty($sheet_data)) {
                    $emails = array();
                    $sheetId = $sheet_data['Sheet']['id'];
                    $sheet_name = $sheet_data['Sheet']['name'];
                    
                    $department_id = $sheet_data['Sheet']['department_id'];
                    
                    $user_id = $sheet_data['Sheet']['user_id'];
                    if (!empty($sheetId)) {

                            $user_id = trim($user_id);
                            $user_data = $this->Sheet->User->findById($user_id);

                            if (!empty($user_data)) {

                                $next1MonthData = array(); $next2MonthData = array();
                                if(!empty($next_1_sheets['Sheet']['id'])){
                                     $next1MonthData = $this->Sheet->getData($next_1_sheets['Sheet']['id']);
                                }
                                if(!empty($next_2_sheets['Sheet']['id'])){
                                     $next2MonthData = $this->Sheet->getData($next_2_sheets['Sheet']['id']);
                                }
                                
                                $data = $this->Sheet->getData($sheetId);
                                $headers = array();
                                foreach ($data[0] as $key => $value) {
                                    if ($key != "sheetId") {
                                        array_push($headers, $key);
                                    }
                                }

                                $rest_values = array();
                                $rest_values[0] = $headers;
                                for ($i = 0; $i < count($data); $i++) {
                                    foreach ($data[$i] as $key => $values) {
                                        if ($key != "sheetId") {
                                            $rest_values[$i + 1][] = $values;
                                        }
                                    }
                                }

                                $budget_array = array(); $lastYear_array = array();
                                $total_array = array();
                                foreach ($rest_values as $key => $rest_value) {
                                    if (in_array('Total', $rest_value)) {
                                        $total_array = $rest_value;
                                    }else if (in_array('Budget', $rest_value)) {
                                        $budget_array = $rest_value;
                                    }else if (in_array('LY Actual', $rest_value)) {
                                        $lastYear_array = $rest_value;
                                    }
                                }

                                $total_keys = array(); $lastYear_today = array();
                                unset($rest_values[0][0], $rest_values[0][1]);
                                foreach ($rest_values[0] as $key => $rest_value) {
                                    $total_keys[$rest_value] = $total_array[$key];
                                    $budget_today[$rest_value] = $budget_array[$key];
                                    $lastYear_today[$rest_value] = $lastYear_array[$key];
                                }
                                 $yesterday_date = date('Y-m-d',strtotime('-1 day'));
 
                                $SheetdataDetails1 = array(); $Sheetdata_1Month = array(); $budget1_today = array();
                                $lastYear1_today = array();
                                if(!empty($next1MonthData)){
                                    foreach ($next1MonthData as $values) {
                                        if ($values['Date'] == 'Total') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $Sheetdata_1Month[$tkey] = $total_key;
                                                }
                                            }
                                        }elseif ($values['Date'] == 'Budget') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $budget1_today[$tkey] = $total_key;
                                                }
                                            }
                                        }elseif ($values['Date'] == 'LY Actual') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $lastYear1_today[$tkey] = $total_key;
                                                }
                                            }
                                        }
                                    }
                                    
                                    //Get Yesterday date and data first
                                            $yest_date_data1 = ClassRegistry::init('SheetHistory')->find('first', array('conditions' => array('SheetHistory.sheet_id' => $next_1_sheets['Sheet']['id'], 'SheetHistory.date' => $yesterday_date), 'field' => 'date', 'order' => 'SheetHistory.date DESC'));
                                            if (!empty($yest_date_data1)) {
                                                $yest_date = $yest_date_data1['SheetHistory']['date'];
                                                $SheetdataDetails1 = ClassRegistry::init('SheetHistory')->find('list', array('conditions' => array('SheetHistory.sheet_id' => $next_1_sheets['Sheet']['id'], 'SheetHistory.date' => $yest_date), 'fields' => array('SheetHistory.type', 'SheetHistory.total')));
                                            }

                                            //save data once get yesterday data
                                            $SheetdataDate1 = ClassRegistry::init('SheetHistory')->find('count', array('conditions' => array('SheetHistory.sheet_id' => $next_1_sheets['Sheet']['id'], 'SheetHistory.date' => date('Y-m-d'))));
                                            if ($SheetdataDate1 == 0) {
                                                $t = 0;
                                                foreach ($Sheetdata_1Month as $tkey => $total_key) {
                                                    $Sheetdata1[$t]['sheet_id'] = $next_1_sheets['Sheet']['id'];
                                                    $Sheetdata1[$t]['date'] = date('Y-m-d');
                                                    $Sheetdata1[$t]['type'] = $tkey;
                                                    $Sheetdata1[$t]['total'] = $total_key;
                                                    $t++;
                                                }
                                                ClassRegistry::init('SheetHistory')->saveAll($Sheetdata1); //plz uncomment
                                            }
                                }
                                
                                $SheetdataDetails2 = array(); $Sheetdata_2Month = array(); $budget2_today = array();
                                $lastYear2_today = array();
                                if(!empty($next2MonthData)){
                                    foreach ($next2MonthData as $values) {
                                        if ($values['Date'] == 'Total') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $Sheetdata_2Month[$tkey] = $total_key;
                                                }
                                            }
                                        }elseif ($values['Date'] == 'Budget') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $budget2_today[$tkey] = $total_key;
                                                }
                                            }
                                        }elseif ($values['Date'] == 'LY Actual') {
                                            foreach ($values as $tkey => $total_key) {
                                                if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                                    $lastYear2_today[$tkey] = $total_key;
                                                }
                                            }
                                        }
                                    }
                                    
                                    //Get Yesterday date and data first
                                        $yest_date_data2 = ClassRegistry::init('SheetHistory')->find('first', array('conditions' => array('SheetHistory.sheet_id' => $next_2_sheets['Sheet']['id'], 'SheetHistory.date' => $yesterday_date), 'field' => 'date', 'order' => 'SheetHistory.date DESC'));
                                        if (!empty($yest_date_data2)) {
                                            $yest_date = $yest_date_data2['SheetHistory']['date'];
                                            $SheetdataDetails2 = ClassRegistry::init('SheetHistory')->find('list', array('conditions' => array('SheetHistory.sheet_id' => $next_2_sheets['Sheet']['id'], 'SheetHistory.date' => $yest_date), 'fields' => array('SheetHistory.type', 'SheetHistory.total')));
                                        }

                                        //save data once get yesterday data
                                        $SheetdataDate2 = ClassRegistry::init('SheetHistory')->find('count', array('conditions' => array('SheetHistory.sheet_id' => $next_2_sheets['Sheet']['id'], 'SheetHistory.date' => date('Y-m-d'))));
                                        if ($SheetdataDate2 == 0) {
                                            $t = 0;
                                            foreach ($Sheetdata_2Month as $tkey => $total_key) {
                                                $Sheetdata2[$t]['sheet_id'] = $next_2_sheets['Sheet']['id'];
                                                $Sheetdata2[$t]['date'] = date('Y-m-d');
                                                $Sheetdata2[$t]['type'] = $tkey;
                                                $Sheetdata2[$t]['total'] = $total_key;
                                                $t++;
                                            }
                                           ClassRegistry::init('SheetHistory')->saveAll($Sheetdata2); //plz uncomment
                                        }
                                    
                                }
                             
                                //Get Yesterday date and data first
                                $yest_date_data = ClassRegistry::init('SheetHistory')->find('first', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => $yesterday_date), 'field' => 'date', 'order' => 'SheetHistory.date DESC'));
                                $SheetdataDetails = array();
                                if (!empty($yest_date_data)) {
                                    $yest_date = $yest_date_data['SheetHistory']['date'];
                                    $SheetdataDetails = ClassRegistry::init('SheetHistory')->find('list', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => $yest_date), 'fields' => array('SheetHistory.type', 'SheetHistory.total')));
                                }

                                //save data once get yesterday data
                                $SheetdataDate = ClassRegistry::init('SheetHistory')->find('count', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => date('Y-m-d'))));
                                if ($SheetdataDate == 0) {
                                    $t = 0;
                                    foreach ($total_keys as $tkey => $total_key) {
                                        $Sheetdata[$t]['sheet_id'] = $sheetId;
                                        $Sheetdata[$t]['date'] = date('Y-m-d');
                                        $Sheetdata[$t]['type'] = $tkey;
                                        $Sheetdata[$t]['total'] = $total_key;
                                        $t++;
                                    }
                                   ClassRegistry::init('SheetHistory')->saveAll($Sheetdata); //plz uncomment
                                }
                                
                                //echo '<pre>';print_r($total_keys); print_r($lastYear_today);  print_r($budget_today); exit;

                                $emails = ClassRegistry::init('EmailSummarySheet')->find('list', array('conditions'=>array('client_id'=>$client_id,'EmailSummarySheet.type'=>'summary'),'fields' => array('email')));
                                        
                                if (!empty($emails)) {
                                        set_time_limit(40);                                        
                                        $email_from = "duncan.bramwell@revenue-performance.com"; // The email you are sending from (example)
                                        $email_subject = $email_main_subject;
                                        $summary_table = "<tr valign='top'>
						<td style='padding: 0px 3px 10px 0px;'>
							<table cellpadding='0' border='0' style='font-family: verdana,arial,sans-serif;font-size:11px;	color:#333333;border-width: 1px;border-color: #666666;border-collapse: collapse;line-height:20px;'>
                                                        <tr>
                                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>&nbsp;</td>
                                                        <td colspan='2' style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>".$monthName."</td>";

                                                        if(!empty($Sheetdata_1Month)){ 
                                                            $summary_table .= "<td colspan='2' style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>".date('F', mktime(0, 0, 0, $next_1_sheets['Sheet']['month'], 10))."</td>";
                                                        }
                                                        if(!empty($Sheetdata_2Month)){ 
                                                           $summary_table .= "<td colspan='2' style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>".date('F', mktime(0, 0, 0, $next_2_sheets['Sheet']['month'], 10))."</td>";
                                                        }
                                                        
                                                        $summary_table .= "</tr>
							<tr>
								<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>&nbsp;</td>
								<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Today</td>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Yesterday</td>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Today</td>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Yesterday</td>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Today</td>
                                                                <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Yesterday</td>";
                                        
                                        $summary_table .= "</tr>";
                                        
                                        
                                        //BOB
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total BOB</td>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $total_keys['BOB'] . "</td>";
                                        if (count($SheetdataDetails) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails['BOB']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 1 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_1Month['BOB'] . "</td>";
                                        if (count($SheetdataDetails1) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails1['BOB']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 2 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_2Month['BOB'] . "</td>";
                                        if (count($SheetdataDetails2) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails2['BOB']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "</tr>";
                                        
                                        
                                        //ADR
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total ADR</td>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $total_keys['ADR'] . "</td>";
                                        if (count($SheetdataDetails) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails['ADR']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 1 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_1Month['ADR'] . "</td>";
                                        if (count($SheetdataDetails1) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails1['ADR']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 2 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_2Month['ADR'] . "</td>";
                                        if (count($SheetdataDetails2) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails2['ADR']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "</tr>";
                                        
                                        //Revenue
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total Revenue</td>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $total_keys['Revenue'] . "</td>";
                                        if (count($SheetdataDetails) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails['Revenue']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 1 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_1Month['Revenue'] . "</td>";
                                        if (count($SheetdataDetails1) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails1['Revenue']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 2 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_2Month['Revenue'] . "</td>";
                                        if (count($SheetdataDetails2) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails2['Revenue']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "</tr>";
                                        
                                        $summary_table .= "<tr><td colspan='7'>&nbsp;</td></tr>";
                                        
                                        //Budget Rooms
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Budget Rooms</td>";

                                        if(!empty($budget_today['BOB'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $budget_today['BOB'] . "</td>";                                            
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 1 month
                                        if(!empty($budget1_today['BOB'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $budget1_today['BOB'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 2 month
                                        if(!empty($budget2_today['BOB'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $budget2_today['BOB'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        $summary_table .= "</tr>";
                                        
                                        //Forecast Rooms
                                        $summary_table .= "<tr><td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Forecast Rooms</td>";
                                                                                
                                        if(!empty($total_keys['Fcst Rooms'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $total_keys['Fcst Rooms'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        
                                        if (count($SheetdataDetails) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails['Fcst Rooms']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 1 month
                                        if(!empty($Sheetdata_1Month['Fcst Rooms'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_1Month['Fcst Rooms'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>NA</td>";
                                        }
                                        
                                        if (count($SheetdataDetails1) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails1['Fcst Rooms']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 2 month
                                        if(!empty($Sheetdata_2Month['Fcst Rooms'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $Sheetdata_2Month['Fcst Rooms'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        
                                        if (count($SheetdataDetails2) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails2['Fcst Rooms']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "</tr>";
                                       
                                        $summary_table .= "<tr><td colspan='7'>&nbsp;</td></tr>";
                                        
                                        //Budget ADR
                                        $summary_table .= "<tr><td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Budget ADR</td>";
                                        if(!empty($budget_today['ADR'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $budget_today['ADR'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 1 month
                                        if(!empty($budget1_today['ADR'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $budget1_today['ADR'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 2 month
                                        if(!empty($budget2_today['ADR'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $budget2_today['ADR'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        $summary_table .= "</tr>";
                                        
                                        //Forecast ADR
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Forecast ADR</td>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $total_keys['ADR Fcst'] . "</td>";
                                        if (count($SheetdataDetails) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails['ADR Fcst']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 1 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_1Month['ADR Fcst'] . "</td>";
                                        if (count($SheetdataDetails1) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails1['ADR Fcst']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 2 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_2Month['ADR Fcst'] . "</td>";
                                        if (count($SheetdataDetails2) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails2['ADR Fcst']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "</tr>";
                                        
                                        $summary_table .= "<tr><td colspan='7'>&nbsp;</td></tr>";
                                        
                                        //Budget Revenue
                                        $summary_table .= "<tr><td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Budget Revenue</td>";
                                        if(!empty($budget_today['Revenue'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $budget_today['Revenue'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 1 month
                                        if(!empty($budget1_today['Revenue'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $budget1_today['Revenue'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 2 month
                                        if(!empty($budget2_today['Revenue'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $budget2_today['Revenue'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        $summary_table .= "</tr>";
                                        
                                        //Forecast ADR
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Forecast Revenue</td>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $total_keys['Revenue'] . "</td>";
                                        if (count($SheetdataDetails) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails['Revenue']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 1 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_1Month['Revenue'] . "</td>";
                                        if (count($SheetdataDetails1) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails1['Revenue']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 2 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_2Month['Revenue'] . "</td>";
                                        if (count($SheetdataDetails2) > 0) {
                                            $SheetdataDetails1_new = (str_replace(".00", "", $SheetdataDetails2['Revenue']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails1_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "</tr>";
                                        
                                        //LY Revenue
                                        $summary_table .= "<tr><td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>LY Revenue</td>";
                                        if(!empty($lastYear_today['Revenue'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $lastYear_today['Revenue'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 1 month
                                        if(!empty($lastYear1_today['Revenue'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $lastYear1_today['Revenue'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 2 month
                                        if(!empty($lastYear2_today['Revenue'])){
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $lastYear2_today['Revenue'] . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        $summary_table .= "</tr>";
                                        
                                        $summary_table .= "<tr><td colspan='7'>&nbsp;</td></tr>";
                                        
                                        //Rooms to Budget
                                        $m1_bob_budget = str_replace(',','',$budget_today['BOB']) - str_replace(',','',$total_keys['BOB']);
                                        $m2_bob_budget = str_replace(',','',$budget1_today['BOB']) - str_replace(',','',$Sheetdata_1Month['BOB']);
                                        $m3_bob_budget = str_replace(',','',$budget2_today['BOB']) - str_replace(',','',$Sheetdata_2Month['BOB']);
                                        
                                        if($m1_bob_budget == ''){ $m1_bob_budget = 'NA'; }
                                        if($m2_bob_budget == ''){ $m2_bob_budget = 'NA'; }
                                        if($m3_bob_budget == ''){ $m3_bob_budget = 'NA'; }
                                        
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Rooms to Budget</td>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $m1_bob_budget . "</td>";
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 1 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" .$m2_bob_budget . "</td>";
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 2 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $m3_bob_budget . "</td>";
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        $summary_table .= "</tr>";
                                        
                                        //ADR to Budget
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>ADR to Budget</td>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . round(((str_replace(',','',$budget_today['Revenue']) - str_replace(',','',$total_keys['Revenue'])) / $m1_bob_budget ),'2'). "</td>";
                                        if (count($SheetdataDetails) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails['Sell Rate']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 1 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . round(((str_replace(',','',$budget1_today['Revenue']) - str_replace(',','',$Sheetdata_1Month['Revenue'])) / $m2_bob_budget),'2') . "</td>";
                                        if (count($SheetdataDetails1) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails1['Sell Rate']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 2 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . round(((str_replace(',','',$budget2_today['Revenue']) - str_replace(',','',$Sheetdata_2Month['Revenue'])) /$m3_bob_budget),'2') . "</td>";
                                        if (count($SheetdataDetails2) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails2['Sell Rate']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "</tr>";
                                        
                                        //Rev to Budget
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Rev to Budget</td>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . (str_replace(',','',$budget_today['Revenue']) - str_replace(',','',$total_keys['Revenue'])) . "</td>";
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 1 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" .(str_replace(',','',$budget1_today['Revenue']) - str_replace(',','',$Sheetdata_1Month['Revenue'])) . "</td>";
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 2 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . (str_replace(',','',$budget2_today['Revenue']) - str_replace(',','',$Sheetdata_2Month['Revenue'])) . "</td>";
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        $summary_table .= "</tr>";
                                        
                                        $summary_table .= "<tr><td colspan='7'>&nbsp;</td></tr>";
                                        
                                        //RevPAR
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>RevPAR</td>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $total_keys['RevPAR']. "</td>";
                                        if (count($SheetdataDetails) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails['RevPAR']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 1 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_1Month['RevPAR'] . "</td>";
                                        if (count($SheetdataDetails1) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails1['RevPAR']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 2 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_2Month['RevPAR'] . "</td>";
                                        if (count($SheetdataDetails2) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails2['RevPAR']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "</tr>";
                                        
                                        if($budget_today['RevPAR'] == ''){ $budget_today['RevPAR'] = 'NA'; }
                                        if($budget1_today['RevPAR'] == ''){ $budget1_today['RevPAR'] = 'NA'; }
                                        if($budget2_today['RevPAR'] == ''){ $budget2_today['RevPAR'] = 'NA'; }
                                        //Budget RevPAR
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Budget RevPAR</td>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>". $budget_today['RevPAR'] . "</td>";
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 1 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" .$budget1_today['RevPAR'] . "</td>";
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        //Next 2 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $budget2_today['RevPAR'] . "</td>";
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'></td>";
                                        $summary_table .= "</tr>";
                                        
                                        //Forecast RevPAR
                                        $summary_table .= "<tr>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Forecast RevPAR</td>
                                        <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $total_keys['RevPAR Fcst']. "</td>";
                                        if (count($SheetdataDetails) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails['RevPAR Fcst']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 1 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" .  $Sheetdata_1Month['RevPAR Fcst'] . "</td>";
                                        if (count($SheetdataDetails1) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails1['RevPAR Fcst']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        //Next 2 month
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $Sheetdata_2Month['RevPAR Fcst'] . "</td>";
                                        if (count($SheetdataDetails2) > 0) {
                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails2['RevPAR Fcst']));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>";
                                        }else{
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>NA</td>";
                                        }
                                        $summary_table .= "</tr>";
                                        
                                        
                                        $summary_table .= "</table>
						</td>
						</tr>";
                                        
                                      
                                        $email_txt = "<table cellspacing='0' cellpadding='0' border='0' >
                                        <tr>
                                        <td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>MyDashboard
                                        </td>
                                        </tr>
                                        <tr>
                                        <td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' valign='top' style='font-size: 12px;'>
                                        <div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br>
                                        <table cellpadding='0' style='margin-top: 5px;border:0;'>
                                        <tr valign='top'>
                                        <td style='padding: 0px 3px 10px 0px;'>
                                        <FONT SIZE=2 FACE='Arial'>Please find below the Summary of your revenue forecast.</FONT>
                                        </td>
                                        </tr>" . $summary_table . "
                                        </table>
                                        <br><br>
                                                <div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
                                                <br>
                                                </div>
                                                <div style='margin: 0pt;'>Thanks &amp; Regards,<br>MyDashboard<br>
                                                <a href='http://www.myrevenuedashboard.net'>www.myrevenuedashboard.net</a>
                                                </div>
                                                </td>
                                                <td align='left' width='150' valign='top' style='padding-left: 15px;'>
                                                <table cellspacing='0' cellpadding='0' width='100%'>
                                                <tbody><tr>
                                                <td style='padding: 10px'>
                                                <div style='margin-bottom: 15px;'>
                                                <a target='blank' href='http://www.revenue-performance.com'>
                                                        <img src='http://" . $_SERVER['HTTP_HOST'] . "/img/RP-logo.png' alt='' style='border:0px;'>
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
                                              
                                        //echo $email_txt; exit;
                                        
                                        $semi_rand = md5(time());
                                        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
                                        $headers = "From: $email_from"; // Who the email is from (example)
                                        $headers .= "\nMIME-Version: 1.0\n" .
                                                "Content-Type: multipart/mixed;\n" .
                                                " boundary=\"{$mime_boundary}\"";
                                        @$email_message .= "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type:text/html;charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $email_txt;
                                        $email_message .= "\n\n";
                                        
                                        $data = chunk_split(base64_encode($data));
                                        
                                        $email_message .= "--{$mime_boundary}\n" .
                                                "Content-Type: text/html;charset=iso-8859-1;\n" .
                                                "Content-Transfer-Encoding: base64\n\n" .
                                                $data . "\n\n" .
                                                "--{$mime_boundary}--\n";
     
                                        foreach ($emails as $emls) {
                                            $email_to = $emls; // The email you are sending to (example)
                                            if(!empty($email_to)){
                                               if (mail($email_to, $email_subject, $email_message, $headers)) {
                                                    echo 'Mail Send <br>';
                                                } else {
                                                    echo 'Mail Not Send <br>';
                                                } 
                                            }
                                    }
                                }

                                unlink($path);
                                rmdir($file_path);
                            }//end foreach
                       
                    }//end if statement
               
            }
        }
        
        $this->redirect('https://hedsor.revenue-performance.com/admin/notification');
        exit;
    }

    
}//end class