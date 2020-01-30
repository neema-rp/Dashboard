<?php
class DepartmentsController extends AppController {

	/**
	 * Class property that stores the controller name
	 */
	var $name = 'Departments';

	/**
	 * Index function for the Users controller
	 *
	 * @return void
	 */
	function index() {
	      $this->redirect("/staff/sheets/index");
	}//end index()


	/**
	 * Login action for Users
	 *
	 * @return void()
	 */
	function login() {
		// Display error message on failed authentication
		if (!empty($this->data) && !$this->Auth->_loggedIn) {
			$this->Session->setFlash($this->Auth->loginError);
		}

		// Redirect the logged in user to respective pages
		$this->setLoginRedirects();
	}//end login()


	/**
	 * Logout action for Users
	 */
	function logout() {
		$this->redirect($this->Auth->logout());
	}//end logout()


	/**
	 * action to view edit the client profile
	 * 
	 * @access public
	 * @return void
	 */
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
	function admin_index($client_id=null) 
		{
		if ($this->data['Department']['value']) {
			$search = $this->data['Department']['value'];
			$conditions = array("Department.client_id " => $client_id, 'Department.status !=' => 2,'Department.name LIKE'=>"%$search%");
		} else {
			$conditions = array("Department.client_id " => $client_id, 'Department.status !=' => 2);
		}

		//$this->User->recursive = 0;
		$this->paginate['conditions'] = $conditions;
		$results = $this->paginate();

		//$this->paginate['contain'] = array('Client');
		$this->set('departments', $results);
		$this->set('client_id', $client_id);
	}//end admin_index()


	/**
	 * Admin action to view the user details
	 * 
	 * @param Integer $id ID of the user to be viewed
	 * @access public
	 * @return void
	 */
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Department', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('department', $this->Department->read(null, $id));
	}//end admin_view()


	/**
	 * Action for admin to add a department
	 * 
	 * @param int $clientId The client ID
	 * @access public
	 * @return void
	 */
	function admin_add($clientId = null) {
		if (!empty($this->data)) {
			//pr($this->data); exit;
			$this->Department->create();
			$this->data['Department']['client_id'] = $clientId;
			//pr($this->data); exit;
			if ($this->Department->save($this->data)) {
				$this->Session->setFlash(__('The department has been added', true));
				$this->redirect(array('action' => 'index', $clientId));
			} else {
				$this->data['User']['password'] = '';
				$this->Session->setFlash(__('The department could not be added. Please, try again.', true));
			}
		}
		//$clients = $this->Department->Client->find('list');
		$this->set('clientId', $clientId);
	}//end admin_add()





	/**
	 * Action for admin to add a user
	 * 
	 * @param int $clientId The client ID
	 * @access public
	 * @return void
	 */
	function client_add($clientId = null) {
		if (!empty($this->data)) {
			$this->data['User']['client_id'] = $this->Auth->user('id');
			$this->User->create();
			if ($this->User->saveAll($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->data['User']['password'] = '';
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		//$clients = $this->User->Client->find('list');
		$clientId = $this->Auth->user('id');
		$this->set("clientId", $clientId);
	}//end admin_add()


	/**
	 * Action for admin to edit a user
	 * 
	 * @param integer $id The user ID
	 * @access public
	 * @return void
	 */
	function admin_edit($depatmentId = null, $client_id = null) {
		if (!$depatmentId && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Department', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			
			if ($this->Department->save($this->data)) {
			    $dept_users_obj = ClassRegistry::init('DepartmentsUser');
			    $dept_users_obj->updateAll(
						  array(
						      'DepartmentsUser.department_name'=>'\''.$this->data['Department']['name'].'\''
							),
						  array(
						      'DepartmentsUser.department_id'=>$this->data['Department']['id']
						       )
						      );
				$this->Session->setFlash(__('The department has been updated', true));
				$this->redirect(array('action' => 'index', $client_id));
			} else {
				$this->Session->setFlash(__('The department could not be updated. Please, try again.', true));
			}
		}
		//$clients = $this->Department->Client->find('list');
		if (empty($this->data)) {
			$this->data = $this->Department->read(null, $depatmentId);
		}
		$this->set('depatmentId', $depatmentId);
		$this->set('client_id', $client_id);
	}//end admin_edit()


	/**
	 * Admin action to logically delete a user
	 * 
	 * @param integer ID of the user to be deleted
	 * @access public
	 * @return void
	 */
	function admin_delete($id = null, $clientId = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for department', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Department->softDelete($id)) {
			$this->Session->setFlash(__('Department deleted', true));
			$this->redirect(array('action'=>'index', $clientId));
		}
		$this->Session->setFlash(__('Department could not be deleted', true));
		$this->redirect(array('action' => 'index', $clientId));
	}//end admin_delete()


	/**
	 * Action for client to list the users
	 * 
	 * @access public
	 * @return void
	 */
	function client_index()
	{
		// Get the logged-in client ID
		$clientId = $this->Auth->user('id');
		if ($this->data['User']['value']) {
			//debug($this->data);
			$search = $this->data['User']['value'];
			  $conditions = array	(
						'OR'  => array('User.username LIKE' => "%$search%", 'User.firstname LIKE' => "%$search%", 'User.lastname LIKE' => "%$search%"), 
						'AND' => array('User.client_id' => $clientId)
						);
		} else {
			$conditions = array('User.client_id' => $clientId);
		}

		// Find the paginated list of users
		$this->User->recursive = -1;
		$this->paginate['conditions'] = $conditions;
		$users = $this->paginate();

		$this->set(compact('users'));
	}//end client_index()


	function client_list($clientId=null) {

		if($clientId == ''){
			// Get the logged-in client ID
			$clientId = $this->Auth->user('id');
			$this->set('chain_user','0');
		}else{
			$this->set('chain_user','1');
		}

		$clientArray = $this->Department->getChildHotelsArray($clientId);
		$clientStr = implode(',', $clientArray);
//print_r($clientParentArray);
		$condition = "Department.client_id IN (".$clientStr.") and Department.status != 2";
		$user_data = $this->Department->find('all',array('conditions'=>array($condition)));
		if ($this->data['Department']['value']) {
		$search = trim($this->data['Department']['value']);

		$this->set('search',$search);

// 		$conditions = array('OR'  => array('Department.name LIKE' => "%$search%"), 
// 						'AND' => array('Department.client_id' => $clientId, 'Department.status != '=>2)
// 						);
		$conditions = array('Department.name LIKE' => "%$search%", 'Department.client_id' => $clientId, 'Department.status != '=>2);
		unset($user_data);
		$user_data = $this->Department->find('all',array('conditions'=>array('Department.name LIKE' => "%$search%", 'Department.client_id'=>$clientId,'Department.status != '=>2)));
		} else {
			$conditions = array('Department.client_id' => $clientId , 'Department.status != '=>2);
		}

		$this->set('departments',$user_data);
     		$this->set('clientId', $clientId);
		$this->paginate['conditions'] = $conditions;

		$users = $this->paginate();
		$this->set(compact('departments'));
	}//end client_view()

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
        
        
        function staff_list() {

                $clientId = $this->Auth->user('client_id');
            
                $this->User = ClassRegistry::init('User');
                $userData = $this->User->read(null, $this->Auth->user('id'));
                $depts = array();
                foreach($userData['DepartmentsUser'] as $dept)
                {
                        array_push($depts,$dept['department_id']);
                }
                //print_r($depts);
                
                $deptsStr = implode(',', $depts);
                
                if ($this->data['Department']['value']) {
        		$search = trim($this->data['Department']['value']);
                	$this->set('search',$search);
                        $conditions = array('Department.name LIKE' => "%$search%", 'Department.id' => $depts, 'Department.status != '=>2);
                } else {
			$conditions = "Department.id IN (".$deptsStr.") and Department.status != 2";
		}

               // print_r($conditions);
     		$this->set('clientId', $clientId);
		$this->paginate['conditions'] = $conditions;
		$departments = $this->paginate();
		$this->set(compact('departments'));
	}//end client_view()


}//end class
