<?php
require_once($_SERVER['DOCUMENT_ROOT'] ."/app/config/config.php");
class BogusAction {
	public $action;
	public $method;
	public $data;
	public $tid;
}

$isForm = false;
$isUpload = false;

if(isset($RAW_DATA)) {
	header('Content-Type: text/javascript');
	$data = json_decode($RAW_DATA);
} else if (isset($_POST['extAction'])) { // form post
	$isForm = true;
	$isUpload = $_POST['extUpload'] == 'true';
	$data = new BogusAction();
	$data->action = $_POST['extAction'];
	$data->method = $_POST['extMethod'];
    $data->tid = isset($_POST['extTID']) ? $_POST['extTID'] : null; // not set for upload
	$data->data = array($_POST, $_FILES);
} else {
	die('Invalid request.');
}
//print_r($data);

switch ($data->method) {
	case 'getdata':
		$result = ClassRegistry::init('Sheet')->getsheetdata($data->data[0]->sheetId);
		break;
	case 'savedata':
		//$result = $data->data[0]->sheetId;
		$result = ClassRegistry::init('Sheet')->saveData($data->data[0]->sheetId);
		break;
}


$routerData['type']   = $data->type;
$routerData['tid']    = $data->tid;
$routerData['action'] = $data->action;
$routerData['method'] = $data->method;
$routerData['result'] = $result;

echo json_encode($routerData);
