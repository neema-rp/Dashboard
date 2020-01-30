<?php
class Subadmin extends AppModel {
	var $name = 'Subadmin';
	var $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter username',
			),
			'unique' =>array(
				'rule' => array('checkusername'),
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

		),
		'phone' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter phone number',
			),
		),
	);

	var $hasMany = array('SubadminClient');
		    
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


	function checkusername($rowName){
		$row = $this->find('first',array('conditions'=>array('Subadmin.username'=>$rowName['username'], 'Subadmin.status !='=>2)));
		if(empty($row)){
			return true;
		}else{
			return false;
		}
	}



}//end class
