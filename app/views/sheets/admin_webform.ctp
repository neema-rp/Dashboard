<?php ?>
<style>
.refreshButton{
    padding: 10px;
    position: absolute;
    top: 565px;
    left:310px;
}
.refreshlink{
    background-color: #A42C1E;
    border-radius: 5px 5px 5px 5px;
    color: #FFFFFF;
    float: left;
    margin-right: 5px;
    margin-top: -3px;
    padding: 3px;
    text-decoration: none;
}

.page-content{
 padding:0px;   
}
.row-fluid{
/*    width: 110%;
    float: right;*/
}


/* hack for ipad */
@media only screen
and (min-device-width : 768px)
and (max-device-width : 1024px) {
/* Styles */
.x-grid-view{
overflow:auto;overflow-x:scroll;overflow-y:scroll;
}
}

#webform-grid{ width: 100%; border-left:1px solid #fff; }
.x-panel{ margin:auto; }
</style>

<?php if($session->check('Message.flash.message')) { ?> 
<style>
.refreshButton{ top: 590px; }
</style>
<?php } ?>

<?php
//Prepare a JSON object for the column heads;
$columnGrid = array(
	array(
		'dataIndex' => 'id',
		'width'     => 100,
		'text'      => 'id',
		'hideable'  => false,
		'editable'  => false,
		'hidden'    => true
	),
	array(
		'dataIndex' => 'sheetId',
		'width'     => 100,
		'text'      => 'sheetId',
		'hideable'  => false,
		'editable'  => false,
		'hidden'    => true
	),
	array(
		'dataIndex' => 'Date',
		'width'     => 100,
		'text'      => 'Date',
		'hideable'  => false,
		'editable'    => false,
		'renderer'  => 'd/m/y',
	)
);

$dataIndices = array('id', 'sheetId', 'Date');
$rows_obj = ClassRegistry::init('RowsSheet');
$rows_data = $rows_obj->find('all',array('conditions'=>array('sheet_id'=>$sheetId,'locked'=>1)));
$row_name = array();
$row_name_obj = ClassRegistry::init('Rows');
foreach($rows_data as $rows){
  $row_name[] = $row_name_obj->field('name',array('id'=>$rows['RowsSheet']['row_id']));
}
$total_locked_rows = count($row_name);
$show_bar_update = '0';
foreach ($columns as $column) {
        if($column['id'] == '118'){
            $show_bar_update = '1';
        }
    
	$arr = array();
	$dataIndices[]    = $column['name'];
	$arr['dataIndex'] = $column['name'];
	$arr['flex']      = 1;
	$arr['text']      = $column['name'];
	
	//if column is locked then make it uneditable
	if($column['ColumnsSheet']['locked'] == "1" || $column['ColumnsSheet']['locked'] == 1){
		$arr['editable'] = "false";
	}else{
		$arr['editor']    = 'textfield';
	}
	$columnGrid[] = $arr;
}

//Add JS to the page;
echo $this->Html->scriptBlock("var total_locked_rows = ".$total_locked_rows.";\nvar row_names = ".json_encode($row_name).";\nvar sheetId = ". $sheetId ."\nvar columnIndex = ". json_encode($dataIndices) .";\nvar columnGrid = ". json_encode($columnGrid) .";\nvar dataGrid = ". json_encode($data).";\nvar alternate_weekend = ". $is_habtoor, array('inline' => false));
echo $this->Html->script('webform', array('inline' => false));
?>

<?php
  $dept_obj = ClassRegistry::init('Department');
  $dept_data = $dept_obj->field('name',array('id'=>$sheet['User']['department_name']));
?>

<div class="sheets index" id="webform-grid"></div>

<div class="refreshButton" style=" font-weight: bold;text-align: center;width: 65%;">
Values Last Updated on : <?php if(empty($last_refresh_time) || ($last_refresh_time == '0000-00-00 00:00:00')){ echo date('d/m/y H:i:s'); }else{ echo date('d/m/y H:i:s',strtotime($last_refresh_time));  } ?> GMT

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php if ($show_bar_update == '1'){ ?>
    <a class="btn btn-info" href="/webservices/get_bar_levels/<?php echo $clientId; ?>/<?php echo $sheetId; ?>">Update Bar Level</a>
<?php } ?>
    
    <a class="btn btn-info" href="/webservices/update_last_year_row/<?php echo $sheetId; ?>">Update LY Actual</a>

    <input type="hidden" value="<?php echo $sheetId; ?>" id="sheet_id" />
<a href="javascript:void(0);" id="iframe_subscribe" class="btn btn-info">Manage Notes</a>
    
</div>

<link rel="stylesheet" type="text/css" href="/css/colorbox/colorbox.css" />
<script type="text/javascript" src="/js/jquery.colorbox.js"></script>

<script>
$(document).ready(function(){

$('#iframe_subscribe').click(function(event){
    var sheet_id = $("#sheet_id").val();
    var colorbox_url = '/webservices/inine_edit/'+sheet_id;
    $('#iframe_subscribe').colorbox({href:colorbox_url, iframe: true, width:"75%", height:"70%"});	
});
  
});

</script>