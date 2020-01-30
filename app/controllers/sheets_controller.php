<?php

class SheetsController extends AppController {

    var $name = 'Sheets';
    var $helpers = array('Html', 'Javascript', 'Session');
    var $components = array('Export');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('import_file_validation', 'weekly_report', 'weekly_report_data', 'save_bob_data', 'send_webform_email_regional', 'router', 'delete_server_sheet', 'updateOrder', 'save_all_pdf', 'send_webform_email');
        $this->Auth->allow('import_excel_report', 'hotel_import_providence_nfh', 'hotel_import_providence_csv', 'hotel_import_providence', 'hotel_import_caperoyal', 'hotel_import_smartline', 'hotel_import_faircity', 'hotel_import_junction', 'hotel_import_excel', 'hotel_import_palheiro', 'hotel_import_txt', 'hotel_import_sanbona', 'hotel_import_oceanview', 'hotel_import_cie', 'hotel_import_raithwaite', 'hotel_import_4ccsv', 'router_adv', 'get_staff_pickup_chart_weekly', 'get_staff_combined_chart', 'get_staff_chart', 'get_staff_forecast_chart');
    }

    function admin_index($userId, $dept_id = null) {
        $this->__check_user($userId);

        if (!empty($this->data) && trim($this->data['Sheet']['value']) != '') {
            $conditions = array('Sheet.name LIKE' => "%" . $this->data['Sheet']['value'] . "%", 'Sheet.user_id LIKE' => "%" . $userId . "%", 'Sheet.department_id' => $this->params['pass'][1], 'Sheet.status !=' => 2);
        } else {
            $conditions = array('Sheet.user_id LIKE' => "%" . $userId . "%", 'Sheet.status !=' => 2, 'Sheet.department_id' => $this->params['pass'][1]);
        }

        $userSheets = $this->Sheet->find('all', array('conditions' => $conditions, 'contain' => array('User'), 'order' => array('Sheet.year ASC', 'Sheet.month ASC')));

        $depts_obj = ClassRegistry::init('DepartmentsUser');
        $department = $depts_obj->field('department_name', array('DepartmentsUser.user_id LIKE' => "%" . $userId . "%", 'DepartmentsUser.department_id' => $this->params['pass'][1]));
        $formula_obj = ClassRegistry::init('Formula');
        for ($i = 0; $i < count($userSheets); $i++) {
            $current_sheet_id = $userSheets[$i]['Sheet']['id'];
            $formula_data = $formula_obj->findBySheetId($current_sheet_id);
            if (!empty($formula_data)) {
                $userSheets[$i]['Sheet']['formula_status'] = "yes";
            } else {
                $userSheets[$i]['Sheet']['formula_status'] = "no";
            }
        }

        $sheetYears = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => array('DISTINCT Sheet.year'), 'recursive' => -1, 'order' => 'Sheet.year ASC'));

        $msheets = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => array('DISTINCT Sheet.year, Sheet.month'), 'recursive' => -1, 'order' => 'Sheet.year ASC'));
        $this->set(compact('userSheets', 'userId', 'department', 'dept_id', 'msheets', 'sheetYears'));
        $last_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $this->params['pass'][1]), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
        $this->set('last_sheet', $last_sheet);
    }

    function client_index($deptId = null) {
        if (!empty($deptId)) {
            $this->Sheet->User->unbindModel(array('belongsTo' => array('Client'), 'hasOne' => array('Sheet')));
            $dept_obj = ClassRegistry::init('DepartmentsUser');
            $all_users = $dept_obj->find('all', array('fields' => 'DepartmentsUser.user_id', 'conditions' => array('DepartmentsUser.department_id' => $deptId)));

            $users = array();
            foreach ($all_users as $user) {
                $users[] = $user['DepartmentsUser']['user_id'];
            }

            $conditions = array('department_id' => $deptId, 'Sheet.status !=' => 2);
            $userSheets = $this->Sheet->find('all', array('conditions' => $conditions, 'contain' => array('User', 'Column')));

            $dep_obj = ClassRegistry::init('Department');
            $Current['department_name'] = $dep_obj->field('Department.name', array('Department.id' => $deptId));

            $sheetYears = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => array('DISTINCT Sheet.year'), 'recursive' => -1, 'order' => 'Sheet.year ASC'));

            $msheets = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => array('DISTINCT Sheet.year, Sheet.month'), 'recursive' => -1, 'order' => 'Sheet.year ASC'));
            $this->set(compact('userSheets', 'userId', 'Current', 'msheets', 'sheetYears'));
        } else {
            $this->Session->setFlash("Invalid Department");
            $this->redirect(array('controller' => 'departments', 'action' => 'list'));
        }
    }

    function staff_index() {

        $msheets = array();
        $userId = $this->Auth->user('id');

        if ($userId == '338') {
            $this->redirect(array('controller' => 'users', 'action' => 'staff_flash'));
        }

        $clientId = $this->Auth->user('client_id');

        if (!empty($this->data) && trim($this->data['Sheet']['value']) != '') {
            $search = trim($this->data['Sheet']['value']);
            $this->set('search', $search);
            $conditions = array('Sheet.name LIKE' => "%" . $search . "%", 'Sheet.user_id LIKE' => "%" . $userId . "%", 'Sheet.status' => 1);
        } else {
            $conditions = array('Sheet.user_id LIKE' => "%" . $userId . "%", 'Sheet.status !=' => 2);
        }
        $userSheets = $this->Sheet->find('all', array('conditions' => $conditions, 'contain' => array('User', 'Column'), 'order' => array('Sheet.year ASC', 'Sheet.month ASC')));
        $dept_obj = ClassRegistry::init('Department');
        if (!empty($userSheets)) {
            for ($i = 0; $i < count($userSheets); $i++) {
                $dept_name = $dept_obj->findById($userSheets[$i]['Sheet']['department_id'], array('fields' => 'Department.name'));
                $userSheets[$i]['Sheet']['department_name'] = $dept_name['Department']['name'];
            }
        }

        $msheets = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => array('DISTINCT Sheet.year, Sheet.month'), 'recursive' => -1, 'order' => array('Sheet.year ASC', 'Sheet.month ASC')));
        $this->set(compact('userSheets', 'userId', 'msheets'));

        //new conditions added here for Dashborad Graphs
        App::import('Model', 'Department');
        $this->Department = new Department();
        App::import('Model', 'Client');
        $this->Client = new Client();
        $this->set('clientId', $clientId);
        $this->Client->Department->recursive = -1;
        $deparments = $this->Client->Department->find('all', array('conditions' => array('Department.client_id' => $clientId, 'Department.status' => 1)));

        $deparmentCount = count($deparments);

        $this->User = ClassRegistry::init('User');
        $users = $this->User->find('all', array('conditions' => array('User.client_id' => $clientId, 'User.status' <> '2'), 'recursive' => '-1'));
        $usersCount = count($users);

        $this->DepartmentsUser = ClassRegistry::init('DepartmentsUser');
        foreach ($users as $single_user) {
            $assignusers = $this->DepartmentsUser->find('all', array('conditions' => array('DepartmentsUser.user_id' => $single_user['User']['id']), 'recursive' => '-1'));
            $totalassignusers[] = $assignusers;
        }

        $finalassignusers = $totalassignusers[0];
        $assignuserCount = count($finalassignusers);

        $parent_data = $this->Client->find('first', array('conditions' => array('Client.id' => $clientId, 'Client.status' => 1), 'fields' => 'parent_id', 'recursive' => '0'));
        if (!empty($parent_data)) {
            $parent_id = $parent_data['Client']['parent_id'];
        } else {
            $parent_id = '';
        }

        if (!empty($parent_id) || ($clientId == '40')) {
            $child_data = $this->Client->find('all', array('conditions' =>
                array('OR' => array('Client.parent_id' => $clientId, 'Client.id' => array($clientId, $parent_id)), 'Client.status' => 1)
                , 'fields' => 'hotelname,id', 'recursive' => '0'));
        } else {
            $child_data = $this->Client->find('all', array('conditions' =>
                array('OR' => array('Client.parent_id' => $clientId, 'Client.id' => $clientId), 'Client.status' => 1)
                , 'fields' => 'hotelname,id', 'recursive' => '0'));
        }

        $show_pickup = '1';

        $this->set('child_data', $child_data);
        $this->set('show_pickup', $show_pickup);
        $this->set(compact('deparmentCount', 'usersCount', 'assignuserCount'));
    }

    function staff_list($deptId = null) {

        $msheets = array();
        $userId = $this->Auth->user('id');

        $clientId = $this->Auth->user('client_id');

        if (!empty($this->data) && trim($this->data['Sheet']['value']) != '') {
            $search = trim($this->data['Sheet']['value']);
            $this->set('search', $search);
            $conditions = array('Sheet.name LIKE' => "%" . $search . "%", 'Sheet.user_id LIKE' => "%" . $userId . "%", 'Sheet.status' => 1, 'department_id' => $deptId);
        } else {
            $conditions = array('Sheet.user_id LIKE' => "%" . $userId . "%", 'Sheet.status !=' => 2, 'department_id' => $deptId);
        }

        $userSheets = $this->Sheet->find('all', array('conditions' => $conditions, 'contain' => array('User', 'Column'), 'order' => array('Sheet.year ASC', 'Sheet.month ASC')));
        $dept_obj = ClassRegistry::init('Department');
        if (!empty($userSheets)) {
            for ($i = 0; $i < count($userSheets); $i++) {
                $dept_name = $dept_obj->findById($userSheets[$i]['Sheet']['department_id'], array('fields' => 'Department.name'));
                $userSheets[$i]['Sheet']['department_name'] = $dept_name['Department']['name'];
            }
        }
        $sheetYears = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => array('DISTINCT Sheet.year'), 'recursive' => -1, 'order' => 'Sheet.year ASC'));

        $msheets = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => array('DISTINCT Sheet.year, Sheet.month'), 'recursive' => -1, 'order' => array('Sheet.year ASC', 'Sheet.month ASC')));
        $this->set(compact('userSheets', 'userId', 'msheets', 'sheetYears'));
    }

    function staff_add($userId) {
        // Check the given user ID
        $this->__check_user($userId);

        if (!empty($this->data)) {
            $this->data['Sheet']['month'] = $this->data['Sheet']['departmentmonth']['month'];
            $this->data['Sheet']['year'] = $this->data['Sheet']['departmentmonth']['year'];
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
    }

    function staff_view($sheetId = null) {
        if (!$sheetId) {
            $this->Session->setFlash(__('Invalid Department Sheet ID', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('sheet', $this->Sheet->read(null, $sheetId));
    }

    /**
     * Action for admin to view the Department sheet
     * @param int $sheetId The Department sheet ID to be viewed
     * @access public
     * @return void
     */
    function admin_view($sheetId = null, $dept_id = null) {
        if (!$sheetId) {
            $this->Session->setFlash(__('Invalid Department Sheet ID', true));
            $this->redirect(array('action' => 'index'));
        }
        $sheet = $this->Sheet->read(null, $sheetId);

        $department_obj = ClassRegistry::init('DepartmentsUser');
        $dept_name = $department_obj->field('department_name', array('user_id' => $sheet['Sheet']['user_id'], 'department_id' => $dept_id));
        $this->set('sheet', $sheet);
        $this->set('department_name', $dept_name);
    }

//end admin_view()

    function send_webform_email() {

        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.is_email' => '1'), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.name', 'Sheet.email', 'Sheet.user_id', 'Sheet.month'), 'recursive' => '0'));

        if (!empty($all_sheets)) {
            foreach ($all_sheets as $sheet_data) {
                $emails = array();
                $sheetId = $sheet_data['Sheet']['id'];
                $sheet_name = $sheet_data['Sheet']['name'];
                $department_id = $sheet_data['Sheet']['department_id'];
                $user_id = $sheet_data['Sheet']['user_id'];

                if (!empty($sheetId)) {

                    $formula_obj = ClassRegistry::init('Formula');
                    $formula_obj->recursive = -1;
                    $formula_data = $formula_obj->findBySheetId($sheetId);

                    if (!empty($formula_data)) {

                        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/app/webroot/email_webform/" . $sheetId;

                        if (@file_exists($file_path)) {
                            @chmod($file_path, 0777);
                        } else {
                            @mkdir($file_path, '0777');
                            @chmod($file_path, 0777);
                        }

                        $user_id = trim($user_id);
                        $user_data = $this->Sheet->User->findById($user_id);

                        if (!empty($user_data)) {
                            $budget_today = array();
                            $clienImage = $user_data['Client']['logo'];
                            $data = $this->Sheet->getData($sheetId);
                            $headers = array();
                            foreach ($data[0] as $key => $value) {
                                if ($key != "sheetId") {
                                    array_push($headers, $key);
                                }
                            }

                            $rest_values = array();
                            $rest_values[0] = $headers;
                            for ($i = 0; $i < count($data); $i++) {
                                foreach ($data[$i] as $key => $values) {
                                    if ($key != "sheetId") {
                                        $rest_values[$i + 1][] = $values;
                                    }
                                }
                            }

                            ob_start();

                            App::import('Vendor', 'tcpdf');
                            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8");

                            $dept_obj = ClassRegistry::init('Department');
                            $dept_name = $dept_obj->field('name', array('id' => $department_id));
                            $date = date('Y-m-d');
                            $htms = '';
                            $htms .= '<table border="">';
                            $htms .= '<tr><td>Department Sheet : ' . $sheet_name . '</td></tr>';
                            $htms .= '<tr><td>Department Name  : ' . $dept_name . '</td></tr>';
                            $htms .= '<tr><td>Staff User : ' . $user_data['User']['firstname'] . ' ' . $user_data['User']['lastname'] . '</td></tr>';
                            $htms .= '<tr><td>Downloaded Date : ' . "$date" . '</td></tr>';
                            $htms .= '</table>';
                            $pdf->SetCreator(PDF_CREATOR);
                            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                            $pdf->SetHeaderMargin(-1);
                            $pdf->SetFooterMargin(-2);
                            $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans'
                            $pdf->SetAuthor("Revenue Performance at www.myrevenuedashboard.net");
                            $pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);
                            $pdf->setHeaderFont(array($textfont, '', 8));
                            $pdf->xheadercolor = array(150, 0, 0);
                            $pdf->xheadertext = 'Selected ';
                            $pdf->xfootertext = "Copyright &copy; Revenue Performance. All rights reserved.";
                            $pdf->setPrintHeader(false);
                            $pdf->setPrintFooter(false);
                            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                            $pdf->SetAutoPageBreak(true);
                            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                            $pdf->AddPage();
                            $pdf->SetAutoPageBreak(true);
                            $pdf->SetFillColorArray(array(255, 255, 255));
                            $pdf->SetTextColor(0, 0, 0);
                            if (!empty($clienImage)) {
                                $ext = pathinfo($clienImage, PATHINFO_EXTENSION);
                                if ($ext == "png" || $ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "bmp") {
                                    $exts = split("[/\\.]", $clienImage);
                                    $n = count($exts) - 1;
                                    $exts = $exts[$n];
                                    $imgPath = WWW_ROOT . 'files' . DS . 'clientlogos' . DS . $clienImage;
                                    $pdf->Image($imgPath, 245, 32, 40, 14, $exts, '', '', true, 150);
                                }
                            }

                            $pdf->SetXY(5, 25);
                            $pdf->writeHTML($htms, true, false, true, false, '');
                            $z = 0;
                            $html234 = "\n" . '<table cellpadding="2" cellspacing="1" border="1">';
                            $i = 0;
                            foreach ($rest_values as $vkey => $values) {
                                if ($i > 0) {
                                    $arrtmp = explode("/", $values[1]);
                                    $dateStr = $arrtmp[1] . "/" . $arrtmp[0] . "/" . $arrtmp[2];
                                    $day = date('N', strtotime($dateStr));
                                } else {
                                    $day = "NA";
                                }
                                $html234 .= '<tr>';
                                foreach ($values as $zkey => $val) {
                                    if (($zkey != 0) && ($zkey != 1) && ($vkey != 0)) {
                                        if (($val != 0) || ($val != 0.00)) {
                                            $z = 1;
                                        }
                                    }
                                    $html234 .= '<td>' . $val . '</td>';
                                }
                                $html234 .= '</tr>';
                                $i++;
                            }
                            $html234 .= '</table>';
                            $html_res['values'] = $html234;
                            $pdf->SetXY(125, 15);
                            $pdf->writeHTML($user_data['Client']['hotelname'], true, false, true, false, '');
                            $pdf->SetXY(5, 50);
                            $pdf->writeHTML($html_res['values'], true, false, true, false, '');
                            ob_end_clean();

                            $path = $file_path . "/" . $user_id . '_' . date('d-M-Y') . ".pdf";

                            $pdf->Output($path, 'F');

                            $total_array = array();
                            $lastYear_today = array();
                            foreach ($rest_values as $key => $rest_value) {
                                if (in_array('Total', $rest_value)) {
                                    $total_array = $rest_value;
                                } elseif (in_array('Budget', $rest_value)) {
                                    $budget_today = $rest_value;
                                } elseif (in_array('LY Actual', $rest_value)) {
                                    $lastYear_today = $rest_value;
                                }
                            }

                            $total_keys = array();
                            $budget_rev_arr = array();
                            $ly_rev_arr = array();
                            unset($rest_values[0][0], $rest_values[0][1]);
                            foreach ($rest_values[0] as $key => $rest_value) {
                                $total_keys[$rest_value] = $total_array[$key];

                                //for budget_line
                                $budget_rev_arr[$rest_value] = $budget_today[$key];

                                //for LY ACTUAL
                                $ly_rev_arr[$rest_value] = $lastYear_today[$key];
                            }
                            //Get Yesterday date and data first
                            $yest_date_data = ClassRegistry::init('SheetHistory')->find('first', array('conditions' => array('SheetHistory.sheet_id' => $sheetId), 'field' => 'date', 'order' => 'SheetHistory.date DESC'));
                            $SheetdataDetails = array();
                            if (!empty($yest_date_data)) {
                                $yest_date = $yest_date_data['SheetHistory']['date'];
                                $SheetdataDetails = ClassRegistry::init('SheetHistory')->find('list', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => $yest_date), 'fields' => array('SheetHistory.type', 'SheetHistory.total')));
                            }

                            //save data once get yesterday data
                            $SheetdataDate = ClassRegistry::init('SheetHistory')->find('count', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => date('Y-m-d'))));
                            if ($SheetdataDate == 0) {
                                $t = 0;
                                foreach ($total_keys as $tkey => $total_key) {
                                    $Sheetdata[$t]['sheet_id'] = $sheetId;
                                    $Sheetdata[$t]['date'] = date('Y-m-d');
                                    $Sheetdata[$t]['type'] = $tkey;
                                    $Sheetdata[$t]['total'] = $total_key;
                                    $t++;
                                }
                                ClassRegistry::init('SheetHistory')->saveAll($Sheetdata); //plz uncomment
                            }

                            $emails = $this->Sheet->EmailSheet->find('list', array('conditions' => array('EmailSheet.sheet_id' => $sheetId), 'fields' => array('EmailSheet.id', 'EmailSheet.email')));

                            if (!empty($emails)) {

                                set_time_limit(40);

                                $email_from = "duncan.bramwell@revenue-performance.com"; // The email you are sending from (example)
                                $monthName = date("F", mktime(0, 0, 0, $sheet_data['Sheet']['month'], 10));
                                $email_subject = "Daily Update - " . $user_data['Client']['hotelname'] . " (" . $monthName . ")"; // The Subject of the email
                                $summary_table = "<tr valign='top'>
                                            <td style='padding: 0px 3px 10px 0px;'>
                                                    <table cellpadding='0' border='0' style='font-family: verdana,arial,sans-serif;font-size:11px;color:#333333;border-width: 1px;border-color: #666666;border-collapse: collapse;line-height:20px;'>
                                                    <tr>
                                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>&nbsp;</td>
                                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Today</td>";
                                if (count($SheetdataDetails) > 0) {

                                    $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Yesterday</td>

                                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'> % Change </td>
                                                            <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>&nbsp;</td>";
                                }
                                $summary_table .= "</tr>";
                                foreach ($total_keys as $key => $total_key) {

                                    if (($key != 'TripAdvisor') && ($key != 'BAR Level') && ($key != 'Notes')) {
                                        $total_key_new = (str_replace(".00", "", $total_key));
                                        $summary_table .= "<tr>
                                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total " . $key . "</td>
                                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $total_key_new . "</td>";

                                        if (count($SheetdataDetails) > 0) {
                                            if ($key == 'Sell Rate') {
                                                $color = '';
                                            } else if ($key == 'Pickup Req') {
                                                $percent_change = 100.0 * (str_replace(",", "", $total_key) - str_replace(",", "", $SheetdataDetails[$key])) / str_replace(",", "", $total_key);
                                                $percent_change = round($percent_change, 2);
                                                if ($percent_change == 0) {
                                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_White.png" alt="White">';
                                                } elseif ($percent_change < 0) {
                                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Green.png" alt="Red">';
                                                } elseif ($percent_change > 0 && $percent_change < 5) {
                                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Yellow.png" alt="Yellow">';
                                                } elseif ($percent_change > 5) {
                                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Red.png" alt="Green">';
                                                }
                                            } else {
                                                $percent_change = 100.0 * (str_replace(",", "", $total_key) - str_replace(",", "", $SheetdataDetails[$key])) / str_replace(",", "", $total_key);
                                                $percent_change = round($percent_change, 2);
                                                if ($percent_change == 0) {
                                                    //$color = 'white';
                                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_White.png" alt="White">';
                                                } elseif ($percent_change < 0) {
                                                    //$color = 'red';
                                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Red.png" alt="Red">';
                                                } elseif ($percent_change > 0 && $percent_change < 5) {
                                                    //$color = 'yellow';
                                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Yellow.png" alt="Yellow">';
                                                } elseif ($percent_change > 5) {
                                                    //$color = 'green';
                                                    $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Green.png" alt="Green">';
                                                }
                                            }


                                            $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails[$key]));
                                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>
                                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $percent_change . " %</td>
                                                                    <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>";
                                        }
                                        $summary_table .= "</tr>";
                                    }//enf if key value check
                                }//End Foreach
                                //code to LY Actual line
                                if (!empty($lastYear_today)) {
                                    $color = '';
                                    $summary_table .= "<tr>
                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total LY Actual</td>
                                            <td colspan='3' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $ly_rev_arr['Revenue'] . "</td>
                                            <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>
                                            </tr>";
                                }

                                //code to add budget line
                                if (!empty($budget_today)) {
                                    $variance = str_replace(',', '', $total_keys['Revenue']) - str_replace(',', '', $budget_rev_arr['Revenue']);
                                    $variance = number_format($variance, '2');

                                    $color = '';
                                    $summary_table .= "<tr>
                                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total Budget</td>
                                                            <td colspan='3' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $budget_rev_arr['Revenue'] . "</td>
                                                            <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>
                                                            </tr>";

                                    if ($variance == 0) {
                                        $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_White.png" alt="White">';
                                    } elseif ($variance < 0) {
                                        $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Red.png" alt="Red">';
                                    } elseif ($variance > 0) {
                                        $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Green.png" alt="Green">';
                                    }
                                    $summary_table .= "<tr>
                                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total Variance</td>
                                                            <td colspan='3' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $variance . "</td>
                                                            <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>
                                                            </tr>";
                                }

                                $summary_table .= "</table>
                                            </td>
                                            </tr>";

                                $email_txt = "<table cellspacing='0' cellpadding='0' border='0' >
                                                            <tr>
                                                            <td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>MyDashboard
                                                            </td>
                                                            </tr>
                                                            <tr>
                                                            <td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' valign='top' style='font-size: 12px;'>
                                                            <div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
                                                            <table cellpadding='0' style='margin-top: 5px;border:0;'>

                                                            <tr valign='top'>
                                                            <td style='padding: 0px 3px 10px 0px;'>
                                                            <FONT SIZE=2 FACE='Arial'>Please find below the Summary of your revenue forecast.</FONT>
                                                            </td>
                                                            </tr>" . $summary_table . "

                                                            </table>
                                                            <br>
                                                                    <br>
                                                                    <div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
                                                                    <br>
                                                                    </div>
                                                                    <div style='margin: 0pt;'>Thanks &amp; Regards,<br>MyDashboard<br>
                                                                    <a href='http://www.myrevenuedashboard.net'>www.myrevenuedashboard.net</a>
                                                                    </div>
                                                                    </td>
                                                                    <td align='left' width='150' valign='top' style='padding-left: 15px;'>
                                                                    <table cellspacing='0' cellpadding='0' width='100%'>
                                                                    <tbody><tr>
                                                                    <td style='padding: 10px'>
                                                                    <div style='margin-bottom: 15px;'>
                                                                    <a target='blank' href='http://www.revenue-performance.com'>
                                                                            <img src='http://" . $_SERVER['HTTP_HOST'] . "/img/RP-logo.png' alt='' style='border:0px;'>
                                                                    </a>
                                                                    </div>
                                                                    </td>
                                                                    </tr>
                                                                    </tbody></table>
                                                                    </td>
                                                                    </tr>
                                                                    </tbody></table>
                                                                    <img alt='' style='border: 0pt none; min-height: 1px; width: 1px;'>
                                                                    </td></tr></tbody></table>";

                                // echo $email_txt; exit;

                                $fileatt = $path; // Path to the file (example)
                                $fileatt_type = "application/pdf"; // File Type
                                $fileatt_name = date('d-M-Y') . "_Webform_Details.pdf"; // Filename that will be used for the file as the attachment
                                $file = fopen($fileatt, 'rb');
                                $data = fread($file, filesize($fileatt));
                                fclose($file);
                                $semi_rand = md5(time());
                                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
                                $headers = "From: $email_from"; // Who the email is from (example)
                                $headers .= "\nMIME-Version: 1.0\n" .
                                        "Content-Type: multipart/mixed;\n" .
                                        " boundary=\"{$mime_boundary}\"";
                                @$email_message .= "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type:text/html;charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $email_txt;
                                $email_message .= "\n\n";
                                $data = chunk_split(base64_encode($data));
                                $email_message .= "--{$mime_boundary}\n" .
                                        "Content-Type: {$fileatt_type};\n" .
                                        " name=\"{$fileatt_name}\"\n" .
                                        "Content-Transfer-Encoding: base64\n\n" .
                                        $data . "\n\n" .
                                        "--{$mime_boundary}--\n";

                                foreach ($emails as $emls) {
                                    $email_to = $emls; // The email you are sending to (example)
                                    if (!empty($email_to)) {
                                        if (mail($email_to, $email_subject, $email_message, $headers)) {
                                            echo 'Mail Send <br/>';
                                        } else {
                                            echo 'Mail Not Send <br/>';
                                        }
                                    }
                                }
                            }
                            unlink($path);
                            rmdir($file_path);
                        }//end foreach
                    }//end if statement
                }//end if statement
            }//end if statement
        }

        ClassRegistry::init('SheetHistory')->deleteAll(array('DATE(date) < ' => date("Y-m-d", strtotime("-6 months"))));

        //$sendFlashUpdate = $this->requestAction('/Clients/email_flash');
        exit;
    }

//function to send the summary for a Hotel Group
    function send_webform_email_regional() {

        $this->layout = false;
        $this->autoRender = false;

        $alert_clients = ClassRegistry::init('EmailSummarySheet')->find('list', array('conditions' => array('EmailSummarySheet.type' => 'regional'), 'fields' => array('client_id'), 'group' => 'client_id'));

        foreach ($alert_clients as $client_id) {

            App::import('Model', 'Department');
            $this->Department = new Department();
            App::import('Model', 'Client');
            $this->Client = new Client();

            $month = date('m');
            $year = date('Y');

            $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $client_id), 'fields' => 'hotelname'));
            $hotelname = $hotels_data['Client']['hotelname'];

            $client_id_list = $this->Client->find('list', array('conditions' => array('OR' => array('Client.parent_id' => $client_id, 'Client.id' => $client_id), 'Client.status' => 1), 'fields' => 'id', 'recursive' => '0'));

            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $client_id_list, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('all', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));

            $dept_ids = array();
            unset($dept_ids);
            foreach ($dept_data as $dept) {
                $dept_ids[] = $dept['Department']['id'];
            }

            $monthName = date("F", mktime(0, 0, 0, $month, 10));
            $email_main_subject = "Daily Update - " . $hotelname . " (" . $monthName . ")";

            $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' => $year, 'Sheet.month' => $month, 'Sheet.department_id' => $dept_ids), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.name', 'Sheet.email', 'Sheet.user_id', 'Sheet.month'), 'recursive' => '0'));

            if (!empty($all_sheets)) {
                $summary_table = '';
                foreach ($all_sheets as $sheet_data) {
                    $emails = array();
                    $sheetId = $sheet_data['Sheet']['id'];
                    $sheet_name = $sheet_data['Sheet']['name'];
                    $department_id = $sheet_data['Sheet']['department_id'];
                    $user_id = $sheet_data['Sheet']['user_id'];

                    $user_id = trim($user_id);
                    $user_data = $this->Sheet->User->findById($user_id);

                    if (!empty($user_data)) {

                        //Sheet data work started here
                        $sheet_value = $this->Sheet->getData($sheetId);

                        $budget_today = array();

                        //Get previous date and data first
                        foreach ($sheet_value as $values) {
                            if ($values['Date'] == 'Total') {
                                foreach ($values as $tkey => $total_key) {
                                    if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                        $Sheetdata_today[$tkey] = $total_key;
                                    }
                                }
                            } elseif ($values['Date'] == 'Budget') {
                                foreach ($values as $tkey => $total_key) {
                                    if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                        $budget_today[$tkey] = $total_key;
                                    }
                                }
                            } elseif ($values['Date'] == 'LY Actual') {
                                foreach ($values as $tkey => $total_key) {
                                    if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                        $lastYear_today[$tkey] = $total_key;
                                    }
                                }
                            }
                        }//end foreach to save data

                        $SheetdataDate = ClassRegistry::init('SheetHistory')->find('count', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => date('Y-m-d'))));
                        if ($SheetdataDate == 0) {
                            $t = 0;
                            foreach ($Sheetdata_today as $tkey => $total_key) {
                                $Sheetdata[$t]['sheet_id'] = $sheetId;
                                $Sheetdata[$t]['date'] = date('Y-m-d');
                                $Sheetdata[$t]['type'] = $tkey;
                                $Sheetdata[$t]['total'] = $total_key;
                                $t++;
                            }
                            ClassRegistry::init('SheetHistory')->saveAll($Sheetdata); //plz uncomment
                        }
                        $SheetdataDetails = array();

                        $yesterday_date = date('Y-m-d', strtotime('-1 day'));
                        //$yest_date_data = ClassRegistry::init('SheetHistory')->find('first', array('conditions' => array('SheetHistory.sheet_id' => $sheetId), 'field' => 'date', 'order' => 'SheetHistory.date DESC'));
                        $yest_date_data = ClassRegistry::init('SheetHistory')->find('first', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => $yesterday_date), 'field' => 'date', 'order' => 'SheetHistory.date DESC'));

                        if (!empty($yest_date_data)) {
                            $yest_date = $yest_date_data['SheetHistory']['date'];
                            $SheetdataDetails = ClassRegistry::init('SheetHistory')->find('list', array('conditions' => array('SheetHistory.sheet_id' => $sheetId, 'SheetHistory.date' => $yest_date), 'fields' => array('SheetHistory.type', 'SheetHistory.total')));
                        }

                        $email_from = "duncan.bramwell@revenue-performance.com"; // The email you are sending from (example)
                        $monthName = date("F", mktime(0, 0, 0, $sheet_data['Sheet']['month'], 10));
                        $email_subject = "Daily Update - " . $user_data['Client']['hotelname'] . " (" . $monthName . ")"; // The Subject of the email
                        $summary_table .= "<table><tr><td><b>" . $email_subject . "</b></td></tr>
                                    <tr valign='top'>
                                            <td style='padding: 0px 3px 10px 0px;'>
                                                    <table cellpadding='0' border='0' style='font-family: verdana,arial,sans-serif;font-size:11px;	color:#333333;border-width: 1px;border-color: #666666;border-collapse: collapse;line-height:20px;'>
                                                    <tr>
                                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>&nbsp;</td>
                                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Today</td>";
                        if (count($SheetdataDetails) > 0) {

                            $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'>Yesterday</td>
                                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede'> % Change </td>
                                                            <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>&nbsp;</td>";
                        }
                        $summary_table .= "</tr>";
                        foreach ($Sheetdata_today as $key => $total_key) {

                            if (($key != 'TripAdvisor') && ($key != 'BAR Level') && ($key != 'Notes')) {
                                $total_key_new = (str_replace(".00", "", $total_key));
                                $summary_table .= "<tr>
                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total " . $key . "</td>
                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $total_key_new . "</td>";
                                if (count($SheetdataDetails) > 0) {
                                    if ($key == 'Sell Rate') {
                                        $color = '';
                                    } else if ($key == 'Pickup Req') {
                                        $percent_change = 100.0 * (str_replace(",", "", $total_key) - str_replace(",", "", $SheetdataDetails[$key])) / str_replace(",", "", $total_key);
                                        $percent_change = round($percent_change, 2);
                                        if ($percent_change == 0) {
                                            $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_White.png" alt="White">';
                                        } elseif ($percent_change < 0) {
                                            $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Green.png" alt="Red">';
                                        } elseif ($percent_change > 0 && $percent_change < 5) {
                                            $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Yellow.png" alt="Yellow">';
                                        } elseif ($percent_change > 5) {
                                            $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Red.png" alt="Green">';
                                        }
                                    } else {
                                        $percent_change = 100.0 * (str_replace(",", "", $total_key) - str_replace(",", "", $SheetdataDetails[$key])) / str_replace(",", "", $total_key);
                                        $percent_change = round($percent_change, 2);
                                        if ($percent_change == 0) {
                                            $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_White.png" alt="White">';
                                        } elseif ($percent_change < 0) {
                                            $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Red.png" alt="Red">';
                                        } elseif ($percent_change > 0 && $percent_change < 5) {
                                            $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Yellow.png" alt="Yellow">';
                                        } elseif ($percent_change > 5) {
                                            $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Green.png" alt="Green">';
                                        }
                                    }

                                    $SheetdataDetails_new = (str_replace(".00", "", $SheetdataDetails[$key]));
                                    $summary_table .= "<td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $SheetdataDetails_new . "</td>
                                                    <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $percent_change . " %</td>
                                                    <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>";
                                }

                                $summary_table .= "</tr>";
                            }//enf if key value check
                        }//End Foreach
                        //code to LY Actual line
                        if (!empty($lastYear_today)) {

                            $variance = str_replace(',', '', $Sheetdata_today['Revenue']) - str_replace(',', '', $lastYear_today['Revenue']);
                            $variance = number_format($variance, '2');

                            if ($variance == 0) {
                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_White.png" alt="White">';
                            } elseif ($variance < 0) {
                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Red.png" alt="Red">';
                            } elseif ($variance > 0) {
                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Green.png" alt="Green">';
                            }

                            $color = '';
                            $summary_table .= "<tr>
                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total LY Actual</td>
                            <td colspan='3' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $lastYear_today['Revenue'] . "</td>
                            <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>
                            </tr>";
                        }

                        //code to add budget line
                        if (!empty($budget_today)) {

                            $variance = str_replace(',', '', $Sheetdata_today['Revenue']) - str_replace(',', '', $budget_today['Revenue']);
                            $variance = number_format($variance, '2');

                            $color = '';
                            $summary_table .= "<tr>
                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total Budget</td>
                                            <td colspan='3' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $budget_today['Revenue'] . "</td>
                                            <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>
                                            </tr>";

                            if ($variance == 0) {
                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_White.png" alt="White">';
                            } elseif ($variance < 0) {
                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Red.png" alt="Red">';
                            } elseif ($variance > 0) {
                                $color = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/img/Circle_Green.png" alt="Green">';
                            }
                            $summary_table .= "<tr>
                                            <td style='border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;'>Total Variance</td>
                                            <td colspan='3' style='text-align:center;border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;'>" . $variance . "</td>
                                            <td style='border-width: 1px;border-style: solid;border-color: #ffffff;background-color: #ffffff;padding-left:10px;'>" . $color . "</td>
                                            </tr>";
                        }

                        $summary_table .= "</table></td></tr></table>";
                    }//end foreach
                }//end if statement

                $email_txt = "<table cellspacing='0' cellpadding='0' border='0' >
                                    <tr><td style='padding: 4px 8px; background: rgb(59, 89, 152) none repeat scroll 0% 0%; -mz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); font-weight: bold; font-family: 'lucida grande',tahoma,verdana,arial,sans-serif; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;'>MyDashboard</td></tr>
                                    <tr><td valign='top' style='border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(59, 89, 152); padding: 15px; background-color: rgb(255, 255, 255); font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;'><table><tr><td align='left' valign='top' style='font-size: 12px;'>
                                    <div style='margin-bottom: 15px;'><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div><br/>
                                    <table cellpadding='0' style='margin-top: 5px;border:0;'>
                                    <tr valign='top'>
                                    <td style='padding: 0px 3px 10px 0px;'>
                                    <FONT SIZE=2 FACE='Arial'>Please find below the Summary of your revenue forecast.</FONT>
                                    </td>
                                    </tr></table><br/><br/>" . $summary_table . "
                                    <br><br><div style='border-bottom: 1px solid rgb(204, 204, 204); line-height: 5px;'> </div>
                                    <br></div>
                                    <div style='margin: 0pt;'>Thanks &amp; Regards,<br>MyDashboard<br>
                                    <a href='http://www.myrevenuedashboard.net'>www.myrevenuedashboard.net</a>
                                    </div></td>
                                    <td align='left' width='150' valign='top' style='padding-left: 15px;'>
                                    <table cellspacing='0' cellpadding='0' width='100%'><tbody><tr><td style='padding: 10px'>
                                    <div style='margin-bottom: 15px;'><a target='blank' href='http://www.revenue-performance.com'>
                                    <img src='http://" . $_SERVER['HTTP_HOST'] . "/img/RP-logo.png' alt='' style='border:0px;'></a></div>
                                    </td></tr></tbody></table>
                                    </td></tr></tbody></table>
                                    <img alt='' style='border: 0pt none; min-height: 1px; width: 1px;'>
                                    </td></tr></tbody></table>";

                $headers = 'From: Revenue Performance<support@revenue-performance.com>' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                $headers .= "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

                set_time_limit(40);

                $email_list = ClassRegistry::init('EmailSummarySheet')->find('list', array('conditions' => array('client_id' => $client_id, 'EmailSummarySheet.type' => 'regional'), 'fields' => array('email')));

                foreach ($email_list as $email_to) {
                    if (!empty($email_to)) {
                        if (mail($email_to, $email_main_subject, $email_txt, $headers)) {
                            echo 'Mail Send <br/>';
                        } else {
                            echo 'Mail Not Send <br/>';
                        }
                    }
                }
            }
        }

        $this->requestAction('/subadmins/email_webform_summary');
        exit;
    }

    /**
     * Action for CLient to view the Department sheet
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
    }

//end client_view()

    function client_assign($id = null) {
        $clientId = $this->Auth->user('id');
        App::import('Model', 'User');
        $this->User = new User();
        $dept_obj = ClassRegistry::init('DepartmentsUser');
        App::import('Model', 'Department');
        $this->Department = new Department();
        $userid = $dept_obj->find('list', array('fields' => 'DepartmentsUser.user_id', 'conditions' => array('DepartmentsUser.department_id' => $id)));
        $flag = 0;
        $this->set("userid", $userid);
        if (!empty($this->data)) {
            foreach ($userid as $user) {
                $dept_obj->deleteAll(array('DepartmentsUser.user_id' => $user, 'DepartmentsUser.department_id' => $id));
            }
            $user_ids = $this->data['User']['id'];
            for ($i = 0; $i < count($user_ids); $i++) {
                $dept_id = $dept_obj->field('id', array('DepartmentsUser.user_id' => $user_ids[$i], 'DepartmentsUser.department_id' => $id));
                if (!empty($dept_id)) {
                    $this->data['DepartmentsUser']['id'] = $dept_id;
                } else {
                    $this->data['DepartmentsUser']['id'] = "";
                }

                $this->data['DepartmentsUser']['department_name'] = $this->data['User']['department_name'];
                $this->data['DepartmentsUser']['department_id'] = $id;
                $this->data['DepartmentsUser']['user_id'] = $user_ids[$i];
                if ($dept_obj->save($this->data['DepartmentsUser'])) {
                    $flag = 1;

                    //we have to find out department sheets
                    $sheet_obj = ClassRegistry::init('Sheet');
                    $sheet_obj->recursive = -1;
                    $sheet_array = $sheet_obj->find('all', array('conditions' => array('Sheet.department_id' => $id, 'Sheet.status !=' => 2)));

                    if (!empty($sheet_array)) {
                        foreach ($sheet_array as $sheetupdate) {
                            //assign users to those sheets
                            $arraytoupdate = $sheetupdate;
                            if ($i == 0) {
                                $arraytoupdate['Sheet']['user_id'] = $user_ids[$i];
                            } else {
                                $arraytoupdate['Sheet']['user_id'] = $arraytoupdate['Sheet']['user_id'] . ", " . $user_ids[$i];
                            }
                            $sheet_obj->save($arraytoupdate);
                            $arraytoupdate = null;
                        }
                    }
                }
            }
            if ($flag) {
                $this->Session->setFlash(__('The User has been updated', true));
                $this->redirect(array('controller' => 'departments', 'action' => 'client_list'));
            } else {
                $this->Session->setFlash(__('The USer could not be updated. Please, try again.', true));
            }
        }

        $deparid = $this->params;
        $departmentid = $deparid ['pass'][0];

        $departmentname = $this->Department->find('all', array('fields' => array('name', 'id'), 'conditions' => array('Department.id' => $departmentid)));
        $departmentlist = $this->Department->find('list', array('fields' => array('name', 'id'), 'conditions' => array('Department.id' => $departmentid)));
        $clientFrmDetpId = $this->Department->find('first', array('fields' => array('client_id'), 'conditions' => array('Department.id' => $departmentid)));

        $userdata = $this->User->find('all', array('fields' => array(), 'conditions' => array('User.client_id' => $clientFrmDetpId['Department']['client_id'], 'User.status !=' => 2)));

        $this->set('departmentname', $departmentname);
        $this->set('departmentlist', $departmentlist);
        $this->set('userdata', $userdata);
    }

    function admin_assign($clientId = null, $id = null) {

        App::import('Model', 'User');
        $this->User = new User();
        $dept_obj = ClassRegistry::init('DepartmentsUser');
        App::import('Model', 'Department');
        $this->Department = new Department();

        $userid = $dept_obj->find('list', array('fields' => 'DepartmentsUser.user_id', 'conditions' => array('DepartmentsUser.department_id' => $id)));
        $flag = 0;
        $this->set("userid", $userid);

        if (!empty($this->data)) {

            foreach ($userid as $user) {
                $dept_obj->deleteAll(array('DepartmentsUser.user_id' => $user, 'DepartmentsUser.department_id' => $id));
            }

            $user_ids = $this->data['User']['id'];

            for ($i = 0; $i < count($user_ids); $i++) {
                $dept_id = $dept_obj->field('id', array('DepartmentsUser.user_id' => $user_ids[$i], 'DepartmentsUser.department_id' => $id));
                if (!empty($dept_id)) {
                    $this->data['DepartmentsUser']['id'] = $dept_id;
                } else {
                    $this->data['DepartmentsUser']['id'] = "";
                }

                $this->data['DepartmentsUser']['department_name'] = $this->data['User']['department_name'];
                $this->data['DepartmentsUser']['department_id'] = $id;
                $this->data['DepartmentsUser']['user_id'] = $user_ids[$i];
                if ($dept_obj->save($this->data['DepartmentsUser'])) {
                    $flag = 1;
                    $sheet_obj = ClassRegistry::init('Sheet');
                    $sheet_obj->recursive = -1;
                    $sheet_array = $sheet_obj->find('all', array('conditions' => array('Sheet.department_id' => $id, 'Sheet.status !=' => 2)));

                    if (!empty($sheet_array)) {
                        foreach ($sheet_array as $sheetupdate) {
                            //assign users to those sheets
                            $arraytoupdate = $sheetupdate;
                            if ($i == 0) {
                                $arraytoupdate['Sheet']['user_id'] = $user_ids[$i];
                            } else {
                                $arraytoupdate['Sheet']['user_id'] = $arraytoupdate['Sheet']['user_id'] . ", " . $user_ids[$i];
                            }
                            $sheet_obj->save($arraytoupdate);
                            $arraytoupdate = null;
                        }
                    }
                }
            }

            if ($flag) {
                $this->Session->setFlash(__('The User has been updated', true));
                $this->redirect(array('controller' => 'departments', 'action' => 'admin_index', $clientId));
            } else {
                $this->Session->setFlash(__('The User could not be updated. Please, try again.', true));
            }
        }

        $deparid = $this->params;
        $departmentid = $id;

        $departmentname = $this->Department->find('all', array('fields' => array('name', 'id'), 'conditions' => array('Department.id' => $departmentid)));
        $departmentlist = $this->Department->find('list', array('fields' => array('name', 'id'), 'conditions' => array('Department.id' => $departmentid)));
        $clientFrmDetpId = $this->Department->find('first', array('fields' => array('client_id'), 'conditions' => array('Department.id' => $departmentid)));
        $userdata = $this->User->find('all', array('fields' => array(), 'conditions' => array('User.client_id' => $clientFrmDetpId['Department']['client_id'], 'User.status !=' => 2)));

        $this->set('clientId', $clientId);
        $this->set('departmentname', $departmentname);
        $this->set('departmentlist', $departmentlist);
        $this->set('userdata', $userdata);
    }

    function admin_add($userId) {
        // Check the given user ID

        $this->__check_user($userId);

        if (!empty($this->data)) {
            if ($this->data['Sheet']['is_email'] == 0) {
                unset($this->data['EmailSheet']);
            }

            $this->data['Sheet']['month'] = $this->data['Sheet']['departmentmonth']['month'];
            $this->data['Sheet']['year'] = $this->data['Sheet']['departmentmonth']['year'];
            $this->data['Sheet']['user_id'] = $userId;
            $dept_id = $this->data['Sheet']['department_id'];
            $this->Sheet->create();

            if ($this->Sheet->saveAll($this->data)) {
                $this->Session->setFlash(__('The Department sheet has been saved', true));
                $this->redirect(array('action' => 'index', $userId, $dept_id));
            } else {
                $this->Session->setFlash(__('The Department sheet could not be saved. Please, try again.', true));
            }
        }

        $columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2)));
        $rows = $this->Sheet->Row->find('list', array('conditions' => array('Row.status !=' => 2)));
        $depts_obj = ClassRegistry::init('DepartmentsUser');
        $department = $depts_obj->field('department_name', array('DepartmentsUser.user_id' => $userId, 'DepartmentsUser.department_id' => $this->params['pass'][1]));

        $this->set(compact('userId', 'department', 'columns', 'rows'));
    }

//end admin_add()

    /* function to copy entire sheet */
    function admin_copy($userId = null, $sheet_id = null) {
        // Check the given user ID
        $this->__check_user($userId);

        if (!empty($this->data)) {

            /* get data from rows of copied sheet */
            $rows_obj = ClassRegistry::init('RowsSheet');
            $rows_data = $rows_obj->find('all', array('conditions' => array('sheet_id' => $sheet_id)));

            /* get data from columns of copied sheet */
            $cols_obj = ClassRegistry::init('ColumnsSheet');
            $cols_data = $cols_obj->find('all', array('conditions' => array('sheet_id' => $sheet_id)));

            /* get data from data of copied sheet */
            $datas_obj = ClassRegistry::init('Datum');
            $datas_data = $datas_obj->find('all', array('conditions' => array('sheet_id' => $sheet_id)));

            /* get data from data of copied sheet */
            $formulas_obj = ClassRegistry::init('Formula');
            $formula_data = $formulas_obj->find('all', array('conditions' => array('sheet_id' => $sheet_id)));

            $this->data['Sheet']['month'] = $this->data['Sheet']['departmentmonth']['month'];
            $this->data['Sheet']['year'] = $this->data['Sheet']['departmentmonth']['year'];
            $this->data['Sheet']['user_id'] = $userId;
            $dept_id = $this->data['Sheet']['department_id'];
            $this->Sheet->create();

            $days_in_sheet_month = cal_days_in_month(CAL_GREGORIAN, $this->data['Sheet']['departmentmonth']['month'], $this->data['Sheet']['departmentmonth']['year']);

            if ($this->Sheet->saveAll($this->data)) {
                $curr_sheet_id = $this->Sheet->getLastInsertID();

                /* save all rows_sheets data */
                if (!empty($rows_data)) {
                    foreach ($rows_data as $rows) {
                        unset($rows['RowsSheet']['id']);
                        unset($rows['RowsSheet']['sheet_id']);
                        $rows['RowsSheet']['sheet_id'] = $curr_sheet_id;
                        $rows_obj->create();
                        $rows_obj->saveAll($rows);
                    }
                }

                /* save all columns_sheets data */
                if (!empty($cols_data)) {
                    foreach ($cols_data as $cols) {
                        unset($cols['ColumnsSheet']['id']);
                        unset($cols['ColumnsSheet']['sheet_id']);
                        $cols['ColumnsSheet']['sheet_id'] = $curr_sheet_id;
                        $cols_obj->create();
                        $cols_obj->saveAll($cols);
                    }
                }

                /* save all data as datum data */
                if (!empty($rows_data)) {
                    foreach ($datas_data as $datas) {
                        unset($datas['Datum']['id']);
                        unset($datas['Datum']['sheet_id']);
                        $datas['Datum']['sheet_id'] = $curr_sheet_id;
                        $datas_obj->create();
                        $datas_obj->saveAll($datas['Datum']);
                    }
                }

                /* save all data as datum data */
                if (!empty($formula_data)) {
                    foreach ($formula_data as $formula) {
                        unset($formula['Formula']['id']);
                        unset($formula['Formula']['sheet_id']);
                        $formula['Formula']['sheet_id'] = $curr_sheet_id;

                        //check the month and update the days as per month in the required formula added on 25 Jan'2016
                        $replace_array = array('* 31 )', '* 30 )', '( 31 *', '( 30 *');
                        $replace1 = '* ' . $days_in_sheet_month . ' )';
                        $replace2 = '* ' . $days_in_sheet_month . ' )';
                        $replace3 = '( ' . $days_in_sheet_month . ' *';
                        $replace4 = '( ' . $days_in_sheet_month . ' *';
                        $new_array = array($replace1, $replace2, $replace3, $replace4);
                        $updated_formula = str_replace($replace_array, $new_array, $formula['Formula']['formula']);
                        $formula['Formula']['formula'] = $updated_formula;

                        $formulas_obj->create();
                        $formulas_obj->saveAll($formula['Formula']);
                    }
                }

                $this->Session->setFlash(__('The Department sheet has been saved', true));
                $this->redirect(array('action' => 'index', $userId, $dept_id));
            } else {
                $this->Session->setFlash(__('The Department sheet could not be saved. Please, try again.', true));
            }
        }

        $columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2)));
        $rows = $this->Sheet->Row->find('list', array('conditions' => array('Row.status !=' => 2)));

        $depts_obj = ClassRegistry::init('DepartmentsUser');
        $department = $depts_obj->field('department_name', array('DepartmentsUser.user_id' => $userId, 'DepartmentsUser.department_id' => $this->params['pass'][2]));
        $this->set(compact('userId', 'department', 'columns', 'rows'));
    }

//end admin_copy()


    /*     * **********************
     * Function Added on 05 July 2013
     * Added By : Neema Tembhurnikar
     * Description : to make mutiple copies of sheets for the selected months
     * *********************** */
    function admin_copysheet($userId = null, $sheet_id = null) {

        // Check the given user ID
        //$this->__check_user($userId);

        if (!empty($this->data)) {

            $depts_obj = ClassRegistry::init('DepartmentsUser');
            $departmentUsers = $depts_obj->find('list', array('conditions' => array('DepartmentsUser.department_id' => $this->data['department_name']), 'fields' => array('user_id', 'user_id')));
            $save_userId = implode(',', $departmentUsers);


            /* get data from rows of copied sheet */
            $rows_obj = ClassRegistry::init('RowsSheet');
            $rows_data = $rows_obj->find('all', array('conditions' => array('sheet_id' => $sheet_id)));

            /* get data from columns of copied sheet */
            $cols_obj = ClassRegistry::init('ColumnsSheet');
            $cols_data = $cols_obj->find('all', array('conditions' => array('sheet_id' => $sheet_id)));

            /* get data from data of copied sheet */
            $datas_obj = ClassRegistry::init('Datum');
            $datas_data = $datas_obj->find('all', array('conditions' => array('sheet_id' => $sheet_id)));

            /* get data from data of copied sheet */
            $formulas_obj = ClassRegistry::init('Formula');
            $formula_data = $formulas_obj->find('all', array('conditions' => array('sheet_id' => $sheet_id)));

            unset($this->data['Sheet']['department_id']);

            foreach ($this->data['Sheet']['departmentmonth']['month'] as $months) {

                $this->data['Sheet']['month'] = $months;
                $this->data['Sheet']['year'] = $this->data['Sheet']['departmentmonth']['year'];
                $this->data['Sheet']['import_excel'] = $this->data['Sheet']['import_excel'];
                $this->data['Sheet']['import_txt'] = $this->data['Sheet']['import_txt'];

                $this->data['Sheet']['user_id'] = $save_userId;
                $this->data['Sheet']['department_id'] = $this->data['department_name'][0];

                $this->Sheet->create();

                $days_in_sheet_month = cal_days_in_month(CAL_GREGORIAN, $months, $this->data['Sheet']['departmentmonth']['year']);

                if ($this->Sheet->saveAll($this->data)) {

                    $curr_sheet_id = $this->Sheet->getLastInsertID();

                    /* save all rows_sheets data */
                    if (!empty($rows_data)) {
                        foreach ($rows_data as $rows) {
                            unset($rows['RowsSheet']['id']);
                            unset($rows['RowsSheet']['sheet_id']);
                            $rows['RowsSheet']['sheet_id'] = $curr_sheet_id;
                            $rows_obj->create();
                            $rows_obj->saveAll($rows);
                        }
                    }

                    /* save all columns_sheets data */
                    if (!empty($cols_data)) {
                        foreach ($cols_data as $cols) {
                            unset($cols['ColumnsSheet']['id']);
                            unset($cols['ColumnsSheet']['sheet_id']);
                            $cols['ColumnsSheet']['sheet_id'] = $curr_sheet_id;
                            $cols_obj->create();
                            $cols_obj->saveAll($cols);
                        }
                    }

                    /* save all data as datum data */
                    if (!empty($rows_data)) {
                        foreach ($datas_data as $datas) {
                            unset($datas['Datum']['id']);
                            unset($datas['Datum']['sheet_id']);
                            $datas['Datum']['sheet_id'] = $curr_sheet_id;
                            $datas['Datum']['value'] = '0';
                            $datas_obj->create();
                            $datas_obj->saveAll($datas['Datum']);
                        }
                    }

                    /* save all data as datum data */
                    if (!empty($formula_data)) {
                        foreach ($formula_data as $formula) {
                            unset($formula['Formula']['id']);
                            unset($formula['Formula']['sheet_id']);
                            $formula['Formula']['sheet_id'] = $curr_sheet_id;

                            //check the month and update the days as per month in the required formula added on 25 Jan'2016
                            $replace_array = array('* 31 )', '* 30 )', '( 31 *', '( 30 *');
                            $replace1 = '* ' . $days_in_sheet_month . ' )';
                            $replace2 = '* ' . $days_in_sheet_month . ' )';
                            $replace3 = '( ' . $days_in_sheet_month . ' *';
                            $replace4 = '( ' . $days_in_sheet_month . ' *';
                            $new_array = array($replace1, $replace2, $replace3, $replace4);
                            $updated_formula = str_replace($replace_array, $new_array, $formula['Formula']['formula']);
                            $formula['Formula']['formula'] = $updated_formula;

                            $formulas_obj->create();
                            $formulas_obj->saveAll($formula['Formula']);
                        }
                    }
                }
            } //foreach ends here
            $this->Session->setFlash(__('The Department sheet has been saved', true));
        } //ends not empty condition
        $columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2)));
        $rows = $this->Sheet->Row->find('list', array('conditions' => array('Row.status !=' => 2)));

        $depts_obj = ClassRegistry::init('DepartmentsUser');
        $this->Department = ClassRegistry::init('Department');

        $clientFrmDetpId = $this->Department->find('first', array('fields' => array('client_id'), 'conditions' => array('Department.id' => $this->params['pass'][2])));
        $clientId = $clientFrmDetpId['Department']['client_id'];

        $this->Client = ClassRegistry::init('Client');
        $all_hotels = $this->Client->find('list', array('conditions' => array('Client.status !=' => 2), 'fields' => array('id', 'hotelname')));

        $department = $depts_obj->field('department_name', array('DepartmentsUser.user_id' => $userId, 'DepartmentsUser.department_id' => $this->params['pass'][2]));
        $this->set(compact('userId', 'department', 'columns', 'rows', 'clientId', 'all_hotels'));
    }

//end admin_copysheet()

    function admin_edit($id = null, $dept_id = null) {
        $this->Sheet->resetBindings('Sheet');
        if ($id == null || !$this->Sheet->hasAny(array('Sheet.id'))) {
            $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'index'));
        }

        $userId = $this->Sheet->field('user_id', array('Sheet.id' => $id));
        if (!empty($this->data)) {
            if (isset($this->data['Sheet']['is_email'])) {
                $this->Sheet->EmailSheet->deleteAll(array('EmailSheet.sheet_id' => $id));
                $this->Sheet->id = $id;
                if ($this->Sheet->saveField('is_email', $this->data['Sheet']['is_email'])) {
                    if ($this->data['Sheet']['is_email'] == 0) {
                        unset($this->data['EmailSheet']);
                    }
                    if (isset($this->data['EmailSheet']) && !empty($this->data['EmailSheet'])) {
                        foreach ($this->data['EmailSheet'] as $key => $EmailSheet) {
                            $this->data['EmailSheet'][$key]['sheet_id'] = $id;
                        }
                        $this->Sheet->EmailSheet->saveAll($this->data['EmailSheet']);
                    }

                    $this->Session->setFlash(__('The Department sheet was updated successfully', true));
                    $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'index', $userId, $this->data['Sheet']['department_id']));
                } else {
                    $this->Session->setFlash(__('Department Sheet was not updated. Please, try again.', true));
                }
            }

            $selected = array();
            foreach ($this->data['Column']['Column'] as $key => $value) {
                if ($value != 0) {
                    array_push($selected, $value);
                }
            }
            $row_selected = array();
            foreach ($this->data['Row']['Row'] as $ky => $vale) {
                if ($vale != 0) {
                    array_push($row_selected, $vale);
                }
            }
            $this->data['Column']['Column'] = $selected;
            $this->data['Row']['Row'] = $row_selected;
            $this->data['Sheet']['month'] = $this->data['Sheet']['departmentmonth']['month'];
            $this->data['Sheet']['year'] = $this->data['Sheet']['departmentmonth']['year'];
            $this->data['Sheet']['user_id'] = $userId;

            $total_cols = $this->Sheet->ColumnsSheet->find('all', array('conditions' => array('ColumnsSheet.sheet_id' => $id)));
            $total_rows = $this->Sheet->RowsSheet->find('all', array('conditions' => array('RowsSheet.sheet_id' => $id)));
            $data_cols = $this->data['Column']['Column'];
            $data_rows = $this->data['Row']['Row'];

            $prev_cols = array();
            $prev_rows = array();

            foreach ($total_cols as $p_cols) {
                $prev_cols[] = $p_cols['ColumnsSheet']['column_id'];
            }

            foreach ($total_rows as $p_rows) {
                $prev_rows[] = $p_rows['RowsSheet']['row_id'];
            }

            $dif_prev_cur = array_diff($prev_cols, $data_cols);
            $dif_prev_cur_rows = array_diff($prev_rows, $data_rows);

            if ($this->Sheet->save($this->data)) {
                foreach ($this->data['Column']['Locked'] as $key => $val) {
                    if ($val > 0) {
                        $cond = "sheet_id = {$id} AND column_id = {$val}";
                        $arr = $this->Sheet->ColumnsSheet->find('first', array("conditions" => $cond));
                        $arr['ColumnsSheet']['locked'] = 1;
                        $this->Sheet->ColumnsSheet->save($arr);
                    }
                }

                foreach ($this->data['Column']['Is_decimal'] as $key => $val) {
                    if ($val > 0) {
                        $cond = "sheet_id = {$id} AND column_id = {$val}";
                        $arr = $this->Sheet->ColumnsSheet->find('first', array("conditions" => $cond));
                        $arr['ColumnsSheet']['is_decimal'] = 1;
                        $this->Sheet->ColumnsSheet->save($arr);
                    }
                }

                for ($i = 0; $i < count($this->data['Column']['Column']); $i++) {
                    foreach ($total_cols as $cols) {
                        if ($cols['ColumnsSheet']['column_id'] == $this->data['Column']['Column'][$i]) {
                            $this->Sheet->ColumnsSheet->updateAll(array('ColumnsSheet.order' => $cols['ColumnsSheet']['order']), array('ColumnsSheet.column_id' => $this->data['Column']['Column'][$i], 'ColumnsSheet.sheet_id' => $this->data['Sheet']['id']));
                        }
                    }
                }

                foreach ($this->data['Row']['Locked'] as $ky => $vl) {
                    if ($vl > 0) {
                        $cond = "sheet_id = {$id} AND row_id = {$vl}";
                        $arr = $this->Sheet->RowsSheet->find('first', array("conditions" => $cond));
                        $arr['RowsSheet']['locked'] = 1;
                        $this->Sheet->RowsSheet->save($arr);
                    }
                }

                /* delete formulae associated with cols */

                if (!empty($dif_prev_cur)) {
                    $all_formulas = $this->Sheet->Formula->find('all', array('conditions' => array('Formula.sheet_id' => $id)));
                    $formula_to_del = array();
                    foreach ($all_formulas as $formula) {
                        $formula_disect_stage1 = explode(" ", $formula['Formula']['formula']);
                        foreach ($formula_disect_stage1 as $sub_cols_rows) {
                            if (substr($sub_cols_rows, 0, 1) == "C") {
                                $temp_col = explode("C", $sub_cols_rows);
                                foreach ($dif_prev_cur as $col_id) {
                                    if ($col_id == $temp_col[1]) {
                                        $formula_to_del[] = $formula['Formula']['id'];
                                    }
                                }
                            }
                        }
                    }

                    $final_del_ids = array_unique($formula_to_del);
                    if (!empty($final_del_ids)) {
                        foreach ($final_del_ids as $f_id) {
                            $this->Sheet->Formula->delete($f_id);
                        }
                    }
                }
                /* till here ..... delete formulae associated with cols */


                /* delete formulae associated with rows */

                if (!empty($dif_prev_cur_rows)) {
                    $all_formulas = $this->Sheet->Formula->find('all', array('conditions' => array('Formula.sheet_id' => $id)));
                    $formula_to_del_rows = array();
                    foreach ($all_formulas as $formula) {
                        $formula_disect_stage2 = explode(" ", $formula['Formula']['formula']);
                        foreach ($formula_disect_stage2 as $sub_cols_rows) {
                            if (substr($sub_cols_rows, 0, 1) == "R") {
                                $temp_col = explode("R", $sub_cols_rows);
                                foreach ($dif_prev_cur_rows as $col_id) {
                                    if ($col_id == $temp_col[1]) {
                                        $formula_to_del_rows[] = $formula['Formula']['id'];
                                    }
                                }
                            }
                        }
                    }

                    $formula_to_del_rows = array_unique($formula_to_del_rows);
                    if (!empty($formula_to_del_rows)) {
                        foreach ($formula_to_del_rows as $f_id) {
                            $this->Sheet->Formula->delete($f_id);
                        }
                    }
                }
                /* till here ....... delete formulae associated with rows */

                /* till here to delete formula */

                $this->Session->setFlash(__('The Department sheet was updated successfully', true));
                $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'index', $userId, $this->data['Sheet']['department_id']));
            } else {
                $this->Session->setFlash(__('Department Sheet was not updated. Please, try again.', true));
            }
        }

        $columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2)));
        $rows = $this->Sheet->Row->find('list', array('conditions' => array('Row.status !=' => 2)));
        $this->Sheet->contain(array('Column' => array('fields' => array('id')), 'Row' => array('fields' => array('id')), 'EmailSheet'));
        $this->data = $this->Sheet->find('first', array('conditions' => array('Sheet.id' => $id)));
        $total_columns = $this->Sheet->ColumnsSheet->find('all', array('conditions' => array('ColumnsSheet.sheet_id' => $id), 'order' => array('ColumnsSheet.order ASC')));
        $selected_columns = Set::extract('/ColumnsSheet/column_id', $total_columns);
        $selected_rows = Set::extract('/id', $this->data['Row']);
        $department_obj = ClassRegistry::init('DepartmentsUser');
        $department = $department_obj->field('department_name', array('user_id' => $userId, 'department_id' => $dept_id));
        $locked = Set::extract('/ColumnsSheet/locked', $this->data['Column']);
        $is_decimal = Set::extract('/ColumnsSheet/is_decimal', $this->data['Column']);
        $col_id = Set::extract('/ColumnsSheet/column_id', $this->data['Column']);
        $rowlocked = Set::extract('/RowsSheet/locked', $this->data['Row']);
        $row_id = Set::extract('/RowsSheet/row_id', $this->data['Row']);
        $this->set(compact('id', 'total_columns', 'columns', 'rows', 'department', 'selected_columns', 'selected_rows', 'locked', 'rowlocked', 'col_id', 'row_id', 'userId', 'is_decimal'));
    }

//end admin_edit()

    /**
     * Action for admin to delete a sheet
     *
     * @param integer $sheetId Id of the sheet to be deleted
     * @access public
     * @return void
     */
    function admin_delete($sheetId = null, $dept_id = null) {
        if (!$sheetId) {
            $this->Session->setFlash(__('Invalid sheet id', true));
            $this->redirect(array('action' => 'index'));
        }

        // Find the sheet user id
        $userId = $this->Sheet->field('user_id', array('Sheet.id' => $sheetId));

        // Logically delete the sheet and redirect to the user sheet listing page
        if ($this->Sheet->softDelete($sheetId)) {
            $this->Session->setFlash(__('Sheet deleted successfully', true));
            $this->redirect(array('action' => 'index', $userId, $dept_id));
        }

        $this->Session->setFlash(__('Sheet was not deleted, please try again.', true));
        $this->redirect(array('action' => 'index'));
    }

//end admin_delete()

    /**
     * Action for admin to view the Department sheet
     * @param int $sheetId The Department sheet ID to be viewed
     * @access public
     * @return void
     */
    function admin_webform($sheetId) {
        //Configure::write('debug', 2);

        $formula_obj = ClassRegistry::init('Formula');
        $formula_data = $formula_obj->findBySheetId($sheetId);
        // Find the sheet user id
        $userId = $this->Sheet->field('user_id', array('Sheet.id' => $sheetId));
        $dept_id = $this->Sheet->field('department_id', array('Sheet.id' => $sheetId));

        if (empty($formula_data)) {
            $this->Session->setFlash(__('Please Create Formula for this Webform', true));
            $this->redirect(array('action' => 'index', $userId, $dept_id));
        }

        $this->Department = ClassRegistry::init('Department');
        $this->Client = ClassRegistry::init('Client');
        $clientFrmDetpId = $this->Department->find('first', array('fields' => array('client_id'), 'conditions' => array('Department.id' => $dept_id)));
        $clientId = $clientFrmDetpId['Department']['client_id'];

        $this->set('is_habtoor', '0');

        $this->set('clientId', $clientId);

        // Set the ext-js layout for this action
        $this->layout = 'ext';
        // Set the debug mode to zero
        $sheet = $this->Sheet->read(null, $sheetId);

        $datas_obj = ClassRegistry::init('Datum');
        $datas_data = $datas_obj->find('all', array('conditions' => array('sheet_id' => $sheetId), 'fields' => array('MAX(Datum.modified) AS last_date')));
        $last_refresh_time = $datas_data[0][0]['last_date'];
        $this->set('last_refresh_time', $last_refresh_time);

        $columns = $sheet['Column'];

        $total_columns = $this->Sheet->ColumnsSheet->find('all', array('conditions' => array('ColumnsSheet.sheet_id' => $sheetId), 'order' => array('ColumnsSheet.order ASC')));
        $selected_columns = Set::extract('/ColumnsSheet/column_id', $total_columns);
        $new_arr = array();
        foreach ($selected_columns as $scols) {
            foreach ($columns as $key => $value) {
                if ($scols == $value['id']) {
                    $new_arr[] = $value;
                    unset($columns[$key]);
                }
            }
        }

        unset($columns);
        $columns = $new_arr;
        $data = array();
        $this->set(compact('sheet', 'columns', 'data', 'sheetId'));
    }

//end admin_webform()

    /**
     * Action for admin to import the Department sheet
     *
     * @param int $sheetId The Department sheet ID to be viewed
     * @access public
     * @return void
     */
    function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    function admin_import_txt($sheetId) {
        //Configure::write('debug',2);
        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/text/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_txt/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $contents = file_get_contents($this->data['Sheet']['browse_file']["tmp_name"]);
                $explode = explode("\n", $contents);

                foreach ($explode as $value) {
                    $explode_row[] = explode("\t", $value);
                }
                $pdata = $explode_row[0];
                $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                $date_col = array_search('CHAR_CONSIDERED_DATE', $pdata);
                unset($explode_row[0]);

                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($date_col)) {
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    foreach ($explode_row as $data) {

                        if (!empty($data[$date_col])) {
                            $date_details = explode(' ', $data[$date_col]);
                            $date_details = $date_details[0];
                            $date_details = str_replace("-", "/", $date_details);
                        } else {
                            $date_details = '';
                        }

                        if ((!empty($data[$cols['BOB']]) && isset($data[$cols['BOB']])) && (!empty($data[$cols['ADR']]) && isset($data[$cols['ADR']])) && !empty($date_details)) {

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = str_replace(',', '', $data[$cols['ADR']]);

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if ($sheets_d['Date'] == $date_details) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'admin_import_txt', $sheetId));
                            }
                        }
                    }

                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('Text file imported successfully.', true));
                    $this->redirect(array('action' => 'admin_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function admin_import_operaexcel($sheetId) {

        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/text/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_operaexcel/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $contents = file_get_contents($this->data['Sheet']['browse_file']["tmp_name"]);
                $explode = explode("\n", $contents);

                foreach ($explode as $value) {
                    $explode_row[] = explode("\t", $value);
                }

                $pdata = $explode_row[0];
                $cols['BOB'] = array_search('NO_ROOMS', $pdata); //25
                $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata); //49
                $cols['Revenue'] = array_search('REVENUE', $pdata); //24
                $date_col = array_search('CHAR_CONSIDERED_DATE', $pdata);
                $cols['BOB-subs'] = array_search('COMPLIMENTARY_ROOMS', $pdata);
                unset($explode_row[0]);

                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($date_col)) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    $department_id = $sdata['Sheet']['department_id'];
                    $cellars_dept = array('281');

                    foreach ($explode_row as $data) {
                        if (!empty($data[$date_col])) {
                            $date_details = explode(' ', $data[$date_col]);
                            $date_details = $date_details[0];
                        } else {
                            $date_details = '';
                        }
                        if (preg_match("/^([0-9]{2})-([0-9]{2})-([0-9]{2})$/", $date_details)) {
                            $date_details = str_replace("-", "/", $date_details);
                        }
                        if ((isset($data[$cols['BOB']])) && isset($data[$cols['ADR']]) && !empty($date_details)) {

                            $date_details = str_replace('.', '/', $date_details);
                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']] - $data[$cols['BOB-subs']];
                            $new_data[$row]['ADR'] = str_replace(',', '', $data[$cols['ADR']]);
                            $new_data[$row]['Revenue'] = str_replace(',', '', $data[$cols['Revenue']]);

                            foreach ($columns as $ckey => $col) {
                                if (in_array($department_id, $cellars_dept)) {
                                    if ($col != 'BOB' && $col != 'ADR' && $col != 'Revenue') {
                                        foreach ($sheet_data as $sheets_d) {
                                            if (($sheets_d['Date']) == ($date_details)) {
                                                $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    if ($col != 'BOB' && $col != 'ADR') {
                                        foreach ($sheet_data as $sheets_d) {
                                            if (($sheets_d['Date']) == ($date_details)) {
                                                $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                                break;
                                            }
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                                set_time_limit(40);
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'admin_import_operaexcel', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('File imported successfully!!', true));
                    $this->redirect(array('action' => 'admin_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }

        $this->set(get_defined_vars());
    }

    function client_import_operaexcel($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/text/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_operaexcel/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $contents = file_get_contents($this->data['Sheet']['browse_file']["tmp_name"]);
                $explode = explode("\n", $contents);

                foreach ($explode as $value) {
                    $explode_row[] = explode("\t", $value);
                }
                $pdata = $explode_row[0];
                $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                $cols['Revenue'] = array_search('REVENUE', $pdata); //24
                $date_col = array_search('CHAR_CONSIDERED_DATE', $pdata);
                $cols['BOB-subs'] = array_search('COMPLIMENTARY_ROOMS', $pdata);
                unset($explode_row[0]);

                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($date_col)) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    $department_id = $sdata['Sheet']['department_id'];
                    $cellars_dept = array('281');

                    foreach ($explode_row as $data) {
                        if (!empty($data[$date_col])) {
                            $date_details = explode(' ', $data[$date_col]);
                            $date_details = $date_details[0];
                        } else {
                            $date_details = '';
                        }
                        if (preg_match("/^([0-9]{2})-([0-9]{2})-([0-9]{2})$/", $date_details)) {
                            $date_details = str_replace("-", "/", $date_details);
                        }

                        if ((isset($data[$cols['BOB']])) && isset($data[$cols['ADR']]) && !empty($date_details)) {
                            $date_details = str_replace('.', '/', $date_details);
                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']] - $data[$cols['BOB-subs']];
                            $new_data[$row]['ADR'] = str_replace(',', '', $data[$cols['ADR']]);

                            $new_data[$row]['Revenue'] = str_replace(',', '', $data[$cols['Revenue']]);

                            foreach ($columns as $ckey => $col) {
                                if (in_array($department_id, $cellars_dept)) {
                                    if ($col != 'BOB' && $col != 'ADR' && $col != 'Revenue') {
                                        foreach ($sheet_data as $sheets_d) {
                                            if (($sheets_d['Date']) == ($date_details)) {
                                                $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    if ($col != 'BOB' && $col != 'ADR') {
                                        foreach ($sheet_data as $sheets_d) {
                                            if (($sheets_d['Date']) == ($date_details)) {
                                                $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                                break;
                                            }
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                                set_time_limit(40);
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'client_import_operaexcel', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('File imported successfully!!', true));
                    $this->redirect(array('action' => 'client_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }

        $this->set(get_defined_vars());
    }

    function staff_import_operaexcel($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/text/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_operaexcel/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $contents = file_get_contents($this->data['Sheet']['browse_file']["tmp_name"]);
                $explode = explode("\n", $contents);

                foreach ($explode as $value) {
                    $explode_row[] = explode("\t", $value);
                }
                $pdata = $explode_row[0];
                $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                $cols['Revenue'] = array_search('REVENUE', $pdata); //24
                $date_col = array_search('CHAR_CONSIDERED_DATE', $pdata);
                $cols['BOB-subs'] = array_search('COMPLIMENTARY_ROOMS', $pdata);
                unset($explode_row[0]);

                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($date_col)) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    $department_id = $sdata['Sheet']['department_id'];
                    $cellars_dept = array('281');

                    foreach ($explode_row as $data) {
                        if (!empty($data[$date_col])) {
                            $date_details = explode(' ', $data[$date_col]);
                            $date_details = $date_details[0];
                        } else {
                            $date_details = '';
                        }
                        if (preg_match("/^([0-9]{2})-([0-9]{2})-([0-9]{2})$/", $date_details)) {
                            $date_details = str_replace("-", "/", $date_details);
                        }
                        if ((isset($data[$cols['BOB']])) && isset($data[$cols['ADR']]) && !empty($date_details)) {
                            $date_details = str_replace('.', '/', $date_details);
                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']] - $data[$cols['BOB-subs']];
                            $new_data[$row]['ADR'] = str_replace(',', '', $data[$cols['ADR']]);

                            $new_data[$row]['Revenue'] = str_replace(',', '', $data[$cols['Revenue']]);

                            foreach ($columns as $ckey => $col) {
                                if (in_array($department_id, $cellars_dept)) {
                                    if ($col != 'BOB' && $col != 'ADR' && $col != 'Revenue') {
                                        foreach ($sheet_data as $sheets_d) {
                                            if (($sheets_d['Date']) == ($date_details)) {
                                                $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    if ($col != 'BOB' && $col != 'ADR') {
                                        foreach ($sheet_data as $sheets_d) {
                                            if (($sheets_d['Date']) == ($date_details)) {
                                                $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                                break;
                                            }
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                                set_time_limit(40);
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'staff_import_operaexcel', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('File imported successfully!!', true));
                    $this->redirect(array('action' => 'staff_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }

        $this->set(get_defined_vars());
    }

    function client_import_txt($sheetId) {

        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/text/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_txt/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $contents = file_get_contents($this->data['Sheet']['browse_file']["tmp_name"]);
                $explode = explode("\n", $contents);

                foreach ($explode as $value) {
                    $explode_row[] = explode("\t", $value);
                }
                $pdata = $explode_row[0];
                $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                $date_col = array_search('CHAR_CONSIDERED_DATE', $pdata);
                unset($explode_row[0]);

                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($date_col)) {
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    foreach ($explode_row as $data) {

                        if (!empty($data[$date_col])) {
                            $date_details = explode(' ', $data[$date_col]);
                            $date_details = $date_details[0];
                            $date_details = str_replace("-", "/", $date_details);
                        } else {
                            $date_details = '';
                        }

                        if ((!empty($data[$cols['BOB']]) && isset($data[$cols['BOB']])) && (!empty($data[$cols['ADR']]) && isset($data[$cols['ADR']])) && !empty($date_details)) {

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = str_replace(',', '', $data[$cols['ADR']]);

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if ($sheets_d['Date'] == $date_details) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'client_import_txt', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('Text file imported successfully.', true));
                    $this->redirect(array('action' => 'client_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function staff_import_txt($sheetId) {

        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/text/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_txt/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $contents = file_get_contents($this->data['Sheet']['browse_file']["tmp_name"]);
                $explode = explode("\n", $contents);

                foreach ($explode as $value) {
                    $explode_row[] = explode("\t", $value);
                }
                $pdata = $explode_row[0];
                $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                $date_col = array_search('CHAR_CONSIDERED_DATE', $pdata);
                unset($explode_row[0]);

                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($date_col)) {
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    foreach ($explode_row as $data) {

                        if (!empty($data[$date_col])) {
                            $date_details = explode(' ', $data[$date_col]);
                            $date_details = $date_details[0];
                            $date_details = str_replace("-", "/", $date_details);
                        } else {
                            $date_details = '';
                        }

                        if ((!empty($data[$cols['BOB']]) && isset($data[$cols['BOB']])) && (!empty($data[$cols['ADR']]) && isset($data[$cols['ADR']])) && !empty($date_details)) {

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = str_replace(',', '', $data[$cols['ADR']]);

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if ($sheets_d['Date'] == $date_details) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'staff_import_txt', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('Text file imported successfully.', true));
                    $this->redirect(array('action' => 'staff_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function admin_import_excel($sheetId) {
        $this->layout = 'default';
        $sheet_type = 1;
        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_excel/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;


                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $department_id = $sdata['Sheet']['department_id'];

                $check_numcols = '21';

                if ($ndata[0]['numCols'] == $check_numcols) {

                    if (isset($ndata[0]['cells'][4][1]) && ($ndata[0]['cells'][4][1] == 'All Package-Codes')) {
                        $pdata = $ndata[0]['cells'][7];
                        unset($ndata[0]['cells'][1], $ndata[0]['cells'][2], $ndata[0]['cells'][3], $ndata[0]['cells'][4], $ndata[0]['cells'][6], $ndata[0]['cells'][7]);
                    } else {
                        $pdata = $ndata[0]['cells'][6];
                        unset($ndata[0]['cells'][1], $ndata[0]['cells'][2], $ndata[0]['cells'][3], $ndata[0]['cells'][4], $ndata[0]['cells'][6]);
                    }

                    $cols['BOB'] = array_search('Res
Ro.', $pdata);
                    $cols['ADR'] = array_search('Accom./Ro.', $pdata);
                    $date_col = array_search('Date', $pdata);
                    $sheet_type = 2;
                } else {
                    $pdata = $ndata[0]['cells'][1];
                    $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                    $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                    $date_col = array_search('CHAR_CONSIDERED_DATE', $pdata);
                    unset($ndata[0]['cells'][1]);
                }

                if (!empty($date_col)) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    foreach ($ndata[0]['cells'] as $data) {
                        if (!empty($data[$date_col])) {

                            if ($sheet_type == 2) {
                                $date_details = explode('/', $data[$date_col]);
                                $date_details = $date_details[1] . '/' . $date_details[0] . '/' . $date_details[2];
                            } else {
                                $date_details = explode(' ', $data[$date_col]);
                                $date_details = $date_details[0];
                            }

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']];

                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (strtotime($sheets_d['Date']) == strtotime($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'admin_import_excel', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    $this->Session->setFlash(__('Excel imported successfully.', true));
                    fclose($handle);
                    $this->redirect(array('action' => 'admin_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function admin_import_protel($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_protel/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $sheet_data = $this->Sheet->find('first', array('conditions' => array('Sheet.id' => $sheetId), 'recursive' => '0'));
                $sheet_data['Sheet']['month'] = sprintf('%02d', $sheet_data['Sheet']['month']);

                $s_month = $sheet_data['Sheet']['month'];
                $s_year = $sheet_data['Sheet']['year'];
                $check_date = $s_month . '/' . $s_year;
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;
                $count_data = '1';
                foreach ($ndata[0]['cells'] as $chk_data) {
                    if (!empty($chk_data[1])) {
                        if ($check_date == trim($chk_data[1])) {
                            $unset_till = $count_data;
                            break;
                        }
                    }
                    $count_data++;
                }

                if ($unset_till == '') {
                    $this->Session->setFlash(__('Month Data is not available!', true));
                    $this->redirect(array('action' => 'admin_import_protel', $sheetId));
                }

                $pdata = $ndata[0]['cells'][8];
                $cols['BOB'] = array_search('Total F Room Nights', $pdata);
                $cols['ADR'] = array_search('Total StN F RevPOR Excomp', $pdata);
                $date_col = array_search('Daily', $pdata);
                $sheet_type = 2;

                for ($i = 0; $i <= $count_data; $i++) {
                    unset($ndata[0]['cells'][$i]);
                }

                if (!empty($date_col)) {
                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    foreach ($ndata[0]['cells'] as $data) {
                        $num = count($data);
                        if (!empty($data[$date_col])) {
                            $date_details = explode('-', $data[$date_col]);
                            $month = $date_details[1];
                            $year = date('y', strtotime($date_details[2]));
                            $date_details = $i . '/' . $month . '/' . $year;
                        } else {
                            $date_details = '';
                        }

                        if (!empty($date_details)) {

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[27] == '' ? '0' : $data[27];
                            $new_data[$row]['ADR'] = $data[29] == '' ? '0' : $data[29];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if ($sheets_d['Date'] == $date_details) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'admin_import_protel', $sheetId));
                            }
                        } else {
                            break;
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('Protel imported successfully.', true));
                    $this->redirect(array('action' => 'admin_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function client_import_protel($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_protel/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $sheet_data = $this->Sheet->find('first', array('conditions' => array('Sheet.id' => $sheetId), 'recursive' => '0'));
                $sheet_data['Sheet']['month'] = sprintf('%02d', $sheet_data['Sheet']['month']);

                $s_month = $sheet_data['Sheet']['month'];
                $s_year = $sheet_data['Sheet']['year'];
                $check_date = $s_month . '/' . $s_year;
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;
                $count_data = '1';
                foreach ($ndata[0]['cells'] as $chk_data) {
                    if (!empty($chk_data[1])) {
                        if ($check_date == trim($chk_data[1])) {
                            $unset_till = $count_data;
                            break;
                        }
                    }
                    $count_data++;
                }

                if ($unset_till == '') {
                    $this->Session->setFlash(__('Month Data is not available!', true));
                    $this->redirect(array('action' => 'client_import_protel', $sheetId));
                }

                $pdata = $ndata[0]['cells'][8];
                $cols['BOB'] = array_search('Total F Room Nights', $pdata);
                $cols['ADR'] = array_search('Total StN F RevPOR Excomp', $pdata);
                $date_col = array_search('Daily', $pdata);
                $sheet_type = 2;

                for ($i = 0; $i <= $count_data; $i++) {
                    unset($ndata[0]['cells'][$i]);
                }

                if (!empty($date_col)) {
                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    foreach ($ndata[0]['cells'] as $data) {
                        $num = count($data);
                        //BOB - AA(27), ADR - AC(29)
                        if (!empty($data[$date_col])) {
                            $date_details = explode('-', $data[$date_col]);
                            $month = $date_details[1];
                            $year = date('y', strtotime($date_details[2]));
                            $date_details = $i . '/' . $month . '/' . $year;
                        } else {
                            $date_details = '';
                        }

                        if (!empty($date_details)) {

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[27] == '' ? '0' : $data[27];
                            $new_data[$row]['ADR'] = $data[29] == '' ? '0' : $data[29];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if ($sheets_d['Date'] == $date_details) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'client_import_protel', $sheetId));
                            }
                        } else {
                            break;
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('Protel imported successfully.', true));
                    $this->redirect(array('action' => 'client_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

//Client Protel Import function ends here

    function get_column_data($column_id, $sheet_id) {
        
    }

    function staff_import_protel($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_protel/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $sheet_data = $this->Sheet->find('first', array('conditions' => array('Sheet.id' => $sheetId), 'recursive' => '0'));
                $sheet_data['Sheet']['month'] = sprintf('%02d', $sheet_data['Sheet']['month']);

                $s_month = $sheet_data['Sheet']['month'];
                $s_year = $sheet_data['Sheet']['year'];
                $check_date = $s_month . '/' . $s_year;
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;
                $count_data = '1';
                foreach ($ndata[0]['cells'] as $chk_data) {
                    if (!empty($chk_data[1])) {
                        if ($check_date == trim($chk_data[1])) {
                            $unset_till = $count_data;
                            break;
                        }
                    }
                    $count_data++;
                }

                if ($unset_till == '') {
                    $this->Session->setFlash(__('Month Data is not available!', true));
                    $this->redirect(array('action' => 'staff_import_protel', $sheetId));
                }

                $pdata = $ndata[0]['cells'][8];
                $cols['BOB'] = array_search('Total F Room Nights', $pdata);
                $cols['ADR'] = array_search('Total StN F RevPOR Excomp', $pdata);
                $date_col = array_search('Daily', $pdata);
                $sheet_type = 2;

                for ($i = 0; $i <= $count_data; $i++) {
                    unset($ndata[0]['cells'][$i]);
                }

                if (!empty($date_col)) {
                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    foreach ($ndata[0]['cells'] as $data) {
                        $num = count($data);
                        //BOB - AA(27), ADR - AC(29)
                        if (!empty($data[$date_col])) {
                            $date_details = explode('-', $data[$date_col]);
                            $month = $date_details[1];
                            $year = date('y', strtotime($date_details[2]));
                            $date_details = $i . '/' . $month . '/' . $year;
                        } else {
                            $date_details = '';
                        }

                        if (!empty($date_details)) {

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[27] == '' ? '0' : $data[27];
                            $new_data[$row]['ADR'] = $data[29] == '' ? '0' : $data[29];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if ($sheets_d['Date'] == $date_details) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'staff_import_protel', $sheetId));
                            }
                        } else {
                            break;
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('Protel imported successfully.', true));
                    $this->redirect(array('action' => 'staff_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function admin_import_csv($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_csv/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");
                $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                $date_col = array_search('CONSIDERED_DATE', $pdata);

                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($date_col)) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $date_details = $data[$date_col];

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = $data[$cols['ADR']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (strtotime($sheets_d['Date']) == strtotime($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }//end foreach

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'admin_import_csv', $sheetId));
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'admin_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    /* function to download csv */

    function admin_export_csv($sheetId = null, $type = null) {
        if (!empty($sheetId)) {
            if (empty($type)) {
                $type = "csv";
            }
            if (!empty($sheetId)) {
                $data = $this->Sheet->getData($sheetId);
                $sheet = $this->Sheet->read(null, $sheetId);
                $columns = $sheet['Column'];

                $total_columns = $this->Sheet->ColumnsSheet->find('all', array('conditions' => array('ColumnsSheet.sheet_id' => $sheetId), 'order' => array('ColumnsSheet.order ASC')));
                $selected_columns = Set::extract('/ColumnsSheet/column_id', $total_columns);
                $new_arr = array();
                foreach ($selected_columns as $scols) {
                    foreach ($columns as $key => $value) {
                        if ($scols == $value['id']) {
                            $new_arr[] = $value;
                            unset($columns[$key]);
                        }
                    }
                }
                unset($columns);
                $columns = $new_arr;
            }
            $only_column_names = Set::extract('/name', $columns);
            $final_columns = array();
            $final_columns[0] = "Date";
            foreach ($only_column_names as $col_name) {
                $final_columns[] = $col_name;
            }
            $rest_values = array();
            $rest_values[0] = $final_columns;

            for ($i = 0; $i < count($data); $i++) {
                foreach ($data[$i] as $key => $values) {

                    if ($key != "sheetId") {
                        $position = array_search($key, $final_columns);
                        $position = $position + 1;
                        $rest_values[$i + 1][$position] = $values;
                    }
                }
                ksort($rest_values[$i + 1]);
            }
            $this->Export->download1($rest_values, $type);
        }
    }

//function export_csv ends..

    function client_import_excel($sheetId) {
        $this->layout = 'default';
        $sheet_type = 1;
        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_excel/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $department_id = $sdata['Sheet']['department_id'];

                $check_numcols = '21';

                if ($ndata[0]['numCols'] == $check_numcols) {

                    if (isset($ndata[0]['cells'][4][1]) && ($ndata[0]['cells'][4][1] == 'All Package-Codes')) {
                        $pdata = $ndata[0]['cells'][7];
                        unset($ndata[0]['cells'][1], $ndata[0]['cells'][2], $ndata[0]['cells'][3], $ndata[0]['cells'][4], $ndata[0]['cells'][6], $ndata[0]['cells'][7]);
                    } else {
                        $pdata = $ndata[0]['cells'][6];
                        unset($ndata[0]['cells'][1], $ndata[0]['cells'][2], $ndata[0]['cells'][3], $ndata[0]['cells'][4], $ndata[0]['cells'][6]);
                    }

                    $cols['BOB'] = array_search('Res
Ro.', $pdata);
                    $cols['ADR'] = array_search('Accom./Ro.', $pdata);
                    $date_col = array_search('Date', $pdata);
                    $sheet_type = 2;
                } else {
                    $pdata = $ndata[0]['cells'][1];
                    $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                    $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                    $date_col = array_search('CHAR_CONSIDERED_DATE', $pdata);
                    unset($ndata[0]['cells'][1]);
                }

                if (!empty($date_col)) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    foreach ($ndata[0]['cells'] as $data) {

                        if (!empty($data[$date_col])) {

                            if ($sheet_type == 2) {
                                $date_details = explode('/', $data[$date_col]);
                                $date_details = $date_details[1] . '/' . $date_details[0] . '/' . $date_details[2];
                            } else {
                                $date_details = explode(' ', $data[$date_col]);
                                $date_details = $date_details[0];
                            }

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']];

                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (strtotime($sheets_d['Date']) == strtotime($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'client_import_excel', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    $this->Session->setFlash(__('Excel imported successfully.', true));
                    fclose($handle);
                    $this->redirect(array('action' => 'client_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function client_import_csv($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_csv/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");
                $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                $date_col = array_search('CONSIDERED_DATE', $pdata);

                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($date_col)) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $date_details = $data[$date_col];

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = $data[$cols['ADR']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (strtotime($sheets_d['Date']) == strtotime($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }//end foreach

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'client_import_csv', $sheetId));
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'client_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function staff_import_excel($sheetId) {
        $this->layout = 'default';
        $sheet_type = 1;
        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_excel/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $department_id = $sdata['Sheet']['department_id'];

                $check_numcols = '21';

                if ($ndata[0]['numCols'] == $check_numcols) {

                    if (isset($ndata[0]['cells'][4][1]) && ($ndata[0]['cells'][4][1] == 'All Package-Codes')) {
                        $pdata = $ndata[0]['cells'][7];
                        unset($ndata[0]['cells'][1], $ndata[0]['cells'][2], $ndata[0]['cells'][3], $ndata[0]['cells'][4], $ndata[0]['cells'][6], $ndata[0]['cells'][7]);
                    } else {
                        $pdata = $ndata[0]['cells'][6];
                        unset($ndata[0]['cells'][1], $ndata[0]['cells'][2], $ndata[0]['cells'][3], $ndata[0]['cells'][4], $ndata[0]['cells'][6]);
                    }

                    $cols['BOB'] = array_search('Res
Ro.', $pdata);
                    $cols['ADR'] = array_search('Accom./Ro.', $pdata);
                    $date_col = array_search('Date', $pdata);
                    $sheet_type = 2;
                } else {
                    $pdata = $ndata[0]['cells'][1];
                    $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                    $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                    $date_col = array_search('CHAR_CONSIDERED_DATE', $pdata);
                    unset($ndata[0]['cells'][1]);
                }

                if (!empty($date_col)) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    foreach ($ndata[0]['cells'] as $data) {

                        if (!empty($data[$date_col])) {

                            if ($sheet_type == 2) {
                                $date_details = explode('/', $data[$date_col]);
                                $date_details = $date_details[1] . '/' . $date_details[0] . '/' . $date_details[2];
                            } else {
                                $date_details = explode(' ', $data[$date_col]);
                                $date_details = $date_details[0];
                            }

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']];

                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (strtotime($sheets_d['Date']) == strtotime($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'staff_import_excel', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    $this->Session->setFlash(__('Excel imported successfully.', true));
                    fclose($handle);
                    $this->redirect(array('action' => 'staff_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function staff_import_csv($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_csv/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");
                $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                $date_col = array_search('CONSIDERED_DATE', $pdata);

                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($date_col)) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $date_details = $data[$date_col];

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = $data[$cols['ADR']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (strtotime($sheets_d['Date']) == strtotime($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }//end foreach

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'staff_import_csv', $sheetId));
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'staff_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

//end staff_import_csv()

    function admin_export_pdf($sheetId = null, $type = null) {
        if (!empty($sheetId)) {
            $sheet_data = $this->Sheet->find('first', array('conditions' => array('Sheet.id' => $sheetId)));
            $this->set('sheet_data', $sheet_data);
            $user_id = $this->Sheet->field('Sheet.user_id', array('Sheet.id' => $sheetId));

            $user_obj = ClassRegistry::init('User');
            $user_data = $user_obj->findById($user_id);
            $clienImage = $user_data['Client']['logo'];
            $this->set(compact('clienImage', 'user_data'));
            $data = $this->Sheet->getData($sheetId);

            $headers = array();
            foreach ($data[0] as $key => $value) {
                if ($key != "sheetId") {
                    array_push($headers, $key);
                }
            }
            $rest_values = array();
            $rest_values[0] = $headers;
            for ($i = 0; $i < count($data); $i++) {
                foreach ($data[$i] as $key => $values) {
                    if ($key != "sheetId") {
                        $rest_values[$i + 1][] = $values;
                    }
                }
            }
            $this->set('rest_values', $rest_values);
        }
    }

//function export_pdf ends..

    function admin_pdfwebforms($sheet_id) {
        $this->layout = 'default';
        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/app/webroot/webforms/" . $sheet_id . "/";
        $this->set('file_path', $file_path);
    }

    function client_pdfwebforms($sheet_id) {
        $this->layout = 'default';
        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/app/webroot/webforms/" . $sheet_id . "/";
        $this->set('file_path', $file_path);
    }

    function staff_pdfwebforms($sheet_id) {
        $this->layout = 'default';
        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/app/webroot/webforms/" . $sheet_id . "/";
        $this->set('file_path', $file_path);
    }

    /*     * ******************************
      Added On : 4 March'2013
      Description : Created to save all webform PDF for users[Used for Cron]
     * ******************************** */

    function save_all_pdf() {

        Configure::write('debug', 0);
        $this->autoRender = false;

        $this->Admin = ClassRegistry::init('Admin');
        $adminData = $this->Admin->find('first', array('fields' => array('archive_hotel_ids'), 'conditions' => array('Admin.id' => '1')));
        $clientIds = explode(',', $adminData['Admin']['archive_hotel_ids']);

        $this->Department = ClassRegistry::init('Department');
        $department_arr = $this->Department->find('list', array('fields' => array('id', 'id'), 'conditions' => array('Department.client_id' => $clientIds, 'Department.status' => '1')));

        $sheetIds = array();
        for ($i = '0'; $i <= '2'; $i++) {
            $year = date('Y');
            $month = date('m');
            $month = $month + $i;
            if ($month > '12') {
                $month = $month - '12';
                $year = $year + '1';
            }
            $monthSheet = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' => $year, 'Sheet.month' => $month, 'Sheet.department_id' => $department_arr), 'fields' => array('Sheet.id'), 'recursive' => '0'));
            foreach ($monthSheet as $sheet_ids) {
                $sheetIds[] = $sheet_ids['Sheet']['id'];
            }
        }

        $conditions = array('Sheet.id' => $sheetIds);
        $all_sheets = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => 'id,user_id,name,department_id,month,year', 'recursive' => -1));

        foreach ($all_sheets as $sheet_data) {

            $sheetMonht = $sheet_data['Sheet']['month'];
            $sheetYear = $sheet_data['Sheet']['year'];

            $sheetId = $sheet_data['Sheet']['id'];
            $user_id = $sheet_data['Sheet']['user_id'];
            $sheet_name = $sheet_data['Sheet']['name'];
            $department_id = $sheet_data['Sheet']['department_id'];

            if (!empty($sheetId)) {
                $formula_obj = ClassRegistry::init('Formula');
                $formula_obj->recursive = -1;
                $formula_data = $formula_obj->findBySheetId($sheetId);

                if (!empty($formula_data)) {
                    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/app/webroot/webforms/" . $sheetId;
                    if (@file_exists($file_path)) {
                        @chmod($file_path, 0777);
                    } else {
                        @mkdir($file_path, '0777');
                        @chmod($file_path, 0777);
                    }

                    $user_obj = ClassRegistry::init('User');
                    unset($all_users1);
                    $all_users1 = explode(',', $user_id);
                    unset($all_users);
                    //Condition added on 05 July 2013 to get only one sheet per webform
                    $all_users[] = $all_users1[0];
                    $user_id = $all_users1[0];
                    $user_id = trim($user_id);
                    $user_data = $user_obj->findById($user_id);

                    if (!empty($user_data)) {
                        $clienImage = $user_data['Client']['logo'];
                        $data = $this->Sheet->getData($sheetId);
                        $headers = array();
                        foreach ($data[0] as $key => $value) {
                            if ($key != "sheetId") {
                                array_push($headers, $key);
                            }
                        }
                        $rest_values = array();
                        $rest_values[0] = $headers;
                        for ($i = 0; $i < count($data); $i++) {
                            foreach ($data[$i] as $key => $values) {
                                if ($key != "sheetId") {
                                    $rest_values[$i + 1][] = $values;
                                }
                            }
                        }
                        ob_start();
                        App::import('Vendor', 'tcpdf');
                        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8");
                        $dept_obj = ClassRegistry::init('Department');
                        $dept_name = $dept_obj->field('name', array('id' => $department_id));
                        $date = date('Y-m-d');

                        $htms = '';
                        $htms .= '<table border="">';
                        $htms .= '<tr><td>Department Sheet : ' . $sheet_name . '</td></tr>';
                        $htms .= '<tr><td>Department Name  : ' . $dept_name . '</td></tr>';
                        $htms .= '<tr><td>Staff User : ' . $user_data['User']['firstname'] . ' ' . $user_data['User']['lastname'] . '</td></tr>';
                        $htms .= '<tr><td>Downloaded Date : ' . "$date" . '</td></tr>';
                        $htms .= '</table>';
                        $pdf->SetCreator(PDF_CREATOR);
                        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                        $pdf->SetHeaderMargin(-1);
                        $pdf->SetFooterMargin(-2);
                        $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans'
                        $pdf->SetAuthor("Revenue Performance at www.myrevenuedashboard.net");
                        $pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);
                        $pdf->setHeaderFont(array($textfont, '', 8));
                        $pdf->xheadercolor = array(150, 0, 0);
                        $pdf->xheadertext = 'Selected ';
                        $pdf->xfootertext = "Copyright &copy; Revenue Performance. All rights reserved.";
                        $pdf->setPrintHeader(false);
                        $pdf->setPrintFooter(false);
                        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                        $pdf->SetAutoPageBreak(true);
                        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                        $pdf->AddPage();
                        $pdf->SetAutoPageBreak(true);
                        $pdf->SetFillColorArray(array(255, 255, 255));
                        $pdf->SetTextColor(0, 0, 0);
                        if (!empty($clienImage)) {
                            $ext = pathinfo($clienImage, PATHINFO_EXTENSION);
                            if ($ext == "png" || $ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "bmp") {
                                $exts = split("[/\\.]", $clienImage);
                                $n = count($exts) - 1;
                                $exts = $exts[$n];
                                $imgPath = WWW_ROOT . 'files' . DS . 'clientlogos' . DS . $clienImage;
                                $pdf->Image($imgPath, 245, 32, 40, 14, $exts, '', '', true, 150);
                            }
                        }
                        $pdf->SetXY(5, 25);
                        $pdf->writeHTML($htms, true, false, true, false, '');
                        $z = 0;
                        $html234 = "\n" . '<table cellpadding="2" cellspacing="1" border="1">';
                        $i = 0;

                        foreach ($rest_values as $vkey => $values) {
                            if ($i > 0) {
                                $arrtmp = explode("/", $values[1]);
                                $dateStr = $arrtmp[1] . "/" . $arrtmp[0] . "/" . $arrtmp[2];
                                $day = date('N', strtotime($dateStr));
                            } else {
                                $day = "NA";
                            }
                            $html234 .= '<tr>';
                            foreach ($values as $zkey => $val) {
                                if (($zkey != 0) && ($zkey != 1) && ($vkey != 0)) {
                                    if (($val != 0) || ($val != 0.00)) {
                                        $z = 1;
                                    }
                                }
                                $html234 .= '<td>' . $val . '</td>';
                            }
                            $html234 .= '</tr>';
                            $i++;
                        }
                        $html234 .= '</table>';
                        $html_res['values'] = $html234;
                        $pdf->SetXY(125, 15);
                        $pdf->writeHTML($user_data['Client']['hotelname'], true, false, true, false, '');
                        $pdf->SetXY(5, 50);
                        $pdf->writeHTML($html_res['values'], true, false, true, false, '');
                        ob_end_clean();
                        $path = $file_path . "/" . $user_id . '_' . date('d-M-Y') . ".pdf";
                        echo $path . '<br/>';
                        $pdf->Output($path, 'F');
                    }
                }
            }
        }//end foreach
    }

//function save_all_pdf ends..

    function delete_server_sheet($url_encode_path = null) {
        $path = str_replace('$', '/', $url_encode_path);
        $new_path = $_SERVER['DOCUMENT_ROOT'] . "/app/webroot" . $path;
        unlink($new_path);
        $ref_url = $this->referer();
        $this->Session->setFlash(__('File Deleted!', true));
        $this->redirect($ref_url);
    }

    function client_export_csv($sheetId = null, $type = null) {
        if (!empty($sheetId)) {
            if (empty($type)) {
                $type = "csv";
            }
            if (!empty($sheetId)) {
                $data = $this->Sheet->getData($sheetId);
                $sheet = $this->Sheet->read(null, $sheetId);
                $columns = $sheet['Column'];

                $total_columns = $this->Sheet->ColumnsSheet->find('all', array('conditions' => array('ColumnsSheet.sheet_id' => $sheetId), 'order' => array('ColumnsSheet.order ASC')));
                $selected_columns = Set::extract('/ColumnsSheet/column_id', $total_columns);
                $new_arr = array();
                foreach ($selected_columns as $scols) {
                    foreach ($columns as $key => $value) {
                        if ($scols == $value['id']) {
                            $new_arr[] = $value;
                            unset($columns[$key]);
                        }
                    }
                }
                unset($columns);
                $columns = $new_arr;
            }

            $only_column_names = Set::extract('/name', $columns);
            $final_columns = array();
            $final_columns[0] = "Date";
            foreach ($only_column_names as $col_name) {
                $final_columns[] = $col_name;
            }

            $rest_values = array();
            $rest_values[0] = $final_columns;

            for ($i = 0; $i < count($data); $i++) {
                foreach ($data[$i] as $key => $values) {
                    if ($key != "sheetId") {
                        $position = array_search($key, $final_columns);
                        $position = $position + 1;
                        $rest_values[$i + 1][$position] = $values;
                    }
                }
                ksort($rest_values[$i + 1]);
            }
            $this->Export->download1($rest_values, $type);
        }
    }

//function export_csv ends..

    function client_export_pdf($sheetId = null, $type = null) {
        if (!empty($sheetId)) {
            $user_id = $this->Sheet->field('Sheet.user_id', array('Sheet.id' => $sheetId));
            $user_obj = ClassRegistry::init('User');
            $user_data = $user_obj->findById($user_id);
            $clienImage = $user_data['Client']['logo'];
            $this->set(compact('clienImage', 'user_data'));
            $data = $this->Sheet->getData($sheetId);

            $sheetname = $this->Sheet->findById($sheetId);
            $this->set('sheet_name', $sheetname['Sheet']['name']);
            $this->set('department_id', $sheetname['Sheet']['department_id']);

            $headers = array();
            foreach ($data[0] as $key => $value) {
                if ($key != "sheetId") {
                    array_push($headers, $key);
                }
            }
            $rest_values = array();
            $rest_values[0] = $headers;
            for ($i = 0; $i < count($data); $i++) {
                foreach ($data[$i] as $key => $values) {
                    if ($key != "sheetId") {
                        $rest_values[$i + 1][] = $values;
                    }
                }
            }
            $this->set('rest_values', $rest_values);
        }
    }

    function staff_export_csv($sheetId = null, $type = null) {
        if (!empty($sheetId)) {
            if (empty($type)) {
                $type = "csv";
            }
            if (!empty($sheetId)) {
                $data = $this->Sheet->getData($sheetId);
                $sheet = $this->Sheet->read(null, $sheetId);
                $columns = $sheet['Column'];
                $total_columns = $this->Sheet->ColumnsSheet->find('all', array('conditions' => array('ColumnsSheet.sheet_id' => $sheetId), 'order' => array('ColumnsSheet.order ASC')));
                $selected_columns = Set::extract('/ColumnsSheet/column_id', $total_columns);
                $new_arr = array();
                foreach ($selected_columns as $scols) {
                    foreach ($columns as $key => $value) {
                        if ($scols == $value['id']) {
                            $new_arr[] = $value;
                            unset($columns[$key]);
                        }
                    }
                }
                unset($columns);
                $columns = $new_arr;
            }
            $only_column_names = Set::extract('/name', $columns);
            $final_columns = array();
            $final_columns[0] = "Date";
            foreach ($only_column_names as $col_name) {
                $final_columns[] = $col_name;
            }
            $rest_values = array();
            $rest_values[0] = $final_columns;
            for ($i = 0; $i < count($data); $i++) {
                foreach ($data[$i] as $key => $values) {
                    if ($key != "sheetId") {
                        $position = array_search($key, $final_columns);
                        $position = $position + 1;
                        $rest_values[$i + 1][$position] = $values;
                    }
                }
                ksort($rest_values[$i + 1]);
            }
            $this->Export->download1($rest_values, $type);
        }
    }

//function export_csv ends..

    function staff_export_pdf($sheetId = null, $type = null) {
        if (!empty($sheetId)) {
            $user_id = $this->Sheet->field('Sheet.user_id', array('Sheet.id' => $sheetId));
            $user_obj = ClassRegistry::init('User');
            $user_data = $user_obj->findById($user_id);
            $clienImage = $user_data['Client']['logo'];
            $this->set(compact('clienImage', 'user_data'));
            $data = $this->Sheet->getData($sheetId);

            $sheetname = $this->Sheet->findById($sheetId);
            $this->set('sheet_name', $sheetname['Sheet']['name']);
            $this->set('department_id', $sheetname['Sheet']['department_id']);

            $headers = array();
            foreach ($data[0] as $key => $value) {
                if ($key != "sheetId") {
                    array_push($headers, $key);
                }
            }
            $rest_values = array();
            $rest_values[0] = $headers;
            for ($i = 0; $i < count($data); $i++) {
                foreach ($data[$i] as $key => $values) {
                    if ($key != "sheetId") {
                        $rest_values[$i + 1][] = $values;
                    }
                }
            }
            $this->set('rest_values', $rest_values);
        }
    }

//function export_pdf ends..

    /**
     * Action for staff to view the Department sheet
     * @param int $sheetId The Department sheet ID to be viewed
     * @access public
     * @return void
     */
    function staff_webform($sheetId) {

        $formula_obj = ClassRegistry::init('Formula');
        $formula_data = $formula_obj->findBySheetId($sheetId);
        if (empty($formula_data)) {
            $this->Session->setFlash(__('Please Create Formula for this Webform', true));
            $this->redirect(array('action' => 'staff_index'));
        }

        $this->set('is_habtoor', '0');
        $this->set('clientId', $clientId);

        // Set the ext-js layout for this action
        $this->layout = 'ext';

        $sheet = $this->Sheet->read(null, $sheetId);

        //$last_refresh_time = $sheet['Sheet']['last_refresh'];
        $datas_obj = ClassRegistry::init('Datum');
        $datas_data = $datas_obj->find('all', array('conditions' => array('sheet_id' => $sheetId), 'fields' => array('MAX(Datum.modified) AS last_date')));
        $last_refresh_time = $datas_data[0][0]['last_date'];
        $this->set('last_refresh_time', $last_refresh_time);

        $columns = $sheet['Column'];

        $total_columns = $this->Sheet->ColumnsSheet->find('all', array('conditions' => array('ColumnsSheet.sheet_id' => $sheetId), 'order' => array('ColumnsSheet.order ASC')));
        $selected_columns = Set::extract('/ColumnsSheet/column_id', $total_columns);
        $new_arr = array();
        foreach ($selected_columns as $scols) {
            foreach ($columns as $key => $value) {
                if ($scols == $value['id']) {
                    $new_arr[] = $value;
                    unset($columns[$key]);
                }
            }
        }
        unset($columns);
        $columns = $new_arr;
        $data = array();
        $this->set(compact('sheet', 'columns', 'data', 'sheetId'));
    }

//end admin_webform()

    /**
     * Action for client to view the Department sheet
     * @param int $sheetId The Department sheet ID to be viewed
     * @access public
     * @return void
     */
    function client_webform($sheetId) {

        $formula_obj = ClassRegistry::init('Formula');
        $formula_data = $formula_obj->findBySheetId($sheetId);
        $dept_id = $this->Sheet->field('department_id', array('Sheet.id' => $sheetId));

        if (empty($formula_data)) {
            $this->Session->setFlash(__('Please Create Formula for this Webform', true));
            $this->redirect(array('action' => 'client_index', $dept_id));
        }

        $clientId = $this->Auth->user('id');
        $this->set('is_habtoor', '0');
        $this->set('clientId', $clientId);

        // Set the ext-js layout for this action
        $this->layout = 'ext';
        // Set the debug mode to zero
        Configure::write('debug', 0);
        $sheet = $this->Sheet->read(null, $sheetId);

        $datas_obj = ClassRegistry::init('Datum');
        $datas_data = $datas_obj->find('all', array('conditions' => array('sheet_id' => $sheetId), 'fields' => array('MAX(Datum.modified) AS last_date')));
        $last_refresh_time = $datas_data[0][0]['last_date'];

        $this->set('last_refresh_time', $last_refresh_time);
        $columns = $sheet['Column'];
        $total_columns = $this->Sheet->ColumnsSheet->find('all', array('conditions' => array('ColumnsSheet.sheet_id' => $sheetId), 'order' => array('ColumnsSheet.order ASC')));
        $selected_columns = Set::extract('/ColumnsSheet/column_id', $total_columns);
        $new_arr = array();
        foreach ($selected_columns as $scols) {
            foreach ($columns as $key => $value) {
                if ($scols == $value['id']) {
                    $new_arr[] = $value;
                    unset($columns[$key]);
                }
            }
        }
        unset($columns);
        $columns = $new_arr;
        $data = array();
        $this->set(compact('sheet', 'columns', 'data', 'sheetId'));
    }

//end client_webform()

    /**
     * Action to load the department sheet data
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
    }

//end admin_data()

    /*     * * The router for the ext-js actions */
    function router() {
        $this->layout = false;
        Configure::write('debug', 0);
        $this->set('RAW_DATA', $GLOBALS['HTTP_RAW_POST_DATA']);
    }

    function router_adv() {
        $this->layout = false;
        Configure::write('debug', 0);
        $this->set('RAW_DATA', $GLOBALS['HTTP_RAW_POST_DATA']);
    }

//end router()

    /**
     * Method o check whether the user exists
     * @param int $userId The user ID to be checked
     * @access private
     * @return void
     */
    private function __check_user($userId) {
        if (!$this->Sheet->User->hasAny(array('User.id' => $userId, 'User.status' => 1))) {
            $this->Session->setFlash("Invalid User ID");
            $this->redirect(array('prefix' => 'admin', 'admin' => true, 'controller' => 'users'));
        }
    }

    function client_lock($sheet_id = null) {
        if (!empty($sheet_id)) {
            $this->Sheet->id = $sheet_id;
            if ($this->Sheet->saveField('status', 0)) {
                $this->Session->setFlash('Sheet Successfully Locked.');
                $this->redirect($this->referer());
            }
        }
    }

    function client_unlock($sheet_id = null) {
        if (!empty($sheet_id)) {
            $this->Sheet->id = $sheet_id;
            if ($this->Sheet->saveField('status', 1)) {
                $this->Session->setFlash('Sheet Successfully unlocked.');
                $this->redirect($this->referer());
            }
        }
    }

    function admin_lock($sheet_id = null) {
        if (!empty($sheet_id)) {
            $this->Sheet->id = $sheet_id;
            if ($this->Sheet->saveField('status', 0)) {
                $this->Session->setFlash('Sheet Successfully Locked.');
                $this->redirect($this->referer());
            }
        }
    }

    function admin_unlock($sheet_id = null) {
        if (!empty($sheet_id)) {
            $this->Sheet->id = $sheet_id;
            if ($this->Sheet->saveField('status', 1)) {
                $this->Session->setFlash('Sheet Successfully unlocked.');
                $this->redirect($this->referer());
            }
        }
    }

    /* generate pie-line charts for admin section */

    function admin_viewchart($sheetId = null, $type = null, $year = null, $cur_col = null, $month = 1) {
        $this->layout = false;
        if (!empty($cur_col)) {
            $column_id['Formula']['column_id'] = $cur_col;
        } else {
            $column_id = $this->Sheet->Formula->find('first', array('fields' => array('Formula.column_id'), 'conditions' => array('Formula.sheet_id' => $sheetId, 'Formula.type' => 'main')));
            if (empty($column_id)) {
                $cols_obj = ClassRegistry::init('Column');
                $col_id = $cols_obj->find('first', array('conditions' => array('Column.name' => 'Total', 'Column.status !=' => 2)));
                $column_id['Formula']['column_id'] = $col_id['Column']['id'];
            }
        }
        $all_columns = $this->Sheet->Formula->find('all', array('fields' => array('Formula.column_id'), 'conditions' => array('Formula.sheet_id' => $sheetId)));
        $calc_data = $this->Sheet->Column->findById($column_id['Formula']['column_id'], array('fields' => 'name'));
        $cols_array = array();
        foreach ($all_columns as $col) {
            $col_name = $this->Sheet->Column->findById($col['Formula']['column_id'], array('fields' => 'name'));
            $cols_array[$col['Formula']['column_id']] = $col_name['Column']['name'];
        }

        if (!empty($year) && $year != "year") {
            $this->Sheet->unbindModel(array('belongsTo' => array('User'), 'hasMany' => array('Datum', 'Formula'), 'hasAndBelongsToMany' => array('Column')));
            $userData = $this->Sheet->findById($sheetId, array('fields' => 'user_id, department_id'));
            $chart_data = $this->Sheet->getMonthlyData($userData['Sheet']['user_id'], $userData['Sheet']['department_id'], $calc_data['Column']['name'], $year, $month);
        } else {
            $data = $this->Sheet->getData($sheetId);
            $chart_data = array();
            if (!empty($calc_data)) {
                foreach ($data as $key => $value) {
                    if ($data[$key][$calc_data['Column']['name']] != 0) {
                        $chart_data[$data[$key]['Date']] = $data[$key][$calc_data['Column']['name']];
                    }
                }
            }
        }
        $this->set('month', $month);
        $this->set('arr', $chart_data);
        $this->set('type', $type);
        $this->set('cols_array', $cols_array);
        $this->set('default_col', $column_id);
    }

//end admin_viewchart

    /* generate pie-line charts for admin section */
    function staff_viewchart($sheetId = null, $type = null, $year = null, $cur_col = null, $month = 1) {
        $this->layout = false;
        if (!empty($cur_col)) {
            $column_id['Formula']['column_id'] = $cur_col;
        } else {
            $column_id = $this->Sheet->Formula->find('first', array('fields' => array('Formula.column_id'), 'conditions' => array('Formula.sheet_id' => $sheetId, 'Formula.type' => 'main')));
            if (empty($column_id)) {
                $cols_obj = ClassRegistry::init('Column');
                $col_id = $cols_obj->find('first', array('conditions' => array('Column.name' => 'Total', 'Column.status !=' => 2)));
                $column_id['Formula']['column_id'] = $col_id['Column']['id'];
            }
        }

        $all_columns = $this->Sheet->Formula->find('all', array('fields' => array('Formula.column_id'), 'conditions' => array('Formula.sheet_id' => $sheetId)));
        $calc_data = $this->Sheet->Column->findById($column_id['Formula']['column_id'], array('fields' => 'name'));
        $cols_array = array();

        foreach ($all_columns as $col) {
            $col_name = $this->Sheet->Column->findById($col['Formula']['column_id'], array('fields' => 'name'));
            $cols_array[$col['Formula']['column_id']] = $col_name['Column']['name'];
        }
        if (!empty($year) && $year != "year") {
            $this->Sheet->unbindModel(array('belongsTo' => array('User'), 'hasMany' => array('Datum', 'Formula'), 'hasAndBelongsToMany' => array('Column')));
            $userData = $this->Sheet->findById($sheetId, array('fields' => 'user_id, department_id'));
            $chart_data = $this->Sheet->getMonthlyData($userData['Sheet']['user_id'], $userData['Sheet']['department_id'], $calc_data['Column']['name'], $year, $month);
        } else {
            $data = $this->Sheet->getData($sheetId);
            $chart_data = array();
            foreach ($data as $key => $value) {
                if ($data[$key][$calc_data['Column']['name']] != 0) {
                    $chart_data[$data[$key]['Date']] = $data[$key][$calc_data['Column']['name']];
                }
            }
        }
        $this->set('month', $month);
        $this->set('arr', $chart_data);
        $this->set('type', $type);
        $this->set('cols_array', $cols_array);
        $this->set('default_col', $column_id);
    }

    function client_viewchart($sheetId = null, $type = null, $year = null, $cur_col = null, $month = 1) {
        $this->layout = false;
        if (!empty($cur_col)) {
            $column_id['Formula']['column_id'] = $cur_col;
        } else {
            $column_id = $this->Sheet->Formula->find('first', array('fields' => array('Formula.column_id'), 'conditions' => array('Formula.sheet_id' => $sheetId, 'Formula.type' => 'main')));
            if (empty($column_id)) {
                $cols_obj = ClassRegistry::init('Column');
                $col_id = $cols_obj->find('first', array('conditions' => array('Column.name' => 'Total', 'Column.status !=' => 2)));
                $column_id['Formula']['column_id'] = $col_id['Column']['id'];
            }
        }
        $all_columns = $this->Sheet->Formula->find('all', array('fields' => array('Formula.column_id'), 'conditions' => array('Formula.sheet_id' => $sheetId)));
        $calc_data = $this->Sheet->Column->findById($column_id['Formula']['column_id'], array('fields' => 'name'));
        $cols_array = array();

        foreach ($all_columns as $col) {
            $col_name = $this->Sheet->Column->findById($col['Formula']['column_id'], array('fields' => 'name'));
            $cols_array[$col['Formula']['column_id']] = $col_name['Column']['name'];
        }
        if (!empty($year) && $year != "year") {
            $this->Sheet->unbindModel(array('belongsTo' => array('User'), 'hasMany' => array('Datum', 'Formula'), 'hasAndBelongsToMany' => array('Column')));
            $userData = $this->Sheet->findById($sheetId, array('fields' => 'user_id, department_id'));
            $chart_data = $this->Sheet->getMonthlyData($userData['Sheet']['user_id'], $userData['Sheet']['department_id'], $calc_data['Column']['name'], $year, $month);
        } else {
            $data = $this->Sheet->getData($sheetId);
            $chart_data = array();
            foreach ($data as $key => $value) {
                if ($data[$key][$calc_data['Column']['name']] != 0) {
                    $chart_data[$data[$key]['Date']] = $data[$key][$calc_data['Column']['name']];
                }
            }
        }

        $this->set('month', $month);
        $this->set('arr', $chart_data);
        $this->set('type', $type);
        $this->set('cols_array', $cols_array);
        $this->set('default_col', $column_id);
    }

    function updateOrder($sheet_id = null) {
        $this->autoRender = false;
        $array = $_POST['arrayorder'];
        $count = 1;
        $total_columns = $this->Sheet->ColumnsSheet->find('all', array('conditions' => array('ColumnsSheet.sheet_id' => $sheet_id)));
        foreach ($array as $idval) {
            $final_idval = explode('#', $idval);
            $this->Sheet->ColumnsSheet->updateAll(array('ColumnsSheet.order' => $count), array('ColumnsSheet.column_id' => $final_idval[0], 'ColumnsSheet.sheet_id' => $sheet_id));
            foreach ($total_columns as $cols) {
                if ($final_idval[0] == $cols['ColumnsSheet']['column_id']) {
                    $count++;
                }
            }
        }
        echo 'All saved! refresh the page to see the changes';
    }

    function admin_webform_refresh($sheetId) {
        $this->layout = false;
        $formula_obj = ClassRegistry::init('Formula');
        $formula_data = $formula_obj->findBySheetId($sheetId);
        // Find the sheet user id
        $userId = $this->Sheet->field('user_id', array('Sheet.id' => $sheetId));
        $dept_id = $this->Sheet->field('department_id', array('Sheet.id' => $sheetId));

        if (empty($formula_data)) {
            $this->Session->setFlash(__('Please Create Formula for this Webform', true));
            $this->redirect(array('action' => 'index', $userId, $dept_id));
        }

        // Set the ext-js layout for this action
        $this->layout = 'ext';
        // Set the debug mode to zero
        Configure::write('debug', 0);
        $sheet = $this->Sheet->read(null, $sheetId);
        $columns = $sheet['Column'];
        $total_columns = $this->Sheet->ColumnsSheet->find('all', array('conditions' => array('ColumnsSheet.sheet_id' => $sheetId), 'order' => array('ColumnsSheet.order ASC')));
        $selected_columns = Set::extract('/ColumnsSheet/column_id', $total_columns);
        $new_arr = array();
        foreach ($selected_columns as $scols) {
            foreach ($columns as $key => $value) {
                if ($scols == $value['id']) {
                    $new_arr[] = $value;
                    unset($columns[$key]);
                }
            }
        }
        unset($columns);
        $columns = $new_arr;
        $data = $this->Sheet->getData($sheetId);
        $this->set(compact('sheet', 'columns', 'data', 'sheetId'));
    }

//end admin_webform()

    /*     * ************************
     * Added on : 2 Aug'2013
     * Description :For cron to save Rooms department data for Pickup graph
     * ******************************* */
    public function save_bob_data() {

        $this->autoRender = false;
        $this->layout = '';
        App::import('Model', 'Client');
        $this->Client = new Client();

        $client_data = $this->Client->find('all', array('conditions' => array('Client.status !=' => '2'), 'fields' => 'Client.id', 'recursive' => '0'));

        foreach ($client_data as $clientDetails) {

            $client_id = $clientDetails['Client']['id'];

            $dept_ids = $this->requestAction('/Clients/get_room_department/' . $client_id);

            App::import('Model', 'Sheet');
            $this->Sheet = new Sheet();

            $sheetIds = array();
            for ($i = '0'; $i <= '2'; $i++) {
                $year = date('Y');
                $month = date('m');
                $month = $month + $i;
                if ($month > '12') {
                    $month = $month - '12';
                    $year = $year + '1';
                }
                $monthSheet = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' => $year, 'Sheet.month' => $month, 'Sheet.department_id' => $dept_ids), 'fields' => array('Sheet.id'), 'recursive' => '0'));
                foreach ($monthSheet as $sheet_ids) {
                    $sheetIds[] = $sheet_ids['Sheet']['id'];
                }
            }

            $present_month = date('m');
            $prev_mnth = date('m', strtotime('-1 month'));
            $month = array($present_month, $prev_mnth);

            if (!empty($dept_ids)) {

                $conditions = array('Sheet.id' => $sheetIds);
                $all_sheets = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => 'id,user_id,name,department_id,month,year', 'recursive' => -1));

                if (!empty($all_sheets)) {

                    foreach ($all_sheets as $sheets) {
                        $sheetId = $sheets['Sheet']['id'];

                        $days_in_presnt_month = cal_days_in_month(CAL_GREGORIAN, $sheets['Sheet']['month'], $sheets['Sheet']['year']);

                        unset($bob_value);
                        $bob_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => '62', 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value,Datum.date'), 'order' => 'Datum.date ASC'));
                        foreach ($bob_value as $bob) {
                            $bob_data['BobData']['month'] = $sheets['Sheet']['month'];
                            $bob_data['BobData']['year'] = $sheets['Sheet']['year'];
                            $bob_data['BobData']['value'] = $bob['Datum']['value'];
                            $bob_data['BobData']['sheet_id'] = $sheetId;
                            $bob_data['BobData']['id'] = '';
                            $bob_data['BobData']['date'] = $bob['Datum']['date'];
                            ClassRegistry::init('BobData')->save($bob_data);
                        }

                        unset($adr_value);
                        $adr_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => '64', 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value,Datum.date'), 'order' => 'Datum.date ASC'));
                        foreach ($adr_value as $adr) {
                            $adr_data['AdrData']['month'] = $sheets['Sheet']['month'];
                            $adr_data['AdrData']['year'] = $sheets['Sheet']['year'];
                            $adr_data['AdrData']['value'] = $adr['Datum']['value'];
                            $adr_data['AdrData']['sheet_id'] = $sheetId;
                            $adr_data['AdrData']['id'] = '';
                            $adr_data['AdrData']['date'] = $adr['Datum']['date'];
                            ClassRegistry::init('AdrData')->save($adr_data);
                        }
                    }
                }
            }
        }//end foreach of clients

        ClassRegistry::init('BobData')->deleteAll(array('DATE(created) < ' => date("Y-m-d", strtotime("-8 months"))));
        ClassRegistry::init('AdrData')->deleteAll(array('DATE(created) < ' => date("Y-m-d", strtotime("-8 months"))));
    }

//save_bob_data function ends here

    /*     * ********************
     * Function : to save weekly report data
     * Added on 27 Jan'2014
     * ****************************** */
    public function weekly_report_data() {

        $this->autoRender = false;
        $this->layout = '';

        App::import('Model', 'Client');
        $this->Client = new Client();
        $client_data = $this->Client->find('all', array('conditions' => array('Client.status !=' => '2'), 'fields' => 'Client.id', 'recursive' => '0'));
        foreach ($client_data as $clientDetails) {

            $client_id = $clientDetails['Client']['id'];
            $dept_ids = $this->requestAction('/Clients/get_room_department/' . $client_id);

            App::import('Model', 'Sheet');
            $this->Sheet = new Sheet();

            if (!empty($dept_ids)) {
                $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.department_id' => $dept_ids, 'Sheet.month' => date('m'), 'Sheet.year' => date('Y')), 'fields' => array('Sheet.id', 'Sheet.month', 'Sheet.year'), 'order' => 'Sheet.modified DESC', 'recursive' => '0'));
                if (!empty($all_sheets)) {
                    foreach ($all_sheets as $sheets) {
                        $sheetId = $sheets['Sheet']['id'];
                        $sheet_value = $this->Sheet->getData($sheetId);
                        foreach ($sheet_value as $values) {
                            if ($values['Date'] == 'Total') {
                                $SheetdataDate = ClassRegistry::init('WeeklyReportData')->find('count', array('conditions' => array('WeeklyReportData.sheet_id' => $sheetId, 'WeeklyReportData.date' => date('Y-m-d'))));
                                if ($SheetdataDate == 0) {
                                    $t = 0;
                                    foreach ($values as $tkey => $total_key) {
                                        if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                                            $Sheetdata[$t]['sheet_id'] = $sheetId;
                                            $Sheetdata[$t]['date'] = date('Y-m-d');
                                            $Sheetdata[$t]['type'] = $tkey;
                                            $Sheetdata[$t]['total'] = $total_key;
                                            $t++;
                                        }
                                    }
                                    ClassRegistry::init('WeeklyReportData')->saveAll($Sheetdata);
                                }
                            }
                        }
                    }
                }
            }
        }
        ClassRegistry::init('WeeklyReportData')->deleteAll(array('DATE(date) < ' => date("Y-m-d", strtotime("-6 months"))));
    }

    function get_staff_pickup_chart_weekly($client_id = null, $pickup_day = null, $month = null, $year = null) {

        $this->layout = '';
        App::import('Model', 'Client');
        $this->Client = new Client();

        if ($month == '0' || empty($month)) {
            $month = date('m');
        }
        if ($year == '0' || empty($year)) {
            $year = date('Y');
        }

        $pickup_date = date("Y-m-d", strtotime("monday last week")); // last week monday
        $today = date('Y-m-d');
        $client_name = $this->Client->find('first', array('conditions' => array('Client.id' => $client_id), 'fields' => 'Client.hotelname'));
        $hotelname = $client_name['Client']['hotelname'];
        $this->set('client_id', $client_id);

        $dept_ids = $this->requestAction('/Clients/get_room_department/' . $client_id);

        App::import('Model', 'Sheet');
        $this->Sheet = new Sheet();
        $columns = array('62');
        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.department_id' => $dept_ids, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'order' => 'Sheet.modified DESC', 'recursive' => '0'));

        $this->set('sheet_id', $all_sheets[0]['Sheet']['id']);
        $sheetId = $all_sheets[0]['Sheet']['id'];
        unset($bob_value);
        unset($bob_fcst_value);
        unset($bob_pickup_value);

        $days_in_presnt_month = cal_days_in_month(CAL_GREGORIAN, $month, $year); //calculate the number of days in present month

        $bob_pickup_value = ClassRegistry::init('BobData')->find('all', array('conditions' => array('sheet_id' => $sheetId, 'date !=' => '0', 'DATE(created)' => $pickup_date, 'date >=' => '1', 'date <=' => $days_in_presnt_month), 'fields' => array('value'), 'order' => 'date ASC'));
        $bob_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => '62', 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date >=' => '1', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value'), 'order' => 'Datum.date ASC'));
        $bob_fcst_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => '63', 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date >=' => '1', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value'), 'order' => 'Datum.date ASC'));

        $bob_arr = '';
        $bob_fcst_arr = '';
        $bob_pickup_arr = '';
        $date_arr = '';
        $bob_count = 0;
        $bob_pickup_count = 0;
        $date_count = 0;
        $bob_fcst_count = 0;
        $i = '0';
        foreach ($bob_value as $bob) {
            if ($bob_count == '') {
                $bob_arr = $bob['Datum']['value'];
            } else {
                $bob_arr = $bob_arr . ',' . $bob['Datum']['value'];
            }
            $bob_count++;
            $i++;
        }

        if (!empty($bob_pickup_value)) {
            $i = '0';
            foreach ($bob_pickup_value as $bob_pickup) {
                $bob_pickup_val = $bob_value[$i]['Datum']['value'] - $bob_pickup['BobData']['value'];
                if ($bob_pickup_count == '') {
                    $bob_pickup_arr = $bob_pickup_val;
                } else {
                    $bob_pickup_arr = $bob_pickup_arr . ',' . $bob_pickup_val;
                }
                $bob_pickup_count++;
                $i++;
            }
            $bob_pickup_arr = '[' . $bob_pickup_arr . ']';
        } else {
            $bob_pickup_arr = '[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]';
        }
        $i = '0';
        foreach ($bob_fcst_value as $bob_fcst) {
            $bob_fcst['Datum']['value'] = $bob_fcst['Datum']['value'] - $bob_value[$i]['Datum']['value'];
            if ($bob_fcst_count == '') {
                $bob_fcst_arr = $bob_fcst['Datum']['value'];
            } else {
                $bob_fcst_arr = $bob_fcst_arr . ',' . $bob_fcst['Datum']['value'];
            }
            $bob_fcst_count++;
            $i++;
        }

        $date_arr = '';
        for ($i = 1; $i <= $days_in_presnt_month; $i++) {
            if ($i == '1') {
                $date_arr = "'" . $i . "'";
            } else {
                $date_arr = $date_arr . ",'" . $i . "'";
            }
        }
        $bob_arr = '[' . $bob_arr . ']';

        $bob_fcst_arr = '[' . $bob_fcst_arr . ']';
        $date_arr = '[' . $date_arr . ']';
        $this->set('bob_arr', $bob_arr);
        $this->set('bob_fcst_arr', $bob_fcst_arr);
        $this->set('date_arr', $date_arr);
        $this->set('hotelname', $hotelname);
        $this->set('bob_pickup_arr', $bob_pickup_arr);
    }

    public function admin_weekly_report($client_id = null) {
        $this->Client = ClassRegistry::init('Client');
        $all_hotels = $this->Client->find('all', array('conditions' => array('Client.status !=' => 2), 'fields' => 'id,hotelname'));
        $this->set('all_hotels', $all_hotels);
        App::import('Model', 'Client');
        $this->Client = new Client();
        $month = date('m');
        $year = date('Y');

        $client_name = $this->Client->find('first', array('conditions' => array('Client.id' => $client_id), 'fields' => 'Client.hotelname'));
        $hotelname = $client_name['Client']['hotelname'];

        $this->set('client_id', $client_id);

        $dept_ids = $this->requestAction('/Clients/get_room_department/' . $client_id);

        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.department_id' => $dept_ids, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'order' => 'Sheet.modified DESC', 'recursive' => '0'));
        $this->set('sheet_id', $all_sheets[0]['Sheet']['id']);
        $sheetId = $all_sheets[0]['Sheet']['id'];

        $sheet_value = $this->Sheet->getData($sheetId);
        $last_monday = date("Y-m-d", strtotime("monday last week"));
        $SheetdataDetails = ClassRegistry::init('WeeklyReportData')->find('list', array('conditions' => array('WeeklyReportData.sheet_id' => $sheetId, 'WeeklyReportData.date' => $last_monday), 'fields' => array('WeeklyReportData.type', 'WeeklyReportData.total')));

        unset($Sheetdata);
        foreach ($sheet_value as $values) {
            if ($values['Date'] == 'Total') {
                foreach ($values as $tkey => $total_key) {
                    if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                        $Sheetdata_today[$tkey] = $total_key;
                    }
                }
            }
        }//end foreach to save data

        $this->set('Sheetdata_today', $Sheetdata_today);
        $this->set('SheetdataDetails', $SheetdataDetails);
    }

    public function client_weekly_report($client_id = null) {
        $this->Client = ClassRegistry::init('Client');
        $all_hotels = $this->Client->find('all', array('conditions' => array('Client.status !=' => 2), 'fields' => 'id,hotelname'));
        $this->set('all_hotels', $all_hotels);
        App::import('Model', 'Client');
        $this->Client = new Client();
        $month = date('m');
        $year = date('Y');

        $client_name = $this->Client->find('first', array('conditions' => array('Client.id' => $client_id), 'fields' => 'Client.hotelname'));
        $hotelname = $client_name['Client']['hotelname'];
        $this->set('client_id', $client_id);

        $dept_ids = $this->requestAction('/Clients/get_room_department/' . $client_id);

        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.department_id' => $dept_ids, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'order' => 'Sheet.modified DESC', 'recursive' => '0'));
        $this->set('sheet_id', $all_sheets[0]['Sheet']['id']);
        $sheetId = $all_sheets[0]['Sheet']['id'];
        $sheet_value = $this->Sheet->getData($sheetId);
        $last_monday = date("Y-m-d", strtotime("monday last week"));
        $SheetdataDetails = ClassRegistry::init('WeeklyReportData')->find('list', array('conditions' => array('WeeklyReportData.sheet_id' => $sheetId, 'WeeklyReportData.date' => $last_monday), 'fields' => array('WeeklyReportData.type', 'WeeklyReportData.total')));

        unset($Sheetdata);
        foreach ($sheet_value as $values) {
            if ($values['Date'] == 'Total') {

                foreach ($values as $tkey => $total_key) {
                    if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                        $Sheetdata_today[$tkey] = $total_key;
                    }
                }
            }
        }//end foreach to save data
        $this->set('Sheetdata_today', $Sheetdata_today);
        $this->set('SheetdataDetails', $SheetdataDetails);
    }

    public function staff_weekly_report($client_id = null) {
        $this->Client = ClassRegistry::init('Client');
        $all_hotels = $this->Client->find('all', array('conditions' => array('Client.status !=' => 2), 'fields' => 'id,hotelname'));
        $this->set('all_hotels', $all_hotels);

        App::import('Model', 'Client');
        $this->Client = new Client();

        $month = date('m');
        $year = date('Y');

        $client_name = $this->Client->find('first', array('conditions' => array('Client.id' => $client_id), 'fields' => 'Client.hotelname'));
        $hotelname = $client_name['Client']['hotelname'];
        $this->set('client_id', $client_id);

        $dept_ids = $this->requestAction('/Clients/get_room_department/' . $client_id);

        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.department_id' => $dept_ids, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'order' => 'Sheet.modified DESC', 'recursive' => '0'));
        $this->set('sheet_id', $all_sheets[0]['Sheet']['id']);
        $sheetId = $all_sheets[0]['Sheet']['id'];

        $sheet_value = $this->Sheet->getData($sheetId);

        $last_monday = date("Y-m-d", strtotime("monday last week"));
        $SheetdataDetails = ClassRegistry::init('WeeklyReportData')->find('list', array('conditions' => array('WeeklyReportData.sheet_id' => $sheetId, 'WeeklyReportData.date' => $last_monday), 'fields' => array('WeeklyReportData.type', 'WeeklyReportData.total')));

        unset($Sheetdata);
        foreach ($sheet_value as $values) {
            if ($values['Date'] == 'Total') {
                foreach ($values as $tkey => $total_key) {
                    if ($tkey != 'id' && $tkey != 'sheetId' && $tkey != 'Date') {
                        $Sheetdata_today[$tkey] = $total_key;
                    }
                }
            }
        }//end foreach to save data

        $this->set('Sheetdata_today', $Sheetdata_today);
        $this->set('SheetdataDetails', $SheetdataDetails);
    }

    function get_staff_chart($client_id = null, $month = null, $year = null, $departmentId = null) {
        $this->layout = '';

        App::import('Model', 'Client');
        $this->Client = new Client();

        if ($month == '0' || empty($month)) {
            $month = date('m');
        }
        if ($year == '0' || empty($year)) {
            $year = date('Y');
        }

        $client_name = $this->Client->find('first', array('conditions' => array('Client.id' => $client_id), 'fields' => 'Client.hotelname'));
        $hotelname = $client_name['Client']['hotelname'];
        $this->set('client_id', $client_id);

        $dept_ids = array();
        if ($departmentId == 0) {
            $dept_ids = $this->requestAction('/Clients/get_room_department/' . $client_id);
            $departmentName = 'Rooms';
            $columns = array('BOB' => '62', 'ADR' => '64');
            $columnNames = array('BOB' => 'BOB', 'ADR' => 'ADR');
        } else {
            $this->Client->Department->recursive = -1;
            $condition = array('Department.id' => $departmentId);
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'name', 'recursive' => '0'));
            $departmentName = $dept_data['Department']['name'];
            if (strstr($departmentName, 'Room')) {
                $columns = array('BOB' => '62', 'ADR' => '64');
                $columnNames = array('BOB' => 'BOB', 'ADR' => 'ADR');
            } elseif ($departmentName == 'Spa') {
                $columns = array('BOB' => '90', 'ADR' => '163');
                $columnNames = array('BOB' => 'Treatments', 'ADR' => 'Ave Spend Booked');
            } elseif ($departmentName == 'Restaurant') {
                $columns = array('BOB' => '93', 'ADR' => '85');
                $columnNames = array('BOB' => 'Covers', 'ADR' => 'Ave Spend');
            } elseif ($departmentName == 'Banqueting') {
                $columns = array('BOB' => '172', 'ADR' => '115');
                $columnNames = array('BOB' => 'Conf Rev', 'ADR' => 'RevPASM');
            } else {
                echo 'Chart Not available';
                exit;
            }
            $dept_ids[] = $departmentId;
        }
        $this->set('departmentName', $departmentName);
        $this->set('columnNames', $columnNames);


        App::import('Model', 'Sheet');
        $this->Sheet = new Sheet();

        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.department_id' => $dept_ids, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'order' => 'Sheet.modified DESC', 'recursive' => '0'));
        $this->set('sheet_id', $all_sheets[0]['Sheet']['id']);
        $sheetId = $all_sheets[0]['Sheet']['id'];
        unset($bob_value);
        unset($adr_value);

        $days_in_presnt_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $bob_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => $columns['BOB'], 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date >=' => '1', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value'), 'order' => 'Datum.date ASC'));
        $adr_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => $columns['ADR'], 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date >=' => '1', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value'), 'order' => 'Datum.date ASC'));
        $bob_arr = '';
        $adr_arr = '';
        $date_arr = '';
        $bob_count = 0;
        $date_count = 0;
        $adr_count = 0;
        foreach ($bob_value as $bob) {
            if ($bob_count == '') {
                $bob_arr = str_replace(',', '', $bob['Datum']['value']);
            } else {
                $bob_arr = $bob_arr . ',' . str_replace(',', '', $bob['Datum']['value']);
            }
            $bob_count++;
        }
        $adr_arr = '';
        foreach ($adr_value as $adr) {
            $adr['Datum']['value'] = str_replace(',', '', $adr['Datum']['value']);
            $adr1 = number_format($adr['Datum']['value'], 2);
            $adr_final = $adr1;

            if ($adr_count == '') {
                $adr_arr = str_replace(',', '', $adr_final);
            } else {
                $adr_arr = $adr_arr . ',' . str_replace(',', '', $adr_final);
                ;
            }
            $adr_count++;
        }
        $date_arr = '';
        for ($i = 1; $i <= count($adr_value); $i++) {
            if ($i == '1') {
                $date_arr = "'" . $i . "'";
            } else {
                $date_arr = $date_arr . ",'" . $i . "'";
            }
        }
        $bob_arr = '[' . $bob_arr . ']';
        $adr_arr = '[' . $adr_arr . ']';
        $date_arr = '[' . $date_arr . ']';

        $this->set('bob_arr', $bob_arr);
        $this->set('adr_arr', $adr_arr);
        $this->set('date_arr', $date_arr);
        $this->set('hotelname', $hotelname);
    }

    function get_staff_forecast_chart($client_id = null, $month = null, $year = null) {
        $this->layout = '';
        App::import('Model', 'Client');
        $this->Client = new Client();

        $client_name = $this->Client->find('first', array('conditions' => array('Client.id' => $client_id), 'fields' => 'Client.hotelname'));
        $hotelname = $client_name['Client']['hotelname'];

        if ($month == '0' || empty($month)) {
            $month = date('m');
        }
        if ($year == '0' || empty($year)) {
            $year = date('Y');
        }

        $this->set('client_id', $client_id);

        $dept_ids = $this->requestAction('/Clients/get_room_department/' . $client_id);

        App::import('Model', 'Sheet');
        $this->Sheet = new Sheet();
        $columns = array('63', '65');
        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.department_id' => $dept_ids, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'order' => 'Sheet.modified DESC', 'recursive' => '0'));
        $this->set('sheet_id', $all_sheets[0]['Sheet']['id']);
        $sheetId = $all_sheets[0]['Sheet']['id'];
        unset($bob_value);
        unset($adr_value);

        $days_in_presnt_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $bob_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => '63', 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date >=' => '1', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value'), 'order' => 'Datum.date ASC'));
        $adr_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => '65', 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date >=' => '1', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value'), 'order' => 'Datum.date ASC'));

        $bob_arr = '';
        $adr_arr = '';
        $date_arr = '';
        $bob_count = 0;
        $date_count = 0;
        $adr_count = 0;
        foreach ($bob_value as $bob) {
            if ($bob_count == '') {
                $bob_arr = str_replace(',', '', $bob['Datum']['value']);
            } else {
                $bob_arr = $bob_arr . ',' . str_replace(',', '', $bob['Datum']['value']);
            }
            $bob_count++;
        }
        $adr_arr = '';
        foreach ($adr_value as $adr) {
            $adr['Datum']['value'] = str_replace(',', '', $adr['Datum']['value']);
            $adr1 = number_format($adr['Datum']['value'], 2);
            $adr_final = $adr1;

            if ($adr_count == '') {
                $adr_arr = str_replace(',', '', $adr_final);
            } else {
                $adr_arr = $adr_arr . ',' . str_replace(',', '', $adr_final);
            }
            $adr_count++;
        }
        $date_arr = '';
        for ($i = 1; $i <= count($adr_value); $i++) {
            if ($i == '1') {
                $date_arr = "'" . $i . "'";
            } else {
                $date_arr = $date_arr . ",'" . $i . "'";
            }
        }
        $bob_arr = '[' . $bob_arr . ']';
        $adr_arr = '[' . $adr_arr . ']';
        $date_arr = '[' . $date_arr . ']';
        $this->set('bob_arr', $bob_arr);
        $this->set('adr_arr', $adr_arr);
        $this->set('date_arr', $date_arr);
        $this->set('hotelname', $hotelname);
    }

    function get_staff_combined_chart($client_id = null, $month = null, $year = null) {
        $this->layout = '';

        App::import('Model', 'Client');
        $this->Client = new Client();

        $client_name = $this->Client->find('first', array('conditions' => array('Client.id' => $client_id), 'fields' => 'Client.hotelname'));
        $hotelname = $client_name['Client']['hotelname'];

        $this->set('client_id', $client_id);

        if ($month == '0' || empty($month)) {
            $month = date('m');
        }
        if ($year == '0' || empty($year)) {
            $year = date('Y');
        }

        $dept_ids = $this->requestAction('/Clients/get_room_department/' . $client_id);

        App::import('Model', 'Sheet');
        $this->Sheet = new Sheet();

        $columns = array('62', '63', '64', '65');
        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' => $year, 'Sheet.department_id' => $dept_ids, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'order' => 'Sheet.modified DESC', 'recursive' => '0'));
        $this->set('sheet_id', $all_sheets[0]['Sheet']['id']);
        $sheetId = $all_sheets[0]['Sheet']['id'];
        unset($bob_fcst_value);
        unset($adr_fsct_value);
        unset($bob_value);
        unset($adr_value);

        $days_in_presnt_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $bob_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => '62', 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date >=' => '1', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value'), 'order' => 'Datum.date ASC'));
        $adr_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => '64', 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date >=' => '1', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value'), 'order' => 'Datum.date ASC'));

        $bob_fcst_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => '63', 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date >=' => '1', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value'), 'order' => 'Datum.date ASC'));
        $adr_fcst_value = $this->Sheet->Datum->find('all', array('conditions' => array('Datum.column_id' => '65', 'Datum.sheet_id' => $sheetId, 'Datum.date !=' => '0', 'Datum.date >=' => '1', 'Datum.date <=' => $days_in_presnt_month), 'fields' => array('Datum.value'), 'order' => 'Datum.date ASC'));

        $bob_arr = '';
        $bob_fcst_arr = '';
        $adr_arr = '';
        $adr_fcst_arr = '';
        $date_arr = '';
        $bob_fcst_count = 0;
        $bob_count = 0;
        $date_count = 0;
        $adr_count = 0;
        $adr_fcst_count = 0;

        foreach ($bob_value as $bob) {
            if ($bob_count == '') {
                $bob_arr = str_replace(',', '', $bob['Datum']['value']);
            } else {
                $bob_arr = $bob_arr . ',' . str_replace(',', '', $bob['Datum']['value']);
            }
            $bob_count++;
        }
        $adr_arr = '';
        foreach ($adr_value as $adr) {
            $adr['Datum']['value'] = str_replace(',', '', $adr['Datum']['value']);
            $adr1 = number_format($adr['Datum']['value'], 2);

            $adr_final = $adr1;

            if ($adr_count == '') {
                $adr_arr = str_replace(',', '', $adr_final);
            } else {
                $adr_arr = $adr_arr . ',' . str_replace(',', '', $adr_final);
            }
            $adr_count++;
        }
        $date_arr = '';
        for ($i = 1; $i <= count($adr_value); $i++) {
            if ($i == '1') {
                $date_arr = "'" . $i . "'";
            } else {
                $date_arr = $date_arr . ",'" . $i . "'";
            }
        }

        $bob_fcst_count = '';
        foreach ($bob_fcst_value as $bob_fcst) {
            if ($bob_fcst_count == '') {
                $bob_fcst_arr = str_replace(',', '', $bob_fcst['Datum']['value']);
            } else {
                $bob_fcst_arr = $bob_fcst_arr . ',' . str_replace(',', '', $bob_fcst['Datum']['value']);
            }
            $bob_fcst_count++;
        }

        foreach ($adr_fcst_value as $adr_fcst) {
            $adr_fcst['Datum']['value'] = str_replace(',', '', $adr_fcst['Datum']['value']);
            $adr11 = number_format($adr_fcst['Datum']['value'], 2);
            $adr_final1 = $adr11;

            if ($adr_fsct_count == '') {
                $adr_fcst_arr = str_replace(',', '', $adr_final1);
            } else {
                $adr_fcst_arr = $adr_fcst_arr . ',' . str_replace(',', '', $adr_final1);
            }
            $adr_fsct_count++;
        }

        $bob_arr = '[' . $bob_arr . ']';
        $adr_arr = '[' . $adr_arr . ']';
        $bob_fcst_arr = '[' . $bob_fcst_arr . ']';
        $adr_fcst_arr = '[' . $adr_fcst_arr . ']';
        $date_arr = '[' . $date_arr . ']';

        $this->set('bob_arr', $bob_arr);
        $this->set('adr_arr', $adr_arr);
        $this->set('bob_fcst_arr', $bob_fcst_arr);
        $this->set('adr_fcst_arr', $adr_fcst_arr);
        $this->set('date_arr', $date_arr);
        $this->set('hotelname', $hotelname);
    }

    public function admin_calendar($department_id = null) {

        $sheetIds = array();
        for ($i = '0'; $i <= '5'; $i++) {
            $year = date('Y');
            $month = date('m');
            $month = $month + $i;
            if ($month > '12') {
                $month = $month - '12';
                $year = $year + '1';
            }
            $monthSheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' => $year, 'Sheet.month' => $month, 'Sheet.department_id' => $department_id), 'fields' => array('Sheet.id'), 'recursive' => '0'));
            $sheetIds[$i] = $monthSheet['Sheet']['id'];
        }
        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.id' => $sheetIds), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.year', 'Sheet.month', 'Sheet.name', 'User.id'), 'recursive' => '0', 'order' => array('month' => 'ASC', 'year' => 'ASC')));

        $this->Department = ClassRegistry::init('Department');
        $this->User = ClassRegistry::init('User');

        $clientFrmDetpId = $this->Department->find('first', array('fields' => array('client_id', 'name'), 'conditions' => array('Department.id' => $department_id)));
        $userdata = $this->User->find('first', array('fields' => array('Client.hotelname'), 'conditions' => array('User.client_id' => $clientFrmDetpId['Department']['client_id'], 'User.status !=' => 2), 'recursive' => '0'));

        $this->set('department_name', $clientFrmDetpId['Department']['name']);
        $this->set('department_id', $department_id);
        $this->set('all_sheets', $all_sheets);
        $this->set('user_data', $userdata);
        $this->set('client_id', $clientFrmDetpId['Department']['client_id']);

        $sdata = $this->Sheet->findById($all_sheets[0]['Sheet']['id']);
        $columnIds = Set::extract('/id', $sdata['Column']);
        $columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2, 'id' => $columnIds), 'fields' => array('Column.name', 'Column.name'), 'recursive' => '0'));
        $this->set('columns', $columns);
    }

    public function client_calendar($department_id = null) {

        $sheetIds = array();
        for ($i = '0'; $i <= '5'; $i++) {
            $year = date('Y');
            $month = date('m');
            $month = $month + $i;
            if ($month > '12') {
                $month = $month - '12';
                $year = $year + '1';
            }
            $monthSheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' => $year, 'Sheet.month' => $month, 'Sheet.department_id' => $department_id), 'fields' => array('Sheet.id'), 'recursive' => '0'));
            $sheetIds[$i] = $monthSheet['Sheet']['id'];
        }
        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.id' => $sheetIds), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.year', 'Sheet.month', 'Sheet.name', 'User.id'), 'recursive' => '0', 'order' => array('month' => 'ASC', 'year' => 'ASC')));

        $this->Department = ClassRegistry::init('Department');
        $this->User = ClassRegistry::init('User');

        $clientFrmDetpId = $this->Department->find('first', array('fields' => array('client_id', 'name'), 'conditions' => array('Department.id' => $department_id)));
        $userdata = $this->User->find('first', array('fields' => array('Client.hotelname'), 'conditions' => array('User.client_id' => $clientFrmDetpId['Department']['client_id'], 'User.status !=' => 2), 'recursive' => '0'));

        $this->set('department_name', $clientFrmDetpId['Department']['name']);
        $this->set('department_id', $department_id);
        $this->set('all_sheets', $all_sheets);
        $this->set('user_data', $userdata);
        $this->set('client_id', $clientFrmDetpId['Department']['client_id']);

        $sdata = $this->Sheet->findById($all_sheets[0]['Sheet']['id']);
        $columnIds = Set::extract('/id', $sdata['Column']);
        $columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2, 'id' => $columnIds), 'fields' => array('Column.name', 'Column.name'), 'recursive' => '0'));
        $this->set('columns', $columns);
    }

    function admin_import_protel_gb($sheetId) {

        $this->layout = 'default';
        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_protel_gb/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {
                $sheet_data = $this->Sheet->find('first', array('conditions' => array('Sheet.id' => $sheetId), 'recursive' => '0'));
                $sheet_data['Sheet']['month'] = sprintf('%02d', $sheet_data['Sheet']['month']);
                $s_month = $sheet_data['Sheet']['month'];
                $s_year = $sheet_data['Sheet']['year'];

                $check_date = $s_month . '/' . $s_year;

                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $count_data = '1';
                $new_array = array();
                foreach ($ndata[0]['cells'] as $chk_data) {
                    if (!empty($chk_data[1])) {

                        if (strstr($chk_data[1], 'Summe für')) {
                            $unset_till = $count_data;
                            $cols[$count_data]['BOB'] = $chk_data[4];
                            $cols[$count_data]['ADR'] = $chk_data[7];
                            $replace_array = array('Summe für  Mo.', 'Summe für  Di.', 'Summe für  Mi.', 'Summe für  Do.', 'Summe für  Fr.', 'Summe für  Sa.', 'Summe für  So.');
                            $exlode_date = str_replace($replace_array, '', $chk_data[1]);
                            $date_col = trim($exlode_date);
                            $cols[$count_data]['Date'] = $date_col;
                            $count_data++;
                        }
                    }
                }
                $sheet_data_values = $this->Sheet->getWebformData($sheetId);

                if (!empty($cols)) {
                    $i = 1;
                    foreach ($cols as $data) {
                        $date_details = date('d/m/y', strtotime($data['Date']));
                        $this->Sheet->contain(array('Column'));
                        $sdata = $this->Sheet->findById($sheetId);
                        //$columnIds = Set::extract('/id', $sdata['Column']);
                        $columns = Set::extract('/name', $sdata['Column']);
                        $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                        //$expl = explode('/', $date_details);
                        //$new_data[$row]['id'] = $expl[0];
                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data['BOB'];
                        $new_data[$row]['ADR'] = $data['BOB'];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data_values as $sheets_d) {
                                    if (($sheets_d['Date'] == $date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }
                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'admin_import_protel_gb', $sheetId));
                        }
                        $i++;
                    } //foreach ends here

                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('Protel imported successfully.', true));
                    $this->redirect(array('action' => 'admin_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function staff_import_protel_gb($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_protel_gb/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {
                $sheet_data = $this->Sheet->find('first', array('conditions' => array('Sheet.id' => $sheetId), 'recursive' => '0'));
                $sheet_data['Sheet']['month'] = sprintf('%02d', $sheet_data['Sheet']['month']);
                $s_month = $sheet_data['Sheet']['month'];
                $s_year = $sheet_data['Sheet']['year'];

                $check_date = $s_month . '/' . $s_year;

                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $count_data = '1';
                $new_array = array();
                foreach ($ndata[0]['cells'] as $chk_data) {
                    if (!empty($chk_data[1])) {

                        if (strstr($chk_data[1], 'Summe für')) {
                            $unset_till = $count_data;
                            $cols[$count_data]['BOB'] = $chk_data[4];
                            $cols[$count_data]['ADR'] = $chk_data[7];
                            $replace_array = array('Summe für  Mo.', 'Summe für  Di.', 'Summe für  Mi.', 'Summe für  Do.', 'Summe für  Fr.', 'Summe für  Sa.', 'Summe für  So.');
                            $exlode_date = str_replace($replace_array, '', $chk_data[1]);
                            $date_col = trim($exlode_date);
                            $cols[$count_data]['Date'] = $date_col;
                            $count_data++;
                        }
                    }
                }
                $sheet_data_values = $this->Sheet->getWebformData($sheetId);

                if (!empty($cols)) {
                    $i = 1;
                    foreach ($cols as $data) {
                        $date_details = date('d/m/y', strtotime($data['Date']));
                        $this->Sheet->contain(array('Column'));
                        $sdata = $this->Sheet->findById($sheetId);
                        //$columnIds = Set::extract('/id', $sdata['Column']);
                        $columns = Set::extract('/name', $sdata['Column']);
                        $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data['BOB'];
                        $new_data[$row]['ADR'] = $data['BOB'];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data_values as $sheets_d) {
                                    if (($sheets_d['Date'] == $date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }
                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'staff_import_protel_gb', $sheetId));
                        }
                        $i++;
                    } //foreach ends here

                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('Protel imported successfully.', true));
                    $this->redirect(array('action' => 'staff_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function client_import_protel_gb($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_protel_gb/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {
                $sheet_data = $this->Sheet->find('first', array('conditions' => array('Sheet.id' => $sheetId), 'recursive' => '0'));
                $sheet_data['Sheet']['month'] = sprintf('%02d', $sheet_data['Sheet']['month']);
                $s_month = $sheet_data['Sheet']['month'];
                $s_year = $sheet_data['Sheet']['year'];

                $check_date = $s_month . '/' . $s_year;

                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $count_data = '1';
                $new_array = array();
                foreach ($ndata[0]['cells'] as $chk_data) {
                    if (!empty($chk_data[1])) {

                        if (strstr($chk_data[1], 'Summe für')) {
                            $unset_till = $count_data;
                            $cols[$count_data]['BOB'] = $chk_data[4];
                            $cols[$count_data]['ADR'] = $chk_data[7];
                            $replace_array = array('Summe für  Mo.', 'Summe für  Di.', 'Summe für  Mi.', 'Summe für  Do.', 'Summe für  Fr.', 'Summe für  Sa.', 'Summe für  So.');
                            $exlode_date = str_replace($replace_array, '', $chk_data[1]);
                            $date_col = trim($exlode_date);
                            $cols[$count_data]['Date'] = $date_col;
                            $count_data++;
                        }
                    }
                }
                $sheet_data_values = $this->Sheet->getWebformData($sheetId);

                if (!empty($cols)) {
                    $i = 1;
                    foreach ($cols as $data) {
                        $date_details = date('d/m/y', strtotime($data['Date']));
                        $this->Sheet->contain(array('Column'));
                        $sdata = $this->Sheet->findById($sheetId);
                        $columns = Set::extract('/name', $sdata['Column']);
                        $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data['BOB'];
                        $new_data[$row]['ADR'] = $data['BOB'];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data_values as $sheets_d) {
                                    if (($sheets_d['Date'] == $date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }
                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'client_import_protel_gb', $sheetId));
                        }
                        $i++;
                    } //foreach ends here

                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('Protel imported successfully.', true));
                    $this->redirect(array('action' => 'client_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    public function staff_copy_column($sheetId = null) {
        $this->layout = 'default';
        $sdata = $this->Sheet->findById($sheetId);
        $sheet_name = $sdata['Sheet']['name'];
        $user_id = $sdata['Sheet']['user_id'];
        $client_id = $sdata['User']['client_id'];
        $user_fullname = $sdata['User']['fullname'];

        $depts_obj = ClassRegistry::init('DepartmentsUser');
        $department = $depts_obj->field('department_name', array('DepartmentsUser.department_id' => $sdata['Sheet']['department_id']));
        $this->set('department', $department);

        App::import('Model', 'Department');
        $this->Department = new Department();
        $this->Department->recursive = -1;
        $deparments = $this->Department->find('list', array('conditions' => array('Department.client_id' => $client_id, 'Department.status' => 1)));

        $columnIds = Set::extract('/id', $sdata['Column']);
        $sheet_columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2, 'id' => $columnIds), 'fields' => array('Column.id', 'Column.name'), 'recursive' => '0'));
        $columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2), 'fields' => array('Column.name', 'Column.name'), 'recursive' => '0'));

        $this->set('user_id', $user_id);
        $this->set('client_id', $client_id);
        $this->set('sheetId', $sheetId);
        $this->set(compact('sdata', 'columns', 'sheet_columns', 'deparments'));

        if (!empty($this->data)) {

            $datas_obj = ClassRegistry::init('Datum');
            $datas_data = $datas_obj->find('all', array('conditions' => array('sheet_id' => $this->data['Sheet']['sheet_id'], 'column_id' => $this->data['Sheet']['column_from'], 'Datum.date !=' => '0'), 'fields' => array('Datum.date', 'Datum.value'), 'order' => array('Datum.date ASC')));
            $month = $this->data['Sheet']['month'];

            $conditions = array('Sheet.department_id' => $this->data['Sheet']['department'], 'Sheet.month' => $this->data['Sheet']['month'], 'Sheet.year' => $this->data['Sheet']['year'], 'Sheet.status' => 1);
            $userSheets = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => array('Sheet.id'), 'recursive' => -1));

            if (!empty($userSheets)) {
                $new_sheetId = $userSheets[0]['Sheet']['id'];
                $i = '1';
                $new_arr = array();
                $sdata = $this->Sheet->findById($new_sheetId);
                $columns = Set::extract('/name', $sdata['Column']);

                foreach ($datas_data as $data_val) {
                    unset($new_arr);
                    $new_arr[$i]['id'] = $data_val['Datum']['date'];
                    $new_arr[$i]['Date'] = $data_val['Datum']['date'] . '/' . $month . '/' . date("y", strtotime($this->data['Sheet']['year']));
                    $new_arr[$i]['sheetId'] = $new_sheetId;

                    foreach ($columns as $ckey => $col) {
                        foreach ($sdata['Datum'] as $dkey => $datum) {
                            if (($datum['sheet_id'] == $new_sheetId) && ($datum['column_id'] == $sdata['Column'][$ckey]['id']) && ($datum['date'] == $data_val['Datum']['date']) && ($datum['row_id'] == 0)) {
                                $new_arr[$i][$col] = $datum['value'];
                            }
                        }
                    }
                    $new_arr[$i][$this->data['Sheet']['column_to']] = $data_val['Datum']['value'];
                    $this->Sheet->importData($new_sheetId, $new_arr[$i]);
                    $i++;
                    $error = '0';
                }
            } else {
                $error = '1';
            }
            if ($error == 0) {
                $this->Session->setFlash(__('The Department sheet Column was updated successfully', true));
                $this->redirect(array('action' => 'staff_index'));
            } else {
                $this->Session->setFlash(__('Sheet not found.', true));
                $this->redirect($this->referer());
            }
        }
    }

    function admin_import_excel_barbados($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_excel_barbados/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $date_col = '1';
                unset($ndata[0]['cells'][1], $ndata[0]['cells'][2]);

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                foreach ($ndata[0]['cells'] as $data) {
                    if (!empty($data[$date_col])) {
                        $date_exp = explode('/', trim($data[$date_col]));
                        $date_details = $date_exp[1] . '/' . $date_exp[0] . '/' . substr($date_exp[2], -2);
                        $day_id = $date_exp[1];
                    } else {
                        $date_details = '';
                    }

                    if (!empty($date_details) && $date_details != 'Totals:' && $date_details != 'Daily Breakdown  ~ ' && $day_id != '') {

                        $new_data[$row]['id'] = $day_id;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $explode_revenue = explode(']', $data['4']);
                        $explode_revenue['1'] = str_replace(",", "", $explode_revenue['1']);
                        $new_data[$row]['BOB'] = $data['3'];
                        $new_data[$row]['ADR'] = round($explode_revenue['1'] / $data['3'], '2');  //ADR = Revenue/BOB

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'admin_import_excel_barbados', $sheetId));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                fclose($handle);
                $this->Session->setFlash(__('Excel imported successfully.', true));
                $this->redirect(array('action' => 'admin_webform', $sheetId));
            }
        }
        $this->set(get_defined_vars());
    }

    function client_import_excel_barbados($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_excel_barbados/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $date_col = '1';
                unset($ndata[0]['cells'][1], $ndata[0]['cells'][2]);

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                foreach ($ndata[0]['cells'] as $data) {
                    if (!empty($data[$date_col])) {
                        $date_exp = explode('/', trim($data[$date_col]));
                        $date_details = $date_exp[1] . '/' . $date_exp[0] . '/' . substr($date_exp[2], -2);
                        $day_id = $date_exp[1];
                    } else {
                        $date_details = '';
                    }

                    if (!empty($date_details) && $date_details != 'Totals:' && $date_details != 'Daily Breakdown  ~ ' && $day_id != '') {

                        $new_data[$row]['id'] = $day_id;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $explode_revenue = explode(']', $data['4']);
                        $explode_revenue['1'] = str_replace(",", "", $explode_revenue['1']);
                        $new_data[$row]['BOB'] = $data['3'];
                        $new_data[$row]['ADR'] = round($explode_revenue['1'] / $data['3'], '2');  //ADR = Revenue/BOB

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'client_import_excel_barbados', $sheetId));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                fclose($handle);
                $this->Session->setFlash(__('Excel imported successfully.', true));
                $this->redirect(array('action' => 'client_webform', $sheetId));
            }
        }
        $this->set(get_defined_vars());
    }

    function staff_import_excel_barbados($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_excel_barbados/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $date_col = '1';
                unset($ndata[0]['cells'][1], $ndata[0]['cells'][2]);

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                foreach ($ndata[0]['cells'] as $data) {
                    if (!empty($data[$date_col])) {
                        $date_exp = explode('/', trim($data[$date_col]));
                        $date_details = $date_exp[1] . '/' . $date_exp[0] . '/' . substr($date_exp[2], -2);
                        $day_id = $date_exp[1];
                    } else {
                        $date_details = '';
                    }

                    if (!empty($date_details) && $date_details != 'Totals:' && $date_details != 'Daily Breakdown  ~ ' && $day_id != '') {

                        $new_data[$row]['id'] = $day_id;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $explode_revenue = explode(']', $data['4']);
                        $explode_revenue['1'] = str_replace(",", "", $explode_revenue['1']);
                        $new_data[$row]['BOB'] = $data['3'];
                        $new_data[$row]['ADR'] = round($explode_revenue['1'] / $data['3'], '2');  //ADR = Revenue/BOB

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'staff_import_excel_barbados', $sheetId));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                fclose($handle);
                $this->Session->setFlash(__('Excel imported successfully.', true));
                $this->redirect(array('action' => 'staff_webform', $sheetId));
            }
        }
        $this->set(get_defined_vars());
    }

    public function staff_calendar($department_id = null) {

        $sheetIds = array();
        for ($i = '0'; $i <= '5'; $i++) {
            $year = date('Y');
            $month = date('m');
            $month = $month + $i;
            if ($month > '12') {
                $month = $month - '12';
                $year = $year + '1';
            }
            $monthSheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' => $year, 'Sheet.month' => $month, 'Sheet.department_id' => $department_id), 'fields' => array('Sheet.id'), 'recursive' => '0'));
            $sheetIds[$i] = $monthSheet['Sheet']['id'];
        }
        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.id' => $sheetIds), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.year', 'Sheet.month', 'Sheet.name', 'User.id'), 'recursive' => '0', 'order' => array('month' => 'ASC', 'year' => 'ASC')));

        $this->Department = ClassRegistry::init('Department');
        $this->User = ClassRegistry::init('User');
        $clientFrmDetpId = $this->Department->find('first', array('fields' => array('client_id', 'name'), 'conditions' => array('Department.id' => $department_id)));
        $userdata = $this->User->find('first', array('fields' => array('Client.hotelname'), 'conditions' => array('User.client_id' => $clientFrmDetpId['Department']['client_id'], 'User.status !=' => 2), 'recursive' => '0'));

        $this->set('department_name', $clientFrmDetpId['Department']['name']);
        $this->set('department_id', $department_id);
        $this->set('all_sheets', $all_sheets);
        $this->set('user_data', $userdata);
        $this->set('client_id', $clientFrmDetpId['Department']['client_id']);

        $sdata = $this->Sheet->findById($all_sheets[0]['Sheet']['id']);
        $columnIds = Set::extract('/id', $sdata['Column']);
        $columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2, 'id' => $columnIds), 'fields' => array('Column.name', 'Column.name'), 'recursive' => '0'));
        $this->set('columns', $columns);
    }

    public function admin_copy_column($sheetId = null) {
        $this->layout = 'default';

        $sdata = $this->Sheet->findById($sheetId);
        $sheet_name = $sdata['Sheet']['name'];
        $user_id = $sdata['Sheet']['user_id'];
        $client_id = $sdata['User']['client_id'];
        $user_fullname = $sdata['User']['fullname'];

        $depts_obj = ClassRegistry::init('DepartmentsUser');
        $department = $depts_obj->field('department_name', array('DepartmentsUser.department_id' => $sdata['Sheet']['department_id']));
        $this->set('department', $department);

        App::import('Model', 'Department');
        $this->Department = new Department();
        $this->Department->recursive = -1;
        $deparments = $this->Department->find('list', array('conditions' => array('Department.client_id' => $client_id, 'Department.status' => 1)));

        $columnIds = Set::extract('/id', $sdata['Column']);
        $sheet_columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2, 'id' => $columnIds), 'fields' => array('Column.id', 'Column.name'), 'recursive' => '0'));
        $columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2), 'fields' => array('Column.name', 'Column.name'), 'recursive' => '0'));

        $this->set('user_id', $user_id);
        $this->set('client_id', $client_id);
        $this->set('sheetId', $sheetId);
        $this->set(compact('sdata', 'columns', 'sheet_columns', 'deparments'));

        if (!empty($this->data)) {

            $datas_obj = ClassRegistry::init('Datum');
            $datas_data = $datas_obj->find('all', array('conditions' => array('sheet_id' => $this->data['Sheet']['sheet_id'], 'column_id' => $this->data['Sheet']['column_from'], 'Datum.date !=' => '0'), 'fields' => array('Datum.date', 'Datum.value'), 'order' => array('Datum.date ASC')));
            $month = $this->data['Sheet']['month'];

            $conditions = array('Sheet.department_id' => $this->data['Sheet']['department'], 'Sheet.month' => $this->data['Sheet']['month'], 'Sheet.year' => $this->data['Sheet']['year'], 'Sheet.status' => 1);
            $userSheets = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => array('Sheet.id'), 'recursive' => -1));

            if (!empty($userSheets)) {
                $new_sheetId = $userSheets[0]['Sheet']['id'];
                $i = '1';
                $new_arr = array();
                $sdata = $this->Sheet->findById($new_sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                foreach ($datas_data as $data_val) {
                    unset($new_arr);
                    $new_arr[$i]['id'] = $data_val['Datum']['date'];
                    $new_arr[$i]['Date'] = $data_val['Datum']['date'] . '/' . $month . '/' . date("y", strtotime($this->data['Sheet']['year']));
                    $new_arr[$i]['sheetId'] = $new_sheetId;

                    foreach ($columns as $ckey => $col) {
                        foreach ($sdata['Datum'] as $dkey => $datum) {
                            if (($datum['sheet_id'] == $new_sheetId) && ($datum['column_id'] == $sdata['Column'][$ckey]['id']) && ($datum['date'] == $data_val['Datum']['date']) && ($datum['row_id'] == 0)) {
                                $new_arr[$i][$col] = $datum['value'];
                            }
                        }
                    }
                    $new_arr[$i][$this->data['Sheet']['column_to']] = $data_val['Datum']['value'];
                    $this->Sheet->importWebform($new_sheetId, $new_arr[$i]);
                    $i++;
                    $error = '0';
                }
                $this->Sheet->updateRowsTotal($new_sheetId);
            } else {
                $error = '1';
            }
            if ($error == 0) {
                $this->Session->setFlash(__('The Department sheet Column was updated successfully', true));
                $this->redirect('/admin/users');
            } else {
                $this->Session->setFlash(__('Sheet not found.', true));
                $this->redirect($this->referer());
            }
        }
    }

    public function client_copy_column($sheetId = null) {
        $this->layout = 'default';
        $sdata = $this->Sheet->findById($sheetId);

        $sheet_name = $sdata['Sheet']['name'];
        $user_id = $sdata['Sheet']['user_id'];
        $client_id = $sdata['User']['client_id'];
        $user_fullname = $sdata['User']['fullname'];

        $depts_obj = ClassRegistry::init('DepartmentsUser');
        $department = $depts_obj->field('department_name', array('DepartmentsUser.department_id' => $sdata['Sheet']['department_id']));
        $this->set('department', $department);

        App::import('Model', 'Department');
        $this->Department = new Department();
        $this->Department->recursive = -1;
        $deparments = $this->Department->find('list', array('conditions' => array('Department.client_id' => $client_id, 'Department.status' => 1)));

        $columnIds = Set::extract('/id', $sdata['Column']);
        $sheet_columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2, 'id' => $columnIds), 'fields' => array('Column.id', 'Column.name'), 'recursive' => '0'));
        $columns = $this->Sheet->Column->find('list', array('conditions' => array('Column.status !=' => 2), 'fields' => array('Column.name', 'Column.name'), 'recursive' => '0'));

        $this->set('user_id', $user_id);
        $this->set('client_id', $client_id);
        $this->set('sheetId', $sheetId);
        $this->set(compact('sdata', 'columns', 'sheet_columns', 'deparments'));

        if (!empty($this->data)) {
            $datas_obj = ClassRegistry::init('Datum');
            $datas_data = $datas_obj->find('all', array('conditions' => array('sheet_id' => $this->data['Sheet']['sheet_id'], 'column_id' => $this->data['Sheet']['column_from'], 'Datum.date !=' => '0'), 'fields' => array('Datum.date', 'Datum.value'), 'order' => array('Datum.date ASC')));
            $month = $this->data['Sheet']['month'];
            $conditions = array('Sheet.department_id' => $this->data['Sheet']['department'], 'Sheet.month' => $this->data['Sheet']['month'], 'Sheet.year' => $this->data['Sheet']['year'], 'Sheet.status' => 1);
            $userSheets = $this->Sheet->find('all', array('conditions' => $conditions, 'fields' => array('Sheet.id'), 'recursive' => -1));

            if (!empty($userSheets)) {
                $new_sheetId = $userSheets[0]['Sheet']['id'];
                $i = '1';
                $new_arr = array();
                $sdata = $this->Sheet->findById($new_sheetId);
                $columns = Set::extract('/name', $sdata['Column']);

                foreach ($datas_data as $data_val) {
                    unset($new_arr);
                    $new_arr[$i]['id'] = $data_val['Datum']['date'];
                    $new_arr[$i]['Date'] = $data_val['Datum']['date'] . '/' . $month . '/' . date("y", strtotime($this->data['Sheet']['year']));
                    $new_arr[$i]['sheetId'] = $new_sheetId;

                    foreach ($columns as $ckey => $col) {
                        foreach ($sdata['Datum'] as $dkey => $datum) {
                            if (($datum['sheet_id'] == $new_sheetId) && ($datum['column_id'] == $sdata['Column'][$ckey]['id']) && ($datum['date'] == $data_val['Datum']['date']) && ($datum['row_id'] == 0)) {
                                $new_arr[$i][$col] = $datum['value'];
                            }
                        }
                    }
                    $new_arr[$i][$this->data['Sheet']['column_to']] = $data_val['Datum']['value'];

                    $this->Sheet->importWebform($new_sheetId, $new_arr[$i]);
                    $i++;
                    $error = '0';
                }
                $this->Sheet->updateRowsTotal($new_sheetId);
            } else {
                $error = '1';
            }
            if ($error == 0) {
                $this->Session->setFlash(__('The Department sheet Column was updated successfully', true));
                $this->redirect('/client/departments/list');
            } else {
                $this->Session->setFlash(__('Sheet not found.', true));
                $this->redirect($this->referer());
            }
        }
    }

    public function import_file_validation($type = null, $name = null, $tmp_name = null, $prefix = null, $redirect_url = null, $sheetId = null) {

        $this->layout = false;
        $this->autoRender = false;

        if (!$name) {
            $this->Session->setFlash(__('Please uploaded file!', true));
            $this->redirect(array('prefix' => $prefix, $prefix => true, 'action' => $redirect_url, $sheetId));
        }

        $path_parts = pathinfo($name);
        $extension = $path_parts['extension'];

        if ($type == 'text') {
            if ($extension != 'txt' && $extension != 'TXT') {
                $this->Session->setFlash(__('Please uploaded excel(.txt) file!', true));
                $this->redirect(array('prefix' => $prefix, $prefix => true, 'action' => $redirect_url, $sheetId));
            }
        } else if ($type == 'csv' && $extension != 'CSV') {
            if ($extension != 'csv') {
                $this->Session->setFlash(__('Please uploaded csv file!', true));
                $this->redirect(array('prefix' => $prefix, $prefix => true, 'action' => $redirect_url, $sheetId));
            }
        } else if ($type == 'excel') {
            if ($extension != 'xls' && $extension != 'Xls' && $extension != 'XLS') {
                $this->Session->setFlash(__('Please uploaded excel(.xls) file!', true));
                $this->redirect(array('prefix' => $prefix, $prefix => true, 'action' => $redirect_url, $sheetId));
            }
        }
        $tmp_name = urldecode($tmp_name);
        $handle = fopen($tmp_name, 'r');
        if (!$handle) {
            $this->Session->setFlash(__('Cannot open uploaded file!', true));
            $this->redirect(array('prefix' => $prefix, $prefix => true, 'action' => $redirect_url, $sheetId));
        }
        return $handle;
    }

    function admin_import_4ccsv($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_csv/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Occupied', $pdata);
                $cols['ADR'] = array_search('ADR', $pdata);
                $cols['Revenue'] = array_search('RoomRev', $pdata);
                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['Revenue']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                    $department_id = $sdata['Sheet']['department_id'];

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];
                    $today_str_date = strtotime(date("Y-m-d"));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['Revenue']]) && !empty($data[$date_col])) {

                            if (strstr($data[$date_col], '-')) {
                                $exp_date = explode('-', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            } else {
                                $exp_date = explode('/', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            }
                            $sheet_str_date = $sheet_year . '-' . $sheet_month . '-' . $exp_date[0];

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $data[$cols['Revenue']] = str_replace(',', '', $data[$cols['Revenue']]);

                            if ((strtotime($sheet_str_date) >= $today_str_date)) {
                                //deduct 20% from Revenue for today and all future dates
                                //$ADR_vat = (($data[$cols['Revenue']])*0.2);
                                //$new_data[$row]['Revenue'] = $data[$cols['Revenue']] - $ADR_vat; //Revenue
                                $new_data[$row]['Revenue'] = $data[$cols['Revenue']] / '1.2';
                            } else {
                                $new_data[$row]['Revenue'] = $data[$cols['Revenue']];
                            }

                            $new_data[$row]['ADR'] = $new_data[$row]['Revenue'] / $data[$cols['BOB']]; //ADR

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR' && $col != 'Revenue') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'admin_import_4ccsv', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'admin_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function client_import_4ccsv($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_csv/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Occupied', $pdata);
                $cols['ADR'] = array_search('ADR', $pdata);
                $cols['Revenue'] = array_search('RoomRev', $pdata);
                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['Revenue']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                    $department_id = $sdata['Sheet']['department_id'];

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];
                    $today_str_date = strtotime(date("Y-m-d"));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['Revenue']]) && !empty($data[$date_col])) {
                            if (strstr($data[$date_col], '-')) {
                                $exp_date = explode('-', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            } else {
                                $exp_date = explode('/', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            }
                            $sheet_str_date = $sheet_year . '-' . $sheet_month . '-' . $exp_date[0];

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $data[$cols['Revenue']] = str_replace(',', '', $data[$cols['Revenue']]);

                            if ((strtotime($sheet_str_date) >= $today_str_date)) {
                                //deduct 20% from Revenue for today and all future dates
                                //$ADR_vat = (($data[$cols['Revenue']])*0.2);
                                //$new_data[$row]['Revenue'] = $data[$cols['Revenue']] - $ADR_vat; //Revenue
                                $new_data[$row]['Revenue'] = $data[$cols['Revenue']] / '1.2';
                            } else {
                                $new_data[$row]['Revenue'] = $data[$cols['Revenue']];
                            }

                            $new_data[$row]['ADR'] = $new_data[$row]['Revenue'] / $data[$cols['BOB']]; //ADR

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR' && $col != 'Revenue') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'client_import_4ccsv', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'client_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function staff_import_4ccsv($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_csv/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Occupied', $pdata);
                $cols['ADR'] = array_search('ADR', $pdata);
                $cols['Revenue'] = array_search('RoomRev', $pdata);
                //$date_col = $pdata['0'];
                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['Revenue']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                    $department_id = $sdata['Sheet']['department_id'];

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];
                    $today_str_date = strtotime(date("Y-m-d"));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['Revenue']]) && !empty($data[$date_col])) {
                            if (strstr($data[$date_col], '-')) {
                                $exp_date = explode('-', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            } else {
                                $exp_date = explode('/', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            }
                            $sheet_str_date = $sheet_year . '-' . $sheet_month . '-' . $exp_date[0];

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $data[$cols['Revenue']] = str_replace(',', '', $data[$cols['Revenue']]);

                            if ((strtotime($sheet_str_date) >= $today_str_date)) {
                                //deduct 20% from Revenue for today and all future dates
                                // $ADR_vat = (($data[$cols['Revenue']])*0.2);
                                // $new_data[$row]['Revenue'] = $data[$cols['Revenue']] - $ADR_vat; //Revenue
                                $new_data[$row]['Revenue'] = $data[$cols['Revenue']] / '1.2';
                            } else {
                                $new_data[$row]['Revenue'] = $data[$cols['Revenue']];
                            }

                            $new_data[$row]['ADR'] = $new_data[$row]['Revenue'] / $data[$cols['BOB']]; //ADR

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR' && $col != 'Revenue') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'staff_import_4ccsv', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'staff_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_4ccsv($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {
            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_csv/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Occupied', $pdata);
                $cols['ADR'] = array_search('ADR', $pdata);
                $cols['Revenue'] = array_search('RoomRev', $pdata);
                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['Revenue']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                    $department_id = $sdata['Sheet']['department_id'];

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];
                    $today_str_date = strtotime(date("Y-m-d"));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['Revenue']]) && !empty($data[$date_col])) {

                            if (strstr($data[$date_col], '-')) {
                                $exp_date = explode('-', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            } else {
                                $exp_date = explode('/', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            }
                            $sheet_str_date = $sheet_year . '-' . $sheet_month . '-' . $exp_date[0];
                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $data[$cols['Revenue']] = str_replace(',', '', $data[$cols['Revenue']]);

                            if ((strtotime($sheet_str_date) >= $today_str_date)) {
                                //deduct 20% from Revenue for today and all future dates
                                //$ADR_vat = (($data[$cols['Revenue']])*0.2);
                                //$new_data[$row]['Revenue'] = $data[$cols['Revenue']] - $ADR_vat; //Revenue
                                $new_data[$row]['Revenue'] = $data[$cols['Revenue']] / '1.2';
                            } else {
                                $new_data[$row]['Revenue'] = $data[$cols['Revenue']];
                            }

                            $new_data[$row]['ADR'] = $new_data[$row]['Revenue'] / $data[$cols['BOB']]; //ADR

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR' && $col != 'Revenue') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function admin_import_luckname($sheetId) {

        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_luckname/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {

                $cols['BOB'] = '5';
                $cols['BOB-subs'] = '4';
                $cols['ADR'] = '12';
                $date_col = '0';

                $sheet_data = $this->Sheet->getWebformData($sheetId);

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                $department_id = $sdata['Sheet']['department_id'];

                $sheet_month = $sdata['Sheet']['month'];
                $sheet_year = $sdata['Sheet']['year'];
                $today_str_date = strtotime(date("Y-m-d"));

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                    if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date')) {
                        $exp_date[0] = substr($data[$date_col], 0, -4);

                        $date_details = date('d/m/y', strtotime($exp_date[0]));
                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $bob = trim($data[$cols['BOB']]) == '-' ? '0' : $data[$cols['BOB']];
                        $new_data[$row]['BOB'] = $bob;

                        $new_data[$row]['ADR'] = trim($data[$cols['ADR']]) == '-' ? '0' : $data[$cols['ADR']];


                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'admin_import_luckname', $sheetId));
                        }
                    }
                }

                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                $this->Session->setFlash(__('CSV imported successfully.', true));
                $this->redirect(array('action' => 'admin_webform', $sheetId));
            } else {
                $this->redirect(array('action' => 'admin_import_luckname', $sheetId));
            }
            fclose($handle);
        }
        $this->set(get_defined_vars());
    }

    function client_import_luckname($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/client/import_luckname/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {

                $cols['BOB'] = '5';
                $cols['BOB-subs'] = '4';
                $cols['ADR'] = '12';
                $date_col = '0';

                $sheet_data = $this->Sheet->getWebformData($sheetId);

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                $department_id = $sdata['Sheet']['department_id'];

                $sheet_month = $sdata['Sheet']['month'];
                $sheet_year = $sdata['Sheet']['year'];
                $today_str_date = strtotime(date("Y-m-d"));

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                    if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date')) {

                        $exp_date[0] = substr($data[$date_col], 0, -4);
                        $date_details = date('d/m/y', strtotime($exp_date[0]));
                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $bob = trim($data[$cols['BOB']]) == '-' ? '0' : $data[$cols['BOB']];
                        $new_data[$row]['BOB'] = $bob;
                        $new_data[$row]['ADR'] = trim($data[$cols['ADR']]) == '-' ? '0' : $data[$cols['ADR']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'client_import_luckname', $sheetId));
                        }
                    }
                }

                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                $this->Session->setFlash(__('CSV imported successfully.', true));
                $this->redirect(array('action' => 'client_webform', $sheetId));
            } else {
                $this->redirect(array('action' => 'client_import_luckname', $sheetId));
            }
            fclose($handle);
        }
        $this->set(get_defined_vars());
    }

    function staff_import_luckname($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/staff/import_luckname/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {

                $cols['BOB'] = '5';
                $cols['BOB-subs'] = '4';
                $cols['ADR'] = '12';
                $date_col = '0';

                $sheet_data = $this->Sheet->getWebformData($sheetId);

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                $department_id = $sdata['Sheet']['department_id'];

                $sheet_month = $sdata['Sheet']['month'];
                $sheet_year = $sdata['Sheet']['year'];
                $today_str_date = strtotime(date("Y-m-d"));

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                    if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date')) {

                        $exp_date[0] = substr($data[$date_col], 0, -4);
                        $date_details = date('d/m/y', strtotime($exp_date[0]));

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $bob = trim($data[$cols['BOB']]) == '-' ? '0' : $data[$cols['BOB']];
                        $new_data[$row]['BOB'] = $bob;
                        $new_data[$row]['ADR'] = trim($data[$cols['ADR']]) == '-' ? '0' : $data[$cols['ADR']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'staff_import_luckname', $sheetId));
                        }
                    }
                }

                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                $this->Session->setFlash(__('CSV imported successfully.', true));
                $this->redirect(array('action' => 'staff_webform', $sheetId));
            } else {
                $this->redirect(array('action' => 'staff_import_luckname', $sheetId));
            }
            fclose($handle);
        }
        $this->set(get_defined_vars());
    }

    function admin_import_cie($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_cie/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('PP_RMS', $pdata);
                $cols['ADR'] = array_search('PP_ADR', $pdata);
                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                    $department_id = $sdata['Sheet']['department_id'];

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];
                    $today_str_date = strtotime(date("Y-m-d"));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col])) {

                            if (strstr($data[$date_col], '-')) {
                                $exp_date = explode('-', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            } else {
                                $exp_date = explode('/', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            }
                            $sheet_str_date = $sheet_year . '-' . $sheet_month . '-' . $exp_date[0];

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']];

                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'admin_import_cie', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'admin_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function client_import_cie($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/client/import_cie/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('PP_RMS', $pdata);
                $cols['ADR'] = array_search('PP_ADR', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                    $department_id = $sdata['Sheet']['department_id'];

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];
                    $today_str_date = strtotime(date("Y-m-d"));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col])) {

                            if (strstr($data[$date_col], '-')) {
                                $exp_date = explode('-', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            } else {
                                $exp_date = explode('/', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            }
                            $sheet_str_date = $sheet_year . '-' . $sheet_month . '-' . $exp_date[0];

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']];

                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'client_import_cie', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'client_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function staff_import_cie($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/staff/import_cie/' . $sheetId);

            $new_data = array();
            $row = 1;

            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('PP_RMS', $pdata);
                $cols['ADR'] = array_search('PP_ADR', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                    $department_id = $sdata['Sheet']['department_id'];

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];
                    $today_str_date = strtotime(date("Y-m-d"));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col])) {

                            if (strstr($data[$date_col], '-')) {
                                $exp_date = explode('-', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            } else {
                                $exp_date = explode('/', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            }
                            $sheet_str_date = $sheet_year . '-' . $sheet_month . '-' . $exp_date[0];

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']];

                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'staff_import_cie', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'staff_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_cie($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {
            //$year = date('Y');
            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_csv/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('PP_RMS', $pdata);
                $cols['ADR'] = array_search('PP_ADR', $pdata);
                //$cols['Revenue'] = array_search('PP_REV', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                    $department_id = $sdata['Sheet']['department_id'];

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];
                    $today_str_date = strtotime(date("Y-m-d"));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col])) {
                            //$num = count($data);

                            if (strstr($data[$date_col], '-')) {
                                $exp_date = explode('-', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            } else {
                                $exp_date = explode('/', $data[$date_col]);
                                $date_details = sprintf('%02d', $exp_date[0]) . '/' . sprintf('%02d', $exp_date[1]) . '/' . substr($exp_date[2], '-2');
                            }
                            $sheet_str_date = $sheet_year . '-' . $sheet_month . '-' . $exp_date[0];

                            //$new_data[$row]['id'] = $exp_date['1'];
                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;

                            $new_data[$row]['BOB'] = $data[$cols['BOB']];

                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                //$this->redirect(array('action' => 'hotel_import_cie', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function admin_import_simola($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_simola/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {

                $cols['BOB'] = '1';
                $cols['Revenue'] = '2';
                $date_col = '0';

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                $sheet_month = sprintf('%02d', $sheet_data['Sheet']['month']);
                $sheet_year = $sdata['Sheet']['year'];

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $day = explode(':', $data[$date_col]);
                    if (!empty($data[$cols['BOB']]) && !empty($data[$cols['Revenue']]) && !empty($day[1])) {

                        $date_details = $day[1] . '/' . $sheet_month . '/' . substr($sheet_year, '-2');

                        $new_data[$row]['id'] = $day[1];
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = round($data[$cols['Revenue']] / $data[$cols['BOB']], '2');  //ADR = Revenue/BOB
                        $new_data[$row]['Revenue'] = $data[$cols['Revenue']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR' && $col != 'Revenue') {
                                $new_data[$row][$col] = '0';
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'admin_import_simola', $sheetId));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                fclose($handle);
                $this->Session->setFlash(__('CSV imported successfully.', true));
                $this->redirect(array('action' => 'admin_webform', $sheetId));
            }
        }
        $this->set(get_defined_vars());
    }

    function client_import_simola($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/client/import_simola/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {

                $cols['BOB'] = '1';
                $cols['Revenue'] = '2';
                $date_col = '0';

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                //echo '<pre>'; print_r($columns);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                $sheet_month = sprintf('%02d', $sheet_data['Sheet']['month']);
                $sheet_year = $sdata['Sheet']['year'];

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $day = explode(':', $data[$date_col]);
                    if (!empty($data[$cols['BOB']]) && !empty($data[$cols['Revenue']]) && !empty($day[1])) {

                        $date_details = $day[1] . '/' . $sheet_month . '/' . substr($sheet_year, '-2');

                        $new_data[$row]['id'] = $day[1];
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = round($data[$cols['Revenue']] / $data[$cols['BOB']], '2');  //ADR = Revenue/BOB
                        $new_data[$row]['Revenue'] = $data[$cols['Revenue']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR' && $col != 'Revenue') {
                                $new_data[$row][$col] = '0';
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'client_import_simola', $sheetId));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                fclose($handle);
                $this->Session->setFlash(__('CSV imported successfully.', true));
                $this->redirect(array('action' => 'client_webform', $sheetId));
            }
        }
        $this->set(get_defined_vars());
    }

    function staff_import_simola($sheetId) {
        $this->layout = 'default';

        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/staff/import_simola/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {

                $cols['BOB'] = '1';
                $cols['Revenue'] = '2';
                $date_col = '0';

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                //echo '<pre>'; print_r($columns);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                $sheet_month = sprintf('%02d', $sheet_data['Sheet']['month']);
                $sheet_year = $sdata['Sheet']['year'];

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $day = explode(':', $data[$date_col]);
                    if (!empty($data[$cols['BOB']]) && !empty($data[$cols['Revenue']]) && !empty($day[1])) {

                        $date_details = $day[1] . '/' . $sheet_month . '/' . substr($sheet_year, '-2');

                        $new_data[$row]['id'] = $day[1];
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = round($data[$cols['Revenue']] / $data[$cols['BOB']], '2');  //ADR = Revenue/BOB
                        $new_data[$row]['Revenue'] = $data[$cols['Revenue']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR' && $col != 'Revenue') {
                                $new_data[$row][$col] = '0';
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            $this->redirect(array('action' => 'staff_import_simola', $sheetId));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                fclose($handle);
                $this->Session->setFlash(__('CSV imported successfully.', true));
                $this->redirect(array('action' => 'staff_webform', $sheetId));
            }
        }
        $this->set(get_defined_vars());
    }

    function admin_import_raithwaite($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_raithwaite/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Total', $pdata);
                $cols['ADR'] = array_search('ARR', $pdata);
                //$cols['Revenue'] = array_search('Accomm', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col])) {

                            $strDate = str_replace('/', '-', $data[$date_col]);
                            $date_details = date('d/m/y', strtotime($strDate));

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'admin_import_raithwaite', $sheetId));
                            }
                        }
                    }

                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'admin_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_raithwaite($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {

            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_raithwaite/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Total', $pdata);
                $cols['ADR'] = array_search('ARR', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col])) {

                            $strDate = str_replace('/', '-', $data[$date_col]);
                            $date_details = date('d/m/y', strtotime($strDate));

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            }
                        }
                    }

                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function client_import_raithwaite($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/client/import_raithwaite/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Total', $pdata);
                $cols['ADR'] = array_search('ARR', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col])) {

                            $strDate = str_replace('/', '-', $data[$date_col]);
                            $date_details = date('d/m/y', strtotime($strDate));
                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'client_import_raithwaite', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'client_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function staff_import_raithwaite($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/staff/import_raithwaite/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Total', $pdata);
                $cols['ADR'] = array_search('ARR', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col])) {

                            $strDate = str_replace('/', '-', $data[$date_col]);
                            $date_details = date('d/m/y', strtotime($strDate));
                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'staff_import_raithwaite', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'staff_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function admin_import_oceanview($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_oceanview/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Rooms Sold', $pdata);
                $cols['ADR'] = array_search('Ave Rate', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date') && ($data[$date_col] != 'Totals')) {

                            $strDate = str_replace('/', '-', $data[$date_col]);
                            $date_details = date('d/m/y', strtotime($strDate));

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'admin_import_oceanview', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'admin_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function client_import_oceanview($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/client/import_oceanview/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Rooms Sold', $pdata);
                $cols['ADR'] = array_search('Ave Rate', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date') && ($data[$date_col] != 'Totals')) {

                            $strDate = str_replace('/', '-', $data[$date_col]);
                            $date_details = date('d/m/y', strtotime($strDate));

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'client_import_oceanview', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'client_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function staff_import_oceanview($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/staff/import_oceanview/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Rooms Sold', $pdata);
                $cols['ADR'] = array_search('Ave Rate', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date') && ($data[$date_col] != 'Totals')) {

                            $strDate = str_replace('/', '-', $data[$date_col]);
                            $date_details = date('d/m/y', strtotime($strDate));

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'staff_import_oceanview', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'staff_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_oceanview($clientId) {

        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {

            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/hotel/import_oceanview/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Rooms Sold', $pdata);
                $cols['ADR'] = array_search('Ave Rate', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date') && ($data[$date_col] != 'Totals')) {

                            $strDate = str_replace('/', '-', $data[$date_col]);
                            $date_details = date('d/m/y', strtotime($strDate));

                            if (($this->data['Sheet']['month']['month'] != date('m', strtotime($strDate))) && $row == 1) {
                                $this->Session->setFlash(__('Please upload selected month report', true));
                                $this->redirect(array('action' => 'hotel_import_oceanview', $clientId));
                            }

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function admin_import_sanbona($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_sanbona/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata1 = fgetcsv($handle, 1000, ",");
                $pdata2 = fgetcsv($handle, 1000, ",");
                $pdata3 = fgetcsv($handle, 1000, ",");

                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Total Occ.', $pdata);
                $cols['ADR'] = array_search('Average Rate', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);

                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];

                    $userId = $sdata['Sheet']['user_id'];
                    $this->User = ClassRegistry::init('User');
                    $usersData = $this->User->find('first', array('conditions' => array('User.id' => array($userId), 'User.status' <> '2'), 'fields' => 'client_id'));
                    $check_weekend = '0';
                    if ($usersData['User']['client_id'] == '149') {
                        //sanbona hotel
                        if (($sheet_month == '10' && $sheet_year == '2017') || ($sheet_month == '11' && $sheet_year == '2017') || ($sheet_month == '12' && $sheet_year == '2017') || ($sheet_month == '1' && $sheet_year == '2018') || ($sheet_month == '2' && $sheet_year == '2018') || ($sheet_month == '3' && $sheet_year == '2018')) {
                            $bob_diff = '3';
                            $check_weekend = '1';
                        } else {
                            $bob_diff = '6';
                        }
                    } else {
                        $bob_diff = '0';
                    }


                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date') && ($data[$date_col] != 'Total') && ($data[$date_col] != 'Subtotal')) {

                            $dateexp = explode(" ", $data[$date_col]);
                            $date_details = str_replace('-', '/', $dateexp[0]);

                            if ($check_weekend == '1') {
                                $check_day = date('w', strtotime($date_details));
                                if ($check_day == '5' || $check_day == '6') {
                                    $bob_diff = '3';
                                } else {
                                    $bob_diff = '6';
                                }
                            }

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']] - $bob_diff;
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'admin_import_sanbona', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'admin_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function client_import_sanbona($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/client/import_sanbona/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {

                $pdata1 = fgetcsv($handle, 1000, ",");
                $pdata2 = fgetcsv($handle, 1000, ",");
                $pdata3 = fgetcsv($handle, 1000, ",");

                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Total Occ.', $pdata);
                $cols['ADR'] = array_search('Average Rate', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];

                    $userId = $sdata['Sheet']['user_id'];
                    $this->User = ClassRegistry::init('User');
                    $usersData = $this->User->find('first', array('conditions' => array('User.id' => array($userId), 'User.status' <> '2'), 'fields' => 'client_id'));
                    $check_weekend = '0';
                    if ($usersData['User']['client_id'] == '149') {
                        //sanbona hotel
                        if (($sheet_month == '10' && $sheet_year == '2017') || ($sheet_month == '11' && $sheet_year == '2017') || ($sheet_month == '12' && $sheet_year == '2017') || ($sheet_month == '1' && $sheet_year == '2018') || ($sheet_month == '2' && $sheet_year == '2018') || ($sheet_month == '3' && $sheet_year == '2018')) {
                            $bob_diff = '3';
                            $check_weekend = '1';
                        } else {
                            $bob_diff = '6';
                        }
                    } else {
                        $bob_diff = '0';
                    }

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date') && ($data[$date_col] != 'Total') && ($data[$date_col] != 'Subtotal')) {

                            $dateexp = explode(" ", $data[$date_col]);
                            $date_details = str_replace('-', '/', $dateexp[0]);

                            if ($check_weekend == '1') {
                                $check_day = date('w', strtotime($date_details));
                                if ($check_day == '5' || $check_day == '6') {
                                    $bob_diff = '3';
                                } else {
                                    $bob_diff = '6';
                                }
                            }

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']] - $bob_diff;
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'client_import_sanbona', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'client_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function staff_import_sanbona($sheetId) {
        $this->layout = 'default';
        if (!empty($this->data)) {
            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/staff/import_sanbona/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {

                $pdata1 = fgetcsv($handle, 1000, ",");
                $pdata2 = fgetcsv($handle, 1000, ",");
                $pdata3 = fgetcsv($handle, 1000, ",");

                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Total Occ.', $pdata);
                $cols['ADR'] = array_search('Average Rate', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];

                    $userId = $sdata['Sheet']['user_id'];
                    $this->User = ClassRegistry::init('User');
                    $usersData = $this->User->find('first', array('conditions' => array('User.id' => array($userId), 'User.status' <> '2'), 'fields' => 'client_id'));
                    $check_weekend = '0';
                    if ($usersData['User']['client_id'] == '149') {
                        //sanbona hotel
                        if (($sheet_month == '10' && $sheet_year == '2017') || ($sheet_month == '11' && $sheet_year == '2017') || ($sheet_month == '12' && $sheet_year == '2017') || ($sheet_month == '1' && $sheet_year == '2018') || ($sheet_month == '2' && $sheet_year == '2018') || ($sheet_month == '3' && $sheet_year == '2018')) {
                            $bob_diff = '3';
                            $check_weekend = '1';
                        } else {
                            $bob_diff = '6';
                        }
                    } else {
                        $bob_diff = '0';
                    }

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date') && ($data[$date_col] != 'Total') && ($data[$date_col] != 'Subtotal')) {

                            $dateexp = explode(" ", $data[$date_col]);
                            $date_details = str_replace('-', '/', $dateexp[0]);

                            if ($check_weekend == '1') {
                                $check_day = date('w', strtotime($date_details));
                                if ($check_day == '5' || $check_day == '6') {
                                    $bob_diff = '3';
                                } else {
                                    $bob_diff = '6';
                                }
                            }

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']] - $bob_diff;
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'staff_import_sanbona', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                    $this->Session->setFlash(__('CSV imported successfully.', true));
                    $this->redirect(array('action' => 'staff_webform', $sheetId));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_sanbona($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {
            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_sanbona/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {

                $pdata1 = fgetcsv($handle, 1000, ",");
                $pdata2 = fgetcsv($handle, 1000, ",");
                $pdata3 = fgetcsv($handle, 1000, ",");

                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = array_search('Total Occ.', $pdata);
                $cols['ADR'] = array_search('Average Rate', $pdata);

                $date_col = '0';
                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($pdata['0'])) {

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    $sheet_month = $sdata['Sheet']['month'];
                    $sheet_year = $sdata['Sheet']['year'];

                    $userId = $sdata['Sheet']['user_id'];
                    $this->User = ClassRegistry::init('User');
                    $usersData = $this->User->find('first', array('conditions' => array('User.id' => array($userId), 'User.status' <> '2'), 'fields' => 'client_id'));
                    $check_weekend = '0';
                    if ($usersData['User']['client_id'] == '149') {
                        //sanbona hotel
                        if (($sheet_month == '10' && $sheet_year == '2017') || ($sheet_month == '11' && $sheet_year == '2017') || ($sheet_month == '12' && $sheet_year == '2017') || ($sheet_month == '1' && $sheet_year == '2018') || ($sheet_month == '2' && $sheet_year == '2018') || ($sheet_month == '3' && $sheet_year == '2018')) {
                            $bob_diff = '3';
                            $check_weekend = '1';
                        } else {
                            $bob_diff = '6';
                        }
                    } else {
                        $bob_diff = '0';
                    }

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date') && ($data[$date_col] != 'Total') && ($data[$date_col] != 'Subtotal')) {
                            $dateexp = explode(" ", $data[$date_col]);
                            $date_details = str_replace('-', '/', $dateexp[0]);

                            if ($check_weekend == '1') {
                                $check_day = date('w', strtotime($date_details));
                                if ($check_day == '5' || $check_day == '6') {
                                    $bob_diff = '3';
                                } else {
                                    $bob_diff = '6';
                                }
                            }

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']] - $bob_diff;
                            $new_data[$row]['ADR'] = $data[$cols['ADR']];

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if (($sheets_d['Date']) == ($date_details)) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'hotel_import_sanbona', $sheetId));
                            }
                        }
                    }
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    $this->Session->setFlash(__('CSV imported successfully.', true));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_txt($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {
            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/text/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/admin/import_txt/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $contents = file_get_contents($this->data['Sheet']['browse_file']["tmp_name"]);
                $explode = explode("\n", $contents);

                foreach ($explode as $value) {
                    $explode_row[] = explode("\t", $value);
                }
                $pdata = $explode_row[0];
                $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                $date_col = array_search('CHAR_CONSIDERED_DATE', $pdata);
                unset($explode_row[0]);

                if (!empty($cols['BOB']) && !empty($cols['ADR']) && !empty($date_col)) {
                    $this->Sheet->contain(array('Column'));
                    $sdata = $this->Sheet->findById($sheetId);
                    $columns = Set::extract('/name', $sdata['Column']);
                    $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                    $sheet_data = $this->Sheet->getWebformData($sheetId);

                    foreach ($explode_row as $data) {

                        if (!empty($data[$date_col])) {
                            $date_details = explode(' ', $data[$date_col]);
                            $date_details = $date_details[0];
                            $date_details = str_replace("-", "/", $date_details);
                        } else {
                            $date_details = '';
                        }

                        if ((!empty($data[$cols['BOB']]) && isset($data[$cols['BOB']])) && (!empty($data[$cols['ADR']]) && isset($data[$cols['ADR']])) && !empty($date_details)) {

                            $new_data[$row]['id'] = $row;
                            $new_data[$row]['sheetId'] = $sheetId;
                            $new_data[$row]['Date'] = $date_details;
                            $new_data[$row]['BOB'] = $data[$cols['BOB']];
                            $new_data[$row]['ADR'] = str_replace(',', '', $data[$cols['ADR']]);

                            foreach ($columns as $ckey => $col) {
                                if ($col != 'BOB' && $col != 'ADR') {
                                    foreach ($sheet_data as $sheets_d) {
                                        if ($sheets_d['Date'] == $date_details) {
                                            $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                                $row++;
                            } else {
                                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                                $this->redirect(array('action' => 'hotel_import_txt', $sheetId));
                            }
                        }
                    }
                    //exit;
                    $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                    fclose($handle);
                    $this->Session->setFlash(__('Text file imported successfully.', true));
                } else {
                    $this->Session->setFlash(__('Columns are not matching with webform. Please try again!', true));
                }
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    public function hotel_import_palheiro($clientId = null) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {

            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/hotel/hotel_import_palheiro/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {
                $sheet_data = $this->Sheet->getWebformData($sheetId);

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (count($data) < '70') {
                        //village
                        $cols['BOB'] = '26';
                        $cols['Revenue'] = '33';
                        $date_col = '24';
                    } else {
                        //casa velha
                        $cols['BOB'] = '33';
                        $cols['Revenue'] = '42';
                        $date_col = '31';
                    }

                    if (!empty($data[$cols['BOB']]) && !empty($data[$cols['Revenue']]) && !empty($data[$date_col])) {
                        $date_details = str_replace('-', '/', $data[$date_col]);

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['Revenue'] = $data[$cols['Revenue']];
                        $new_data[$row]['ADR'] = $new_data[$row]['Revenue'] / $data[$cols['BOB']]; //ADR

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR' && $col != 'Revenue') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }
                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId);

                $this->Session->setFlash(__('CSV imported successfully.', true));

                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_excel($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {

            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/hotel/import_excel/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);

                $check_numcols = '21';

                if ($ndata[0]['numCols'] == $check_numcols) {

                    if (isset($ndata[0]['cells'][4][1]) && ($ndata[0]['cells'][4][1] == 'All Package-Codes')) {
                        $pdata = $ndata[0]['cells'][7];
                        unset($ndata[0]['cells'][1], $ndata[0]['cells'][2], $ndata[0]['cells'][3], $ndata[0]['cells'][4], $ndata[0]['cells'][6], $ndata[0]['cells'][7]);
                    } else {
                        $pdata = $ndata[0]['cells'][6];
                        unset($ndata[0]['cells'][1], $ndata[0]['cells'][2], $ndata[0]['cells'][3], $ndata[0]['cells'][4], $ndata[0]['cells'][6]);
                    }

                    $cols['BOB'] = array_search('Res
Ro.', $pdata);
                    $cols['ADR'] = array_search('Accom./Ro.', $pdata);
                    $date_col = array_search('Date', $pdata);
                    $sheet_type = 2;
                } else {
                    $pdata = $ndata[0]['cells'][1];
                    $cols['BOB'] = array_search('NO_ROOMS', $pdata);
                    $cols['ADR'] = array_search('CF_AVERAGE_ROOM_RATE', $pdata);
                    $date_col = array_search('CHAR_CONSIDERED_DATE', $pdata);
                    unset($ndata[0]['cells'][1]);
                }

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $s_month = $sdata['Sheet']['month'];
                $s_year = $sdata['Sheet']['year'];
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                foreach ($ndata[0]['cells'] as $data) {
                    if (!empty($data[$date_col])) {
                        if ($sheet_type == 2) {

                            $date_details = explode('/', $data[$date_col]);
                            $date_details = $date_details[1] . '/' . $date_details[0] . '/' . substr($date_details[2], '-2');
                        } else {
                            $date_details = explode(' ', $data[$date_col]);
                            $exp_date = explode('/', $date_details[0]);
                            $date_details = $exp_date[0] . '/' . $exp_date[1] . '/' . substr($exp_date[2], '-2');
                        }

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = $data[$cols['ADR']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }
                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                $this->Session->setFlash(__('Report imported successfully.', true));

                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_junction($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {

            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/hotel/import_junction/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);

                $cols['BOB'] = '6';
                $cols['ADR'] = '27';
                $date_col = '3';

                for ($i = '0'; $i <= '10'; $i++) {
                    unset($ndata[0]['cells'][$i]);
                }

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                foreach ($ndata[0]['cells'] as $data) {
                    if (!empty($data[$date_col]) && $data[$date_col] != 'Date') {
                        $date_details = date('d/m/y', strtotime($data[$date_col]));

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;
                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = $data[$cols['ADR']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }
                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                $this->Session->setFlash(__('Report imported successfully.', true));

                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_faircity($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {
            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/hotel/import_faircity/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {

                $cols['BOB'] = '4'; //Res
                $cols['ADR'] = '16'; //Accom./Ro.
                $date_col = '2';

                $sheet_data = $this->Sheet->getWebformData($sheetId);

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (!empty($data[1]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date')) {

                        $date_exp = explode('/', $data[$date_col]);
                        $date_details = $date_exp[0] . '/' . $date_exp[1] . '/' . substr($date_exp[2], -2);

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;

                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = $data[$cols['ADR']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }
                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows                
                $this->Session->setFlash(__('CSV imported successfully.', true));
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_smartline($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {

            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/hotel/import_smartline/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);

                $cols['BOB'] = '7';
                $cols['ADR'] = '14';
                $date_col = '2';

                for ($i = '0'; $i <= '8'; $i++) {
                    unset($ndata[0]['cells'][$i]);
                }

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $s_month = $sdata['Sheet']['month'];
                $s_year = $sdata['Sheet']['year'];
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                foreach ($ndata[0]['cells'] as $data) {
                    if (!empty($data[$date_col]) && $data[$date_col] != 'Date' && $data[$date_col] != 'Grand Total') {
                        $date_details = explode(' ', trim($data[$date_col]));

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = sprintf('%02d', $date_details['0']) . '/' . sprintf('%02d', $s_month) . '/' . substr($s_year, -2);
                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = $data[$cols['ADR']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($new_data[$row]['Date'])) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }
                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                $this->Session->setFlash(__('Report imported successfully.', true));

                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_caperoyal($clientId) {

        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {

            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/hotel/import_caperoyal/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = '1';
                $cols['ADR'] = '5';

                $date_col = '0';

                $sheet_data = $this->Sheet->getWebformData($sheetId);

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (!empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date') && ($data[$date_col] != 'Totals')) {

                        $date_val = substr($data[$date_col], 0, -4);
                        $date_details = str_replace('-', '/', $date_val);
                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;
                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = preg_replace('/[^(\,\x20-\x7F)\x0A\x0D]*/', '', $data[$cols['ADR']]);

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                $this->Session->setFlash(__('CSV imported successfully.', true));

                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_providence($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {

            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/hotel/import_smartline/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;


                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);

                if ($clientId == '164') {
                    $cols['BOB'] = '4';
                    $cols['ADR'] = '7';
                    unset($ndata[0]['cells'][1]);
                    unset($ndata[0]['cells'][2]);
                } else {
                    $cols['BOB'] = '3';
                    $cols['ADR'] = '16';
                    unset($ndata[0]['cells'][1]);
                    unset($ndata[0]['cells'][2]);
                    unset($ndata[0]['cells'][3]);
                }
                $date_col = '1';

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $s_month = $sdata['Sheet']['month'];
                $s_year = $sdata['Sheet']['year'];
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                foreach ($ndata[0]['cells'] as $data) {

                    if (!empty($data[$date_col]) && $data[$date_col] != 'Date' && $data[$date_col] != 'Grand Total') {

                        $exp_date = explode('/', $data[$date_col]);
                        if ($clientId == '164') {
                            $date_details = $exp_date[1] . '/' . $exp_date[0] . '/' . substr($exp_date[2], '-2');
                        } else {
                            $date_details = $exp_date[0] . '/' . $exp_date[1] . '/' . substr($exp_date[2], '-2');
                        }

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;
                        $new_data[$row]['BOB'] = $data[$cols['BOB']];
                        $new_data[$row]['ADR'] = $data[$cols['ADR']];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($new_data[$row]['Date'])) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }
                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                $this->Session->setFlash(__('Report imported successfully.', true));

                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_providence_csv($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {

            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/csv/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/hotel/import_caperoyal/' . $sheetId);

            $new_data = array();
            $row = 1;
            if ($handle) {
                $pdata = fgetcsv($handle, 1000, ",");

                $cols['BOB'] = '2';
                $cols['ADR'] = '7';

                $date_col = '1';
                $sheet_data = $this->Sheet->getWebformData($sheetId);

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (($data[$cols['BOB']] != '0') && !empty($data[$cols['BOB']]) && !empty($data[$cols['ADR']]) && !empty($data[$date_col]) && ($data[$date_col] != 'Date') && ($data[$date_col] != 'Totals')) {

                        $dateexp = explode(" ", $data[$date_col]);
                        $exp_date = explode('/', $dateexp[1]);
                        $date_details = $exp_date[0] . '/' . $exp_date[1] . '/' . substr($exp_date[2], '-2');

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;
                        $new_data[$row]['BOB'] = ltrim($data[$cols['BOB']], '0');
                        ;
                        $new_data[$row]['ADR'] = preg_replace('/[^(\,\x20-\x7F)\x0A\x0D]*/', '', trim($data[$cols['ADR']]));

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($date_details)) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                        }
                    }
                }

                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows

                $this->Session->setFlash(__('CSV imported successfully.', true));
                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function hotel_import_providence_nfh($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {

            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/hotel/import_smartline/' . $sheetId);
            $new_data = array();
            $row = 1;

            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);

                $bob_col = '4';
                $adr_col = '15';
                unset($ndata[0]['cells'][1]);
                unset($ndata[0]['cells'][2]);
                unset($ndata[0]['cells'][3]);

                $date_col = '1';

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $s_month = $sdata['Sheet']['month'];
                $s_year = $sdata['Sheet']['year'];
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));

                foreach ($ndata[0]['cells'] as $colKey => $data) {

                    if (!empty($data[$date_col]) && $data[$date_col] != 'Date' && (count($data) > '15')) {

                        $UNIX_DATE = ($ndata[0]['cellsInfo'][$colKey][$date_col]['raw'] - 25569) * 86400;
                        $date_details = gmdate("d/m/Y", $UNIX_DATE);

                        $new_data[$row]['id'] = $row;
                        $new_data[$row]['sheetId'] = $sheetId;
                        $new_data[$row]['Date'] = $date_details;
                        $new_data[$row]['BOB'] = $ndata[0]['cellsInfo'][$colKey][$bob_col]['raw'];
                        $new_data[$row]['ADR'] = $ndata[0]['cellsInfo'][$colKey][$adr_col]['raw'];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($new_data[$row]['Date'])) {
                                        $new_data[$row][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }
                        if ($this->Sheet->importWebform($sheetId, $new_data[$row])) {
                            $row++;
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                        }
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                $this->Session->setFlash(__('Report imported successfully.', true));

                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

    function import_excel_report($clientId) {
        $this->layout = 'default';

        $this->Client = ClassRegistry::init('Client');
        $hotels_data = $this->Client->find('first', array('conditions' => array('Client.status !=' => 2, 'Client.id' => $clientId), 'fields' => 'hotelname'));
        $hotelname = $hotels_data['Client']['hotelname'];

        if (!empty($this->data)) {

            $year = $this->data['Sheet']['year']['year'];
            $month = $this->data['Sheet']['month']['month'];

            $this->Client = ClassRegistry::init('Client');
            $this->Client->Department->recursive = -1;
            $condition = array('Department.client_id' => $clientId, 'Department.name LIKE' => 'Room%', 'Department.status' => '1');
            $dept_data = $this->Client->Department->find('first', array('conditions' => $condition, 'fields' => 'id', 'recursive' => '0'));
            $dept_id = $dept_data['Department']['id'];

            $selectd_sheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status !=' => 2, 'Sheet.department_id' => $dept_id, 'Sheet.year' => $year, 'Sheet.month' => $month), 'fields' => array('Sheet.id'), 'recursive' => -1, 'order' => 'Sheet.year DESC, Sheet.month DESC'));
            $sheetId = $selectd_sheet['Sheet']['id'];

            $handle = $this->requestAction('/Sheets/import_file_validation/excel/' . $this->data['Sheet']['browse_file']['name'] . '/' . urlencode($this->data['Sheet']['browse_file']['tmp_name']) . '/hotel/import_smartline/' . $sheetId);
            $new_data = array();

            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['Sheet']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;

                $this->Sheet->contain(array('Column'));
                $sdata = $this->Sheet->findById($sheetId);

                $cols['BOB'] = array_search('Total', $ndata[0]['cells'][4]);
                $cols['ADR'] = array_search('ARR', $ndata[0]['cells'][4]);

                unset($ndata[0]['cells'][1]);
                unset($ndata[0]['cells'][2]);
                unset($ndata[0]['cells'][3]);
                unset($ndata[0]['cells'][4]);

                $date_col = '1';

                $sheet_data = $this->Sheet->getWebformData($sheetId);
                $s_month = $sdata['Sheet']['month'];
                $s_year = $sdata['Sheet']['year'];
                $columns = Set::extract('/name', $sdata['Column']);
                $columns = array_diff($columns, array('Notes', 'TripAdvisor', 'BAR Level'));
                $check_month = sprintf('%02d', $sdata['Sheet']['month']);
                $check_year = substr($s_year, '-2');
                $day = '01';
                foreach ($ndata[0]['cells'] as $key => $data) {

                    if (!empty($data[$date_col]) && $data[$date_col] != 'Date' && $data[$cols['BOB']] != '' && $data[$cols['ADR']] != '') {

                        $date_details = $day . '/' . $check_month . '/' . $check_year;

                        $new_data[$day]['id'] = $day;
                        $new_data[$day]['sheetId'] = $sheetId;
                        $new_data[$day]['Date'] = $date_details;
                        $new_data[$day]['BOB'] = $data[$cols['BOB']];
                        $new_data[$day]['ADR'] = $ndata[0]['cellsInfo'][$key][$cols['ADR']]['raw'];

                        foreach ($columns as $ckey => $col) {
                            if ($col != 'BOB' && $col != 'ADR') {
                                foreach ($sheet_data as $sheets_d) {
                                    if (($sheets_d['Date']) == ($new_data[$day]['Date'])) {
                                        $new_data[$day][$col] = $sheets_d[$col] == '' ? '0' : $sheets_d[$col];
                                        break;
                                    }
                                }
                            }
                        }

                        if ($this->Sheet->importWebform($sheetId, $new_data[$day])) {
                            
                        } else {
                            $this->Session->setFlash(__('Cannot open uploaded file!', true));
                        }
                        $day++;
                        $day = sprintf("%02d", $day);
                    }
                }
                $this->Sheet->updateRowsTotal($sheetId); //Update Total and Rows
                $this->Session->setFlash(__('Report imported successfully.', true));

                fclose($handle);
            }
        }
        $this->set(get_defined_vars());
    }

}

//end class