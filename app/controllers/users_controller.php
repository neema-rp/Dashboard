<?php
class UsersController extends AppController {

	/**
	 * Class property that stores the controller name
	 */
	var $name = 'Users';
	var $components = array('RequestHandler', 'Sendemail','Session','Email','Cookie');
	
	/**
	 * Index function for the Users controller
	 *
	 * @return void
	 */
        function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('forget_password');
		$this->Auth->allow('getDepartmentList');
                $this->Auth->allow('autologinUser');
                $this->Auth->allow('autologinById');
                
// $this->layout = "innerpages";
	}
	function index() {
	      $this->redirect("/staff/sheets/index");
	}//end index()


	/**
	 * Login action for Users
	 *
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
	
	function client_login() {

                $this->redirect("/staff/users/alllogin");
            
                $this->layout = 'login_layout';

		// Display error message on failed authentication
		if (!empty($this->data) && !$this->Auth->_loggedIn) {
			$this->Session->setFlash($this->Auth->loginError);			
		}
		// Redirect the logged in user to respective pages
		$this->setLoginRedirects();
	}//end login()
        
        
//Function added on 9 July'2014 for common login for users and Assigned users
        function staff_alllogin() {
            
            $this->setLoginRedirects();
            
                $this->layout = 'login_layout';
		// Display error message on failed authentication
		if (!empty($this->data) && !$this->Auth->_loggedIn) {
			$this->Session->setFlash($this->Auth->loginError);
                        $this->redirect("http://" . $_SERVER['HTTP_HOST']);
		}else{
                    $this->redirect("http://" . $_SERVER['HTTP_HOST']);
                }
		// Redirect the logged in user to respective pages
		
	}//end staff_alllogin()

	/**
	 * Logout action for Users
	 */
	function logout() {
            
                $activityId = $this->Session->read('activityId');
		if(!empty($activityId))
		{
                        $this->Activity = ClassRegistry::init('Activity');
			$this->Activity->updateActivityIsLogout($activityId);
		}
            
		$this->Session->setFlash(__('You are successfully Logged out.', true));
		$this->redirect($this->Auth->logout());
	}//end logout()


	/**
	 * Forget password action for Users
	 *
	 * @return void()
	 */
	
	
   		
        
   	function forget_password()
	{
		if(!empty($this->data))
		{
			$val = $this->data['User']['email'];
			$condition['conditions'] = array("User.email" => $val, "User.status !=" =>2 );
			$condition['recursive']= -1;
			$condition['limit']= 1;

		# getting userdata
			$datalist = $this->User->find('first', $condition);
			//debug($datalist);
		//	exit;
		if (isset($datalist) && !empty($datalist['User']['email']))
		{
			if($datalist['User']['status'] == 2)
			{
				$this->Session->setFlash(__('<div class="errorCommnt" id="server_message">The account is Blocked. Please contact Administrator !</div>',true));
				$this->redirect(array('controller' => 'users','action' => 'forget_password'));
			}
			if($datalist['User']['status'] == 0)
			{
				$this->data['User']['email'] = '';
				$this->Session->setFlash(__('<div class="errorCommnt" id="server_message">Email address does not exist !</div>',true));
				$this->redirect(array('controller' => 'users','action' => 'forget_password'));
			} else {
				//$this->randomnumber();
				$uniqueid = substr(rand(),0,5);
				$this->data['User']['password'] = $this->Auth->password($uniqueid);
				$this->data['User']['id'] = $datalist['User']['id'];
				$result = $this->User->save($this->data);
				if($result)
				{
					$this->data['User']['username'] = $datalist['User']['username'];
					$this->data['User']['password'] = $uniqueid;
					
					$getuserinfo = $this->data['User'];
					$to = $datalist['User']['email'];
					$username = $datalist['User']['username'];
					$addcc = $this->User->Field('email', array('id' =>'1'));
					# mail Section
					
					$result = $this->Sendemail->userforgotpassword($to, $addcc, $getuserinfo);
					if($result)
					{
					$this->data['User']['email'] = '';
					$this->Session->setFlash(__('<div class="successCommnt" id="server_message">Your password has been sent to your email.</div>',true));
					$this->redirect(array('controller' => 'users','action' => 'login'));
					}
				}
			}
		} else {
			$this->data['User']['email'] = '';
			$this->Session->setFlash(__('<div class="errorCommnt" id="server_message">Email address does not exist !</div>',true));
			$this->redirect(array('controller' => 'users','action' => 'forget_password'));
		}
		}
	}
  

	function profile()
	{
		// Find the logged-in user details
		$userId = $this->Auth->user('id');
		$user   = $this->User->findById($userId);

		// Set the client information to display in the view
		$this->set(compact('user'));
	}//end profile()


	/**
	 * action to edit the user profile
	 * 
	 * @access public
	 * @return void
	 */
	function edit()
	{
		// Find the logged-in client details
		$userId = $this->Auth->user('id');
		$user   = $this->User->findById($userId);

		// Save the POSTed data
		if (!empty($this->data)) {
			$this->data['User']['id'] = $userId;
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('Profile has been updated', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Profile could not be saved. Please, try again.', true));
			}
		} else {
			// Set the form data (display prefilled data) 
			$this->data = $user;
		}

		// Set the view variable
		$this->set(compact('user'));
	}//end profile()


	/**
	 * Admin action to list the users
	 * 
	 * @access public
	 * @return void
	 */
	function admin_index_back2Feb2016() {
			
                if($this->Session->read('Auth.Admin.IsSubAdmin')){
                    $subadmin_id = $this->Session->read('Auth.Admin.SubAdminID');
                    $this->Subadmin = ClassRegistry::init('Subadmin');
                    $subadmin_client = $this->Subadmin->read(null, $subadmin_id);
                    $allclients = array();
                    foreach($subadmin_client['SubadminClient'] as $clients)
                    {
                            array_push($allclients,$clients['client_id']);
                    }
                
                    if ($this->data['User']['value']) {
                            $search = trim($this->data['User']['value']);
                            $this->set('search',$search);
                            $conditions = array(
                                          'OR'  => array('User.username LIKE' => "%$search%", 'User.firstname LIKE' => "%$search%", 'User.lastname LIKE' => "%$search%"), 
                                          'AND' => array('User.status !=' => 2,'User.client_id'=>$allclients)
                                    );
                    } else {
                            $conditions = array('User.status !='=>2,'User.client_id'=>$allclients);
                    }
                
                }else{
                    if ($this->data['User']['value']) {
                            $search = trim($this->data['User']['value']);
                            $this->set('search',$search);
                            $conditions = array(
                                          'OR'  => array('User.username LIKE' => "%$search%", 'User.firstname LIKE' => "%$search%", 'User.lastname LIKE' => "%$search%"), 
                                          'AND' => array('User.status !=' => 2)
                                    );
                    } else {
                            $conditions = array('User.status !='=>2);
                    }
                }
		
		$allusers = $this->User->find('list',array('fields'=>array('User.id'), 'conditions'=>$conditions));
		$this->User->unbindModel(array('hasOne'=>array('Sheet')));

// 		$all_users = $this->User->find('all',array('conditions'=>array('User.status !='=>2)));
// print_r($conditions);
		$all_users = $this->User->find('all',array('conditions'=>$conditions));

		//$this->User->recursive = 0;
		$this->paginate['conditions'] = array('DepartmentsUser.user_id'=>$allusers);
		$this->paginate['order'] = array('DepartmentsUser.department_id'=>'DESC');
		$this->paginate['limit'] = 20;

		$deptusers = $this->paginate('DepartmentsUser');
		for($i=0;$i<count($deptusers); $i++)
		{
		  foreach($all_users as $user){
		      if($deptusers[$i]['DepartmentsUser']['user_id'] == $user['User']['id']){
			  $deptusers[$i]['User'] = $user['User'];
			  $deptusers[$i]['Client'] = $user['Client'];
		      }
		  }
		}

		$this->set('users',$deptusers);
		
	}//end admin_index()


        
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
                
                    if ($this->data['User']['value']) {
                            $search = trim($this->data['User']['value']);
                            $this->set('search',$search);
                            $conditions = array(
                                          'OR'  => array('User.username LIKE' => "%$search%", 'User.firstname LIKE' => "%$search%", 'User.lastname LIKE' => "%$search%"), 
                                          'AND' => array('User.status !=' => 2,'User.client_id'=>$allclients,'Client.status !='=>2)
                                    );
                    } else {
                            $conditions = array('User.status !='=>2,'User.client_id'=>$allclients,'Client.status !='=>2);
                    }
                
                }else{
                    if ($this->data['User']['value']) {
                            $search = trim($this->data['User']['value']);
                            $this->set('search',$search);
                            $conditions = array(
                                          'OR'  => array('User.username LIKE' => "%$search%", 'User.firstname LIKE' => "%$search%", 'User.lastname LIKE' => "%$search%"), 
                                          'AND' => array('User.status !=' => 2,'Client.status !='=>2)
                                    );
                    } else {
                            $conditions = array('User.status !='=>2,'Client.status !='=>2);
                    }
                }

                //$conditions = array('Client.status !='=>2);
                $allusers = $this->User->find("list", array(
                                "joins" => array(
                                    array(
                                        "table" => "clients",
                                        "alias" => "Client",
                                        "type" => "LEFT",
                                        "conditions" => array(
                                            "User.client_id = Client.id"
                                        )
                                    )
                                ),
                               'fields'=>array('User.id'),
                               'conditions' => $conditions
                    ));

		//$allusers = $this->User->find('list',array('fields'=>array('User.id'), 'conditions'=>$conditions));
		$this->User->unbindModel(array('hasOne'=>array('Sheet')));

		$all_users = $this->User->find('all',array('conditions'=>$conditions));

		//$this->User->recursive = 0;
		$this->paginate['conditions'] = array('DepartmentsUser.user_id'=>$allusers);
		$this->paginate['order'] = array('DepartmentsUser.department_id'=>'DESC');
		$this->paginate['limit'] = 20;

		$deptusers = $this->paginate('DepartmentsUser');
		for($i=0;$i<count($deptusers); $i++)
		{
		  foreach($all_users as $user){
		      if($deptusers[$i]['DepartmentsUser']['user_id'] == $user['User']['id']){
			  $deptusers[$i]['User'] = $user['User'];
			  $deptusers[$i]['Client'] = $user['Client'];
		      }
		  }
		}
		$this->set('users',$deptusers);
		
	}
        
	/**
	 * Admin action to view the user details
	 * 
	 * @param Integer $id ID of the user to be viewed
	 * @access public
	 * @return void
	 */
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}//end admin_view()



	/**
	 * Action for admin to add a user
	 * 
	 * @param int $clientId The client ID
	 * @access public
	 * @return void
	 */
	function admin_add($clientId = null) {
// 		App::import('Model','DepartmentUser');
		if (!empty($this->data)) {
		
		$client_id = $this->data['User']['client_id'];		
		$aDepartments = array();
		if(!empty($this->data['department_name'])){
		    $cDepts = $this->data['department_name'];		    
		    $this->data['User']['department_name'] = implode(",", $cDepts);
		}
		$this->User->create();

			if ($this->User->save($this->data)) {
				$user_id = $this->User->id;
				$dept_obj = ClassRegistry::init('DepartmentsUser');
				$find_dept = ClassRegistry::init('Department');
				if(!empty($this->data['department_name'])){
				    foreach($this->data['department_name'] as $dept_id)
				    {  
				      $dept_data['user_id'] = $user_id;
				      $dept_data['department_id'] = $dept_id;
				      $dept_name_data = $find_dept->find('first',array('fields'=>'name','conditions'=>array('Department.id'=>$dept_id)));
				      
				      $dept_data['department_name'] = $dept_name_data['Department']['name'];
				      $dept_obj->saveAll($dept_data);


				      //we have to find out department sheets
				      $sheet_obj = ClassRegistry::init('Sheet');
				      $sheet_obj->recursive = -1;
				      $sheet_conditions = array('Sheet.department_id' => $dept_id, 'Sheet.status !=' => 2);
				      $sheet_array = $sheet_obj->find('all', array('conditions'=>array('Sheet.department_id' => $dept_id, 'Sheet.status !=' => 2)));

				      if(!empty($sheet_array)){
					  foreach($sheet_array as $sheetupdate){
					      //assign users to those sheets
					      $arraytoupdate = $sheetupdate;
					      $arraytoupdate['Sheet']['user_id'] = $arraytoupdate['Sheet']['user_id'].", ".$user_id;
					      $sheet_obj->save($arraytoupdate);
					      $arraytoupdate = null;
					  }
				      }
				    }
				}

				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->data['User']['password'] = '';
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		$this->User->Client->recursive = -1;
		$clientArray = $this->User->Client->find('all',array('conditions'=>array('Client.status !='=>2)));

		$clients = array();
		foreach($clientArray as $client){
			$clients[$client['Client']['id']] = $client['Client']['hotelname'];
		}

		asort($clients);

		$departments = array();
		$departments[0] = 'Select Department';
	  
		$this->set('departments', $departments);

		$this->set(compact('clients', 'clientId'));
		
	}//end admin_add()

	/**
	 * Action for admin to add a user
	 * 
	 * @param int $clientId The client ID
	 * @access public
	 * @return void
	 */
	  function getDepartmentList($client_id=null) {
		  $this->layout = false;
		  //$this->autoRender = false;
		  $departments = array();
		 
		  if($client_id != null){
			  $client = $this->User->Client->Department->find('all',array('conditions'=>array('Client.id'=>$client_id,'Department.status'=>1)));
			   foreach($client as $cli)
				  {
				    $departments[$cli['Department']['id']] = $cli['Department']['name'];				      
				  }
		  }
		  $this->set('departments', $departments);
		 
		  //echo $this->Form->input('department_name', array('options' => $departments));
	  }


	/**
	 * Action for admin to add a user
	 * 
	 * @param int $clientId The client ID
	 * @access public
	 * @return void
	 */
	function client_add($clientId = null) {
		App::import('Model','Department');
		$this->Department = new Department();
		if (!empty($this->data)) {
			$aDepartments = array();
			if(!empty($this->data['User']['department_name'])){
			    $cDepts = $this->data['User']['department_name'];		    
			    $this->data['User']['department_name'] = implode(",", $cDepts);
			}

			$this->data['User']['client_id'] = $this->Auth->user('id');
			$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
			$this->User->create();
			if ($this->User->save($this->data)) {

				$user_id = $this->User->id;
				$dept_obj = ClassRegistry::init('DepartmentsUser');
				$find_dept = ClassRegistry::init('Department');
				if(!empty($cDepts)){
				    foreach($cDepts as $dept_id)
				    {  
				      $dept_data['user_id'] = $user_id;
				      $dept_data['department_id'] = $dept_id;
				      $dept_name_data = $find_dept->find('first',array('fields'=>'name','conditions'=>array('Department.id'=>$dept_id)));
				      
				      $dept_data['department_name'] = $dept_name_data['Department']['name'];
				      $dept_obj->saveAll($dept_data);

				      //we have to find out department sheets
				      $sheet_obj = ClassRegistry::init('Sheet');
				      $sheet_obj->recursive = -1;
				      $sheet_conditions = array('Sheet.department_id' => $dept_id, 'Sheet.status !=' => 2);
				      $sheet_array = $sheet_obj->find('all', array('conditions'=>array('Sheet.department_id' => $dept_id, 'Sheet.status !=' => 2)));

				      if(!empty($sheet_array)){
					  foreach($sheet_array as $sheetupdate){
					      //assign users to those sheets
					      $arraytoupdate = $sheetupdate;
					      $arraytoupdate['Sheet']['user_id'] = $arraytoupdate['Sheet']['user_id'].", ".$user_id;
					      $sheet_obj->save($arraytoupdate);
					      $arraytoupdate = null;
					  }
				      }
				    }
				}

				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->data['User']['password'] = '';
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		//$clients = $this->User->Client->find('list');
		$clientId = $this->Auth->user('id'); 	
		
		$clientArray = $this->User->getChildHotelsArray($clientId);
		$clientStr = implode(',', $clientArray);
		
		$condition = "Department.status = 1 AND Department.client_id IN ($clientStr)";
		$departments1 = $this->Department->find('all', array('fields'=> 'Department.id, Department.name, Client.hotelname', 'conditions'=>$condition));
		$departments = array();
		foreach($departments1 as $dept){
			$departments[$dept['Department']['id']] = $dept['Department']['name']." - ".$dept['Client']['hotelname'];
		}	  
		$this->set('departments', $departments);

		$this->set("clientId", $clientId);
	}//end client_add()


	/**
	 * Action for admin to edit a user
	 * 
	 * @param integer $id The user ID
	 * @access public
	 * @return void
	 */
	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {

		    $users_data['User'] = $this->data['User'];
		    
		    $user_id = $this->data['User']['id'];
		    $dept_obj = ClassRegistry::init('DepartmentsUser');
		   
		    $find_dept = ClassRegistry::init('Department');

		    /*check for unique username and email*/
		    $prev_info = $this->User->findById($users_data['User']['id']);
		    $prev_username = $prev_info['User']['username'];
		    $prev_email = $prev_info['User']['email'];

		    if($users_data['User']['username'] == $prev_username){
			unset($users_data['User']['username']);  
		    }
		    if($users_data['User']['email'] == $prev_email){
			unset($users_data['User']['email']);
		    }
		 
			if ($this->User->save($users_data)) {  
				    if(!empty($this->data['DepartmentsUser']['department_name'])){
					  $dept_obj->deleteAll(array('DepartmentsUser.user_id'=>$user_id));
					  foreach($this->data['DepartmentsUser']['department_name'] as $dept_id)
					  {  
					    $dept_data['user_id'] = $user_id;
					    $dept_data['department_id'] = $dept_id;
					    $dept_name_data = $find_dept->find('first',array('fields'=>'name','conditions'=>array('Department.id'=>$dept_id)));
					    
					    $dept_data['department_name'] = $dept_name_data['Department']['name'];
					    $dept_obj->saveAll($dept_data);
					  }
					}

				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));

			}
		}
		if (empty($this->data) || isset($this->data['User']['password'])) {
			$this->data = $this->User->read(null, $id);

			$depts = array();
			foreach($this->data['DepartmentsUser'] as $dept)
			{
				array_push($depts,$dept['department_id']);
			}
			$this->set('depts',$depts);
			$client_id = $this->data['User']['client_id'];
			$client_data = $this->User->Client->findById($client_id);

			$total_depts = array();
			foreach($client_data['Department'] as $depts){
			  $total_depts[$depts['id']] = $depts['name'];
			}
			$this->set('total_depts',$total_depts);

		}
			$for_depts = $this->User->read(null, $id);
			$depts = array();
			foreach($for_depts['DepartmentsUser'] as $dept)
			{
				array_push($depts,$dept['department_id']);
			}
			$this->set('depts',$depts);
			$client_id = $for_depts['User']['client_id'];
			$client_data = $this->User->Client->findById($client_id);

			$total_depts = array();
			foreach($client_data['Department'] as $depts){
			  $total_depts[$depts['id']] = $depts['name'];
			}
			$this->set('total_depts',$total_depts);

		$clients = $this->User->Client->find('list');

		$this->set(compact('clients'));
	}//end admin_edit()


	/**
	 * Admin action to logically delete a user
	 * 
	 * @param integer ID of the user to be deleted
	 * @access public
	 * @return void
	 */
	function admin_delete($id = null, $dept=null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		$dept_obj = ClassRegistry::init('DepartmentsUser');
		$all_depts = $dept_obj->find('all',array('conditions'=>array('DepartmentsUser.user_id'=>$id)));

		$this->User->id=$id;

		if($this->User->saveField('status',2)){
			if(!empty($all_depts)){
			  foreach($all_depts as $depts){
				      $dept_obj->delete($depts['DepartmentsUser']['id']);
			  }
			}
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}//end admin_delete()


	/**
	 * Action for client to list the users
	 * 
	 * @access public
	 * @return void
	 */
	function client_index($clientId=null)
	{
		if($clientId == ''){
			// Get the logged-in client ID
			$clientId = $this->Auth->user('id');
			$this->set('chain_user','0');
		}else{
			$this->set('chain_user','1');
		}

		$clientArray = $this->User->getChildHotelsArray($clientId);
		$clientStr = implode(',', $clientArray);
		
		if ($this->data['User']['value']) {
			//debug($this->data);
			$search = trim($this->data['User']['value']);

			$this->set('search',$search);

			$condition = "((User.username LIKE '%$search%') OR (User.firstname LIKE '%$search%') OR (User.lastname LIKE '%$search%')) AND (User.client_id IN ($clientStr) and User.status='1')";
			$conditions = array(
							'OR'  => array('User.username LIKE' => "%$search%", 'User.firstname LIKE' => "%$search%", 'User.lastname LIKE' => "%$search%"), 
							'AND' => array('User.client_id IN' => $clientId)
						  );
		} else {
			$condition = "User.client_id IN ($clientStr) and User.status='1'";
			$conditions = array('User.client_id IN' => $clientId);
		}
		// Find the paginated list of users
		$this->User->recursive = -1;
                $this->paginate['conditions'] = $condition;
                $users = $this->paginate();
                
                $child_data = $this->User->Client->find('list',
                        array('conditions'=>
                            array('OR'=>array('Client.parent_id'=>$clientId,'Client.id'=>$clientId),'Client.status'=>1)
                        ,'fields'=>'id,hotelname','recursive'=>'0'));

	      if(!empty($users)){
		$dep_user_obj = ClassRegistry::init('DepartmentsUser');
		for($i=0;$i<count($users);$i++){
		      $dept_data = $dep_user_obj->find('all',array('fields'=>'DepartmentsUser.department_name','conditions'=>array('DepartmentsUser.user_id'=>$users[$i]['User']['id'])));
		      foreach($dept_data as $dept){
			  $users[$i]['Department'][] = $dept['DepartmentsUser']['department_name'];
		    }		  
		}
	      }

		$this->set(compact('users','child_data'));
	}//end client_index()


	/**
	 * Client action to view the user details
	 * 
	 * @param Integer $id ID of the user to be viewed
	 * @access public
	 * @return void
	 */
	function client_view($id = null) {
		$this->admin_view($id);
	}//end client_view()

	function client_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}//end client_delete()


	function client_edit($depatmentId = null, $client_id = null) {
		App::import('Model','Department');
		$this->Department = new Department();
		if (!$depatmentId && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Department', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
		    $users_data['User'] = $this->data['User'];
		    
		    $user_id = $this->data['User']['id'];
		    $dept_obj = ClassRegistry::init('DepartmentsUser');
		   
		    $find_dept = ClassRegistry::init('Department');
		if(!empty($this->data['DepartmentsUser']['department_name'])){
		    $dept_obj->deleteAll(array('DepartmentsUser.user_id'=>$user_id));
		    foreach($this->data['DepartmentsUser']['department_name'] as $dept_id)
		    {  
		      $dept_data['user_id'] = $user_id;
		      $dept_data['department_id'] = $dept_id;
		      $dept_name_data = $find_dept->find('first',array('fields'=>'name','conditions'=>array('Department.id'=>$dept_id)));
		      
		      $dept_data['department_name'] = $dept_name_data['Department']['name'];
		      $dept_obj->saveAll($dept_data);
		    }
		  }
		  if(isset($users_data['User']['password']) && !empty($users_data['User']['password'])){
			    if($users_data['User']['password'] == $users_data['User']['confirm_password']){
				$hashed_pass = $this->Auth->password($users_data['User']['password']);
				$users_data['User']['password'] = $hashed_pass;
			    }
		      }		  
			if ($this->User->save($users_data)) {
				$this->Session->setFlash(__('The department has been updated', true));
				$this->redirect(array('action' => 'index', $client_id));
			} else {
				$this->Session->setFlash(__('The department could not be updated. Please, try again.', true));
			}
		}
		
			$this->data = $this->User->read(null, $depatmentId);
			$this->data['User']['password'] = '';
			$depts = array();

			foreach($this->data['DepartmentsUser'] as $dept)
			{
				array_push($depts,$dept['department_id']);
			}

			$this->set('depts',$depts);
		
		$clientId = $this->Auth->user('id'); 	
		
		$clientArray = $this->User->getChildHotelsArray($clientId);
		$clientStr = implode(',', $clientArray);
		
		$condition = "Department.status = 1 AND Department.client_id IN ($clientStr)";

		$departments1 = $this->Department->find('all', array('fields'=> 'Department.id, Department.name, Client.hotelname', 'conditions'=>$condition));

		$departments = array();
		foreach($departments1 as $dept){
			$departments[$dept['Department']['id']] = $dept['Department']['name']." - ".$dept['Client']['hotelname'];
		}
	  
		$this->set('departments', $departments);
		$this->set('depatmentId', $depatmentId);
		$this->set('client_id', $client_id);
	}//end admin_edit()

	function password($user,$password) {

		if ($user === false) {
		debug(__METHOD__." failed to retrieve User data for user.id: {$user['User']['id']}");
		return false;
	      }
		$this->set('user', $user);
		$this->set('password', $password);
		//print_r($user['User']['email']);exit;		
		$this->Email->to = $user['User']['email'];
		$this->Email->bcc = array('Your-Domain Accounts <accounts@your-domain.com>');
		$this->Email->subject = 'Password Change Request';
		$this->Email->from = 'noreply@your-domain.com';
		$this->Email->template = 'users_'.$this->action;
		$this->Email->sendAs = 'both'; // you probably want to use both
		$this->Cookie->write('Referer', $this->referer(), true, '+2 weeks');
		$this->Session->setFlash('A new password has been sent to your supplied email address.');
		return $this->Email->send();
	}
        
        //////////////////////////////
        function staff_daily_flash($date=null,$flashId=null){

            $client_id = $this->Auth->user('client_id');
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
                    $this->data['FlashFinance']['id'] = '';
                    $this->data['FlashFinance']['daily_flash_id'] = $flashId;
                    $this->FlashFinance->save($this->data);

                    $this->redirect(array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'flash'));
                    //$this->redirect(array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'flash_report',$flashId));
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
                                $this->redirect(array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'flash'));
                            }
                    }
                    
                    $this->set('date',$date);

                    $this->AdvancedSheet = ClassRegistry::init('AdvancedSheet');
                    $this->User = ClassRegistry::init('User');

                    $users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));

                    $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
            }
        }
        
        function staff_flash_report($flashId=null){

            //Configure::write('debug',2);
            
            $client_id = $this->Auth->user('client_id');
            
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
            $this->set('total_field_value',$total_field_value);
            
            
            //Get total values for Restaurant Department
            $condition = array('Department.client_id' => $client_id, 'Department.name LIKE' => 'Restaurant%', 'Department.status' => '1');
            $res_dept_ids = $this->User->Client->Department->find('list', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
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
            //$monthToDateArr = flash_month_to_date($client_id,$month_start,$flashData['DailyFlash']['date']);
            $monthToDateArr = $this->requestAction('/Clients/flash_month_to_date/'.$client_id.'/'.$month_start.'/'.$flashData['DailyFlash']['date']);
            $this->set('monthToDateArr',$monthToDateArr);
        }
        
        function staff_flash(){
            
            $client_id = $this->Auth->user('client_id');
            $clientId = $client_id;
            
            $this->Client = ClassRegistry::init('Client');
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
                
                if($this->data['DailyFlash']['new_input'] == '1'){
                        if(empty($flashData)){
                            $this->redirect(array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'daily_flash',$date));
                        }else{
                            $this->Session->setFlash(__('Flash Data Already inputed for selected Date', true));
                            $this->redirect(array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'flash'));
                        }
                }else{
                    if(!empty($flashData)){
                        $flash_id = $flashData['DailyFlash']['id'];
                        $this->redirect(array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'flash_report' ,$flash_id));
                    }else{
                        $this->Session->setFlash(__('No Report Found', true));
                        $this->redirect(array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'flash'));
                    }
                }
            }
        }
    
     public function autologinById($usertype=null,$uid=null){
         $id = base64_decode($uid);
         
        if($usertype == 'Admin'){
            $cl_details = Classregistry::init('Admin')->findById($id);
            $this->Session->write('Auth.Admin',$cl_details['Admin']);
            $this->redirect(array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'index'));
        }else if($usertype == 'Subadmin'){
            $cl_details = Classregistry::init('Subadmin')->findById($id);
            $cl_details['Admin']['IsSubAdmin'] = '1';
            $this->Session->write('Auth.Admin',$cl_details['Subadmin']);
            $this->redirect(array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'index'));
        }elseif($usertype == 'User'){
            $cl_details = Classregistry::init('User')->findById($id);
            $this->Session->write('Auth.User',$cl_details['User']);                                        
            $this->redirect(array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'index'));
        }elseif($usertype == 'Hotel'){
            $cl_details = Classregistry::init('Client')->findById($id);
            $this->Session->write('Auth.Client',$cl_details['Client']);                                        
            $this->redirect(array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'index'));   
        }
    }   
        
    public function autologinUser($usertype=null,$username=null){
        //$this->redirect(array('action' => 'autologinById','Admin','1'));
        //Configure::write('debug',2);
        
        if($usertype == 'Admin'){
            $cl_details = Classregistry::init('Admin')->findByUsername($username);
            $this->Session->write('Auth.Admin',$cl_details['Admin']);
             $this->Session->write('error','');
            //echo '<pre>'; print_r($this->Session->read('Auth'));
            
            // echo '<pre>'; print_r($this->Session); exit;
             
            //echo 'login with session';
            //exit;
            //$this->setLoginRedirects();
            $this->redirect(array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'index'));
        }else if($usertype == 'Subadmin'){
            $cl_details = Classregistry::init('Admin')->findByUsername($username);
            $cl_details['Admin']['IsSubAdmin'] = '1';
            $this->Session->write('Auth.Admin',$cl_details['Admin']);
            $this->redirect(array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'index'));
        }elseif($usertype == 'User'){
            $cl_details = Classregistry::init('User')->findByUsername($username);
            $this->Session->write('Auth.User',$cl_details['User']);                                        
            $this->redirect(array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'index'));
        }elseif($usertype == 'Hotel'){
            $cl_details = Classregistry::init('Client')->findByUsername($username);
            $this->Session->write('Auth.Client',$cl_details['Client']);                                        
            $this->redirect(array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'index'));   
        }
    }   

}//end class