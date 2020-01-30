<?php
class ClientsController extends AppController {

	/**
	 * Class property that stores the Controller name
	 */
	var $name = 'Clients';
	//var $helpers = array('Html', 'Javascript', 'Session');
	var $components = array('RequestHandler', 'Sendemail','Session','Email','Cookie');

        function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('forget_password');
// 		$this->Auth->allow('get_user_list');
		$this->Auth->allow('get_subhotel_list');
		$this->Auth->allow('landing');
                $this->Auth->allow('palheiro_progress');
                $this->Auth->allow('get_chart','get_adr_pickup_chart','get_staff_adr_pickup_chart');
                $this->Auth->allow('get_forecast_chart');
                $this->Auth->allow('get_pickup_chart_weekly');
                $this->Auth->allow('flash_pdf','flash_verified','get_flash_cashes');
                $this->Auth->allow('flash_month_to_date','currency_rate');
                $this->Auth->allow('get_room_department','get_flash_finances','email_flash','get_pickup_chart_new');
        }

        function currency_rate($from_Currency='SCR',$to_Currency='EUR',$amount){
            $this->autoRender = false;
            $this->layout = '';
            $url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from_Currency . $to_Currency .'=X';
            $handle = @fopen($url, 'r');
            if ($handle) {
                $result = fgets($handle, 4096);
                fclose($handle);
            }
            $allData = explode(',',$result); /* Get all the contents to an array */
            $value = $allData[1];
            return round(($value * $amount),2);
        }
        
        public function admin_update_regional_emails($client_id = null){
            //Configure::write('debug',2);
            
            if(!empty($this->data)){
                //echo '<pre>'; print_r($this->data); exit;
                $clientId = $this->data['EmailSummarySheet']['client_id'];
                
                $this->EmailSummarySheet = ClassRegistry::init('EmailSummarySheet');
                        
                if (isset($this->data['Client']['regional_email'])) {

                $this->EmailSummarySheet->deleteAll(array('EmailSummarySheet.client_id' => $clientId,'EmailSummarySheet.type'=>'regional'));
                $this->Client->id = $clientId;
                if ($this->Client->saveField('regional_email', $this->data['Client']['regional_email'])) {

                    if ($this->data['Client']['regional_email'] == 0) {
                        unset($this->data['EmailSummarySheet']);
                    }

                    if (isset($this->data['EmailSummarySheet']) && !empty($this->data['EmailSummarySheet'])) {
                        foreach ($this->data['EmailSummarySheet'] as $key => $EmailSheet) {
                            if(!empty($EmailSheet['email'])){
                            $this->data['EmailSummarySheet']['id'] = '';
                            $this->data['EmailSummarySheet']['type'] = 'regional';
                            $this->data['EmailSummarySheet']['client_id'] = $clientId;
                            $this->data['EmailSummarySheet']['email'] = $EmailSheet['email'];
                            ClassRegistry::init('EmailSummarySheet')->save($this->data['EmailSummarySheet']);
                            }
                        }
                    }

                    $this->Session->setFlash(__('Email Summary updated successfully', true));
                    $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'edit', $clientId));
                } else {
                    $this->Session->setFlash(__('Email Summary Unable to Update', true));
                }
            } else if (isset($this->data['Client']['monthly_detailed_email'])) {

                $this->EmailSummarySheet->deleteAll(array('EmailSummarySheet.client_id' => $clientId,'EmailSummarySheet.type'=>'summary'));
                $this->Client->id = $clientId;
                if ($this->Client->saveField('monthly_detailed_email', $this->data['Client']['monthly_detailed_email'])) {

                    if ($this->data['Client']['monthly_detailed_email'] == 0) {
                        unset($this->data['EmailSummarySheet']);
                    }

                    if (isset($this->data['EmailSummarySheet']) && !empty($this->data['EmailSummarySheet'])) {
                        foreach ($this->data['EmailSummarySheet'] as $key => $EmailSheet) {
                            if(!empty($EmailSheet['email'])){
                                $this->data['EmailSummarySheet']['id'] = '';
                                $this->data['EmailSummarySheet']['type'] = 'summary';
                                $this->data['EmailSummarySheet']['client_id'] = $clientId;
                                $this->data['EmailSummarySheet']['email'] = $EmailSheet['email'];
                                ClassRegistry::init('EmailSummarySheet')->save($this->data['EmailSummarySheet']);
                            }
                        }                        
                    }
                    $this->Session->setFlash(__('Email Summary updated successfully', true));
                    $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'edit', $clientId));
                } else {
                    $this->Session->setFlash(__('Email Summary Unable to Update', true));
                }
            }
                
            }
        }
        
        
         function get_pickup_chart_new($client_id=null,$pickup_day=null,$month=null,$year=null){
                      
		$this->layout = '';
                App::import('Model','Department');
		$this->Department = new Department();
                
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
                
                $this->Client->Department->recursive = -1;
                $condition = array('Department.client_id' => $client_id,'Department.name' => 'Rooms','status'=>'1');
                $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
                if(empty($dept_data)){
                    $condition = array('Department.client_id' => $client_id,'Department.name LIKE' => 'Room%','status'=>'1');
                    $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
                }
                $dept_ids = array ();
                unset($dept_ids);
                foreach($dept_data as $dept){
                    $dept_ids[] = $dept['Department']['id'];
                }
                
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
                App::import('Model','Department');
		$this->Department = new Department();
                
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
                
                $this->Client->Department->recursive = -1;
                
                $condition = array('Department.client_id' => $client_id,'Department.name' => 'Rooms','status'=>'1');
                $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
                if(empty($dept_data)){
                    $condition = array('Department.client_id' => $client_id,'Department.name LIKE' => 'Room%','status'=>'1');
                    $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
                }
               
                $dept_ids = array ();
                 unset($dept_ids);
                foreach($dept_data as $dept){
                    $dept_ids[] = $dept['Department']['id'];
                }
                
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
       
       
        function get_pickup_chart($client_id=null,$pickup_date=null){
            
            //$this->autoRender = false;
		$this->layout = '';
                App::import('Model','Department');
		$this->Department = new Department();
                
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
                
                $this->Client->Department->recursive = -1;
                
                $condition = array('Department.client_id' => $client_id,'Department.name' => 'Rooms','status'=>'1');
                $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
                if(empty($dept_data)){
                    $condition = array('Department.client_id' => $client_id,'Department.name LIKE' => 'Room%','status'=>'1');
                    $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
                }
                
                
                $dept_ids = array ();
                 unset($dept_ids);
                foreach($dept_data as $dept){
                    $dept_ids[] = $dept['Department']['id'];
                }
                
                App::import('Model','Sheet');
		$this->Sheet = new Sheet();
//Column Id -> BOB 62 //,'Datum.column_id','Datum.value','Datum.date'
                //Column Id -> Fcst Rooms 63
                $columns = array('62');
                $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
             
                $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
                $sheetId = $all_sheets[0]['Sheet']['id'];
                  unset($bob_value);
                  unset($bob_fcst_value);
                  unset($bob_pickup_value);

             //$days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,date('m'), date('Y'));
                   $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year);
             
            $bob_pickup_value =  ClassRegistry::init('BobData')->find('all',array('conditions'=>array('sheet_id'=>$sheetId,'date !='=>'0','date >='=>'1','DATE(created)'=>$pickup_date,'date <='=>$days_in_presnt_month),'fields'=> array('value'),'order'=>'date ASC'));
             
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
                  $bob_pickup_arr = '[0.0, 0.0, 0.0,0.0, 0.0, 0.0,0.0, 0.0, 0.0,0.0, 0.0, 0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0]';
                  
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
        

        function index() {
		$clientId = $this->Auth->user('id');
                
                $this->set('clientId',$clientId);
		
                $parent_data = $this->Client->find('first',array('conditions'=>array('Client.id'=>$clientId,'Client.status'=>1),'fields'=>'parent_id','recursive'=>'0'));
                if(!empty($parent_data)){
                       $parent_id = $parent_data['Client']['parent_id'];
                }else{
                        $parent_id = '';
                }
                
                if(!empty($parent_id)){
                    /*$child_data = $this->Client->find('all',
                        array('conditions'=>
                            array('OR'=>array('Client.parent_id'=>$clientId,'Client.id'=>array($clientId,$parent_id)),'Client.status'=>1)
                        ,'fields'=>'hotelname,id','recursive'=>'0'));*/
                     $child_data = $this->Client->find('all',
                        array('conditions'=>
                            array('OR'=>array('Client.parent_id'=>$clientId,'Client.id'=>array($clientId)),'Client.status'=>1)
                        ,'fields'=>'hotelname,id','recursive'=>'0'));
                    
                }else{
                    $child_data = $this->Client->find('all',
                        array('conditions'=>
                            array('OR'=>array('Client.parent_id'=>$clientId,'Client.id'=>$clientId),'Client.status'=>1)
                        ,'fields'=>'hotelname,id','recursive'=>'0'));
                }
                $this->set('child_data',$child_data);
               
               	}//end index()

	/**
	 * Login action for clients
	 *
	 * @access public
	 * @return void()
	 */
	function login() {
            
                $this->redirect("/staff/users/alllogin");
                
                $this->layout = 'login_layout';
                
		// Display error message on failed authentication
		if (!empty($this->data) && !$this->Auth->_loggedIn) {
			$this->Session->setFlash($this->Auth->loginError);
		}
                // Redirect the logged in user to respective pages
		$this->setLoginRedirects();
	}//end login()


	/**
	 * Logout action for Clients
	 * 
	 * @access public
	 * @return void
	 */
	function logout() {
            
                $activityId = $this->Session->read('activityId');
		if(!empty($activityId))
		{
                        $this->Activity = ClassRegistry::init('Activity');
			$this->Activity->updateActivityIsLogout($activityId);
		}
            
		$this->Session->setFlash(__('You are successfully Logged out.', true));
		session_destroy();
		$this->redirect($this->Auth->logout());
	}//end logout()
	
	
	function admin_assign($id=null) {
	
		$this->Client->bindModel(array(
			'hasMany' => array(
				'User' => array('conditions'=>array('User.status !=' => 2)),
				'ClientUser' => array()
		)));
	
		if(!empty($this->data)){
		
			if(!empty($this->data['ClientUser']['user_id'])){
			
				foreach($this->data['ClientUser']['user_id'] as $key=> $usr_id){
					$data['ClientUser'][$key]['user_id'] = $usr_id;
					$data['ClientUser'][$key]['client_id'] = $id;
				}
				
				$this->Client->ClientUser->deleteAll(array('ClientUser.client_id'=>$id));
				
				if($this->Client->ClientUser->saveAll($data['ClientUser'])){
					$this->Session->setFlash('The User has been updated', 'default', array('type' => 'success'));
					//$this->redirect(array('action' => 'index'));			
				}else{
					$this->Session->setFlash(__('The User could not be updated.Please, try again.', true));	
				
				}
			}else{
				$this->Session->setFlash(__('Please select users.', true));				
			}
		}else{
			$d_user_ids = $this->Client->ClientUser->find('list',array('conditions'=>array('ClientUser.client_id'=>$id),'fields'=>array('ClientUser.user_id','ClientUser.user_id')));
			if(!empty($d_user_ids)){
				foreach($d_user_ids as $key=>$d_user_id){
					$this->data['ClientUser']['user_id'][$key] = $d_user_id;
				}
			}
		}
	
		$clientId = $id;
		$client_name = $this->Client->field('Client.hotelname',array('Client.id'=>$id));
		$this->Client->User->bindModel(array(
			'hasMany' => array(
				'DepartmentsUser' => array()
		)));
		
		$c_user_ids = $this->Client->User->find('list',array('conditions'=>array('User.client_id'=>$id,'User.status !=' => 2),'fields'=>array('User.id','User.id')));
		if(!empty($c_user_ids)){
			$c_dep_user_ids = $this->Client->User->DepartmentsUser->find('list',array('conditions'=>array('DepartmentsUser.user_id'=>$c_user_ids),'fields'=>array('DepartmentsUser.user_id','DepartmentsUser.user_id')));
			if(!empty($c_dep_user_ids)){
				$users[$client_name] = $this->Client->User->find('list',array('conditions'=>array('User.id'=>$c_dep_user_ids,'User.status !=' => 2),'fields'=>array('User.id','User.fullname')));
			}
		}
	
		$clients = $this->Client->find('list',array('conditions'=>array('Client.parent_id'=>$id,'Client.status !='=>2),'fields'=>array('Client.id','Client.hotelname'),'recursive'=>-1));
		
		if(!empty($clients)) {
			foreach($clients as $key=>$client){		
				$user_ids = $this->Client->User->find('list',array('conditions'=>array('User.client_id'=>$key,'User.status !=' => 2),'fields'=>array('User.id','User.id')));
				if(!empty($user_ids)){
					$dep_user_ids = $this->Client->User->DepartmentsUser->find('list',array('conditions'=>array('DepartmentsUser.user_id'=>$user_ids),'fields'=>array('DepartmentsUser.user_id','DepartmentsUser.user_id')));
					if(!empty($dep_user_ids)){
						$users[$client] = $this->Client->User->find('list',array('conditions'=>array('User.id'=>$dep_user_ids,'User.status !=' => 2),'fields'=>array('User.id','User.fullname')));
					}
				}
			}
		}
	
                $this->set(get_defined_vars());
		
	}


	/**
	 * action to view edit the client profile
	 * 
	 * @access public
	 * @return void
	 */
	function profile()
	{
		// Find the logged-in client details
		$clientId = $this->Auth->user('id');
		$client   = $this->Client->findById($clientId);

		// Set the client information to display in the view
		$this->set(compact('client'));
	}//end profile()


	/**
	 * action to view edit the client profile
	 * 
	 * @access public
	 * @return void
	 */
	function edit($clientId=null)
	{
		if($clientId == ''){
			// Find the logged-in client details
			$clientId = $this->Auth->user('id');
			$this->set('chain_user','0');
		}else{
			$this->set('chain_user','1');
		}

		$client   = $this->Client->findById($clientId);

		// Save the POSTed data
		if (!empty($this->data)) {
			$this->data['Client']['id'] = $clientId;
			if ($this->Client->save($this->data)) {
				$this->Client->saveLogo($clientId, $this->data['Client']['clientlogo']);
				$this->Session->setFlash(__('Profile has been updated', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Profile could not be saved. Please, try again.', true));
			}
		} else {
			// Set the form data (display prefilled data) 
			$this->data = $client;
		}

		// Set the view variable
		$this->set(compact('client'));
	}//end profile()


	function forget_password()
	{
		if(!empty($this->data))
		{
			$val = $this->data['Client']['email'];
			$condition['conditions'] = array("Client.email" => $val, "Client.status !=" =>2 );
			$condition['recursive']= -1;
			$condition['limit']= 1;

		# getting userdata
                $datalist = $this->Client->find('first', $condition);
		
		if (isset($datalist) && !empty($datalist['Client']['email']))
		{
			if($datalist['Client']['status'] == 2)
			{
				$this->Session->setFlash(__('<div class="errorCommnt" id="server_message">The account is Blocked. Please contact Administrator !</div>',true));
				$this->redirect(array('controller' => 'clients','action' => 'forget_password'));
			}
			if($datalist['Client']['status'] == 0)
			{
				$this->data['Client']['email'] = '';
				$this->Session->setFlash(__('<div class="errorCommnt" id="server_message">Email address does not exist !</div>',true));
				$this->redirect(array('controller' => 'Clients','action' => 'forget_password'));
			} else {
				//$this->randomnumber();
				$uniqueid = substr(rand(),0,5);
				$this->data['Client']['password'] = $this->Auth->password($uniqueid);
				$this->data['Client']['id'] = $datalist['Client']['id'];

				$result = $this->Client->save($this->data);
				if($result)
				{
					$this->data['Client']['username'] = $datalist['Client']['firstname'].' '.$datalist['Client']['lastname'];
					$this->data['Client']['password'] = $uniqueid;
					
					$getuserinfo = $this->data['Client'];
					$to = $datalist['Client']['email'];
					$username = $datalist['Client']['username'];
					$addcc = $this->Client->Field('email', array('id' =>'1'));
					# mail Section
					
					$result = $this->Sendemail->userforgotpassword($to, $addcc, $getuserinfo);
					if($result)
					{
					$this->data['Client']['email'] = '';
					$this->Session->setFlash(__('<div class="successCommnt" id="server_message">Your password has been sent to your email.</div>',true));
					$this->redirect(array('controller' => 'clients','action' => 'login'));
					}
				}
			}
		} else {
			$this->data['Client']['email'] = '';
			$this->Session->setFlash(__('<div class="errorCommnt" id="server_message">Email address does not exist !</div>',true));
			$this->redirect(array('controller' => 'clients','action' => 'forget_password'));
		}
		}
	}


	function deparments()
	{
		echo "Test Messagess";
	}

  
	/**
	 * Admin index function to list all the clients
	 * 
	 * @access public
	 * @return void
	 */
	function admin_index() {
		
                if($this->Session->read('Auth.Admin.IsSubAdmin')){
                    $subadmin_id = $this->Session->read('Auth.Admin.SubAdminID');
                    $this->Subadmin = ClassRegistry::init('Subadmin');
                    $subadmin_client = $this->Subadmin->read(null, $subadmin_id);
                    $allclients = array();
                    foreach($subadmin_client['SubadminClient'] as $clients)
                    {
                            array_push($allclients,$clients['client_id']);
                    }
                
                    if ($this->data['Client']['value']) {
                            $search = trim($this->data['Client']['value']);
                            $this->set('search',$search);
                            $conditions = array(
                                'OR'  => array('Client.hotelname LIKE' => "%$search%",'Client.username LIKE' => "%$search%", 'Client.firstname LIKE' => "%$search%", 'Client.lastname LIKE' => "%$search%"), 
                                'AND' => array('Client.status !=' => 2,'Client.id'=>$allclients)
                          );
                    } else {
                            $conditions = array('Client.status !=' => 2,'Client.id'=>$allclients);
                    }
                
                }else{
                    if ($this->data['Client']['value']) {
                            $search = trim($this->data['Client']['value']);
                            $this->set('search',$search);
                            $conditions = array(
                                'OR'  => array('Client.hotelname LIKE' => "%$search%",'Client.username LIKE' => "%$search%", 'Client.firstname LIKE' => "%$search%", 'Client.lastname LIKE' => "%$search%"), 
                                'AND' => array('Client.status !=' => 2)
                          );
                    } else {
                            $conditions = array('Client.status !=' => 2);
                    }
                }
                
                
		// List the clients to the admin
		$this->Client->recursive = 0;
		$this->paginate['conditions'] = $conditions;
                $this->paginate['order'] = 'Client.hotelname ASC';
		$clients = $this->paginate();
		for($i=0; $i<count($clients); $i++)
		{
		  $parent_name = $this->Client->field('Client.hotelname',array('Client.id'=>$clients[$i]['Client']['parent_id']));
		  $clients[$i]['Client']['parent_name'] = $parent_name;
		}

		$this->set('clients', $clients);
	}//end admin_index()



	/**
	 * Admin index function to list all the parent clients
	 * Added on 5thFeb'2013
	 * @access public
	 * @return void
	 */
	function admin_chain($id=null) {
		if ($this->data['Client']['value']) {
			$search = $this->data['Client']['value'];
			$conditions = array(
							'OR'  => array('Client.username LIKE' => "%$search%", 'Client.firstname LIKE' => "%$search%", 'Client.lastname LIKE' => "%$search%"), 
							'AND' => array('Client.status !=' => 2,'parent_id'=>$id)
						  );
		} else {
			$conditions = array('Client.status !=' => 2,'parent_id'=>$id);
		}
		// List the clients to the admin
		$this->Client->recursive = 0;
		$this->paginate['conditions'] = $conditions;
		$clients = $this->paginate();
		for($i=0; $i<count($clients); $i++)
		{
		  $parent_name = $this->Client->field('Client.hotelname',array('Client.id'=>$clients[$i]['Client']['parent_id']));
		  $clients[$i]['Client']['parent_name'] = $parent_name;
		}

		$parent_name = $this->Client->field('Client.hotelname',array('Client.id'=>$id));
		$this->set('parent_name', $parent_name);

		$this->set('id', $id);
		$this->set('clients', $clients);

	}//end admin_index()


	function chain_list() {

//echo $this->Auth->password('testing');

		$id = $this->Auth->user('id');
                $this->set('clientId',$id);

		if ($this->data['Client']['value']) {
			$search = $this->data['Client']['value'];
			$conditions = array(
							'OR'  => array('Client.username LIKE' => "%$search%", 'Client.firstname LIKE' => "%$search%", 'Client.lastname LIKE' => "%$search%"), 
							'AND' => array('Client.status !=' => 2,'parent_id'=>$id)
						  );
		} else {
			$conditions = array('Client.status !=' => 2,'parent_id'=>$id);
		}
		// List the clients to the admin
		$this->Client->recursive = 0;
		$this->paginate['conditions'] = $conditions;
		$clients = $this->paginate();
		for($i=0; $i<count($clients); $i++)
		{
		  $parent_name = $this->Client->field('Client.hotelname',array('Client.id'=>$clients[$i]['Client']['parent_id']));
		  $clients[$i]['Client']['parent_name'] = $parent_name;
		}

		$parent_name = $this->Client->field('Client.hotelname',array('Client.id'=>$id));
		$this->set('parent_name', $parent_name);

		$this->set('id', $id);
		$this->set('clients', $clients);

	}//end admin_index()



	/**
	 * Admin function to view a client details
	 * 
	 * @param integer $id ID of the client to be viewed
	 * @access public
	 * @return void
	 */
	function admin_view($id = null) {
		// Redirect on invalid client ID
		if (!$id) {
			$this->Session->setFlash(__('Invalid Hotel', true));
			$this->redirect(array('action' => 'index'));
		}
		$user_obj = ClassRegistry::init('User');
		$user_obj->unbindModel(array('hasMany'=>array('DepartmentsUser'), 'hasOne'=>array('Sheet')));
		$user_info = $user_obj->find('all',array('conditions'=>array('User.client_id'=>$id,'User.status'=>1)));
		$related_users = array();
		if(!empty($user_info)){
		      foreach($user_info as $user){
			      $related_users[] = $user['User'];
		      }
		}
		$client_info = $this->Client->read(null, $id);
		$client_info['User'] = $related_users;
		// Set the client details to display in the view
		$this->set('client', $client_info);
	}//end admin_view()


	/**
	 * Method for admin to add a client
	 * 
	 * @access public
	 * @return void
	 */
	function admin_add() {
		if (!empty($this->data)) {

                    // Prepare the Client Model for a new record
		      if(empty($this->data['Client']['parent_id'])){
			    $this->data['Client']['parent_id'] = 0;
			}

			$this->Client->create();

			// Save the client record into the database
			if ($this->Client->save($this->data)) {
				$clientId = $this->Client->getLastInsertId();
				// Save the client logo
				$this->Client->saveLogo($clientId, $this->data['Client']['clientlogo']);
				$this->Session->setFlash(__('The Hotel has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Hotel could not be saved. Please, try again.', true));
			}
		}
		    
		    $allhotels = $this->Client->find('all',array('fields'=>'Client.id, Client.hotelname','conditions'=>array('Client.status !='=>2)));
		    $this->set('allhotels',$allhotels);
		
	}//end admin_add()


	/**
	 * Method for admin to edit a client
	 * 
	 * @param integer $id The Client ID to be edited
	 * @access public
	 * @return void
	 */
        
        
	function admin_edit($id = null) {
            //Configure::write('debug',2);
            
		// Redirect for invalid id
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Hotel', true));
			$this->redirect(array('action' => 'index'));
		}

		// Save the Client Data
		if (!empty($this->data)) {
                    
			if(empty($this->data['Client']['parent_id']) && !isset($this->data['Client']['password'])){
			    $this->data['Client']['parent_id'] = 0;
			}
			if ($this->Client->save($this->data)) {
			      if(isset($this->data['Client']['clientlogo']) && !empty($this->data['Client']['clientlogo'])){
				$this->Client->saveLogo($id, $this->data['Client']['clientlogo']);
			      }
				$this->Session->setFlash(__('The Hotel has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Hotel could not be saved. Please, try again.', true));
			}
		}

		// Read the client data and set it for the view
		if (empty($this->data) || isset($this->data['Client']['password'])) {
                    $this->data = $this->Client->read(null, $id);
		    $allhotels = $this->Client->find('all',array('fields'=>'Client.id, Client.hotelname','conditions'=>array('Client.status !='=>2, 'Client.id !='=>$this->params['pass'][0])));
		    $this->set('allhotels',$allhotels);
                                        
                    $this->data['EmailSummarySheet'] = ClassRegistry::init('EmailSummarySheet')->find('all', array('conditions' => array('client_id'=>$id,'EmailSummarySheet.type'=>'regional')));
                    $summaryEmails = ClassRegistry::init('EmailSummarySheet')->find('all', array('conditions' => array('client_id'=>$id,'EmailSummarySheet.type'=>'summary')));
                    $this->set('summaryEmails',$summaryEmails);
                    
                    $flashEmails = ClassRegistry::init('FlashEmail')->find('all', array('conditions' => array('client_id'=>$id)));
                    $this->set('flashEmailsList',$flashEmails);
                    
		}
	}//end admin_edit()


	/**
	 * Admin action to logically delete a client
	 * 
	 * @param integer $id The ID of the client to be deleted
	 * @access public
	 * @return void
	 */
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Hotel', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Client->softDelete($id)) {
			$this->Session->setFlash(__('Hotel deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Hotel was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}//end admin_delete()

	public function admin_get_user_list(){

		$parent_hotels = $this->Client->find('all',array('conditions'=>array('Client.parent_id'=>0,'Client.status !=' => 2)));

		$i = '0';
		foreach($parent_hotels as $hotels){
			$id = $hotels['Client']['id'];
			$user_obj = ClassRegistry::init('User');
			$user_obj->unbindModel(array('hasMany'=>array('DepartmentsUser'), 'hasOne'=>array('Sheet')));
			$user_info = $user_obj->find('all',array('conditions'=>array('User.client_id'=>$id,'User.status'=>1)));
			$related_users = array();
			if(!empty($user_info)){
			      foreach($user_info as $user){
				      $related_users[] = $user['User'];
			      }
			}
			$client_info[$i] = $this->Client->read(null, $id);
			$client_info[$i]['User'] = $related_users;
		$i++; }

		$this->set('clients', $client_info);

	}

	public function get_subhotel_list($id=null){

		$this->autoRender = false;
		$this->layout = '';

		$parent_hotels = $this->Client->find('all',array('conditions'=>array('Client.parent_id'=>$id,'Client.status !=' => 2)));

		$i = '0';
		foreach($parent_hotels as $hotels){
			$id = $hotels['Client']['id'];
			$user_obj = ClassRegistry::init('User');
			$user_obj->unbindModel(array('hasMany'=>array('DepartmentsUser'), 'hasOne'=>array('Sheet')));
			$user_info = $user_obj->find('all',array('conditions'=>array('User.client_id'=>$id,'User.status'=>1)));
			$related_users = array();
			if(!empty($user_info)){
			      foreach($user_info as $user){
				      $related_users[] = $user['User'];
			      }
			}
			$client_info[$i] = $this->Client->read(null, $id);
			$client_info[$i]['User'] = $related_users;
		$i++; }

		return $client_info;
	}
	
	
	public function landing($hotel_name = null){
		$hotel_name = urldecode($hotel_name);
		$this->layout = ''; 
		$client_info = $this->Client->find('first',array('conditions'=>array('Client.hotelname'=>$hotel_name,'Client.parent_id'=>0,'Client.status'=>1)));
		$this->set('client_info',$client_info);
	}
        
        
       function get_chart($client_id=null,$month=null,$year=null,$departmentId=null){
		$this->layout = '';

                App::import('Model','Department');
		$this->Department = new Department();
                
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
                
            $this->Client->Department->recursive = -1;
            
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
              
             //$days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,date('m'), date('Y'));
               $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year);
              
           $bob_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columns['BOB'],'Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));
            $adr_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columns['ADR'],'Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=> array('Datum.value'),'order'=>'Datum.date ASC'));

            $bob_arr = '';
                    $adr_arr = '';
                    $date_arr = '';
                    $bob_count = 0; $date_count = 0; $adr_count = 0;
                    foreach($bob_value as $bob){
                        if($bob_count == ''){
                        $bob_arr = str_replace( ',', '', $bob['Datum']['value'] );
                        }else{
                          $bob_arr = $bob_arr.','.str_replace( ',', '', $bob['Datum']['value'] );  
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
                          $adr_arr = $adr_arr.','. str_replace( ',', '',$adr_final );;  
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
        
        function get_forecast_chart($client_id=null,$month=null,$year=null){
            	$this->layout = '';

                App::import('Model','Department');
		$this->Department = new Department();
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
                
            $this->Client->Department->recursive = -1;
            
            
            $condition = array('Department.client_id' => $client_id,'Department.name' => 'Rooms','status'=>'1');
            $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
            if(empty($dept_data)){
                $condition = array('Department.client_id' => $client_id,'Department.name LIKE' => 'Room%','status'=>'1');
                $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
            }

           
            $dept_ids = array ();
             unset($dept_ids);
            foreach($dept_data as $dept){
                $dept_ids[] = $dept['Department']['id'];
            }

                App::import('Model','Sheet');
		$this->Sheet = new Sheet();

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
                        $bob_arr = str_replace( ',', '',$bob['Datum']['value']);
                        }else{
                          $bob_arr = $bob_arr.','.str_replace( ',', '',$bob['Datum']['value']);
                        }
                        $bob_count++;
                    }
                    $adr_arr = '';
                    foreach($adr_value as $adr){
                      
                        $adr['Datum']['value'] = str_replace( ',', '',$adr['Datum']['value'] );
                        $adr1 = number_format($adr['Datum']['value'], 2);
                
                        $adr_final = $adr1;
                        
                        if($adr_count == ''){
                        $adr_arr =  str_replace( ',', '',$adr_final);
                        }else{
                          $adr_arr = $adr_arr.','.str_replace( ',', '',$adr_final);
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

                App::import('Model','Department');
		$this->Department = new Department();
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
                
            $this->Client->Department->recursive = -1;
            
            $condition = array('Department.client_id' => $client_id,'Department.name' => 'Rooms','status'=>'1');
            $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
            if(empty($dept_data)){

                $condition = array('Department.client_id' => $client_id,'Department.name LIKE' => 'Room%','status'=>'1');
                $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
            }

           
            $dept_ids = array ();
             unset($dept_ids);
            foreach($dept_data as $dept){
                $dept_ids[] = $dept['Department']['id'];
            }

                App::import('Model','Sheet');
		$this->Sheet = new Sheet();
                $columns = array('62','63','64','65');
            $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.year'=>$year,'Sheet.department_id'=>$dept_ids,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
            $sheetId = $all_sheets[0]['Sheet']['id'];
             unset($bob_fcst_value);
              unset($adr_fsct_value);
               unset($bob_value);
              unset($adr_value);

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
                        $bob_arr = str_replace( ',', '',$bob['Datum']['value']);
                        }else{
                          $bob_arr = $bob_arr.','.str_replace( ',', '',$bob['Datum']['value']);
                        }
                        $bob_count++;
                    }
                    $adr_arr = '';
                    foreach($adr_value as $adr){
                      
                        $adr['Datum']['value'] = str_replace( ',', '',$adr['Datum']['value'] );
                        $adr1 = number_format($adr['Datum']['value'], 2);
                        $adr_final = $adr1;
                        
                        if($adr_count == ''){
                        $adr_arr =  str_replace( ',', '',$adr_final);
                        }else{
                          $adr_arr = $adr_arr.','.str_replace( ',', '',$adr_final);  
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
                        $adr_final1 = $adr11;
                        
                        if($adr_fsct_count == ''){
                        $adr_fcst_arr = str_replace( ',', '', $adr_final1);
                        }else{
                          $adr_fcst_arr = $adr_fcst_arr.','.str_replace( ',', '', $adr_final1);;  
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


 //Function added on 20 June'2014 for ADR pickup graph
        function get_adr_pickup_chart($client_id=null,$pickup_day=null,$month=null,$year=null){

                $this->layout = '';
                App::import('Model','Department');
                $this->Department = new Department();

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
                
                $this->Client->Department->recursive = -1;
                
                $condition = array('Department.client_id' => $client_id,'Department.name' => 'Rooms','status'=>'1');
                $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
                if(empty($dept_data)){                    
                    $condition = array('Department.client_id' => $client_id,'Department.name LIKE' => 'Room%','status'=>'1');
                    $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
                }               

                $dept_ids = array ();
                unset($dept_ids);
                foreach($dept_data as $dept){
                    $dept_ids[] = $dept['Department']['id'];
                }
                
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
                        
                        $adr_fcst['Datum']['value'] = $adr_fcst['Datum']['value'] - $adr_value[$i]['Datum']['value'];
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
                        $date_arr = $i == '1' ? "'".$i."'" : $date_arr.",'".$i."'";
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

         //Function added on 20 June'2014 for ADR pickup graph
        function get_staff_adr_pickup_chart($client_id=null,$pickup_day=null,$month=null,$year=null){

                $this->layout = '';
                App::import('Model','Department');
                $this->Department = new Department();

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
                
                $this->Client->Department->recursive = -1;
                
                $condition = array('Department.client_id' => $client_id,'Department.name' => 'Rooms','status'=>'1');
                $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
                if(empty($dept_data)){                    
                    $condition = array('Department.client_id' => $client_id,'Department.name LIKE' => 'Room%','status'=>'1');
                    $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
                }               

                $dept_ids = array ();
                unset($dept_ids);
                foreach($dept_data as $dept){
                    $dept_ids[] = $dept['Department']['id'];
                }
                
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
                        
                        $adr_fcst['Datum']['value'] = $adr_fcst['Datum']['value'] - $adr_value[$i]['Datum']['value'];
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
                        $date_arr = $i == '1' ? "'".$i."'" : $date_arr.",'".$i."'";
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

        
        function admin_daily_flash($client_id=null,$date=null,$flashId=null){

            //Configure::write('debug',2);
            
            if($date == ''){
                $date = date('Y-m-d');
            }
            $this->set('client_id',$client_id);
            
            if(!empty($this->data)){
                
                //echo '<pre>'; print_r($this->data); exit;
                
                //Condition to remove the breakfast amount if included in Rooms revenue
                $total_breakfast_deduction = '0';
                $number_of_adult = $this->data['DailyFlash']['number_of_adults']; 
                $number_of_children = $this->data['DailyFlash']['number_of_childrens'];
                $adult_deduction = $number_of_adult * $this->data['DailyFlash']['deduction'];
                $child_deduction = $number_of_children * $this->data['DailyFlash']['child_deduction'];
                if($this->data['DailyFlash']['breakfast_included'] == '1'){
                    $total_breakfast_deduction = $adult_deduction + $child_deduction;
                    $this->data['DailyFlash']['revenue'] = $this->data['DailyFlash']['revenue'] - $total_breakfast_deduction;
                }                
                
               $client_id = $this->data['DailyFlash']['client_id'];
               $this->DailyFlash = ClassRegistry::init('DailyFlash');
                
                if ($this->DailyFlash->save($this->data)) {
                    if(!empty($this->data['DailyFlash']['id'])){
                        $flashId = $this->data['DailyFlash']['id'];
                    }else{
                        $flashId = $this->DailyFlash->getLastInsertId();
                    }
                    
                    $this->FlashFinance = ClassRegistry::init('FlashFinance');
                    $this->data['FlashFinance']['id'] = '';
                    $this->data['FlashFinance']['daily_flash_id'] = $flashId;
                    $this->FlashFinance->save($this->data);
                    
                    $this->FlashCash = ClassRegistry::init('FlashCash');
                    $this->FlashCash->deleteAll(array('FlashCash.daily_flash_id' => $flashId));
                    if(!empty ($this->data['FlashCash'])){
                        foreach($this->data['FlashCash'] as $cash_type => $cash_values){
                            foreach($cash_values as $cash_name => $values){
                                $flash_cash_data['FlashCash']['id'] = '';
                                $flash_cash_data['FlashCash']['daily_flash_id'] = $flashId;
                                $flash_cash_data['FlashCash']['cash_type'] = $cash_type;
                                $flash_cash_data['FlashCash']['name'] = $cash_name;
                                $flash_cash_data['FlashCash']['value'] = $values;
                                $this->FlashCash->save($flash_cash_data);
                            }
                        }
                    }
                    
                    if($this->data['DailyFlash']['submit'] == 'Save Draft'){
                        $this->Session->setFlash(__('Draft Saved Successfully.', true));
                        $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'flash',$client_id));
                    }else{
                        $this->requestAction('/Clients/flash_verified/'.$client_id.'/'.$flashId);
                    }
                } else {
                        $this->Session->setFlash(__('Unable to generate Report. Please, try again.', true));
                }
            }else{
               
                    $this->DailyFlash = ClassRegistry::init('DailyFlash');
                    $this->DailyFlash->recursive = 1;
                    if(!empty($flashId)){
                            $this->data = $this->DailyFlash->find('first', array('conditions' => array('DailyFlash.id'=>$flashId)));
                            $this->FlashFinance = ClassRegistry::init('FlashFinance');
                            $this->data['FlashFinance'] = $this->FlashFinance->find('first', array('conditions' => array('FlashFinance.daily_flash_id'=>$flashId)));
                            $this->FlashCash = ClassRegistry::init('FlashCash');
                            $this->data['FlashCash'] = $this->FlashCash->find('all', array('conditions' => array('FlashCash.daily_flash_id'=>$flashId),'fields'=>array('cash_type,name,value')));
                            //echo '<pre>here'; print_r($this->data['FlashCash']); echo '</pre>';
                    }else{
                            $flashData = $this->DailyFlash->find('first', array('conditions' => array('DailyFlash.client_id'=>$client_id,'DailyFlash.date'=>$date),'fields'=>'id'));
                            if(!empty($flashData)){
                                $this->Session->setFlash(__('Report Already Filled Selected date.', true));
                                $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'flash', $client_id));
                            }
                    }
                    $this->set('date',$date);

//                    $this->AdvancedSheet = ClassRegistry::init('AdvancedSheet');
//                    $this->User = ClassRegistry::init('User');
//
//                    $users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));
//
//                    $this->Client->Department->recursive = -1;
//                    $condition = array('Department.client_id' => $client_id,'Department.name' => 'Rooms','status'=>'1');
//                    $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
//                    if(empty($dept_data)){
//                        $condition = array('Department.client_id' => $client_id,'Department.name LIKE' => 'Room%','status'=>'1');
//                        $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
//                    }
//
//                    $dept_ids = array ();
//                    unset($dept_ids);
//                    foreach($dept_data as $dept){
//                        $dept_ids[] = $dept['Department']['id'];
//                    }
            }
        }
        
        function get_flash_cashes($flashId=null){
            
            $this->autoRender = false;
            $this->layout = false;
            
            $this->FlashCash = ClassRegistry::init('FlashCash');
            $this->FlashCash->recursive = 1;
            $this->data['FlashCash'] = $this->FlashCash->find('all', array('conditions' => array('FlashCash.daily_flash_id'=>$flashId),'fields'=>array('cash_type,name,value')));
            return $this->data['FlashCash'];
        }
        
        function admin_flash_report($client_id=null,$flashId=null){

            $this->DailyFlash = ClassRegistry::init('DailyFlash');
            $this->DailyFlash->recursive = 1;
            $flashData = $this->DailyFlash->find('first', array('conditions' => array('DailyFlash.id'=>$flashId)));
            $this->set('flashData',$flashData);
            
            //Get Rooms Department ID
            $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
            
            $financeData = $this->requestAction('/Clients/get_flash_finances/'.$flashId);
            $this->set('financeData',$financeData);
            
            $cashData = $this->requestAction('/Clients/get_flash_cashes/'.$flashId);
            $this->set('cashData',$cashData);

            $day = date('d',strtotime($flashData['DailyFlash']['date']));
            $month = date('m',strtotime($flashData['DailyFlash']['date']));
            $year = date('Y',strtotime($flashData['DailyFlash']['date']));
            
            //Get the market segments of Rooms department Segmentation sheet
            $this->User = ClassRegistry::init('User');
            $users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));
            
            $this->AdvancedSheet = ClassRegistry::init('AdvancedSheet');
            $this->AdvancedSheet->recursive = '-1';
            $conditions = array('AdvancedSheet.status !='=>'2','AdvancedSheet.department_id'=>$dept_ids,'AdvancedSheet.user_id'=>$users,'AdvancedSheet.month'=>$month,'AdvancedSheet.year'=>$year);
            $sheetData = $this->AdvancedSheet->find('all', array('conditions' => $conditions,'fields'=>  array('AdvancedSheet.market_segments,AdvancedSheet.id')));
            $sheet_segments = array();
            foreach($sheetData as $sheetSeg){
                $sheet_segments[] = explode(',',$sheetSeg['AdvancedSheet']['market_segments']);
            }
            $sheetSegment = call_user_func_array('array_merge', $sheet_segments);
            $sheetSegment = array_unique($sheetSegment);
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$sheetSegment)));
            $this->set('marketsegments',$marketsegments);
            $this->set('client_id',$client_id);

            //Get BOB and ADR values for each market segment
            $adr_segments = $this->AdvancedSheet->AdvanceData->find('list',array('conditions'=>array('AdvanceData.column_id'=>'64','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date'=>$day),'fields'=> array('AdvanceData.market_segment_id','AdvanceData.value'),'order'=>'AdvanceData.date ASC'));
            $bob_segments = $this->AdvancedSheet->AdvanceData->find('list',array('conditions'=>array('AdvanceData.column_id'=>'62','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date'=>$day),'fields'=> array('AdvanceData.market_segment_id','AdvanceData.value'),'order'=>'AdvanceData.date ASC'));
            $this->set('adr_segments',$adr_segments);
            $this->set('bob_segments',$bob_segments);            
            
            //Get Month-To-Date BOB and ADR values for each market segment
            $flash_date = strtotime($flashData['DailyFlash']['date']);
            $day = date('d',$flash_date);
            //$prev_day = $day - 1;
            $prev_day = $day;
            $month_adr_segments = $this->AdvancedSheet->AdvanceData->find('all',array('conditions'=>array('AdvanceData.column_id'=>'64','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date !='=>'Total','AdvanceData.date >='=>'1','AdvanceData.date <='=>$prev_day),'fields'=> array('AdvanceData.market_segment_id','sum(AdvanceData.value) as value'),'group'=>'AdvanceData.market_segment_id'));
            $month_bob_segments = $this->AdvancedSheet->AdvanceData->find('all',array('conditions'=>array('AdvanceData.column_id'=>'62','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date !='=>'Total','AdvanceData.date >='=>'1','AdvanceData.date <='=>$prev_day),'fields'=> array('AdvanceData.market_segment_id','sum(AdvanceData.value) as value'),'group'=>'AdvanceData.market_segment_id'));
            $month_adr_segments = Set::combine($month_adr_segments, '{n}.AdvanceData.market_segment_id', '{n}.0.value');
            $month_bob_segments = Set::combine($month_bob_segments, '{n}.AdvanceData.market_segment_id', '{n}.0.value');
            $this->set('month_adr_segments',$month_adr_segments);
            $this->set('month_bob_segments',$month_bob_segments);
            
            //Get Room Department sheet values
            App::import('Model','Sheet');
            $this->Sheet = new Sheet();
            $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
            $sheetId = $all_sheets[0]['Sheet']['id'];
            $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year);
            //Fcst Rooms Total,Fcst Revenue,Fcst Revpar
            $columnIds = array ('63','69','70');
            $total_field_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columnIds,'Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=>array('Datum.column_id','sum(Datum.value) as value'),'group'=>array('Datum.column_id')));
            $total_field_value = Set::combine($total_field_value, '{n}.Datum.column_id', '{n}.0.value');
            $this->set('total_field_value',$total_field_value);
            
            //Get total values for Restaurant Department
            $condition = array('Department.client_id' => $client_id, 'Department.name LIKE' => 'Restaurant%', 'Department.status' => '1');
            $res_dept_ids = $this->Client->Department->find('list', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $all_res_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$res_dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            $resSheetId = $all_res_sheets[0]['Sheet']['id'];
            //Covers,Ave Spend,Rev Fcst(revenue),RevPASH
            $columnResIds = array ('93','85','69','82');
            $total_restaurant = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columnResIds,'Datum.sheet_id'=>$resSheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=>array('Datum.column_id','sum(Datum.value) as value'),'group'=>array('Datum.column_id')));
            $total_restaurant = Set::combine($total_restaurant, '{n}.Datum.column_id', '{n}.0.value');
            $this->set('total_restaurant',$total_restaurant);
            
            
            $flash_date = strtotime($flashData['DailyFlash']['date']);
             $startDate = date('d', strtotime("+1 day", $flash_date));
             //$endDate = date('d', strtotime("+7 day", $flash_date));
            $endDate = $startDate + '6';
            if((int)$days_in_presnt_month <= (int)$endDate){
                $endDate = $days_in_presnt_month;
            }
            
            $adr_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'64','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));
            $bob_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'62','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));
            $notes_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'128','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));
            
            $this->set('adr_value',$adr_value);
            $this->set('bob_value',$bob_value);
            $this->set('notes_value',$notes_value);
            
            //get month-to-date values for all flash table columns
            $month_start = date('Y-m-01', strtotime($flashData['DailyFlash']['date']));
            //echo '/Clients/flash_month_to_date/'.$client_id.'/'.$month_start.'/'.$flashData['DailyFlash']['date'];
            $monthToDateArr = $this->requestAction('/Clients/flash_month_to_date/'.$client_id.'/'.$month_start.'/'.$flashData['DailyFlash']['date']);
            $this->set('monthToDateArr',$monthToDateArr);
            //echo '<pre>here'; print_r($monthToDateArr); echo '</pre>';
        }
        
        function admin_flash($clientId=null){
            
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$clientId),'fields'=>'Client.hotelname'));
            $hotelname = $client_name['Client']['hotelname'];
            $this->set('hotelname',$hotelname);
            $this->set('clientId',$clientId);
            
            $this->DailyFlash = ClassRegistry::init('DailyFlash');
            $unverifiedFlash = $this->DailyFlash->find('all', array('conditions' => array('DailyFlash.client_id'=>$clientId,'DailyFlash.is_verified'=>'0'),'fields'=>'id,date'));
            $this->set('unverifiedFlash',$unverifiedFlash);
            
            if(!empty ($this->data)){
                $date = $this->data['year'].'-'.sprintf("%02d",$this->data['month']).'-'.sprintf("%02d",$this->data['flash_date']);
                $this->DailyFlash = ClassRegistry::init('DailyFlash');
                $this->DailyFlash->recursive = 1;
                $flashData = $this->DailyFlash->find('first', array('conditions' => array('DailyFlash.client_id'=>$this->data['DailyFlash']['client_id'],'DailyFlash.date'=>$date),'fields'=>'id'));
                
                $client_id = $this->data['DailyFlash']['client_id'];
                
                if($this->data['DailyFlash']['new_input'] == '1'){
                        if(empty($flashData)){
                            $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'daily_flash',$client_id,$date));
                        }else{
                            $this->Session->setFlash(__('Flash Data Already inputed for selected Date', true));
                            $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'flash', $client_id));
                        }
                }else{
                    if(!empty($flashData)){
                        $flash_id = $flashData['DailyFlash']['id'];
                        $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'flash_report',$client_id ,$flash_id));
                    }else{
                        $this->Session->setFlash(__('No Report Found', true));
                        $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'flash', $client_id));
                    }
                }
            }
        }

        function admin_land($client_id=null){
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname'));
            $hotelname = $client_name['Client']['hotelname'];
            $this->set('hotelname',$hotelname);
            $this->set('client_id',$client_id);
        }
        
        
        function flash_verified($client_id=null,$flashId=null,$type='admin'){
            $this->DailyFlash = ClassRegistry::init('DailyFlash');
            if ($this->DailyFlash->saveField('is_verified', '1')) {
                
                //send email report to all users in email list
                $sendFlashUpdate = $this->requestAction('/Clients/email_flash/'.$client_id.'/'.$flashId);
                
                if($type == 'admin'){
                    $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'flash_report',$client_id,$flashId));
                }elseif($type == 'client'){
                  $this->redirect(array('prefix' => 'client', 'client' => true, 'controller' => 'clients', 'action' => 'flash_report',$flashId));  
                }
            }
        }
        
        //function to generate PDF of Flash report
        function flash_pdf($client_id=null,$flashId=null){
            
            //Configure::write('debug',2);
      
            $this->DailyFlash = ClassRegistry::init('DailyFlash');
            $this->DailyFlash->recursive = 1;
            $flashData = $this->DailyFlash->find('first', array('conditions' => array('DailyFlash.id'=>$flashId)));
            $this->set('flashData',$flashData);
            
            $financeData = $this->requestAction('/Clients/get_flash_finances/'.$flashId);
            $this->set('financeData',$financeData);
            
            $cashData = $this->requestAction('/Clients/get_flash_cashes/'.$flashId);
            $this->set('cashData',$cashData);
            
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname,Client.logo'));
            $hotelname = $client_name['Client']['hotelname'];
            $clienImage = $client_name['Client']['logo'];
            $this->set('hotelname',$hotelname);
            $this->set('clienImage',$clienImage);
            
            //Get Rooms Department ID
            $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
            
            $day = date('d',strtotime($flashData['DailyFlash']['date']));
            $month = date('m',strtotime($flashData['DailyFlash']['date']));
            $year = date('Y',strtotime($flashData['DailyFlash']['date']));
            
            //Get the market segments of Rooms department Segmentation sheet
            
            $this->User = ClassRegistry::init('User');
            $users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));
            
            $this->AdvancedSheet = ClassRegistry::init('AdvancedSheet');
            $this->AdvancedSheet->recursive = '-1';
            $conditions = array('AdvancedSheet.status !='=>'2','AdvancedSheet.department_id'=>$dept_ids,'AdvancedSheet.user_id'=>$users,'AdvancedSheet.month'=>$month,'AdvancedSheet.year'=>$year);
            $sheetData = $this->AdvancedSheet->find('all', array('conditions' => $conditions,'fields'=>  array('AdvancedSheet.market_segments,AdvancedSheet.id')));
            $sheet_segments = array();
            foreach($sheetData as $sheetSeg){
                $sheet_segments[] = explode(',',$sheetSeg['AdvancedSheet']['market_segments']);
            }
            
            $flash_date = strtotime($flashData['DailyFlash']['date']);
            $day = date('d',$flash_date);
            $prev_day = $day;
                
            if(!empty($sheet_segments)){
                $sheetSegment = call_user_func_array('array_merge', $sheet_segments);
                $sheetSegment = array_unique($sheetSegment);
                $this->MarketSegment = ClassRegistry::init('MarketSegment');
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$sheetSegment)));
            
            
                //Get BOB and ADR values for each market segment
                $adr_segments = $this->AdvancedSheet->AdvanceData->find('list',array('conditions'=>array('AdvanceData.column_id'=>'64','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date'=>$day),'fields'=> array('AdvanceData.market_segment_id','AdvanceData.value'),'order'=>'AdvanceData.date ASC'));
                $bob_segments = $this->AdvancedSheet->AdvanceData->find('list',array('conditions'=>array('AdvanceData.column_id'=>'62','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date'=>$day),'fields'=> array('AdvanceData.market_segment_id','AdvanceData.value'),'order'=>'AdvanceData.date ASC'));
                $month_adr_segments = $this->AdvancedSheet->AdvanceData->find('all',array('conditions'=>array('AdvanceData.column_id'=>'64','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date !='=>'Total','AdvanceData.date >='=>'1','AdvanceData.date <='=>$prev_day),'fields'=> array('AdvanceData.market_segment_id','sum(AdvanceData.value) as value'),'group'=>'AdvanceData.market_segment_id'));
                $month_bob_segments = $this->AdvancedSheet->AdvanceData->find('all',array('conditions'=>array('AdvanceData.column_id'=>'62','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date !='=>'Total','AdvanceData.date >='=>'1','AdvanceData.date <='=>$prev_day),'fields'=> array('AdvanceData.market_segment_id','sum(AdvanceData.value) as value'),'group'=>'AdvanceData.market_segment_id'));
                $month_adr_segments = Set::combine($month_adr_segments, '{n}.AdvanceData.market_segment_id', '{n}.0.value');
                $month_bob_segments = Set::combine($month_bob_segments, '{n}.AdvanceData.market_segment_id', '{n}.0.value');
            }else{
                $marketsegments = '';  $adr_segments=''; $bob_segments=''; $month_adr_segments='';$month_bob_segments='';
            }
            
            $this->set('marketsegments',$marketsegments);
            $this->set('client_id',$client_id);
            $this->set('adr_segments',$adr_segments);
            $this->set('bob_segments',$bob_segments);            
            
            //Get Month-To-Date BOB and ADR values for each market segment
            
            $this->set('month_adr_segments',$month_adr_segments);
            $this->set('month_bob_segments',$month_bob_segments);
            
            //Get Room Department sheet values
            App::import('Model','Sheet');
            $this->Sheet = new Sheet();
            $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
            $sheetId = $all_sheets[0]['Sheet']['id'];
            $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year);
            //Fcst Rooms Total,Fcst Revenue,Fcst Revpar
            $columnIds = array ('63','69','70');
            $total_field_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columnIds,'Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=>array('Datum.column_id','sum(Datum.value) as value'),'group'=>array('Datum.column_id')));
            $total_field_value = Set::combine($total_field_value, '{n}.Datum.column_id', '{n}.0.value');
            $this->set('total_field_value',$total_field_value);
            
            //Get total values for Restaurant Department
            $condition = array('Department.client_id' => $client_id, 'Department.name LIKE' => 'Restaurant%', 'Department.status' => '1');
            $res_dept_ids = $this->Client->Department->find('list', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $all_res_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$res_dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            if(!empty($all_res_sheets)){
                $resSheetId = $all_res_sheets[0]['Sheet']['id'];
                //Covers,Ave Spend,Rev Fcst(revenue),RevPASH
                $columnResIds = array ('93','85','69','82');
                $total_restaurant = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columnResIds,'Datum.sheet_id'=>$resSheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=>array('Datum.column_id','sum(Datum.value) as value'),'group'=>array('Datum.column_id')));
                $total_restaurant = Set::combine($total_restaurant, '{n}.Datum.column_id', '{n}.0.value');
            }else{
                $total_restaurant = '';
            }
            
            $this->set('total_restaurant',$total_restaurant);
            
            
            $flash_date = strtotime($flashData['DailyFlash']['date']);
            $startDate = date('d', strtotime("+1 day", $flash_date));
            //$endDate = date('d', strtotime("+7 day", $flash_date));
            $endDate = $startDate + '6';
            if((int)$days_in_presnt_month <= (int)$endDate){
                $endDate = $days_in_presnt_month;
            }
            
            $adr_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'64','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));
            $bob_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'62','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));
            $notes_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'128','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));
            $this->set('adr_value',$adr_value);
            $this->set('bob_value',$bob_value);
            $this->set('notes_value',$notes_value);
            
            //get month-to-date values for all flash table columns
            $month_start = date('Y-m-01', strtotime($flashData['DailyFlash']['date']));
            $monthToDateArr = $this->requestAction('/Clients/flash_month_to_date/'.$client_id.'/'.$month_start.'/'.$flashData['DailyFlash']['date']);
            $this->set('monthToDateArr',$monthToDateArr);
        }
        
        //function to sent flash update everyday by cron
        function email_flash($client_id=null,$flashId=null){
            
            //Configure::write('debug',2);
            
            $this->autoRender = false;
            $this->layout = false;
            
            $this->DailyFlash = ClassRegistry::init('DailyFlash');
            $this->DailyFlash->recursive = 1;
            $flashData = $this->DailyFlash->find('first', array('conditions' => array('DailyFlash.id'=>$flashId)));
            $client_email = $flashData['Client']['email'];
            $financeData = $this->requestAction('/Clients/get_flash_finances/'.$allFlash['DailyFlash']['id']);
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>'Client.hotelname,Client.logo'));
            $hotelname = $client_name['Client']['hotelname'];
            $clienImage = $client_name['Client']['logo'];
            
            $financeData = $this->requestAction('/Clients/get_flash_finances/'.$flashId);
            
            $cashData = $this->requestAction('/Clients/get_flash_cashes/'.$flashId);
            
            //Get Rooms Department ID
            $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);

            $day = date('d',strtotime($flashData['DailyFlash']['date']));
            $month = date('m',strtotime($flashData['DailyFlash']['date']));
            $year = date('Y',strtotime($flashData['DailyFlash']['date']));

            //Get the market segments of Rooms department Segmentation sheet
            $this->User = ClassRegistry::init('User');
            $users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));

            $this->AdvancedSheet = ClassRegistry::init('AdvancedSheet');
            $this->AdvancedSheet->recursive = '-1';
            $conditions = array('AdvancedSheet.status !='=>'2','AdvancedSheet.department_id'=>$dept_ids,'AdvancedSheet.user_id'=>$users,'AdvancedSheet.month'=>$month,'AdvancedSheet.year'=>$year);
            $sheetData = $this->AdvancedSheet->find('all', array('conditions' => $conditions,'fields'=>  array('AdvancedSheet.market_segments,AdvancedSheet.id')));
            $sheet_segments = array();
            foreach($sheetData as $sheetSeg){
                $sheet_segments[] = explode(',',$sheetSeg['AdvancedSheet']['market_segments']);
            }
            $sheetSegment = call_user_func_array('array_merge', $sheet_segments);
            $sheetSegment = array_unique($sheetSegment);
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$sheetSegment)));
 
            //Get BOB and ADR values for each market segment                    
            $adr_segments = $this->AdvancedSheet->AdvanceData->find('list',array('conditions'=>array('AdvanceData.column_id'=>'64','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date'=>$day),'fields'=> array('AdvanceData.market_segment_id','AdvanceData.value'),'order'=>'AdvanceData.date ASC'));
            $bob_segments = $this->AdvancedSheet->AdvanceData->find('list',array('conditions'=>array('AdvanceData.column_id'=>'62','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date'=>$day),'fields'=> array('AdvanceData.market_segment_id','AdvanceData.value'),'order'=>'AdvanceData.date ASC'));

            //Get Month-To-Date BOB and ADR values for each market segment
            $flash_date = strtotime($flashData['DailyFlash']['date']);
            $day = date('d',$flash_date);
            $prev_day = $day;
            $month_adr_segments = $this->AdvancedSheet->AdvanceData->find('all',array('conditions'=>array('AdvanceData.column_id'=>'64','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date !='=>'Total','AdvanceData.date >='=>'1','AdvanceData.date <='=>$prev_day),'fields'=> array('AdvanceData.market_segment_id','sum(AdvanceData.value) as value'),'group'=>'AdvanceData.market_segment_id'));
            $month_bob_segments = $this->AdvancedSheet->AdvanceData->find('all',array('conditions'=>array('AdvanceData.column_id'=>'62','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date !='=>'Total','AdvanceData.date >='=>'1','AdvanceData.date <='=>$prev_day),'fields'=> array('AdvanceData.market_segment_id','sum(AdvanceData.value) as value'),'group'=>'AdvanceData.market_segment_id'));
            $month_adr_segments = Set::combine($month_adr_segments, '{n}.AdvanceData.market_segment_id', '{n}.0.value');
            $month_bob_segments = Set::combine($month_bob_segments, '{n}.AdvanceData.market_segment_id', '{n}.0.value');

            //Get Room Department sheet values
            App::import('Model','Sheet');
            $this->Sheet = new Sheet();
            $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            $sheetId = $all_sheets[0]['Sheet']['id'];
            $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year);
            //Fcst Rooms Total,Fcst Revenue,Fcst Revpar
            $columnIds = array ('63','69','70');
            $total_field_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columnIds,'Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=>array('Datum.column_id','sum(Datum.value) as value'),'group'=>array('Datum.column_id')));
            $total_field_value = Set::combine($total_field_value, '{n}.Datum.column_id', '{n}.0.value');

            //Get total values for Restaurant Department
            $condition = array('Department.client_id' => $client_id, 'Department.name LIKE' => 'Restaurant%', 'Department.status' => '1');
            $res_dept_ids = $this->Client->Department->find('list', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $all_res_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$res_dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            $resSheetId = $all_res_sheets[0]['Sheet']['id'];
            //Covers,Ave Spend,Rev Fcst(revenue),RevPASH
            $columnResIds = array ('93','85','69','82');
            $total_restaurant = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columnResIds,'Datum.sheet_id'=>$resSheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=>array('Datum.column_id','sum(Datum.value) as value'),'group'=>array('Datum.column_id')));
            $total_restaurant = Set::combine($total_restaurant, '{n}.Datum.column_id', '{n}.0.value');

            $flash_date = strtotime($flashData['DailyFlash']['date']);
            $startDate = date('d', strtotime("+1 day", $flash_date));
            //$endDate = date('d', strtotime("+7 day", $flash_date));
            $endDate = $startDate + '6';
            if((int)$days_in_presnt_month <= (int)$endDate){
                $endDate = $days_in_presnt_month;
            }

            $adr_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'64','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));
            $bob_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'62','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));
            $notes_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'128','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));

            //get month-to-date values for all flash table columns
            $month_start = date('Y-m-01', strtotime($flashData['DailyFlash']['date']));
            $monthToDateArr = $this->requestAction('/Clients/flash_month_to_date/'.$client_id.'/'.$month_start.'/'.$flashData['DailyFlash']['date']);

            $file_path = $_SERVER['DOCUMENT_ROOT'] . "/app/webroot/email_webform/" . $sheetId;
            if (@file_exists($file_path)) {
                @chmod($file_path, 0777);
            } else {
                @mkdir($file_path, '0777');
                @chmod($file_path, 0777);
            }
                    
            ob_start();
            App::import('Vendor','tcpdf');
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8");
            $date = date('Y-m-d');
            $htms = '';
            $htms.='<table border="">';
            $htms.='<tr><td>Hotel Name : '.$hotelname.'</td></tr>';
            $htms.='<tr><td>Report For   : '.date('d F Y',strtotime($flashData['DailyFlash']['date'])).'</td></tr>';
            $htms.='<tr><td>Downloaded Date : '.date('m-d-Y').'</td></tr>';
            $htms.='</table>';
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->SetHeaderMargin(-1);
            $pdf->SetFooterMargin(-2);
            $textfont = 'freesans';
            $pdf->SetAuthor("Revenue Performance at www.myrevenuedashboard.net");
            $pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);
            $pdf->setHeaderFont(array($textfont,'',8));
            $pdf->SetFont($textfont, '', 10);
            $pdf->xheadercolor = array(150,0,0);
            $pdf->xheadertext = 'Selected ';
            $pdf->xfootertext = "Copyright &copy; Revenue Performance. All rights reserved.";
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
            $pdf->SetAutoPageBreak(true); 
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(true);
            $pdf->SetFillColorArray(array(255, 255, 255));
            $pdf->SetTextColor(0, 0, 0);
             if (!empty($clienImage)) {
                $ext = pathinfo($clienImage, PATHINFO_EXTENSION);
                if ($ext == "png" || $ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "bmp") {
                    $exts = split("[/\\.]", $clienImage);
                    $n = count($exts) - 1;
                    $exts = $exts[$n];
                    $imgPath = WWW_ROOT . 'files' . DS . 'clientlogos' . DS . $clienImage;
                    $pdf->Image($imgPath, 245, 32, 40, 14, $exts, '', '', true, 150);
                }
            }

            $pdf->SetXY(5, 25);
            $pdf->writeHTML($htms, true, false, true, false, '');

                            $number_of_rooms = $flashData['Client']['number_of_rooms'] == '' ? '1' : $flashData['Client']['number_of_rooms'];
                $restaurant_open_hours = $flashData['Client']['restaurant_open_hours'] == '' ? '6' : $flashData['Client']['restaurant_open_hours'];
                $chairs_in_restaurant = $flashData['Client']['chairs_in_restaurant'] == '' ? '1' : $flashData['Client']['chairs_in_restaurant'];
                
                $number_of_adult = $flashData['DailyFlash']['number_of_adults']; 
                $number_of_children = $flashData['DailyFlash']['number_of_childrens']; 
                
                $adult_ded = $flashData['DailyFlash']['deduction'];
                $child_ded = $flashData['DailyFlash']['child_deduction'];
                
            $html234 = "\n".'<hr/>';
            $html234 .= '<h2>Daily Operations</h2>';
            $html234 .= '<table border="1" cellpadding="2" cellspacing="1">
                        <tr><td><b>Arrivals</b></td><td>'.$flashData['DailyFlash']['total_arrival'].'</td></tr>
                        <tr><td><b>Departures</b></td><td>'.$flashData['DailyFlash']['total_departure'].'</td></tr>
                        <tr><td><b>Group Arrivals</b></td><td>'.$flashData['DailyFlash']['group_arrival'].'</td></tr>
                        <tr><td><b>Group Departures</b></td><td>'.$flashData['DailyFlash']['group_departure'].'</td></tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Guest Comments</b></td></tr>
                        <tr><td>'.$flashData['DailyFlash']['comments'].'</td></tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Site Inspection Rooms</b></td></tr>
                        <tr><td>'.$flashData['DailyFlash']['inspection_comments'].'</td></tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Maintenance Rooms</b></td></tr>
                        <tr><td>'.$flashData['DailyFlash']['maintainance_comments'].'</td></tr>
                    </table>
                    <p></p><hr/>
 		<h2>Reservations Summary</h2>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr>
                            <td style="color:#E32E32;background-color:#DEDEDE;"><b>Current Month</b></td>
                            <td style="background-color:#DEDEDE;">&nbsp;</td>
                            <td style="background-color:#DEDEDE;">&nbsp;</td>
                            <td style="background-color:#DEDEDE;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><b>Yesterday</b></td>
                            <td><b>Month to Date</b></td>
                            <td><b>Daily Ave.</b></td>
                        </tr>';
                        $flash_date = strtotime($flashData['DailyFlash']['date']);
                            $day = date('d',$flash_date);
                            $prev_day = $day - 1;
                            $d12 = round($monthToDateArr[0][0]['reservation']/$prev_day,2);
                            $d13 = round($monthToDateArr[0][0]['room_night']/$prev_day,2);
                $html234 .= '<tr>
                            <td><b>New Bookings</b></td>
                            <td>'.$flashData['DailyFlash']['reservation'].'</td>
                            <td>'.$monthToDateArr[0][0]['reservation'].'</td>
                            <td>'.$d12.'</td>
                        </tr>
                        <tr>
                            <td><b>New Room Nights</b></td>
                            <td>'.$flashData['DailyFlash']['room_night'].'</td>
                            <td>'.$monthToDateArr[0][0]['room_night'].'</td>
                            <td>'.$d13.'</td>
                        </tr>
                        <tr>
                            <td><b>ADR</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue']/$flashData['DailyFlash']['room_night'],2).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue']/$monthToDateArr[0][0]['room_night'],2).'</td>
                            <td>'.number_format(($monthToDateArr[0][0]['rooms_revenue']/$prev_day)/$d13,2).'</td>
                        </tr>
                        <tr>
                            <td><b>Revenue</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue']/$prev_day,0).'</td>
                        </tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr>
                            <td style="color:#E32E32;background-color:#DEDEDE;"><b>Following Month</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td><td><b>Yesterday</b></td>
                            <td><b>Month to Date</b></td>
                            <td><b>Daily Ave.</b></td>
                        </tr>';
                        $flash_date = strtotime($flashData['DailyFlash']['date']);
                            $day = date('d',$flash_date);
                            $prev_day = $day - 1;
                            $d12 = round($monthToDateArr[0][0]['reservation_next']/$prev_day,2);
                            $d13 = round($monthToDateArr[0][0]['room_night_next']/$prev_day,2);
                       $html234 .= '<tr>
                            <td><b>New Bookings</b></td>
                            <td>'.$flashData['DailyFlash']['reservation_next'].'</td>
                            <td>'.$monthToDateArr[0][0]['reservation_next'].'</td>
                            <td>'.$d12.'</td>
                        </tr>
                        <tr>
                            <td><b>New Room Nights</b></td>
                            <td>'.$flashData['DailyFlash']['room_night_next'].'</td>
                            <td>'.$monthToDateArr[0][0]['room_night_next'].'</td>
                            <td>'.$d13.'</td>
                        </tr>
                        <tr>
                            <td><b>ADR</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue_next']/$flashData['DailyFlash']['room_night_next'],2).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_next']/$monthToDateArr[0][0]['room_night_next'],2).'</td>
                            <td>'.number_format(($monthToDateArr[0][0]['rooms_revenue_next']/$prev_day)/$d13,2).'</td>
                        </tr>
                        <tr>
                            <td><b>Revenue</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue_next'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_next'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_next']/$prev_day,0).'</td>
                        </tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr>
                            <td style="color:#E32E32;background-color:#DEDEDE;"><b>Future Month</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td><td><b>Yesterday</b></td>
                            <td><b>Month to Date</b></td>
                            <td><b>Daily Ave.</b></td>
                        </tr>';
                        $flash_date = strtotime($flashData['DailyFlash']['date']);
                            $day = date('d',$flash_date);
                            $prev_day = $day - 1;
                        
                            $d12 = round($monthToDateArr[0][0]['reservation_future']/$prev_day,2);
                            $d13 = round($monthToDateArr[0][0]['room_night_future']/$prev_day,2);
                        $html234 .= '<tr>
                            <td><b>New Bookings</b></td>
                            <td>'.$flashData['DailyFlash']['reservation_future'].'</td>
                            <td>'.$monthToDateArr[0][0]['reservation_future'].'</td>
                            <td>'.$d12.'</td>
                        </tr>
                        <tr>
                            <td><b>New Room Nights</b></td>
                            <td>'.$flashData['DailyFlash']['room_night_future'].'</td>
                            <td>'.$monthToDateArr[0][0]['room_night_future'].'</td>
                            <td>'.$d13.'</td>
                        </tr>
                        <tr>
                            <td><b>ADR</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue_future']/$flashData['DailyFlash']['room_night_future'],2).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_future']/$monthToDateArr[0][0]['room_night_future'],2).'</td>
                            <td>'.number_format(($monthToDateArr[0][0]['rooms_revenue_future']/$prev_day)/$d13,2).'</td>
                        </tr>
                        <tr>
                            <td><b>Revenue</b></td>
                            <td>'.number_format($flashData['DailyFlash']['rooms_revenue_future'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_future'],0).'</td>
                            <td>'.number_format($monthToDateArr[0][0]['rooms_revenue_future']/$prev_day,0).'</td>
                        </tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Market Segmentation</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td style="text-align:center;"><b>Last Night</b></td><td>&nbsp;</td><td style="text-align:center;"><b>Month to Date</b></td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td><b>Room Nights</b></td><td><b>ADR</b></td><td><b>Room Nights</b></td><td><b>ADR</b></td></tr>';

                        if(!empty($marketsegments)){
                                foreach($marketsegments as $seg_key => $seg_val){
                            $html234 .= '<tr>
                                <td><b>'.$seg_val.'</b></td>
                                <td>'.$bob_segments[$seg_key].'</td>
                                <td>'.$adr_segments[$seg_key].'</td>
                                <td>'.$month_bob_segments[$seg_key].'</td>
                                <td>'.$month_adr_segments[$seg_key].'</td>
                            </tr>';
                            }
                        }
                    $html234 .= '</table><p></p>
                                <table border="1" cellpadding="2" cellspacing="1">
                            <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>7-Day Summary (forecast)</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                            <tr>
                                <td>&nbsp;</td>';
                                $flash_date = strtotime($flashData['DailyFlash']['date']);
                                for($i=1;$i<=7;$i++){
                        $html234 .= '<td><b>';
                                    $date = date('Y-m-d', strtotime("+".$i." day", $flash_date));
                         $html234 .= date('D',strtotime($date));
                        $html234 .=  " - ".date('d/m',strtotime($date));
                                    $html234 .= '</b></td>';
                                }
                                
                            $html234 .= '</tr>
                              <tr>
                                <td><b>Occupied</b></td>';
                                for($i=1;$i<=7;$i++){
                                $date = date('Y-m-d', strtotime("+".$i." day", $flash_date)); 
                                $html234 .= '<td>'.$bob_value[ltrim(date('d',strtotime($date)),'0')].'</td>';
                                }
                            $html234 .= '</tr>
                              <tr>
                                <td><b>ADR</b></td>';
                                for($i=1;$i<=7;$i++){
                                $date = date('Y-m-d', strtotime("+".$i." day", $flash_date));
                                    $html234 .= '<td>'.number_format($adr_value[ltrim(date('d',strtotime($date)),'0')],2).'</td>';
                                }
                            $html234 .= '</tr>
                              <tr>
                                <td><b>Note</b></td>';
                                for($i=1;$i<=7;$i++){
                                $date = date('Y-m-d', strtotime("+".$i." day", $flash_date));
                                    $html234 .= '<td>'.$notes_value[ltrim(date('d',strtotime($date)),'0')].'</td>';
                                }
                            $html234 .= '</tr>
                        </table><p></p><hr/>
 		<h2>Financial Summary</h2>';
                     
                    $number_of_rooms = $flashData['Client']['number_of_rooms'] == '' ? '1' : $flashData['Client']['number_of_rooms'];
                    $restaurant_open_hours = $flashData['Client']['restaurant_open_hours'] == '' ? '6' : $flashData['Client']['restaurant_open_hours'];
                    $chairs_in_restaurant = $flashData['Client']['chairs_in_restaurant'] == '' ? '1' : $flashData['Client']['chairs_in_restaurant'];
                    $number_of_adult = $flashData['DailyFlash']['number_of_adults']; 
                    $number_of_children = $flashData['DailyFlash']['number_of_childrens'];
                    
                $html234 .= '<table border="1" cellpadding="2" cellspacing="1">
                            <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Rooms</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                            <tr><td>&nbsp;</td><td><b>Last Night</b></td><td><b>Month to Date</b></td><td><b>Target</b></td></tr>
                            <tr>
                                <td><b>Occupied</b></td>
                                <td>'.$flashData['DailyFlash']['occupied'].'</td>
                                <td>'.$monthToDateArr[0][0]['occupied'].'</td>
                                <td>'.$total_field_value['63'].'</td>
                            </tr>
                            <tr>
                                <td><b>Ave Daily Rate</b></td>
                                <td>'.number_format($flashData['DailyFlash']['revenue']/$flashData['DailyFlash']['occupied'],2).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['revenue']/$monthToDateArr[0][0]['occupied'],2).'</td>
                                <td>'.number_format($total_field_value['69']/$total_field_value['63'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Revenue</b></td>
                                <td>'.number_format($flashData['DailyFlash']['revenue'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['revenue'],0).'</td>
                                <td>'.number_format($total_field_value['69'],0).'</td>
                            </tr>
                            <tr>
                                <td><b>RevPAR</b></td>
                                <td>'.number_format($flashData['DailyFlash']['revenue']/$number_of_rooms,2).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['revenue']/$number_of_rooms,2).'</td>
                                <td>'.number_format($total_field_value['70'],2).'</td>
                            </tr>';

                            if($flashData['DailyFlash']['breakfast_included'] == '1'){
                            $html234 .= '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                            <tr><td><b>Adults in house</b></td><td>&nbsp;</td><td ><b>'.$number_of_adult.'(Breakfast Deduction : '.$flashData['DailyFlash']['deduction'].')</b></td><td>&nbsp;</td></tr>
                             <tr><td><b>Children in house</b></td><td>&nbsp;</td><td><b>'.$number_of_children.' (Breakfast Deduction : '.$flashData['DailyFlash']['child_deduction'].')</b></td><td>&nbsp;</td></tr>';
                             }
                             
                        $html234 .= '</table><p></p>
                        <table border="1" cellpadding="2" cellspacing="1">
                            <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Restaurant</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                            <tr><td>&nbsp;</td><td><b>Last Night</b></td><td><b>Month to Date</b></td><td><b>Forecast</b></td></tr>
                            <tr>';
                               $total_rev = (float)$flashData['DailyFlash']['food_revenue'] + (float)$flashData['DailyFlash']['bev_revenue'];
                                $monthTodate_rev = $monthToDateArr[0][0]['food_revenue'] + $monthToDateArr[0][0]['bev_revenue'];
                               
                                $html234 .= '<td><b>Covers</b></td>
                                <td>'.$flashData['DailyFlash']['covers'].'</td>
                                <td>'.$monthToDateArr[0][0]['covers'].'</td>
                                <td>'.$total_restaurant['93'].'</td>
                            </tr>
                            <tr>
                                <td><b>Ave Spend</b></td>
                                <td>'.number_format($total_rev/$flashData['DailyFlash']['covers'],2).'</td>
                                <td>'.number_format($monthTodate_rev/$monthToDateArr[0][0]['covers'],2).'</td>
                                <td>'.number_format($total_restaurant['85'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Revenue</b></td>
                                <td>'.number_format($total_rev,0).'</td>
                                <td>'.number_format($monthTodate_rev,0).'</td>
                                <td>'.number_format($total_restaurant['69'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>RevPASH</b></td>
                                <td>'.number_format($total_rev/($restaurant_open_hours * $chairs_in_restaurant),2).'</td>
                                <td>'.number_format($monthTodate_rev/($restaurant_open_hours * $chairs_in_restaurant),2).'</td>
                                <td>'.number_format($total_restaurant['82'],2).'</td>
                            </tr>
                        </table><p></p>
                        <table border="1" cellpadding="2" cellspacing="1">
                            <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Other Revenues</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><b>#People</b></td>
                                <td><b>Month To Date</b></td>
                                <td><b>Revenue</b></td>
                                <td><b>Month To Date</b></td>
                                <td><b>Average Spent/Person</b></td>
                            </tr>
                            <tr>
                                <td><b>Golf</b></td>
                                <td>'.$flashData['DailyFlash']['golf_people'].'</td>
                                <td>'.$monthToDateArr[0][0]['golf_people'].'</td>
                                <td>'.number_format($flashData['DailyFlash']['golf_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['golf_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['golf_rev']/$monthToDateArr[0][0]['golf_people'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Weddings/Events</b></td>
                                <td>'.$flashData['DailyFlash']['event_people'].'</td>
                                <td>'.$monthToDateArr[0][0]['event_people'].'</td>
                                <td>'.number_format($flashData['DailyFlash']['event_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['event_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['event_rev']/$monthToDateArr[0][0]['event_people'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Conference</b></td>
                                <td>'.$flashData['DailyFlash']['conference_people'].'</td>
                                <td>'.$monthToDateArr[0][0]['conference_people'].'</td>
                                <td>'.number_format($flashData['DailyFlash']['conference_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['conference_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['conference_rev']/$monthToDateArr[0][0]['conference_people'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Watersports</b></td>
                                <td>'.$flashData['DailyFlash']['sports_people'].'</td>
                                <td>'.$monthToDateArr[0][0]['sports_people'].'</td>
                                <td>'.number_format($flashData['DailyFlash']['sports_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['sports_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['sports_rev']/$monthToDateArr[0][0]['sports_people'],2).'</td>
                            </tr>
                            <tr>
                                <td><b>Other</b></td>
                                <td>'.$flashData['DailyFlash']['other_people'].'</td>
                                <td>'.$monthToDateArr[0][0]['other_people'].'</td>
                                <td>'.number_format($flashData['DailyFlash']['other_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['other_rev'],0).'</td>
                                <td>'.number_format($monthToDateArr[0][0]['other_rev']/$monthToDateArr[0][0]['other_people'],2).'</td>
                            </tr>
                        </table><p></p>
                        <table border="1" cellpadding="2" cellspacing="1">
                            <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Banking</b></td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><b>Cash</b></td>
                            <td><b>Credit</b></td>
                            <td><b>EFT</b></td> 
                        </tr>';
                                
                        $cash_total = $financeData['FlashFinance']['rooms_cash'] + $financeData['FlashFinance']['restaurant_cash'] + $financeData['FlashFinance']['bar_cash'] + $financeData['FlashFinance']['advance_cash'];
                        $advance_total = $financeData['FlashFinance']['rooms_credit'] + $financeData['FlashFinance']['restaurant_credit'] + $financeData['FlashFinance']['bar_credit'] + $financeData['FlashFinance']['advance_credit'];
                        $eft_total = $financeData['FlashFinance']['rooms_eft'] + $financeData['FlashFinance']['restaurant_eft'] + $financeData['FlashFinance']['bar_eft'] + $financeData['FlashFinance']['advance_eft'];
                       $grand_total = $cash_total + $advance_total + $eft_total;
                        $html234 .= '<tr>
                            <td><b>Rooms</b></td>
                            <td>'.$financeData['FlashFinance']['rooms_cash'].'</td>
                            <td>'.$financeData['FlashFinance']['rooms_credit'].'</td>
                            <td>'.$financeData['FlashFinance']['rooms_eft'].'</td>
                        </tr>
                        <tr>
                            <td><b>Restaurant</b></td>
                            <td>'.$financeData['FlashFinance']['restaurant_cash'].'</td>
                            <td>'.$financeData['FlashFinance']['restaurant_credit'].'</td>
                            <td>'.$financeData['FlashFinance']['restaurant_eft'].'</td>
                        </tr>
                        <tr>
                            <td><b>Bar</b></td>
                            <td>'.$financeData['FlashFinance']['bar_cash'].'</td>
                            <td>'.$financeData['FlashFinance']['bar_credit'].'</td>
                            <td>'.$financeData['FlashFinance']['bar_eft'].'</td>
                        </tr>
                        <tr>
                            <td><b>Advance Deposits</b></td>
                            <td>'.$financeData['FlashFinance']['advance_cash'].'</td>
                            <td>'.$financeData['FlashFinance']['advance_credit'].'</td>
                            <td>'.$financeData['FlashFinance']['advance_eft'].'</td>
                        </tr>
                        <tr>
                            <td><b>Sub Total</b></td>
                            <td><b>'.$cash_total.'</b></td>
                            <td><b>'.$advance_total.'</b></td>
                            <td><b>'.$eft_total.'</b></td>
                        </tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td><b>Grand Total</b></td><td><b>'.$grand_total.'</b></td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    </table><p></p>';
                  
                    $opening_balance = '0';  $cash_banked = '0'; $total_monies = '';
                    $hand_cash_tin =''; $hand_restuarant_tin = ''; $hand_bar_tin = ''; $hand_floats = ''; $hand_miscellaneous = ''; $hand_adv_deposit = ''; $hand_data = ''; $hand_rooms ='';
                    $received_restaurant = ''; $received_bar=''; $received_accomodation = ''; $received_account = ''; $received_data=''; $received_paid_in_tin='';
                    $pay_1 =''; $pay_2 =''; $pay_3 = '';
                    $monies_tips = ''; $monies_wood = ''; $monies_bread = ''; $monies_youghurt = ''; $monies_casual_wages = ''; $monies_banked = '';
                    if(!empty($cashData)){
                        foreach($cashData as $clash_flash){
                            if($clash_flash['FlashCash']['cash_type'] == 'Hand'){
                               if($clash_flash['FlashCash']['name'] == 'rooms'){
                                    $hand_rooms = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'data'){
                                    $hand_data = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'adv_deposit'){
                                    $hand_adv_deposit = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'miscellaneous'){
                                    $hand_miscellaneous = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'cash_tin'){
                                    $hand_cash_tin = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'restuarant_tin'){
                                    $hand_restuarant_tin = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'bar_tin'){
                                    $hand_bar_tin = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'floats'){
                                    $hand_floats = $clash_flash['FlashCash']['value'];
                                }
                                $opening_balance = $opening_balance + $clash_flash['FlashCash']['value'];
                            }elseif($clash_flash['FlashCash']['cash_type'] == 'Received'){
                                if($clash_flash['FlashCash']['name'] == 'restaurant'){
                                    $received_restaurant = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'bar'){
                                    $received_bar = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'accomodation'){
                                    $received_accomodation = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'account'){
                                    $received_account = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'data'){
                                    $received_data = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'paid_in_tin'){
                                    $received_paid_in_tin = $clash_flash['FlashCash']['value'];
                                }
                            }elseif($clash_flash['FlashCash']['cash_type'] == 'Payment'){
                                if($clash_flash['FlashCash']['name'] == '1'){
                                    $pay_1 = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == '2'){
                                    $pay_2 = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == '3'){
                                    $pay_3 = $clash_flash['FlashCash']['value'];
                                }
                                $cash_banked = $cash_banked + $clash_flash['FlashCash']['value'];
                            }elseif($clash_flash['FlashCash']['cash_type'] == 'Monies'){
                                if($clash_flash['FlashCash']['name'] == 'tips'){
                                    $monies_tips = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'wood'){
                                    $monies_wood = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'bread'){
                                    $monies_bread = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'youghurt'){
                                    $monies_youghurt = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'casual_wages'){
                                    $monies_casual_wages = $clash_flash['FlashCash']['value'];
                                }elseif($clash_flash['FlashCash']['name'] == 'banked'){
                                    $monies_banked = $clash_flash['FlashCash']['value'];
                                }
                                $total_monies = $total_monies + $clash_flash['FlashCash']['value'];
                            }
                        }
                    } 
                    
                    $html234 .= '<table  border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Cash on Hand</b></td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr>
                            <td>Rooms</td><td>'.$hand_rooms.'</td>
                        </tr>
                        <tr>
                            <td >Cash Tin</td><td>'.$hand_cash_tin.'</td>
                        </tr>
                        <tr>
                            <td>Rest. Tin</td><td>'.$hand_restuarant_tin.'</td>
                        </tr>
                        <tr>
                            <td>Bar Tin</td><td>'.$hand_bar_tin.'</td>
                        </tr>
                        <tr>
                            <td>Floats</td><td>'.$hand_floats.'</td>
                        </tr>
                        <tr>
                            <td>Data</td><td>'.$hand_data.'</td>
                        </tr>
                        <tr>
                            <td>Advance deposits</td><td>'.$hand_adv_deposit.'</td>
                        </tr>
                        <tr>
                            <td>Miscellaneous</td><td>'.$hand_miscellaneous.'</td>
                        </tr>
                        <tr>
                            <td><b>Opening Balance</b></td><td><b>'.$opening_balance.'</b></td>
                        </tr>
                    </table>
                    <p></p>
                    <table border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Monies Paid-out</b></td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr>
                            <td>Tips</td><td>'.$monies_tips.'</td>
                        </tr>
                        <tr>
                            <td>Wood</td><td>'.$monies_wood.'</td>
                        </tr>
                        <tr>
                            <td>Bread</td><td>'.$monies_bread.'</td>
                        </tr>
                        <tr>
                            <td>Yogurt</td><td>'.$monies_youghurt.'</td>
                        </tr>
                        <tr>
                            <td>Casual Wages</td><td>'.$monies_casual_wages.'</td>
                        </tr>
                        <tr>
                            <td>Banked</td><td>'.$monies_banked.'</td>
                        </tr>
                        <tr>
                            <td><b>Total</b></td><td><b>'.$total_monies.'</b></td>
                        </tr>
                    </table>
                    <p></p>
                    <p></p>
                    <table  border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Cash Received</b></td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr>
                            <td>Restaurant</td><td>'.$received_restaurant.'</td>
                        </tr>
                        <tr>
                            <td>BAR</td><td>'.$received_bar.'</td>
                        </tr>
                        <tr>
                            <td>Accommodation</td><td>'.$received_accomodation.'</td>
                        </tr>
                        <tr>
                            <td>Account</td><td>'.$received_account.'</td>
                        </tr>
                        <tr>
                            <td>Data</td><td>'.$received_data.'</td>
                        </tr>
                        <tr>
                            <td>Cash Paid into Tin</td><td>'.$received_paid_in_tin.'</td>
                        </tr>
                    </table><p></p>
                    <table  border="1" cellpadding="2" cellspacing="1">
                        <tr><td style="color:#E32E32;background-color:#DEDEDE;"><b>Cash Payments</b></td><td style="background-color:#DEDEDE;">&nbsp;</td></tr>
                        <tr>
                            <td>#1</td><td>'.$pay_1.'</td>
                        </tr>
                        <tr>
                            <td>#2</td><td>'.$pay_2.'</td>
                        </tr>
                        <tr>
                            <td>#3</td><td>'.$pay_3.'</td>
                        </tr>
                        <tr>
                            <td><b>Cash Banked</b></td><td><b>'.$cash_banked.'</b></td>
                        </tr>
                    </table>';            
                        
            //echo $html234; exit;

                $pdf->SetXY(125,15);
                $pdf->SetXY(5,50);
                $pdf->writeHTML($html234, true, false, true, false, '');

                ob_end_clean();
                $path = $file_path . "/FlashReport_" . $flash_date . ".pdf";
                $pdf->Output($path, 'F'); //plz uncomment

                $email_from = "duncan.bramwell@revenue-performance.com"; // The email you are sending from (example)
                $email_subject = "Daily Flash Update"; // The Subject of the email

                $summary_table = '<tr><td>Please find the attached report for Daily Flash. </td></tr>';

                $email_txt = "<table cellspacing='0' cellpadding='0' border='0' >
                                                        <tr>
                                                        <td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>MyDashboard
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
                        
                    $fileatt = $path; // Path to the file (example)
                    $fileatt_type = "application/pdf"; // File Type
                    $fileatt_name = "FlashReport_" . $flash_date . ".pdf"; // Filename that will be used for the file as the attachment
                    $file = fopen($fileatt, 'rb');
                    $data = fread($file, filesize($fileatt));
                    fclose($file);
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
                            "Content-Type: {$fileatt_type};\n" .
                            " name=\"{$fileatt_name}\"\n" .
                            "Content-Transfer-Encoding: base64\n\n" .
                            $data . "\n\n" .
                            "--{$mime_boundary}--\n";
                           
                           $flashEmails = ClassRegistry::init('FlashEmail')->find('all', array('conditions' => array('client_id'=>$client_id),'fields'=>array('email')));
                           foreach($flashEmails as $clientEmail){
                               $email_to = $clientEmail['FlashEmail']['email'];
                              // $email_to = 'neema@revenue-performance.com';
                                if (mail($email_to, $email_subject, $email_message, $headers)) {
                                    //echo 'Mail Send <br/>';
                                } else {
                                   // echo 'Mail Not Send <br/>';
                                }
                           }
                           unlink($path);
                           //rmdir($file_path);
            return true;
        }
        //email_flash function ends here
        
        
        function client_daily_flash($date=null,$flashId=null){

            $client_id = $this->Auth->user('id');
            
            if($date == ''){
                $date = date('Y-m-d');
            }
            $this->set('client_id',$client_id);
            
            if(!empty($this->data)){
               $client_id = $this->data['DailyFlash']['client_id'];
               
               //Condition to remove the breakfast amount if included in Rooms revenue
                $total_breakfast_deduction = '0';
                $number_of_adult = $this->data['DailyFlash']['number_of_adults']; 
                $number_of_children = $this->data['DailyFlash']['number_of_childrens'];
                $adult_deduction = $number_of_adult * $this->data['DailyFlash']['deduction'];
                $child_deduction = $number_of_children * $this->data['DailyFlash']['child_deduction'];
                if($this->data['DailyFlash']['breakfast_included'] == '1'){
                    $total_breakfast_deduction = $adult_deduction + $child_deduction;
                    $this->data['DailyFlash']['revenue'] = $this->data['DailyFlash']['revenue'] - $total_breakfast_deduction;
                }
               
               $this->DailyFlash = ClassRegistry::init('DailyFlash');
                
                if ($this->DailyFlash->save($this->data)) {
                    //$flashId = $this->DailyFlash->getLastInsertId();
                    if(!empty($this->data['DailyFlash']['id'])){
                        $flashId = $this->data['DailyFlash']['id'];
                    }else{
                        $flashId = $this->DailyFlash->getLastInsertId();
                    }
                    
                     $this->FlashCash = ClassRegistry::init('FlashCash');
                    $this->FlashCash->deleteAll(array('FlashCash.daily_flash_id' => $flashId));
                    if(!empty ($this->data['FlashCash'])){
                        foreach($this->data['FlashCash'] as $cash_type => $cash_values){
                            foreach($cash_values as $cash_name => $values){
                                $flash_cash_data['FlashCash']['id'] = '';
                                $flash_cash_data['FlashCash']['daily_flash_id'] = $flashId;
                                $flash_cash_data['FlashCash']['cash_type'] = $cash_type;
                                $flash_cash_data['FlashCash']['name'] = $cash_name;
                                $flash_cash_data['FlashCash']['value'] = $values;
                                $this->FlashCash->save($flash_cash_data);
                            }   
                        }
                    }
                    
                    
                    $this->FlashFinance = ClassRegistry::init('FlashFinance');
                    $this->FlashFinance->deleteAll(array('FlashFinance.daily_flash_id' => $flashId));
                    $this->data['FlashFinance']['id'] = '';
                    $this->data['FlashFinance']['daily_flash_id'] = $flashId;
                    $this->FlashFinance->save($this->data);
                    
                    if($this->data['DailyFlash']['submit'] == 'Save Draft'){
                        $this->Session->setFlash(__('Draft Saved Successfully.', true));
                        $this->redirect(array('prefix' => 'client', 'client' => true, 'controller' => 'clients', 'action' => 'flash'));
                    }else{
                        $this->requestAction('/Clients/flash_verified/'.$client_id.'/'.$flashId.'/client');
                    }

                    
                    //$this->redirect(array('prefix' => 'client', 'client' => true, 'controller' => 'clients', 'action' => 'flash_report',$flashId));
                } else {
                        $this->Session->setFlash(__('Unable to generate Report. Please, try again.', true));
                }
            }else{  
                    $this->DailyFlash = ClassRegistry::init('DailyFlash');
                    $this->DailyFlash->recursive = 1;
                    if(!empty($flashId)){
                            $this->data = $this->DailyFlash->find('first', array('conditions' => array('DailyFlash.id'=>$flashId)));
                            $this->FlashFinance = ClassRegistry::init('FlashFinance');
                            $this->data['FlashFinance'] = $this->FlashFinance->find('first', array('conditions' => array('FlashFinance.daily_flash_id'=>$flashId)));
                            $this->FlashCash = ClassRegistry::init('FlashCash');
                            $this->data['FlashCash'] = $this->FlashCash->find('all', array('conditions' => array('FlashCash.daily_flash_id'=>$flashId),'fields'=>array('cash_type,name,value')));
                    }else{
                            $flashData = $this->DailyFlash->find('first', array('conditions' => array('DailyFlash.client_id'=>$client_id,'DailyFlash.date'=>$date),'fields'=>'id'));
                            if(!empty($flashData)){
                                $this->Session->setFlash(__('Report Already Filled for Selected date.', true));
                                $this->redirect(array('prefix' => 'client', 'client' => true, 'controller' => 'clients', 'action' => 'flash'));
                            }
                    }
                    
                    $this->set('date',$date);

//                    $this->AdvancedSheet = ClassRegistry::init('AdvancedSheet');
//                    $this->User = ClassRegistry::init('User');
//
//                    $users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));
//
//                    $this->Client->Department->recursive = -1;
//                    $condition = array('Department.client_id' => $client_id,'Department.name' => 'Rooms','status'=>'1');
//                    $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
//                    if(empty($dept_data)){
//                        $condition = array('Department.client_id' => $client_id,'Department.name LIKE' => 'Room%','status'=>'1');
//                        $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
//                    }
//
//                    $dept_ids = array ();
//                    unset($dept_ids);
//                    foreach($dept_data as $dept){
//                        $dept_ids[] = $dept['Department']['id'];
//                    }
            }
        }
        
        function client_flash_report($flashId=null){

            $client_id = $this->Auth->user('id');
            
            $this->set('clientId',$client_id);
            
            $this->DailyFlash = ClassRegistry::init('DailyFlash');
            $this->DailyFlash->recursive = 1;
            $flashData = $this->DailyFlash->find('first', array('conditions' => array('DailyFlash.id'=>$flashId)));
            $this->set('flashData',$flashData);

            $financeData = $this->requestAction('/Clients/get_flash_finances/'.$flashId);
            $this->set('financeData',$financeData);
            
            $cashData = $this->requestAction('/Clients/get_flash_cashes/'.$flashId);
            $this->set('cashData',$cashData);
            
            //Get Rooms Department ID
            $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
            
            $day = date('d',strtotime($flashData['DailyFlash']['date']));
            $month = date('m',strtotime($flashData['DailyFlash']['date']));
            $year = date('Y',strtotime($flashData['DailyFlash']['date']));
            
            //Get the market segments of Rooms department Segmentation sheet
            $this->User = ClassRegistry::init('User');
            $users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));
            
            $this->AdvancedSheet = ClassRegistry::init('AdvancedSheet');
            $this->AdvancedSheet->recursive = '-1';
            $conditions = array('AdvancedSheet.status !='=>'2','AdvancedSheet.department_id'=>$dept_ids,'AdvancedSheet.user_id'=>$users,'AdvancedSheet.month'=>$month,'AdvancedSheet.year'=>$year);
            $sheetData = $this->AdvancedSheet->find('all', array('conditions' => $conditions,'fields'=>  array('AdvancedSheet.market_segments,AdvancedSheet.id')));
            $sheet_segments = array();
            foreach($sheetData as $sheetSeg){
                $sheet_segments[] = explode(',',$sheetSeg['AdvancedSheet']['market_segments']);
            }
            $sheetSegment = call_user_func_array('array_merge', $sheet_segments);
            $sheetSegment = array_unique($sheetSegment);
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$sheetSegment)));
            $this->set('marketsegments',$marketsegments);
            $this->set('client_id',$client_id);

            //Get BOB and ADR values for each market segment
            $adr_segments = $this->AdvancedSheet->AdvanceData->find('list',array('conditions'=>array('AdvanceData.column_id'=>'64','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date'=>$day),'fields'=> array('AdvanceData.market_segment_id','AdvanceData.value'),'order'=>'AdvanceData.date ASC'));
            $bob_segments = $this->AdvancedSheet->AdvanceData->find('list',array('conditions'=>array('AdvanceData.column_id'=>'62','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date'=>$day),'fields'=> array('AdvanceData.market_segment_id','AdvanceData.value'),'order'=>'AdvanceData.date ASC'));
            $this->set('adr_segments',$adr_segments);
            $this->set('bob_segments',$bob_segments);            
            
            //Get Month-To-Date BOB and ADR values for each market segment
            $flash_date = strtotime($flashData['DailyFlash']['date']);
            $day = date('d',$flash_date);
            //$prev_day = $day - 1;
            $prev_day = $day;
            $month_adr_segments = $this->AdvancedSheet->AdvanceData->find('all',array('conditions'=>array('AdvanceData.column_id'=>'64','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date !='=>'Total','AdvanceData.date >='=>'1','AdvanceData.date <='=>$prev_day),'fields'=> array('AdvanceData.market_segment_id','sum(AdvanceData.value) as value'),'group'=>'AdvanceData.market_segment_id'));
            $month_bob_segments = $this->AdvancedSheet->AdvanceData->find('all',array('conditions'=>array('AdvanceData.column_id'=>'62','AdvanceData.advanced_sheet_id'=>$sheetData[0]['AdvancedSheet']['id'],'AdvanceData.date !='=>'Total','AdvanceData.date >='=>'1','AdvanceData.date <='=>$prev_day),'fields'=> array('AdvanceData.market_segment_id','sum(AdvanceData.value) as value'),'group'=>'AdvanceData.market_segment_id'));
            $month_adr_segments = Set::combine($month_adr_segments, '{n}.AdvanceData.market_segment_id', '{n}.0.value');
            $month_bob_segments = Set::combine($month_bob_segments, '{n}.AdvanceData.market_segment_id', '{n}.0.value');
            $this->set('month_adr_segments',$month_adr_segments);
            $this->set('month_bob_segments',$month_bob_segments);
            
            //Get Room Department sheet values
            App::import('Model','Sheet');
            $this->Sheet = new Sheet();
            $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            $this->set('sheet_id',$all_sheets[0]['Sheet']['id']);
            $sheetId = $all_sheets[0]['Sheet']['id'];
            $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$month, $year);
            //Fcst Rooms Total,Fcst Revenue,Fcst Revpar
            $columnIds = array ('63','69','70');
            $total_field_value = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columnIds,'Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=>array('Datum.column_id','sum(Datum.value) as value'),'group'=>array('Datum.column_id')));
            $total_field_value = Set::combine($total_field_value, '{n}.Datum.column_id', '{n}.0.value');
            
            
            //Get total values for Restaurant Department
            $condition = array('Department.client_id' => $client_id, 'Department.name LIKE' => 'Restaurant%', 'Department.status' => '1');
            $res_dept_ids = $this->Client->Department->find('list', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $all_res_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$res_dept_ids,'Sheet.year'=>$year,'Sheet.month'=>$month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
            $resSheetId = $all_res_sheets[0]['Sheet']['id'];
            //Covers,Ave Spend,Rev Fcst(revenue),RevPASH
            $columnResIds = array ('93','85','69','82');
            $total_restaurant = $this->Sheet->Datum->find('all',array('conditions'=>array('Datum.column_id'=>$columnResIds,'Datum.sheet_id'=>$resSheetId,'Datum.date !='=>'0','Datum.date >='=>'1','Datum.date <='=>$days_in_presnt_month),'fields'=>array('Datum.column_id','sum(Datum.value) as value'),'group'=>array('Datum.column_id')));
            $total_restaurant = Set::combine($total_restaurant, '{n}.Datum.column_id', '{n}.0.value');
            $this->set('total_restaurant',$total_restaurant);
            

            $this->set('total_field_value',$total_field_value);
            $flash_date = strtotime($flashData['DailyFlash']['date']);
             $startDate = date('d', strtotime("+1 day", $flash_date));
             //$endDate = date('d', strtotime("+7 day", $flash_date));
            $endDate = $startDate + '6';
            if((int)$days_in_presnt_month <= (int)$endDate){
                $endDate = $days_in_presnt_month;
            }
            
            $adr_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'64','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));
            $bob_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'62','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));
            $notes_value = $this->Sheet->Datum->find('list',array('conditions'=>array('Datum.column_id'=>'128','Datum.sheet_id'=>$sheetId,'Datum.date !='=>'0','Datum.date >='=>$startDate,'Datum.date <='=>$endDate),'fields'=> array('Datum.date','Datum.value'),'order'=>'Datum.date ASC'));
            
            $this->set('adr_value',$adr_value);
            $this->set('bob_value',$bob_value);
            $this->set('notes_value',$notes_value);
            
            //get month-to-date values for all flash table columns
            $month_start = date('Y-m-01', strtotime($flashData['DailyFlash']['date']));
            $monthToDateArr = $this->requestAction('/Clients/flash_month_to_date/'.$client_id.'/'.$month_start.'/'.$flashData['DailyFlash']['date']);
            $this->set('monthToDateArr',$monthToDateArr);
        }
        
        function client_flash(){
            $client_id = $this->Auth->user('id');
            $clientId = $client_id;
            
            $client_name = $this->Client->find('first',array('conditions'=>array('Client.id'=>$clientId),'fields'=>'Client.hotelname'));
            $hotelname = $client_name['Client']['hotelname'];
            $this->set('hotelname',$hotelname);
            $this->set('clientId',$clientId);
            
            $this->DailyFlash = ClassRegistry::init('DailyFlash');
            $unverifiedFlash = $this->DailyFlash->find('all', array('conditions' => array('DailyFlash.client_id'=>$clientId,'DailyFlash.is_verified'=>'0'),'fields'=>'id,date'));
            $this->set('unverifiedFlash',$unverifiedFlash);
            
            if(!empty ($this->data)){
                $date = $this->data['year'].'-'.sprintf("%02d",$this->data['month']).'-'.sprintf("%02d",$this->data['flash_date']);
                $this->DailyFlash = ClassRegistry::init('DailyFlash');
                $this->DailyFlash->recursive = 1;
                $flashData = $this->DailyFlash->find('first', array('conditions' => array('DailyFlash.client_id'=>$this->data['DailyFlash']['client_id'],'DailyFlash.date'=>$date),'fields'=>'id'));
                
                $client_id = $this->data['DailyFlash']['client_id'];
                
                if($this->data['DailyFlash']['new_input'] == '1'){
                        if(empty($flashData)){
                            $this->redirect(array('prefix' => 'client', 'client' => true, 'controller' => 'clients', 'action' => 'daily_flash',$date));
                        }else{
                            $this->Session->setFlash(__('Flash Data Already inputed for selected Date', true));
                            $this->redirect(array('prefix' => 'client', 'client' => true, 'controller' => 'clients', 'action' => 'flash'));
                        }
                }else{
                    if(!empty($flashData)){
                        $flash_id = $flashData['DailyFlash']['id'];
                        $this->redirect(array('prefix' => 'client', 'client' => true, 'controller' => 'clients', 'action' => 'flash_report',$flash_id));
                    }else{
                        $this->Session->setFlash(__('No Report Found', true));
                        $this->redirect(array('prefix' => 'client', 'client' => true, 'controller' => 'clients', 'action' => 'flash'));
                    }
                }
            }
        }
        
        function get_flash_finances($flash_id=null){
            
            $this->autoRender = false;
            $this->layout = false;
            
            $this->FlashFinance = ClassRegistry::init('FlashFinance');
            $this->FlashFinance->recursive = 1;
            $condition = array('FlashFinance.daily_flash_id' => $flash_id);
            $financeData = $this->FlashFinance->find('all', array('conditions' => $condition));
            //echo '<pre>'; print_r($financeData); exit;
            return $financeData[0];
        }
        
        function flash_month_to_date($client_id,$month_start,$flash_date){
            //Configure::write('debug',2);
                $this->autoRender = false;
                $this->layout = false;
                
                $this->DailyFlash = ClassRegistry::init('DailyFlash');
                $monthToDateArr = $this->DailyFlash->find('all',array(
                'conditions'=>array('DailyFlash.client_id'=>$client_id,'DailyFlash.date >='=>$month_start,'DailyFlash.date <='=>$flash_date),
                'fields'=>array(
                    'sum(DailyFlash.occupied) as occupied',
                    'sum(DailyFlash.revenue) as revenue',
                    'sum(DailyFlash.reservation) as reservation',
                    'sum(DailyFlash.room_night) as room_night',
                    'sum(DailyFlash.rooms_revenue_next) as rooms_revenue_next',
                    'sum(DailyFlash.reservation_next) as reservation_next',
                    'sum(DailyFlash.room_night_next) as room_night_next',
                    'sum(DailyFlash.rooms_revenue_future) as rooms_revenue_future',
                    'sum(DailyFlash.reservation_future) as reservation_future',
                    'sum(DailyFlash.room_night_future) as room_night_future',
                    'sum(DailyFlash.rooms_revenue) as rooms_revenue',
                    'sum(DailyFlash.covers) as covers',
                    'sum(DailyFlash.food_revenue) as food_revenue',
                    'sum(DailyFlash.bev_revenue) as bev_revenue',
                    'sum(DailyFlash.golf_people) as golf_people',
                    'sum(DailyFlash.golf_rev) as golf_rev',
                    'sum(DailyFlash.event_people) as event_people',
                    'sum(DailyFlash.event_rev) as event_rev',
                    'sum(DailyFlash.conference_people) as conference_people',
                    'sum(DailyFlash.conference_rev) as conference_rev',
                    'sum(DailyFlash.sports_people) as sports_people',
                    'sum(DailyFlash.sports_rev) as sports_rev',
                    'sum(DailyFlash.other_people) as other_people',
                    'sum(DailyFlash.other_rev) as other_rev'
                    )
                ));
            
                return $monthToDateArr;
        }
        
        function get_room_department($client_id){
            $this->autoRender = false;
            $this->layout = false;
            
            //$this->User = ClassRegistry::init('User');
            //$users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));

            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $client_id,'Department.name' => 'Rooms','status'=>'1');
            $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
            if(empty($dept_data)){
                $condition = array('Department.client_id' => $client_id,'Department.name LIKE' => 'Room%','status'=>'1');
                $dept_data = $this->Client->Department->find('all', array('conditions' => $condition,'fields'=>'id'));
            }
            $dept_ids = array ();
            unset($dept_ids);
            foreach($dept_data as $dept){
                $dept_ids[] = $dept['Department']['id'];
            }
            return $dept_ids;
            
        }
        
                
       public function admin_update_flash_emails($client_id = null){
            if(!empty($this->data)){
                $clientId = $this->data['FlashEmail']['client_id'];
                $this->FlashEmail = ClassRegistry::init('FlashEmail');
                if (isset($this->data['Client']['flash_email'])) {
                $this->FlashEmail->deleteAll(array('FlashEmail.client_id' => $clientId));
                $this->Client->id = $clientId;
                if ($this->Client->saveField('flash_email', $this->data['Client']['flash_email'])) {
                    if (isset($this->data['FlashEmail']) && !empty($this->data['FlashEmail'])) {
                        foreach ($this->data['FlashEmail'] as $key => $EmailSheet) {
                            if(!empty($EmailSheet['email'])){
                            $this->data['FlashEmail']['id'] = '';
                            $this->data['FlashEmail']['client_id'] = $clientId;
                            $this->data['FlashEmail']['email'] = $EmailSheet['email'];
                            ClassRegistry::init('FlashEmail')->save($this->data['FlashEmail']);
                            }
                        }
                    }
                    $this->Session->setFlash(__('Flash Email updated successfully', true));
                    $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'edit', $clientId));
                } else {
                    $this->Session->setFlash(__('Flash Email Unable to Update', true));
                }
            }
            }
        }
        
        function palheiro_progress(){
            //Configure::write('debug',2);
            $this->layout = '';
            $parent_client_id = '153';
            $child_data = $this->Client->find('all',array('conditions'=>array('Client.parent_id'=>$parent_client_id,'Client.status'=>1),'fields'=>'hotelname,id','recursive'=>'0'));
            $this->set('child_data',$child_data);
            
            $revenue_data = array();
            
            foreach($child_data as $hotels){
                if($hotels['Client']['id'] != ''){
                    $dept_ids = $this->requestAction('/Clients/get_room_department/'.$hotels['Client']['id']);
                    $this->Sheet = ClassRegistry::init('Sheet');
                    $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.department_id' => $dept_ids, 'Sheet.year' => date('Y'), 'Sheet.month' => date('m')), 'fields' => array('Sheet.id'), 'order' => 'Sheet.modified DESC', 'recursive' => '0'));
                    $this->set('sheet_id', $all_sheets[0]['Sheet']['id']);
                    $sheetId = $all_sheets[0]['Sheet']['id'];
                    $sheet_value = $this->Sheet->getData($sheetId);
                    
                    $day = '0'; $fcst = '0'; $budget = '0'; $ly = '0';
                    $trevpar_day = '0'; $trevpar_fcst = '0'; $trevpar_budget = '0'; $trevpar_ly = '0';
                    foreach($sheet_value as $sheetData){
                        $date = date('d').'/'.date('m').'/'.date('y');
                        if($sheetData['Date'] == $date){
                            $day = $sheetData['RevPAR'];
                        }elseif($sheetData['Date'] == 'Total'){
                            $fcst = $sheetData['RevPAR'];
                        }elseif($sheetData['Date'] == 'Budget'){
                            $budget = $sheetData['RevPAR'];
                        }elseif($sheetData['Date'] == 'LY Actual'){
                            $ly = $sheetData['RevPAR'];
                        }
                    }
                    
                    $trevpar_day += $day;
                    $trevpar_fcst += $fcst;
                    $trevpar_budget += $budget;
                    $trevpar_ly += $ly;
                    
                    $revenue_data[$hotels['Client']['id']]['Day'] = $day;
                    $revenue_data[$hotels['Client']['id']]['Fcst'] = $fcst;
                    $revenue_data[$hotels['Client']['id']]['Budget'] = $budget;
                    $revenue_data[$hotels['Client']['id']]['LY'] = $ly;
                }else{
                    $trevpar_day += '0';
                    $trevpar_fcst += '0';
                    $trevpar_budget += '0';
                    $trevpar_ly += '0';
                    $revenue_data[$hotels['Client']['id']]['Day'] = '';
                    $revenue_data[$hotels['Client']['id']]['Fcst'] = '';
                    $revenue_data[$hotels['Client']['id']]['Budget'] = '';
                    $revenue_data[$hotels['Client']['id']]['LY'] = '';
                }
            }
            
            $revenue_data['TrevPAR']['Day'] = $trevpar_day;
            $revenue_data['TrevPAR']['Fcst'] = $trevpar_fcst;
            $revenue_data['TrevPAR']['Budget'] = $trevpar_budget;
            $revenue_data['TrevPAR']['LY'] = $trevpar_ly;
            
            $this->set('revenue_data',$revenue_data);
            //echo '<pre>'; print_r($child_data); exit;
        }

}//end class
