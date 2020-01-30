<?php

class TemplatesController extends AppController {

    var $name = 'Templates';
    var $helpers = array('Html', 'Javascript', 'Session');
    var $components = array('Export');

    function beforeFilter() {
        parent::beforeFilter();   
        $this->Auth->allow('updateOrderFormula','updateOrder');
   }

    function admin_index() {
        if (!empty($this->data) && trim($this->data['Template']['value']) != '') {
            $conditions = array('Template.name LIKE' => "%" . $this->data['Template']['value'] . "%", 'Template.status !=' => 2);
        } else {
            $conditions = array('Template.status !=' => 2);
        }
        $userTemplates = $this->Template->find('all', array('conditions' => $conditions));
        $this->set('userTemplates', $userTemplates);
    }

    function admin_view($TemplateId = null) {
        if (!$TemplateId) {
            $this->Session->setFlash(__('Invalid Template ID', true));
            $this->redirect(array('action' => 'index'));
        }
        $Template = $this->Template->read(null, $TemplateId);
        $this->set('Template', $Template);
    }

    function admin_create_advance($userId=null,$department_id=null){
        $conditions = array('Template.status !=' => 2);
        $templates = $this->Template->find('list', array('conditions' => $conditions));
        $this->set('templates', $templates);
        $this->set('userId', $userId);
        
        if(!empty($this->data)){
            $conditions = "Template.id = '".$this->data['Template']['id']."'";
            $templates = $this->Template->find('first', array('conditions' => $conditions));
            $rows_data = $templates['ResultColumnTemplate'];
            $cols_data = $templates['ColumnsTemplate'];
            $segment_data = $templates['MarketSegmentsTemplate'];
            $columnIds = Set::extract('/column_id', $templates['ColumnsTemplate']);
            $columnIds = array_unique($columnIds);
            
            $rowIds = Set::extract('/column_id', $templates['ResultColumnTemplate']);
            $segmentIds = Set::extract('/market_segment_id', $templates['MarketSegmentsTemplate']);
            foreach ($this->data['Sheet']['departmentmonth']['month'] as $months) {
                $this->data['AdvancedSheet']['market_segments'] = implode(',',$segmentIds);
                $this->data['AdvancedSheet']['columns'] = implode(',',$columnIds);
                $this->data['AdvancedSheet']['month'] = $months;
                $this->data['AdvancedSheet']['template_id'] = $this->data['Template']['id'];
                $this->data['AdvancedSheet']['year'] = $this->data['Sheet']['departmentmonth']['year'];
                $this->data['AdvancedSheet']['user_id'] = $userId;
                $this->data['AdvancedSheet']['name'] = $this->data['Sheet']['name'];
                $this->data['AdvancedSheet']['department_id'] = $this->data['Sheet']['department_id'];
                $dept_id = $this->data['Sheet']['department_id'];                
                $this->AdvancedSheet = ClassRegistry::init('AdvancedSheet');
                $this->AdvancedSheet->create();
                if ($this->AdvancedSheet->saveAll($this->data)) {
                    $curr_sheet_id = $this->AdvancedSheet->getLastInsertID();
                    $rows_formula_obj = ClassRegistry::init('ResultColumnFormula');
                    if (!empty($templates['ResultColumnTemplate'])) {
                        foreach ($templates['ResultColumnTemplate'] as $rowsData) {
                            unset($rowsData['id']);
                            $rowsData['advanced_sheet_id'] = $curr_sheet_id;
                            $rows_formula_obj->create();
                            $rows_formula_obj->saveAll($rowsData);
                        }
                    }
                    $datas_obj = ClassRegistry::init('AdvanceData');
                    $numDays = date('t', mktime(0, 0, 0, $months, 1, $this->data['Sheet']['departmentmonth']['year']));
                    for ($i = 1; $i <= $numDays; $i++) {
                        foreach($segmentIds as $advArr){
                                foreach ($columnIds as $key => $columnId) {
                                    $datas['AdvanceData']['id'] = '';
                                    $datas['AdvanceData']['advanced_sheet_id'] = $curr_sheet_id;
                                    $datas['AdvanceData']['value'] = '0';
                                    $datas['AdvanceData']['row_id'] = '0';
                                    $datas['AdvanceData']['column_id'] = $columnId;
                                    $datas['AdvanceData']['date'] = $i;
                                    $datas['AdvanceData']['market_segment_id'] = $advArr;
                                    $datas_obj->create();
                                    $datas_obj->saveAll($datas['AdvanceData']);
                                }
                        }
                    }
                    
                    foreach($segmentIds as $advArr){
                            foreach ($columnIds as $key => $columnId) {
                                $datas['AdvanceData']['id'] = '';
                                $datas['AdvanceData']['advanced_sheet_id'] = $curr_sheet_id;
                                $datas['AdvanceData']['value'] = '0';
                                $datas['AdvanceData']['row_id'] = '0';
                                $datas['AdvanceData']['column_id'] = $columnId;
                                $datas['AdvanceData']['date'] = 'Total';
                                $datas['AdvanceData']['market_segment_id'] = $advArr;
                                $datas_obj->create();
                                $datas_obj->saveAll($datas['AdvanceData']);
                            }
                    }
                    
                    foreach($segmentIds as $advArr){
                         foreach ($rowIds as $key => $rowId) {
                                $datas['AdvanceData']['id'] = '';
                                $datas['AdvanceData']['advanced_sheet_id'] = $curr_sheet_id;
                                $datas['AdvanceData']['value'] = '0';
                                $datas['AdvanceData']['total_row_id'] = $rowId;
                                $datas['AdvanceData']['column_id'] = '0';
                                $datas['AdvanceData']['date'] = '0';
                                $datas['AdvanceData']['market_segment_id'] = $advArr;
                                $datas_obj->create();
                                $datas_obj->saveAll($datas['AdvanceData']);
                          }
                    }
                    
                }
            } //foreach ends here
            $this->Session->setFlash(__('The sheet has been saved', true));
        }
    }
    
    function admin_add() {
        
        if (!empty($this->data)) {    
           $this->Template->create();
             if ($this->Template->save($this->data)) {
                 $last_insert_id = $this->Template->getLastInsertId();
                 foreach ($this->data['Template']['MarketSegment'] as $mardata){
                     if(!empty($mardata) && $mardata != '0'){
                         $formulaData['MarketSegmentsTemplate']['id'] = '';
                         $formulaData['MarketSegmentsTemplate']['market_segment_id'] = $mardata;
                         $formulaData['MarketSegmentsTemplate']['template_id'] = $last_insert_id;
                         $this->Template->MarketSegmentsTemplate->save($formulaData);
                     }
                  }
                 
                 foreach ($this->data['Column']['Column'] as $coldata){
                     if(!empty($coldata) && $coldata != '0'){
                         $formulaData['ColumnsTemplate']['id'] = '';
                         $formulaData['ColumnsTemplate']['column_id'] = $coldata;
                         $formulaData['ColumnsTemplate']['template_id'] = $last_insert_id;
                         $this->Template->ColumnsTemplate->save($formulaData);
                     }
                 }
                if(!empty($this->data['Row']['Row'])){
                 foreach ($this->data['Row']['Row'] as $rowdata){
                     if(!empty($rowdata) && $rowdata != '0'){
                         $formulaData['ResultColumnTemplate']['id'] = '';
                         //$formulaData['ResultColumnTemplate']['column_id'] = '0';
                         $formulaData['ResultColumnTemplate']['column_id'] = $rowdata;
                         $formulaData['ResultColumnTemplate']['template_id'] = $last_insert_id;
                         $this->Template->ResultColumnTemplate->save($formulaData);
                     }
                 }
                }
                 
                $this->Session->setFlash(__('The Template has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Template could not be saved. Please, try again.', true));
            }
        }

        $columns = $this->Template->Column->find('list', array('conditions' => array('Column.status !=' => 2)));
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));
        $this->set(compact('columns','marketsegments'));
    }

    function admin_delete($TemplateId = null) {
        if (!$TemplateId) {
            $this->Session->setFlash(__('Invalid Template id', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Template->softDelete($TemplateId)) {
            $this->Session->setFlash(__('Template deleted successfully', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Template was not deleted, please try again.', true));
        $this->redirect(array('action' => 'index'));
    }

    function admin_edit($id = null) {
        
        //Configure::write('debug',2);
        
        $this->Template->resetBindings('Template');
        if (!empty($this->data)) {
            
            //echo '<pre>'; print_r($this->data); exit;
            
            $updatetemplate['Template']['id'] = $this->data['Template']['id'];
            $updatetemplate['Template']['name'] = $this->data['Template']['name'];
            if ($this->Template->save($updatetemplate)) {
                
                $previousResultCol = $this->Template->ResultColumnTemplate->find('list', array('conditions' => array('ResultColumnTemplate.status !=' => 2,'ResultColumnTemplate.template_id'=>$id),'fields'=>array('ResultColumnTemplate.column_id')));
                
                $this->Template->MarketSegmentsTemplate->deleteAll(array('MarketSegmentsTemplate.template_id' => $id));
                $this->Template->ColumnsTemplate->deleteAll(array('ColumnsTemplate.template_id' => $id));
                  foreach ($this->data['MarketSegment']['MarketSegment'] as $mardata){
                      if(!empty($mardata) && $mardata != '0'){
                         $formulaData['MarketSegmentsTemplate']['id'] = '';
                         $formulaData['MarketSegmentsTemplate']['market_segment_id'] = $mardata;
                         $formulaData['MarketSegmentsTemplate']['template_id'] = $id;
                         $this->Template->MarketSegmentsTemplate->save($formulaData);
                      }
                  }
                 
                 $order='1';
                 foreach ($this->data['Column']['Column'] as $coldata){
                     if(!empty($coldata) && $coldata != '0'){
                         $formulaData['ColumnsTemplate']['id'] = '';
                         $formulaData['ColumnsTemplate']['order'] = $order;
                         $formulaData['ColumnsTemplate']['column_id'] = $coldata;
                         $formulaData['ColumnsTemplate']['template_id'] = $id;
                         $this->Template->ColumnsTemplate->save($formulaData);
                         $order++;
                     }
                 }
                 
                 $newResCol = array();
                if(!empty($this->data['Row']['Row'])){
                 foreach ($this->data['Row']['Row'] as $rowdata){
                     if(!empty($rowdata) && $rowdata != '0'){
                         $newResCol[] = $rowdata;
                         $formulaData['ResultColumnTemplate']['column_id'] = $rowdata;
                         $formulaData['ResultColumnTemplate']['template_id'] = $id;
                         $check = $this->Template->ResultColumnTemplate->find('first', array('conditions' => array('ResultColumnTemplate.status !=' => 2,'ResultColumnTemplate.column_id'=>$rowdata,'ResultColumnTemplate.template_id'=>$id),'fields'=>array('ResultColumnTemplate.id')));
                         if(!empty($check)){
                             $formulaData['ResultColumnTemplate']['id'] = $check['ResultColumnTemplate']['id'];
                         }else{
                             $formulaData['ResultColumnTemplate']['id'] = '';
                         }
                          $formulaData['ResultColumnTemplate']['is_locked'] = '0';
                         $this->Template->ResultColumnTemplate->save($formulaData);
                     }
                 }
                }
                
                foreach ($this->data['Row']['Locked'] as $ky => $vl) {
                    if ($vl > 0) {
                        $cond = "template_id = {$id} AND column_id = {$vl}";
                        $arr = $this->Template->ResultColumnTemplate->find('first', array("conditions" => $cond));
                        $arr['ResultColumnTemplate']['is_locked'] = '1';
                        $this->Template->ResultColumnTemplate->save($arr);
                    }
                }

                $deleteResCol = array();
                foreach ($previousResultCol as $oldCol){
                  //  echo $oldCol;
                 if(!in_array($oldCol,$newResCol)){
                                $deleteResCol[] = $oldCol;
                        }
                }
                
//                echo '<pre>'; //print_r($this->data['Row']['Row']);  
//                echo "New Rows:"; print_r($newResCol); 
//                echo 'deletRow:'; print_r($deleteResCol); 
//                echo 'Previous:'; print_r($previousResultCol); 
//                exit;
                
                if(!empty ($deleteResCol)){
                    foreach($deleteResCol as $del_Col){
                        $this->Template->ResultColumnTemplate->deleteAll(array('ResultColumnTemplate.column_id' => $del_Col, 'ResultColumnTemplate.template_id' => $id));
                    }
                }
            
                $this->Session->setFlash(__('The Template was updated successfully', true));
                $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'Templates', 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('Template was not updated. Please, try again.', true));
            }
        }

        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $columns = $this->Template->Column->find('list', array('conditions' => array('Column.status !=' => 2)));
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));        
        $this->data = $this->Template->find('first', array('conditions' => array('Template.id' => $id)));
        $total_columns = $this->Template->ColumnsTemplate->find('all', array('conditions' => array('ColumnsTemplate.template_id' => $id), 'order' => array('ColumnsTemplate.order ASC')));
        $selected_columns = Set::extract('/ColumnsTemplate/column_id', $total_columns);
        $selected_rows = Set::extract('/column_id', $this->data['ResultColumnTemplate']);        
        $row_id = Set::extract('/column_id', $this->data['ResultColumnTemplate']);
        $rowlocked = Set::extract('/is_locked', $this->data['ResultColumnTemplate']);
        $col_id = Set::extract('/column_id', $this->data['ColumnsTemplate']);
        $selected_marketsegments = Set::extract('/market_segment_id', $this->data['MarketSegmentsTemplate']); 
        $this->set(compact('id', 'total_columns', 'columns', 'rows','marketsegments','rowlocked', 'selected_marketsegments', 'selected_columns', 'selected_rows','col_id', 'row_id', 'userId'));
    }

    function admin_formula($TemplateId=null){
        $template = $this->Template->find($TemplateId);
        $formulas = $template['ResultColumnTemplate'];
        $SheetColumns = $this->Template->ResultColumnTemplate->find('all', array("conditions"=>"template_id = {$TemplateId}"));
        $columnIds = Set::extract('/ResultColumnTemplate/column_id', $SheetColumns);
        $idstr = implode(',',$columnIds);
        $total_columns = $this->Template->ColumnsTemplate->find('all', array('conditions' => array('ColumnsTemplate.template_id' => $TemplateId)));
        $selected_columns = Set::extract('/ColumnsTemplate/column_id', $total_columns);
        $selected_columnsidstr = implode(',',$selected_columns);
        $total_columns = array();
        if(!empty($selected_columnsidstr))
        {
                $columns_data = $this->Template->Column->find('all', array('conditions'=>"id in({$selected_columnsidstr})"));
                foreach($columns_data as $cols){
                  if($cols['Column']['status'] != 2){
                      $total_columns[$cols['Column']['id']] = $cols['Column']['name'];
                  }
                }
        }
        $rest_formula = array();
        if(!empty($idstr))
        {
                $columns_data = $this->Template->Column->find('all', array('conditions'=>"id in({$idstr})"));
                foreach($columns_data as $cols){
                  if($cols['Column']['status'] != 2){
                      $columns[$cols['Column']['id']] = $cols['Column']['name'];
                      $total_columns[$cols['Column']['id']] = $cols['Column']['name'];
                  }
                }
        }else
        {
                $columns = "";
        }
        $operators = array(
                        "0" => "+",
                        "1" => "-",
                        "2" => "*",
                        "3" => "/",
                        "4" => "(",
                        "5" => ")"
                );
        
        $all_columns = $this->Template->Column->find('list');
        $a_column = array();
        $a_col_keys = array();
        $a_col_values = array();
        
        foreach($all_columns as $ckey=>$cvalue)
        {
                $a_column['C'.$ckey] = $cvalue;
        }
        foreach($a_column as $ke=>$va)
        {
                $a_col_keys[] = $ke;
                $a_col_values[] = $va;
        }
        $a_col_keys = array_reverse($a_col_keys);
        $a_col_values = array_reverse($a_col_values);
        
        $all_formulas = $this->Template->ResultColumnTemplate->find('all',array('conditions'=>array('ResultColumnTemplate.template_id'=>$TemplateId), 'order'=>array('ResultColumnTemplate.order'=>'ASC')));

                $cal_formula = array();
		for($i=0;$i<count($all_formulas);$i++){
                    $column_data = $this->Template->Column->findById($all_formulas[$i]['ResultColumnTemplate']['column_id'],array('fields'=>'Column.name'));
                    $cal_formula[$i]['res'] = $column_data['Column']['name'];
                    $rest_formula[] = str_replace($a_col_keys,$a_col_values,$all_formulas[$i]['ResultColumnTemplate']['formula']);
                    foreach($operators as $key=>$value){
                            $temp_formula = explode($value,$all_formulas[$i]['ResultColumnTemplate']['formula']);
                            if(count($temp_formula)>1){
                                    $val = 0;
                                    foreach($temp_formula as $tfmla){
                                            $temp_formula2 = explode('C',$tfmla);
                                            if(!empty($temp_formula2[1]))
                                            {
                                            $column_data = $this->Template->Column->findById($temp_formula2[1],array('fields'=>'Column.name'));
                                            $cal_formula[$i]['formula'][] = $column_data['Column']['name'];
                                            if($val != count($temp_formula)-1)
                                            $cal_formula[$i]['formula'][] = $value;
                                            $val++;
                                            }
                                    }
                            }
                     }
		}

		$str_formula = array();
		for($i=0;$i<count($cal_formula);$i++){
                    if(!empty($rest_formula[$i])){
			$str = $cal_formula[$i]['res'].' = ';
			$str.= $rest_formula[$i];
                            $str_formula[$all_formulas[$i]['ResultColumnTemplate']['column_id']]=$str;
                    }
		}

                $final_str_order = $this->Template->ResultColumnTemplate->find ('all', array('conditions'=> array('ResultColumnTemplate.template_id'=>$TemplateId),'fields'=> array('ResultColumnTemplate.column_id','ResultColumnTemplate.id'), 'order'=>array('ResultColumnTemplate.column_id'=>'ASC')));
		$formula_ids = array();
		foreach($final_str_order as $formulas){
		    $formula_ids[$formulas['ResultColumnTemplate']['column_id']] = $formulas['ResultColumnTemplate']['id'];
		}
                $this->set(compact('formula_ids'));
		$this->set('all_formulas',$str_formula);
                $this->set(compact('template', 'TemplateId','formulas','columns','operators','total_columns'));
    }
    
    
 function updateOrder($template_id=null) {
        $this->autoRender = false;
        $array = $_POST['arrayorder'];
        $count = 1;
        $total_columns = $this->Template->ColumnsTemplate->find('all', array('conditions' => array('ColumnsTemplate.template_id' => $template_id)));
        foreach ($array as $idval) {
            $final_idval = explode('#', $idval);
            $this->Template->ColumnsTemplate->updateAll(array('ColumnsTemplate.order' => $count), array('ColumnsTemplate.column_id' => $final_idval[0], 'ColumnsTemplate.template_id' => $template_id));
            foreach ($total_columns as $cols) {
                if ($final_idval[0] == $cols['ColumnsTemplate']['column_id']) {
                    $count++;
                }
            }
        }
        echo 'All saved! refresh the page to see the changes';
    }

    
    function admin_add_formula() {
		if(!empty($this->data)){
		if(!empty($this->data['Template']['Formula']))
		{
			$operators = array(
					"0" => "+",
					"1" => "-",
					"2" => "*",
					"3" => "/",
					"4" => "(",
					"5" => ")"
				);
		
                        
			$temp_fromula_array = array();
			$arrresult = explode(" = ", $this->data['Template']['Formula']);
			$res_column_name = implode(" ", explode("_", $arrresult[0]));

                        $res_column = $this->Template->Column->find('first',array('conditions'=>array('name'=>$res_column_name , 'status !='=>2)));
                        $res_row_id = 0;
			
			$res_column_id = $res_column['Column']['id'];
			$arrtemp = explode(" ", $arrresult[1]);

			$front_column_name_array =array();
			foreach($arrtemp as $key=>$value){
				if($value != ""){

					if(in_array($value, $operators)){ //if it is an operator
						array_push($temp_fromula_array, $value);
					}else{ //if it is a column name
						$column_name = implode(" ", explode("_", $value));

						$front_column_name = str_replace("[","",str_replace("]","",$column_name));
						$front_column_name_array[] = $front_column_name;

						$column = $this->Template->Column->find('first',array('conditions'=>array('name'=>$column_name , 'status !='=>2)));

						if(!empty($column)){
						    array_push($temp_fromula_array, "C".$column['Column']['id']);
						}else{
						      array_push($temp_fromula_array, $value);
						}
					}
				}
			}
			
			$arr_formula = array();
			$arr_formula['ResultColumnTemplate']['template_id'] = $this->data['Template']['id'];
			$arr_formula['ResultColumnTemplate']['column_id'] = $res_column_id;
			$arr_formula['ResultColumnTemplate']['formula'] = implode(" ", $temp_fromula_array);
                        
			$formula_current = $this->Template->ResultColumnTemplate->find('first',array('conditions'=>array('ResultColumnTemplate.template_id'=>$this->data['Template']['id'],'ResultColumnTemplate.column_id'=>$res_column_id)));
			
			if(!empty($formula_current)){		
			  $arr_formula['ResultColumnTemplate']['id'] = $formula_current['ResultColumnTemplate']['id'];
			}
			if($this->Template->ResultColumnTemplate->save($arr_formula)){
				$this->Session->setFlash(__('Formula created and saved successfully.', true));
				$this->redirect(array('action' => 'index'));
			}else{
				$this->Session->setFlash(__('Formula could not be saved.', true));
				$this->redirect(array('action' => 'index'));
			}

		}else
		{
			$this->Session->setFlash(__('Formula could not be Empty.', true));
			$this->redirect(array('action' => 'admin_index'));
		}
	}

	}

         function admin_remove($id=null){
            if(!empty($id)){
                $formula['ResultColumnTemplate']['id'] = $id;
                $formula['ResultColumnTemplate']['formula'] = '';
                $this->Template->ResultColumnTemplate->save($formula);
                $this->Session->setFlash("Formula Deleted !");
                $this->redirect($this->referer());
            }else{
                $this->Session->setFlash("Invalid Formula Selected");
                $this->redirect($this->referer());
            }
      }
      
      function updateOrderFormula($template_id=null){
		$this->autoRender = false;
		$array = $_POST['arrayorder'];
		$count = 1;
		foreach ($array as $idval) {
			$final_idval = explode('#',$idval);
			$this->Template->ResultColumnTemplate->updateAll(array('ResultColumnTemplate.order'=>$count), array('ResultColumnTemplate.column_id'=>$final_idval[0],'ResultColumnTemplate.template_id'=>$template_id));
			$count ++;
		}
		echo 'All saved! refresh the page to see the changes';
      }
    
}
//end class