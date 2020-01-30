<?php
class Client extends AppModel {
	var $name = 'Client';
	var $displayField = 'username';
	var $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter username',
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
				'message' => 'Your custom Client Name',
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
				'message' => 'Please enter your phone number',
			),
		),
	);


	//var $hasMany = array('User');
	var $hasMany = array(
				'Department' => array('conditions' => array('Department.status ' => 1))
			    );


	/**
	 * Method to save the uploaded logo image
	 * 
	 * @param integer $clientID The Client ID
	 * @param array   $logo     The uploaded image details
	 * @access public
	 * @return Mixed filename on success, false on error
	 */
	function saveLogo($clientID, $logo)
	{
		if ($logo['error'] == 0) {
			// Upload ok, save the image
			if (in_array($logo['type'], array('image/jpeg', 'image/png', 'image/gif'))) {
				$target   = "files/clientlogos";
				$logoName = 'client'. $clientID ."_". $logo['name'];

				if (move_uploaded_file($logo['tmp_name'], $target . DS . $logoName)) {
					// Update the client record
					$this->id = $clientID;
					$this->saveField('logo', $logoName); 

					return $logoName;
				}
			}
		}

		// Return false by default
		return false;
	}//end saveLogo()

}//end class

