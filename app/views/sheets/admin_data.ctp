<?php
//Prepare a JSON object for the column heads;
/*$columnGrid = array(array('dataIndex' => 'Date', 'width' => 80, 'text' => 'Date'));
foreach ($columns as $key => $column) {
	$arr['dataIndex'] = $column;
	$arr['flex'] = 1;
	$arr['text'] = $column;
	$columnGrid[] = $arr;
}
*/
//Add JS to the page;
echo $this->Html->scriptBlock('var dataGrid = '. json_encode($data));
?>

