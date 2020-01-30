<?php
class Department extends AppModel {
	var $name = 'Department';
	var $displayField = 'name';
	var $validate = array(
		/*'client_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
			),
		),*/
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter the department name',
			),
		),

		'name' => array(
			'notempty' => array(
				'rule' => array('checkDepartmentName'),
				'message' => 'Department name not available.',
			),
		),

	);

	//var $hasOne    = array('Sheet');
	var $belongsTo = array('Client');
	

	function checkDepartmentName($deptName){
/*	echo "<pre>";
	print_r($deptName);
	print_r($_POST);
	print_r($_GET);*/
	
		//need client_id - will get it from url
		$url = $_GET['url'];
		$expUrlArr = explode('/', $_GET['url']);
		$client_id = $expUrlArr[3];
		$deprtName = $deptName['name'];

		$allrows = $this->find('all',array('conditions'=>array('client_id'=>$client_id, 'name'=>$deprtName)));
		$isduplicate = false;
		foreach($allrows as $row){
			if($row['Department']['status'] == 1 || $row['Department']['status'] == '1'){
				$isduplicate = true;
				break;
			}
		}
// echo "<pre>";
// echo $deprtName;
// print_r($row);
// exit;
		if(!($isduplicate)){
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
