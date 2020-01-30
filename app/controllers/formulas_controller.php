<?php
class FormulasController extends AppController {

	var $name = 'Formulas';
	var $helpers = array('Html', 'Javascript', 'Session');
	var $uses = array('Sheet', 'Formula', 'Column','Row');


	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('router','updatePrimary','updateOrder');
	}

	/**
	 * Action to list all the available columns
	 * 
	 * @param $userId The Sheet user ID  
	 * @access public
	 * @return void
	 */
	function admin_index($sheetId) {
            
           // Configure::write('debug',2);
		// Check the given user ID
		//$this->__check_user($userId);
	
		$sheet = $this->Sheet->find($sheetId);
		$formulas = $sheet['Formula'];
		$SheetColumns = $this->Sheet->ColumnsSheet->find('all', array("conditions"=>"sheet_id = {$sheetId}"));
		//$unlocksheetColumns = $this->Sheet->ColumnsSheet->find('all', array("conditions"=>"sheet_id = {$sheetId} AND  locked !=1"));
		$unlocksheetColumns = $this->Sheet->ColumnsSheet->find('all', array("conditions"=>"sheet_id = {$sheetId}"));

		$columnIds = Set::extract('/ColumnsSheet/column_id', $SheetColumns);
                //$columns = array_diff($columns, array('Notes', 'TripAdvisor','BAR Level'));
                $columnIds = array_diff($columnIds, array('128', '78','118'));
		//$idstr = implode(',',$columnIds);

		$unlock_columnids = Set::extract('/ColumnsSheet/column_id', $unlocksheetColumns);
		//$unlock_idstr = implode(',',$unlock_columnids);
		$rest_formula = array();


		if(!empty($columnIds))
		{
                        $columns = $this->Sheet->Column->find('list', array('conditions'=>array('id'=>$columnIds)));
                        $unlock_columns = $this->Sheet->Column->find('list', array('conditions'=>array('id'=>$unlock_columnids,'status'=>'1')));
		}else
		{
			$columns = "";
			$unlock_columns = "";
		}

		$SheetRows = $this->Sheet->RowsSheet->find('all', array("conditions"=>array('sheet_id'=>$sheetId)));
		$rowIds = Set::extract('/RowsSheet/row_id', $SheetRows);
		$id_str = implode(',',$rowIds);
		if(!empty($rowIds))
		{
			$rows = $this->Sheet->Row->find('list', array('conditions'=>array('id'=>$rowIds)));
			$rows = $this->array_push_assoc($rows,'total','MonthTotal'); //added for total
			//$bottom_rows = $this->Sheet->Row->find('list', array('conditions'=>"id in({$id_str})"));
			//$bottom_rows = $this->array_push_assoc($rows,'total','MonthTotal'); //changed for total
			$bottom_rows = $rows;
		}else
		{
			//$rows = "";
			$rows =  array();
			$bottom_rows = "";
			$bottom_rows = $this->array_push_assoc($rows,'total','MonthTotal');
			$bottom_rows = $rows; //added for total

		}
		
		$operators = array(
					"0" => "+",
					"1" => "-",
					"2" => "*",
					"3" => "/",
					"4" => "(",
					"5" => ")"
					//"4" => "=",
				);

		$all_columns = $this->Column->find('list');
		$a_column = array();
		$a_col_keys = array();
		$a_col_values = array();

		$all_rows = $this->Row->find('list');
		$a_row = array();
		$a_row_keys = array();
		$a_row_values = array();

		foreach($all_columns as $ckey=>$cvalue)
			{
				$a_column['C'.$ckey] = $cvalue;
			}
		foreach($all_rows as $ckey=>$cvalue)
			{
				$a_row['R'.$ckey] = $cvalue;
			}

		foreach($a_column as $ke=>$va)
		{
			$a_col_keys[] = $ke;
			$a_col_values[] = $va;
		}
		$a_col_keys = array_reverse($a_col_keys);
		$a_col_values = array_reverse($a_col_values);


		foreach($a_row as $ke=>$va)
		{
			$a_row_keys[] = $ke;
			$a_row_values[] = $va;
		}
		$a_row_keys = array_reverse($a_row_keys);
		$a_row_values = array_reverse($a_row_values);


		$all_formulas = $this->Formula->find('all',array('conditions'=>array('Formula.sheet_id'=>$sheetId),'order'=>array('Formula.column_order'=>'ASC')));
//echo "<pre> all";
//print_r($all_formulas);
		$cal_formula = array();
		for($i=0;$i<count($all_formulas);$i++){

//$column_data = $this->Sheet->Column->findById($all_formulas[$i]['Formula']['column_id'],array('fields'=>'Column.name'));
//$cal_formula[$i]['res'] = $column_data['Column']['name'];

$cal_formula[$i]['res'] = $columns[$all_formulas[$i]['Formula']['column_id']];

                    
if($all_formulas[$i]['Formula']['row_id'] == "Total"){
	$cal_formula[$i]['row'] = "Total";
}else{
	//$row_data = $this->Sheet->Row->findById($all_formulas[$i]['Formula']['row_id'],array('fields'=>'Row.name'));
	//$cal_formula[$i]['row'] = $row_data['Row']['name'];
    
        $cal_formula[$i]['row'] = $rows[$all_formulas[$i]['Formula']['row_id']];
}

$rest_formula[] = str_replace($a_col_keys,$a_col_values,str_replace($a_row_keys,$a_row_values,$all_formulas[$i]['Formula']['formula']));

foreach($operators as $key=>$value){

	$temp_formula = explode($value,$all_formulas[$i]['Formula']['formula']);
	if(count($temp_formula)>1){
		$val = 0;
		//pr($temp_formula);
		foreach($temp_formula as $tfmla){
			$temp_formula2 = explode('C',$tfmla);
			if(!empty($temp_formula2[1]))
			{
//			$column_data = $this->Sheet->Column->findById($temp_formula2[1],array('fields'=>'Column.name'));
//			$cal_formula[$i]['formula'][] = $column_data['Column']['name'];
                        $cal_formula[$i]['formula'][] = $columns[$temp_formula2[1]];
			if($val != count($temp_formula)-1)
			$cal_formula[$i]['formula'][] = $value;
			$val++;
			}
		}
	}
 }
		}



		$str_formula = array();
// 		pr($cal_formula);
		//exit();
		for($i=0;$i<count($cal_formula);$i++){
			$row_formula = "";

			if(!empty($cal_formula[$i]['row']))
			{
				$row_formula = '|'.$cal_formula[$i]['row'];
			}

			$str = $cal_formula[$i]['res'] .$row_formula. ' = ';
			$str.= $rest_formula[$i];
// 			foreach($cal_formula[$i]['formula'] as $val){
// 				$str .= ' '. $val;
// 			}
			$str_formula[$all_formulas[$i]['Formula']['column_id']][$all_formulas[$i]['Formula']['row_id']]=$str;
			
			if($all_formulas[$i]['Formula']['type'] == "main"){
				$str_formula['type'] = $all_formulas[$i]['Formula']['column_id'].'_'.$all_formulas[$i]['Formula']['row_id'];
			}
		}


		$final_str_formula = array();
		$final_column_order = array();
		$final_ordering_formula = array();
// 		pr($str_formula);
		foreach($str_formula as $str_key=>$str_value)
		{
				if(is_array($str_value))
				{
					foreach($str_value as $str_k=>$str_v)
					{
						$final_str_formula[$str_key.'_'.$str_k]=$str_v;
					}
				}else
				{
					$final_str_formula['type']=$str_value;
				}
		}


$final_str_order = $this->Formula->find ('all', array('conditions'=> array('Formula.sheet_id'=>$sheetId),'fields'=> array('Formula.column_id','Formula.row_id','Formula.id'), 'order'=>array('Formula.column_order'=>'ASC')));
/* get the result based on column order */

		foreach($final_str_order as $final_str_key=>$final_str_value)
		{
			foreach($final_str_value as $final_str_k=>$final_str_v)
			{
				$final_column_order[$final_str_key] = $final_str_v['column_id'].'_'.$final_str_v['row_id'];
			}
		}

/* Made the final order formula*/

		foreach($final_column_order as $final_column_k=>$final_column_v)
		{	
				$final_ordering_formula[$final_column_v]=$final_str_formula[$final_column_v];
				if(!empty($final_str_formula['type']))
				{
				$final_ordering_formula['type'] = $final_str_formula['type'];
				}

		}

		$formula_ids = array();
		foreach($final_str_order as $formulas){
		    $formula_ids[$formulas['Formula']['column_id'].'_'.$formulas['Formula']['row_id']] = $formulas['Formula']['id'];
		}
		  $this->set(compact('formula_ids'));
		$this->set('all_formulas',$final_ordering_formula);
// echo "<pre>";
// print_r($final_ordering_formula);
		$this->set(compact('sheet', 'sheetId','formulas','columns','operators','rows','unlock_columns','bottom_rows'));

	}//end admin_index()


	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

	/**
	 * Action to add the formula in the database
	 * 
	 * @param $userId The Sheet user ID  
	 * @access public
	 * @return void
	 */
	function admin_add_formula() {

		if(!empty($this->data)){	
// 		pr($this->data);
// 		exit();
		if(!empty($this->data['Formula']))
		{
			$operators = array(
					"0" => "+",
					"1" => "-",
					"2" => "*",
					"3" => "/",
					"4" => "(",
					"5" => ")"
					//"4" => "=",
				);
			$pipe_sign = array("0"=>"|");
			$total_sign = array("0"=>"MonthTotal]");
			
			$temp_fromula_array = array();
			$arrresult = explode(" = ", $this->data['Formula']);
			$res_column_name = implode(" ", explode("_", $arrresult[0]));

			if(strpos($res_column_name, '|'))
			{

				$column_name = substr($res_column_name, 0, strpos($res_column_name , '|'));
				$column_name=trim($column_name);
				$res_column = $this->Column->find('first',array('conditions'=>array('name'=>$column_name , 'status !='=>2)));

				$row_name = substr(strrchr($res_column_name, "|"), 1);
				$rowName=trim($row_name);

				if($rowName == "MonthTotal"){
					$res_row_id = "Total";
				}else{
					$res_row = $this->Row->find('first',array('conditions'=>array('name'=>$rowName,'status !='=>2)));
					$res_row_id = $res_row['Row']['id'];
				}

			}
			else
			{
				$res_column = $this->Column->find('first',array('conditions'=>array('name'=>$res_column_name , 'status !='=>2)));
				$res_row_id = 0;
			}
			
			//$res_column = $this->Column->find('first', array("conditions"=>"name = '{$res_column_name}'"));
			$res_column_id = $res_column['Column']['id'];
			$arrtemp = explode(" ", $arrresult[1]);

			//pr($arrtemp);
			$front_column_name_array =array();
// pr($arrtemp);exit;
			if(in_array('|',$arrtemp))
			{
			foreach($arrtemp as $key=>$value){
				if($value != ""){

					if(in_array($value, $operators)){ //if it is an operator
						array_push($temp_fromula_array, $value);
					}else if(in_array($value, $pipe_sign)){
					}else if(in_array($value, $total_sign))
					{
						array_push($temp_fromula_array,'|');
						array_push($temp_fromula_array, "Total");
					}else{ //if it is a column name
						$column_name = implode(" ", explode("_", $value));
						$front_column_name = str_replace("[","",str_replace("]","",$column_name));
						//$front_column_name_array[] = $front_column_name;

				//$column = $this->Column->find('first', array("conditions"=>"name = '{$front_column_name}'"));
				$column = $this->Column->find('first',array('conditions'=>array('name'=>$front_column_name, 'status !='=>2)));
						if(!empty($column))
							{	
							array_push($temp_fromula_array, "C".$column['Column']['id']);
							}
						      else{
							      if(is_numeric($value)){
								  array_push($temp_fromula_array, $value);
							      }
						      }

				//$ro = $this->Row->find('first', array("conditions"=> "name = '{$front_column_name}'",'recursive'=>'-1'));
				$ro = $this->Row->find('first',array('conditions'=>array('name'=>$front_column_name, 'status !='=>2), 'recursive'=>'-1'));
						if(!empty($ro))
						{
							array_push($temp_fromula_array,'|');
							array_push($temp_fromula_array, "R".$ro['Row']['id']);
						}

					}
				}
			} 
			}
			else
			{

			foreach($arrtemp as $key=>$value){
				if($value != ""){

					if(in_array($value, $operators)){ //if it is an operator
						array_push($temp_fromula_array, $value);
					}else{ //if it is a column name
						$column_name = implode(" ", explode("_", $value));

						$front_column_name = str_replace("[","",str_replace("]","",$column_name));
						$front_column_name_array[] = $front_column_name;

						//$column = $this->Column->find('first', array("conditions"=>"name = '{$column_name}'"));
						$column = $this->Column->find('first',array('conditions'=>array('name'=>$column_name , 'status !='=>2)));
						/*edited by raman August  to add numneric values as well*/
						if(!empty($column)){
						    array_push($temp_fromula_array, "C".$column['Column']['id']);
						}else{
						      array_push($temp_fromula_array, $value);
						}
					}
				}
			}
			}

// 					if(!empty($front_column_name_array))
// 					{
//
// 					foreach($front_column_name_array as $key=>$val){
//
// 						if($key%2!=0){
// 							$k = 'row';
// 						}else{
// 							$k = 'column';
// 						}
// 						$front_column_name_array[$k][] = $val;
// 					}
//
// 					/*For adding the column in db*/
// 					foreach($front_column_name_array['column'] as $single_column)
// 						{
// 						echo $single_column;
// 						$co = $this->Column->find('first', array("conditions"=>"name = '{$single_column}'"));
// 						array_push($temp_fromula_array, "C".$co['Column']['id']);
//
//
// 						}
// 					/*For adding the rows in db*/
// 					foreach($front_column_name_array['row'] as $single_row)
// 						{
// 						echo $single_row;
// 						$ro = $this->Row->find('first', array("conditions"=> "name = '{$single_row}'",'recursive'=>'-1'));
// 						array_push($temp_fromula_array, "R".$ro['Row']['id']);
//
// 						}
//
// 					}
// 			pr($this->data);
//  		pr($temp_fromula_array);
// 			exit();
			$arr_formula = array();
			$arr_formula['Formula']['sheet_id'] = $this->data['Sheet']['id'];
			$arr_formula['Formula']['column_id'] = $res_column_id;
			$arr_formula['Formula']['row_id'] = $res_row_id;
			$arr_formula['Formula']['formula'] = implode(" ", $temp_fromula_array);
			if($res_row_id == '0')
			{
			$formula_current = $this->Formula->find('first',array('conditions'=>array('Formula.sheet_id'=>$arr_formula['Formula']['sheet_id'],'Formula.column_id'=>$res_column_id,'Formula.row_id'=>'0')));
			}else
			{
			$formula_current = $this->Formula->find('first',array('conditions'=>array('Formula.sheet_id'=>$arr_formula['Formula']['sheet_id'],'Formula.column_id'=>$res_column_id,'Formula.row_id'=>$res_row_id)));
			}
	
			if(!empty($formula_current)){		
			  $arr_formula['Formula']['id'] = $formula_current['Formula']['id'];
			}
// pr($arr_formula);exit;
			if($this->Formula->save($arr_formula)){
				$this->Session->setFlash(__('Formula created and saved successfully.', true));
				$this->redirect(array('action' => 'index', $this->data['Sheet']['id'],$this->data['Department']['id']));
			}else{
				$this->Session->setFlash(__('Formula could not be saved.', true));
				$this->redirect(array('action' => 'index', $this->data['Sheet']['id']));
			}

			//pr($arr_formula); exit;
		}else
		{
			$this->Session->setFlash(__('Formula could not be Empty.', true));
			$this->redirect(array('action' => 'admin_index',$this->data['Sheet']['id'],$this->data['Department']['id']));
		}
	}

	}


	/**
	 * Action to list all the available columns
	 * 
	 * @param $userId The Sheet user ID  
	 * @access public
	 * @return void
	 */
	function client_index($userId) {
		if (!empty($this->data) && trim($this->data['Sheet']['value']) != '') {
			$this->paginate['conditions'] = array('Sheet.name LIKE' => "%". $this->data['Sheet']['value'] ."%", 'Sheet.user_id' => $userId, 'Sheet.status' => 1);
		} else {
			$this->paginate['conditions'] = array('Sheet.user_id' => $userId, 'Sheet.status' => 1);
		}
		$this->paginate['contain'] = array('User', 'Column');
		$userSheets = $this->paginate();
		$this->set(compact('userSheets', 'userId'));
	}//end client_index()


	/**
	 * Action to list all the available columns
	 * 
	 * @param $userId The Sheet user ID  
	 * @access public
	 * @return void
	 */
	function staff_index() {
		$userId = $this->Auth->user('id');
		if (!empty($this->data) && trim($this->data['Sheet']['value']) != '') {
			$this->paginate['conditions'] = array('Sheet.name LIKE' => "%". $this->data['Sheet']['value'] ."%", 'Sheet.user_id' => $userId, 'Sheet.status' => 1);
		} else {
			$this->paginate['conditions'] = array('Sheet.user_id' => $userId, 'Sheet.status' => 1);
		}
		$this->paginate['contain'] = array('User', 'Column');
		$userSheets = $this->paginate();
		$this->set(compact('userSheets', 'userId'));

		//echo "hehe... {$userId}"; exit;

	}//end client_index()



	/**
	 * Action for admin to view the Department sheet
	 * 
	 * @param int $sheetId The Department sheet ID to be viewed
	 * @access public
	 * @return void
	 */
	function admin_view($sheetId = null) {
		if (!$sheetId) {
			$this->Session->setFlash(__('Invalid Department Sheet ID', true));
			$this->redirect(array('action' => 'index'));
		}

		$this->set('sheet', $this->Sheet->read(null, $sheetId));
	}//end admin_view()


	/**
	 * Action for CLient to view the Department sheet
	 * 
	 * @param int $sheetId The Department sheet ID to be viewed
	 * @access public
	 * @return void
	 */
	function client_view($sheetId = null) {
		if (!$sheetId) {
			$this->Session->setFlash(__('Invalid Department Sheet ID', true));
			$this->redirect(array('action' => 'index'));
		}

		$this->set('sheet', $this->Sheet->read(null, $sheetId));
	}//end client_view()


	/**
	 * Action for admin to add a new Sheet
	 * 
	 * @param $userId The Department user ID for which the sheet is to be added
	 * @access public
	 * @return void
	 */
	function admin_add($userId) {
		// Check the given user ID
		$this->__check_user($userId);

		if (!empty($this->data)) {
			$this->data['Sheet']['month']   = $this->data['Sheet']['departmentmonth']['month'];
			$this->data['Sheet']['year']    = $this->data['Sheet']['departmentmonth']['year'];
			$this->data['Sheet']['user_id'] = $userId;

			$this->Sheet->create();
			if ($this->Sheet->save($this->data)) {
				$this->Session->setFlash(__('The Department sheet has been saved', true));
				$this->redirect(array('action' => 'index', $userId));
			} else {
				$this->Session->setFlash(__('The Department sheet could not be saved. Please, try again.', true));
			}
		}

		$columns = $this->Sheet->Column->find('list');
		$department = $this->Sheet->User->field('department_name', array('User.id' => $userId));
		$this->set(compact('userId', 'department', 'columns'));
	}//end admin_add()


	/**
	 * Action for admin to edit a department sheet
	 * 
	 * @param int $id ID of the department sheet to be edited
	 * @access public
	 * @return void
	 */
	function admin_edit($id = null) {
		if ($id == null || !$this->Sheet->hasAny(array('Sheet.id'))) {
			$this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'index'));
		}
		Configure::write('debug', 3);
		// Get the user ID
		$userId = $this->Sheet->field('user_id', array('Sheet.id' => $id));

		if (!empty($this->data)) {
			$selected = array();

			foreach($this->data['Column']['Column'] as $key => $value){
				if($value != 0){
				      array_push($selected, $value);
				}
			}
			$this->data['Column']['Column'] = $selected;

			$this->data['Sheet']['month']   = $this->data['Sheet']['departmentmonth']['month'];
			$this->data['Sheet']['year']    = $this->data['Sheet']['departmentmonth']['year'];
			$this->data['Sheet']['user_id'] = $userId;

			if ($this->Sheet->save($this->data)) {
				foreach($this->data['Column']['Locked'] as $key => $val){
					if($val > 0){
						$cond = "sheet_id = {$id} AND column_id = {$val}";
						$arr = $this->Sheet->ColumnsSheet->find('first', array("conditions" => $cond));
						$arr['ColumnsSheet']['locked'] = 1;
						$this->Sheet->ColumnsSheet->save($arr);
					}
				}

				$this->Session->setFlash(__('The Department sheet was updated successfully', true));
				$this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'index', $userId));
			} else {
				$this->Session->setFlash(__('Department Sheet was not updated. Please, try again.', true));
			}
		}

		$columns = $this->Sheet->Column->find('list');

		$this->Sheet->contain('Column.id');
		$this->data = $this->Sheet->find('first', array('conditions' => array('Sheet.id' => $id)));

		$selected_columns = Set::extract('/id', $this->data['Column']);
		//pr($selected_columns);exit

		$department = $this->Sheet->User->field('department_name', array('User.id' => $userId));
		$locked = Set::extract('/ColumnsSheet/locked', $this->data['Column']);
		$col_id = Set::extract('/ColumnsSheet/column_id', $this->data['Column']);

		$this->set(compact('id', 'columns', 'department', 'selected_columns', 'locked', 'col_id'));
	}//end admin_edit()


	/**
	 * Action for admin to delete a sheet
	 * 
	 * @param integer $sheetId Id of the sheet to be deleted
	 * @access public
	 * @return void
	 */
	function admin_delete($sheetId = null) {
		if (!$sheetId) {
			$this->Session->setFlash(__('Invalid sheet id', true));
			$this->redirect(array('action'=>'index'));
		}

		// Find the sheet user id
		$userId = $this->Sheet->field('user_id', array('Sheet.id' => $sheetId));

		// Logically delete the sheet and redirect to the user sheet listing page
		if ($this->Sheet->softDelete($sheetId)) {
			$this->Session->setFlash(__('Sheet deleted successfully', true));
			$this->redirect(array('action'=>'index', $userId));
		}

		$this->Session->setFlash(__('Sheet was not deleted, please try again.', true));
		$this->redirect(array('action' => 'index'));
	}//end admin_delete()


	/**
	 * Action for admin to view the Department sheet
	 * 
	 * @param int $sheetId The Department sheet ID to be viewed
	 * @access public
	 * @return void
	 */
	function admin_webform($sheetId) {
		/*if (!$sheetId) {
			$this->Session->setFlash(__('Invalid Department Sheet ID', true));
			$this->redirect(array('action' => 'index'));
		}*/
		// Set the ext-js layout for this action
		$this->layout = 'ext';
		// Set the debug mode to zero
		Configure::write('debug', 3);
		$sheet   = $this->Sheet->read(null, $sheetId);
		$columns = $sheet['Column'];
		//$lock_status = Set::extract('/locked', $sheet['Column']['ColumnsSheet']);
		$data    = $this->Sheet->getData($sheetId);
		$this->set(compact('sheet', 'columns', 'data', 'sheetId'));

	}//end admin_webform()


	/**
	 * Action for staff to view the Department sheet
	 * 
	 * @param int $sheetId The Department sheet ID to be viewed
	 * @access public
	 * @return void
	 */
	function staff_webform($sheetId) {
		/*if (!$sheetId) {
			$this->Session->setFlash(__('Invalid Department Sheet ID', true));
			$this->redirect(array('action' => 'index'));
		}*/

		// Set the ext-js layout for this action
		$this->layout = 'ext';

		// Set the debug mode to zero
		Configure::write('debug', 0);

		$sheet   = $this->Sheet->read(null, $sheetId);
		$columns = Set::extract('/name', $sheet['Column']);
		$data    = $this->Sheet->getData($sheetId);

		$this->set(compact('sheet', 'columns', 'data', 'sheetId'));

	}//end admin_webform()


	/**
	 * Action for client to view the Department sheet
	 * 
	 * @param int $sheetId The Department sheet ID to be viewed
	 * @access public
	 * @return void
	 */
	function client_webform($sheetId) {
		if (!$sheetId) {
			$this->Session->setFlash(__('Invalid Department Sheet ID', true));
			$this->redirect(array('action' => 'index'));
		}

		// Set the ext-js layout for this action
		$this->layout = 'ext';
		// Set the debug mode to zero
		Configure::write('debug', 0);

		$sheet   = $this->Sheet->read(null, $sheetId);
		$columns = Set::extract('/name', $sheet['Column']);
		$data    = $this->Sheet->getData($sheetId);

		$this->set(compact('sheet', 'columns', 'data', 'sheetId'));

	}//end client_webform()


	/**
	 * Action to load the department sheet data
	 * 
	 * @param int Sheet ID
	 * @access public
	 * @return void
	 */
	function admin_data($sheetId) {
		// Set the debug mode to zero
		Configure::write('debug', 0);
		$this->layout = false;
		$data = $this->Sheet->getData($sheetId);
		$this->set(compact('data'));
	}//end admin_data()


	/**
	 * The router for the ext-js actions
	 */
	function router() {
		$this->layout = false;
		Configure::write('debug', 0);
		$this->set('RAW_DATA', $GLOBALS['HTTP_RAW_POST_DATA']);
	}//end router()



	/**
	 * Method o check whether the user exists
	 *
	 * @param int $userId The user ID to be checked
	 * @access private
	 * @return void
	 */
	private function __check_user($userId) {
		if (!$this->Sheet->User->hasAny(array('User.id' => $userId, 'User.status' => 1))) {
			$this->Session->setFlash("Invalid User ID");
			$this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'users'));
		}
	}//end __check_user()
      

	  function updatePrimary(){
	      $this->autoRender = false;
	      $sheet_id = $_POST['sheetid'];
	      $column_id =$_POST['columnid'];
		  $row_id =$_POST['rowid'];

	      if(!empty($sheet_id) && !empty($column_id)){
		  $old_data =  $this->Formula->find('first',array('conditions'=>array('Formula.sheet_id'=>$sheet_id,'Formula.type'=>'main')));
		  $this->Formula->id = $old_data['Formula']['id'];
		  $this->Formula->saveField('type','');    
		  $data = $this->Formula->find('first',array('conditions'=>array('Formula.sheet_id'=>$sheet_id, 'Formula.column_id'=>$column_id,'Formula.row_id'=>$row_id)));
		  $this->Formula->id = $data['Formula']['id'];
		  $this->Formula->saveField('type','main');    
	      }
	}	

      function updateOrder($sheet_id=null){
		$this->autoRender = false;
		$array = $_POST['arrayorder'];
		$count = 1;
		foreach ($array as $idval) {
			$final_idval = explode('#',$idval);
			$this->Formula->updateAll(array('Formula.column_order'=>$count), array('Formula.column_id'=>$final_idval[0],'Formula.row_id'=>$final_idval[1],'Formula.sheet_id'=>$sheet_id));
			$count ++;
		}
		echo 'All saved! refresh the page to see the changes';
      }

      /*function to remove formula */
      function admin_remove($id=null){

	if(!empty($id)){
		  $this->Formula->delete($id);
		  $this->Session->setFlash("Formula Deleted !");
		$this->redirect($this->referer());
	}else{
		$this->Session->setFlash("Invalid Formula Selected");
		$this->redirect($this->referer());
	}
      }
      

}//end class
