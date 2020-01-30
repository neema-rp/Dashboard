<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @subpackage    cake.cake.libs.model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application model for Cake.
 *
 * This is a placeholder class.
 * Create the same file in app/app_model.php
 * Add your application-wide methods to the class, your models will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.model
 */
class AppModel extends Model {

	var $actsAs = array('Containable');

	/**
	 * Function called before the data for client model is saved
	 *
	 * @return void
	 */
/*	function beforeSave() {
		debug($this->alias);
		debug(AuthComponent::user());exit;
		if (isset($this->data['Client']['password'])) {
			$this->data['Client']['password'] = AuthComponent::password($this->data['Client']['password']);
		}
	}//end beforeSave()
*/

	/**
	 * Method to logically delete one or more records.
	 * 
	 * @param mixed $id ID or array of IDs to be deleted
	 * @return boolean true/false
	 */
	function softDelete($id) {
		if ($this->updateAll(array('status' => "'2'"), array($this->alias.'.id' => $id))) {
			return true;
		}

		return false;
	}//end softDelete()


	/**
	 * Method to check if the password field is empty
	 * 
	 * @return boolean true/false
	 */
	function emptypass() {
		if ($this->data[$this->alias]['password'] == '' || ($this->data[$this->alias]['password'] == AuthComponent::password(''))) {
			return false;
		}

		return true;
	}//emptypass()


	/**
	 * Method to match the entered passwords
	 * 
	 * @return boolean true/false
	 */
	function confirmpass() {
		//debug($this->data[$this->alias]['password']);
		//debug(AuthComponent::password($this->data[$this->alias]['confirm_password']));exit;
		if ($this->data[$this->alias]['password'] == AuthComponent::password($this->data[$this->alias]['confirm_password'])) {
			return true;
		}

		return false;
	}//confirmpass()



}//end class
