<?php

class Sheet extends AppModel {

    var $name = 'Sheet';
    var $displayField = 'name';
    var $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please enter sheet name',
            ),
        ),
        'month' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Please select month',
                'last' => true,
            ),
            'unique' => array(
                'rule' => array('uniqueDate'),
                'message' => 'Department Sheet already created for the selected month',
            ),
        ),
        'year' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Please select year',
            ),
        ),
    );
    var $belongsTo = array('User');
    var $hasMany = array(
        'Datum' => array(
            'order' => array('Datum.date ASC', 'Datum.column_id ASC')
        ),
        'Formula' => array(
            'order' => array('Formula.column_order ASC')
        ),
        'EmailSheet' => array(
            'className' => 'EmailSheet',
            'foreignKey' => 'sheet_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => 'EmailSheet.id ASC',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    var $hasAndBelongsToMany = array('Column' => array('order' => array('Column.id ASC')), 'Row' => array('order' => array('Row.id ASC')));

    /**
     * Custom validation rule to block addition of duplicate sheets
     * @access public
     * @return boolean true/false
     */
    function uniqueDate() {
        $conditions = array(
            'Sheet.user_id' => $this->data['Sheet']['user_id'],
            'Sheet.Month' => $this->data['Sheet']['month'],
            'Sheet.year' => $this->data['Sheet']['year'],
            'Sheet.department_id' => $this->data['Sheet']['department_id'],
            'Sheet.status' => 1,
        );

        if ($this->id) {
            $conditions['Sheet.id !='] = $this->id;
        }

        if ($this->hasAny($conditions)) {
            return false;
        }

        return true;
    }

//end uniqueDate()

    /**
     * Method to find the sheet Data
     * 
     * @param int $sheetId The Department Sheet ID
     * @access public
     * @return array
     */
    function getData($sheetId) {

        $checkDecimal = $this->ColumnsSheet->find('list', array('conditions' => array('ColumnsSheet.sheet_id' => $sheetId), 'fields' => array('ColumnsSheet.column_id', 'ColumnsSheet.is_decimal')));

        $this->contain(array('User' => array('username', 'department_name'), 'Datum', 'Column'));
        $this->contain(array('Column', 'Row', 'Datum', 'User', 'Formula'));
        $data = $this->findById($sheetId);

        $filteredData = array();
        $columnIds = Set::extract('/id', $data['Column']);
        foreach ($data['Datum'] as $webformData) {
            if (in_array($webformData['column_id'], $columnIds)) {
                array_push($filteredData, $webformData);
            }
        }

        $data['Datum'] = $filteredData;

        $dates = Set::extract('/date', $data['Datum']);

        $values = Set::extract('/value', $data['Datum']);
        $dataColumns = Set::extract('/column_id', $data['Datum']);
        $dataRows = Set::extract('/row_id', $data['Datum']);

        $newdataRows = array();
        foreach ($dataRows as $row_key => $row_val) {
            if ($row_val != '0') {
                $newdataRows[$row_key] = $row_val;
            }
        }

        $columnIds = Set::extract('/id', $data['Column']);
        $columns = Set::extract('/name', $data['Column']);
        $rowIds = Set::extract('/id', $data['Row']);
        $rows = Set::extract('/name', $data['Row']);
        $numDays = date('t', mktime(0, 0, 0, $data['Sheet']['month'], 1, $data['Sheet']['year']));
        $departmentData = array();

        for ($num_check = 0; $num_check < count($columns); $num_check++) {

            $gtotal = 0;
            $check_name = $columns[$num_check];
            $check_id = $columnIds[$num_check];
            for ($i = 1; $i <= $numDays; $i++) {

                $dateKeys = array_keys($dates, $i);

                $columnData[$i]['id'] = $i;
                $columnData[$i]['sheetId'] = $sheetId;
                $columnData[$i]['Date'] = date('d/m/y', mktime(0, 0, 0, $data['Sheet']['month'], $i, $data['Sheet']['year'])); //$data['Sheet']['year'] .",".  $data['Sheet']['month'] .",". $i;

                foreach ($columnIds as $key => $columnId) {
                    if ($columns[$key] == 'DOW') {
                        $columnData[$i][$columns[$key]] = date('D', mktime(0, 0, 0, $data['Sheet']['month'], $i, $data['Sheet']['year']));
                    } else {

                        if (isset($dateKeys[$key])) {

                            $j = $dateKeys[$key];
                            if ((int) $values[$j] != $values[$j]) {
                                if ($checkDecimal[$columnId] == '1'){
                                    if($columnId == '78'){
                                     $valTemp = number_format($values[$j], 1, '.', ',');    // 1 decimal for TripAdvisor
                                    }else{
                                     $valTemp = number_format($values[$j], 2, '.', ',');   
                                    }
                                }else{
                                    $valTemp = number_format($values[$j], 0, '.', ',');
                                }
                            } else {
                                $valTemp = $values[$j];
                            }
                            $columnData[$i][$columns[$key]] = ($dates[$j] == $i) && ($dataColumns[$j] == $columnId) ? $valTemp : "0";

                            $gotVal = ($dates[$j] == $i) && ($dataColumns[$j] == $columnId) ? $valTemp : "0";

                            //////////////////////////////////////   for calculating verticcal total values.....//////////////////////
                            if ($check_name == $columns[$key]) {
                                $gtotal += str_replace(",", "", $gotVal);
                            }
                            ///////////////////////////////////////////////////////////////////////////////////////////////////
                        } else {
                            $columnData[$i][$columns[$key]] = "0";
                        }
                    }
                }
            }//end for
            ///////////////////for calculating vertical total///////////////////////
            $columnData[$numDays + 1]['id'] = $numDays + 1;
            $columnData[$numDays + 1]['sheetId'] = $sheetId;
            $columnData[$numDays + 1]['Date'] = "Total";

            if ($checkDecimal[$check_id] == '1') {
                $columnData[$i][$check_name] = number_format($gtotal, 2, '.', ',');
            } else {
                $columnData[$i][$check_name] = number_format($gtotal, 0, '.', ',');
            }

            ////////////////////////////////////////////////////////////////////////

            /* for adding the extra rows */
            if (!empty($rows)) {
                $ro = 2;
                $k = 1;
                foreach ($rows as $single_key => $single_val) {
                    $dateKeys = array_keys($dates, $k);
                    $columnData[$numDays + $ro]['id'] = $numDays + $ro;
                    $columnData[$numDays + $ro]['sheetId'] = $sheetId;
                    $columnData[$numDays + $ro]['Date'] = $single_val;
                    foreach ($columnIds as $key => $columnId) {
                        $gather_row_value = $this->Datum->find('first', array('conditions' => array('Datum.row_id' => $rowIds[$single_key], 'Datum.column_id' => $columnId, 'Datum.sheet_id' => $sheetId), 'fields' => array('Datum.value')));
                        if (!empty($gather_row_value)) {
                            if ($gather_row_value['Datum']['value'] == '0') {
                                $columnData[$numDays + $ro][$columns[$key]] = '';
                            } else {
                                $columnData[$numDays + $ro][$columns[$key]] = $gather_row_value['Datum']['value'];
                            }

                            if ($checkDecimal[$columnId] == '1')
                                $columnData[$numDays + $ro][$columns[$key]] = number_format($columnData[$numDays + $ro][$columns[$key]], 2, '.', ',');
                            else
                                $columnData[$numDays + $ro][$columns[$key]] = number_format($columnData[$numDays + $ro][$columns[$key]], 0, '.', ',');
                        }
                        else {
                            $columnData[$numDays + $ro][$columns[$key]] = '0';
                        }
                    }
                    $ro++;
                    $k++;
                }
            }
        }//end  first for...
//check formula for Total

        $formula_details = $this->Formula->find('all', array('conditions' => array('Formula.sheet_id' => $sheetId, 'Formula.row_id' => 'Total'), 'order' => array('Formula.column_order')));

        $operatorArray = array("+", "-", "*", "/", "(", ")");

        foreach ($formula_details as $formula) {
            $resultColumn = $this->Column->find('first', array('conditions' => array('Column.id' => $formula['Formula']['column_id']), 'recursive' => -1));
            $operands = explode(" ", $formula['Formula']['formula']);
            $newFormula = array();
            foreach ($operands as $operand) {
                if (substr($operand, 0, 1) == "C") {
                    $column_data = $this->Column->find('first', array('conditions' => array('Column.id' => substr($operand, 1)), 'recursive' => -1));
                    $totalForColumn = $columnData[$numDays + 1][$column_data['Column']['name']];
                    array_push($newFormula, $totalForColumn);
                    //$column_data = $this->Column->findById();
                } elseif (in_array($operand, $operatorArray)) {
                    array_push($newFormula, $operand);
                } elseif (is_numeric($operand)) {
                    array_push($newFormula, $operand);
                }
            }

            $formulaForTotal = implode(" ", $newFormula);

            $column_value = $this->calculate_string($formulaForTotal);

            if ($checkDecimal[$formula['Formula']['column_id']] == '1') {
                $columnData[$numDays + 1][$resultColumn['Column']['name']] = number_format($column_value, 2, '.', ',');
            } else {
                $columnData[$numDays + 1][$resultColumn['Column']['name']] = number_format($column_value, 0, '.', ',');
            }
        }
        return array_values($columnData);
    }

//end getData()
    //Called from Webforms Sheets
    function getsheetdata($sheetId) {

        $checkDecimal = $this->ColumnsSheet->find('list', array('conditions' => array('ColumnsSheet.sheet_id' => $sheetId), 'fields' => array('ColumnsSheet.column_id', 'ColumnsSheet.is_decimal')));

        $this->contain(array('User' => array('username', 'department_name'), 'Datum', 'Column'));
        $this->contain(array('Column', 'Row', 'Datum', 'User', 'Formula'));
        $data = $this->findById($sheetId);

        $filteredData = array();
        $columnIds = Set::extract('/id', $data['Column']);
        foreach ($data['Datum'] as $webformData) {
            if (in_array($webformData['column_id'], $columnIds)) {
                array_push($filteredData, $webformData);
            }
        }

        $data['Datum'] = $filteredData;

        $dates = Set::extract('/date', $data['Datum']);

        $values = Set::extract('/value', $data['Datum']);
        $dataColumns = Set::extract('/column_id', $data['Datum']);
        $dataRows = Set::extract('/row_id', $data['Datum']);

        $newdataRows = array();
        foreach ($dataRows as $row_key => $row_val) {
            if ($row_val != '0') {
                $newdataRows[$row_key] = $row_val;
            }
        }

        $columnIds = Set::extract('/id', $data['Column']);
        $columns = Set::extract('/name', $data['Column']);
        $rowIds = Set::extract('/id', $data['Row']);
        $rows = Set::extract('/name', $data['Row']);
        $numDays = date('t', mktime(0, 0, 0, $data['Sheet']['month'], 1, $data['Sheet']['year']));
        $departmentData = array();

        for ($num_check = 0; $num_check < count($columns); $num_check++) {

            $gtotal = 0;
            $check_name = $columns[$num_check];
            $check_id = $columnIds[$num_check];
            for ($i = 1; $i <= $numDays; $i++) {

                $dateKeys = array_keys($dates, $i);

                $columnData[$i]['id'] = $i;
                $columnData[$i]['sheetId'] = $sheetId;
                $columnData[$i]['Date'] = date('d/m/y', mktime(0, 0, 0, $data['Sheet']['month'], $i, $data['Sheet']['year'])); //$data['Sheet']['year'] .",".  $data['Sheet']['month'] .",". $i;

                foreach ($columnIds as $key => $columnId) {
                    if ($columns[$key] == 'DOW') {
                        $columnData[$i][$columns[$key]] = date('D', mktime(0, 0, 0, $data['Sheet']['month'], $i, $data['Sheet']['year']));
                    } else {

                        if (isset($dateKeys[$key])) {

                            $j = $dateKeys[$key];
                            if ((int) $values[$j] != $values[$j]) {
                                if ($checkDecimal[$columnId] == '1'){
                                    if($columnId == '78'){
                                        $valTemp = number_format($values[$j], 1, '.', ',');    // 1 decimal for TripAdvisor
                                       }else{
                                        $valTemp = number_format($values[$j], 2, '.', ',');   
                                       }
                                    //$valTemp = number_format($values[$j], 2, '.', ',');
                                }else{
                                    $valTemp = number_format($values[$j], 0, '.', ',');
                                }
                            } else {
                                $valTemp = $values[$j];
                            }
                            $columnData[$i][$columns[$key]] = ($dates[$j] == $i) && ($dataColumns[$j] == $columnId) ? $valTemp : "0";

                            $gotVal = ($dates[$j] == $i) && ($dataColumns[$j] == $columnId) ? $values[$j] : "0";

                            //////////////////////////////////////   for calculating verticcal total values.....//////////////////////
                            if ($check_name == $columns[$key]) {
                                $gtotal += str_replace(",", "", $gotVal);
                            }
                            ///////////////////////////////////////////////////////////////////////////////////////////////////
                        } else {
                            $columnData[$i][$columns[$key]] = "0";
                        }
                    }
                }
            }//end for
            ///////////////////for calculating vertical total///////////////////////
            $columnData[$numDays + 1]['id'] = $numDays + 1;
            $columnData[$numDays + 1]['sheetId'] = $sheetId;
            $columnData[$numDays + 1]['Date'] = "Fcst Total";

            if ($check_name == 'Notes' || $check_name == 'TripAdvisor') {
                $columnData[$i][$check_name] = '';
            } else {
                if ($checkDecimal[$check_id] == '1') {
                    $columnData[$i][$check_name] = number_format($gtotal, 2, '.', ',');
                } else {
                    $columnData[$i][$check_name] = number_format($gtotal, 0, '.', ',');
                }
            }

            /* for adding the extra rows */
            if (!empty($rows)) {
                $ro = 2;
                $k = 1;
                foreach ($rows as $single_key => $single_val) {
                    $dateKeys = array_keys($dates, $k);
                    $columnData[$numDays + $ro]['id'] = $numDays + $ro;
                    $columnData[$numDays + $ro]['sheetId'] = $sheetId;
                    $columnData[$numDays + $ro]['Date'] = $single_val;
                    foreach ($columnIds as $key => $columnId) {
                        $gather_row_value = $this->Datum->find('first', array('conditions' => array('Datum.row_id' => $rowIds[$single_key], 'Datum.column_id' => $columnId, 'Datum.sheet_id' => $sheetId), 'fields' => array('Datum.value')));
                        if (!empty($gather_row_value)) {
                            if ($gather_row_value['Datum']['value'] == '0') {
                                $columnData[$numDays + $ro][$columns[$key]] = '';
                            } else {
                                $columnData[$numDays + $ro][$columns[$key]] = $gather_row_value['Datum']['value'];
                            }

                            if ($checkDecimal[$columnId] == '1')
                                $columnData[$numDays + $ro][$columns[$key]] = number_format($columnData[$numDays + $ro][$columns[$key]], 2, '.', ',');
                            else
                                $columnData[$numDays + $ro][$columns[$key]] = number_format($columnData[$numDays + $ro][$columns[$key]], 0, '.', ',');
                        }
                        else {
                            $columnData[$numDays + $ro][$columns[$key]] = '0';
                        }
                    }
                    $ro++;
                    $k++;
                }
            }
        }//end  first for...

        $formula_details = $this->Formula->find('all', array('conditions' => array('Formula.sheet_id' => $sheetId, 'Formula.row_id' => 'Total'), 'order' => array('Formula.column_order')));

        $operatorArray = array("+", "-", "*", "/", "(", ")");

        foreach ($formula_details as $formula) {
            $resultColumn = $this->Column->find('first', array('conditions' => array('Column.id' => $formula['Formula']['column_id']), 'recursive' => -1));
            $operands = explode(" ", $formula['Formula']['formula']);
            $newFormula = array();
            foreach ($operands as $operand) {
                if (substr($operand, 0, 1) == "C") {
                    $column_data = $this->Column->find('first', array('conditions' => array('Column.id' => substr($operand, 1)), 'recursive' => -1));
                    $totalForColumn = $columnData[$numDays + 1][$column_data['Column']['name']];
                    array_push($newFormula, $totalForColumn);
                } elseif (in_array($operand, $operatorArray)) {
                    array_push($newFormula, $operand);
                } elseif (is_numeric($operand)) {
                    array_push($newFormula, $operand);
                }
            }

            $formulaForTotal = implode(" ", $newFormula);

            $column_value = $this->calculate_string($formulaForTotal);

            if ($checkDecimal[$formula['Formula']['column_id']] == '1') {
                $columnData[$numDays + 1][$resultColumn['Column']['name']] = number_format($column_value, 2, '.', ',');
            } else {
                $columnData[$numDays + 1][$resultColumn['Column']['name']] = number_format($column_value, 0, '.', ',');
            }
        }
        return array_values($columnData);
    }

//end getDataWebform()

    /**
     * Method to find the sheet Data for date columns only 
     * (same as getData but it returns only for dates not for the row section at the bottom of webform)
     * @param int $sheetId The Department Sheet ID
     * @access public
     * @return array
     */
    function getWebformData($sheetId) {

        $checkDecimal = $this->ColumnsSheet->find('list', array('conditions' => array('ColumnsSheet.sheet_id' => $sheetId), 'fields' => array('ColumnsSheet.column_id', 'ColumnsSheet.is_decimal')));

        $this->contain(array('Datum', 'Column'));

        $data = $this->findById($sheetId);

        $filteredData = array();
        $columnIds = Set::extract('/id', $data['Column']);
        foreach ($data['Datum'] as $webformData) {
            if (in_array($webformData['column_id'], $columnIds)) {
                array_push($filteredData, $webformData);
            }
        }

        $data['Datum'] = $filteredData;
        $dates = Set::extract('/date', $data['Datum']);
        $values = Set::extract('/value', $data['Datum']);
        $dataColumns = Set::extract('/column_id', $data['Datum']);

        $columnIds = Set::extract('/id', $data['Column']);
        $columns = Set::extract('/name', $data['Column']);
        $numDays = date('t', mktime(0, 0, 0, $data['Sheet']['month'], 1, $data['Sheet']['year']));
        $departmentData = array();

        for ($num_check = 0; $num_check < count($columns); $num_check++) {
            for ($i = 1; $i <= $numDays; $i++) {
                $dateKeys = array_keys($dates, $i);
                $columnData[$i]['id'] = $i;
                $columnData[$i]['sheetId'] = $sheetId;
                $columnData[$i]['Date'] = date('d/m/y', mktime(0, 0, 0, $data['Sheet']['month'], $i, $data['Sheet']['year'])); //$data['Sheet']['year'] .",".  $data['Sheet']['month'] .",". $i;

                foreach ($columnIds as $key => $columnId) {
                    if ($columns[$key] == 'DOW') {
                        $columnData[$i][$columns[$key]] = date('D', mktime(0, 0, 0, $data['Sheet']['month'], $i, $data['Sheet']['year']));
                    } else {
                        if (isset($dateKeys[$key])) {
                            $j = $dateKeys[$key];
                            if ((int) $values[$j] != $values[$j]) {
                                if ($checkDecimal[$columnId] == '1')
                                    $valTemp = @number_format($values[$j], 2, '.', ',');
                                else
                                    $valTemp = number_format($values[$j], 0, '.', ',');
                            } else {
                                $valTemp = $values[$j];
                            }
                            $columnData[$i][$columns[$key]] = ($dates[$j] == $i) && ($dataColumns[$j] == $columnId) ? $valTemp : "0";
                        } else {
                            $columnData[$i][$columns[$key]] = "0";
                        }
                    }
                }
            }//end for
        }//end  first for...
        return array_values($columnData);
    }

//end getWebformData()

    /**
     * Method :importWebform to import the sheet data for all dates only
     * @param int $sheetId The Sheet ID and array import data
     * @access public
     * @return void
     * Added : 14 March 2016
     */
    function importWebform($sheetId, $pdata = null) {
        $postData = $pdata;
        $columnNames = array_keys(array_slice($postData, 3));

        $formula_details = $this->Formula->find('all', array('conditions' => array('Formula.sheet_id' => $sheetId), 'order' => array('Formula.column_order'), 'recursive' => -1));

        $sort_by_cols = array();
        $appplied_formulae = array();
        $formula_columns = array();
        $formulas = array();
        $seleted_formula_column = array();

        foreach ($formula_details as $formula) {
            $sort_by_cols[] = $formula['Formula']['column_id'];
            if ($formula['Formula']['row_id'] == '0') {
                array_push($formula_columns, $formula['Formula']['column_id']);
                array_push($formulas, $formula['Formula']['formula']);
            }
        }

        foreach ($columnNames as $col_key => $col_value) {
            $search_column_data = $this->Column->find('first', array('conditions' => array('Column.name' => $col_value, 'Column.status !=' => 2), 'fields' => array('Column.id'), 'recursive' => -1));
            $check_col_id = $search_column_data['Column']['id'];
            foreach ($sort_by_cols as $colids) {
                if ($check_col_id == $colids) {
                    $appplied_formulae[$colids] = $col_value;
                    unset($columnNames[$col_key]);
                }
            }
        }
        for ($i = 0; $i < count($sort_by_cols); $i++) {
            foreach ($appplied_formulae as $app_key => $app_val) {
                if ($sort_by_cols[$i] == $app_key) {
                    $sort_by_cols[$i] = $app_val;
                }
            }
        }
        $testarray = array();
        $testarray = $columnNames;
        $new_cols_names = array();

        foreach ($testarray as $cols) {
            $new_cols_names[] = $cols;
        }
        unset($columnNames);
        $columnNames = array_merge($new_cols_names, $sort_by_cols);

        //save all the result column names
        $dateArr = explode('/', $postData['Date']); //improve
        // Prepare the array of data to be saved
        $data['sheet_id'] = (int) $postData['sheetId'];
        $data['date'] = (int) $dateArr[0];

        $columnNames = array_unique($columnNames);

        $columns_details = $this->Column->find('list', array('conditions' => array('Column.name' => $columnNames, 'Column.status !=' => 2)));

        foreach ($columns_details as $columnId => $column) {
            $data['column_id'] = $columnId;

            $column_formula1 = array();
            $seleted_formula_column1 = array();

            //check if the column id is there in the array of formula result columns
            foreach ($formula_columns as $single_column_k => $single_column_v) {
                if ($single_column_v == $data['column_id']) {
                    $column_formula1[] = $formulas[$single_column_k];
                    $seleted_formula_column1[] = $formula_columns[$single_column_k];
                }
            }

            //split the formula and take all the values in an array
            if (!empty($column_formula1)) {
                $arr_formula_val = explode(" ", $column_formula1[0]);
                $arr_indx = 0;
                foreach ($arr_formula_val as $val) {
                    if (substr($val, 0, 1) == "C") {
                        $acutal_val = $postData[$columns_details[substr($val, 1)]];
                        if (substr_count($acutal_val, '.') == '2') {
                            if (substr($acutal_val, -3) == '.00') {
                                $acutal_val = str_replace('.00', '', $acutal_val);
                            }
                        }
                        $arr_formula_val[$arr_indx] = $acutal_val;
                    }
                    $arr_indx += 1;
                }
                $math_string = implode("", $arr_formula_val);
                $data['value'] = $this->calculate_string($math_string);
                $postData[$column] = $data['value'];
            } else {
                $data['value'] = $postData[$column];
            }

            $conditions = array(
                'Datum.sheet_id' => $data['sheet_id'],
                'Datum.column_id' => $data['column_id'],
                'Datum.row_id' => '0',
                'Datum.date' => $data['date']
            );

            $dataId = $this->Datum->field('id', $conditions);
            if ($dataId) {
                $this->Datum->query("UPDATE `data` SET `modified` = '" . date('Y-m-d H:i:s') . "',`value`='" . $data['value'] . "'  WHERE `id`= '" . $dataId . "'");
            } else {
                $this->Datum->create();
                $this->Datum->save($data);
            }
        }
        return true;
    }

    /**
     * Method :updateRowsTotal to Update the total and Rows values only for a sheet
     * @param int $sheetId The Sheet ID
     * @access public
     * @return void
     * Added : 14 March 2016
     */
    function updateRowsTotal($sheetId) {
        /* added to calculate locked rows on each update */
        $rows_obj = ClassRegistry::init('RowsSheet');
        $obtained_rows = $rows_obj->find('list', array('conditions' => array('sheet_id' => $sheetId), 'fields' => array('RowsSheet.row_id'), 'recursive' => -1));

        $sheetdates = $this->find('first', array('fields' => 'month,year', 'conditions' => array('id' => $sheetId), 'recursive' => -1));
        $numDays = date('t', mktime(0, 0, 0, $sheetdates['Sheet']['month'], 1, $sheetdates['Sheet']['year']));

        $fromula_for_rows = array();
        $loop_count = 0;
        $operattions_operators = array("+", "-", "*", "/");
        $final_values = array();
        $final_update_to_db = array();
        $total_row_done = false;
        $calc_for_total = array();

        $index = 0;
        $new_row_formulas_arr = $this->Formula->find('all', array('conditions' => array('Formula.row_id' => "Total", 'Formula.sheet_id' => $sheetId), 'order' => array('Formula.column_order'), 'recursive' => -1));

        if (!empty($new_row_formulas_arr)) {
            foreach ($new_row_formulas_arr as $new_row_formulas) {
                $formula_array_stage1 = explode(" ", $new_row_formulas['Formula']['formula']);

                foreach ($formula_array_stage1 as $sub_cols_rows) {
                    if (substr($sub_cols_rows, 0, 1) == "C") {
                        $temp_col = explode("C", $sub_cols_rows);
                        $current_col_id = $temp_col[1];
                        $fromula_for_rows[$index]['col_id'] = $current_col_id;
                    } elseif (substr($sub_cols_rows, 0, 1) == "R") {
                        $temp_col = explode("R", $sub_cols_rows);
                        $current_row_id = $temp_col[1];
                        $fromula_for_rows[$index]['row_id'] = $current_row_id;
                    } elseif ($sub_cols_rows == "Total") {
                        $fromula_for_rows[$index]['row_id'] = "Total";
                    } elseif (in_array($sub_cols_rows, $operattions_operators)) {
                        $index++;
                        $fromula_for_rows[$index]['operator'] = $sub_cols_rows;
                    }
                }

                $new_math_string = array();
                foreach ($fromula_for_rows as $sticky_formula) {
                    $math_string = "";
                    $temp_tot = 0;
                    if (!isset($sticky_formula['operator'])) {
                        if ($sticky_formula['row_id'] == "Total") {

                            $total_datum_value = $this->Datum->query("SELECT SUM(replace(value, ',', '')) as value FROM data WHERE sheet_id = " . $sheetId . " AND column_id = " . $sticky_formula['col_id'] . " AND row_id = '0' AND date != '0' and date <= $numDays");
                            $temp_tot = $total_datum_value[0][0]['value'];

                            $math_string .= "" . $temp_tot;
                        } else {
                            $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id']), 'fields' => array('Datum.value'), 'recursive' => -1));
                            if (empty($cols_data['Datum']['value'])) {
                                $cols_data['Datum']['value'] = 0;
                            }
                            $math_string .= "" . $cols_data['Datum']['value'];
                        }
                    } else {
                        if ($sticky_formula['row_id'] == "Total") {

                            $total_datum_value = $this->Datum->query("SELECT SUM(replace(value, ',', '')) as value FROM data WHERE sheet_id = " . $sheetId . " AND column_id = " . $sticky_formula['col_id'] . " AND row_id = '0' AND date != '0' and date <= $numDays");
                            $temp_tot = $total_datum_value[0][0]['value'];

                            $math_string .= $sticky_formula['operator'] . $temp_tot;
                        } else {
                            $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id']), 'fields' => array('Datum.value'), 'recursive' => -1));
                            if (empty($cols_data['Datum']['value'])) {
                                $cols_data['Datum']['value'] = 0;
                            }
                            $math_string .= $sticky_formula['operator'] . $cols_data['Datum']['value'];
                        }
                    }
                    $new_math_string [] = $math_string;
                }
                unset($fromula_for_rows);
                $total_math_string_final = implode("", $new_math_string);

                $final_values[$loop_count] = $this->calculate_string($total_math_string_final);

                $arrTemp = array();

                $arrTemp['sheet_id'] = $sheetId;
                $arrTemp['column_id'] = $new_row_formulas['Formula']['column_id'];
                $arrTemp['row_id'] = $new_row_formulas['Formula']['row_id'];
                $arrTemp['value'] = $final_values[$loop_count];

                array_push($calc_for_total, $arrTemp);

                $loop_count++;
            } // end for $new_row_formulas_arr
        }

        $fromula_for_rows = array();
        $loop_count = 0;
        $operattions_operators = array("+", "-", "*", "/", "(", ")");
        $final_values = array();
        $final_update_to_db = array();

        foreach ($obtained_rows as $rows) {
            $index = 0;
            $new_row_formulas_arr = $this->Formula->find('all', array('conditions' => array('Formula.row_id' => $rows, 'Formula.sheet_id' => $sheetId), 'order' => array('Formula.column_order'), 'recursive' => -1));

            if (!empty($new_row_formulas_arr)) {

                foreach ($new_row_formulas_arr as $new_row_formulas) {

                    $formula_array_stage1 = explode(" ", $new_row_formulas['Formula']['formula']);

                    foreach ($formula_array_stage1 as $sub_cols_rows) {

                        if (substr($sub_cols_rows, 0, 1) == "C") {
                            $temp_col = explode("C", $sub_cols_rows);
                            $current_col_id = $temp_col[1];
                            $fromula_for_rows[$index]['col_id'] = $current_col_id;
                        } elseif (substr($sub_cols_rows, 0, 1) == "R") {
                            $temp_col = explode("R", $sub_cols_rows);
                            $current_row_id = $temp_col[1];
                            $fromula_for_rows[$index]['row_id'] = $current_row_id;
                        } elseif ($sub_cols_rows == "Total") {
                            $fromula_for_rows[$index]['row_id'] = "Total";
                        } elseif (in_array($sub_cols_rows, $operattions_operators)) {
                            $index++;
                            $fromula_for_rows[$index]['operator'] = $sub_cols_rows;
                        } else if (is_numeric($sub_cols_rows)) {
                            $fromula_for_rows[$index]['val'] = $sub_cols_rows;
                        }
                    }
                    $new_math_string = array();
                    foreach ($fromula_for_rows as $sticky_formula) {
                        $math_string = "";
                        $temp_tot = 0;

                        if (!isset($sticky_formula['operator'])) {
                            if ($sticky_formula['row_id'] == "Total") {
                                $is_val_found = false;
                                foreach ($calc_for_total as $arr) {
                                    if ($sticky_formula['col_id'] == $arr['column_id']) {
                                        $temp_tot = $arr['value'];
                                        $is_val_found = true;
                                    }
                                }
                                if (!$is_val_found) {
                                    $total_datum_value = $this->Datum->query("SELECT SUM(replace(value, ',', '')) as value FROM data WHERE sheet_id = " . $sheetId . " AND column_id = " . $sticky_formula['col_id'] . " AND row_id = '0' AND date != '0' and date <= $numDays");
                                    $temp_tot = $total_datum_value[0][0]['value'];
                                }

                                $math_string .= "" . $temp_tot;
                            } else {
                                $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id']), 'fields' => array('Datum.value'), 'recursive' => -1));
                                if (empty($cols_data['Datum']['value'])) {
                                    $cols_data['Datum']['value'] = 0;
                                }
                                $math_string .= "" . $cols_data['Datum']['value'];
                            }
                        } else {
                            if ($sticky_formula['row_id'] == "Total") {

                                $is_val_found = false;
                                foreach ($calc_for_total as $arr) {
                                    if ($sticky_formula['col_id'] == $arr['column_id']) {
                                        $temp_tot = $arr['value'];
                                        $is_val_found = true;
                                    }
                                }
                                if (!$is_val_found) {

                                    $total_datum_value = $this->Datum->query("SELECT SUM(replace(value, ',', '')) as value FROM data WHERE sheet_id = " . $sheetId . " AND column_id = " . $sticky_formula['col_id'] . " AND row_id = '0' AND date != '0' and date <= $numDays");
                                    $temp_tot = $total_datum_value[0][0]['value'];
                                }

                                $math_string .= $sticky_formula['operator'] . $temp_tot;
                            } else {
                                if (!empty($sticky_formula['col_id']) && !empty($sticky_formula['row_id'])) {
                                    //this if is not required here possibly
                                    $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id']), 'fields' => array('Datum.value'), 'recursive' => -1));
                                    if (empty($cols_data['Datum']['value'])) {
                                        $cols_data['Datum']['value'] = 0;
                                    }
                                    $math_string .= $sticky_formula['operator'] . $cols_data['Datum']['value'];
                                } else if (!empty($sticky_formula)) {
                                    foreach ($sticky_formula as $formla) {
                                        $math_string .= $formla;
                                    }
                                }
                            }
                        }
                        //we will have to see if any formula there for column of Total row.
                        $new_math_string [] = $math_string;
                    }
                    unset($fromula_for_rows);

                    $math_string_final = implode("", $new_math_string);

                    $final_values[$loop_count] = $this->calculate_string($math_string_final);
                    $final_update_to_db[$loop_count]['sheet_id'] = $sheetId;
                    $final_update_to_db[$loop_count]['column_id'] = $new_row_formulas['Formula']['column_id'];
                    $final_update_to_db[$loop_count]['row_id'] = $new_row_formulas['Formula']['row_id'];
                    $final_update_to_db[$loop_count]['value'] = $final_values[$loop_count];
                    $final_update_to_db[$loop_count]['date'] = 0;
                    $loop_count++;
                } // end for $new_row_formulas_arr
            }
        }

        foreach ($final_update_to_db as $values) {
            $conditions1_all = array(
                'Datum.sheet_id' => $values['sheet_id'],
                'Datum.column_id' => $values['column_id'],
                'Datum.row_id' => $values['row_id'],
                'Datum.date' => '0'
            );
            $dataId1_all = $this->Datum->field('id', $conditions1_all);
            if ($dataId1_all) {
                $this->Datum->query("UPDATE `data` SET `modified` = '" . date('Y-m-d H:i:s') . "',`value`='" . $values['value'] . "'  WHERE `id`= '" . $dataId1_all . "'");
            } else {
                $this->Datum->create();
                $this->Datum->save($values);
            }
        }
        return true;
    }

    /**
     * Method to import the sheet data
     * @param int $sheetId The Sheet ID
     * @access public
     * @return void
     */
    function importData($sheetId, $pdata = null) {

        if (!empty($pdata)) {
            $postData = $pdata;
        } else {
            $post = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
            $postData = get_object_vars($post->data[0]);
        }

        $columnNames = array_keys(array_slice($postData, 3));

        /* edited on Aug 09 2012 */
        $formula_details = $this->Formula->find('all', array('conditions' => array('Formula.sheet_id' => $sheetId), 'fields' => array('Formula.column_id'), 'order' => array('Formula.column_order'), 'recursive' => -1));

        $sort_by_cols = array();
        $appplied_formulae = array();
        foreach ($formula_details as $current_formulas_columns) {
            $sort_by_cols[] = $current_formulas_columns['Formula']['column_id'];
        }

        foreach ($columnNames as $col_key => $col_value) {
            $search_column_data = $this->Column->find('first', array('conditions' => array('Column.name' => $col_value, 'Column.status !=' => 2), 'fields' => array('Column.id'), 'recursive' => -1));
            $check_col_id = $search_column_data['Column']['id'];
            foreach ($sort_by_cols as $colids) {
                if ($check_col_id == $colids) {
                    $appplied_formulae[$colids] = $col_value;
                    unset($columnNames[$col_key]);
                }
            }
        }
        for ($i = 0; $i < count($sort_by_cols); $i++) {
            foreach ($appplied_formulae as $app_key => $app_val) {
                if ($sort_by_cols[$i] == $app_key) {
                    $sort_by_cols[$i] = $app_val;
                }
            }
        }
        $testarray = array();
        $testarray = $columnNames;
        $new_cols_names = array();

        foreach ($testarray as $cols) {
            $new_cols_names[] = $cols;
        }
        unset($columnNames);
        $columnNames = array_merge($new_cols_names, $sort_by_cols);

        $this->contain('Formula');
        $sheetObj = $this->findById((int) $postData['sheetId']);

        $arr_formulas = $sheetObj['Formula']; //get all the formulae associated with this sheet

        $formula_columns = array();
        $formula_columns_pipe = array();
        $formula_rows = array();
        $formulas = array();
        $formulas_pipe = array();
        $final_arr_formula_val = array();
        $seleted_formula_column = array();

        foreach ($arr_formulas as $formula) {
            if ($formula['row_id'] != '0') {
                array_push($formula_columns_pipe, $formula['column_id']);
                array_push($formula_rows, $formula['row_id']);
                array_push($formulas_pipe, $formula['formula']);
            } else {
                array_push($formula_columns, $formula['column_id']);
                array_push($formulas, $formula['formula']);
            }
        }

        /* for calculating columns........................................ */

        //save all the result column names
        $dateArr = explode('/', $postData['Date']);

        // Prepare the array of data to be saved
        $data['sheet_id'] = (int) $postData['sheetId'];
        $data['date'] = (int) $dateArr[0];

        foreach ($columnNames as $column) {

            $columns = $this->Column->find('first', array('conditions' => array('Column.name' => $column, 'Column.status !=' => 2), 'fields' => array('Column.id'), 'recursive' => -1));
            $data['column_id'] = $columns['Column']['id'];

            $column_formula1 = array();
            $seleted_formula_column1 = array();

            //check if the column id is there in the array of formula result columns
            foreach ($formula_columns as $single_column_k => $single_column_v) {
                if ($single_column_v == $data['column_id']) {
                    $column_formula1[] = $formulas[$single_column_k];
                    $seleted_formula_column1[] = $formula_columns[$single_column_k];
                }
            }

            //split the formula and take all the values in an array
            if (!empty($column_formula1)) {

                $arr_formula_val = explode(" ", $column_formula1[0]);
                $arr_indx = 0;
                foreach ($arr_formula_val as $val) {

                    if (substr($val, 0, 1) == "C") {
                        $this->Column->recursive = -1;
                        $column_data = $this->Column->findById(substr($val, 1));

                        $acutal_val = $postData[$column_data['Column']['name']];

                        //Added on 25 July 2014
                        if (substr_count($acutal_val, '.') == '2') {
                            if (substr($acutal_val, -3) == '.00') {
                                $acutal_val = str_replace('.00', '', $acutal_val);
                            }
                        }
                        $arr_formula_val[$arr_indx] = $acutal_val;
                    }
                    $arr_indx += 1;
                }
                $math_string = implode("", $arr_formula_val);
                $data['value'] = $this->calculate_string($math_string);
                $postData[$column] = $data['value'];
            } else {
                $data['value'] = $postData[$column];
            }

            $conditions = array(
                'Datum.sheet_id' => $data['sheet_id'],
                'Datum.column_id' => $data['column_id'],
                'Datum.row_id' => '0',
                'Datum.date' => $data['date']
            );

            $dataId = $this->Datum->field('id', $conditions);
            if ($dataId) {
                $this->Datum->query("UPDATE `data` SET `modified` = '" . date('Y-m-d H:i:s') . "',`value`='" . $data['value'] . "'  WHERE `id`= '" . $dataId . "'");
            } else {
                $this->Datum->create();
                $this->Datum->save($data);
            }
        }

        /* added to calculate locked rows on each update */
        $rows_obj = ClassRegistry::init('RowsSheet');

        $rows_data = $this->RowsSheet->find('list', array('conditions' => array('RowsSheet.sheet_id' => $sheetId), 'fields' => array('RowsSheet.id', 'RowsSheet.row_id')));
        $obtained_rows = array_values($rows_data);

        $total_locked_values = array();
        $fromula_for_rows = array();
        $loop_count = 0;
        $operattions_operators = array("+", "-", "*", "/");
        $final_values = array();
        $final_update_to_db = array();
        $total_row_done = false;
        $calc_for_total = array();

        $index = 0;
        $new_row_formulas_arr = $this->Formula->find('all', array('conditions' => array('Formula.row_id' => "Total", 'Formula.sheet_id' => $sheetId), 'order' => array('Formula.column_order'), 'recursive' => -1));

        if (!empty($new_row_formulas_arr)) {
            foreach ($new_row_formulas_arr as $new_row_formulas) {
                $formula_array_stage1 = explode(" ", $new_row_formulas['Formula']['formula']);

                foreach ($formula_array_stage1 as $sub_cols_rows) {
                    if (substr($sub_cols_rows, 0, 1) == "C") {
                        $temp_col = explode("C", $sub_cols_rows);
                        $current_col_id = $temp_col[1];
                        $fromula_for_rows[$index]['col_id'] = $current_col_id;
                    } elseif (substr($sub_cols_rows, 0, 1) == "R") {
                        $temp_col = explode("R", $sub_cols_rows);
                        $current_row_id = $temp_col[1];
                        $fromula_for_rows[$index]['row_id'] = $current_row_id;
                    } elseif ($sub_cols_rows == "Total") {
                        $fromula_for_rows[$index]['row_id'] = "Total";
                    } elseif (in_array($sub_cols_rows, $operattions_operators)) {
                        $index++;
                        $fromula_for_rows[$index]['operator'] = $sub_cols_rows;
                    }
                }

                $new_math_string = array();
                foreach ($fromula_for_rows as $sticky_formula) {
                    $math_string = "";
                    $temp_tot = 0;
                    if (!isset($sticky_formula['operator'])) {
                        if ($sticky_formula['row_id'] == "Total") {
                            $cols_data = $this->Datum->find('all', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => 0), 'fields' => array('Datum.value'), 'recursive' => -1));
                            foreach ($cols_data as $data_cols) {
                                $temp_tot += $data_cols['Datum']['value'];
                            }

                            $math_string .= "" . $temp_tot;
                        } else {
                            $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id']), 'fields' => array('Datum.value'), 'recursive' => -1));
                            if (empty($cols_data['Datum']['value'])) {
                                $cols_data['Datum']['value'] = 0;
                            }
                            $math_string .= "" . $cols_data['Datum']['value'];
                        }
                    } else {
                        if ($sticky_formula['row_id'] == "Total") {
                            $cols_data = $this->Datum->find('all', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => 0), 'fields' => array('Datum.value'), 'recursive' => -1));
                            foreach ($cols_data as $data_cols) {
                                $temp_tot += $data_cols['Datum']['value'];
                            }

                            $math_string .= $sticky_formula['operator'] . $temp_tot;
                        } else {
                            $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id']), 'fields' => array('Datum.value'), 'recursive' => -1));
                            if (empty($cols_data['Datum']['value'])) {
                                $cols_data['Datum']['value'] = 0;
                            }
                            $math_string .= $sticky_formula['operator'] . $cols_data['Datum']['value'];
                        }
                    }
                    $new_math_string [] = $math_string;
                }
                unset($fromula_for_rows);
                $total_math_string_final = implode("", $new_math_string);

                $final_values[$loop_count] = $this->calculate_string($total_math_string_final);

                $arrTemp = array();

                $arrTemp['sheet_id'] = $sheetId;
                $arrTemp['column_id'] = $new_row_formulas['Formula']['column_id'];
                $arrTemp['row_id'] = $new_row_formulas['Formula']['row_id'];
                $arrTemp['value'] = $final_values[$loop_count];

                array_push($calc_for_total, $arrTemp);

                $loop_count++;
            } // end for $new_row_formulas_arr
        }

        $total_locked_values = array();
        $fromula_for_rows = array();
        $loop_count = 0;
        $operattions_operators = array("+", "-", "*", "/");
        $final_values = array();
        $final_update_to_db = array();

        foreach ($obtained_rows as $rows) {
            $index = 0;
            $new_row_formulas_arr = $this->Formula->find('all', array('conditions' => array('Formula.row_id' => $rows, 'Formula.sheet_id' => $sheetId), 'order' => array('Formula.column_order'), 'recursive' => -1));

            if (!empty($new_row_formulas_arr)) {

                foreach ($new_row_formulas_arr as $new_row_formulas) {

                    $formula_array_stage1 = explode(" ", $new_row_formulas['Formula']['formula']);

                    foreach ($formula_array_stage1 as $sub_cols_rows) {

                        if (substr($sub_cols_rows, 0, 1) == "C") {
                            $temp_col = explode("C", $sub_cols_rows);
                            $current_col_id = $temp_col[1];
                            $fromula_for_rows[$index]['col_id'] = $current_col_id;
                        } elseif (substr($sub_cols_rows, 0, 1) == "R") {
                            $temp_col = explode("R", $sub_cols_rows);
                            $current_row_id = $temp_col[1];
                            $fromula_for_rows[$index]['row_id'] = $current_row_id;
                        } elseif ($sub_cols_rows == "Total") {
                            $fromula_for_rows[$index]['row_id'] = "Total";
                        } elseif (in_array($sub_cols_rows, $operattions_operators)) {
                            $index++;
                            $fromula_for_rows[$index]['operator'] = $sub_cols_rows;
                        }
                    }
                    $new_math_string = array();
                    foreach ($fromula_for_rows as $sticky_formula) {
                        $math_string = "";
                        $temp_tot = 0;

                        if (!isset($sticky_formula['operator'])) {
                            if ($sticky_formula['row_id'] == "Total") {

                                $is_val_found = false;
                                foreach ($calc_for_total as $arr) {
                                    if ($sticky_formula['col_id'] == $arr['column_id']) {
                                        $temp_tot = $arr['value'];
                                        $is_val_found = true;
                                    }
                                }
                                if (!$is_val_found) {
                                    $cols_data = $this->Datum->find('all', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => 0), 'fields' => array('Datum.value'), 'recursive' => -1));
                                    foreach ($cols_data as $data_cols) {
                                        $temp_tot += $data_cols['Datum']['value'];
                                    }
                                }

                                $math_string .= "" . $temp_tot;
                            } else {
                                $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id']), 'fields' => array('Datum.value'), 'recursive' => -1));
                                if (empty($cols_data['Datum']['value'])) {
                                    $cols_data['Datum']['value'] = 0;
                                }
                                $math_string .= "" . $cols_data['Datum']['value'];
                            }
                        } else {
                            if ($sticky_formula['row_id'] == "Total") {
                                $cols_data = $this->Datum->find('all', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => 0), 'fields' => array('Datum.value'), 'recursive' => -1));
                                foreach ($cols_data as $data_cols) {
                                    $temp_tot += $data_cols['Datum']['value'];
                                }

                                $is_val_found = false;
                                foreach ($calc_for_total as $arr) {
                                    if ($sticky_formula['col_id'] == $arr['column_id']) {
                                        $temp_tot = $arr['value'];
                                        $is_val_found = true;
                                    }
                                }
                                if (!$is_val_found) {
                                    $cols_data = $this->Datum->find('all', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => 0), 'fields' => array('Datum.value'), 'recursive' => -1));
                                    foreach ($cols_data as $data_cols) {
                                        $temp_tot += $data_cols['Datum']['value'];
                                    }
                                }

                                $math_string .= $sticky_formula['operator'] . $temp_tot;
                            } else {
                                $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id']), 'fields' => array('Datum.value'), 'recursive' => -1));
                                if (empty($cols_data['Datum']['value'])) {
                                    $cols_data['Datum']['value'] = 0;
                                }
                                $math_string .= $sticky_formula['operator'] . $cols_data['Datum']['value'];
                            }
                        }

                        //we will have to see if any formula there for column of Total row.
                        $new_math_string [] = $math_string;
                    }
                    unset($fromula_for_rows);
                    $math_string_final = implode("", $new_math_string);

                    $final_values[$loop_count] = $this->calculate_string($math_string_final);

                    $final_update_to_db[$loop_count]['sheet_id'] = $sheetId;
                    $final_update_to_db[$loop_count]['column_id'] = $new_row_formulas['Formula']['column_id'];
                    $final_update_to_db[$loop_count]['row_id'] = $new_row_formulas['Formula']['row_id'];
                    $final_update_to_db[$loop_count]['value'] = $final_values[$loop_count];
                    $final_update_to_db[$loop_count]['date'] = 0;
                    $loop_count++;
                } // end for $new_row_formulas_arr
            }
        }

        foreach ($final_update_to_db as $values) {
            $conditions1_all = array(
                'Datum.sheet_id' => $values['sheet_id'],
                'Datum.column_id' => $values['column_id'],
                'Datum.row_id' => $values['row_id'],
                'Datum.date' => '0'
            );

            $dataId1_all = $this->Datum->field('id', $conditions1_all);
            if ($dataId1_all) {
                $this->Datum->query("UPDATE `data` SET `modified` = '" . date('Y-m-d H:i:s') . "',`value`='" . $values['value'] . "'  WHERE `id`= '" . $dataId1_all . "'");
            } else {
                $this->Datum->create();
                $this->Datum->save($values);
            }
        }
        return $postData;
    }

//end importData()

    /**
     * Method to save the sheet data
     * 
     * @param int $sheetId The Sheet ID
     * @access public
     * @return void
     */
    function saveData($sheetId, $pdata = null) {
        // Configure:: write('debug',2);

        if (!empty($pdata)) {
            $postData = $pdata;
        } else {
            $post = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
            $postData = get_object_vars($post->data[0]);
        }

        $sheetdates = $this->find('first', array('fields' => 'month,year', 'conditions' => array('id' => $sheetId), 'recursive' => -1));
        $numDays = date('t', mktime(0, 0, 0, $sheetdates['Sheet']['month'], 1, $sheetdates['Sheet']['year']));

        $row_name = $postData['Date'];
        $clicked_row_detail = $this->Row->find('first', array('conditions' => array('Row.name' => $row_name, 'Row.status !=' => 2), 'recursive' => '-1'));
        $clicked_row_id = $clicked_row_detail['Row']['id'];

        $columnNames = array_keys(array_slice($postData, 3));
        $sheetCols = $this->Column->find('list', array('conditions' => array('Column.name' => $columnNames, 'Column.status !=' => 2), 'fields' => array('Column.id', 'Column.name')));

        $formula_cols = $this->Formula->find('list', array('conditions' => array('Formula.sheet_id' => $sheetId), 'fields' => array('Formula.id', 'Formula.column_id'), 'order' => array('Formula.column_order')));
        $formulaCols = $this->Column->find('list', array('conditions' => array('Column.id' => $formula_cols, 'Column.status !=' => 2), 'fields' => array('Column.id', 'Column.name')));
        foreach ($formula_cols as $app_key => $app_val) {
            $sort_by_cols[] = $formulaCols[$app_val];
        }

        $sort_by_cols = array_merge($sort_by_cols, $columnNames);
        unset($columnNames);
        $columnNames = array_values($sort_by_cols);

        $arr_formulas = $this->Formula->find('all', array('conditions' => array('Formula.sheet_id' => $sheetId), 'order' => array('Formula.column_order')));

        $formula_columns = array();
        $formula_columns_pipe = array();
        $formula_rows = array();
        $formulas = array();
        $formulas_pipe = array();
        $final_arr_formula_val = array();
        $seleted_formula_column = array();

        foreach ($arr_formulas as $formula) {
            if ($formula['Formula']['row_id'] != '0') {
                array_push($formula_columns_pipe, $formula['Formula']['column_id']);
                array_push($formula_rows, $formula['Formula']['row_id']);
                array_push($formulas_pipe, $formula['Formula']['formula']);
            } else {
                array_push($formula_columns, $formula['Formula']['column_id']);
                array_push($formulas, $formula['Formula']['formula']);
            }
        }

        if (!empty($clicked_row_id)) {

            // Prepare the array of data to be saved
            $data['sheet_id'] = (int) $postData['sheetId'];

            $column_formula = array();
            /* Checked the clicked row id and compare to formula row id */
            foreach ($formula_rows as $single_row_key => $single_row_value) {
                if ($clicked_row_id == $single_row_value) {
                    $column_formula[] = $formulas_pipe[$single_row_key];
                    $seleted_formula_column[] = $formula_columns_pipe[$single_row_key];
                }
            }

            if (!empty($column_formula)) {
                foreach ($column_formula as $single_column_key => $single_column_value) {
                    $arr_formula_val = explode(" ", $single_column_value);
                    $arr_indx = 0;
                    foreach ($arr_formula_val as $val) {
                        if (substr($val, 0, 1) == "C") {
                            $db_column_id = substr($val, 1);
                            $colName = $sheetCols[$db_column_id];
                            $acutal_val = $postData[$colName];

                            $arr_formula_val[$arr_indx] = $acutal_val;
                        } else if (substr($val, 0, 1) == "R" || substr($val, 0, 1) == "T") {

                            if (substr($val, 0, 1) == "R") {
                                $row_data = $this->Row->findById(substr($val, 1));
                                $acutal_val_row = $row_data['Row']['id'];

                                if ($acutal_val_row != $clicked_row_id) {
                                    $search_conditions = array(
                                        'Datum.sheet_id' => $data['sheet_id'],
                                        'Datum.column_id' => $db_column_id,
                                        'Datum.row_id' => $acutal_val_row
                                    );

                                    $dataIds = $this->Datum->field('id', $search_conditions);
                                    if ($dataIds) {
                                        $gather_value = $this->Datum->find('first', array('conditions' => array('Datum.id' => $dataIds), 'fields' => array('Datum.value')));
                                        $arr_formula_val[$arr_indx] = $gather_value['Datum']['value'];
                                    } else {
                                        $arr_formula_val[$arr_indx] = 0;
                                    }
                                }
                            } else {
                                $total_datum_value = $this->Datum->query("SELECT SUM(replace(value, ',', '')) as value FROM data WHERE sheet_id = " . $data['sheet_id'] . " AND column_id = " . $db_column_id . " AND row_id = '0' AND date != '0' and date <= $numDays");
                                $arr_formula_val[$arr_indx] = $total_datum_value[0][0]['value'];
                            }
                        }
                        $arr_indx += 1;
                    }

                    $final_arr_formula_val = array();
                    foreach ($arr_formula_val as $key1 => $value1) {
                        if ($value1 === "|") {
                            if (substr($arr_formula_val[$key1 + 1], 0, 1) == "R") {
                                $final_arr_formula_val[] = $arr_formula_val[$key1 - 1];
                            } else {
                                $final_arr_formula_val[] = $arr_formula_val[$key1 + 1];
                            }
                        } else if ($value1 === "+" || $value1 === "-" || $value1 === "*" || $value1 === "/" || $value1 === "(" || $value1 === ")") {
                            array_push($final_arr_formula_val, $value1);
                        } else if (is_numeric($value1) && substr($arr_formula_val[$key1 + 1], 0, 1) != "|") {
                            array_push($final_arr_formula_val, $value1);
                        }
                    }

                    $data['row_id'] = $clicked_row_id;
                    $column = $sheetCols[$seleted_formula_column[$single_column_key]];

                    $math_string = implode("", $final_arr_formula_val);

                    $data['value'] = $this->calculate_string($math_string);
                    $postData[$column] = $data['value'];

                    unset($final_arr_formula_val);
                }
            }

            $columnNames = array_unique($columnNames);
            foreach ($columnNames as $column) {

                $data['column_id'] = array_search($column, $sheetCols);
                $data['value'] = $postData[$column];
                $data['row_id'] = $clicked_row_id;

                $conditions1 = array(
                    'Datum.sheet_id' => $data['sheet_id'],
                    'Datum.column_id' => $data['column_id'],
                    'Datum.row_id' => $data['row_id'],
                    'Datum.date' => '0'
                );

                $dataId1 = $this->Datum->field('id', $conditions1);
                if ($dataId1) {
                    $this->Datum->updateAll($data, array('Datum.id' => $dataId1));
                } else {
                    $this->Datum->create();
                    $this->Datum->save($data);
                }
            }


            $obtained_rows = array();

            /* added to calculate locked rows on each update */
            $rows_obj = ClassRegistry::init('RowsSheet');

            $total_locked_values = array();
            $fromula_for_rows = array();
            $index = 0;
            $loop_count = 0;
            $operattions_operators = array("+", "-", "*", "/");
            $final_values = array();
            $final_update_to_db = array();

            foreach ($obtained_rows as $rows) {
                $new_row_formulas = $this->Formula->find('first', array('conditions' => array('Formula.row_id' => $rows, 'Formula.sheet_id' => $sheetId), 'order' => array('Formula.column_order')));
                if (!empty($new_row_formulas)) {
                    $formula_array_stage1 = explode(" ", $new_row_formulas['Formula']['formula']);

                    foreach ($formula_array_stage1 as $sub_cols_rows) {
                        if (substr($sub_cols_rows, 0, 1) == "C") {
                            $temp_col = explode("C", $sub_cols_rows);
                            $current_col_id = $temp_col[1];
                            $fromula_for_rows[$index]['col_id'] = $current_col_id;
                        } elseif (substr($sub_cols_rows, 0, 1) == "R") {
                            $temp_col = explode("R", $sub_cols_rows);
                            $current_row_id = $temp_col[1];
                            $fromula_for_rows[$index]['row_id'] = $current_row_id;
                        } elseif ($sub_cols_rows == "Total") {
                            $fromula_for_rows[$index]['row_id'] = "Total";
                        } elseif (in_array($sub_cols_rows, $operattions_operators)) {
                            $index++;
                            $fromula_for_rows[$index]['operator'] = $sub_cols_rows;
                        }
                    }

                    $new_math_string = array();

                    foreach ($fromula_for_rows as $sticky_formula) {
                        $math_string = "";
                        $temp_tot = 0;
                        if (!isset($sticky_formula['operator'])) {
                            if ($sticky_formula['row_id'] == "Total") {
                                $cols_data = $this->Datum->query("SELECT SUM(replace(value, ',', '')) as value FROM data WHERE sheet_id = " . $sheetId . " AND column_id = " . $sticky_formula['col_id'] . " AND row_id = '0' AND date != '0' and date <= $numDays");
                                $temp_tot = $cols_data[0][0]['value'];

                                $math_string .= "" . $temp_tot;
                            } else {
                                $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id'])));
                                if (empty($cols_data['Datum']['value'])) {
                                    $cols_data['Datum']['value'] = 0;
                                }
                                $math_string .= "" . $cols_data['Datum']['value'];
                            }
                        } else {
                            if ($sticky_formula['row_id'] == "Total") {
                                $cols_data = $this->Datum->query("SELECT SUM(replace(value, ',', '')) as value FROM data WHERE sheet_id = " . $sheetId . " AND column_id = " . $sticky_formula['col_id'] . " AND row_id = '0' AND date != '0' and date <= $numDays");
                                $temp_tot = $cols_data[0][0]['value'];

                                $math_string .= $sticky_formula['operator'] . $temp_tot;
                            } else {
                                $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id'])));
                                if (empty($cols_data['Datum']['value'])) {
                                    $cols_data['Datum']['value'] = 0;
                                }
                                $math_string .= $sticky_formula['operator'] . $cols_data['Datum']['value'];
                            }
                        }
                        $new_math_string [] = $math_string;
                    }

                    $math_string_final = implode("", $new_math_string);

                    $final_values[$loop_count] = $this->calculate_string($math_string_final);

                    $final_update_to_db[$loop_count]['sheet_id'] = $sheetId;
                    $final_update_to_db[$loop_count]['column_id'] = $new_row_formulas['Formula']['column_id'];
                    $final_update_to_db[$loop_count]['row_id'] = $new_row_formulas['Formula']['row_id'];
                    $final_update_to_db[$loop_count]['value'] = $final_values[$loop_count];
                    $final_update_to_db[$loop_count]['date'] = 0;
                    $loop_count++;
                }
            }

            foreach ($final_update_to_db as $values) {
                $conditions1_all = array(
                    'Datum.sheet_id' => $values['sheet_id'],
                    'Datum.column_id' => $values['column_id'],
                    'Datum.row_id' => $values['row_id'],
                    'Datum.date' => '0'
                );

                $dataId1_all = $this->Datum->field('id', $conditions1_all);
                if ($dataId1_all) {
                    $this->Datum->query("UPDATE `data` SET  `modified` = '" . date('Y-m-d H:i:s') . "',`value`='" . $values['value'] . "'  WHERE `id`= '" . $dataId1_all . "'");
                } else {
                    $this->Datum->create();
                    $this->Datum->save($values);
                }
            }
            return $postData;
        } else {

            //save all the result column names
            $dateArr = explode('/', $postData['Date']);

            // Prepare the array of data to be saved
            $data['sheet_id'] = (int) $postData['sheetId'];
            $data['date'] = (int) $dateArr[0];

            $columnNames = array_unique($columnNames);

            foreach ($columnNames as $column) {

                $data['column_id'] = array_search($column, $sheetCols);

                $column_formula1 = array();
                $seleted_formula_column1 = array();

                //check if the column id is there in the array of formula result columns
                foreach ($formula_columns as $single_column_k => $single_column_v) {
                    if ($single_column_v == $data['column_id']) {
                        $column_formula1[] = $formulas[$single_column_k];
                        $seleted_formula_column1[] = $formula_columns[$single_column_k];
                    }
                }

                //split the formula and take all the values in an array
                if (!empty($column_formula1)) {
                    $arr_formula_val = explode(" ", $column_formula1[0]);
                    $arr_indx = 0;
                    foreach ($arr_formula_val as $val) {
                        //if(is_numeric($val)){
                        if (substr($val, 0, 1) == "C") {
                            $db_column_id = substr($val, 1);
                            $colName = $sheetCols[$db_column_id];
                            $acutal_val = $postData[$colName];

                            $arr_formula_val[$arr_indx] = $acutal_val;
                        }
                        $arr_indx += 1;
                    }
                    $math_string = implode("", $arr_formula_val);
                    $data['value'] = $this->calculate_string($math_string);
                    $postData[$column] = $data['value'];
                } else {
                    $data['value'] = $postData[$column];
                }

                $conditions = array(
                    'Datum.sheet_id' => $data['sheet_id'],
                    'Datum.column_id' => $data['column_id'],
                    'Datum.row_id' => '0',
                    'Datum.date' => $data['date']
                );
                $dataId = $this->Datum->field('id', $conditions);
                if ($dataId) {
                    $this->Datum->query("UPDATE `data` SET `modified` = '" . date('Y-m-d H:i:s') . "',`value`='" . $data['value'] . "'  WHERE `id`= '" . $dataId . "'");
                } else {
                    $this->Datum->create();
                    $this->Datum->save($data);
                }
            }

            $rows_obj = ClassRegistry::init('RowsSheet');

            $rows_data = $this->RowsSheet->find('list', array('conditions' => array('RowsSheet.sheet_id' => $sheetId), 'fields' => array('RowsSheet.id', 'RowsSheet.row_id')));
            $obtained_rows = array_values($rows_data);

            $total_locked_values = array();
            $fromula_for_rows = array();

            $loop_count = 0;
            $operattions_operators = array("+", "-", "*", "/");
            $final_values = array();
            $final_update_to_db = array();

            $total_row_done = false;

            $calc_for_total = array();

            $index = 0;
            $new_row_formulas_arr = $this->Formula->find('all', array('conditions' => array('Formula.row_id' => "Total", 'Formula.sheet_id' => $sheetId), 'order' => array('Formula.column_order')));

            if (!empty($new_row_formulas_arr)) {
                foreach ($new_row_formulas_arr as $new_row_formulas) {
                    $formula_array_stage1 = explode(" ", $new_row_formulas['Formula']['formula']);

                    foreach ($formula_array_stage1 as $sub_cols_rows) {
                        if (substr($sub_cols_rows, 0, 1) == "C") {
                            $temp_col = explode("C", $sub_cols_rows);
                            $current_col_id = $temp_col[1];
                            $fromula_for_rows[$index]['col_id'] = $current_col_id;
                        } elseif (substr($sub_cols_rows, 0, 1) == "R") {
                            $temp_col = explode("R", $sub_cols_rows);
                            $current_row_id = $temp_col[1];
                            $fromula_for_rows[$index]['row_id'] = $current_row_id;
                        } elseif ($sub_cols_rows == "Total") {
                            $fromula_for_rows[$index]['row_id'] = "Total";
                        } elseif (in_array($sub_cols_rows, $operattions_operators)) {
                            $index++;
                            $fromula_for_rows[$index]['operator'] = $sub_cols_rows;
                        }
                    }

                    $new_math_string = array();
                    foreach ($fromula_for_rows as $sticky_formula) {
                        $math_string = "";
                        $temp_tot = 0;
                        if (!isset($sticky_formula['operator'])) {
                            if ($sticky_formula['row_id'] == "Total") {
                                $cols_data = $this->Datum->query("SELECT SUM(replace(value, ',', '')) as value FROM data WHERE sheet_id = " . $sheetId . " AND column_id = " . $sticky_formula['col_id'] . " AND row_id = '0' AND date != '0' and date <= $numDays");
                                $temp_tot = $cols_data[0][0]['value'];

                                $math_string .= "" . $temp_tot;
                            } else {
                                $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id'])));
                                if (empty($cols_data['Datum']['value'])) {
                                    $cols_data['Datum']['value'] = 0;
                                }
                                $math_string .= "" . $cols_data['Datum']['value'];
                            }
                        } else {
                            if ($sticky_formula['row_id'] == "Total") {
                                $cols_data = $this->Datum->query("SELECT SUM(replace(value, ',', '')) as value FROM data WHERE sheet_id = " . $sheetId . " AND column_id = " . $sticky_formula['col_id'] . " AND row_id = '0' AND date != '0' and date <= $numDays");
                                $temp_tot = $cols_data[0][0]['value'];

                                $math_string .= $sticky_formula['operator'] . $temp_tot;
                            } else {
                                $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id'])));
                                if (empty($cols_data['Datum']['value'])) {
                                    $cols_data['Datum']['value'] = 0;
                                }
                                $math_string .= $sticky_formula['operator'] . $cols_data['Datum']['value'];
                            }
                        }
                        $new_math_string [] = $math_string;
                    }
                    unset($fromula_for_rows);
                    $total_math_string_final = implode("", $new_math_string);

                    $final_values[$loop_count] = $this->calculate_string($total_math_string_final);

                    $arrTemp = array();
                    $arrTemp['sheet_id'] = $sheetId;
                    $arrTemp['column_id'] = $new_row_formulas['Formula']['column_id'];
                    $arrTemp['row_id'] = $new_row_formulas['Formula']['row_id'];
                    $arrTemp['value'] = $final_values[$loop_count];
                    array_push($calc_for_total, $arrTemp);
                    $loop_count++;
                } // end for $new_row_formulas_arr
            }

            $total_locked_values = array();
            $fromula_for_rows = array();

            $loop_count = 0;

            $operattions_operators = array("+", "-", "*", "/", "(", ")");
            $final_values = array();
            $final_update_to_db = array();

            foreach ($obtained_rows as $rows) {
                $index = 0;
                $new_row_formulas_arr = $this->Formula->find('all', array('conditions' => array('Formula.row_id' => $rows, 'Formula.sheet_id' => $sheetId), 'order' => array('Formula.column_order')));

                if (!empty($new_row_formulas_arr)) {
                    foreach ($new_row_formulas_arr as $new_row_formulas) {
                        $formula_array_stage1 = explode(" ", $new_row_formulas['Formula']['formula']);
                        foreach ($formula_array_stage1 as $sub_cols_rows) {
                            if (substr($sub_cols_rows, 0, 1) == "C") {
                                $temp_col = explode("C", $sub_cols_rows);
                                $current_col_id = $temp_col[1];
                                $fromula_for_rows[$index]['col_id'] = $current_col_id;
                            } elseif (substr($sub_cols_rows, 0, 1) == "R") {
                                $temp_col = explode("R", $sub_cols_rows);
                                $current_row_id = $temp_col[1];
                                $fromula_for_rows[$index]['row_id'] = $current_row_id;
                            } elseif ($sub_cols_rows == "Total") {
                                $fromula_for_rows[$index]['row_id'] = "Total";
                            } elseif (in_array($sub_cols_rows, $operattions_operators)) {
                                $index++;
                                $fromula_for_rows[$index]['operator'] = $sub_cols_rows;
                            } else if (is_numeric($sub_cols_rows)) {
                                $fromula_for_rows[$index]['val'] = $sub_cols_rows;
                            }
                        }
                        $new_math_string = array();
                        foreach ($fromula_for_rows as $sticky_formula) {
                            $math_string = "";
                            $temp_tot = 0;

                            if (!isset($sticky_formula['operator'])) {
                                if ($sticky_formula['row_id'] == "Total") {
                                    $is_val_found = false;
                                    foreach ($calc_for_total as $arr) {
                                        if ($sticky_formula['col_id'] == $arr['column_id']) {
                                            $temp_tot = $arr['value'];
                                            $is_val_found = true;
                                        }
                                    }
                                    if (!$is_val_found) {
                                        $cols_data = $this->Datum->query("SELECT SUM(replace(value, ',', '')) as value FROM data WHERE sheet_id = " . $sheetId . " AND column_id = " . $sticky_formula['col_id'] . " AND row_id = '0' AND date != '0' and date <= $numDays");
                                        $temp_tot = $cols_data[0][0]['value'];
                                    }

                                    $math_string .= "" . $temp_tot;
                                } else {
                                    $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id'])));
                                    if (empty($cols_data['Datum']['value'])) {
                                        $cols_data['Datum']['value'] = 0;
                                    }
                                    $math_string .= "" . $cols_data['Datum']['value'];
                                }
                            } else {
                                if ($sticky_formula['row_id'] == "Total") {
                                    $is_val_found = false;
                                    foreach ($calc_for_total as $arr) {
                                        if ($sticky_formula['col_id'] == $arr['column_id']) {
                                            $temp_tot = $arr['value'];
                                            $is_val_found = true;
                                        }
                                    }
                                    if (!$is_val_found) {
                                        $cols_data = $this->Datum->query("SELECT SUM(replace(value, ',', '')) as value FROM data WHERE sheet_id = " . $sheetId . " AND column_id = " . $sticky_formula['col_id'] . " AND row_id = '0' and date <= $numDays");
                                        $temp_tot = $cols_data[0][0]['value'];
                                    }
                                    $math_string .= $sticky_formula['operator'] . $temp_tot;
                                } else {


                                    if (!empty($sticky_formula['col_id']) && !empty($sticky_formula['row_id'])) {
                                        //this if is not required here possibly
                                        $cols_data = $this->Datum->find('first', array('conditions' => array('sheet_id' => $sheetId, 'column_id' => $sticky_formula['col_id'], 'row_id' => $sticky_formula['row_id']), 'fields' => array('Datum.value'), 'recursive' => -1));
                                        if (empty($cols_data['Datum']['value'])) {
                                            $cols_data['Datum']['value'] = 0;
                                        }
                                        $math_string .= $sticky_formula['operator'] . $cols_data['Datum']['value'];
                                    } else if (!empty($sticky_formula)) {
                                        foreach ($sticky_formula as $formla) {
                                            $math_string .= $formla;
                                        }
                                    }
                                }
                            }

                            //we will have to see if any formula there for column of Total row.
                            $new_math_string [] = $math_string;
                        }
                        unset($fromula_for_rows);
                        $math_string_final = implode("", $new_math_string);

                        $final_values[$loop_count] = $this->calculate_string($math_string_final);

                        $final_update_to_db[$loop_count]['sheet_id'] = $sheetId;
                        $final_update_to_db[$loop_count]['column_id'] = $new_row_formulas['Formula']['column_id'];
                        $final_update_to_db[$loop_count]['row_id'] = $new_row_formulas['Formula']['row_id'];
                        $final_update_to_db[$loop_count]['value'] = $final_values[$loop_count];
                        $final_update_to_db[$loop_count]['date'] = 0;
                        $loop_count++;
                    } // end for $new_row_formulas_arr
                }
            }

            foreach ($final_update_to_db as $values) {
                $conditions1_all = array(
                    'Datum.sheet_id' => $values['sheet_id'],
                    'Datum.column_id' => $values['column_id'],
                    'Datum.row_id' => $values['row_id'],
                    'Datum.date' => '0'
                );

                $dataId1_all = $this->Datum->field('id', $conditions1_all);
                if ($dataId1_all) {
                    $this->Datum->query("UPDATE `data` SET `modified` = '" . date('Y-m-d H:i:s') . "',`value`='" . $values['value'] . "'  WHERE `id`= '" . $dataId1_all . "'");
                } else {
                    $this->Datum->create();
                    $this->Datum->save($values);
                }
            }
            return $postData;
        }

        return $postData;
    }

//end saveData()

    function calculate_string($mathString) {

        $mathString = trim($mathString);     // trim white spaces
        $mathString = str_replace(',', '', $mathString);
        $mathString = str_replace('|Total', '', $mathString);


        $mathString = str_replace('--', '+', $mathString);

        $mathString = str_replace('.00 ( ', '0.00 * ( ', $mathString);

        //added on 16May 2016
        $mathString = str_replace('(*)', '0', $mathString);

        if ($mathString == '*0' || $mathString == '+' || $mathString == '0*') {
            return 0;
        } else {

            $mathString = ereg_replace('[^0-9\+-\*\/\(\) ]', '', $mathString);    // remove any non-numbers chars; exception for math operators

            $compute = create_function("", "return (" . $mathString . ");");

            $calcualted_val = 0 + $compute();
            return round($calcualted_val, 2);
        }
    }

    /* data to generate yealy report */

    function getYearlyData($userId, $dept_id, $col_name, $year) {

        $this->contain(array('User' => array('username', 'department_name'), 'Datum', 'Column'));
        $datas = $this->find('all', array('conditions' => array('Sheet.user_id' => $userId, 'Sheet.year' => $year, 'Sheet.department_id' => $dept_id)));

        $yearly_data = array();
        $init_val = 0;
        foreach ($datas as $data) {

            $sub_total = 0;
            $dates = Set::extract('/date', $data['Datum']);
            $values = Set::extract('/value', $data['Datum']);
            $dataColumns = Set::extract('/column_id', $data['Datum']);

            $columnIds = Set::extract('/id', $data['Column']);
            $columns = Set::extract('/name', $data['Column']);

            $numDays = date('t', mktime(0, 0, 0, $data['Sheet']['month'], 1, $data['Sheet']['year']));

            $departmentData = array();
            for ($i = 1; $i <= $numDays; $i++) {

                $dateKeys = array_keys($dates, $i);

                $columnData[$i]['Date'] = date('m-y', mktime(0, 0, 0, $data['Sheet']['month'], $i, $data['Sheet']['year'])); //$data['Sheet']['year'] .",".  $data['Sheet']['month'] .",". $i;
                foreach ($columnIds as $key => $columnId) {
                    if ($columns[$key] == 'DOW') {
                        $columnData[$i][$columns[$key]] = date('D', mktime(0, 0, 0, $data['Sheet']['month'], $i, $data['Sheet']['year']));
                    } else {
                        if (isset($dateKeys[$key])) {
                            $j = $dateKeys[$key];
                            $columnData[$i][$columns[$key]] = ($dates[$j] == $i) && ($dataColumns[$j] == $columnId) ? $values[$j] : "0";
                        } else {
                            $columnData[$i][$columns[$key]] = "0";
                        }
                    }
                    if ($columns[$key] == $col_name) {
                        $yearly_data[$columnData[$i]['Date']] = ($sub_total += $columnData[$i][$columns[$key]]);
                    }
                }
            }//end for
            $init_val++;
        }//foreach ends.....

        return $yearly_data;
    }

//end getYearlyData()

    function getMonthlyData($userId, $dept_id, $col_name, $year, $month) {

        $this->contain(array('User' => array('username', 'department_name'), 'Datum', 'Column'));
        $datas = $this->find('all', array('conditions' => array('Sheet.user_id' => $userId, 'Sheet.year' => $year, 'Sheet.month' => $month, 'Sheet.department_id' => $dept_id)));

        $monthly_data = array();
        $init_val = 0;
        foreach ($datas as $data) {
            $sub_total = 0;
            $dates = Set::extract('/date', $data['Datum']);
            $values = Set::extract('/value', $data['Datum']);
            $dataColumns = Set::extract('/column_id', $data['Datum']);

            $columnIds = Set::extract('/id', $data['Column']);
            $columns = Set::extract('/name', $data['Column']);

            $numDays = date('t', mktime(0, 0, 0, $data['Sheet']['month'], 1, $data['Sheet']['year']));

            $departmentData = array();
            for ($i = 1; $i <= $numDays; $i++) {

                $dateKeys = array_keys($dates, $i);

                $columnData[$i]['Date'] = date('d-m', mktime(0, 0, 0, $data['Sheet']['month'], $i, $data['Sheet']['year'])); //$data['Sheet']['year'] .",".  $data['Sheet']['month'] .",". $i;
                foreach ($columnIds as $key => $columnId) {
                    if ($columns[$key] == 'DOW') {
                        $columnData[$i][$columns[$key]] = date('D', mktime(0, 0, 0, $data['Sheet']['month'], $i, $data['Sheet']['year']));
                    } else {
                        if (isset($dateKeys[$key])) {
                            $j = $dateKeys[$key];
                            $columnData[$i][$columns[$key]] = ($dates[$j] == $i) && ($dataColumns[$j] == $columnId) ? $values[$j] : "0";
                        } else {
                            $columnData[$i][$columns[$key]] = "0";
                        }
                    }

                    if ($columns[$key] == $col_name) {
                        $monthly_data[$columnData[$i]['Date']] = ($columnData[$i][$columns[$key]]);
                    }
                }
            }//end for
            $init_val++;
        }//foreach ends.....

        return $monthly_data;
    }

//end getYearlyData()
}

//end class