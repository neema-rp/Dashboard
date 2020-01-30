<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {
	var $components = array('Session', 'Auth');


	/**
	 * The default controller function that is called before every controller action
	 *
	 * @return void
	 */
	function beforeFilter() {
		$controller = $this->params['controller'];
		$this->Auth->allow(array('home', 'display','autologinUser'));
		// Set the usermodel
		
                
                if ($this->Session->check('Auth.Client')) {
                    $activityId = $this->Session->read('activityId');
                    if (!empty($activityId)) {
                            ClassRegistry::init('Activity')->updateActivity($activityId);
                    }else{
                        $clientId = $this->Session->read('Auth.Client.id');
                        $activityId  = Classregistry::init('Activity')->insertActivity('0', $clientId);
                        $activityIdWrite = $this->Session->write('activityId', $activityId);
                    }                    
                }elseif ($this->Session->check('Auth.User')) {
                    $activityId = $this->Session->read('activityId');
                    if (!empty($activityId)) {
                            ClassRegistry::init('Activity')->updateActivity($activityId);
                    }else{
                        $userId = $this->Session->read('Auth.User.id');
                        $clientId = $this->Session->read('Auth.User.client_id');
                        $activityId  = Classregistry::init('Activity')->insertActivity($userId, $clientId);
                        $activityIdWrite = $this->Session->write('activityId', $activityId);
                    }
                }
                
		
                
                
                if (($controller == 'subadmins' || (isset($this->params['prefix']) && $this->params['prefix'] == 'subadmin')) && ($this->params['action'] == 'login')) {
			$this->Auth->userModel     = 'Subadmin';
                        $this->Auth->userScope     = array('Subadmin.status' => 1);
			$this->Auth->loginAction   = array('prefix' => 'subadmin', 'subadmin' => false, 'controller' => 'subadmins', 'action' => 'login');
			$this->Auth->loginRedirect = array('prefix' => 'admin', 'admin' => false, 'controller' => 'subadmins', 'action' => 'index');
			if ($this->modelClass != 'Subadmin' && isset($this->data[$this->modelClass]['password'])) {
				$this->data[$this->modelClass]['password'] = $this->Auth->password($this->data[$this->modelClass]['password']);
			}
		} elseif ($controller == 'admins' || (isset($this->params['prefix']) && $this->params['prefix'] == 'admin')) {
			$this->Auth->userModel     = 'Admin';
                        $this->Auth->userScope     = array('Admin.status' => 1);
			$this->Auth->loginAction   = array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'login');
			$this->Auth->loginRedirect = array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'index');

			// Auth will not hash other model passwords. Hash them manually.
			if ($this->modelClass != 'Admin' && isset($this->data[$this->modelClass]['password'])) {
				$this->data[$this->modelClass]['password'] = $this->Auth->password($this->data[$this->modelClass]['password']);
			}
		} elseif ($controller == 'users' && (isset($this->params['prefix']) && $this->params['prefix'] == 'client') && ($this->params['action'] == 'client_login')) {
			
			$this->Auth->userModel     = 'User';
                        $this->Auth->userScope     = array('User.status' => 1);
                        
                        $this->Auth->loginAction   = array('prefix' => 'client', 'client' => true, 'controller' => 'users', 'action' => 'login');
                        
			//$this->Auth->loginRedirect = array('prefix' => 'client', 'client' => true, 'controller' => 'departments', 'action' => 'list');
                        $this->Auth->loginRedirect = array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'index');
			
			$this->Session->write('clientuser',1);
                        
		} elseif ($controller == 'users' && (isset($this->params['prefix']) && $this->params['prefix'] == 'staff') && ($this->params['action'] == 'staff_alllogin')) {
			
			$this->Auth->userModel     = 'User';
                        $this->Auth->userScope     = array('User.status' => 1);
                        $this->Auth->loginAction   = array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'alllogin');
                        $this->Auth->loginRedirect = array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'alllogin');
			$this->Session->write('checkclientuser',1);
                        
		} elseif ($controller == 'clients' || (isset($this->params['prefix']) && $this->params['prefix'] == 'client')) {
			$this->Auth->userModel     = 'Client';
                        $this->Auth->userScope     = array('Client.status' => 1);
			$this->Auth->loginAction   = array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'login');
                        
			//$this->Auth->loginRedirect = array('prefix' => 'client', 'client' => true, 'controller' => 'departments', 'action' => 'list');
                        $this->Auth->loginRedirect = array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'index');
                        
		} elseif ($controller == 'users' || (isset($this->params['prefix']) && $this->params['prefix'] == 'staff')) {
			$this->Auth->userModel     = 'User';
                        $this->Auth->userScope     = array('User.status' => 1);
			$this->Auth->loginAction   = array('prefix' => 'staff', 'client' => false, 'controller' => 'users', 'action' => 'login');
			$this->Auth->loginRedirect = array('prefix' => 'staff', 'client' => false, 'controller' => 'users', 'action' => 'index');
		}

		
// 		else {
// 			$this->Auth->loginAction   = array('controller' => 'users', 'action' => 'login');
// 			$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'index');
// 		}


		$this->Auth->loginError = 'Incorrect username or password';
	}//end beforeFilter()


	/**
	 * Function to set the login redirects. 
	 * If a user is already logged in, the system will redirect him to the default user page.
	 * 
	 * @return void
	 */
	function setLoginRedirects() {
            
           // print_r($this->Session->read('Auth.Admin.id'));
                    
                    //echo 'Admin'; exit;
                    

            //print_r($this->params); exit;
            
		if ($this->Session->check('Auth.Subadmin')) {
                    
                        $cl_details = Classregistry::init('Admin')->findById('1');
                        
                        $cl_details['Admin']['IsSubAdmin'] = '1';
                        $cl_details['Admin']['SubAdminID'] = $this->Session->read('Auth.Subadmin.id');
                        
                        $this->Session->delete('Auth.Subadmin');

                        $this->Session->write('Auth.Admin',$cl_details['Admin']);
                    	$this->redirect(array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'index'));
                        
                        
		} elseif ($this->Session->check('Auth.Admin')) {
			$this->redirect(array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'index'));
		} elseif ($this->Session->check('Auth.Client')) {
                    
			$this->redirect(array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'index'));
		} elseif ($this->Session->check('Auth.User')) {
			if($this->Session->check('checkclientuser')){
				$client_user_data = Classregistry::init('ClientUser')->find('first',array('conditions'=>array('user_id'=>$this->Session->read('Auth.User.id'))));
                                
                                if(!empty($client_user_data)){
                                        
                                        $cl_id = $client_user_data['ClientUser']['client_id'];
                                        
                                        //$cl_id = $this->Session->read('Auth.User.client_id');
                                        if($cl_id == '0'){
                                                $cl_id = $this->Session->read('Auth.User.client_id');
                                        }
                                        $cl_details = Classregistry::init('Client')->findById($cl_id);
                                        $cl_details['Client']['firstname'] = $this->Session->read('Auth.User.firstname');
                                        $cl_details['Client']['lastname'] = $this->Session->read('Auth.User.lastname');

                                        $this->Session->delete('Auth.User');
                                        $this->Session->write('Auth.Client',$cl_details['Client']);
                                        
                                        $this->redirect(array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'index'));
                                }else{
                                    $this->redirect(array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'index'));
                                }
                                $this->Session->delete('checkclientuser');
                                
			}elseif($this->Session->check('clientuser')){
				//$cl_id = Classregistry::init('Client')->field('Client.parent_id',array('Client.id'=>$this->Session->read('Auth.User.client_id')));
				
				
                                $client_user_data = Classregistry::init('ClientUser')->find('first',array('conditions'=>array('user_id'=>$this->Session->read('Auth.User.id'))));
                                if(!empty($client_user_data)){
                                    
                                    $cl_id = $client_user_data['ClientUser']['client_id'];
                                    
                                    //$cl_id = $this->Session->read('Auth.User.client_id');
                                    if($cl_id == '0'){
                                            $cl_id = $this->Session->read('Auth.User.client_id');
                                    }
                                    
                                    $cl_details = Classregistry::init('Client')->findById($cl_id);
                                    $cl_details['Client']['firstname'] = $this->Session->read('Auth.User.firstname');
                                    $cl_details['Client']['lastname'] = $this->Session->read('Auth.User.lastname');

                                    $this->Session->delete('Auth.User');
                                    $this->Session->write('Auth.Client',$cl_details['Client']);
                                    //pr($this->Session->read());
                                    $this->redirect(array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'index'));
                                }else{
                                    $this->Session->delete('clientuser');
                                    $this->Session->delete('Auth.User');
                                }
			}else{			
				$this->redirect(array('controller' => 'users','action' => 'index'));
			}
		}
	}// setLoginRedirects()

}//end class
