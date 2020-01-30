<?php
class Admin extends AppModel {
	var $name = 'Admin';
	var $displayField = 'username';
	var $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('emptypass'),
				'message' => 'Please enter the password',
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
				'message' => 'Please enter First Name',
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Please enter a valid email address',
			),
		),
	);


	/**
	 * Method to check if the passwords is empty
	 * 
	 * @return boolean true/false
	 */
	/*function emptypass() {
		if ($this->data['Admin']['password'] == '' || ($this->data['Admin']['password'] == AuthComponent::password(''))) {
			return false;
		}

		return true;
	}*///emptypass()


	/**
	 * Method to match the entered passwords
	 * 
	 * @return boolean true/false
	 */
	/*function confirmpass() {
		if ($this->data['Admin']['password'] == AuthComponent::password($this->data['Admin']['confirm_password'])) {
			return true;
		}

		return false;
	}*///confirmpass()


}//end class
