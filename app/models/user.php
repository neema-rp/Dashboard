<?php
class User extends AppModel {
	var $name = 'User';
	var $displayField = 'username';
	var $virtualFields = array(
		'fullname' => "CONCAT(User.firstname, ' ', User.lastname)"
	);
	var $validate = array(
		/*'client_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
			),
		),*/
		'department_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter the department name',
			),
		),
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter username',
			),
			'unique' =>array(
				'rule' => array('checkUsername'),
				'message' => 'This username has already been taken.'
				)
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('emptypass'),
				'message' => 'Please enter password',
			),
		),
		'confirm_password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please re-enter the password',
				'last' => true,
			),
			'confirm'  => array(
				'rule' => array('confirmpass'),
				'message' => 'Passwords did not match!',
			),
		),
		'firstname' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter first name',
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Please enter a valid email',
			),

// 			'unique' =>array(
// 				'rule' => array('checkEmail'),
// 				'message' => 'This email has already been taken.'
// 				)

		),
		'phone' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter phone number',
			),
		),
	);


	var $hasOne    = array('Sheet');
	var $belongsTo = array('Client');
	
	var $hasMany = array('DepartmentsUser');
		    
	function createTempPassword($len) {
		$pass = '';
		$lchar = 0;
		$char = 0;
		for($i = 0; $i < $len; $i++) {
			while($char == $lchar) {
				$char = rand(48, 109);
				if($char > 57) $char += 7;
				if($char > 90) $char += 6;
			}
			$pass .= chr($char);
			$lchar = $char;
		}
		return $pass;
	}


	function checkUsername($rowName){
		$row = $this->find('first',array('conditions'=>array('User.username'=>$rowName['username'], 'User.status !='=>2)));
		if(empty($row)){
			return true;
		}else{
			return false;
		}
	}

	function checkEmail($rowName){
		$row = $this->find('first',array('conditions'=>array('User.email'=>$rowName['email'], 'User.status !='=>2)));
		if(empty($row)){
			return true;
		}else{
			return false;
		}
	}

	function getChildHotelsArray($clientID){
		$allrows = $this->find('all',array('conditions'=>array('parent_id'=>$clientID, 'Client.status !=' => 2)));
		
		$array = array();
		$array[]=$clientID;
		foreach($allrows as $row){
			if(!in_array($row['Client']['id'], $array)){
				$array[] = $row['Client']['id'];
			}
		}
		return $array;
		//print_r($allrows);
	}


}//end class
