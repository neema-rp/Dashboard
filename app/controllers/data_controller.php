<?php
class DataController extends AppController {

	/**
	 * Class property that stores the controller name
	 */
	var $name = 'Data';

	/**
	 * Admin action to list the users
	 * 
	 * @access public
	 * @return void
	 */
	function admin_index() {
		$data = $this->Datum->Sheet->find('all');
		debug($data);exit;
	}//end admin_index()

}//end class
