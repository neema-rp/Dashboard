<?php
class AdvancedSheetsController extends AppController {

    var $name = 'AdvancedSheets';
    var $helpers = array('Html', 'Javascript', 'Session');
    var $components = array('Export');

    function beforeFilter() {
        parent::beforeFilter();     
        $this->Auth->allow('calculate_string','updateOrderFormula','updateOrder','update_result_cols','update_sum_total','admin_update_data','admin_update_result','admin_update_total','admin_export_csv','admin_export_pdf');
    }

    function admin_index($userId, $dept_id=null) {
        if (!empty($this->data) && trim($this->data['AdvancedSheet']['value']) != '') {
            $conditions = array('AdvancedSheet.name LIKE' => "%" . $this->data['AdvancedSheet']['value'] . "%", 'AdvancedSheet.user_id LIKE' => "%" . $userId . "%", 'AdvancedSheet.department_id' => $this->params['pass'][1], 'AdvancedSheet.status !=' => 2);
        } else {
            //$conditions = array('AdvancedSheet.user_id LIKE' => "%" . $userId . "%", 'AdvancedSheet.status !=' => 2, 'AdvancedSheet.department_id' => $this->params['pass'][1]);
            $conditions = array('AdvancedSheet.status !=' => 2, 'AdvancedSheet.department_id' => $this->params['pass'][1]);
        }
        
        $userAdvancedSheets = $this->AdvancedSheet->find('all', array('conditions' => $conditions, 'contain' => array('User'), 'order' => array('AdvancedSheet.year ASC', 'AdvancedSheet.month ASC')));
        
        $depts_obj = ClassRegistry::init('DepartmentsUser');
        $department = $depts_obj->field('department_name', array('DepartmentsUser.user_id LIKE' => "%" . $userId . "%", 'DepartmentsUser.department_id' => $this->params['pass'][1]));
        $mAdvancedSheets = $this->AdvancedSheet->find('all', array('conditions' => $conditions, 'fields' => array('DISTINCT AdvancedSheet.year, AdvancedSheet.month'), 'recursive' => -1, 'order' => 'AdvancedSheet.year ASC'));
        $this->set(compact('userAdvancedSheets', 'userId', 'department', 'dept_id', 'mAdvancedSheets'));
        $last_AdvancedSheet = $this->AdvancedSheet->find('first', array('conditions' => array('AdvancedSheet.status !=' => 2, 'AdvancedSheet.department_id' => $this->params['pass'][1]), 'fields' => array('AdvancedSheet.id'), 'recursive' => -1, 'order' => 'AdvancedSheet.year DESC, AdvancedSheet.month DESC'));
        $this->set('last_AdvancedSheet', $last_AdvancedSheet);
    }

    
    function admin_edit($sheetId){
        if(!empty($this->data)){
            //echo '<pre>'; print_r($this->data); exit;
            
            $this->data['AdvancedSheet']['month'] = $this->data['AdvancedSheet']['month']['month'];
            $this->data['AdvancedSheet']['year'] = $this->data['AdvancedSheet']['year']['year'];
            
            if($this->AdvancedSheet->saveAll($this->data)){
                $this->Session->setFlash(__('Segmentation updated successfully', true));
            }else{
                $this->Session->setFlash(__('Unable to update Segmentation', true));
            }
            
            $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'AdvancedSheets', 'action' => 'index', $this->data['AdvancedSheet']['user_id'], $this->data['AdvancedSheet']['department_id']));
            
        }else{
             $this->data = $this->AdvancedSheet->find('first', array('conditions' => array('AdvancedSheet.id' => $sheetId)));
        }
        
    }
    
    function admin_segments($sheetId){
        $conditions = array('AdvancedSheet.id'=>$sheetId);
        $this->AdvancedSheet->recursive = '-1';
        $data = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
        $sheet_segments = explode(',',$data['AdvancedSheet']['market_segments']);
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));
        $this->set(compact('data','sheet_segments','marketsegments'));
        if(!empty($this->data)){
            $addSegments = array(); $deleteSegments = array();
            $updatedSegments = array();
            $previousSegments = explode(',',$this->data['AdvancedSheet']['previous_segments']);
            if($this->data['MarketSegment']['MarketSegment']){
                    foreach($this->data['MarketSegment']['MarketSegment'] as $segments){
                        if(!in_array($segments,$previousSegments)){
                            if($segments != '0'){
                                $addSegments[] = $segments;
                            }
                        }
                     if($segments != '0'){
                         $updatedSegments[] = $segments;
                     }
                    }
            }
            foreach ($previousSegments as $oldSeg){
                 if(!in_array($oldSeg,$this->data['MarketSegment']['MarketSegment'])){
                            $deleteSegments[] = $oldSeg;
                    }
            }
            if(!empty ($deleteSegments)){
                foreach($deleteSegments as $del_Seg){
                    //delete code for this segment in AdvanceData
                    $this->AdvancedSheet->AdvanceData->deleteAll(array('AdvanceData.market_segment_id' => $del_Seg, 'AdvanceData.advanced_sheet_id' => $this->data['AdvancedSheet']['id']));
                }
            }
            $conditions = array('AdvancedSheet.id'=>$this->data['AdvancedSheet']['id']);
            $this->AdvancedSheet->recursive = '-1';
            $sheetData = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
            if(!empty ($addSegments)){
                //add code for this segment in AdvanceData
                
                $rows_obj = ClassRegistry::init('ResultColumnFormula');
                $rows_data = $rows_obj->find('all',array('conditions'=>array('advanced_sheet_id'=>$this->data['AdvancedSheet']['id']),'fields'=>array('ResultColumnFormula.column_id'),'order'=>  array('order ASC'),'recursive'=>-1));
                $rowIds = Set::extract('/ResultColumnFormula/column_id',$rows_data);
                
                $columnIds = explode(',',$sheetData['AdvancedSheet']['columns']);
                
                $numDays = date('t', mktime(0, 0, 0, $sheetData['AdvancedSheet']['month'], 1, $sheetData['AdvancedSheet']['year']));
                for ($i = 1; $i <= $numDays; $i++) {
                    foreach($addSegments as $add_Seg){
                            foreach ($columnIds as $key => $columnId) {
                                unset($datas);
                                $datas['AdvanceData']['id'] = '';
                                $datas['AdvanceData']['advanced_sheet_id'] = $this->data['AdvancedSheet']['id'];
                                $datas['AdvanceData']['value'] = '0';
                                $datas['AdvanceData']['row_id'] = '0';
                                $datas['AdvanceData']['column_id'] = $columnId;
                                $datas['AdvanceData']['date'] = $i;
                                $datas['AdvanceData']['market_segment_id'] = $add_Seg;
                                $this->AdvancedSheet->AdvanceData->saveAll($datas['AdvanceData']);
                            }
                    }
                }
                
                foreach($addSegments as $add_Seg){
                       foreach ($columnIds as $key => $columnId) {
                            unset($datas);
                            $datas['AdvanceData']['id'] = '';
                            $datas['AdvanceData']['advanced_sheet_id'] = $this->data['AdvancedSheet']['id'];
                            $datas['AdvanceData']['value'] = '0';
                            $datas['AdvanceData']['row_id'] = '0';
                            $datas['AdvanceData']['column_id'] = $columnId;
                            $datas['AdvanceData']['date'] = 'Total';
                            $datas['AdvanceData']['market_segment_id'] = $add_Seg;
                            $this->AdvancedSheet->AdvanceData->saveAll($datas['AdvanceData']);
                        }
                }
                
                foreach($addSegments as $advArr){
                         foreach ($rowIds as $key => $rowId) {
                                unset($datas);
                                $datas['AdvanceData']['id'] = '';
                                $datas['AdvanceData']['advanced_sheet_id'] = $this->data['AdvancedSheet']['id'];
                                $datas['AdvanceData']['value'] = '0';
                                $datas['AdvanceData']['total_row_id'] = $rowId;
                                $datas['AdvanceData']['column_id'] = '0';
                                $datas['AdvanceData']['date'] = '0';
                                $datas['AdvanceData']['market_segment_id'] = $advArr;
                                $this->AdvancedSheet->AdvanceData->saveAll($datas['AdvanceData']);
                          }
                    }
            }
            $this->data['AdvancedSheet']['market_segments'] = implode(',',$updatedSegments);
            if($this->AdvancedSheet->save($this->data)){
                            $this->Session->setFlash(__('Market Segments Updated successfully.', true));
                            $this->redirect('/admin/advancedSheets/index/'.$sheetData['AdvancedSheet']['user_id'].'/'.$sheetData['AdvancedSheet']['department_id']);
            }
        }//end not empty
    }//end function
    
    
    function admin_webform_back($sheetId){
        $conditions = array('AdvancedSheet.id'=>$sheetId);
        $this->AdvancedSheet->contain(array('Column','Row','AdvanceData','Template','User'));
        $data = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
        $sheet_name = $data['AdvancedSheet']['name'];
        $user_id = $data['AdvancedSheet']['user_id'];
        $department_id = $data['AdvancedSheet']['department_id'];
        $dept_obj = ClassRegistry::init('Department');
	$dept_name = $dept_obj->field('name',array('id'=>$department_id));
        $username = $data['User']['username'];
        $this->set('sheet_name',$sheet_name);
        $this->set('dept_name',$dept_name);
        $this->set('username',$username);
        $rows_obj = ClassRegistry::init('ResultColumnFormula');
        $rows_data = $rows_obj->find('all',array('conditions'=>array('advanced_sheet_id'=>$sheetId),'fields'=>array('ResultColumnFormula.column_id','ResultColumnFormula.is_locked','ResultColumnFormula.formula'),'order'=>  array('order ASC'),'recursive'=>-1));
        $rowIds = Set::extract('/ResultColumnFormula/column_id',$rows_data);
        $lockedRows = Set::extract('/ResultColumnFormula/is_locked',$rows_data);
        $rowsFormulas = Set::extract('/ResultColumnFormula/formula',$rows_data);
        $this->Column = ClassRegistry::init('Column');
        $column_data = $this->Column->find('list',array('conditions'=>array('Column.id'=>$rowIds , 'Column.status !='=>2)));
        $lockedIds = array();
        foreach($lockedRows as $key => $locked){
            if($locked == '1'){
                $lockedIds[] = $column_data[$rowIds[$key]];
            }
        }
        $totalDataColumns = Set::extract('/total_row_id', $data['AdvanceData']);
        $dates  = Set::extract('/date', $data['AdvanceData']);
        $values = Set::extract('/value', $data['AdvanceData']);
        $dataColumns = Set::extract('/column_id', $data['AdvanceData']);
        $dataRows = Set::extract('/row_id', $data['AdvanceData']);
        
        $marketSegmentIds = Set::extract('/market_segment_id', $data['AdvanceData']);
       // $marketSegmentIds = explode(',',$data['AdvancedSheet']['market_segments']);
        
        
        $columnIds = Set::extract('/id', $data['Column']);
        $columns = Set::extract('/name', $data['Column']);
        $numDays = date('t', mktime(0, 0, 0, $data['AdvancedSheet']['month'], 1, $data['AdvancedSheet']['year']));
        $total_maket_segments = array_unique($marketSegmentIds);
        $newCols = array();
        
        foreach($columnIds as $col_key=>$col_val)
        {
            $newCols[$col_val] = $columns[$col_key];
        }
        $columnIdsAr = array_unique($columnIds);
        asort($columnIdsAr);
        //asort($total_maket_segments);
        $newSegments = array();
        foreach($total_maket_segments as $seg_key=>$seg_val)
        {
            if(!empty($seg_val)){
            $ms_obj = ClassRegistry::init('MarketSegment');
            $marketsegments = $ms_obj->find('first', array('conditions' => array('MarketSegment.id' => $seg_val),'fields'=>array('name')));
            $newSegments[$seg_val] = $marketsegments['MarketSegment']['name'];
            }
        }
       
        //echo '<pre>'; print_r($total_maket_segments); print_r($newSegments); echo '</pre>';
        
        
        $final_array  = array(); 
        $final_array_total = array();
        for ($i = 1; $i <= $numDays; $i++) {
                foreach($columnIdsAr as $key =>$colsArr){
                       $dateKeys = array_keys($dates, $i);        
                       $dateKeysUpdated = array();
                       foreach($dateKeys as $keys=>$new_data){
                           if($dataColumns[$new_data] ==  $colsArr){
                               $dateKeysUpdated[] = $new_data;
                            }
                       }
                       
                       $mk = '0';
                        foreach($total_maket_segments as $advArr){
                                if(!empty($advArr)){
                                  $j = $dateKeysUpdated[$mk];
                                  $final_array[$i.'/'.$data['AdvancedSheet']['month'].'/'.$data['AdvancedSheet']['year']][$newCols[$colsArr]][$advArr] = $values[$j];
                                  $mk++;
                                }
                        }
                }
        }
        
        foreach($columnIdsAr as $key =>$colsArr){
               $dateKeys = array_keys($dates, 'Total');
               $dateKeysUpdated = array();
               foreach($dateKeys as $keys=>$new_data){
                   if($dataColumns[$new_data] ==  $colsArr){
                       $dateKeysUpdated[] = $new_data;
                    }
               }
               $mk = '0';
                foreach($total_maket_segments as $advArr){
                    if(!empty($advArr)){
                          $j = $dateKeysUpdated[$mk];
                          $new_total_array['Total'][$newCols[$colsArr]][$advArr] = $values[$j];
                          $mk++;
                    }
                }
        }
        $total_rows_array = array();
        if(!empty($rowIds)){
            foreach($rowIds as $rows){
                
                    $dateKeys = array_keys($dates, '0');
                    $dateKeysUpdated = array();
                     foreach($dateKeys as $keys=>$new_data){
                           if($totalDataColumns[$new_data] ==  $rows){
                               $dateKeysUpdated[] = $new_data;
                            }
                     }
                     //make array with segments
                    $mk = '0';
                    foreach($total_maket_segments as $advArr){
                        if(!empty($advArr)){
                      $j = $dateKeysUpdated[$mk];
                      $total_rows_array['Total'][$column_data[$rows]][$advArr] = $values[$j];
                      $mk++;
                        }
                    }                    
            }
        }
        
        $this->set('final_array',$final_array);
        $this->set('columns',$newCols);
        $this->set('marketSegments',$newSegments);
        $this->set('final_array_total',$new_total_array);
        $this->set('sheetId',$sheetId);
        $this->set('total_rows_array',$total_rows_array);
        $this->set('lockedIds',$lockedIds);
        $this->set('data',$data);
        
    }
    
    function admin_update_data($sheet_id,$date,$col_id,$market_segment_id,$value){
        $this->autoRender = false;
        $this->layout = false;
        $search_column_data = $this->AdvancedSheet->Column->find('first',array('conditions'=>array('Column.name'=>$col_id , 'Column.status !='=>2),'fields'=>array('Column.id'),'recursive'=>-1));
        $colId = $search_column_data['Column']['id'];
        $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'column_id'=>$colId,'row_id'=>0,'market_segment_id'=>$market_segment_id,'date'=>$date),'fields'=>array('AdvanceData.id'),'recursive'=>-1));
        $data_row_id = $cols_data['AdvanceData']['id'];
        $save_value['AdvanceData']['id'] = $data_row_id;
        $save_value['AdvanceData']['value'] = $value;
        $this->AdvancedSheet->AdvanceData->save($save_value);
        return true;
    }
    
    function admin_update_result($sheet_id,$col_id,$market_segment_id,$value){
        //Configure::write(debug,'2');
        $this->autoRender = false;
        $this->layout = false;
        $search_column_data = $this->AdvancedSheet->Column->find('first',array('conditions'=>array('Column.name'=>$col_id , 'Column.status !='=>2),'fields'=>array('Column.id'),'recursive'=>-1));
        $colId = $search_column_data['Column']['id'];
        $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'total_row_id'=>$colId,'date'=>'0','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.id'),'recursive'=>-1));
        $data_row_id = $cols_data['AdvanceData']['id'];
        $save_value['AdvanceData']['id'] = $data_row_id;
        $save_value['AdvanceData']['value'] = $value;
        $this->AdvancedSheet->AdvanceData->save($save_value);
        
        //check for formulas and update that values
        $rows_obj = ClassRegistry::init('ResultColumnFormula');
        $formula_details = $rows_obj->find('all',array('conditions'=>array('ResultColumnFormula.advanced_sheet_id'=>$sheet_id,'ResultColumnFormula.formula !='=>''),'order'=>array('ResultColumnFormula.order ASC'),'fields'=>array('ResultColumnFormula.column_id','ResultColumnFormula.formula')));
        $sheetData = $this->AdvancedSheet->find('first', array('conditions' => array('AdvancedSheet.id' => $sheet_id), 'fields' => array('AdvancedSheet.columns')));
        $sheetColumnsTotal = explode(',',$sheetData['AdvancedSheet']['columns']);
        $this->Column = ClassRegistry::init('Column');
        $return_array = array();
        
        //$formula_details['-1']['ResultColumnFormula']['column_id'] = '69'; //Rev Fcst
        //$formula_details['-1']['ResultColumnFormula']['formula'] = 'C65 * C63'; //ADR F'cst * Fcst Rooms
        //Fcst Revenue = Fsct Bob * Fcst ADR
        
        $formula_details['-2']['ResultColumnFormula']['column_id'] = '68'; //Revenue
        $formula_details['-2']['ResultColumnFormula']['formula'] = 'C62 * C64'; //BOB * ADR
        
        $i = '0';
        //asort($formula_details);
        foreach($formula_details as $formulaData){
                if(!empty($formulaData['ResultColumnFormula']['formula'])){                    
                   $save_data = array();
             
                 $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'total_row_id'=>$formulaData['ResultColumnFormula']['column_id'],'date'=>'0','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.id'),'recursive'=>-1));
                 $data_row_id = $cols_data['AdvanceData']['id'];

                 $save_data['AdvanceData']['id'] = $data_row_id;

                    $column_data = $this->Column->find('first',array('conditions'=>array('Column.id'=>$formulaData['ResultColumnFormula']['column_id'] , 'Column.status !='=>2),'fields'=>array('Column.name')));
                    $colname = $column_data['Column']['name'];
                    
                        $arr_formula_val = explode(" ", $formulaData['ResultColumnFormula']['formula']);
				$arr_indx = 0;
				foreach($arr_formula_val as $val){
					  if(substr($val, 0,1) == "C"){
                                                $formula_col_id = substr($val,1);
                                                if(in_array($formula_col_id,$sheetColumnsTotal)){
                                                    //this is total value
                                                    if($formula_col_id == '64'){
                                                        //calculate ADR value as Revenue/BOB
                                                        $sum_bob = $this->AdvancedSheet->AdvanceData->find('first', array(
                                                            'conditions' => array(
                                                            'AdvanceData.advanced_sheet_id' => $sheet_id,'AdvanceData.column_id' => '62','AdvanceData.date' => 'Total','AdvanceData.market_segment_id' => $market_segment_id),
                                                            'fields' => array('AdvanceData.value'
                                                            )
                                                        )
                                                        );
                                                        $all_day_adr = $this->AdvancedSheet->AdvanceData->find('all', array(
                                                            'conditions' => array(
                                                            'AdvanceData.advanced_sheet_id' => $sheet_id,'AdvanceData.column_id' => '64','AdvanceData.date !=' => 'Total','AdvanceData.market_segment_id' => $market_segment_id),
                                                            'fields' => array('AdvanceData.value')
                                                        )
                                                       );
                                                        $all_day_bob = $this->AdvancedSheet->AdvanceData->find('all', array(
                                                            'conditions' => array(
                                                            'AdvanceData.advanced_sheet_id' => $sheet_id,'AdvanceData.column_id' => '62','AdvanceData.date !=' => 'Total','AdvanceData.market_segment_id' => $market_segment_id),
                                                            'fields' => array('AdvanceData.value')
                                                        )
                                                       );
                                                        
                                                      $revenueFinal = '0';
                                                      if(!empty($all_day_bob) && !empty($all_day_adr)){
                                                          foreach($all_day_bob as $bobkey => $bobval){
                                                               $revenueFinal = $revenueFinal + ($bobval['AdvanceData']['value'] * $all_day_adr[$bobkey]['AdvanceData']['value']);
                                                          }
                                                      }                                    
                                                      $acutal_val = $revenueFinal/$sum_bob['AdvanceData']['value'];
                                                      $acutal_val = number_format($acutal_val, 2);
                                                        
                                                    }else{
                                                        $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'column_id'=>$formula_col_id,'date'=>'Total','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.value'),'recursive'=>-1));
                                                        $acutal_val = $cols_data['AdvanceData']['value'];
                                                    }
                                                }else{
                                                    //this is result Total column
                                                    $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'total_row_id'=>$formula_col_id,'date'=>'0','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.value'),'recursive'=>-1));
                                                    $acutal_val = $cols_data['AdvanceData']['value'];
                                                }
        					$arr_formula_val[$arr_indx] = $acutal_val;
					}
					$arr_indx += 1;
				}
				 $math_string = implode("",$arr_formula_val);
				 $final_val = $this->calculate_string($math_string);
				 
                                 $save_data['AdvanceData']['value'] = $final_val;
                                 
                                 $return_array[$i]['id'] =  str_replace(' ','-',$colname).'_'.$market_segment_id;
                                 if($colname == 'Sell Rate'){
                                     $return_array[$i]['value'] = number_format($final_val,'2');
                                 }else{
                                     $return_array[$i]['value'] = $final_val;
                                 }
                                 
                                 //save value
                                 $this->AdvancedSheet->AdvanceData->save($save_data);
			}
                    $i++;
        }
        return json_encode($return_array);
    }
    
    //function admin_update_total($sheet_id='74',$col_id='BOB',$market_segment_id='105',$value='2'){
    function admin_update_total($sheet_id,$col_id,$market_segment_id,$value){
        
        $this->autoRender = false;
        $this->layout = false;
        
        $search_column_data = $this->AdvancedSheet->Column->find('first',array('conditions'=>array('Column.name'=>$col_id , 'Column.status !='=>2),'fields'=>array('Column.id'),'recursive'=>-1));
        $colId = $search_column_data['Column']['id'];
        
        $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'column_id'=>$colId,'date'=>'Total','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.id'),'recursive'=>-1));
        $data_row_id = $cols_data['AdvanceData']['id'];
        
        $save_value['AdvanceData']['id'] = $data_row_id;
        $save_value['AdvanceData']['value'] = $value;
        $this->AdvancedSheet->AdvanceData->save($save_value);
        
        //check for formulas and update that values
        $rows_obj = ClassRegistry::init('ResultColumnFormula');
        $formula_details = $rows_obj->find('all',array('conditions'=>array('ResultColumnFormula.advanced_sheet_id'=>$sheet_id,'ResultColumnFormula.formula !='=>''),'order'=>array('ResultColumnFormula.order ASC'),'fields'=>array('ResultColumnFormula.column_id','ResultColumnFormula.formula')));

        //echo '<pre>'; print_r($formula_details); exit;
        
        //$formula_details['-1']['ResultColumnFormula']['column_id'] = '69'; //Rev Fcst
        //$formula_details['-1']['ResultColumnFormula']['formula'] = 'C65 * C63'; //ADR F'cst * Fcst Rooms
        //Fcst Revenue = Fsct Bob * Fcst ADR
        
        //commented on 16 June 2016 as formula is already available
        //$formula_details['-2']['ResultColumnFormula']['column_id'] = '68'; //Revenue
        //$formula_details['-2']['ResultColumnFormula']['formula'] = 'C62 * C64'; //BOB * ADR
       
        $sheetData = $this->AdvancedSheet->find('first', array('conditions' => array('AdvancedSheet.id' => $sheet_id), 'fields' => array('AdvancedSheet.columns')));
        $sheetColumnsTotal = explode(',',$sheetData['AdvancedSheet']['columns']);
        
        $this->Column = ClassRegistry::init('Column');
        
        $return_array = array();
        $i = '0';
        foreach($formula_details as $formulaData){
                if(!empty($formulaData['ResultColumnFormula']['formula'])){                    
                   $save_data = array();
             
                 $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'total_row_id'=>$formulaData['ResultColumnFormula']['column_id'],'date'=>'0','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.id'),'recursive'=>-1));
                 $data_row_id = $cols_data['AdvanceData']['id'];

                 $save_data['AdvanceData']['id'] = $data_row_id;

                    $column_data = $this->Column->find('first',array('conditions'=>array('Column.id'=>$formulaData['ResultColumnFormula']['column_id'] , 'Column.status !='=>2),'fields'=>array('Column.name')));
                    $colname = $column_data['Column']['name'];
                    
                        $arr_formula_val = explode(" ", $formulaData['ResultColumnFormula']['formula']);
				$arr_indx = 0;
				foreach($arr_formula_val as $val){
					  if(substr($val, 0,1) == "C"){
				                $formula_col_id = substr($val,1);
                                                if(in_array($formula_col_id,$sheetColumnsTotal)){
                                                    //this is total value

                                                    /* //Commented on 16June 2016
                                                    if($formula_col_id == '64'){
                                                        //calculate ADR value as Revenue/BOB
                                                        $sum_bob = $this->AdvancedSheet->AdvanceData->find('first', array(
                                                            'conditions' => array(
                                                            'AdvanceData.advanced_sheet_id' => $sheet_id,'AdvanceData.column_id' => '62','AdvanceData.date' => 'Total','AdvanceData.market_segment_id' => $market_segment_id),
                                                            'fields' => array('AdvanceData.value'
                                                            )
                                                        )
                                                        );
                                                        $all_day_adr = $this->AdvancedSheet->AdvanceData->find('all', array(
                                                            'conditions' => array(
                                                            'AdvanceData.advanced_sheet_id' => $sheet_id,'AdvanceData.column_id' => '64','AdvanceData.date !=' => 'Total','AdvanceData.market_segment_id' => $market_segment_id),
                                                            'fields' => array('AdvanceData.value')
                                                        )
                                                       );
                                                        $all_day_bob = $this->AdvancedSheet->AdvanceData->find('all', array(
                                                            'conditions' => array(
                                                            'AdvanceData.advanced_sheet_id' => $sheet_id,'AdvanceData.column_id' => '62','AdvanceData.date !=' => 'Total','AdvanceData.market_segment_id' => $market_segment_id),
                                                            'fields' => array('AdvanceData.value')
                                                        )
                                                       );
                                                        
                                                      $revenueFinal = '0';
                                                      if(!empty($all_day_bob) && !empty($all_day_adr)){
                                                          foreach($all_day_bob as $bobkey => $bobval){
                                                               $revenueFinal = $revenueFinal + ($bobval['AdvanceData']['value'] * $all_day_adr[$bobkey]['AdvanceData']['value']);
                                                          }
                                                      }                                    
                                                      $acutal_val = $revenueFinal/$sum_bob['AdvanceData']['value'];
                                                      $acutal_val = number_format($acutal_val, 2);
                                                        
                                                    }else{
                                                        $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'column_id'=>$formula_col_id,'date'=>'Total','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.value'),'recursive'=>-1));
                                                        $acutal_val = $cols_data['AdvanceData']['value'];
                                                    }
                                                    */
                                                    
                                                    $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'column_id'=>$formula_col_id,'date'=>'Total','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.value'),'recursive'=>-1));
                                                    $acutal_val = $cols_data['AdvanceData']['value'];
                                                }else{
                                                    //this is result Total column
                                                    $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'total_row_id'=>$formula_col_id,'date'=>'0','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.value'),'recursive'=>-1));
                                                    $acutal_val = $cols_data['AdvanceData']['value'];
                                                }
                                		$arr_formula_val[$arr_indx] = $acutal_val;
					}
					$arr_indx += 1;
				}
				 $math_string = implode("",$arr_formula_val);
				 $final_val = $this->calculate_string($math_string);
                                 
				 //added on 10Feb2015
                                 $final_val = round($final_val,'2');
                                 
                                 $save_data['AdvanceData']['value'] = $final_val;
                                 
                                 $return_array[$i]['id'] =  str_replace(' ','-',$colname).'_'.$market_segment_id;
                                 $return_array[$i]['value'] = $final_val;
                                 
                                 //save value
                                 $this->AdvancedSheet->AdvanceData->save($save_data);
			}
                    $i++;
        }
        return json_encode($return_array);
    }
        
    function calculate_string( $mathString ){
            $mathString = trim($mathString);     // trim white spaces
	    $mathString = str_replace(',', '', $mathString);
            $mathString = str_replace('--', '+', $mathString);
            $mathString = str_replace('.00 ( ', '0.00 * ( ', $mathString);
            
            if($mathString == '*0' || $mathString == '+' || $mathString == '0-' || $mathString == '((0*0)-(*))/0' || $mathString == '*'){
                return 0; 
            }else{
                $mathString = ereg_replace ('[^0-9\+-\*\/\(\) ]', '', $mathString);    // remove any non-numbers chars; exception for math operators
                $compute = create_function("", "return (" . $mathString . ");" );
                return 0 + $compute();
            }
    }
        
        
    function admin_formula($sheetId=null){

    $conditions = array('AdvancedSheet.id'=>$sheetId);
    $this->AdvancedSheet->contain(array('Column','Row','AdvanceData','Template','User'));
    $sheetData = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
    
    $this->ResultColumnFormula = ClassRegistry::init('ResultColumnFormula');
    $SheetColumns = $this->ResultColumnFormula->find('all', array("conditions"=>"advanced_sheet_id = {$sheetId}"));
    $columnIds = Set::extract('/ResultColumnFormula/column_id', $SheetColumns);
    $idstr = implode(',',$columnIds);
    $selected_columns = explode(',',$sheetData['AdvancedSheet']['columns']);
    $selected_columnsidstr = $sheetData['AdvancedSheet']['columns'];

    $total_columns = array();
    if(!empty($selected_columnsidstr))
    {
            $this->Column = ClassRegistry::init('Column');
            $columns_data = $this->Column->find('all', array('conditions'=>"id in({$selected_columnsidstr})"));
            foreach($columns_data as $cols){
              if($cols['Column']['status'] != 2){
                  $total_columns[$cols['Column']['id']] = $cols['Column']['name'];
              }
            }
    }

    $rest_formula = array();
    if(!empty($idstr))
    {
            $this->Column = ClassRegistry::init('Column');
            $columns_data = $this->Column->find('all', array('conditions'=>"id in({$idstr})"));
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

    $this->Column = ClassRegistry::init('Column');
    $all_columns = $this->Column->find('list');
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

    $all_formulas = $this->ResultColumnFormula->find('all',array('conditions'=>array('ResultColumnFormula.advanced_sheet_id'=>$sheetId), 'order'=>array('ResultColumnFormula.order'=>'ASC')));

            $cal_formula = array();
            for($i=0;$i<count($all_formulas);$i++){
                $column_data = $this->Column->findById($all_formulas[$i]['ResultColumnFormula']['column_id'],array('fields'=>'Column.name'));
                $cal_formula[$i]['res'] = $column_data['Column']['name'];
                $rest_formula[] = str_replace($a_col_keys,$a_col_values,$all_formulas[$i]['ResultColumnFormula']['formula']);
                foreach($operators as $key=>$value){
                        $temp_formula = explode($value,$all_formulas[$i]['ResultColumnFormula']['formula']);
                        if(count($temp_formula)>1){
                                $val = 0;
                                foreach($temp_formula as $tfmla){
                                        $temp_formula2 = explode('C',$tfmla);
                                        if(!empty($temp_formula2[1]))
                                        {
                                        $column_data = $this->Column->findById($temp_formula2[1],array('fields'=>'Column.name'));
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
                        $str_formula[$all_formulas[$i]['ResultColumnFormula']['column_id']]=$str;
                }
            }

            $final_str_order = $this->ResultColumnFormula->find ('all', array('conditions'=> array('ResultColumnFormula.advanced_sheet_id'=>$sheetId),'fields'=> array('ResultColumnFormula.column_id','ResultColumnFormula.id'), 'order'=>array('ResultColumnFormula.order'=>'ASC')));
            $formula_ids = array();
            foreach($final_str_order as $formulas){
                $formula_ids[$formulas['ResultColumnFormula']['column_id']] = $formulas['ResultColumnFormula']['id'];
            }
            $this->set(compact('formula_ids'));
            $this->set('all_formulas',$str_formula);
            $sheetId = $sheetData['AdvancedSheet']['id']; 
            $this->set(compact('sheetData', 'sheetId','formulas','columns','operators','total_columns'));

}

    
    function admin_add_formula() {

        if(!empty($this->data)){
                    $this->Column = ClassRegistry::init('Column');
                    $this->ResultColumnFormula = ClassRegistry::init('ResultColumnFormula');
                    
		if(!empty($this->data['AdvancedSheet']['Formula']))
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
			$arrresult = explode(" = ", $this->data['AdvancedSheet']['Formula']);
			$res_column_name = implode(" ", explode("_", $arrresult['0']));

                        $res_column = $this->Column->find('first',array('conditions'=>array('name'=>$res_column_name , 'status !='=>2)));
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
						$column = $this->Column->find('first',array('conditions'=>array('name'=>$column_name , 'status !='=>2)));
						if(!empty($column)){
						    array_push($temp_fromula_array, "C".$column['Column']['id']);
						}else{
						      array_push($temp_fromula_array, $value);
						}
					}
				}
			}
	
			$arr_formula = array();
			$arr_formula['ResultColumnFormula']['advanced_sheet_id'] = $this->data['AdvancedSheet']['id'];
			$arr_formula['ResultColumnFormula']['column_id'] = $res_column_id;
			$arr_formula['ResultColumnFormula']['formula'] = implode(" ", $temp_fromula_array);
                        
			$formula_current = $this->ResultColumnFormula->find('first',array('conditions'=>array('ResultColumnFormula.advanced_sheet_id'=>$this->data['AdvancedSheet']['id'],'ResultColumnFormula.column_id'=>$res_column_id)));
			
			if(!empty($formula_current)){		
			  $arr_formula['ResultColumnFormula']['id'] = $formula_current['ResultColumnFormula']['id'];
			}
			if($this->ResultColumnFormula->save($arr_formula)){
				$this->Session->setFlash(__('Formula created and saved successfully.', true));
                                $this->redirect($this->referer());
			}else{
				$this->Session->setFlash(__('Formula could not be saved.', true));
                                $this->redirect($this->referer());
			}
		}else
		{
			$this->Session->setFlash(__('Formula could not be Empty.', true));
                        $this->redirect($this->referer());
		}
	}
	}

         function admin_remove($id=null){
            if(!empty($id)){
                $this->ResultColumnFormula = ClassRegistry::init('ResultColumnFormula');
                $formula['ResultColumnFormula']['id'] = $id;
                $formula['ResultColumnFormula']['formula'] = '';
                $this->ResultColumnFormula->save($formula);
                $this->Session->setFlash("Formula Deleted !");
                $this->redirect($this->referer());
            }else{
                $this->Session->setFlash("Invalid Formula Selected");
                $this->redirect($this->referer());
            }
      }
      
      function updateOrderFormula($sheet_id=null){
		$this->autoRender = false;
		$array = $_POST['arrayorder'];
		$count = 1;
                $this->ResultColumnFormula = ClassRegistry::init('ResultColumnFormula');
		foreach ($array as $idval) {
			$final_idval = explode('#',$idval);
			$this->ResultColumnFormula->updateAll(array('ResultColumnFormula.order'=>$count), array('ResultColumnFormula.column_id'=>$final_idval['0'],'ResultColumnFormula.advanced_sheet_id'=>$sheet_id));
			$count ++;
		}
		echo 'All saved! refresh the page to see the changes';
      }
    
      
      function admin_export_pdf($sheetId = null) {
        $conditions = array('AdvancedSheet.id'=>$sheetId);
        $this->AdvancedSheet->contain(array('Column','Row','AdvanceData','Template','User'));
        $data = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
        $sheet_name = $data['AdvancedSheet']['name'];
        $user_id = $data['AdvancedSheet']['user_id'];
        $department_id = $data['AdvancedSheet']['department_id'];
        $dept_obj = ClassRegistry::init('Department');
	$dept_name = $dept_obj->field('name',array('id'=>$department_id));
        $username = $data['User']['username'];
        $this->set('sheet_name',$sheet_name);
        $this->set('dept_name',$dept_name);
        $this->set('username',$username);
        $user_obj = ClassRegistry::init('User');
        $user_data = $user_obj->findById($user_id);
        $clienImage = $user_data['Client']['logo'];
        $this->set(compact('clienImage', 'user_data'));
        $rows_obj = ClassRegistry::init('ResultColumnFormula');
        $rows_data = $rows_obj->find('all',array('conditions'=>array('advanced_sheet_id'=>$sheetId),'fields'=>array('ResultColumnFormula.column_id','ResultColumnFormula.is_locked','ResultColumnFormula.formula'),'order'=>  array('order ASC'),'recursive'=>-1));
        $rowIds = Set::extract('/ResultColumnFormula/column_id',$rows_data);
        $lockedRows = Set::extract('/ResultColumnFormula/is_locked',$rows_data);
        $rowsFormulas = Set::extract('/ResultColumnFormula/formula',$rows_data);
        $this->Column = ClassRegistry::init('Column');
        $column_data = $this->Column->find('list',array('conditions'=>array('Column.id'=>$rowIds , 'Column.status !='=>2)));
        
        $lockedIds = array();
        foreach($lockedRows as $key => $locked){
            if($locked == '1'){
                $lockedIds[] = $column_data[$rowIds[$key]];
            }
        }
        
        $totalDataColumns = Set::extract('/total_row_id', $data['AdvanceData']);
        $dates  = Set::extract('/date', $data['AdvanceData']);
        $values = Set::extract('/value', $data['AdvanceData']);
        $dataColumns = Set::extract('/column_id', $data['AdvanceData']);
        $dataRows = Set::extract('/row_id', $data['AdvanceData']);
        $marketSegmentIds = Set::extract('/market_segment_id', $data['AdvanceData']);
        $columnIds = Set::extract('/id', $data['Column']);
        $columns = Set::extract('/name', $data['Column']);
        $numDays = date('t', mktime(0, 0, 0, $data['AdvancedSheet']['month'], 1, $data['AdvancedSheet']['year']));
        $total_maket_segments = array_unique($marketSegmentIds);
        $newCols = array();
        foreach($columnIds as $col_key=>$col_val)
        {
                $newCols[$col_val] = $columns[$col_key];
        }
        
        $columnIdsAr = array_unique($columnIds);
        asort($columnIdsAr);
        asort($total_maket_segments);
        $newSegments = array();
        foreach($total_maket_segments as $seg_key=>$seg_val)
        {
            if(!empty($seg_val)){
            $ms_obj = ClassRegistry::init('MarketSegment');
            $marketsegments = $ms_obj->find('first', array('conditions' => array('MarketSegment.id' => $seg_val),'fields'=>array('name')));
            $newSegments[$seg_val] = $marketsegments['MarketSegment']['name'];
            }
        }

        $final_array  = array(); 
        $final_array_total = array();
        for ($i = 1; $i <= $numDays; $i++) {
                foreach($columnIdsAr as $key =>$colsArr){
                       $dateKeys = array_keys($dates, $i);
                       $dateKeysUpdated = array();
                       foreach($dateKeys as $keys=>$new_data){
                           if($dataColumns[$new_data] ==  $colsArr){
                               $dateKeysUpdated[] = $new_data;
                            }
                       }
                       $mk = '0';
                        foreach($total_maket_segments as $advArr){
                            if(!empty($advArr)){
                                  $j = $dateKeysUpdated[$mk];
                                  $final_array[$i.'/'.$data['AdvancedSheet']['month'].'/'.$data['AdvancedSheet']['year']][$newCols[$colsArr]][$advArr] = $values[$j];
                                  $mk++;
                            }
                        }
                }
        }
        
        foreach($columnIdsAr as $key =>$colsArr){
               $dateKeys = array_keys($dates, 'Total');
               $dateKeysUpdated = array();
               foreach($dateKeys as $keys=>$new_data){
                   if($dataColumns[$new_data] ==  $colsArr){
                       $dateKeysUpdated[] = $new_data;
                    }
               }
               $mk = '0';
                foreach($total_maket_segments as $advArr){
                    if(!empty($advArr)){
                          $j = $dateKeysUpdated[$mk];
                          $new_total_array['Total'][$newCols[$colsArr]][$advArr] = $values[$j];
                          $mk++;
                    }
                }
        }
        
        $total_rows_array = array();
        if(!empty($rowIds)){
            foreach($rowIds as $rows){
                    $dateKeys = array_keys($dates, '0');
                    $dateKeysUpdated = array();
                     foreach($dateKeys as $keys=>$new_data){
                           if($totalDataColumns[$new_data] ==  $rows){
                               $dateKeysUpdated[] = $new_data;
                            }
                     }
                    $mk = '0';
                    foreach($total_maket_segments as $advArr){
                        if(!empty($advArr)){
                              $j = $dateKeysUpdated[$mk];
                              $total_rows_array['Total'][$column_data[$rows]][$advArr] = $values[$j];
                    $mk++; }
                    }
            }
        }
        
/////////
        $total_RevFcst = '0';
        $total_FcstRooms = '0';
        $total_Revenue = '0';
        $total_PickupReq = '0';
        
        foreach($new_total_array['Total']['Fcst Rooms'] as $resultArray){
               $total_FcstRooms = $total_FcstRooms + $resultArray;
        }
        foreach($total_rows_array as $day => $colsArray){
                foreach($colsArray as $col => $resultArray){
                    if($col == "Rev Fcst"){
                        foreach($resultArray as $seg_key=>$segment_vals){
                          $total_RevFcst = $total_RevFcst + $segment_vals;
                        }
                    }
                    if($col == "Revenue"){
                        foreach($resultArray as $seg_key=>$segment_vals){
                          $total_Revenue = $total_Revenue + $segment_vals;
                        }
                    }
                    if($col == "Pickup Req"){
                        foreach($resultArray as $seg_key=>$segment_vals){
                          $total_PickupReq = $total_PickupReq + $segment_vals;
                        }
                    }
                }
        }
        
//       echo 'Rev-Fcst'.$total_RevFcst.'<br/>';
//       echo 'Fcst-Rooms'.$total_FcstRooms.'<br/>';
//        echo 'Revenue'.$total_Revenue.'<br/>';
//        echo 'Pickup-Req'.$total_PickupReq.'<br/>';
        
        $adr_fcst_total = $total_RevFcst/$total_FcstRooms;
        $sell_rate_total = ($total_RevFcst - $total_Revenue)/$total_PickupReq;
        $sell_rate_total = round($sell_rate_total,'2');
        $adr_fcst_total = round($adr_fcst_total,'2');
        
        $this->set('sell_rate_total',$sell_rate_total);
        $this->set('adr_fcst_total',$adr_fcst_total);
////////////        
        
        $this->set('final_array',$final_array);
        $this->set('columns',$newCols);
        $this->set('marketSegments',$newSegments);
        $this->set('final_array_total',$new_total_array);
        $this->set('sheetId',$sheetId);
        $this->set('total_rows_array',$total_rows_array);
        $this->set('lockedIds',$lockedIds);
      }
     //admin_export_pdf function ends here
    
       function admin_export_csv($sheetId = null) {
                $type = "csv";
            if (!empty($sheetId)) {
                $conditions = array('AdvancedSheet.id'=>$sheetId);
                $this->AdvancedSheet->contain(array('Column','Row','AdvanceData','Template','User'));
                $data = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
                $sheet_name = $data['AdvancedSheet']['name'];
                $user_id = $data['AdvancedSheet']['user_id'];
                $department_id = $data['AdvancedSheet']['department_id'];
                $dept_obj = ClassRegistry::init('Department');
                $dept_name = $dept_obj->field('name',array('id'=>$department_id));            
                $rows_obj = ClassRegistry::init('ResultColumnFormula');
                $rows_data = $rows_obj->find('all',array('conditions'=>array('advanced_sheet_id'=>$sheetId),'fields'=>array('ResultColumnFormula.column_id','ResultColumnFormula.is_locked','ResultColumnFormula.formula'),'order'=>  array('order ASC'),'recursive'=>-1));
                $rowIds = Set::extract('/ResultColumnFormula/column_id',$rows_data);               
                $this->Column = ClassRegistry::init('Column');
                $column_data = $this->Column->find('list',array('conditions'=>array('Column.id'=>$rowIds , 'Column.status !='=>2)));
                $totalDataColumns = Set::extract('/total_row_id', $data['AdvanceData']);
                $dates  = Set::extract('/date', $data['AdvanceData']);
                $values = Set::extract('/value', $data['AdvanceData']);
                $dataColumns = Set::extract('/column_id', $data['AdvanceData']);
                $dataRows = Set::extract('/row_id', $data['AdvanceData']);
                $marketSegmentIds = Set::extract('/market_segment_id', $data['AdvanceData']);
                $columnIds = Set::extract('/id', $data['Column']);
                $columns = Set::extract('/name', $data['Column']);
                $numDays = date('t', mktime(0, 0, 0, $data['AdvancedSheet']['month'], 1, $data['AdvancedSheet']['year']));
                $total_maket_segments = array_unique($marketSegmentIds);
                $newCols = array();
                foreach($columnIds as $col_key=>$col_val)
                {
                    $newCols[$col_val] = $columns[$col_key];
                }
                $columnIdsAr = array_unique($columnIds);
                asort($columnIdsAr);
                asort($total_maket_segments);
                $newSegments = array();
                foreach($total_maket_segments as $seg_key=>$seg_val)
                {
                    if(!empty($seg_val)){
                    $ms_obj = ClassRegistry::init('MarketSegment');
                    $marketsegments = $ms_obj->find('first', array('conditions' => array('MarketSegment.id' => $seg_val),'fields'=>array('name')));
                    $newSegments[$seg_val] = $marketsegments['MarketSegment']['name'];
                    }
                }

                $rest_values = array();
                $final_array  = array(); 
                $final_array_total = array();
                for ($i = 1; $i <= $numDays; $i++) {
                        foreach($columnIdsAr as $key =>$colsArr){
                               $dateKeys = array_keys($dates, $i);
                               $dateKeysUpdated = array();
                               foreach($dateKeys as $keys=>$new_data){
                                   if($dataColumns[$new_data] ==  $colsArr){
                                       $dateKeysUpdated[] = $new_data;
                                    }
                               }
                               $mk = '0';
                                foreach($total_maket_segments as $advArr){
                                    if(!empty($advArr)){
                                          $j = $dateKeysUpdated[$mk];
                                          $final_array[$i.'/'.$data['AdvancedSheet']['month'].'/'.$data['AdvancedSheet']['year']][$newCols[$colsArr]][$advArr] = $values[$j];
                                          $mk++;
                                    }
                                }
                        }
                }

                foreach($columnIdsAr as $key =>$colsArr){
                       $dateKeys = array_keys($dates, 'Total');
                       $dateKeysUpdated = array();
                       foreach($dateKeys as $keys=>$new_data){
                           if($dataColumns[$new_data] ==  $colsArr){
                               $dateKeysUpdated[] = $new_data;
                            }
                       }
                       $mk = '0';
                        foreach($total_maket_segments as $advArr){
                            if(!empty($advArr)){
                                  $j = $dateKeysUpdated[$mk];
                                  $new_total_array['Total'][$newCols[$colsArr]][$advArr] = $values[$j];
                                  $mk++;
                            }
                        }
                }

                $total_rows_array = array();
                if(!empty($rowIds)){
                    foreach($rowIds as $rows){
                            $dateKeys = array_keys($dates, '0');
                            $dateKeysUpdated = array();
                             foreach($dateKeys as $keys=>$new_data){
                                   if($totalDataColumns[$new_data] ==  $rows){
                                       $dateKeysUpdated[] = $new_data;
                                    }
                             }
                            $mk = '0';
                            foreach($total_maket_segments as $advArr){
                                if(!empty($advArr)){
                                      $j = $dateKeysUpdated[$mk];
                                      $total_rows_array['Total'][$column_data[$rows]][$advArr] = $values[$j];
                            $mk++; }
                            }
                    }
                }
            $marketSegments =  $newSegments;
            $header_array = array();
            $header_array['0'] = '';
            $header_array[1] = 'Date';
            $seg = '2';
            if(!empty($marketSegments)){ 
                    foreach($marketSegments as $market){
                       $header_array[$seg] = $market;
                        $seg++;
               }
            }
            $header_array[$seg] = 'Total';
            $rest_values['0'] = $header_array;
            
            $row_count= '0';
            if(!empty($final_array)){
                $segmentBOB = array(); $segmentADR = array();
                    foreach($final_array as $day => $colsArray){
                        $bob_array = array(); $adr_array = array();
                                $bob_total = '0';
                        foreach($colsArray as $col => $resultArray){
                           $row_total = '0';
                           $row_count++;
                           $rest_values[$row_count][] =  $col;
                           $rest_values[$row_count][] =  $day;
                           foreach($resultArray as $seg_key=>$segment_vals){
                               if($col == 'BOB'){
                                    $bob_array[$seg_key] = $segment_vals;
                                    $segmentBOB[$seg_key][$day] = $segment_vals;
                                }
                                if($col == 'ADR'){
                                    $adr_array[$seg_key] = $segment_vals;
                                    $segmentADR[$seg_key][$day] = $segment_vals;
                                }
                                $row_total = $row_total + $segment_vals;
                                $rest_values[$row_count][] = $segment_vals;
                           }
                           
                          $revenue_total = '0';
                          if(!empty($bob_array) && !empty($adr_array)){
                              foreach($bob_array as $bobkey => $bobval){
                                  $revenue_total = $revenue_total + ($bobval * $adr_array[$bobkey]);
                              }
                          }
                          if($col == 'BOB'){
                              $bob_total = $row_total;
                          }
                          if($col == 'ADR'){
                              $row_total = $revenue_total/$bob_total;
                             $row_total = number_format($row_total, 2);
                          }
                           $rest_values[$row_count][] = $row_total;
                     }
                    }
              }
              
              if(!empty($new_total_array)){ 
                    foreach($new_total_array as $day => $colsArray){
                         $bobFinal = '0';
                        foreach($colsArray as $col => $resultArray){
                        $row_total = '0';
                        $row_count++;
                            $rest_values[$row_count][] =  $col;
                            $rest_values[$row_count][] =  $day;
                              foreach($resultArray as $seg_key=>$segment_vals){
                                  
                                   if($col == 'ADR'){
                                      $bobFinal = $colsArray['BOB'][$seg_key];
                                      $revenueFinal = '0';
                                      if(!empty($segmentBOB[$seg_key]) && !empty($segmentADR[$seg_key])){
                                          foreach($segmentBOB[$seg_key] as $bobkey => $bobval){
                                               $revenueFinal = $revenueFinal + ($bobval * $segmentADR[$seg_key][$bobkey]);
                                          }
                                      }
                                      $segment_vals = $revenueFinal/$bobFinal;
                                      $segment_vals = number_format($segment_vals, 2);
                                  }
                                  
                                $row_total = $row_total + $segment_vals;
                                $rest_values[$row_count][] = $segment_vals;
                             }
                             $rest_values[$row_count][] = $row_total;
                         }
                    }
              }
              
              
/////////
        $total_RevFcst = '0';
        $total_FcstRooms = '0';
        $total_Revenue = '0';
        $total_PickupReq = '0';
        
        foreach($new_total_array['Total']['Fcst Rooms'] as $resultArray){
               $total_FcstRooms = $total_FcstRooms + $resultArray;
        }
        foreach($total_rows_array as $day => $colsArray){
                foreach($colsArray as $col => $resultArray){
                    if($col == "Rev Fcst"){
                        foreach($resultArray as $seg_key=>$segment_vals){
                          $total_RevFcst = $total_RevFcst + $segment_vals;
                        }
                    }
                    if($col == "Revenue"){
                        foreach($resultArray as $seg_key=>$segment_vals){
                          $total_Revenue = $total_Revenue + $segment_vals;
                        }
                    }
                    if($col == "Pickup Req"){
                        foreach($resultArray as $seg_key=>$segment_vals){
                          $total_PickupReq = $total_PickupReq + $segment_vals;
                        }
                    }
                }
        }
        
        $adr_fcst_total = $total_RevFcst/$total_FcstRooms;
        $sell_rate_total = ($total_RevFcst - $total_Revenue)/$total_PickupReq;
        $sell_rate_total = round($sell_rate_total,'2');
        $adr_fcst_total = round($adr_fcst_total,'2');
        
////////////        
              
              
              if(!empty($total_rows_array)){ 
                    foreach($total_rows_array as $day => $colsArray){
                        foreach($colsArray as $col => $resultArray){
                            $row_total = '0';
                            $row_count++;
                            
                            $rest_values[$row_count][] =  $col;
                            $rest_values[$row_count][] =  '';
                            foreach($resultArray as $seg_key=>$segment_vals){
                                if($col == 'Revenue' || $col == 'Rev Fcst' || $col == 'Pickup Req'){
                                        $segment_vals = (int)$segment_vals;
                                   }
                                   if($col == 'Sell Rate'){
                                       $segment_vals = round($segment_vals,'2');
                                   }
                                   $row_total = $row_total + $segment_vals;
                                $rest_values[$row_count][] = $segment_vals;
                            }
                            if($col == 'Sell Rate'){
                              $row_total = $sell_rate_total;
                            }
                            if($col == 'ADR Fcst'){
                              $row_total = $adr_fcst_total;
                            }
                            $rest_values[$row_count][] =  $row_total;
                       }
                    }
             }
            $this->Export->download1($rest_values, $type);
            }
    }
    
     function admin_delete($sheetId = null) {
        if (!$sheetId) {
            $this->Session->setFlash(__('Invalid sheet id', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->AdvancedSheet->softDelete($sheetId)) {
            $this->Session->setFlash(__('Sheet deleted successfully', true));
            $this->redirect($this->referer());
        } else{
            $this->Session->setFlash(__('Sheet was not deleted, please try again.', true));
            $this->redirect($this->referer());
        }
    }
    
    
    function admin_columns($sheetId){
        $conditions = array('AdvancedSheet.id'=>$sheetId);
        $this->AdvancedSheet->recursive = '-1';
        $data = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
        $selected_columns = explode(',',$data['AdvancedSheet']['columns']);
        $this->Column = ClassRegistry::init('Column');
        $columns = $this->Column->find('list', array('conditions' => array('Column.status !=' => 2)));
        $rows_obj = ClassRegistry::init('ResultColumnFormula');
        $rows_data = $rows_obj->find('all',array('conditions'=>array('advanced_sheet_id'=>$sheetId),'fields'=>array('ResultColumnFormula.column_id','ResultColumnFormula.is_locked','ResultColumnFormula.formula'),'order'=>  array('order ASC'),'recursive'=>-1));
        $row_id = Set::extract('/ResultColumnFormula/column_id',$rows_data);
        $rowlocked = Set::extract('/ResultColumnFormula/is_locked',$rows_data);
        $selected_rows = $row_id;
        $this->set(compact('data','selected_columns','columns','rowlocked','row_id','selected_rows'));
        if(!empty($this->data)){
            
            //echo '<pre>'; print_r($this->data); exit;
            
            $addCols = array(); $deleteCols = array(); $updatedCols = array();
            $addRows = array(); $deleteRows = array(); $updatedRows = array();
            
            $conditions = array('AdvancedSheet.id'=>$this->data['AdvancedSheet']['id']);
            $this->AdvancedSheet->recursive = '-1';
            $sheetData = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
            
            $previousCols = explode(',',$sheetData['AdvancedSheet']['columns']);
            $rows_obj = ClassRegistry::init('ResultColumnFormula');
            $rows_data = $rows_obj->find('all',array('conditions'=>array('advanced_sheet_id'=>$this->data['AdvancedSheet']['id']),'fields'=>array('ResultColumnFormula.column_id','ResultColumnFormula.is_locked','ResultColumnFormula.formula'),'order'=>  array('order ASC'),'recursive'=>-1));
            $prevRows = Set::extract('/ResultColumnFormula/column_id',$rows_data);
            
            if($this->data['Column']['Column']){
                    foreach($this->data['Column']['Column'] as $columns){
                        if(!in_array($columns,$previousCols)){
                            if($columns != '0'){
                                $addCols[] = $columns;
                            }
                        }
                     if($columns != '0'){
                         $updatedCols[] = $columns;
                     }
                    }
            }
            foreach ($previousCols as $oldCol){
                 if(!in_array($oldCol,$this->data['Column']['Column'])){
                            $deleteCols[] = $oldCol;
                    }
            }
            if(!empty ($deleteCols)){
                foreach($deleteCols as $del_col){
                    //delete code for this columns in AdvanceData
                    $this->AdvancedSheet->AdvanceData->deleteAll(array('AdvanceData.column_id' => $del_col, 'AdvanceData.advanced_sheet_id' => $this->data['AdvancedSheet']['id']));
                }
            }
            if(!empty ($addCols)){
                //add code for this columns in AdvanceData
                $segmentsIds = explode(',',$sheetData['AdvancedSheet']['market_segments']);
                $numDays = date('t', mktime(0, 0, 0, $sheetData['AdvancedSheet']['month'], 1, $sheetData['AdvancedSheet']['year']));
                for ($i = 1; $i <= $numDays; $i++) {
                    foreach($segmentsIds as $add_Seg){
                            foreach ($addCols as $key => $columnId) {
                                if(!empty($add_Seg)){
                                unset($datas);
                                $datas['AdvanceData']['id'] = '';
                                $datas['AdvanceData']['advanced_sheet_id'] = $this->data['AdvancedSheet']['id'];
                                $datas['AdvanceData']['value'] = '0';
                                $datas['AdvanceData']['row_id'] = '0';
                                $datas['AdvanceData']['column_id'] = $columnId;
                                $datas['AdvanceData']['date'] = $i;
                                $datas['AdvanceData']['market_segment_id'] = $add_Seg;
                                $this->AdvancedSheet->AdvanceData->saveAll($datas['AdvanceData']);
                                }
                            }
                    }
                }
                foreach($segmentsIds as $add_Seg){
                            foreach ($addCols as $key => $columnId) {
                                if(!empty($add_Seg)){
                                unset($datas);
                                $datas['AdvanceData']['id'] = '';
                                $datas['AdvanceData']['advanced_sheet_id'] = $this->data['AdvancedSheet']['id'];
                                $datas['AdvanceData']['value'] = '0';
                                $datas['AdvanceData']['row_id'] = '0';
                                $datas['AdvanceData']['column_id'] = $columnId;
                                $datas['AdvanceData']['date'] = 'Total';
                                $datas['AdvanceData']['market_segment_id'] = $add_Seg;
                                $this->AdvancedSheet->AdvanceData->saveAll($datas['AdvanceData']);
                                }
                            }
                    }
            }
            $this->data['AdvancedSheet']['columns'] = implode(',',$updatedCols);
            
            if($this->data['Row']['Row']){
                    foreach($this->data['Row']['Row'] as $rows){
                        if(!in_array($rows,$prevRows)){
                            if($rows != '0'){
                                $addRows[] = $rows;
                            }
                        }
                     if($rows != '0'){
                         $updatedRows[] = $rows;
                     }
                    }
            }
            foreach ($prevRows as $oldRow){
                 if(!in_array($oldRow,$this->data['Row']['Row'])){
                            $deleteRows[] = $oldRow;
                    }
            }
            if(!empty ($deleteRows)){
                foreach($deleteRows as $del_row){
                    $this->AdvancedSheet->AdvanceData->deleteAll(array('AdvanceData.total_row_id' => $del_row, 'AdvanceData.advanced_sheet_id' => $this->data['AdvancedSheet']['id']));                    
                    $rows_obj = ClassRegistry::init('ResultColumnFormula');
                    $rows_data = $rows_obj->deleteAll(array('ResultColumnFormula.advanced_sheet_id'=>$this->data['AdvancedSheet']['id'],'ResultColumnFormula.column_id'=>$del_row));                    
                }
            }
            if(!empty ($addRows)){
                $segmentsIds = explode(',',$sheetData['AdvancedSheet']['market_segments']);
                foreach($segmentsIds as $add_Seg){
                    foreach ($addRows as $key => $rowId) {
                        if(!empty($add_Seg) && !empty($rowId)){
                            unset($datas);
                            $datas['AdvanceData']['id'] = '';
                            $datas['AdvanceData']['advanced_sheet_id'] = $this->data['AdvancedSheet']['id'];
                            $datas['AdvanceData']['value'] = '0';
                            $datas['AdvanceData']['total_row_id'] = $rowId;
                            $datas['AdvanceData']['column_id'] = '0';
                            $datas['AdvanceData']['date'] = '0';
                            $datas['AdvanceData']['market_segment_id'] = $add_Seg;
                            $this->AdvancedSheet->AdvanceData->saveAll($datas['AdvanceData']);

                            unset($rowsData);
                            $rowsData['ResultColumnFormula']['id'] = '';
                            $rowsData['ResultColumnFormula']['column_id'] = $rowId;
                            $rowsData['ResultColumnFormula']['advanced_sheet_id'] = $this->data['AdvancedSheet']['id'];
                            $rows_formula_obj = ClassRegistry::init('ResultColumnFormula');
                            $rows_formula_obj->create();
                            $rows_formula_obj->saveAll($rowsData);
                        }
                    }
                }
            }
            $rows_formula_obj = ClassRegistry::init('ResultColumnFormula');
            $rows_formula_obj->updateAll(array('ResultColumnFormula.is_locked' => '0'), array('ResultColumnFormula.advanced_sheet_id' => $this->data['AdvancedSheet']['id']));
            
                foreach ($this->data['Row']['Locked'] as $ky => $vl) {
                    if ($vl > 0) {
                        $rows_formula_obj = ClassRegistry::init('ResultColumnFormula');
                        $cond = "advanced_sheet_id = {$this->data['AdvancedSheet']['id']} AND column_id = {$vl}";
                        $arr = $rows_formula_obj->find('first', array("conditions" => $cond));
                        $arr['ResultColumnFormula']['is_locked'] = '1';
                        $rows_formula_obj->save($arr);
                    }
                }
            
            if($this->AdvancedSheet->save($this->data)){
                $this->Session->setFlash(__('Columns Updated successfully.', true));
                $this->redirect('/admin/advancedSheets/index/'.$sheetData['AdvancedSheet']['user_id'].'/'.$sheetData['AdvancedSheet']['department_id']);
            }
                
        }//end not empty
    }//end function
    
    
    //Protel Import for Hotel Grunerbaum
    function admin_import_grunerbaum($sheetId){
         // Configure::write('debug',2);
           
        //$this->layout = '';
        $this->set('sheetId',$sheetId);

        if (!empty($this->data)) {
            if (!$this->data['AdvancedSheet']['browse_file']['name']) {
                $this->Session->setFlash(__('Please uploaded file!', true));
                $this->redirect(array('action' => 'admin_import_grunerbaum', $sheetId));
            }

            $path_parts = pathinfo($this->data['AdvancedSheet']['browse_file']["name"]);
            $extension = strtolower($path_parts['extension']);
            if ($extension != 'xls' && $extension != 'Xls') {
                $this->Session->setFlash(__('Please uploaded excel(.xls) file!', true));
                $this->redirect(array('action' => 'admin_import_grunerbaum', $sheetId));
            }

            $handle = fopen($this->data['AdvancedSheet']['browse_file']['tmp_name'], 'r');
            if (!$handle) {
                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                $this->redirect(array('action' => 'admin_import_grunerbaum', $sheetId));
            }
            $new_data = array();
            $row = 1;

                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['AdvancedSheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;
                
                $conditions = array('AdvancedSheet.id'=>$sheetId);
                $this->AdvancedSheet->recursive = '-1';
                $sheetData = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
                $sheet_segments = explode(',',$sheetData['AdvancedSheet']['market_segments']);
                
                
                $this->MarketSegment = ClassRegistry::init('MarketSegment');
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$sheet_segments)));
                //echo '<pre>'; print_r($marketsegments); exit;
                
                for($i='1';$i<='14';$i++){
                    unset($ndata[0]['cells'][$i]);
                }
                
                //echo '<pre>'; print_r($ndata[0]['cells']); exit;
                
                $new_date = '1';
                foreach($ndata[0]['cells'] as $data){
                    if (strstr($data[1],'Summe f�r')){
                        $new_date = '1';
                        continue;
                    }else if ($new_date == '1'){
                        $date_str = explode(' ',$data[1]);
                       // print_r($date_str);
                        //$date_arr = explode('.',$date_str[1]);
                        //$date = $date_arr[0];
                        
                        $date = date("j", strtotime($date_str[1]));
                        
                        $new_date = '0';
                        continue;
                    } else if(isset ($data['4']) && isset ($data['7'])){
                        $new_date = '0';
                        
                         //$market_segment_id = array_search($data['1'], $marketsegments);
                        
                         $market_segment_id = array_search(strtolower($data['1']), array_map('strtolower', $marketsegments));
                         
                         if(empty($market_segment_id)){
                             if (strstr($data[1],'/')){
                                 $explode_seg = explode('/',$data['1']);
                                 foreach($explode_seg as $seg){
                                     $market_segment_id = array_search(strtolower($seg), array_map('strtolower', $marketsegments));
                                     if(!empty($market_segment_id)){
                                         break;
                                     }
                                 }
                             }
                         }
                        
//                         $save_data['AdvanceData']['BOB'] = $data['4'];//62
//                         $save_data['AdvanceData']['ADR'] = $data['7'];//64
//                         $save_data['AdvanceData']['market_segment_id'] = $market_segment_id.':'.$data['1'];
//                         $save_data['AdvanceData']['date'] = $date;
//                         $save_data['AdvanceData']['advanced_sheet_id'] = $sheetId;
//                         $save_data['AdvanceData']['row_id'] = '0';
                         
                         //echo '<pre>'; print_r($save_data);
                         if(empty($data['4'])){ $data['4'] = '0'; }
                         if(empty($data['7'])){ $data['7'] = '0'; }
                         $data['4'] = str_replace(',','',$data['4']);
                         $data['7'] = str_replace(',','',$data['7']);
                         $this->AdvancedSheet->AdvanceData->updateAll(array('AdvanceData.value' => $data['4']), array('AdvanceData.advanced_sheet_id'=>$sheetId,'AdvanceData.column_id'=>'62','AdvanceData.row_id'=>0,'AdvanceData.market_segment_id'=>$market_segment_id,'AdvanceData.date'=>$date));
                         $this->AdvancedSheet->AdvanceData->updateAll(array('AdvanceData.value' => $data['7']), array('AdvanceData.advanced_sheet_id'=>$sheetId,'AdvanceData.column_id'=>'64','AdvanceData.row_id'=>0,'AdvanceData.market_segment_id'=>$market_segment_id,'AdvanceData.date'=>$date));
                     
                    }
                }
               
                //Update Total BOB and ADR for each Segments
                
                //print_r($sheet_segments);
                
                foreach($sheet_segments as $sheetSeg){
//                    $sum_bob = $this->AdvancedSheet->AdvanceData->find('all', array(
//                        'conditions' => array(
//                        'AdvanceData.advanced_sheet_id' => $sheetId,'AdvanceData.column_id' => '62','AdvanceData.date !=' => 'Total','AdvanceData.total_row_id'=>'0','AdvanceData.market_segment_id' => $sheetSeg),
//                        'fields' => array('sum(AdvanceData.value) as segment_sum'
//                        )
//                    )
//                    );
//                    $sum_adr = $this->AdvancedSheet->AdvanceData->find('all', array(
//                        'conditions' => array(
//                        'AdvanceData.advanced_sheet_id' => $sheetId,'AdvanceData.column_id' => '64','AdvanceData.date !=' => 'Total','AdvanceData.total_row_id'=>'0','AdvanceData.market_segment_id' => $sheetSeg),
//                        'fields' => array('sum(AdvanceData.value) as segment_sum'
//                        )
//                    )
//                   );
                    
                    $sum_bob = $this->AdvancedSheet->AdvanceData->find('all', array(
                        'conditions' => array(
                        'AdvanceData.advanced_sheet_id' => $sheetId,'AdvanceData.column_id' => '62','AdvanceData.date !=' => 'Total','AdvanceData.market_segment_id' => $sheetSeg),
                        'fields' => array('sum(AdvanceData.value) as segment_sum'
                        )
                    )
                    );
                    $sum_adr = $this->AdvancedSheet->AdvanceData->find('all', array(
                        'conditions' => array(
                        'AdvanceData.advanced_sheet_id' => $sheetId,'AdvanceData.column_id' => '64','AdvanceData.date !=' => 'Total','AdvanceData.market_segment_id' => $sheetSeg),
                        'fields' => array('sum(AdvanceData.value) as segment_sum'
                        )
                    )
                   );

                    
                    //if(isset($sum_bob[0][0]['segment_sum']) || empty($sum_bob[0][0]['segment_sum']) || is_null($sum_bob[0][0]['segment_sum'])){ $sum_bob[0][0]['segment_sum'] = '0'; }
                    //if(isset($sum_adr[0][0]['segment_sum']) || empty($sum_adr[0][0]['segment_sum']) || is_null($sum_adr[0][0]['segment_sum'])){ $sum_adr[0][0]['segment_sum'] = '0'; }
                    
                    $this->AdvancedSheet->AdvanceData->updateAll(array('AdvanceData.value' => $sum_bob[0][0]['segment_sum']), array('AdvanceData.advanced_sheet_id'=>$sheetId,'AdvanceData.column_id'=>'62','AdvanceData.row_id'=>0,'AdvanceData.market_segment_id'=>$sheetSeg,'AdvanceData.date'=>'Total'));
                    $this->AdvancedSheet->AdvanceData->updateAll(array('AdvanceData.value' => $sum_adr[0][0]['segment_sum']), array('AdvanceData.advanced_sheet_id'=>$sheetId,'AdvanceData.column_id'=>'64','AdvanceData.row_id'=>0,'AdvanceData.market_segment_id'=>$sheetSeg,'AdvanceData.date'=>'Total'));
                    
                }
                
                //Update Result columns as per the formula
                if(!empty($sheetId)){
                $update_cols = $this->requestAction('/AdvancedSheets/update_result_cols/'.$sheetId);
                }
                //Update Result column as per formula ends here
                
                $this->Session->setFlash(__('File Imported successfully.', true));
               $this->redirect('/admin/advancedSheets/webform/'.$sheetId);
            
            }
            
        //exit;
        
    }
     //admin_import_grunerbaum function ends here
    
   
    
    
    //Protel Import for Hotel Grunerbaum
    function client_import_grunerbaum($sheetId){
        $this->set('sheetId',$sheetId);

        if (!empty($this->data)) {
            if (!$this->data['AdvancedSheet']['browse_file']['name']) {
                $this->Session->setFlash(__('Please uploaded file!', true));
                $this->redirect(array('action' => 'client_import_grunerbaum', $sheetId));
            }

            $path_parts = pathinfo($this->data['AdvancedSheet']['browse_file']["name"]);
            $extension = strtolower($path_parts['extension']);
            if ($extension != 'xls' && $extension != 'Xls') {
                $this->Session->setFlash(__('Please uploaded excel(.xls) file!', true));
                $this->redirect(array('action' => 'client_import_grunerbaum', $sheetId));
            }

            $handle = fopen($this->data['AdvancedSheet']['browse_file']['tmp_name'], 'r');
            if (!$handle) {
                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                $this->redirect(array('action' => 'client_import_grunerbaum', $sheetId));
            }
            $new_data = array();
            $row = 1;

                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['AdvancedSheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;
                
                $conditions = array('AdvancedSheet.id'=>$sheetId);
                $this->AdvancedSheet->recursive = '-1';
                $sheetData = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
                $sheet_segments = explode(',',$sheetData['AdvancedSheet']['market_segments']);
                
                $this->MarketSegment = ClassRegistry::init('MarketSegment');
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$sheet_segments)));
                
                for($i='1';$i<='14';$i++){
                    unset($ndata[0]['cells'][$i]);
                }
                
                $new_date = '1';
                foreach($ndata[0]['cells'] as $data){
                    if (strstr($data[1],'Summe f�r')){
                        $new_date = '1';
                        continue;
                    }else if ($new_date == '1'){
                        $date_str = explode(' ',$data[1]);
                        $date = date("j", strtotime($date_str[1]));
                        $new_date = '0';
                        continue;
                    } else if(isset ($data['4']) && isset ($data['7'])){
                        $new_date = '0';
                         $market_segment_id = array_search(strtolower($data['1']), array_map('strtolower', $marketsegments));
                         
                         if(empty($market_segment_id)){
                             if (strstr($data[1],'/')){
                                 $explode_seg = explode('/',$data['1']);
                                 foreach($explode_seg as $seg){
                                     $market_segment_id = array_search(strtolower($seg), array_map('strtolower', $marketsegments));
                                     if(!empty($market_segment_id)){
                                         break;
                                     }
                                 }
                             }
                         }
         
                         if(empty($data['4'])){ $data['4'] = '0'; }
                         if(empty($data['7'])){ $data['7'] = '0'; }
                         $data['4'] = str_replace(',','',$data['4']);
                         $data['7'] = str_replace(',','',$data['7']);
                         $this->AdvancedSheet->AdvanceData->updateAll(array('AdvanceData.value' => $data['4']), array('AdvanceData.advanced_sheet_id'=>$sheetId,'AdvanceData.column_id'=>'62','AdvanceData.row_id'=>0,'AdvanceData.market_segment_id'=>$market_segment_id,'AdvanceData.date'=>$date));
                         $this->AdvancedSheet->AdvanceData->updateAll(array('AdvanceData.value' => $data['7']), array('AdvanceData.advanced_sheet_id'=>$sheetId,'AdvanceData.column_id'=>'64','AdvanceData.row_id'=>0,'AdvanceData.market_segment_id'=>$market_segment_id,'AdvanceData.date'=>$date));
                     
                    }
                }
               
                //Update Total BOB and ADR for each Segments
                
                foreach($sheet_segments as $sheetSeg){
                    $sum_bob = $this->AdvancedSheet->AdvanceData->find('all', array(
                        'conditions' => array(
                        'AdvanceData.advanced_sheet_id' => $sheetId,'AdvanceData.column_id' => '62','AdvanceData.date !=' => 'Total','AdvanceData.market_segment_id' => $sheetSeg),
                        'fields' => array('sum(AdvanceData.value) as segment_sum'
                        )
                    )
                    );
                    $sum_adr = $this->AdvancedSheet->AdvanceData->find('all', array(
                        'conditions' => array(
                        'AdvanceData.advanced_sheet_id' => $sheetId,'AdvanceData.column_id' => '64','AdvanceData.date !=' => 'Total','AdvanceData.market_segment_id' => $sheetSeg),
                        'fields' => array('sum(AdvanceData.value) as segment_sum'
                        )
                    )
                   );
                    $this->AdvancedSheet->AdvanceData->updateAll(array('AdvanceData.value' => $sum_bob[0][0]['segment_sum']), array('AdvanceData.advanced_sheet_id'=>$sheetId,'AdvanceData.column_id'=>'62','AdvanceData.row_id'=>0,'AdvanceData.market_segment_id'=>$sheetSeg,'AdvanceData.date'=>'Total'));
                    $this->AdvancedSheet->AdvanceData->updateAll(array('AdvanceData.value' => $sum_adr[0][0]['segment_sum']), array('AdvanceData.advanced_sheet_id'=>$sheetId,'AdvanceData.column_id'=>'64','AdvanceData.row_id'=>0,'AdvanceData.market_segment_id'=>$sheetSeg,'AdvanceData.date'=>'Total'));
                    
                }
                
                //Update Result columns as per the formula
                if(!empty($sheetId)){
                $update_cols = $this->requestAction('/AdvancedSheets/update_result_cols/'.$sheetId);
                }
                //Update Result column as per formula ends here
                
                $this->Session->setFlash(__('File Imported successfully.', true));
               $this->redirect('/client/advancedSheets/webform/'.$sheetId);
            
            }
        
    }
     //admin_import_grunerbaum function ends here
    
   
    
    //check for formulas and update that values
    function update_result_cols($sheet_id){
        
                $this->autoRender = false;
                $this->layout = false;
        
               //$sheet_id = $sheetId;
                $rows_obj = ClassRegistry::init('ResultColumnFormula');
                $formula_details = $rows_obj->find('all',array('conditions'=>array('ResultColumnFormula.advanced_sheet_id'=>$sheet_id,'ResultColumnFormula.formula !='=>''),'order'=>array('ResultColumnFormula.order ASC'),'fields'=>array('ResultColumnFormula.column_id','ResultColumnFormula.formula')));
                $sheetData = $this->AdvancedSheet->find('first', array('conditions' => array('AdvancedSheet.id' => $sheet_id), 'fields' => array('AdvancedSheet.columns,AdvancedSheet.market_segments')));
                $sheetColumnsTotal = explode(',',$sheetData['AdvancedSheet']['columns']);
                $sheet_segments = explode(',',$sheetData['AdvancedSheet']['market_segments']);
                $this->Column = ClassRegistry::init('Column');
                $return_array = array();

                $formula_details['-2']['ResultColumnFormula']['column_id'] = '68'; //Revenue
                $formula_details['-2']['ResultColumnFormula']['formula'] = 'C62 * C64'; //BOB * ADR
                
                $i = '0';
                foreach($formula_details as $formulaData){
                        if(!empty($formulaData['ResultColumnFormula']['formula'])){
                            
                            foreach($sheet_segments as $market_segment_id){
                            
                           $save_data = array();

                         $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'total_row_id'=>$formulaData['ResultColumnFormula']['column_id'],'date'=>'0','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.id'),'recursive'=>-1));
                         $data_row_id = $cols_data['AdvanceData']['id'];
                         $save_data['AdvanceData']['id'] = $data_row_id;
                            $column_data = $this->Column->find('first',array('conditions'=>array('Column.id'=>$formulaData['ResultColumnFormula']['column_id'] , 'Column.status !='=>2),'fields'=>array('Column.name')));
                            $colname = $column_data['Column']['name'];
                                $arr_formula_val = explode(" ", $formulaData['ResultColumnFormula']['formula']);
                                        $arr_indx = 0;
                                        foreach($arr_formula_val as $val){
                                                  if(substr($val, 0,1) == "C"){
                                                        $formula_col_id = substr($val,1);
                                                        if(in_array($formula_col_id,$sheetColumnsTotal)){
                                                            //this is total value
                                                            
                                                            if($formula_col_id == '64'){
                                                                    //calculate ADR value as Revenue/BOB
                                                                    $sum_bob = $this->AdvancedSheet->AdvanceData->find('first', array(
                                                                        'conditions' => array(
                                                                        'AdvanceData.advanced_sheet_id' => $sheet_id,'AdvanceData.column_id' => '62','AdvanceData.date' => 'Total','AdvanceData.market_segment_id' => $market_segment_id),
                                                                        'fields' => array('AdvanceData.value'
                                                                        )
                                                                    )
                                                                    );
                                                                    $all_day_adr = $this->AdvancedSheet->AdvanceData->find('all', array(
                                                                        'conditions' => array(
                                                                        'AdvanceData.advanced_sheet_id' => $sheet_id,'AdvanceData.column_id' => '64','AdvanceData.date !=' => 'Total','AdvanceData.market_segment_id' => $market_segment_id),
                                                                        'fields' => array('AdvanceData.value')
                                                                    )
                                                                   );
                                                                    $all_day_bob = $this->AdvancedSheet->AdvanceData->find('all', array(
                                                                        'conditions' => array(
                                                                        'AdvanceData.advanced_sheet_id' => $sheet_id,'AdvanceData.column_id' => '62','AdvanceData.date !=' => 'Total','AdvanceData.market_segment_id' => $market_segment_id),
                                                                        'fields' => array('AdvanceData.value')
                                                                    )
                                                                   );

                                                                  $revenueFinal = '0';
                                                                  if(!empty($all_day_bob) && !empty($all_day_adr)){
                                                                      foreach($all_day_bob as $bobkey => $bobval){
                                                                           $revenueFinal = $revenueFinal + ($bobval['AdvanceData']['value'] * $all_day_adr[$bobkey]['AdvanceData']['value']);
                                                                      }
                                                                  }                                    
                                                                  $acutal_val = $revenueFinal/$sum_bob['AdvanceData']['value'];
                                                                  $acutal_val = number_format($acutal_val, 2);

                                                                }else{
                                                                    $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'column_id'=>$formula_col_id,'date'=>'Total','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.value'),'recursive'=>-1));
                                                                    $acutal_val = $cols_data['AdvanceData']['value'];
                                                                }
                                                            
                                                            //$cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'column_id'=>$formula_col_id,'date'=>'Total','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.value'),'recursive'=>-1));
                                                            //$acutal_val = $cols_data['AdvanceData']['value'];
                                                        }else{
                                                            //this is result Total column
                                                            $cols_data = $this->AdvancedSheet->AdvanceData->find('first',array('conditions'=>array('advanced_sheet_id'=>$sheet_id,'total_row_id'=>$formula_col_id,'date'=>'0','market_segment_id'=>$market_segment_id),'fields'=>array('AdvanceData.value'),'recursive'=>-1));
                                                            $acutal_val = $cols_data['AdvanceData']['value'];
                                                        }
                                                        $arr_formula_val[$arr_indx] = $acutal_val;
                                                }
                                                $arr_indx += 1;
                                        }
                                         $math_string = implode("",$arr_formula_val);
                                         $final_val = $this->calculate_string($math_string);
                                         if(empty($final_val) || is_null($final_val)){ $final_val = '0'; }
                                         $save_data['AdvanceData']['value'] = $final_val;
                                         //save value
                                         $this->AdvancedSheet->AdvanceData->save($save_data);
                                }
                 
                }
                }
 
                return true;
        
    }
    
    
    function admin_webform($sheetId){
        $conditions = array('AdvancedSheet.id'=>$sheetId);
        $this->AdvancedSheet->contain(array('Column','Row','AdvanceData','Template','User'));
        $data = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
        $sheet_name = $data['AdvancedSheet']['name'];
        $user_id = $data['AdvancedSheet']['user_id'];
        $department_id = $data['AdvancedSheet']['department_id'];
        $dept_obj = ClassRegistry::init('Department');
	$dept_name = $dept_obj->field('name',array('id'=>$department_id));
        $username = $data['User']['username'];
        $this->set('sheet_name',$sheet_name);
        $this->set('dept_name',$dept_name);
        $this->set('username',$username);
        $rows_obj = ClassRegistry::init('ResultColumnFormula');
        $rows_data = $rows_obj->find('all',array('conditions'=>array('advanced_sheet_id'=>$sheetId),'fields'=>array('ResultColumnFormula.column_id','ResultColumnFormula.is_locked','ResultColumnFormula.formula'),'order'=>  array('order ASC'),'recursive'=>-1));
        $rowIds = Set::extract('/ResultColumnFormula/column_id',$rows_data);
        $lockedRows = Set::extract('/ResultColumnFormula/is_locked',$rows_data);
        $rowsFormulas = Set::extract('/ResultColumnFormula/formula',$rows_data);
        $this->Column = ClassRegistry::init('Column');
        $column_data = $this->Column->find('list',array('conditions'=>array('Column.id'=>$rowIds , 'Column.status !='=>2)));
        $lockedIds = array();
        foreach($lockedRows as $key => $locked){
            if($locked == '1'){
                $lockedIds[] = $column_data[$rowIds[$key]];
            }
        }
        $totalDataColumns = Set::extract('/total_row_id', $data['AdvanceData']);
        $dates  = Set::extract('/date', $data['AdvanceData']);
        $values = Set::extract('/value', $data['AdvanceData']);
        $dataColumns = Set::extract('/column_id', $data['AdvanceData']);
        $dataRows = Set::extract('/row_id', $data['AdvanceData']);
        
        $marketSegmentIds = Set::extract('/market_segment_id', $data['AdvanceData']);
        $sheetmarketSegmentIds = explode(',',$data['AdvancedSheet']['market_segments']);
        
        $columnIds = Set::extract('/id', $data['Column']);
        $columns = Set::extract('/name', $data['Column']);
        $numDays = date('t', mktime(0, 0, 0, $data['AdvancedSheet']['month'], 1, $data['AdvancedSheet']['year']));
        $total_maket_segments = array_unique($marketSegmentIds);
        $newCols = array();
        
        foreach($columnIds as $col_key=>$col_val)
        {
            $newCols[$col_val] = $columns[$col_key];
        }
        $columnIdsAr = array_unique($columnIds);
        asort($columnIdsAr);
        //asort($total_maket_segments);
        $newSegments = array();
        foreach($sheetmarketSegmentIds as $seg_key=>$seg_val)
        //foreach($total_maket_segments as $seg_key=>$seg_val)
        {
            if(!empty($seg_val)){
            $ms_obj = ClassRegistry::init('MarketSegment');
            $marketsegments = $ms_obj->find('first', array('conditions' => array('MarketSegment.id' => $seg_val),'fields'=>array('name')));
            $newSegments[$seg_val] = $marketsegments['MarketSegment']['name'];
            }
        }
       
        //echo '<pre>'; print_r($total_maket_segments); print_r($newSegments); echo '</pre>';
        
        
        $final_array  = array(); 
        $final_array_total = array();
        for ($i = 1; $i <= $numDays; $i++) {
                foreach($columnIdsAr as $key =>$colsArr){
                       $dateKeys = array_keys($dates, $i);        
                       $dateKeysUpdated = array();
                       foreach($dateKeys as $keys=>$new_data){
                           if($dataColumns[$new_data] ==  $colsArr){
                               $dateKeysUpdated[] = $new_data;
                            }
                       }
                       
                       $mk = '0';
                        foreach($total_maket_segments as $advArr){
                                if(!empty($advArr)){
                                  $j = $dateKeysUpdated[$mk];
                                  $final_array[$i.'/'.$data['AdvancedSheet']['month'].'/'.$data['AdvancedSheet']['year']][$newCols[$colsArr]][$advArr] = $values[$j];
                                  $mk++;
                                }
                        }
                }
        }
        
        foreach($columnIdsAr as $key =>$colsArr){
               $dateKeys = array_keys($dates, 'Total');
               $dateKeysUpdated = array();
               foreach($dateKeys as $keys=>$new_data){
                   if($dataColumns[$new_data] ==  $colsArr){
                       $dateKeysUpdated[] = $new_data;
                    }
               }
               $mk = '0';
                foreach($total_maket_segments as $advArr){
                    if(!empty($advArr)){
                          $j = $dateKeysUpdated[$mk];
                          $new_total_array['Total'][$newCols[$colsArr]][$advArr] = $values[$j];
                          $mk++;
                    }
                }
        }
        $total_rows_array = array();
        if(!empty($rowIds)){
            foreach($rowIds as $rows){
                
                    $dateKeys = array_keys($dates, '0');
                    $dateKeysUpdated = array();
                     foreach($dateKeys as $keys=>$new_data){
                           if($totalDataColumns[$new_data] ==  $rows){
                               $dateKeysUpdated[] = $new_data;
                            }
                     }
                     //make array with segments
                    $mk = '0';
                    foreach($total_maket_segments as $advArr){
                        if(!empty($advArr)){
                      $j = $dateKeysUpdated[$mk];
                      $total_rows_array['Total'][$column_data[$rows]][$advArr] = $values[$j];
                      $mk++;
                        }
                    }                    
            }
        }
        
        //echo '<pre>'; print_r($total_rows_array); exit;
        
        $this->set('final_array',$final_array);
        $this->set('columns',$newCols);
        $this->set('marketSegments',$newSegments);
        $this->set('final_array_total',$new_total_array);
        $this->set('sheetId',$sheetId);
        $this->set('total_rows_array',$total_rows_array);
        $this->set('lockedIds',$lockedIds);
        $this->set('data',$data);
        
    }
    
    public function update_sum_total($sheetId){
       $this->autoRender = false;
       $this->layout = false;
        
        
       return true;
    }
    
    public function client_index($department_id=null){
        
        if (!empty($this->data) && trim($this->data['AdvancedSheet']['value']) != '') {
            $conditions = array('AdvancedSheet.name LIKE' => "%" . $this->data['AdvancedSheet']['value'] . "%", 'AdvancedSheet.department_id' => $this->params['pass'][0], 'AdvancedSheet.status !=' => 2);
        } else {
            $conditions = array('AdvancedSheet.status !=' => 2, 'AdvancedSheet.department_id' => $this->params['pass'][0]);
        }
        
        $userAdvancedSheets = $this->AdvancedSheet->find('all', array('conditions' => $conditions, 'contain' => array('User'), 'order' => array('AdvancedSheet.year ASC', 'AdvancedSheet.month ASC')));
        
        $depts_obj = ClassRegistry::init('DepartmentsUser');
        $department = $depts_obj->field('department_name', array('DepartmentsUser.department_id' => $this->params['pass'][0]));
        $mAdvancedSheets = $this->AdvancedSheet->find('all', array('conditions' => $conditions, 'fields' => array('DISTINCT AdvancedSheet.year, AdvancedSheet.month'), 'recursive' => -1, 'order' => 'AdvancedSheet.year ASC'));
        $this->set(compact('userAdvancedSheets', 'userId', 'department', 'dept_id', 'mAdvancedSheets'));
        $last_AdvancedSheet = $this->AdvancedSheet->find('first', array('conditions' => array('AdvancedSheet.status !=' => 2, 'AdvancedSheet.department_id' => $this->params['pass'][0]), 'fields' => array('AdvancedSheet.id'), 'recursive' => -1, 'order' => 'AdvancedSheet.year DESC, AdvancedSheet.month DESC'));
        $this->set('last_AdvancedSheet', $last_AdvancedSheet);
        
    }
    
    function client_webform($sheetId){
        $conditions = array('AdvancedSheet.id'=>$sheetId);
        $this->AdvancedSheet->contain(array('Column','Row','AdvanceData','Template','User'));
        $data = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
        $sheet_name = $data['AdvancedSheet']['name'];
        $user_id = $data['AdvancedSheet']['user_id'];
        $department_id = $data['AdvancedSheet']['department_id'];
        $dept_obj = ClassRegistry::init('Department');
	$dept_name = $dept_obj->field('name',array('id'=>$department_id));
        $username = $data['User']['username'];
        $this->set('sheet_name',$sheet_name);
        $this->set('dept_name',$dept_name);
        $this->set('username',$username);
        $rows_obj = ClassRegistry::init('ResultColumnFormula');
        $rows_data = $rows_obj->find('all',array('conditions'=>array('advanced_sheet_id'=>$sheetId),'fields'=>array('ResultColumnFormula.column_id','ResultColumnFormula.is_locked','ResultColumnFormula.formula'),'order'=>  array('order ASC'),'recursive'=>-1));
        $rowIds = Set::extract('/ResultColumnFormula/column_id',$rows_data);
        $lockedRows = Set::extract('/ResultColumnFormula/is_locked',$rows_data);
        $rowsFormulas = Set::extract('/ResultColumnFormula/formula',$rows_data);
        $this->Column = ClassRegistry::init('Column');
        $column_data = $this->Column->find('list',array('conditions'=>array('Column.id'=>$rowIds , 'Column.status !='=>2)));
        $lockedIds = array();
        foreach($lockedRows as $key => $locked){
            if($locked == '1'){
                $lockedIds[] = $column_data[$rowIds[$key]];
            }
        }
        $totalDataColumns = Set::extract('/total_row_id', $data['AdvanceData']);
        $dates  = Set::extract('/date', $data['AdvanceData']);
        $values = Set::extract('/value', $data['AdvanceData']);
        $dataColumns = Set::extract('/column_id', $data['AdvanceData']);
        $dataRows = Set::extract('/row_id', $data['AdvanceData']);
        
        $marketSegmentIds = Set::extract('/market_segment_id', $data['AdvanceData']);
        $sheetmarketSegmentIds = explode(',',$data['AdvancedSheet']['market_segments']);
        
        $columnIds = Set::extract('/id', $data['Column']);
        $columns = Set::extract('/name', $data['Column']);
        $numDays = date('t', mktime(0, 0, 0, $data['AdvancedSheet']['month'], 1, $data['AdvancedSheet']['year']));
        $total_maket_segments = array_unique($marketSegmentIds);
        $newCols = array();
        foreach($columnIds as $col_key=>$col_val)
        {
            $newCols[$col_val] = $columns[$col_key];
        }
        $columnIdsAr = array_unique($columnIds);
        asort($columnIdsAr);
        $newSegments = array();
        foreach($sheetmarketSegmentIds as $seg_key=>$seg_val)
        {
            if(!empty($seg_val)){
            $ms_obj = ClassRegistry::init('MarketSegment');
            $marketsegments = $ms_obj->find('first', array('conditions' => array('MarketSegment.id' => $seg_val),'fields'=>array('name')));
            $newSegments[$seg_val] = $marketsegments['MarketSegment']['name'];
            }
        }
        
        $final_array  = array(); 
        $final_array_total = array();
        for ($i = 1; $i <= $numDays; $i++) {
                foreach($columnIdsAr as $key =>$colsArr){
                       $dateKeys = array_keys($dates, $i);        
                       $dateKeysUpdated = array();
                       foreach($dateKeys as $keys=>$new_data){
                           if($dataColumns[$new_data] ==  $colsArr){
                               $dateKeysUpdated[] = $new_data;
                            }
                       }
                       $mk = '0';
                        foreach($total_maket_segments as $advArr){
                                if(!empty($advArr)){
                                  $j = $dateKeysUpdated[$mk];
                                  $final_array[$i.'/'.$data['AdvancedSheet']['month'].'/'.$data['AdvancedSheet']['year']][$newCols[$colsArr]][$advArr] = $values[$j];
                                  $mk++;
                                }
                        }
                }
        }
        
        foreach($columnIdsAr as $key =>$colsArr){
               $dateKeys = array_keys($dates, 'Total');
               $dateKeysUpdated = array();
               foreach($dateKeys as $keys=>$new_data){
                   if($dataColumns[$new_data] ==  $colsArr){
                       $dateKeysUpdated[] = $new_data;
                    }
               }
               $mk = '0';
                foreach($total_maket_segments as $advArr){
                    if(!empty($advArr)){
                          $j = $dateKeysUpdated[$mk];
                          $new_total_array['Total'][$newCols[$colsArr]][$advArr] = $values[$j];
                          $mk++;
                    }
                }
        }
        $total_rows_array = array();
        if(!empty($rowIds)){
            foreach($rowIds as $rows){
                
                    $dateKeys = array_keys($dates, '0');
                    $dateKeysUpdated = array();
                     foreach($dateKeys as $keys=>$new_data){
                           if($totalDataColumns[$new_data] ==  $rows){
                               $dateKeysUpdated[] = $new_data;
                            }
                     }
                     //make array with segments
                    $mk = '0';
                    foreach($total_maket_segments as $advArr){
                        if(!empty($advArr)){
                      $j = $dateKeysUpdated[$mk];
                      $total_rows_array['Total'][$column_data[$rows]][$advArr] = $values[$j];
                      $mk++;
                        }
                    }                    
            }
        }
        $this->set('final_array',$final_array);
        $this->set('columns',$newCols);
        $this->set('marketSegments',$newSegments);
        $this->set('final_array_total',$new_total_array);
        $this->set('sheetId',$sheetId);
        $this->set('total_rows_array',$total_rows_array);
        $this->set('lockedIds',$lockedIds);
        $this->set('data',$data);
        
    }
    
    public function staff_index($department_id=null){
        
        if (!empty($this->data) && trim($this->data['AdvancedSheet']['value']) != '') {
            $conditions = array('AdvancedSheet.name LIKE' => "%" . $this->data['AdvancedSheet']['value'] . "%", 'AdvancedSheet.department_id' => $this->params['pass'][0], 'AdvancedSheet.status !=' => 2);
        } else {
            $conditions = array('AdvancedSheet.status !=' => 2, 'AdvancedSheet.department_id' => $this->params['pass'][0]);
        }
        
        $userAdvancedSheets = $this->AdvancedSheet->find('all', array('conditions' => $conditions, 'contain' => array('User'), 'order' => array('AdvancedSheet.year ASC', 'AdvancedSheet.month ASC')));
        
        $depts_obj = ClassRegistry::init('DepartmentsUser');
        $department = $depts_obj->field('department_name', array('DepartmentsUser.department_id' => $this->params['pass'][0]));
        $mAdvancedSheets = $this->AdvancedSheet->find('all', array('conditions' => $conditions, 'fields' => array('DISTINCT AdvancedSheet.year, AdvancedSheet.month'), 'recursive' => -1, 'order' => 'AdvancedSheet.year ASC'));
        $this->set(compact('userAdvancedSheets', 'userId', 'department', 'dept_id', 'mAdvancedSheets'));
        $last_AdvancedSheet = $this->AdvancedSheet->find('first', array('conditions' => array('AdvancedSheet.status !=' => 2, 'AdvancedSheet.department_id' => $this->params['pass'][0]), 'fields' => array('AdvancedSheet.id'), 'recursive' => -1, 'order' => 'AdvancedSheet.year DESC, AdvancedSheet.month DESC'));
        $this->set('last_AdvancedSheet', $last_AdvancedSheet);
        
    }
    
    function staff_webform($sheetId){
        $conditions = array('AdvancedSheet.id'=>$sheetId);
        $this->AdvancedSheet->contain(array('Column','Row','AdvanceData','Template','User'));
        $data = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
        $sheet_name = $data['AdvancedSheet']['name'];
        $user_id = $data['AdvancedSheet']['user_id'];
        $department_id = $data['AdvancedSheet']['department_id'];
        $dept_obj = ClassRegistry::init('Department');
	$dept_name = $dept_obj->field('name',array('id'=>$department_id));
        $username = $data['User']['username'];
        $this->set('sheet_name',$sheet_name);
        $this->set('dept_name',$dept_name);
        $this->set('username',$username);
        $rows_obj = ClassRegistry::init('ResultColumnFormula');
        $rows_data = $rows_obj->find('all',array('conditions'=>array('advanced_sheet_id'=>$sheetId),'fields'=>array('ResultColumnFormula.column_id','ResultColumnFormula.is_locked','ResultColumnFormula.formula'),'order'=>  array('order ASC'),'recursive'=>-1));
        $rowIds = Set::extract('/ResultColumnFormula/column_id',$rows_data);
        $lockedRows = Set::extract('/ResultColumnFormula/is_locked',$rows_data);
        $rowsFormulas = Set::extract('/ResultColumnFormula/formula',$rows_data);
        $this->Column = ClassRegistry::init('Column');
        $column_data = $this->Column->find('list',array('conditions'=>array('Column.id'=>$rowIds , 'Column.status !='=>2)));
        $lockedIds = array();
        foreach($lockedRows as $key => $locked){
            if($locked == '1'){
                $lockedIds[] = $column_data[$rowIds[$key]];
            }
        }
        $totalDataColumns = Set::extract('/total_row_id', $data['AdvanceData']);
        $dates  = Set::extract('/date', $data['AdvanceData']);
        $values = Set::extract('/value', $data['AdvanceData']);
        $dataColumns = Set::extract('/column_id', $data['AdvanceData']);
        $dataRows = Set::extract('/row_id', $data['AdvanceData']);
        
        $marketSegmentIds = Set::extract('/market_segment_id', $data['AdvanceData']);
        $sheetmarketSegmentIds = explode(',',$data['AdvancedSheet']['market_segments']);
        
        $columnIds = Set::extract('/id', $data['Column']);
        $columns = Set::extract('/name', $data['Column']);
        $numDays = date('t', mktime(0, 0, 0, $data['AdvancedSheet']['month'], 1, $data['AdvancedSheet']['year']));
        $total_maket_segments = array_unique($marketSegmentIds);
        $newCols = array();
        foreach($columnIds as $col_key=>$col_val)
        {
            $newCols[$col_val] = $columns[$col_key];
        }
        $columnIdsAr = array_unique($columnIds);
        asort($columnIdsAr);
        $newSegments = array();
        foreach($sheetmarketSegmentIds as $seg_key=>$seg_val)
        {
            if(!empty($seg_val)){
            $ms_obj = ClassRegistry::init('MarketSegment');
            $marketsegments = $ms_obj->find('first', array('conditions' => array('MarketSegment.id' => $seg_val),'fields'=>array('name')));
            $newSegments[$seg_val] = $marketsegments['MarketSegment']['name'];
            }
        }
        
        $final_array  = array(); 
        $final_array_total = array();
        for ($i = 1; $i <= $numDays; $i++) {
                foreach($columnIdsAr as $key =>$colsArr){
                       $dateKeys = array_keys($dates, $i);        
                       $dateKeysUpdated = array();
                       foreach($dateKeys as $keys=>$new_data){
                           if($dataColumns[$new_data] ==  $colsArr){
                               $dateKeysUpdated[] = $new_data;
                            }
                       }
                       $mk = '0';
                        foreach($total_maket_segments as $advArr){
                                if(!empty($advArr)){
                                  $j = $dateKeysUpdated[$mk];
                                  $final_array[$i.'/'.$data['AdvancedSheet']['month'].'/'.$data['AdvancedSheet']['year']][$newCols[$colsArr]][$advArr] = $values[$j];
                                  $mk++;
                                }
                        }
                }
        }
        
        foreach($columnIdsAr as $key =>$colsArr){
               $dateKeys = array_keys($dates, 'Total');
               $dateKeysUpdated = array();
               foreach($dateKeys as $keys=>$new_data){
                   if($dataColumns[$new_data] ==  $colsArr){
                       $dateKeysUpdated[] = $new_data;
                    }
               }
               $mk = '0';
                foreach($total_maket_segments as $advArr){
                    if(!empty($advArr)){
                          $j = $dateKeysUpdated[$mk];
                          $new_total_array['Total'][$newCols[$colsArr]][$advArr] = $values[$j];
                          $mk++;
                    }
                }
        }
        $total_rows_array = array();
        if(!empty($rowIds)){
            foreach($rowIds as $rows){
                
                    $dateKeys = array_keys($dates, '0');
                    $dateKeysUpdated = array();
                     foreach($dateKeys as $keys=>$new_data){
                           if($totalDataColumns[$new_data] ==  $rows){
                               $dateKeysUpdated[] = $new_data;
                            }
                     }
                     //make array with segments
                    $mk = '0';
                    foreach($total_maket_segments as $advArr){
                        if(!empty($advArr)){
                      $j = $dateKeysUpdated[$mk];
                      $total_rows_array['Total'][$column_data[$rows]][$advArr] = $values[$j];
                      $mk++;
                        }
                    }                    
            }
        }
        $this->set('final_array',$final_array);
        $this->set('columns',$newCols);
        $this->set('marketSegments',$newSegments);
        $this->set('final_array_total',$new_total_array);
        $this->set('sheetId',$sheetId);
        $this->set('total_rows_array',$total_rows_array);
        $this->set('lockedIds',$lockedIds);
        $this->set('data',$data);
        
    }
    
    
    //CSV Import for Hotel Simola
    function admin_import_simola($sheetId){
         // Configure::write('debug',2);
           
        //$this->layout = '';
        $this->set('sheetId',$sheetId);

        if (!empty($this->data)) {
            if (!$this->data['AdvancedSheet']['browse_file']['name']) {
                $this->Session->setFlash(__('Please uploaded file!', true));
                $this->redirect(array('action' => 'admin_import_simola', $sheetId));
            }

            $path_parts = pathinfo($this->data['AdvancedSheet']['browse_file']["name"]);
            $extension = strtolower($path_parts['extension']);
            if ($extension != 'xls' && $extension != 'csv') {
                $this->Session->setFlash(__('Please uploaded CSV file!', true));
                $this->redirect(array('action' => 'admin_import_simola', $sheetId));
            }

            $handle = fopen($this->data['AdvancedSheet']['browse_file']['tmp_name'], 'r');
            if (!$handle) {
                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                $this->redirect(array('action' => 'admin_import_simola', $sheetId));
            }
            $new_data = array();
            $row = 1;

                $conditions = array('AdvancedSheet.id'=>$sheetId);
                $this->AdvancedSheet->recursive = '-1';
                $sheetData = $this->AdvancedSheet->find('first', array('conditions' => $conditions));
                $sheet_segments = explode(',',$sheetData['AdvancedSheet']['market_segments']);
                
                $this->MarketSegment = ClassRegistry::init('MarketSegment');
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$sheet_segments)));
                //echo '<pre>'; print_r($marketsegments); exit;
                
                $header = array();
                $header = fgetcsv($handle, 1000, ",");
                unset($header['0']);
                
                 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                     if($data['0'] !== 'Total'){
                         $data['0'] = trim($data['0']);
                         $market_segment_id = array_search(strtolower($data['0']), array_map('strtolower', $marketsegments));
                         unset($data['0']);

                         foreach($data as $key=>$vals){
                             //import for all day BOB vals
                             $explodeDate = explode("\n", $header[$key]);
                             $date = $explodeDate[1];
                             if(!empty($date)){
                                 $this->AdvancedSheet->AdvanceData->updateAll(array('AdvanceData.value' => $vals), array('AdvanceData.advanced_sheet_id'=>$sheetId,'AdvanceData.column_id'=>'62','AdvanceData.row_id'=>0,'AdvanceData.market_segment_id'=>$market_segment_id,'AdvanceData.date'=>$date));
                             }
                         }
                    }
                 }
                
                //Update Total BOB for each Segments
                foreach($sheet_segments as $sheetSeg){
                    
                    $sum_bob = $this->AdvancedSheet->AdvanceData->find('all', array(
                        'conditions' => array(
                        'AdvanceData.advanced_sheet_id' => $sheetId,'AdvanceData.column_id' => '62','AdvanceData.date !=' => 'Total','AdvanceData.market_segment_id' => $sheetSeg),
                        'fields' => array('sum(AdvanceData.value) as segment_sum'
                        )
                    )
                    );

                    $this->AdvancedSheet->AdvanceData->updateAll(array('AdvanceData.value' => $sum_bob[0][0]['segment_sum']), array('AdvanceData.advanced_sheet_id'=>$sheetId,'AdvanceData.column_id'=>'62','AdvanceData.row_id'=>0,'AdvanceData.market_segment_id'=>$sheetSeg,'AdvanceData.date'=>'Total'));
                    
                }
                
                //Update Result columns as per the formula
                if(!empty($sheetId)){
                    $update_cols = $this->requestAction('/AdvancedSheets/update_result_cols/'.$sheetId);
                }
                //Update Result column as per formula ends here
                
                $this->Session->setFlash(__('File Imported successfully.', true));
               $this->redirect('/admin/advancedSheets/webform/'.$sheetId);
            
            }
            
        
    }
     //admin_import_simola function ends here
 
    
    
    
}
//end class