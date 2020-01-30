<?php
class AdminsController extends AppController {

	var $name = 'Admins';
	var $helpers = array('Html', 'Javascript', 'Session');
	var $components = array('RequestHandler', 'Sendemail','Session','Email','Cookie');


	function index() {
            
            //echo 'ondex'; exit;
            
		$this->Admin->recursive = 0;
		$this->set('admins', $this->paginate());
                
                if($this->Session->read('Auth.Admin.IsSubAdmin')){
                    $subadmin_id = $this->Session->read('Auth.Admin.SubAdminID');
                    $this->Subadmin = ClassRegistry::init('Subadmin');
                    $subadmin_client = $this->Subadmin->read(null, $subadmin_id);
                    $allclients = array();
                    foreach($subadmin_client['SubadminClient'] as $clients)
                    {
                            array_push($allclients,$clients['client_id']);
                    }
                    $this->Client = ClassRegistry::init('Client');
                    $all_hotels = $this->Client->find('all',array('conditions'=>array('Client.status !=' => 2,'Client.id'=>$allclients),'fields'=>'id,hotelname','order'=>'Client.hotelname ASC'));
                    //asort($all_hotels);
                    $this->set('all_hotels', $all_hotels);
                    
                }else{
                    $this->Client = ClassRegistry::init('Client');
                    $all_hotels = $this->Client->find('all',array('conditions'=>array('Client.status !=' => 2),'fields'=>'id,hotelname','order'=>'Client.hotelname ASC'));
                    //asort($all_hotels);
                    $this->set('all_hotels', $all_hotels);
                }
                
	}

        function index_new() {
		$this->Admin->recursive = 0;
		$this->set('admins', $this->paginate());
                
                //Added on 3 July 2013 for charts for all clients
                $this->Client = ClassRegistry::init('Client');
                $all_hotels = $this->Client->find('all',array('conditions'=>array('Client.status !=' => 2),'fields'=>'id,hotelname'));
                $this->set('all_hotels', $all_hotels);
                
                
                
	}
        
        public function get_activation_table($client_id){
            //Configure::write('debug',2);
            $this->layout = false;
            $this->autoRender = false;
            
            App::import('Model', 'Department');
            $this->Department = new Department();
            App::import('Model', 'Client');
            $this->Client = new Client();
            App::import('Model', 'Sheet');
            $this->Sheet = new Sheet();

            $month = date('m');
            $year = date('Y');
            
            $client_id_list = $this->Client->find('list', array('conditions' => array('OR'=>array('Client.parent_id' => $client_id,'Client.id' => $client_id), 'Client.status' => 1), 'fields' => 'id', 'recursive' => '0'));

            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $client_id_list, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('all', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));

            $dept_ids = array();
            unset($dept_ids);
            foreach ($dept_data as $dept) {
                $dept_ids[] = $dept['Department']['id'];
            }
//echo 'here'; 
//echo '<pre>'; print_r($dept_ids); echo '</pre>';
//exit;
            $monthName = date("F", mktime(0, 0, 0, $month, 10));
    
            $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' =>$year,'Sheet.month' =>$month,'Sheet.department_id'=>$dept_ids), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.name', 'Sheet.email', 'Sheet.user_id', 'Sheet.month'), 'recursive' => '0'));

            
            //exit;
            if (!empty($all_sheets)) {
                $summary_table = '';
                foreach ($all_sheets as $sheet_data) {
                    $sheetId = $sheet_data['Sheet']['id'];
                    $sheet_name = $sheet_data['Sheet']['name'];
                    $department_id = $sheet_data['Sheet']['department_id'];
                    $user_id = $sheet_data['Sheet']['user_id'];

                    $user_id = trim($user_id);
                    $user_data = $this->Sheet->User->findById($user_id);

                    if (!empty($user_data)) {
                        //Sheet data work started here
                        $sheet_value = $this->Sheet->getData($sheetId);
                        
                        //echo '<pre>'; print_r($sheet_value); exit;

                        $budget_today = array(); $lastYear_today = array();
                        //Get previous date and data first
                        foreach ($sheet_value as $values) {
                            if ($values['Date'] == 'Total') {
                                foreach ($values as $tkey => $total_key) {
                                    if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                        $Sheetdata_today[$tkey] = $total_key;
                                    }
                                }
                            }elseif ($values['Date'] == 'Budget') {
                                foreach ($values as $tkey => $total_key) {
                                    if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                        $budget_today[$tkey] = $total_key;
                                    }
                                }
                            }elseif ($values['Date'] == 'LY Actual') {
                                foreach ($values as $tkey => $total_key) {
                                    if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                        $lastYear_today[$tkey] = $total_key;
                                    }
                                }
                            }
                        }//end foreach to save data

                        $SheetdataDetails = array();

                        $yesterday_date = date('Y-m-d',strtotime('-1 day'));
                        $yest_date_data = ClassRegistry::init('SheetHistory')->find('first', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => $yesterday_date), 'field' => 'date', 'order' => 'SheetHistory.date DESC'));

                        if (!empty($yest_date_data)) {
                            $yest_date = $yest_date_data['SheetHistory']['date'];
                            $SheetdataDetails = ClassRegistry::init('SheetHistory')->find('list', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => $yest_date), 'fields' => array('SheetHistory.type', 'SheetHistory.total')));
                        }
                            $monthName = date("F", mktime(0, 0, 0, $sheet_data['Sheet']['month'], 10));
                            $summary_table .= "<table><tr><td><b>Daily Update - ".$user_data['Client']['hotelname']."</b></td></tr>
                                            <tr valign='top'>
                                                    <td style='padding: 0px 3px 10px 0px;'>
                                                            <table cellpadding='0' border='0' style='font-family: verdana,arial,sans-serif;font-size:11px;	color:#333333;border-width: 1px;border-color: #666666;border-collapse: collapse;line-height:20px;'>
                                                            <tr>
                                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>&nbsp;</td>
                                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Today</td>";
                            if (count($SheetdataDetails) > 0) {
                                $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Yesterday</td>
                                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'> % Change </td>
                                                                    <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>&nbsp;</td>";
                            }
                            $summary_table .= "</tr>";
                            foreach ($Sheetdata_today as $key => $total_key) {
                                if (($key != 'TripAdvisor') && ($key != 'BAR Level') && ($key != 'Notes')) {
                                    $total_key_new = (str_replace(".00", "", $total_key));
                                    $summary_table .= "<tr>
                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total " . $key . "</td>
                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;	background-color: #ffffff;'>" . $total_key_new . "</td>";
                                    if (count($SheetdataDetails) > 0) {
                                        //$change = str_replace(",", "", $total_key) - str_replace(",", "", $SheetdataDetails[$key]);
                                        
                                        if($key == 'Sell Rate'){
                                            $color = '';
                                        }else if($key == 'Pickup Req'){
                                            $percent_change = 100.0 * (str_replace(",", "", $total_key) - str_replace(",", "", $SheetdataDetails[$key])) / str_replace(",", "", $total_key);
                                            $percent_change = round($percent_change, 2);
                                            if ($percent_change == 0) {
                                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_White.png" alt="White">';
                                            } elseif ($percent_change < 0) {
                                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Green.png" alt="Red">';
                                            } elseif ($percent_change > 0 && $percent_change < 5) {
                                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Yellow.png" alt="Yellow">';
                                            } elseif ($percent_change > 5) {
                                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Red.png" alt="Green">';
                                            }
                                        }else{
                                            $percent_change = 100.0 * (str_replace(",", "", $total_key) - str_replace(",", "", $SheetdataDetails[$key])) / str_replace(",", "", $total_key);
                                            $percent_change = round($percent_change, 2);
                                            if ($percent_change == 0) {
                                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_White.png" alt="White">';
                                            } elseif ($percent_change < 0) {
                                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Red.png" alt="Red">';
                                            } elseif ($percent_change > 0 && $percent_change < 5) {
                                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Yellow.png" alt="Yellow">';
                                            } elseif ($percent_change > 5) {
                                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Green.png" alt="Green">';
                                            }
                                        }
                                        
                                        $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails[$key]));
                                        $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>
                                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $percent_change . " %</td>
                                                            <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>";
                                    }

                                    $summary_table .= "</tr>";
                                }//enf if key value check
                            }//End Foreach

                            //code to LY Actual line
                            if(!empty($lastYear_today)){

                                $variance = str_replace(',','',$Sheetdata_today['Revenue']) - str_replace(',','',$lastYear_today['Revenue']);
                                $variance = number_format($variance,'2');

                                if ($variance == 0) {
                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_White.png" alt="White">';
                                } elseif ($variance < 0) {
                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Red.png" alt="Red">';
                                } elseif ($variance > 0) {
                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Green.png" alt="Green">';
                                }

                                $color = '';
                                $summary_table .= "<tr>
                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total LY Actual</td>
                                    <td colspan='3' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $lastYear_today['Revenue'] . "</td>
                                    <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>
                                    </tr>";
                            }
                            
                            //code to add budget line
                            if(!empty($budget_today)){

                                $variance = str_replace(',','',$Sheetdata_today['Revenue']) - str_replace(',','',$budget_today['Revenue']);
                                $variance = number_format($variance,'2');

                                
                                $color = '';
                                $summary_table .= "<tr>
                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total Budget</td>
                                                    <td colspan='3' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $budget_today['Revenue'] . "</td>
                                                    <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>
                                                    </tr>";
                                if ($variance == 0) {
                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_White.png" alt="White">';
                                } elseif ($variance < 0) {
                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Red.png" alt="Red">';
                                } elseif ($variance > 0) {
                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Green.png" alt="Green">';
                                }
                                $summary_table .= "<tr>
                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total Variance</td>
                                                    <td colspan='3' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $variance . "</td>
                                                    <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>
                                                    </tr>";

                            }
                            
                            $summary_table .= "</table></td></tr></table>";

                    }//end foreach
                }//end if statement
                echo $summary_table;
                set_time_limit(40);
            }
            exit;
        }
        
        
        function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('forget_password','get_hotel_department_list','get_activation_table','timings','tee_times');
                $this->Auth->allow('get_pickup_chart_weekly','get_adr_pickup_chart','get_adr_pickup_chart','send_weekly_report','get_adr_pickup_chart_weekly');
	}

        
        
        
	/**
	 * Login action for admin
	 */
	function login() {
            
            //print_r($this->Auth->_loggedIn); exit;
            
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
			$this->Session->setFlash(__('Invalid admin', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('admin', $this->Admin->read(null, $id));
	}

/*
	function add() {
		if (!empty($this->data)) {
			$this->Admin->create();
			if ($this->Admin->save($this->data)) {
				$this->Session->setFlash(__('The admin has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The admin could not be saved. Please, try again.', true));
			}
		}
	}
*/

	function edit() {
		$id = $this->Auth->user('id');
	
		if (!empty($this->data)) {
			if ($this->Admin->save($this->data)) {
				$this->Session->setFlash(__('The admin has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The admin could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data) || isset($this->data['Admin']['password'])) {
			$this->data = $this->Admin->read(null, $id);
		}
	}
        
        function archive() {
		
                $this->Client = ClassRegistry::init('Client');
                $all_hotels = $this->Client->find('list',array('conditions'=>array('Client.status !=' => 2),'fields'=>'id,hotelname','order'=>'id DESC'));
                $this->set('all_hotels', $all_hotels);
                
                $adminData = $this->Admin->read(null, '1');
                $selected_hotes = explode(',',$adminData['Admin']['archive_hotel_ids']);
                $this->set('selected_hotes',$selected_hotes );
                
	
		if (!empty($this->data)) {
                        //echo '<pre>'; print_r($this->data); exit;
                        $this->data['Admin']['id'] = '1';
                        $this->data['Admin']['archive_hotel_ids'] = implode(',',$this->data['Admin']['client_id']);
                        
			if ($this->Admin->save($this->data)) {
				$this->Session->setFlash(__('Archive List has been saved', true));
			} else {
				$this->Session->setFlash(__('Archive List could not be saved. Please, try again.', true));
			}
                        $this->redirect(array('action'=>'archive'));
		}
	}

          function hotel_package() {
		
                $this->Client = ClassRegistry::init('Client');
                $all_hotels = $this->Client->find('list',array('conditions'=>array('Client.status !=' => 2),'fields'=>'id,hotelname','order'=>'id DESC'));
                $this->set('all_hotels', $all_hotels);
                
                $adminData = $this->Admin->read(null, '1');
                $selected_hotes = explode(',',$adminData['Admin']['advance_package_hotel_ids']);
                $this->set('selected_hotes',$selected_hotes );
                
		if (!empty($this->data)) {
                        $this->data['Admin']['id'] = '1';
                        $this->data['Admin']['advance_package_hotel_ids'] = implode(',',$this->data['Admin']['client_id']);
                        
			if ($this->Admin->save($this->data)) {
				$this->Session->setFlash(__('Hotel with Advanced Package has been saved', true));
			} else {
				$this->Session->setFlash(__('Hotel List with Advanced Package could not be saved. Please, try again.', true));
			}
                        $this->redirect(array('action'=>'hotel_package'));
		}
	}

        
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for admin', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Admin->softDelete($id)) {
			$this->Session->setFlash(__('Admin deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Admin was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	function forget_password()
	{
		if(!empty($this->data))
		{
			$val = $this->data['Admin']['email'];
			$condition['conditions'] = array("Admin.email" => $val, "Admin.status !=" =>2 );
			$condition['recursive']= -1;
			$condition['limit']= 1;

		# getting userdata
			$datalist = $this->Admin->find('first', $condition);
			//debug($datalist);
		//	exit;
		if (isset($datalist) && !empty($datalist['Admin']['email']))
		{
			if($datalist['Admin']['status'] == 2)
			{
				$this->Session->setFlash(__('<div class="errorCommnt" id="server_message">The account is Blocked. Please contact Administrator !</div>',true));
				$this->redirect(array('controller' => 'admins','action' => 'forget_password'));
			}
			if($datalist['Admin']['status'] == 0)
			{
				$this->data['Admin']['email'] = '';
				$this->Session->setFlash(__('<div class="errorCommnt" id="server_message">Email address does not exist !</div>',true));
				$this->redirect(array('controller' => 'admins','action' => 'forget_password'));
			} else {
				//$this->randomnumber();
				$uniqueid = substr(rand(),0,5);
				$this->data['Admin']['password'] = $this->Auth->password($uniqueid);
				$this->data['Admin']['id'] = $datalist['Admin']['id'];
				$result = $this->Admin->save($this->data);
				if($result)
				{
					$this->data['Admin']['username'] = $datalist['Admin']['username'];
					$this->data['Admin']['password'] = $uniqueid;
					
					$getuserinfo = $this->data['Admin'];
					$to = $datalist['Admin']['email'];
					$username = $datalist['Admin']['username'];
					$addcc = $this->Admin->Field('email', array('id' =>'1'));
					# mail Section
					
					$result = $this->Sendemail->userforgotpassword($to, $addcc, $getuserinfo);
					if($result)
					{
					$this->data['Admin']['email'] = '';
					$this->Session->setFlash(__('<div class="successCommnt" id="server_message">Your password has been sent to your email.</div>',true));
					$this->redirect(array('controller' => 'admins','action' => 'login'));
					}
				}
			}
		} else {
			$this->data['Admin']['email'] = '';
			$this->Session->setFlash(__('<div class="errorCommnt" id="server_message">Email address does not exist !</div>',true));
			$this->redirect(array('controller' => 'admins','action' => 'forget_password'));
		}
		}
	}
        
        function get_chart($client_id=null,$month=null,$year=null,$departmentId=null){
            //Configure::write('debug',2);
            //$this->autoRender = false;
		$this->layout = '';
                
                App::import('Model','Client');
		$this->Client = new Client();
                
                if($month == '0' || empty($month)){
                $month = date('m');
                }
                
                if($year == '0' || empty($year)){
                $year = date('Y');
                }
                
                $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname'));
               $hotelname = $client_name['Client']['hotelname'];
               
               $this->set('client_id',$client_id);
                
                  $dept_ids = array();
                   if($departmentId == 0){
                       $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
                       $departmentName = 'Rooms';
                       $columns = array('BOB'=>'62','ADR'=>'64');
                       $columnNames = array('BOB'=>'BOB','ADR'=>'ADR');
                   }else{
                       $this->Client->Department->recursive = -1;
                        $condition = array('Department.id' => $departmentId);
                        $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'name', 'recursive' => '0'));
                        $departmentName = $dept_data['Department']['name'];
                        if (strstr($departmentName,'Room')){
                            $columns = array('BOB'=>'62','ADR'=>'64');
                            $columnNames = array('BOB'=>'BOB','ADR'=>'ADR');
                        }elseif ($departmentName == 'Spa'){
                            $columns = array('BOB'=>'90','ADR'=>'163');
                            $columnNames = array('BOB'=>'Treatments','ADR'=>'Ave Spend Booked');
                        }elseif ($departmentName == 'Restaurant'){
                            $columns = array('BOB'=>'93','ADR'=>'85');
                            $columnNames = array('BOB'=>'Covers','ADR'=>'Ave Spend');
                        }elseif ($departmentName == 'Banqueting'){
                            $columns = array('BOB'=>'172','ADR'=>'115');
                            $columnNames = array('BOB'=>'Conf Rev','ADR'=>'RevPASM');
                        }else{
                            echo 'Chart Not available';
                            exit;
                        }
                        $dept_ids[] = $departmentId;
                   }
                   $this->set('departmentName',$departmentName);
                   $this->set('columnNames',$columnNames);

                App::import('Model','Sheet');
		$this->Sheet = new Sheet();
                
            
            $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
            $sheetId = $all_sheets[0]['Sheet']['id'];
             unset($bob_value);
              unset($adr_value);
              
             $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year);
              
           $bob_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columns['BOB'],'Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
            $adr_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columns['ADR'],'Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));

                          $bob_arr = '';
                    $adr_arr = '';
                    $date_arr = '';
                    $bob_count = 0; $date_count = 0; $adr_count = 0;
                    foreach($bob_value as $bob){
                        if($bob_count == ''){
                        $bob_arr = str_replace( ',', '',$bob['Datum']['value']);
                        }else{
                          $bob_arr = $bob_arr.','. str_replace( ',', '',$bob['Datum']['value']);  
                        }
                        $bob_count++;
                    }
                    $adr_arr = '';
                    foreach($adr_value as $adr){
                       $adr['Datum']['value'] = str_replace( ',', '',$adr['Datum']['value'] );
                        $adr1 = number_format($adr['Datum']['value'], 2);
                        $adr_final = $adr1;
                        if($adr_count == ''){
                        $adr_arr =  str_replace( ',', '',$adr_final );
                        }else{
                          $adr_arr = $adr_arr.','. str_replace( ',', '',$adr_final );  
                        }
                        $adr_count++;
                    }
                    $date_arr = '';
                    for($i=1;$i<=count($adr_value);$i++){
                        if($i == '1'){
                        $date_arr = "'".$i."'";
                        }else{
                          $date_arr = $date_arr.",'".$i."'";  
                        }
                        
                    }
                $bob_arr = '['.$bob_arr.']'; 
                $adr_arr =  '['.$adr_arr.']';
               $date_arr =  '['.$date_arr.']';
                    
               $this->set('bob_arr',$bob_arr);
               $this->set('adr_arr',$adr_arr);
               $this->set('date_arr',$date_arr);
                $this->set('hotelname',$hotelname);
               
        }
        
        
        
        function get_pickup_chart($client_id=null,$pickup_date=null,$month=null){
            
            //Configure::write('debug',2);
            //$this->autoRender = false;
		$this->layout = '';
                
                App::import('Model','Client');
		$this->Client = new Client();
                
                if($month == '0' || empty($month)){
                $month = date('m');
                }
                
                if($year == '0' || empty($year)){
                $year = date('Y');
                }
                
                $today = date('Y-m-d');
                
                $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname'));
                $hotelname = $client_name['Client']['hotelname'];
               
                $this->set('client_id',$client_id);
                
                $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
                
                App::import('Model','Sheet');
		$this->Sheet = new Sheet();
//Column Id -> BOB 62 //,'Datum.column_id','Datum.value','Datum.date'
                //Column Id -> Fcst Rooms 63
                $columns = array('62');
                $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
             
                
                $present_month_sheets = $this->Sheet->find('first',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>date('Y'),'Sheet.month'=>date('m')),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
                $present_month_sheet_id = $present_month_sheets['Sheet']['id'];
                // echo 'Sheet Id:'.$all_sheets[0]['Sheet']['id'];
            
                $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
                $sheetId = $all_sheets[0]['Sheet']['id'];
                  unset($bob_value);
                  unset($bob_fcst_value);
                  unset($bob_pickup_value);

                 //   $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,date('m'), date('Y'));
                  
                  $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year);
             
            $bob_pickup_value =  ClassRegistry::init('BobData')->find('all',array('conditions'=>array('sheet_id'=>$present_month_sheet_id,'date !='=>'0','DATE(created)'=>$pickup_date,'date >='=>'1','date <='=>$days_in_presnt_month),'fields'=> array('value'),'order'=>'date ASC'));
       //print_r($bob_pickup_value);      
           $bob_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'62','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
           $bob_fcst_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'63','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
           
           
                  $bob_arr = ''; $bob_fcst_arr = '';  $bob_pickup_arr = '';
                   
                    $date_arr = '';
                    $bob_count = 0; $bob_pickup_count = 0; $date_count = 0; $bob_fcst_count = 0;
                    $i = '0';
                    foreach($bob_value as $bob){
                        if($bob_count == ''){
                        $bob_arr = $bob['Datum']['value'];
                        }else{
                          $bob_arr = $bob_arr.','.$bob['Datum']['value'];  
                        }
                        $bob_count++;
                        $i++;
                    }
                   
                    
                     if(!empty($bob_pickup_value)){
                // print_r($bob_pickup_value);
//                   exit;
                         $i = '0';
                         foreach($bob_pickup_value as $bob_pickup){
                             
                             $bob_pickup_val = $bob_value[$i]['Datum']['value'] - $bob_pickup['BobData']['value'];
                             
                        if($bob_pickup_count == ''){
                        $bob_pickup_arr = $bob_pickup_val;
                        }else{
                          $bob_pickup_arr = $bob_pickup_arr.','.$bob_pickup_val;  
                        }
                        $bob_pickup_count++; $i++;
                    }
                   $bob_pickup_arr = '['.$bob_pickup_arr.']'; 

                   // $bob_pickup_arr = '[0.0, 35.0, 55.0, 29.0, 36.0, 35.0,85.0, 87.0, 35.0,38.0, 14.0, 0.0,1.0,4.0,41.0,100.0,63.0,65.0,35.0,58.0,26.0,31.0,23.0,98.0,8.0,62.0,67.0,84.0,87.0,7.0]';
                    
              }else{
                  $bob_pickup_arr = '[0.0, 0.0, 0.0,0.0, 0.0, 0.0,0.0, 0.0, 0.0,0.0, 0.0, 0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0]';
                  
              }
              
            //  echo $bob_pickup_arr;
             
                    $i = '0';
                    foreach($bob_fcst_value as $bob_fcst){
                        
                        $bob_fcst['Datum']['value'] = $bob_fcst['Datum']['value'] - $bob_value[$i]['Datum']['value'];
                        
                        if($bob_fcst_count == ''){
                        $bob_fcst_arr = $bob_fcst['Datum']['value'];
                        }else{
                          $bob_fcst_arr = $bob_fcst_arr.','.$bob_fcst['Datum']['value'];  
                        }
                        $bob_fcst_count++;
                        $i++;
                    }
                    
                    $date_arr = '';
                    for($i=1;$i<=$days_in_presnt_month;$i++){
                        if($i == '1'){
                        $date_arr = "'".$i."'";
                        }else{
                          $date_arr = $date_arr.",'".$i."'";  
                        }
                        
                    }
                $bob_arr = '['.$bob_arr.']'; 
                
                $bob_fcst_arr= '['.$bob_fcst_arr.']'; 
               
               $date_arr =  '['.$date_arr.']';
                    
               $this->set('bob_arr',$bob_arr);
               $this->set('bob_fcst_arr',$bob_fcst_arr);
               
               $this->set('date_arr',$date_arr);
                $this->set('hotelname',$hotelname);
               $this->set('bob_pickup_arr',$bob_pickup_arr);
        }
        
        function get_pickup_chart_new($client_id=null,$pickup_day=null,$month=null,$year=null){
                      
		$this->layout = '';
                
                App::import('Model','Client');
		$this->Client = new Client();
                
                if($month == '0' || empty($month)){
                $month = date('m');
                }
                
                if($year == '0' || empty($year)){
                $year = date('Y');
                }
                
               $pickup_date = date('Y')."-".date('m')."-".$pickup_day;
               //$pickup_date = $year."-".$month."-".$pickup_day;  
               
               $today = date('Y-m-d');
                
                $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname'));
                $hotelname = $client_name['Client']['hotelname'];
               
                $this->set('client_id',$client_id);
                
                $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
                
                App::import('Model','Sheet');
		$this->Sheet = new Sheet();
                $columns = array('62');
                $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
                
                $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
                $sheetId = $all_sheets[0]['Sheet']['id'];
                  unset($bob_value);
                  unset($bob_fcst_value);
                  unset($bob_pickup_value);

                $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year); //calculate the number of days in present month
             
               $bob_pickup_value =  ClassRegistry::init('BobData')->find('all',array('conditions'=>array('sheet_id'=>$sheetId,'date !='=>'0','DATE(created)'=>$pickup_date,'date >='=>'1','date <='=>$days_in_presnt_month),'fields'=> array('value'),'order'=>'date ASC'));
               $bob_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'62','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
               $bob_fcst_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'63','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
               $bob_arr = ''; $bob_fcst_arr = '';  $bob_pickup_arr = '';
                   
                $date_arr = '';
                $bob_count = 0; $bob_pickup_count = 0; $date_count = 0; $bob_fcst_count = 0;
                $i = '0';
                foreach($bob_value as $bob){
                    if($bob_count == ''){
                    $bob_arr = $bob['Datum']['value'];
                    }else{
                      $bob_arr = $bob_arr.','.$bob['Datum']['value'];  
                    }
                    $bob_count++;
                    $i++;
                }

                 if(!empty($bob_pickup_value)){
                     $i = '0';
                     foreach($bob_pickup_value as $bob_pickup){
                         $bob_pickup_val = $bob_value[$i]['Datum']['value'] - $bob_pickup['BobData']['value'];
                        if($bob_pickup_count == ''){
                            $bob_pickup_arr = $bob_pickup_val;
                        }else{
                          $bob_pickup_arr = $bob_pickup_arr.','.$bob_pickup_val;  
                        }
                        $bob_pickup_count++; $i++;
                    }
                   $bob_pickup_arr = '['.$bob_pickup_arr.']';
              }else{
                  $bob_pickup_arr = '[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]';
              }
                    $i = '0';
                    foreach($bob_fcst_value as $bob_fcst){
                        $bob_fcst['Datum']['value'] = $bob_fcst['Datum']['value'] - $bob_value[$i]['Datum']['value'];
                        if($bob_fcst_count == ''){
                        $bob_fcst_arr = $bob_fcst['Datum']['value'];
                        }else{
                          $bob_fcst_arr = $bob_fcst_arr.','.$bob_fcst['Datum']['value'];  
                        }
                        $bob_fcst_count++;
                        $i++;
                    }
                    
                    $date_arr = '';
                    for($i=1;$i<=$days_in_presnt_month;$i++){
                        if($i == '1'){
                        $date_arr = "'".$i."'";
                        }else{
                          $date_arr = $date_arr.",'".$i."'";  
                        }
                        
                    }
                $bob_arr = '['.$bob_arr.']'; 
                
                $bob_fcst_arr= '['.$bob_fcst_arr.']'; 
               
               $date_arr =  '['.$date_arr.']';
                    
               $this->set('bob_arr',$bob_arr);
               $this->set('bob_fcst_arr',$bob_fcst_arr);
               
               $this->set('date_arr',$date_arr);
                $this->set('hotelname',$hotelname);
               $this->set('bob_pickup_arr',$bob_pickup_arr);
        }
        
         function get_pickup_chart_weekly($client_id=null,$pickup_day=null,$month=null,$year=null){
                      
		$this->layout = '';
                
                App::import('Model','Client');
		$this->Client = new Client();
                
                if($month == '0' || empty($month)){
                $month = date('m');
                }
                
                if($year == '0' || empty($year)){
                $year = date('Y');
                }
                
              // $pickup_date = date('Y')."-".date('m')."-".$pickup_day;
               $pickup_date = date("Y-m-d", strtotime("monday last week")); // last week monday
                
                $today = date('Y-m-d');
                
                $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname'));
                $hotelname = $client_name['Client']['hotelname'];
               
                $this->set('client_id',$client_id);
                
                $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
                
                App::import('Model','Sheet');
		$this->Sheet = new Sheet();
                $columns = array('62');
                $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
                
                $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
                $sheetId = $all_sheets[0]['Sheet']['id'];
                  unset($bob_value);
                  unset($bob_fcst_value);
                  unset($bob_pickup_value);

              $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year); //calculate the number of days in present month
             
           $bob_pickup_value =  ClassRegistry::init('BobData')->find('all',array('conditions'=>array('sheet_id'=>$sheetId,'date !='=>'0','DATE(created)'=>$pickup_date,'date >='=>'1','date <='=>$days_in_presnt_month),'fields'=> array('value'),'order'=>'date ASC'));
           
           $bob_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'62','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
           $bob_fcst_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'63','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));

           //echo '<pre>'; print_r($bob_pickup_value); echo '</pre>'; echo $pickup_date;
           
           $bob_arr = ''; $bob_fcst_arr = '';  $bob_pickup_arr = '';
                   
                    $date_arr = '';
                    $bob_count = 0; $bob_pickup_count = 0; $date_count = 0; $bob_fcst_count = 0;
                    $i = '0';
                    foreach($bob_value as $bob){
                        if($bob_count == ''){
                        $bob_arr = $bob['Datum']['value'];
                        }else{
                          $bob_arr = $bob_arr.','.$bob['Datum']['value'];  
                        }
                        $bob_count++;
                        $i++;
                    }
                   
                    
                     if(!empty($bob_pickup_value)){

                         $i = '0';
                         foreach($bob_pickup_value as $bob_pickup){
                             
                             $bob_pickup_val = $bob_value[$i]['Datum']['value'] - $bob_pickup['BobData']['value'];
                             
                        if($bob_pickup_count == ''){
                        $bob_pickup_arr = $bob_pickup_val;
                        }else{
                          $bob_pickup_arr = $bob_pickup_arr.','.$bob_pickup_val;  
                        }
                        $bob_pickup_count++; $i++;
                    }
                   $bob_pickup_arr = '['.$bob_pickup_arr.']'; 

              }else{
                  $bob_pickup_arr = '[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]';
                  
              }
                    $i = '0';
                    foreach($bob_fcst_value as $bob_fcst){
                        
                        $bob_fcst['Datum']['value'] = $bob_fcst['Datum']['value'] - $bob_value[$i]['Datum']['value'];
                        
                        if($bob_fcst_count == ''){
                        $bob_fcst_arr = $bob_fcst['Datum']['value'];
                        }else{
                          $bob_fcst_arr = $bob_fcst_arr.','.$bob_fcst['Datum']['value'];  
                        }
                        $bob_fcst_count++;
                        $i++;
                    }
                    
                    $date_arr = '';
                    for($i=1;$i<=$days_in_presnt_month;$i++){
                        if($i == '1'){
                        $date_arr = "'".$i."'";
                        }else{
                          $date_arr = $date_arr.",'".$i."'";  
                        }
                        
                    }
                $bob_arr = '['.$bob_arr.']'; 
                
                $bob_fcst_arr= '['.$bob_fcst_arr.']'; 
               
               $date_arr =  '['.$date_arr.']';
                    
               $this->set('bob_arr',$bob_arr);
               $this->set('bob_fcst_arr',$bob_fcst_arr);
               
               $this->set('date_arr',$date_arr);
                $this->set('hotelname',$hotelname);
               $this->set('bob_pickup_arr',$bob_pickup_arr);
        }
        
        
        function get_adr_pickup_chart_weekly($client_id=null,$pickup_day=null,$month=null,$year=null){

                $this->layout = '';

                App::import('Model','Client');
                $this->Client = new Client();

                if($month == '0' || empty($month)){
                $month = date('m');
                }

                if($year == '0' || empty($year)){
                $year = date('Y');
                }

                //$pickup_date = date('Y')."-".date('m')."-".$pickup_day;  
                $pickup_date = date("Y-m-d", strtotime("monday last week")); // last week monday
                $today = date('Y-m-d');
                
                $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname'));
                $hotelname = $client_name['Client']['hotelname'];
               
                $this->set('client_id',$client_id);
                
                $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
                
                App::import('Model','Sheet');
		$this->Sheet = new Sheet();
                $columns = array('64');
                $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
                
                $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
                $sheetId = $all_sheets[0]['Sheet']['id'];
                  unset($adr_value);
                  unset($adr_fcst_value);
                  unset($adr_pickup_value);

                $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year); //calculate the number of days in present month
             
           $adr_pickup_value =  ClassRegistry::init('AdrData')->find('all',array('conditions'=>array('sheet_id'=>$sheetId,'date !='=>'0','DATE(created)'=>$pickup_date,'date >='=>'1','date <='=>$days_in_presnt_month),'fields'=> array('value'),'order'=>'date ASC'));
           $adr_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'64','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
           $adr_fcst_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'65','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));

          // echo '<pre>'; print_r($adr_fcst_value); exit;
           
           $adr_arr = ''; $adr_fcst_arr = '';  $adr_pickup_arr = '';
                   
                    $date_arr = '';
                    $adr_count = 0; $adr_pickup_count = 0; $date_count = 0; $adr_fcst_count = 0;
                    $i = '0';
                    foreach($adr_value as $adr){
                        if($adr_count == ''){
                        $adr_arr = round($adr['Datum']['value'],'2');
                        }else{
                          $adr_arr = $adr_arr.','.round($adr['Datum']['value'],'2');  
                        }
                        $adr_count++;
                        $i++;
                    }                   
                    
                     if(!empty($adr_pickup_value)){
                        $i = '0';
                         foreach($adr_pickup_value as $adr_pickup){
                             
                        $adr_pickup['AdrData']['value'] = str_replace( ',', '',$adr_pickup['AdrData']['value']);
                        $adr_value[$i]['Datum']['value'] = str_replace( ',', '',$adr_value[$i]['Datum']['value']);
                             
                         $adr_pickup_val = $adr_value[$i]['Datum']['value'] - $adr_pickup['AdrData']['value'];
                             
                        if($adr_pickup_count == ''){
                            $adr_pickup_arr = round($adr_pickup_val,'2');
                        }else{
                          $adr_pickup_arr = $adr_pickup_arr.','.round($adr_pickup_val,'2');  
                        }
                        $adr_pickup_count++; $i++;
                    }
                   $adr_pickup_arr = '['.$adr_pickup_arr.']'; 
              }else{
                  $adr_pickup_arr = '[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]';
               }
                    $i = '0';
                    foreach($adr_fcst_value as $adr_fcst){
                        
                        $adr_fcst['Datum']['value'] = str_replace( ',', '',$adr_fcst['Datum']['value']);
                        $adr_value[$i]['Datum']['value'] = str_replace( ',', '',$adr_value[$i]['Datum']['value']);
                        
                        $adr_fcst['Datum']['value'] = round($adr_fcst['Datum']['value'],'2') - round($adr_value[$i]['Datum']['value'],'2');
                        if($adr_fcst_count == ''){
                        $adr_fcst_arr = round($adr_fcst['Datum']['value'],'2');
                        }else{
                          $adr_fcst_arr = $adr_fcst_arr.','.round($adr_fcst['Datum']['value'],'2');  
                        }
                        $adr_fcst_count++;
                        $i++;
                    }
                    
                    $date_arr = '';
                    for($i=1;$i<=$days_in_presnt_month;$i++){
                        if($i == '1'){
                        $date_arr = "'".$i."'";
                        }else{
                          $date_arr = $date_arr.",'".$i."'";  
                        }
                    }
               
               $adr_arr = '['.$adr_arr.']'; 
               $adr_fcst_arr= '['.$adr_fcst_arr.']'; 
               
               $date_arr =  '['.$date_arr.']';
               $this->set('adr_arr',$adr_arr);
               $this->set('adr_fcst_arr',$adr_fcst_arr);
               $this->set('date_arr',$date_arr);
               $this->set('hotelname',$hotelname);
               $this->set('adr_pickup_arr',$adr_pickup_arr);
        }
        //ADR pickup function ends here
        
        function get_forecast_chart($client_id=null,$month=null,$year=null){
            	$this->layout = '';

                App::import('Model','Client');
		$this->Client = new Client();
                
                $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname'));
               $hotelname = $client_name['Client']['hotelname'];
               
               if($month == '0' || empty($month)){
                $month = date('m');
                }
                
                if($year == '0' || empty($year)){
                $year = date('Y');
                }
               
               $this->set('client_id',$client_id);
               
               $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);

                App::import('Model','Sheet');
		$this->Sheet = new Sheet();
//Column Id -> Fcst Rooms 63 //,'Datum.column_id','Datum.value','Datum.date'
                //Column Ud -> ADR Fcst 65  //,'Datum.column_id'=>$columns
                $columns = array('63','65');
            $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
            $sheetId = $all_sheets[0]['Sheet']['id'];
             unset($bob_value);
              unset($adr_value);
              
             //$days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,date('m'), date('Y'));
               $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year);
              
           $bob_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'63','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
            $adr_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'65','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
    
                    $bob_arr = '';
                    $adr_arr = '';
                    $date_arr = '';
                    $bob_count = 0; $date_count = 0; $adr_count = 0;
                    foreach($bob_value as $bob){
                        if($bob_count == ''){
                        $bob_arr = str_replace( ',', '', $bob['Datum']['value']);
                        }else{
                          $bob_arr = $bob_arr.','.str_replace( ',', '', $bob['Datum']['value']);  
                        }
                        $bob_count++;
                    }
                    $adr_arr = '';
                    foreach($adr_value as $adr){
                      
                        $adr['Datum']['value'] = str_replace( ',', '',$adr['Datum']['value'] );
                        $adr1 = number_format($adr['Datum']['value'], 2);
                        //$adr_final = ltrim($adr1,'0');
                        
//                        $adr_final = (float)$adr1;
                        
                        $adr_final = $adr1;
                        
                        if($adr_count == ''){
                        $adr_arr =  str_replace( ',', '', $adr_final);
                        }else{
                          $adr_arr = $adr_arr.','.str_replace( ',', '', $adr_final);  
                        }
                        $adr_count++;
                    }
                    $date_arr = '';
                    for($i=1;$i<=count($adr_value);$i++){
                        if($i == '1'){
                        $date_arr = "'".$i."'";
                        }else{
                          $date_arr = $date_arr.",'".$i."'";  
                        }
                        
                    }
                $bob_arr = '['.$bob_arr.']'; 
                $adr_arr =  '['.$adr_arr.']';
               $date_arr =  '['.$date_arr.']';
                    
               $this->set('bob_arr',$bob_arr);
               $this->set('adr_arr',$adr_arr);
               $this->set('date_arr',$date_arr);
                $this->set('hotelname',$hotelname);
               
        }

        function get_combined_chart($client_id=null,$month=null,$year=null){
            	$this->layout = '';

                App::import('Model','Client');
		$this->Client = new Client();
                
                $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname'));
               $hotelname = $client_name['Client']['hotelname'];
               
               $this->set('client_id',$client_id);
               
               if($month == '0' || empty($month)){
                $month = date('m');
                }
                
                if($year == '0' || empty($year)){
                $year = date('Y');
                }
                
                $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);

                App::import('Model','Sheet');
		$this->Sheet = new Sheet();
//Column Id -> Fcst Rooms 63 //,'Datum.column_id','Datum.value','Datum.date'
                //Column Ud -> ADR Fcst 65  //,'Datum.column_id'=>$columns
                //Column Id -> BOB 62 //,'Datum.column_id','Datum.value','Datum.date'
                //Column Ud -> ADR 64  //,'Datum.column_id'=>$columns

                $columns = array('62','63','64','65');
            $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.year'=>$year,'Sheet.department_id'=>$dept_ids,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
            $sheetId = $all_sheets[0]['Sheet']['id'];
             unset($bob_fcst_value);
              unset($adr_fsct_value);
               unset($bob_value);
              unset($adr_value);
            
              
            // $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,date('m'), date('Y'));
               $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year);
              
           $bob_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'62','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
           $adr_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'64','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));

            $bob_fcst_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'63','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
            $adr_fcst_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'65','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));

            
                    $bob_arr = ''; $bob_fcst_arr ='';
                    $adr_arr = ''; $adr_fcst_arr ='';
                    $date_arr = '';
                    $bob_fcst_count=0;  $bob_count = 0; $date_count = 0; $adr_count = 0; $adr_fcst_count = 0;
                  
                    foreach($bob_value as $bob){
                        if($bob_count == ''){
                        $bob_arr = str_replace( ',', '', $bob['Datum']['value']);
                        }else{
                          $bob_arr = $bob_arr.','.str_replace( ',', '', $bob['Datum']['value']);
                        }
                        $bob_count++;
                    }
                    $adr_arr = '';
                    foreach($adr_value as $adr){
                      
                        $adr['Datum']['value'] = str_replace( ',', '',$adr['Datum']['value'] );
                        $adr1 = number_format($adr['Datum']['value'], 2);
                        //$adr_final = ltrim($adr1,'0');
                        
//                        $adr_final = (float)$adr1;
                        $adr_final = $adr1;
                        
                        if($adr_count == ''){
                        $adr_arr =  str_replace( ',', '',$adr_final);
                        }else{
                          $adr_arr = $adr_arr.','.str_replace( ',', '',$adr_final);;  
                        }
                        $adr_count++;
                    }
                    $date_arr = '';
                    for($i=1;$i<=count($adr_value);$i++){
                        if($i == '1'){
                        $date_arr = "'".$i."'";
                        }else{
                          $date_arr = $date_arr.",'".$i."'";  
                        }
                        
                    }
                    
                    $bob_fcst_count = '';
                    foreach($bob_fcst_value as $bob_fcst){
                        if($bob_fcst_count == ''){
                        $bob_fcst_arr = str_replace( ',', '',$bob_fcst['Datum']['value']);
                        }else{
                          $bob_fcst_arr = $bob_fcst_arr.','.str_replace( ',', '',$bob_fcst['Datum']['value']);
                        }
                        $bob_fcst_count++;
                    }
                    
                    foreach($adr_fcst_value as $adr_fcst){
                      
                        $adr_fcst['Datum']['value'] = str_replace( ',', '',$adr_fcst['Datum']['value'] );
                        $adr11 = number_format($adr_fcst['Datum']['value'], 2);
                        //$adr_final = ltrim($adr1,'0');
                        
//                        $adr_final1 = (float)$adr11;
                        
                        $adr_final1 = $adr11;
                        
                        if($adr_fsct_count == ''){
                        $adr_fcst_arr =  str_replace( ',', '',$adr_final1);
                        }else{
                          $adr_fcst_arr = $adr_fcst_arr.','.str_replace( ',', '',$adr_final1);
                        }
                        $adr_fsct_count++;
                    }
                   
                    
                $bob_arr = '['.$bob_arr.']'; 
                $adr_arr =  '['.$adr_arr.']';
                
                $bob_fcst_arr = '['.$bob_fcst_arr.']'; 
                $adr_fcst_arr =  '['.$adr_fcst_arr.']';
                
               $date_arr =  '['.$date_arr.']';
                    
               $this->set('bob_arr',$bob_arr);
               $this->set('adr_arr',$adr_arr);
               $this->set('bob_fcst_arr',$bob_fcst_arr);
               $this->set('adr_fcst_arr',$adr_fcst_arr);
               $this->set('date_arr',$date_arr);
               $this->set('hotelname',$hotelname);
               
        }

        //Function added on 2 June'2014 for ADR pickup graph
        function get_adr_pickup_chart($client_id=null,$pickup_day=null,$month=null,$year=null){

                $this->layout = '';
                
                App::import('Model','Client');
                $this->Client = new Client();

                if($month == '0' || empty($month)){
                $month = date('m');
                }

                if($year == '0' || empty($year)){
                $year = date('Y');
                }

                $pickup_date = date('Y')."-".date('m')."-".$pickup_day;  
                //$pickup_date = $year."-".$month."-".$pickup_day;
                $today = date('Y-m-d');
                
                $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname'));
                $hotelname = $client_name['Client']['hotelname'];
               
                $this->set('client_id',$client_id);
                
                $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
                
                App::import('Model','Sheet');
		$this->Sheet = new Sheet();
                $columns = array('64');
                $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
                
                $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
                $sheetId = $all_sheets[0]['Sheet']['id'];
                  unset($adr_value);
                  unset($adr_fcst_value);
                  unset($adr_pickup_value);

                $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year); //calculate the number of days in present month
             
           $adr_pickup_value =  ClassRegistry::init('AdrData')->find('all',array('conditions'=>array('sheet_id'=>$sheetId,'date !='=>'0','DATE(created)'=>$pickup_date,'date >='=>'1','date <='=>$days_in_presnt_month),'fields'=> array('value'),'order'=>'date ASC'));
           $adr_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'64','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
           $adr_fcst_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>'65','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));

           $adr_arr = ''; $adr_fcst_arr = '';  $adr_pickup_arr = '';
                   
                    $date_arr = '';
                    $adr_count = 0; $adr_pickup_count = 0; $date_count = 0; $adr_fcst_count = 0;
                    $i = '0';
                    foreach($adr_value as $adr){
                        if($adr_count == ''){
                        $adr_arr = round($adr['Datum']['value'],'2');
                        }else{
                          $adr_arr = $adr_arr.','.round($adr['Datum']['value'],'2');  
                        }
                        $adr_count++;
                        $i++;
                    }                   
                    
                     if(!empty($adr_pickup_value)){
                        $i = '0';
                         foreach($adr_pickup_value as $adr_pickup){
                             
                        $adr_pickup['AdrData']['value'] = str_replace( ',', '',$adr_pickup['AdrData']['value']);
                        $adr_value[$i]['Datum']['value'] = str_replace( ',', '',$adr_value[$i]['Datum']['value']);
                             
                         $adr_pickup_val = $adr_value[$i]['Datum']['value'] - $adr_pickup['AdrData']['value'];
                             
                        if($adr_pickup_count == ''){
                            $adr_pickup_arr = round($adr_pickup_val,'2');
                        }else{
                          $adr_pickup_arr = $adr_pickup_arr.','.round($adr_pickup_val,'2');  
                        }
                        $adr_pickup_count++; $i++;
                    }
                   $adr_pickup_arr = '['.$adr_pickup_arr.']'; 
              }else{
                  $adr_pickup_arr = '[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]';
               }
                    $i = '0';
                    foreach($adr_fcst_value as $adr_fcst){
                        $adr_fcst['Datum']['value'] = str_replace( ',', '',$adr_fcst['Datum']['value']);
                        $adr_value[$i]['Datum']['value'] = str_replace( ',', '',$adr_value[$i]['Datum']['value']);
                        $adr_fcst['Datum']['value'] = round($adr_fcst['Datum']['value'],'2') - round($adr_value[$i]['Datum']['value'],'2');
                        if($adr_fcst_count == ''){
                        $adr_fcst_arr = round($adr_fcst['Datum']['value'],'2');
                        }else{
                          $adr_fcst_arr = $adr_fcst_arr.','.round($adr_fcst['Datum']['value'],'2');  
                        }
                        $adr_fcst_count++;
                        $i++;
                    }
                    
                    $date_arr = '';
                    for($i=1;$i<=$days_in_presnt_month;$i++){
                        if($i == '1'){
                        $date_arr = "'".$i."'";
                        }else{
                          $date_arr = $date_arr.",'".$i."'";  
                        }
                    }
               
               $adr_arr = '['.$adr_arr.']'; 
               $adr_fcst_arr= '['.$adr_fcst_arr.']';                
               $date_arr =  '['.$date_arr.']';
               $this->set('adr_arr',$adr_arr);
               $this->set('adr_fcst_arr',$adr_fcst_arr);
               $this->set('date_arr',$date_arr);
               $this->set('hotelname',$hotelname);
               $this->set('adr_pickup_arr',$adr_pickup_arr);
        }
        //ADR pickup function ends here


        public function send_weekly_report(){
            $this->Client = ClassRegistry::init('Client');
            $all_hotels = $this->Client->find('all', array('conditions' => array('Client.status !=' => 2), 'fields' => 'id,hotelname'));
            foreach($all_hotels as $clientHotels){
                $hotelname = $clientHotels['Client']['hotelname'];
                $client_id = $clientHotels['Client']['id'];
                
                $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);

                $this->Sheet = ClassRegistry::init('Sheet');
                $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.department_id' => $dept_ids, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'order' => 'Sheet.modified DESC', 'recursive' => '0'));
                $this->set('sheet_id', $all_sheets[0]['Sheet']['id']);
                $sheetId = $all_sheets[0]['Sheet']['id'];

                $sheet_value = $this->Sheet->getData($sheetId);
                $last_monday = date("Y-m-d", strtotime("monday last week"));
                $SheetdataDetails = ClassRegistry::init('WeeklyReportData')->find('list', array('conditions' => array('WeeklyReportData.sheet_id' => $sheetId, 'WeeklyReportData.date' => $last_monday), 'fields' => array('WeeklyReportData.type', 'WeeklyReportData.total')));

                unset($Sheetdata); $Sheetdata_today = array();
                foreach ($sheet_value as $values) {
                    if ($values['Date'] == 'Total') {
                        foreach ($values as $tkey => $total_key) {
                            if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                $Sheetdata_today[$tkey] = $total_key;
                            }
                        }
                    }
                }
                
                
                
            } //foreach for All Hotels
            
            
            $html = '';
            $html .= '<table cellpadding="2" cellspacing="1" style="border:1px solid #ccc;width:65%;float:left;margin-left:200px;">
    <tr>
    <td width="40%"><b>FORECAST</b></td>
    <td width="30%"><b>This Week</b></td>
    <td width="30%"><b>Last Week</b></td>
    </tr>
    <tr>
    <td><b>Occ</b></td>';

            $pickup_occ = str_replace(",", "", $Sheetdata_today["BOB"]) - str_replace(",", "", $SheetdataDetails["BOB"]);
            $pickup_revenue = str_replace(",", "", $Sheetdata_today["Revenue"]) -  str_replace(",", "", $SheetdataDetails["Revenue"]);
            $pickup_adr = number_format(($pickup_revenue/$pickup_occ),2);
            $req_pickup_occ = str_replace(",", "", $Sheetdata_today["Fcst Rooms"]) - str_replace(",", "", $Sheetdata_today["BOB"]);
            $req_pickup_revenue = str_replace(",", "", $Sheetdata_today["Rev Fcst"]) -  str_replace(",", "", $Sheetdata_today["Revenue"]);
            $req_pickup_adr = number_format(($req_pickup_revenue/$req_pickup_occ),2);
            
            
                $html .= '<td>'.$Sheetdata_today['Fcst Rooms'].'</td>';
                $html .= '<td>'.$SheetdataDetails['Fcst Rooms'].'</td>
                </tr>
                <tr>
                <td><b>ADR</b></td>';
                $html .= '<td>'.$Sheetdata_today["ADR Fcst"].'</td>';
                $html .= '<td>'.$SheetdataDetails["ADR Fcst"].'</td>
                </tr>
                <tr>
                <td><b>RevPAR</b></td>';
                $html .= '<td>'.$Sheetdata_today['RevPAR Fcst'].'</td>';
                $html .= '<td>'.$SheetdataDetails['RevPAR Fcst'].'</td>
                </tr>
                <tr>
                <td><b>Rev</b></td>';
                $html .= '<td>'.$Sheetdata_today['Rev Fcst'].'</td>';
                $html .= '<td>'.$SheetdataDetails['Rev Fcst'].'</td>
                </tr>
                </table><br/>

                <table cellpadding="2" cellspacing="1"  style="border:1px solid #ccc;width:65%;float:left;margin-left:200px;">
                <tr>
                <td width="40%"><b>PICKUP</b></td>
                <td width="30%"><b>This Week</b></td>
                <td width="30%"><b>Required</b></td>
                </tr>
                <tr>
                <td><b>Occ RN</b></td>';
                $html .= '<td>'.$pickup_occ.'</td>';
                $html .= '<td>'.$req_pickup_occ.'</td>
                </tr>
                <tr>
                <td><b>ADR</b></td>';
                $html .= '<td>'.$pickup_adr.'</td>';
                $html .= '<td>'.$req_pickup_adr.'</td>
                </tr>
                <tr>
                <td><b>Revenue</b></td>';
                $html .= '<td>'.number_format($pickup_revenue,2).'</td>';
                $html .= '<td>'.number_format($req_pickup_revenue,2).'</td>
                </tr>
                </table>';

            
            
        }
        
        
        function get_hotel_department_list($client_id=null) {
            $this->autoRender = false;
            $this->Client = ClassRegistry::init('Client');
            $condition = array('Department.client_id' => $client_id,'Department.status' => '1');
            $hotel_dept_ids = $this->Client->Department->find('list', array('conditions' => $condition,'recursive' => '0'));
            $html = '';
            foreach($hotel_dept_ids as $deptId=>$deptName){
                $html .= '<option value="'.$deptId.'">'.$deptName.'</option>';
            }
            echo $html; exit;
        }
        
        
        function timings(){
            $this->layout = false;
        }
        
        function tee_times(){
            //Configure::write('debug',2);
            $this->layout = false;
            
            $user_id = '1';
            $department_id = '1';
            
            $month = date('m');
            $year = date('Y');
            
            $this->TeeTime = ClassRegistry::init('TeeTime');
            $teeTimesData = $this->TeeTime->getMonthlyData($month,$year,$user_id);
            
            $this->set(get_defined_vars());
            
            if(!empty($this->data)){
                foreach($this->data as $date=>$data){
                    $count='1';
                    foreach($data['tee_time'] as $key=>$tee_time){
                        $saveTeeTime = array();
                        $saveTeeTime['id'] = @$data['id'][$key];;
                        $saveTeeTime['year'] = $year;
                        $saveTeeTime['month'] = $month;
                        $saveTeeTime['date'] = $date;
                        $saveTeeTime['user_id'] = $user_id;
                        $saveTeeTime['department_id'] = $department_id;
                        $saveTeeTime['time_lapse'] = $count;
                        $saveTeeTime['tee_time'] = $tee_time;
                        $saveTeeTime['booked_value'] = $data['booked'][$key];
                        $saveTeeTime['actual_value'] = $data['actual'][$key];
                        
                        $this->TeeTime = ClassRegistry::init('TeeTime');
                        $this->TeeTime->save($saveTeeTime);
                        $count++;
                    }
                }
                $this->redirect(array('action' => 'tee_times'));
            }
            
        }
        
}//end class