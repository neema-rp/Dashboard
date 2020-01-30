<?php
class GpsPacksController extends AppController {

    var $name = 'GpsPacks';
    var $helpers = array('Html', 'Javascript', 'Session');

    function beforeFilter() {
       // Configure::write('debug',2);
        //echo 'in before filter'; exit;
        parent::beforeFilter(); 
        
        $this->Auth->allow('get_webform_monthly_score_test','print_pack','export_pdf','import_countries','get_activity_data','get_channels_vals','get_child_list','get_gps_data','get_total_segment_vals','get_roomtype_values','get_webform_monthly_score','get_last_gps_data','get_country_list','get_market_segments');
   }

    function admin_index($client_id=null,$selected_child_hotel=null) {
        
        if(!empty($selected_child_hotel)){
             if (!empty($this->data) && trim($this->data['GpsPack']['value']) != '') {
                $conditions = array('GpsPack.name LIKE' => "%" . $this->data['GpsPack']['value'] . "%", 'GpsPack.status !=' => 2,'GpsPack.client_id'=>$selected_child_hotel);
            } else {
                $conditions = array('GpsPack.status !=' => 2,'GpsPack.client_id'=>$selected_child_hotel);
            }   
        }else{
            if (!empty($this->data) && trim($this->data['GpsPack']['value']) != '') {
                $conditions = array('GpsPack.name LIKE' => "%" . $this->data['GpsPack']['value'] . "%", 'GpsPack.status !=' => 2,'GpsPack.client_id'=>$client_id);
            } else {
                $conditions = array('GpsPack.status !=' => 2,'GpsPack.client_id'=>$client_id);
            }
        }
        
        
        $userGpsPacks = $this->GpsPack->find('all', array('conditions' => $conditions));
        $this->set('userGpsPacks', $userGpsPacks);
        
        $this->set('selected_child_hotel', $selected_child_hotel);
        $this->set('client_id', $client_id);
        
        $child_data = $this->requestAction('/GpsPacks/get_child_list/'.$client_id);
        $this->set('child_data', $child_data);
    }

    
    function admin_view($GpsPackId = null) {
        //Configure::write('debug',2);
        if (!$GpsPackId) {
            $this->Session->setFlash(__('Invalid GPS Pack ID', true));
            $this->redirect(array('action' => 'index'));
        }
        $GpsPack = $this->GpsPack->read(null, $GpsPackId);
        $this->set('GpsPack', $GpsPack);
        $gps_pack_id = $GpsPack['GpsPack']['id'];
        
        $month = $GpsPack['GpsPack']['month'];
        $month = sprintf("%02d", $month);
        $year = $GpsPack['GpsPack']['year'];
        $client_id = $GpsPack['GpsPack']['client_id'];
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms'] + $gps_settings['GpsSetting']['other_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        
//        $market_performance = 'Pretoria and Surroundings - Upscale & Upper Mid';
//        if($client_id == '67'){
//            $market_performance = 'Johannesburg: Upper and Upper-midscale';
//        }elseif($client_id == '70'){
//            $market_performance = 'Pretoria Upscale Upper Mid';
//        }elseif($client_id == '81'){
//            $market_performance = 'Pretoria & Surrounds';
//        }elseif($client_id == '80'){
//            $market_performance = 'Sandton and Surroundings - Upscale & Upper Mid';
//        }elseif($client_id == '69'){
//            $market_performance = 'Sandton and Surroundings - Upscale & Upper Mid';
//        }
        
        
        $market_performance = $gps_settings['GpsSetting']['summary_mp_label'];
        $this->set('market_performance',$market_performance);
        
        $marketsegments = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        $this->set('marketsegments',$marketsegments);

    }
    
    public function get_market_segments(){
        $this->layout = '';
        $this->autoRender = false;
        
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        
        if(!empty($_REQUEST['term'])){
            $marketsegment_name = strtoupper($_REQUEST['term']);
            $marketsegments['segments'] = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'LOWER(MarketSegment.name) LIKE' => "%" . $marketsegment_name . "%")));
        }else{
            $marketsegments['segments'] = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));
        }        
        return json_encode($marketsegments);
    }




    function admin_edit($step=null,$GpsPackId = null) {
        
        //Configure::write('debug',2);
        
        $GpsPack = $this->GpsPack->read(null, $GpsPackId);
        $client_id = $GpsPack['GpsPack']['client_id'];
        $this->set('client_id',$client_id);
        $this->set('GpsPack', $GpsPack);
        $gps_pack_id = $GpsPack['GpsPack']['id'];
        
        $month = $GpsPack['GpsPack']['month'];
        $month = sprintf("%02d", $month);
        $year = $GpsPack['GpsPack']['year'];
        $client_id = $GpsPack['GpsPack']['client_id'];

        $this->set('step',$step);
        
        $back_step = $step - '1';
        if($back_step >= '10' && $back_step < '14'){
            $back_step = '9';
        }elseif($back_step == '19'){
            $back_step = '18';
        }
        $next_step = $step + '1';
        $this->set('back_step',$back_step);
        $this->set('next_step',$next_step);
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        //code to allow only accessed steps ends here
        if(!empty($gps_settings['GpsSetting']['access_steps'])){
            if($gps_settings['GpsSetting']['access_steps'] != 'ALL'){
                $gps_steps_ids = explode(',',$gps_settings['GpsSetting']['access_steps']);
                if($step == '5' || $step == '6' || $step == '7' || $step == '8'){ 
                    if(!in_array('5-8',$gps_steps_ids)){
                         $this->redirect(array('action' => 'edit','9',$gps_pack_id));
                    }
                }elseif($step == '14' || $step == '15'){
                    if(!in_array('14-15',$gps_steps_ids)){
                         $this->redirect(array('action' => 'edit','16',$gps_pack_id));
                    }
                }elseif($step == '18' || $step == '19'){
                    if(!in_array('18-19',$gps_steps_ids)){
                         $this->redirect(array('action' => 'edit','20',$gps_pack_id));
                    }
                }else{
                    if(!in_array($step,$gps_steps_ids)){
                        if($step == '22'){
                            $this->redirect(array('action' => 'index',$client_id));
                        }else{
                             $this->redirect(array('action' => 'edit',($step+1),$gps_pack_id));
                        }
                    }
                }
             }
        }
        //code to allow only accessed steps ends here

        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms']+ $gps_settings['GpsSetting']['other_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        
        $marketsegments = array();
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        
        if(!empty($GpsPack['GpsPack']['market_segments'])){
            $marketsegment_ids = explode(',',$GpsPack['GpsPack']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }else if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        
        $this->set('marketsegments',$marketsegments);
        
        $market_performance = $gps_settings['GpsSetting']['summary_mp_label'];
        $this->set('market_performance',$market_performance);
        
        if (!empty($this->data)){
            
            if($_SERVER['REMOTE_ADDR'] == '101.59.236.138'){
                //echo '<pre>';  print_r($this->data);
           }
            
            //echo '<pre>'; print_r($this->data); exit;
            
            
            $gps_pack_id = $this->data['GpsPack']['pack_id'];
            if(!empty($this->data['GpsPack']['value'])){
                
                    $this->GpsData = ClassRegistry::init('GpsData');
                    $this->GpsData->deleteAll(array('GpsData.step' => $this->data['GpsPack']['step'], 'GpsData.gps_pack_id' => $gps_pack_id));

                    $question = '1'; $prev_val = '';

                    foreach($this->data['GpsPack']['value'] as $val_key=>$values){
                        if(!empty($this->data['GpsPack']['text'][$val_key])){
                        if($this->data['GpsPack']['text'][$val_key] != $prev_val){
                            $question = '1';
                        }
                        }
                        $prev_val = @$this->data['GpsPack']['text'][$val_key];
                        $gps_data['GpsData']['id'] = '';
                        $gps_data['GpsData']['gps_pack_id'] = $gps_pack_id;
                        $gps_data['GpsData']['value'] = $values;
                        $gps_data['GpsData']['text'] = @$this->data['GpsPack']['text'][$val_key];
                        $gps_data['GpsData']['sub_text'] =  @$this->data['GpsPack']['sub_text'][$val_key];
                        $gps_data['GpsData']['question'] = $question;
                        $gps_data['GpsData']['step'] = $this->data['GpsPack']['step'];
                        $question++;

                        $this->GpsData = ClassRegistry::init('GpsData');
                        $this->GpsData->save($gps_data);
                    }
                
                    if($_POST['submit'] == 'Save & Close'){
                        $this->Session->setFlash(__('The GPS Pack data has been saved successfully', true));
                        $this->redirect(array('action' => 'edit_steps',$gps_pack_id));
                    }
                    
                    $next_step = $this->data['GpsPack']['step']+'1';                    
                    if($next_step == '23' || $next_step > '22'){
                        $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                    }else{
                        $this->redirect(array('action' => 'edit',$next_step,$gps_pack_id));
                    }
            } else {
                   $next_step = $this->data['GpsPack']['step']+'1';
                   $this->redirect(array('action' => 'edit',$next_step,$gps_pack_id));
            }
        }
    }
    
    function get_gps_data($gps_pack_id=null,$step=null){
        $this->layout = '';
        $this->autoRender = false;
        $this->GpsData = ClassRegistry::init('GpsData');
        $gpsDatas = $this->GpsData->find('all', array('conditions' => array('GpsData.gps_pack_id'=>$gps_pack_id,'GpsData.step'=>$step),'order'=>'id ASC'));
        return $gpsDatas;
    }
    
    function get_activity_data($gps_pack_id=null,$gps_month=null){
        $this->layout = '';
        $this->autoRender = false;

        $gpspack = $this->GpsPack->find('first', array('conditions' => array('GpsPack.id'=>$gps_pack_id),'fields'=>array('client_id,month,year')));
        $client_id = $gpspack['GpsPack']['client_id'];
        $year = $gpspack['GpsPack']['year'];
        
        //step 5 for activity////activiy comes for 4 months, present plus next 3
        $new_pack_id = '';
        for($count=0;$count <= 3; $count++){
                $chk_month = (($gps_month - $count) < '1') ?  ('12'-($gps_month - $count)): ($gps_month - $count);
                $year = (($gps_month - $count) < '1') ?  ($year-'1'): $year;
                $condition = array('GpsPack.client_id'=>$client_id,'GpsPack.id !='=>$gps_pack_id,'GpsPack.month'=>$chk_month,'GpsPack.year'=>$year,'GpsPack.status'=>1);
                $packId = $this->GpsPack->find('first', array('conditions' => $condition,'fields'=>'id','order'=>'id DESC'));
                if(!empty($packId)){
                    $new_pack_id = $packId['GpsPack']['id'];
                    $step = '5'+$count;
                    break;
                }
        }
        $gpsDatas = array();
        
        if(!empty($new_pack_id)){
            $this->GpsData = ClassRegistry::init('GpsData');
            $gpsDatas = $this->GpsData->find('all', array('conditions' => array('GpsData.gps_pack_id'=>$new_pack_id,'GpsData.step'=>$step)));
            //echo '<pre>'; print_r($gpsDatas); exit;
        }
        return $gpsDatas;
    }
    
    function get_last_gps_data($gps_pack_id=null,$step=null){
        $this->layout = '';
        $this->autoRender = false;
        
        $gpspack = $this->GpsPack->find('first', array('conditions' => array('GpsPack.id'=>$gps_pack_id),'fields'=>'client_id'));
        $client_id = $gpspack['GpsPack']['client_id'];
        
        $conditions = array('GpsPack.status !=' => 2,'GpsPack.id !='=>$gps_pack_id,'client_id'=>$client_id);
        $userGpsPacks = $this->GpsPack->find('first', array('conditions' => $conditions,'fields'=>'id','order'=>'id DESC'));
        
        $id = $userGpsPacks['GpsPack']['id'];
        
        $this->GpsData = ClassRegistry::init('GpsData');
        $gpsDatas = $this->GpsData->find('all', array('conditions' => array('GpsData.gps_pack_id'=>$id,'GpsData.step'=>$step)));
        return $gpsDatas;
    }
    
    
    function get_total_segment_vals($client_id=null,$month=null,$year=null,$column_id=null){
        $this->layout = '';
        $this->autoRender = false;
        
        $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);
        
        $this->User = ClassRegistry::init('User');
        $users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));
        $this->AdvancedSheet = ClassRegistry::init('AdvancedSheet');
        $this->AdvancedSheet->recursive = '-1';
        $conditions = array('AdvancedSheet.status !='=>'2','AdvancedSheet.department_id'=>$dept_ids,'AdvancedSheet.user_id'=>$users,'AdvancedSheet.month'=>$month,'AdvancedSheet.year'=>$year);
        $sheetData = $this->AdvancedSheet->find('all', array('conditions' => $conditions,'fields'=>  array('AdvancedSheet.market_segments,AdvancedSheet.id')));
        if(!empty($sheetData)){
            $sheetData_id =$sheetData[0]['AdvancedSheet']['id'];
            $segmentData = $this->AdvancedSheet->AdvanceData->find('list',array('conditions'=>array('AdvanceData.column_id'=>$column_id,'AdvanceData.advanced_sheet_id'=>$sheetData_id,'AdvanceData.date'=>'Total'),'fields'=> array('AdvanceData.market_segment_id','AdvanceData.value'),'order'=>'AdvanceData.date ASC'));
        }else{
            $segmentData = array();
        }
        
        return $segmentData;
    }
    
    function get_webform_monthly_score($client_id=null,$fsct_col_id=null,$month=null,$year=null){
        
        if($_SERVER['REMOTE_ADDR'] == '101.59.88.216'){
           // Configure::write('debug',2);
        }
        
        $this->layout = '';
        $this->autoRender = false;
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $financial_month_start = (!empty($gps_settings['GpsSetting']['financial_month_start'])) ? $gps_settings['GpsSetting']['financial_month_start'] : '1';
        
        $this->Sheet = ClassRegistry::init('Sheet');
        
         //Get Rooms Department ID
        $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);

        $this->User = ClassRegistry::init('User');
        $users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));
        
        $this->Datum = ClassRegistry::init('Datum');
        
        $month_arr = array();
        for($month_loop=$financial_month_start;$month_loop <= ($financial_month_start + 11); $month_loop++){
            $month_arr[] = $month_loop > '12' ? $month_loop - '12' : $month_loop;
        }
        
        $loop = '0'; $sum_col_vals = array();
       // for($month_loop=1;$month_loop <= 12; $month_loop++){
         for($month_loop=$financial_month_start;$month_loop <= ($financial_month_start + 11); $month_loop++){
            
             $check_month = $month_loop > '12' ? $month_loop - '12' : $month_loop;
             //$check_year = $month_loop > '12' ? $year+'1' : $year;
            
             if($month_loop > '12' && ($financial_month_start > $month_loop)){
                $check_year = $year+'1';
             }else{
                 $check_year = $year;
                  if(array_search($check_month, $month_arr) < array_search($month, $month_arr)){
                    $check_year = $year-'1';
                 }
             }
             
//             if($check_month > '12'){
//                $check_year = $year+'1';
//             }else{
//                 $check_year = $year;
//                  if(array_search($check_month, $month_arr) < array_search($month, $month_arr)){
//                    $check_year = $year-'1';
//                 }
//             }
             
             //$check_month = $month+$loop > '12' ? '1' : $month+$loop;
             //$check_year = $month+$loop > '12' ? $year+'1' : $year;
             
             $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$check_year,'Sheet.month'=>$check_month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
           
            if(!empty ($all_sheets)){
                $sheetId = $all_sheets[0]['Sheet']['id'];
                
                $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$check_month, $check_year); //calculate the number of days in present month
                
                $this->Formula = ClassRegistry::init('Formula');
                $formula_details = $this->Formula->find('first',array('conditions'=>array('Formula.sheet_id'=>$sheetId, 'Formula.row_id'=>'Total','Formula.column_id'=>$fsct_col_id),'fields'=>array('Formula.formula'), 'order'=>array('Formula.column_order')));
                if(!empty($formula_details)){
                            $operatorArray = array("+","-","*","/","(",")");
                            $operands = explode(" ", $formula_details['Formula']['formula']);
                            $newFormula = array();
                            foreach($operands as $operand){
                                    if(substr($operand, 0,1) == "C"){
                                            $col_sum = $this->Datum->find('all', array(
                                            'conditions' => array(
                                            'sheet_id' => $sheetId,'column_id' =>substr($operand,1),'row_id'=>0,'date >='=>'1','date <='=>$days_in_presnt_month),
                                            'fields' => array('sum(Datum.value) as val_sum'
                                            )));
                                            $totalForColumn = $col_sum[0][0]['val_sum'];
                                            
                                            array_push($newFormula, $totalForColumn);
                                            
                                    }elseif(in_array($operand, $operatorArray)){
                                            array_push($newFormula, $operand);
                                    }elseif(is_numeric($operand)){
                                            array_push($newFormula, $operand);
                                    }
                            }
                            
                            $formulaForTotal = implode(" ",$newFormula);
                            $sum_val = $this->Sheet->calculate_string($formulaForTotal);
                            $sum_col_vals[$check_month] = round($sum_val, 2);
                }else{
                        
                         $cols_data = $this->Datum->find('all', array(
                        'conditions' => array(
                        'sheet_id' => $sheetId,'column_id' =>$fsct_col_id,'row_id'=>0,'date >='=>'1','date <='=>$days_in_presnt_month),
                        'fields' => array('sum(Datum.value) as val_sum'
                        )));
                        

                        $sum_col_vals[$check_month] = $cols_data[0][0]['val_sum'];
                }
            }else{
                $sum_col_vals[$check_month] = '';
            }
            $loop++;
        }
        
        if($_SERVER['REMOTE_ADDR'] == '101.59.88.216'){
           //echo '<pre>'; print_r($sum_col_vals); exit;
        }
        
        return $sum_col_vals;

    }


    function get_roomtype_values($month=null,$roomType=null,$client_id=null,$gps_pack_id=null,$year='2015'){
        $this->layout = '';
        $this->autoRender = false;
        
        $this->GpsData = ClassRegistry::init('GpsData');
        $roomData = array();
        if($month == 'year'){
                $this->GpsSetting = ClassRegistry::init('GpsSetting');
                $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id),'fields'=>'financial_month_start'));
            
                $financial_month_start = (!empty($gps_settings['GpsSetting']['financial_month_start'])) ? $gps_settings['GpsSetting']['financial_month_start'] : '1';            
                for($months=$financial_month_start;$months <= ($financial_month_start + 11); $months++){
                    $roomData_rn = array(); $roomData_adr = array();
                       $use_month =($months > '12')?$months -'12':$months;
                       $use_year =($months > '12')?$year +'1':$year;
                       $roomData_rn = $this->GpsData->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'gps_packs',
                                    'alias' => 'GpsPack',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'GpsPack.id = GpsData.gps_pack_id'
                                    )
                                )
                            ),
                            'conditions' =>array('GpsPack.client_id'=>$client_id,'GpsPack.month'=>$use_month,'GpsPack.year'=>$use_year,'GpsData.text'=>$roomType,'GpsData.sub_text'=>'RN','GpsData.question'=>'1','GpsPack.status'=>1),
                            'fields' => array('GpsPack.month', 'GpsData.value')
                        ));
                       
                       
                           $roomData_adr = $this->GpsData->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'gps_packs',
                                    'alias' => 'GpsPack',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'GpsPack.id = GpsData.gps_pack_id'
                                    )
                                )
                            ),
                            'conditions' =>array('GpsPack.client_id'=>$client_id,'GpsPack.month'=>$use_month,'GpsPack.year'=>$use_year,'GpsData.text'=>$roomType,'GpsData.sub_text'=>'ADR','GpsData.question'=>'4','GpsPack.status'=>1),
                            'fields' => array('GpsPack.month', 'GpsData.value')
                        ));                
                           //echo '<pre>';  echo '---'.$use_month.'-'.$use_year.'----'; print_r($roomData_rn);
                           $roomData['RN'][$use_month] = $roomData_rn['GpsData']['value'];
                           $roomData['ADR'][$use_month] = $roomData_adr['GpsData']['value'];
                }
               // exit;
                
               //echo '<pre>'; print_r($roomData); exit;
                
        }else{
            $roomData['RN'] = $this->GpsData->find('list', array(
                'joins' => array(
                    array(
                        'table' => 'gps_packs',
                        'alias' => 'GpsPack',
                        'type' => 'INNER',
                        'conditions' => array(
                            'GpsPack.id = GpsData.gps_pack_id'
                        )
                    )
                ),
                'conditions' =>array('GpsPack.client_id'=>$client_id,'GpsPack.month'=>$month,'GpsPack.year'=>$year,'GpsData.text'=>$roomType,'GpsData.sub_text'=>'RN','GpsData.question'=>'1','GpsPack.status'=>1),
                'fields' => array('GpsPack.month', 'GpsData.value')
            ));
               $roomData['ADR'] = $this->GpsData->find('list', array(
                'joins' => array(
                    array(
                        'table' => 'gps_packs',
                        'alias' => 'GpsPack',
                        'type' => 'INNER',
                        'conditions' => array(
                            'GpsPack.id = GpsData.gps_pack_id'
                        )
                    )
                ),
                'conditions' =>array('GpsPack.client_id'=>$client_id,'GpsPack.month'=>$month,'GpsPack.year'=>$year,'GpsData.text'=>$roomType,'GpsData.sub_text'=>'ADR','GpsData.question'=>'4','GpsPack.status'=>1),
                'fields' => array('GpsPack.month', 'GpsData.value')
            ));
        }
        
        return $roomData;
    }
   
    function admin_new($client_id='106') {
        $this->set('client_id',$client_id);
        
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $market_seg_ids = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
        }
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        //$marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$market_seg_ids)));
        $this->set('marketsegments',$marketsegments);
        
        
        if (!empty($this->data)) {
            $this->data['GpsPack']['market_segments'] = implode(',',$this->data['GpsPack']['MarketSegment']);
            if ($this->GpsPack->save($this->data)) {
            $gps_pack_id = $this->GpsPack->getLastInsertId();
              $this->redirect(array('action' => 'add','1',$gps_pack_id));
            } else {
                $this->Session->setFlash(__('The GPS Pack could not be saved. Please, try again.', true));
            }
        }
    }
    
    function admin_add($step=null,$gps_pack_id=null) {
        $this->set('step',$step);
        
        if (!empty($this->data)) {
            
           $this->GpsPack->create();
           
             if ($this->GpsPack->save($this->data)) {
                 
                if(!empty($this->data['GpsPack']['id'])){
                        $gps_pack_id = $this->data['GpsPack']['id'];
                }else{
                    $gps_pack_id = $this->GpsPack->getLastInsertId();
                }
                    
            if(!empty($this->data['GpsPack']['value'])){
                $question = '1'; $prev_val = '';
                foreach($this->data['GpsPack']['value'] as $val_key=>$values){
                    if(!empty($this->data['GpsPack']['text'][$val_key])){
                    if($this->data['GpsPack']['text'][$val_key] != $prev_val){
                        $question = '1';
                    }
                    }
                    $prev_val = @$this->data['GpsPack']['text'][$val_key];
                    $gps_data['GpsData']['id'] = '';
                    $gps_data['GpsData']['gps_pack_id'] = $gps_pack_id;
                    $gps_data['GpsData']['value'] = $values;
                    $gps_data['GpsData']['text'] = @$this->data['GpsPack']['text'][$val_key];
                    $gps_data['GpsData']['sub_text'] =  @$this->data['GpsPack']['sub_text'][$val_key];
                    $gps_data['GpsData']['question'] = $question;
                    $gps_data['GpsData']['step'] = $this->data['GpsPack']['step'];
                    $question++;
                    
                    $this->GpsData = ClassRegistry::init('GpsData');
                    $this->GpsData->save($gps_data);
                }
            }

                    $next_step = $this->data['GpsPack']['step']+'1';

                    //$this->Session->setFlash(__('GpsPackS has been saved', true));
                    if($next_step == '23' || $next_step > '22'){
                        $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                    }else{
                        $this->redirect(array('action' => 'add',$next_step,$gps_pack_id));
                    }
            } else {
                $this->Session->setFlash(__('The GPS Packs could not be saved. Please, try again.', true));
            }
        }else{
            
            $this->data = $this->GpsPack->find('first', array('conditions' => array('GpsPack.id' => $gps_pack_id, 'GpsPack.status' => 1)));
            $client_id = $this->data['GpsPack']['client_id'];
            $this->set('client_id',$client_id);
            $this->set('GpsPack', $this->data);
            
            $this->GpsSetting = ClassRegistry::init('GpsSetting');
            $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
            $this->set('gps_settings',$gps_settings);
            
            //code to allow only accessed steps ends here
            if(!empty($gps_settings['GpsSetting']['access_steps'])){
                if($gps_settings['GpsSetting']['access_steps'] != 'ALL'){
                    $gps_steps_ids = explode(',',$gps_settings['GpsSetting']['access_steps']);
                    if($step == '5' || $step == '6' || $step == '7' || $step == '8'){ 
                        if(!in_array('5-8',$gps_steps_ids)){
                             $this->redirect(array('action' => 'add','9',$gps_pack_id));
                        }
                    }elseif($step == '14' || $step == '15'){
                        if(!in_array('14-15',$gps_steps_ids)){
                             $this->redirect(array('action' => 'add','16',$gps_pack_id));
                        }
                    }elseif($step == '18' || $step == '19'){
                        if(!in_array('18-19',$gps_steps_ids)){
                             $this->redirect(array('action' => 'add','20',$gps_pack_id));
                        }
                    }else{
                        if(!in_array($step,$gps_steps_ids)){
                            if($step == '22'){
                                $this->redirect(array('action' => 'index',$client_id));
                            }else{
                                 $this->redirect(array('action' => 'add',($step+1),$gps_pack_id));
                            }
                        }
                    }
                 }
            }
            //code to allow only accessed steps ends here

            
            $marketsegments = array();
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            
            if(!empty($this->data['GpsPack']['market_segments'])){
                $marketsegment_ids = explode(',',$this->data['GpsPack']['market_segments']);
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
            }else if(!empty($gps_settings['GpsSetting']['market_segments'])){
                $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
            }
            $this->set('marketsegments',$marketsegments);
            
            $market_performance = $gps_settings['GpsSetting']['summary_mp_label'];
            $this->set('market_performance',$market_performance);
        }
    }

    function admin_delete($GpsPackId = null) {
        if (!$GpsPackId) {
            $this->Session->setFlash(__('Invalid GPS Pack id', true));
            $this->redirect(array('action' => 'index'));
        }
        $GpsPack = $this->GpsPack->read(null, $GpsPackId);
        $client_id = $GpsPack['GpsPack']['client_id'];
        
        if ($this->GpsPack->softDelete($GpsPackId)) {
            $this->Session->setFlash(__('GPS Pack deleted successfully', true));
            $this->redirect(array('action' => 'index',$client_id));
        }
        $this->Session->setFlash(__('GPS Pack was not deleted, please try again.', true));
        $this->redirect(array('action' => 'index',$client_id));
    }
  
    function client_index($client_id=null,$selected_child_hotel=null) {
        if(!empty($selected_child_hotel)){
             if (!empty($this->data) && trim($this->data['GpsPack']['value']) != '') {
                $conditions = array('GpsPack.name LIKE' => "%" . $this->data['GpsPack']['value'] . "%", 'GpsPack.status !=' => 2,'GpsPack.client_id'=>$selected_child_hotel);
            } else {
                $conditions = array('GpsPack.status !=' => 2,'GpsPack.client_id'=>$selected_child_hotel);
            }   
        }else{
            if (!empty($this->data) && trim($this->data['GpsPack']['value']) != '') {
                $conditions = array('GpsPack.name LIKE' => "%" . $this->data['GpsPack']['value'] . "%", 'GpsPack.status !=' => 2,'GpsPack.client_id'=>$client_id);
            } else {
                $conditions = array('GpsPack.status !=' => 2,'GpsPack.client_id'=>$client_id);
            }
        }
        $userGpsPacks = $this->GpsPack->find('all', array('conditions' => $conditions));
        $this->set('userGpsPacks', $userGpsPacks);
       
        $this->set('selected_child_hotel', $selected_child_hotel);
        $this->set('client_id', $client_id);
        
        $child_data = $this->requestAction('/GpsPacks/get_child_list/'.$client_id);
        $this->set('child_data', $child_data);
    }

    function client_view($GpsPackId = null) {
        if (!$GpsPackId) {
            $this->Session->setFlash(__('Invalid GPS Pack ID', true));
            $this->redirect(array('action' => 'index'));
        }
        $GpsPack = $this->GpsPack->read(null, $GpsPackId);
        $this->set('GpsPack', $GpsPack);
        $gps_pack_id = $GpsPack['GpsPack']['id'];
        
        $month = $GpsPack['GpsPack']['month'];
        $month = sprintf("%02d", $month);
        $year = $GpsPack['GpsPack']['year'];
        $client_id = $GpsPack['GpsPack']['client_id'];
        
//        $market_performance = 'Pretoria and Surroundings - Upscale & Upper Mid';
//        if($client_id == '67'){
//            $market_performance = 'Johannesburg: Upper and Upper-midscale';
//        }elseif($client_id == '70'){
//            $market_performance = 'Pretoria Upscale Upper Mid';
//        }elseif($client_id == '81'){
//            $market_performance = 'Pretoria & Surrounds';
//        }elseif($client_id == '80'){
//            $market_performance = 'Sandton and Surroundings - Upscale & Upper Mid';
//        }elseif($client_id == '69'){
//            $market_performance = 'Sandton and Surroundings - Upscale & Upper Mid';
//        }
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);

        $market_performance = $gps_settings['GpsSetting']['summary_mp_label'];
        $this->set('market_performance',$market_performance);
       
        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms']+ $gps_settings['GpsSetting']['other_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        
        $marketsegments = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        
        $this->set('marketsegments',$marketsegments);   
    }

    function client_new($client_id=null) {
        $this->set('client_id',$client_id);
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $market_seg_ids = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
        }
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        //$marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$market_seg_ids)));
        $this->set('marketsegments',$marketsegments);
        
        if (!empty($this->data)) {
            $this->data['GpsPack']['market_segments'] = implode(',',$this->data['GpsPack']['MarketSegment']);
            if ($this->GpsPack->save($this->data)) {
            $gps_pack_id = $this->GpsPack->getLastInsertId();
              $this->redirect(array('action' => 'add','1',$gps_pack_id));
            } else {
                $this->Session->setFlash(__('The GPS Pack could not be saved. Please, try again.', true));
            }
        }
    }
    function staff_new($client_id=null) {
        $this->set('client_id',$client_id);
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $market_seg_ids = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
        }
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        //$marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$market_seg_ids)));
        $this->set('marketsegments',$marketsegments);
        
        if (!empty($this->data)) {
            $this->data['GpsPack']['market_segments'] = implode(',',$this->data['GpsPack']['MarketSegment']);
            if ($this->GpsPack->save($this->data)) {
            $gps_pack_id = $this->GpsPack->getLastInsertId();
              $this->redirect(array('action' => 'add','1',$gps_pack_id));
            } else {
                $this->Session->setFlash(__('The GPS Pack could not be saved. Please, try again.', true));
            }
        }
    }
    
     function client_add($step=null,$gps_pack_id=null) {
        $this->set('step',$step);
        
        if (!empty($this->data)) {
            
           $this->GpsPack->create();
           
             if ($this->GpsPack->save($this->data)) {
                 
                if(!empty($this->data['GpsPack']['id'])){
                        $gps_pack_id = $this->data['GpsPack']['id'];
                }else{
                    $gps_pack_id = $this->GpsPack->getLastInsertId();
                }
                    
            if(!empty($this->data['GpsPack']['value'])){
                $question = '1'; $prev_val = '';
                foreach($this->data['GpsPack']['value'] as $val_key=>$values){
                    if(!empty($this->data['GpsPack']['text'][$val_key])){
                    if($this->data['GpsPack']['text'][$val_key] != $prev_val){
                        $question = '1';
                    }
                    }
                    $prev_val = @$this->data['GpsPack']['text'][$val_key];
                    $gps_data['GpsData']['id'] = '';
                    $gps_data['GpsData']['gps_pack_id'] = $gps_pack_id;
                    $gps_data['GpsData']['value'] = $values;
                    $gps_data['GpsData']['text'] = @$this->data['GpsPack']['text'][$val_key];
                    $gps_data['GpsData']['sub_text'] =  @$this->data['GpsPack']['sub_text'][$val_key];
                    $gps_data['GpsData']['question'] = $question;
                    $gps_data['GpsData']['step'] = $this->data['GpsPack']['step'];
                    $question++;
                    
                    $this->GpsData = ClassRegistry::init('GpsData');
                    $this->GpsData->save($gps_data);
                }
            }

                    $next_step = $this->data['GpsPack']['step']+'1';

                    //$this->Session->setFlash(__('GpsPackS has been saved', true));
                    if($next_step == '23' || $next_step > '22'){
                        $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                    }else{
                        $this->redirect(array('action' => 'add',$next_step,$gps_pack_id));
                    }
            } else {
                $this->Session->setFlash(__('The GPS Pack could not be saved. Please, try again.', true));
            }
        }else{
            
            $this->data = $this->GpsPack->find('first', array('conditions' => array('GpsPack.id' => $gps_pack_id, 'GpsPack.status' => 1)));
            $client_id = $this->data['GpsPack']['client_id'];
            $this->set('client_id',$client_id);
            $this->set('GpsPack', $this->data);
            
            $this->GpsSetting = ClassRegistry::init('GpsSetting');
            $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
            $this->set('gps_settings',$gps_settings);
            
            //code to allow only accessed steps ends here
            if(!empty($gps_settings['GpsSetting']['access_steps'])){
                if($gps_settings['GpsSetting']['access_steps'] != 'ALL'){
                    $gps_steps_ids = explode(',',$gps_settings['GpsSetting']['access_steps']);
                    if($step == '5' || $step == '6' || $step == '7' || $step == '8'){ 
                        if(!in_array('5-8',$gps_steps_ids)){
                             $this->redirect(array('action' => 'add','9',$gps_pack_id));
                        }
                    }elseif($step == '14' || $step == '15'){
                        if(!in_array('14-15',$gps_steps_ids)){
                             $this->redirect(array('action' => 'add','16',$gps_pack_id));
                        }
                    }elseif($step == '18' || $step == '19'){
                        if(!in_array('18-19',$gps_steps_ids)){
                             $this->redirect(array('action' => 'add','20',$gps_pack_id));
                        }
                    }else{
                        if(!in_array($step,$gps_steps_ids)){
                            if($step == '22'){
                                $this->redirect(array('action' => 'index',$client_id));
                            }else{
                                 $this->redirect(array('action' => 'add',($step+1),$gps_pack_id));
                            }
                        }
                    }
                 }
            }
            //code to allow only accessed steps ends here
            
            $marketsegments = array();
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            
            if(!empty($this->data['GpsPack']['market_segments'])){
                $marketsegment_ids = explode(',',$this->data['GpsPack']['market_segments']);
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
            }else if(!empty($gps_settings['GpsSetting']['market_segments'])){
                $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
            }
            
            
            $this->set('marketsegments',$marketsegments);
            
            $market_performance = $gps_settings['GpsSetting']['summary_mp_label'];
            $this->set('market_performance',$market_performance);
        }
    }
    
    function client_edit($step=null,$GpsPackId = null) {
        
        $GpsPack = $this->GpsPack->read(null, $GpsPackId);
        $client_id = $GpsPack['GpsPack']['client_id'];
        $this->set('client_id',$client_id);
        $this->set('GpsPack', $GpsPack);
        $gps_pack_id = $GpsPack['GpsPack']['id'];
        
        $month = $GpsPack['GpsPack']['month'];
        $month = sprintf("%02d", $month);
        $year = $GpsPack['GpsPack']['year'];
        $client_id = $GpsPack['GpsPack']['client_id'];

        $this->set('step',$step);
        
        $back_step = $step - '1';
        if($back_step >= '10' && $back_step < '14'){
            $back_step = '9';
        }elseif($back_step == '19'){
            $back_step = '18';
        }
        $next_step = $step + '1';
        $this->set('back_step',$back_step);
        $this->set('next_step',$next_step);
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
                //code to allow only accessed steps ends here
        if(!empty($gps_settings['GpsSetting']['access_steps'])){
            if($gps_settings['GpsSetting']['access_steps'] != 'ALL'){
                $gps_steps_ids = explode(',',$gps_settings['GpsSetting']['access_steps']);
                if($step == '5' || $step == '6' || $step == '7' || $step == '8'){ 
                    if(!in_array('5-8',$gps_steps_ids)){
                         $this->redirect(array('action' => 'edit','9',$gps_pack_id));
                    }
                }elseif($step == '14' || $step == '15'){
                    if(!in_array('14-15',$gps_steps_ids)){
                         $this->redirect(array('action' => 'edit','16',$gps_pack_id));
                    }
                }elseif($step == '18' || $step == '19'){
                    if(!in_array('18-19',$gps_steps_ids)){
                         $this->redirect(array('action' => 'edit','20',$gps_pack_id));
                    }
                }else{
                    if(!in_array($step,$gps_steps_ids)){
                        if($step == '22'){
                            $this->redirect(array('action' => 'index',$client_id));
                        }else{
                             $this->redirect(array('action' => 'edit',($step+1),$gps_pack_id));
                        }
                    }
                }
             }
        }
        //code to allow only accessed steps ends here
        
        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms']+ $gps_settings['GpsSetting']['other_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        $marketsegments = array();
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        if(!empty($GpsPack['GpsPack']['market_segments'])){
            $marketsegment_ids = explode(',',$GpsPack['GpsPack']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }else if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        $this->set('marketsegments',$marketsegments);
        
        $market_performance = $gps_settings['GpsSetting']['summary_mp_label'];
        $this->set('market_performance',$market_performance);
        
        if (!empty($this->data)){     
            
            $gps_pack_id = $this->data['GpsPack']['pack_id'];
            if(!empty($this->data['GpsPack']['value'])){
                    
                $this->GpsData = ClassRegistry::init('GpsData');
                $this->GpsData->deleteAll(array('GpsData.step' => $this->data['GpsPack']['step'], 'GpsData.gps_pack_id' => $gps_pack_id));
                
                $question = '1'; $prev_val = '';
                
                foreach($this->data['GpsPack']['value'] as $val_key=>$values){
                    if(!empty($this->data['GpsPack']['text'][$val_key])){
                    if($this->data['GpsPack']['text'][$val_key] != $prev_val){
                        $question = '1';
                    }
                    }
                    $prev_val = @$this->data['GpsPack']['text'][$val_key];
                    $gps_data['GpsData']['id'] = '';
                    $gps_data['GpsData']['gps_pack_id'] = $gps_pack_id;
                    $gps_data['GpsData']['value'] = $values;
                    $gps_data['GpsData']['text'] = @$this->data['GpsPack']['text'][$val_key];
                    $gps_data['GpsData']['sub_text'] =  @$this->data['GpsPack']['sub_text'][$val_key];
                    $gps_data['GpsData']['question'] = $question;
                    $gps_data['GpsData']['step'] = $this->data['GpsPack']['step'];
                    $question++;
                    
                    $this->GpsData = ClassRegistry::init('GpsData');
                    $this->GpsData->save($gps_data);
                }

                    if($_POST['submit'] == 'Save & Close'){
                        $this->Session->setFlash(__('The GPS Pack data has been saved successfully', true));
                        $this->redirect(array('action' => 'edit_steps',$gps_pack_id));
                    }
                
                    $next_step = $this->data['GpsPack']['step']+'1';

                    if($next_step == '23' || $next_step > '22'){
                        $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                    }else{
                        $this->redirect(array('action' => 'edit',$next_step,$gps_pack_id));
                    }
            } else {
                   $next_step = $this->data['GpsPack']['step']+'1';
                   $this->redirect(array('action' => 'edit',$next_step,$gps_pack_id));
                //$this->Session->setFlash(__('The GpsPackS could not be saved. Please, try again.', true));
            }
        }
    }
 
    
    function admin_settings($client_id=null){
        //Configure::write('debug',2);
        
        $country_array = $this->requestAction('/GpsPacks/get_country_list/');
        
        $this->set('client_id',$client_id);
        $this->set('country_array',$country_array);

        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $market_seg_ids = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
        }else{
            $this->Client = ClassRegistry::init('Client');
            $client_data = $this->Client->find('first',
                array('conditions'=>
                    array('Client.id'=>$client_id,'Client.status'=>1)
                ,'fields'=>'market_segment_ids','recursive'=>'0'));
            $market_seg_ids = explode(',',$client_data['Client']['market_segment_ids']);
        }
        
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$market_seg_ids)));
        $this->set('marketsegments',$marketsegments);
        $this->set('market_seg_ids',$market_seg_ids);
        
        if(!empty($this->data)){
            //echo '<pre>'; print_r($this->data); exit;
            
                if (count($this->data['GpsPack']['Country']) > '65') {
                    $this->Session->setFlash(__('Please add upto 65 countries only', true));
                    $this->redirect(array('action' => 'settings',$this->data['GpsPack']['client_id']));
                }
            
                $this->GpsSetting = ClassRegistry::init('GpsSetting');
                
                $settings_data = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$this->data['GpsPack']['client_id']),'fields'=>'GpsSetting.id'));
                if(!empty($settings_data)){
                    $settings_id = $settings_data['GpsSetting']['id'];
                }else{
                    $settings_id = '';
                }

                $gps_settings_data['GpsSetting']['id'] = $settings_id;
                $gps_settings_data['GpsSetting']['client_id'] = $this->data['GpsPack']['client_id'];
                $gps_settings_data['GpsSetting']['financial_month_start'] = $this->data['GpsPack']['financial_month_start']['month'];
                $gps_settings_data['GpsSetting']['financial_month_end'] = $this->data['GpsPack']['financial_month_end']['month'];
                $gps_settings_data['GpsSetting']['standard_rooms'] = $this->data['GpsPack']['standard_rooms'];
                $gps_settings_data['GpsSetting']['executive_rooms'] = $this->data['GpsPack']['executive_rooms'];
                $gps_settings_data['GpsSetting']['deluxe_rooms'] = $this->data['GpsPack']['deluxe_rooms'];
                $gps_settings_data['GpsSetting']['suites_rooms'] = $this->data['GpsPack']['suites_rooms'];
                $gps_settings_data['GpsSetting']['other_rooms'] = $this->data['GpsPack']['other_rooms'];
                $gps_settings_data['GpsSetting']['market_segments'] = implode(',',$this->data['GpsPack']['MarketSegment']);
                $gps_settings_data['GpsSetting']['countries'] = implode(',',$this->data['GpsPack']['Country']);
                
                
                $gps_settings_data['GpsSetting']['summary_mp_label'] = $this->data['GpsPack']['summary_mp_label'];
                $gps_settings_data['GpsSetting']['geo_list'] = implode(',',$this->data['GpsPack']['province_list']);
                
                //Room Types- Standard|Executive|Deluxe|Suites
                $gps_settings_data['GpsSetting']['roomtypes'] = implode('|',$this->data['GpsPack']['roomtypes']);
                
                $gps_settings_data['GpsSetting']['access_steps'] = implode(',',$this->data['GpsPack']['gps_steps']);
                
                //echo '<pre>'; print_r($this->data);
                
                $gps_settings_data['GpsSetting']['channels_gds'] = json_encode($this->data['channels_gds_ar']);
                $gps_settings_data['GpsSetting']['channels_online'] = json_encode($this->data['channels_online_ar']);
                $gps_settings_data['GpsSetting']['channels_direct'] = json_encode($this->data['channels_direct_ar']);
                
                if ($this->GpsSetting->save($gps_settings_data)) {
                    $this->Session->setFlash(__('GPS Settings saved successfully.', true));
                    $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                } else {
                    $this->Session->setFlash(__('The GPS Settings could not be saved. Please, try again.', true));
                }
        }
    }
    
   
    function client_settings($client_id=null){
        
        $country_array = $this->requestAction('/GpsPacks/get_country_list/');
        
        $this->set('client_id',$client_id);
        $this->set('country_array',$country_array);

        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $market_seg_ids = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
        }else{
            $this->Client = ClassRegistry::init('Client');
            $client_data = $this->Client->find('first',
                array('conditions'=>
                    array('Client.id'=>$client_id,'Client.status'=>1)
                ,'fields'=>'market_segment_ids','recursive'=>'0'));
            $market_seg_ids = explode(',',$client_data['Client']['market_segment_ids']);
        }
        
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$market_seg_ids)));
        $this->set('marketsegments',$marketsegments);
        $this->set('market_seg_ids',$market_seg_ids);
        
        if(!empty($this->data)){
            
                if (count($this->data['GpsPack']['Country']) > '65') {
                    $this->Session->setFlash(__('Please add upto 65 countries only', true));
                    $this->redirect(array('action' => 'settings',$this->data['GpsPack']['client_id']));
                }
            
                $this->GpsSetting = ClassRegistry::init('GpsSetting');
                
                $settings_data = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$this->data['GpsPack']['client_id']),'fields'=>'GpsSetting.id'));
                if(!empty($settings_data)){
                    $settings_id = $settings_data['GpsSetting']['id'];
                }else{
                    $settings_id = '';
                }

                $gps_settings_data['GpsSetting']['id'] = $settings_id;
                $gps_settings_data['GpsSetting']['client_id'] = $this->data['GpsPack']['client_id'];
                $gps_settings_data['GpsSetting']['financial_month_start'] = $this->data['GpsPack']['financial_month_start']['month'];
                $gps_settings_data['GpsSetting']['financial_month_end'] = $this->data['GpsPack']['financial_month_end']['month'];
                $gps_settings_data['GpsSetting']['standard_rooms'] = $this->data['GpsPack']['standard_rooms'];
                $gps_settings_data['GpsSetting']['executive_rooms'] = $this->data['GpsPack']['executive_rooms'];
                $gps_settings_data['GpsSetting']['deluxe_rooms'] = $this->data['GpsPack']['deluxe_rooms'];
                $gps_settings_data['GpsSetting']['suites_rooms'] = $this->data['GpsPack']['suites_rooms'];
                $gps_settings_data['GpsSetting']['other_rooms'] = $this->data['GpsPack']['other_rooms'];
                $gps_settings_data['GpsSetting']['market_segments'] = implode(',',$this->data['GpsPack']['MarketSegment']);
                $gps_settings_data['GpsSetting']['countries'] = implode(',',$this->data['GpsPack']['Country']);
                
                $gps_settings_data['GpsSetting']['summary_mp_label'] = $this->data['GpsPack']['summary_mp_label'];
                $gps_settings_data['GpsSetting']['geo_list'] = implode(',',$this->data['GpsPack']['province_list']);
                
                //Room Types- Standard|Executive|Deluxe|Suites
                $gps_settings_data['GpsSetting']['roomtypes'] = implode('|',$this->data['GpsPack']['roomtypes']);
                
                
                //$gps_settings_data['GpsSetting']['access_steps'] = implode(',',$this->data['GpsPack']['gps_steps']);
                
                //echo '<pre>'; print_r($this->data);
                
                $gps_settings_data['GpsSetting']['channels_gds'] = json_encode($this->data['channels_gds_ar']);
                $gps_settings_data['GpsSetting']['channels_online'] = json_encode($this->data['channels_online_ar']);
                $gps_settings_data['GpsSetting']['channels_direct'] = json_encode($this->data['channels_direct_ar']);
                
                if ($this->GpsSetting->save($gps_settings_data)) {
                    $this->Session->setFlash(__('GPS Settings saved successfully.', true));
                    $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                } else {
                    $this->Session->setFlash(__('The GPS Settings could not be saved. Please, try again.', true));
                }
        }
    }
  
    
    function staff_settings($client_id=null){
        
        $country_array = $this->requestAction('/GpsPacks/get_country_list/');
        
        $this->set('client_id',$client_id);
        $this->set('country_array',$country_array);

        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $market_seg_ids = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
        }else{
            $this->Client = ClassRegistry::init('Client');
            $client_data = $this->Client->find('first',
                array('conditions'=>
                    array('Client.id'=>$client_id,'Client.status'=>1)
                ,'fields'=>'market_segment_ids','recursive'=>'0'));
            $market_seg_ids = explode(',',$client_data['Client']['market_segment_ids']);
        }
        
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$market_seg_ids)));
        $this->set('marketsegments',$marketsegments);
        $this->set('market_seg_ids',$market_seg_ids);
        
        if(!empty($this->data)){
            
                if (count($this->data['GpsPack']['Country']) > '65') {
                    $this->Session->setFlash(__('Please add upto 65 countries only', true));
                    $this->redirect(array('action' => 'settings',$this->data['GpsPack']['client_id']));
                }
            
                $this->GpsSetting = ClassRegistry::init('GpsSetting');
                
                $settings_data = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$this->data['GpsPack']['client_id']),'fields'=>'GpsSetting.id'));
                if(!empty($settings_data)){
                    $settings_id = $settings_data['GpsSetting']['id'];
                }else{
                    $settings_id = '';
                }

                $gps_settings_data['GpsSetting']['id'] = $settings_id;
                $gps_settings_data['GpsSetting']['client_id'] = $this->data['GpsPack']['client_id'];
                $gps_settings_data['GpsSetting']['financial_month_start'] = $this->data['GpsPack']['financial_month_start']['month'];
                $gps_settings_data['GpsSetting']['financial_month_end'] = $this->data['GpsPack']['financial_month_end']['month'];
                $gps_settings_data['GpsSetting']['standard_rooms'] = $this->data['GpsPack']['standard_rooms'];
                $gps_settings_data['GpsSetting']['executive_rooms'] = $this->data['GpsPack']['executive_rooms'];
                $gps_settings_data['GpsSetting']['deluxe_rooms'] = $this->data['GpsPack']['deluxe_rooms'];
                $gps_settings_data['GpsSetting']['suites_rooms'] = $this->data['GpsPack']['suites_rooms'];
                $gps_settings_data['GpsSetting']['other_rooms'] = $this->data['GpsPack']['other_rooms'];
                $gps_settings_data['GpsSetting']['market_segments'] = implode(',',$this->data['GpsPack']['MarketSegment']);
                $gps_settings_data['GpsSetting']['countries'] = implode(',',$this->data['GpsPack']['Country']);
                
                $gps_settings_data['GpsSetting']['summary_mp_label'] = $this->data['GpsPack']['summary_mp_label'];
                $gps_settings_data['GpsSetting']['geo_list'] = implode(',',$this->data['GpsPack']['province_list']);
                
                //Room Types- Standard|Executive|Deluxe|Suites
                $gps_settings_data['GpsSetting']['roomtypes'] = implode('|',$this->data['GpsPack']['roomtypes']);
                
                $gps_settings_data['GpsSetting']['channels_gds'] = json_encode($this->data['channels_gds_ar']);
                $gps_settings_data['GpsSetting']['channels_online'] = json_encode($this->data['channels_online_ar']);
                $gps_settings_data['GpsSetting']['channels_direct'] = json_encode($this->data['channels_direct_ar']);
                
                if ($this->GpsSetting->save($gps_settings_data)) {
                    $this->Session->setFlash(__('GPS Settings saved successfully.', true));
                    $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                } else {
                    $this->Session->setFlash(__('The GPS Settings could not be saved. Please, try again.', true));
                }
        }
    }
    
    
    function admin_edit_steps($gps_id=null){
        $this->set('gps_id',$gps_id);
        $GpsPack = $this->GpsPack->read(null, $gps_id);
        
        $client_id = $GpsPack['GpsPack']['client_id'];
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id),'fields'=>array('access_steps')));
        $this->set('gps_settings',$gps_settings);
        
        $this->set('GpsPack', $GpsPack);
    }
    
    function client_edit_steps($gps_id=null){
        $this->set('gps_id',$gps_id);
        $GpsPack = $this->GpsPack->read(null, $gps_id);
        $this->set('GpsPack', $GpsPack);
        
        $client_id = $GpsPack['GpsPack']['client_id'];
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id),'fields'=>array('access_steps')));
        $this->set('gps_settings',$gps_settings);
    }
 
    function admin_steps($client_id=null,$selected_child_hotel=null) {
        $this->set('selected_child_hotel', $selected_child_hotel);
        $this->set('client_id', $client_id);
        $child_data = $this->requestAction('/GpsPacks/get_child_list/'.$client_id);
        $this->set('child_data', $child_data);
        
        $this->User = ClassRegistry::init('User');
        if(!empty($selected_child_hotel)){
            $users = $this->User->find('all',array('conditions'=>array('User.client_id'=>$selected_child_hotel,'User.status'<>'2'),'fields'=>array('User.id,User.firstname,User.lastname'),'recursive'=>'-1'));
        }else{
            $users = $this->User->find('all',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id,User.firstname,User.lastname'),'recursive'=>'-1'));
        }
        $this->set('users', $users);
    }
    
    function admin_assign($user_id=null,$client_id=null){
        $this->set('user_id', $user_id);
        $this->set('client_id', $client_id);
                
        if(!empty($this->data)){
            $this->GpsUser = ClassRegistry::init('GpsUser');
            $gps_user_data = $this->GpsUser->find('first',array('conditions'=>array('GpsUser.user_id'=>$this->data['GpsUser']['user_id']),'fields'=>'GpsUser.id'));
            if(!empty($gps_user_data)){
                $gps_user_id = $settings_data['GpsSetting']['id'];
            }else{
                $gps_user_id = '';
            }
            
            $this->data['GpsUser']['step_module'] = implode(',',$this->data['GpsUser']['Steps']);
            $this->data['GpsUser']['id'] = $gps_user_id;
            
            if ($this->GpsUser->save($this->data)) {
                $this->redirect(array('action' => 'steps',$this->data['GpsUser']['client_id']));
            } else {
                $this->Session->setFlash(__('Unable be saved. Please, try again.', true));
            }
        }else{
            $this->GpsUser = ClassRegistry::init('GpsUser');
            $gps_user_data = $this->GpsUser->find('first',array('conditions'=>array('GpsUser.user_id'=>$user_id)));
            $this->set('gps_user_data',$gps_user_data);
        }        
    }
    
    
    function client_steps($client_id=null,$selected_child_hotel=null) {
        $this->set('selected_child_hotel', $selected_child_hotel);
        $this->set('client_id', $client_id);
        $child_data = $this->requestAction('/GpsPacks/get_child_list/'.$client_id);
        $this->set('child_data', $child_data);
        
        $this->User = ClassRegistry::init('User');
        if(!empty($selected_child_hotel)){
            $users = $this->User->find('all',array('conditions'=>array('User.client_id'=>$selected_child_hotel,'User.status'<>'2'),'fields'=>array('User.id,User.firstname,User.lastname'),'recursive'=>'-1'));
        }else{
            $users = $this->User->find('all',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id,User.firstname,User.lastname'),'recursive'=>'-1'));
        }
        $this->set('users', $users);
    }
    
    function client_assign($user_id=null,$client_id=null){
        $this->set('user_id', $user_id);
        $this->set('client_id', $client_id);
                
        if(!empty($this->data)){
            $this->GpsUser = ClassRegistry::init('GpsUser');
            $gps_user_data = $this->GpsUser->find('first',array('conditions'=>array('GpsUser.user_id'=>$this->data['GpsUser']['user_id']),'fields'=>'GpsUser.id'));
            if(!empty($gps_user_data)){
                $gps_user_id = $settings_data['GpsSetting']['id'];
            }else{
                $gps_user_id = '';
            }
            
            $this->data['GpsUser']['step_module'] = implode(',',$this->data['GpsUser']['Steps']);
            $this->data['GpsUser']['id'] = $gps_user_id;
            
            if ($this->GpsUser->save($this->data)) {
                $this->redirect(array('action' => 'steps',$this->data['GpsUser']['client_id']));
            } else {
                $this->Session->setFlash(__('Unable be saved. Please, try again.', true));
            }
        }else{
            $this->GpsUser = ClassRegistry::init('GpsUser');
            $gps_user_data = $this->GpsUser->find('first',array('conditions'=>array('GpsUser.user_id'=>$user_id)));
            $this->set('gps_user_data',$gps_user_data);
        }        
    }
    
    
    function get_child_list($client_id){
        $this->layout = '';
        $this->autoRender = false;
        
        // Code for the dropdown of all child hotel list
        $this->Client = ClassRegistry::init('Client');
        $hotel_data = $this->Client->find('list',
            array('conditions'=>
                array('Client.id'=>$client_id,'Client.status'=>1)
            ,'fields'=>'id,hotelname','recursive'=>'0'));
        
        $child_data = $this->Client->find('list',
            array('conditions'=>
                array('Client.parent_id'=>$client_id,'Client.status'=>1)
            ,'fields'=>'id,hotelname'));
        
        if(!empty($child_data)){
            $child_data[$client_id] = $hotel_data[$client_id];
        }
        return $child_data;
    }


    function get_country_list(){
        $this->layout = '';
        $this->autoRender = false;
        
        $country_array['Angola'] = 'Angola';
        $country_array['Benin'] = 'Benin';
        $country_array['Botswana'] = 'Botswana';
        $country_array['Burkina Faso'] = 'Burkina Faso';
        $country_array['Burundi'] = 'Burundi';
        $country_array['Cayman Islands'] = 'Cayman Islands';
        $country_array['Cameroon'] = 'Cameroon';
        $country_array['Cape Verde'] = 'Cape Verde';
        $country_array['Congo'] = 'Congo';
        $country_array['DRC'] = 'DRC';
        $country_array['Egypt'] = 'Egypt';
        $country_array['Ethiopia'] = 'Ethiopia';
        $country_array['Gabon'] = 'Gabon';
        $country_array['Gambia'] = 'Gambia';
        $country_array['Ghana'] = 'Ghana';
        
        $country_array['Guam'] = 'Guam';
        $country_array['Guinea'] = 'Guinea';
        $country_array['Iran'] = 'Iran';
        $country_array['Iraq'] = 'Iraq';
        $country_array['Israel'] = 'Israel';
        $country_array['Kenya'] = 'Kenya';
        $country_array['Lesotho'] = 'Lesotho';
        $country_array['Liberia'] = 'Liberia';
        $country_array['Lybia'] = 'Lybia';
        $country_array['Malawi'] = 'Malawi';
        $country_array['Madagascar'] = 'Madagascar';
        $country_array['Mauritius'] = 'Mauritius';
        $country_array['Morrocco'] = 'Morrocco';
        $country_array['Mozambique'] = 'Mozambique';
        $country_array['Namibia'] = 'Namibia';
        $country_array['Niger'] = 'Niger';
        $country_array['Nigeria'] = 'Nigeria';
        $country_array['Senegal'] = 'Senegal';
        $country_array['Seychelles'] = 'Seychelles';
        $country_array['Sierra Leone'] = 'Sierra Leone';
        $country_array['South Africa'] = 'South Africa';
        $country_array['Sudan'] = 'Sudan';
        $country_array['Swaziland'] = 'Swaziland';
        $country_array['Rwanda'] = 'Rwanda';
        $country_array['Tanzania'] = 'Tanzania';
        $country_array['Togo'] = 'Togo';
        $country_array['Tunisia'] = 'Tunisia';
        $country_array['Uganda'] = 'Uganda';
        $country_array['Zambia'] = 'Zambia';
        $country_array['Zimbabwe'] = 'Zimbabwe';
        
        
        $country_array['Austria'] = 'Austria';
        $country_array['Belguim'] = 'Belguim';
        $country_array['Bolivia'] = 'Bolivia';
        $country_array['British Indian Ocean'] = 'British Indian Ocean';
        $country_array['Croatia'] = 'Croatia';
        $country_array['Czech Rebublic'] = 'Czech Republic';
        $country_array['Denmark'] = 'Denmark';
        $country_array['Ecuador'] = 'Ecuador';
        $country_array['France'] = 'France';
        $country_array['Finland'] = 'Finland';
        $country_array['French Guiana'] = 'French Guiana';
        $country_array['Georgia'] = 'Georgia';
        $country_array['Germany'] = 'Germany';
        $country_array['Greece'] = 'Greece';
        $country_array['Hungary'] = 'Hungary';
        $country_array['Ireland'] = 'Ireland';
        $country_array['Italy'] = 'Italy';
        $country_array['Luxembourg'] = 'Luxembourg';
        $country_array['Netherlands'] = 'Netherlands';
        $country_array['Norway'] = 'Norway';
        $country_array['Poland'] = 'Poland';
        $country_array['Portugal'] = 'Portugal';
        $country_array['Romania'] = 'Romania';
        $country_array['Spain'] = 'Spain';
        $country_array['Slovakia'] = 'Slovakia';
        $country_array['Sweden'] = 'Sweden';
        $country_array['Switzerland'] = 'Switzerland';
        $country_array['Turkey'] = 'Turkey';
        $country_array['UK'] = 'UK';
        $country_array['Ukraine'] = 'Ukraine';
        
        
        $country_array['American Samoa'] = 'American Samoa';
        $country_array['Argentina'] = 'Argentina';
        $country_array['Brazil'] = 'Brazil';
        $country_array['Canada'] = 'Canada';
        $country_array['Chile'] = 'Chile';
        $country_array['Colombia'] = 'Colombia';
        $country_array['Cuba'] = 'Cuba';
        $country_array['Dominican Republic'] = 'Dominican Republic';
        $country_array['Mexico'] = 'Mexico';
        $country_array['Peru'] = 'Peru';
        $country_array['Suriname'] = 'Suriname';
        $country_array['Uruguay'] = 'Uruguay';
        $country_array['USA'] = 'USA';
        
        
        $country_array['Bangladesh'] = 'Bangladesh';
        $country_array['Benin'] = 'Benin';
        $country_array['China'] = 'China';
        $country_array['Hong Kong'] = 'Hong Kong';
        $country_array['India'] = 'India';
        $country_array['Iran'] = 'Iran';
        $country_array['Japan'] = 'Japan';
        $country_array['Jordan'] = 'Jordan';
        $country_array['Kuwait'] = 'Kuwait';
        $country_array['Malaysia'] = 'Malaysia';
        $country_array['Pakistan'] = 'Pakistan';
        $country_array['Philippines'] = 'Philippines';
        $country_array['Qatar'] = 'Qatar';
        $country_array['Saudi Arabia'] = 'Saudi Arabia';
        $country_array['Singapore'] = 'Singapore';
        $country_array['Taiwan'] = 'Taiwan';
        $country_array['Thailand'] = 'Thailand';
        $country_array['UAE'] = 'UAE';
        $country_array['Korea'] = 'Korea';
        $country_array['Indonesia'] = 'Indonesia';
        
        
        $country_array['Australia'] = 'Australia';
        $country_array['Nauru'] = 'Nauru';
        $country_array['New Zealand'] = 'New Zealand';
        
        
        $country_array['Antigua'] = 'Antigua';
        $country_array['Barbados'] = 'Barbados';
        $country_array['Belgium'] = 'Belgium';
        $country_array['Bermuda'] = 'Bermuda';
        $country_array['Grt Britain'] = 'Grt Britain';
        $country_array['Guernsey'] = 'Guernsey';
        $country_array['Jamaica'] = 'Jamaica';
        $country_array['Jersey'] = 'Jersey';
        $country_array['Monaco'] = 'Monaco';
        $country_array['Russia'] = 'Russia';
        $country_array['Saint Martin'] = 'Saint Martin';
        $country_array['Trinidad'] = 'Trinidad';
        $country_array['Virgin Islds'] = 'Virgin Islds';
        $country_array['West Indies'] = 'West Indies';
        $country_array['Yugoslavia'] = 'Yugoslavia';
        
        $country_array['Other'] = 'Other';
        $country_array['No Country'] = 'No Country';
        
        return $country_array;
    }
 
    function staff_index($client_id=null) {
        $conditions = array('GpsPack.status !=' => 2,'GpsPack.client_id'=>$client_id);
        $userGpsPacks = $this->GpsPack->find('all', array('conditions' => $conditions));
        $this->set('userGpsPacks', $userGpsPacks);
        $this->set('client_id',$client_id);
    }

    function staff_edit_steps($gps_id=null){

       $this->set('gps_id',$gps_id);
        $GpsPack = $this->GpsPack->read(null, $gps_id);
        $this->set('GpsPack', $GpsPack);
        
        $client_id = $GpsPack['GpsPack']['client_id'];
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id),'fields'=>array('access_steps')));
        $this->set('gps_settings',$gps_settings);
    }
    
    
     function admin_segments($gps_pack_id=null){
        //Configure::write('debug',2);
        
        $GpsPack = $this->GpsPack->read(null, $gps_pack_id);
        $this->set('GpsPack', $GpsPack);
   
        $client_id = $GpsPack['GpsPack']['client_id'];
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $market_seg_ids = array();
        if(!empty($GpsPack['GpsPack']['market_segments'])){
            $market_seg_ids = explode(',',$GpsPack['GpsPack']['market_segments']);
        }else if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
        }
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$market_seg_ids)));
        $this->set('marketsegments',$marketsegments);
        $this->set('market_seg_ids',$market_seg_ids);
      
        if(!empty($this->data)){
            
                $gps_data['GpsPack']['id'] = $this->data['GpsPack']['id'];
                $gps_data['GpsPack']['market_segments'] = implode(',',$this->data['GpsPack']['MarketSegment']);

                if ($this->GpsPack->save($gps_data)) {
                    $this->Session->setFlash(__('Market Segments saved successfully.', true));
                    $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                } else {
                    $this->Session->setFlash(__('Settings could not be saved. Please, try again.', true));
                }
        }
    }
   
    function client_segments($gps_pack_id=null){
        //Configure::write('debug',2);
        $GpsPack = $this->GpsPack->read(null, $gps_pack_id);
        $this->set('GpsPack', $GpsPack);
   
        $client_id = $GpsPack['GpsPack']['client_id'];
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $market_seg_ids = array();
        if(!empty($GpsPack['GpsPack']['market_segments'])){
            $market_seg_ids = explode(',',$GpsPack['GpsPack']['market_segments']);
        }else if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
        }
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$market_seg_ids)));
        $this->set('marketsegments',$marketsegments);
        $this->set('market_seg_ids',$market_seg_ids);
        
        if(!empty($this->data)){
                $gps_data['GpsPack']['id'] = $this->data['GpsPack']['id'];
                $gps_data['GpsPack']['market_segments'] = implode(',',$this->data['GpsPack']['MarketSegment']);

                if ($this->GpsPack->save($gps_data)) {
                    $this->Session->setFlash(__('Market Segments saved successfully.', true));
                    $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                } else {
                    $this->Session->setFlash(__('Settings could not be saved. Please, try again.', true));
                }
        }
    }
   
    function staff_segments($gps_pack_id=null){
        //Configure::write('debug',2);
        
        $GpsPack = $this->GpsPack->read(null, $gps_pack_id);
        $this->set('GpsPack', $GpsPack);
   
        $client_id = $GpsPack['GpsPack']['client_id'];
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $market_seg_ids = array();
        if(!empty($GpsPack['GpsPack']['market_segments'])){
            $market_seg_ids = explode(',',$GpsPack['GpsPack']['market_segments']);
        }else if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
        }
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$market_seg_ids)));
        $this->set('marketsegments',$marketsegments);
        $this->set('market_seg_ids',$market_seg_ids);
        
        if(!empty($this->data)){
                $gps_data['GpsPack']['id'] = $this->data['GpsPack']['id'];
                $gps_data['GpsPack']['market_segments'] = implode(',',$this->data['GpsPack']['MarketSegment']);

                if ($this->GpsPack->save($gps_data)) {
                    $this->Session->setFlash(__('Market Segments saved successfully.', true));
                    $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                } else {
                    $this->Session->setFlash(__('Settings could not be saved. Please, try again.', true));
                }
        }
    }
   
    function staff_edit($step=null,$GpsPackId = null) {
         $GpsPack = $this->GpsPack->read(null, $GpsPackId);
        $client_id = $GpsPack['GpsPack']['client_id'];
        $this->set('client_id',$client_id);
        $this->set('GpsPack', $GpsPack);
        $gps_pack_id = $GpsPack['GpsPack']['id'];
        
        $month = $GpsPack['GpsPack']['month'];
        $month = sprintf("%02d", $month);
        $year = $GpsPack['GpsPack']['year'];
        $client_id = $GpsPack['GpsPack']['client_id'];

        $this->set('step',$step);
        
        $back_step = $step - '1';
        if($back_step >= '10' && $back_step < '14'){
            $back_step = '9';
        }elseif($back_step == '19'){
            $back_step = '18';
        }
        $next_step = $step + '1';
        $this->set('back_step',$back_step);
        $this->set('next_step',$next_step);
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
                //code to allow only accessed steps ends here
        if(!empty($gps_settings['GpsSetting']['access_steps'])){
            if($gps_settings['GpsSetting']['access_steps'] != 'ALL'){
                $gps_steps_ids = explode(',',$gps_settings['GpsSetting']['access_steps']);
                if($step == '5' || $step == '6' || $step == '7' || $step == '8'){ 
                    if(!in_array('5-8',$gps_steps_ids)){
                         $this->redirect(array('action' => 'edit','9',$gps_pack_id));
                    }
                }elseif($step == '14' || $step == '15'){
                    if(!in_array('14-15',$gps_steps_ids)){
                         $this->redirect(array('action' => 'edit','16',$gps_pack_id));
                    }
                }elseif($step == '18' || $step == '19'){
                    if(!in_array('18-19',$gps_steps_ids)){
                         $this->redirect(array('action' => 'edit','20',$gps_pack_id));
                    }
                }else{
                    if(!in_array($step,$gps_steps_ids)){
                        if($step == '22'){
                            $this->redirect(array('action' => 'index',$client_id));
                        }else{
                             $this->redirect(array('action' => 'edit',($step+1),$gps_pack_id));
                        }
                    }
                }
             }
        }
        //code to allow only accessed steps ends here
        
        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms']+ $gps_settings['GpsSetting']['other_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        $marketsegments = array();
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        if(!empty($GpsPack['GpsPack']['market_segments'])){
            $marketsegment_ids = explode(',',$GpsPack['GpsPack']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }else if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        $this->set('marketsegments',$marketsegments);
        
        $market_performance = $gps_settings['GpsSetting']['summary_mp_label'];
        $this->set('market_performance',$market_performance);
        
        if (!empty($this->data)){     
            
            $gps_pack_id = $this->data['GpsPack']['pack_id'];
            if(!empty($this->data['GpsPack']['value'])){
                
                    $this->GpsData = ClassRegistry::init('GpsData');
                    $this->GpsData->deleteAll(array('GpsData.step' => $this->data['GpsPack']['step'], 'GpsData.gps_pack_id' => $gps_pack_id));

                    $question = '1'; $prev_val = '';

                    foreach($this->data['GpsPack']['value'] as $val_key=>$values){
                        if(!empty($this->data['GpsPack']['text'][$val_key])){
                        if($this->data['GpsPack']['text'][$val_key] != $prev_val){
                            $question = '1';
                        }
                        }
                        $prev_val = @$this->data['GpsPack']['text'][$val_key];
                        $gps_data['GpsData']['id'] = '';
                        $gps_data['GpsData']['gps_pack_id'] = $gps_pack_id;
                        $gps_data['GpsData']['value'] = $values;
                        $gps_data['GpsData']['text'] = @$this->data['GpsPack']['text'][$val_key];
                        $gps_data['GpsData']['sub_text'] =  @$this->data['GpsPack']['sub_text'][$val_key];
                        $gps_data['GpsData']['question'] = $question;
                        $gps_data['GpsData']['step'] = $this->data['GpsPack']['step'];
                        $question++;

                        $this->GpsData = ClassRegistry::init('GpsData');
                        $this->GpsData->save($gps_data);
                    }

                    if($_POST['submit'] == 'Save & Close'){
                        $this->Session->setFlash(__('The GPS Pack data has been saved successfully', true));
                        $this->redirect(array('action' => 'edit_steps',$gps_pack_id));
                    }
                
                    $next_step = $this->data['GpsPack']['step']+'1';

                    if($next_step == '23' || $next_step > '22'){
                        $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                    }else{
                        $this->redirect(array('action' => 'edit',$next_step,$gps_pack_id));
                    }
            } else {
                   $next_step = $this->data['GpsPack']['step']+'1';
                   $this->redirect(array('action' => 'edit',$next_step,$gps_pack_id));
                //$this->Session->setFlash(__('The GpsPackS could not be saved. Please, try again.', true));
            }
        }
    }
    
    function staff_view($GpsPackId = null) {
       if (!$GpsPackId) {
            $this->Session->setFlash(__('Invalid GPS Pack ID', true));
            $this->redirect(array('action' => 'index'));
        }
        $GpsPack = $this->GpsPack->read(null, $GpsPackId);
        $this->set('GpsPack', $GpsPack);
        $gps_pack_id = $GpsPack['GpsPack']['id'];
        
        $month = $GpsPack['GpsPack']['month'];
        $month = sprintf("%02d", $month);
        $year = $GpsPack['GpsPack']['year'];
        $client_id = $GpsPack['GpsPack']['client_id'];
        
//        $market_performance = 'Pretoria and Surroundings - Upscale & Upper Mid';
//        if($client_id == '67'){
//            $market_performance = 'Johannesburg: Upper and Upper-midscale';
//        }elseif($client_id == '70'){
//            $market_performance = 'Pretoria Upscale Upper Mid';
//        }elseif($client_id == '81'){
//            $market_performance = 'Pretoria & Surrounds';
//        }elseif($client_id == '80'){
//            $market_performance = 'Sandton and Surroundings - Upscale & Upper Mid';
//        }elseif($client_id == '69'){
//            $market_performance = 'Sandton and Surroundings - Upscale & Upper Mid';
//        }
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);

        $market_performance = $gps_settings['GpsSetting']['summary_mp_label'];
        $this->set('market_performance',$market_performance);
        
        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms']+ $gps_settings['GpsSetting']['other_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        
        $marketsegments = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        
        $this->set('marketsegments',$marketsegments);   
    }
    
    
 function staff_add($step=null,$gps_pack_id=null) {
        $this->set('step',$step);
        
        if (!empty($this->data)) {
            
           $this->GpsPack->create();
           
             if ($this->GpsPack->save($this->data)) {
                 
                if(!empty($this->data['GpsPack']['id'])){
                        $gps_pack_id = $this->data['GpsPack']['id'];
                }else{
                    $gps_pack_id = $this->GpsPack->getLastInsertId();
                }
                    
            if(!empty($this->data['GpsPack']['value'])){
                $question = '1'; $prev_val = '';
                foreach($this->data['GpsPack']['value'] as $val_key=>$values){
                    if(!empty($this->data['GpsPack']['text'][$val_key])){
                    if($this->data['GpsPack']['text'][$val_key] != $prev_val){
                        $question = '1';
                    }
                    }
                    $prev_val = @$this->data['GpsPack']['text'][$val_key];
                    $gps_data['GpsData']['id'] = '';
                    $gps_data['GpsData']['gps_pack_id'] = $gps_pack_id;
                    $gps_data['GpsData']['value'] = $values;
                    $gps_data['GpsData']['text'] = @$this->data['GpsPack']['text'][$val_key];
                    $gps_data['GpsData']['sub_text'] =  @$this->data['GpsPack']['sub_text'][$val_key];
                    $gps_data['GpsData']['question'] = $question;
                    $gps_data['GpsData']['step'] = $this->data['GpsPack']['step'];
                    $question++;
                    
                    $this->GpsData = ClassRegistry::init('GpsData');
                    $this->GpsData->save($gps_data);
                }
            }

                    $next_step = $this->data['GpsPack']['step']+'1';

                    //$this->Session->setFlash(__('GpsPackS has been saved', true));
                    if($next_step == '23' || $next_step > '22'){
                        $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                    }else{
                        $this->redirect(array('action' => 'add',$next_step,$gps_pack_id));
                    }
            } else {
                $this->Session->setFlash(__('The GPS Pack could not be saved. Please, try again.', true));
            }
        }else{
            
            $this->data = $this->GpsPack->find('first', array('conditions' => array('GpsPack.id' => $gps_pack_id, 'GpsPack.status' => 1)));
            $client_id = $this->data['GpsPack']['client_id'];
            $this->set('client_id',$client_id);
            $this->set('GpsPack', $this->data);
            
            $this->GpsSetting = ClassRegistry::init('GpsSetting');
            $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
            $this->set('gps_settings',$gps_settings);
            
            //code to allow only accessed steps ends here
            if(!empty($gps_settings['GpsSetting']['access_steps'])){
                if($gps_settings['GpsSetting']['access_steps'] != 'ALL'){
                    $gps_steps_ids = explode(',',$gps_settings['GpsSetting']['access_steps']);
                    if($step == '5' || $step == '6' || $step == '7' || $step == '8'){ 
                        if(!in_array('5-8',$gps_steps_ids)){
                             $this->redirect(array('action' => 'add','9',$gps_pack_id));
                        }
                    }elseif($step == '14' || $step == '15'){
                        if(!in_array('14-15',$gps_steps_ids)){
                             $this->redirect(array('action' => 'add','16',$gps_pack_id));
                        }
                    }elseif($step == '18' || $step == '19'){
                        if(!in_array('18-19',$gps_steps_ids)){
                             $this->redirect(array('action' => 'add','20',$gps_pack_id));
                        }
                    }else{
                        if(!in_array($step,$gps_steps_ids)){
                            if($step == '22'){
                                $this->redirect(array('action' => 'index',$client_id));
                            }else{
                                 $this->redirect(array('action' => 'add',($step+1),$gps_pack_id));
                            }
                        }
                    }
                 }
            }
            //code to allow only accessed steps ends here
            
            $marketsegments = array();
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            if(!empty($this->data['GpsPack']['market_segments'])){
                $marketsegment_ids = explode(',',$this->data['GpsPack']['market_segments']);
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
            }else if(!empty($gps_settings['GpsSetting']['market_segments'])){
                $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
            }
            $this->set('marketsegments',$marketsegments);
            
            $market_performance = $gps_settings['GpsSetting']['summary_mp_label'];
            $this->set('market_performance',$market_performance);
        
            
        }
    }
    
    public function get_channels_vals($client_id=null,$month=null,$year=null,$sub_text=null,$text=null,$step=null){
        $this->layout = '';
        $this->autoRender = false;
        
        if($sub_text == '0'){
            $sub_text ='';
        }else{
            $sub_text = str_replace('_','/',$sub_text);
        }
        
        $this->GpsPack->recursive = '-1';
        $gps_data = $this->GpsPack->find('first', array('conditions' => array('GpsPack.month' => $month, 'GpsPack.year' => $year, 'GpsPack.status' => 1)));
        $gpsDatas = array();
        if(!empty($gps_data)){
            $gps_id = $gps_data['GpsPack']['id'];
            $this->GpsData = ClassRegistry::init('GpsData');
            $conditions =  array('GpsData.gps_pack_id'=>$gps_id,'GpsData.step'=>$step,'GpsData.sub_text'=>$sub_text,'GpsData.text'=>$text);
            $gpsDatas = $this->GpsData->find('list', array('conditions' =>$conditions,'fields'=>array('question','value')));
        }
        return $gpsDatas;
    }
    
    public function import_countries($gps_pack_id=null,$type='edit'){
        
        $this->set('gps_pack_id',$gps_pack_id);
        $this->set('type',$type);
        
        if(!empty($this->data)){
           // echo '<pre>'; print_r($this->data); exit;
            
            if (!$this->data['GpsPack']['browse_file']['name']) {
                $this->Session->setFlash(__('Please uploaded file!', true));
                $this->redirect(array('action' => 'import_countries', $gps_pack_id,$type));
            }

            $path_parts = pathinfo($this->data['GpsPack']['browse_file']["name"]);
            $extension = $path_parts['extension'];

            if ($extension != 'xls') {
                $this->Session->setFlash(__('Please uploaded excel(.xls) file!', true));
                $this->redirect(array('action' => 'import_countries', $gps_pack_id,$type));
            }

            $handle = fopen($this->data['GpsPack']['browse_file']['tmp_name'], 'r');
            if (!$handle) {
                $this->Session->setFlash(__('Cannot open uploaded file!', true));
                $this->redirect(array('action' => 'import_countries', $gps_pack_id,$type));
            }

            $new_data = array();
            $row = 1;

            if ($handle) {
                App::import('Vendor', 'php-excel-reader/excel_reader2'); //import statement
                $wdata = new Spreadsheet_Excel_Reader($this->data['GpsPack']['browse_file']['tmp_name'], true);
                $ndata = $wdata->sheets;
                //echo '<pre>'; print_r($ndata); exit;
                
                $pdata = $ndata[0]['cells'][1][1];
                $fetch_month = explode('/',$pdata);
                //print_r($pdata);
                //print_r($fetch_month);
                echo 'Month:'.$month = $fetch_month['1'];
                $year = $fetch_month['2'];
                
                $pack_data = $this->GpsPack->read(null, $gps_pack_id);
                $this->GpsSetting = ClassRegistry::init('GpsSetting');
                $settings_data = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$pack_data['GpsPack']['client_id']),'fields'=>'GpsSetting.id'));
                if(!empty($settings_data)){
                    $settings_id = $settings_data['GpsSetting']['id'];
                }else{
                    $settings_id = '';
                }
                $gps_settings_data['GpsSetting']['id'] = $settings_id;
                $gps_settings_data['GpsSetting']['client_id'] = $pack_data['GpsPack']['client_id'];
                
                
                unset($ndata[0]['cells'][1]); unset($ndata[0]['cells'][2]); unset($ndata[0]['cells'][3]); unset($ndata[0]['cells'][4]); unset($ndata[0]['cells'][5]); unset($ndata[0]['cells'][6]); unset($ndata[0]['cells'][7]);
                foreach($ndata[0]['cells'] as $sheetdata){
                    if($country != '' || $country != 'TOTAL'){
                       echo '<pre>'; echo $country = $sheetdata[1];
                        echo '<br/>';
                        
                        $gps_settings_data['GpsSetting']['countries'] = implode(',',$this->data['GpsPack']['Country']);
                        $this->GpsSetting->save($gps_settings_data);
                    }
                }
                exit;
            }
            
        }
        
    }
    
    
    function export_pdf($GpsPackId = null){
        Configure::write('debug',2);
        //$this->layout = 'pdf_doc';
        if (!$GpsPackId) {
            $this->Session->setFlash(__('Invalid GPS Pack ID', true));
            $this->redirect(array('action' => 'index'));
        }
        $GpsPack = $this->GpsPack->read(null, $GpsPackId);
        $this->set('GpsPack', $GpsPack);
        $gps_pack_id = $GpsPack['GpsPack']['id'];
        
        $month = $GpsPack['GpsPack']['month'];
        $month = sprintf("%02d", $month);
        $year = $GpsPack['GpsPack']['year'];
        $client_id = $GpsPack['GpsPack']['client_id'];
        
        $this->Client = ClassRegistry::init('Client');
        $hotel_data = $this->Client->find('first',
            array('conditions'=>
                array('Client.id'=>$client_id,'Client.status'=>1)
            ,'fields'=>'logo,hotelname','recursive'=>'0'));
        
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms']+ $gps_settings['GpsSetting']['other_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        
        $marketsegments = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        $this->set('marketsegments',$marketsegments);
    }
    
    
     function print_pack($GpsPackId = null) {
        if (!$GpsPackId) {
            $this->Session->setFlash(__('Invalid GPS Pack ID', true));
            $this->redirect(array('action' => 'index'));
        }
        $GpsPack = $this->GpsPack->read(null, $GpsPackId);
        $this->set('GpsPack', $GpsPack);
        $gps_pack_id = $GpsPack['GpsPack']['id'];
        
        $month = $GpsPack['GpsPack']['month'];
        $month = sprintf("%02d", $month);
        $year = $GpsPack['GpsPack']['year'];
        $client_id = $GpsPack['GpsPack']['client_id'];
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms']+ $gps_settings['GpsSetting']['other_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        
        $market_performance = $gps_settings['GpsSetting']['summary_mp_label'];
        $this->set('market_performance',$market_performance);
        
        $marketsegments = array();
        
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        if(!empty($GpsPack['GpsPack']['market_segments'])){
            $marketsegment_ids = explode(',',$GpsPack['GpsPack']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }else if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        $this->set('marketsegments',$marketsegments);
    }
    
    
    function get_webform_monthly_score_test($client_id='69',$fsct_col_id='65',$month='1',$year='2015'){
        Configure::write('debug',2);
        $this->layout = '';
        $this->autoRender = false;
        
        echo 'YEAR:'.$year.'<br/>';
        
        //month should be financial month start of GPS Pack
        
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        echo $financial_month_start = (!empty($gps_settings['GpsSetting']['financial_month_start'])) ? $gps_settings['GpsSetting']['financial_month_start'] : '1';
        
        $this->Sheet = ClassRegistry::init('Sheet');
         //Get Rooms Department ID
        $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);

        $this->User = ClassRegistry::init('User');
        $users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));
        
        $this->Datum = ClassRegistry::init('Datum');
        echo '<pre>';
        $month_arr = array();
        for($month_loop=$financial_month_start;$month_loop <= ($financial_month_start + 11); $month_loop++){
            $month_arr[] = $month_loop > '12' ? $month_loop - '12' : $month_loop;
        }
        print_r($month_arr);
        
        
        echo 'GPS PAck Month:'.$month.'::'.$year.'<br/>';
        
        $loop = '0'; $sum_col_vals = array();
       // for($month_loop=1;$month_loop <= 12; $month_loop++){
         for($month_loop=$financial_month_start;$month_loop <= ($financial_month_start + 11); $month_loop++){
            
             echo 'Month Loop:'.$month_loop;
             echo 'Month'.$check_month = $month_loop > '12' ? $month_loop - '12' : $month_loop;
             
             if($month_loop > '12' && ($financial_month_start > $month_loop)){
                $check_year = $year+'1';
             }else{
                 $check_year = $year;
                  if(array_search($check_month, $month_arr) < array_search($month, $month_arr)){
                    $check_year = $year-'1';
                 }
             }
             
             echo 'Year'.$check_year;
             echo '<br>';
            // exit;
             $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$check_year,'Sheet.month'=>$check_month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
           
            if(!empty ($all_sheets)){
                
                $days_in_presnt_month = cal_days_in_month (CAL_GREGORIAN,$check_month, $check_year); //calculate the number of days in present month
                
                $sheetId = $all_sheets[0]['Sheet']['id'];
                
                $this->Formula = ClassRegistry::init('Formula');
                $formula_details = $this->Formula->find('first',array('conditions'=>array('Formula.sheet_id'=>$sheetId, 'Formula.row_id'=>'Total','Formula.column_id'=>$fsct_col_id),'fields'=>array('Formula.formula'), 'order'=>array('Formula.column_order')));
                if(!empty($formula_details)){
                            $operatorArray = array("+","-","*","/","(",")");
                            $operands = explode(" ", $formula_details['Formula']['formula']);
                            $newFormula = array();
                            foreach($operands as $operand){
                                    if(substr($operand, 0,1) == "C"){
                                            $col_sum = $this->Datum->find('all', array(
                                            'conditions' => array(
                                            'sheet_id' => $sheetId,'column_id' =>substr($operand,1),'row_id'=>0,'date >='=>'1','date <='=>$days_in_presnt_month),
                                            'fields' => array('sum(Datum.value) as val_sum'
                                            )));
                                            $totalForColumn = $col_sum[0][0]['val_sum'];
                                            
                                            array_push($newFormula, $totalForColumn);
                                            
                                    }elseif(in_array($operand, $operatorArray)){
                                            array_push($newFormula, $operand);
                                    }elseif(is_numeric($operand)){
                                            array_push($newFormula, $operand);
                                    }
                            }
                            
                            $formulaForTotal = implode(" ",$newFormula);
                            $sum_val = $this->Sheet->calculate_string($formulaForTotal);
                            $sum_col_vals[$month_loop.":".$check_year.':1:'.$sheetId] = round($sum_val, 2).'SheetId - '.$sheetId;
                }else{
                        $cols_data = $this->Datum->find('all', array(
                        'conditions' => array(
                        'sheet_id' => $sheetId,'column_id' =>$fsct_col_id,'row_id'=>0,'date >='=>'1','date <='=>$days_in_presnt_month),
                        'fields' => array('sum(Datum.value) as val_sum'
                        )));

                        $sum_col_vals[$month_loop.":".$check_year.':2:'.$sheetId] = $cols_data[0][0]['val_sum'];
                }
            }else{
                $sum_col_vals[$month_loop] = '';
            }
            $loop++;
        }
        
        echo '<pre>'; print_r($sum_col_vals); exit;
        
        return $sum_col_vals;

    }
    
     function admin_edit_test($step=null,$GpsPackId = null) {
        
        //Configure::write('debug',2);
        
        $GpsPack = $this->GpsPack->read(null, $GpsPackId);
        $client_id = $GpsPack['GpsPack']['client_id'];
        $this->set('client_id',$client_id);
        $this->set('GpsPack', $GpsPack);
        $gps_pack_id = $GpsPack['GpsPack']['id'];
        
        
        $month = $GpsPack['GpsPack']['month'];
        $month = sprintf("%02d", $month);
        $year = $GpsPack['GpsPack']['year'];
        $client_id = $GpsPack['GpsPack']['client_id'];

        $this->set('step',$step);
        
        $back_step = $step - '1';
        if($back_step >= '10' && $back_step < '14'){
            $back_step = '9';
        }elseif($back_step == '19'){
            $back_step = '18';
        }
        $next_step = $step + '1';
        $this->set('back_step',$back_step);
        $this->set('next_step',$next_step);
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms']+ $gps_settings['GpsSetting']['other_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        
        $marketsegments = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        $this->set('marketsegments',$marketsegments);
        $market_performance = $gps_settings['GpsSetting']['summary_mp_label'];
        $this->set('market_performance',$market_performance);
        
        if (!empty($this->data)){
            //echo '<pre>'; print_r($this->data); exit;
            
            $gps_pack_id = $this->data['GpsPack']['pack_id'];
            
            if(!empty($this->data['GpsPack']['value'])){
                
                $this->GpsData = ClassRegistry::init('GpsData');
                $this->GpsData->deleteAll(array('GpsData.step' => $this->data['GpsPack']['step'], 'GpsData.gps_pack_id' => $gps_pack_id));
                
                $question = '1'; $prev_val = '';
                
                foreach($this->data['GpsPack']['value'] as $val_key=>$values){
                    if(!empty($this->data['GpsPack']['text'][$val_key])){
                    if($this->data['GpsPack']['text'][$val_key] != $prev_val){
                        $question = '1';
                    }
                    }
                    $prev_val = @$this->data['GpsPack']['text'][$val_key];
                    $gps_data['GpsData']['id'] = '';
                    $gps_data['GpsData']['gps_pack_id'] = $gps_pack_id;
                    $gps_data['GpsData']['value'] = $values;
                    $gps_data['GpsData']['text'] = @$this->data['GpsPack']['text'][$val_key];
                    $gps_data['GpsData']['sub_text'] =  @$this->data['GpsPack']['sub_text'][$val_key];
                    $gps_data['GpsData']['question'] = $question;
                    $gps_data['GpsData']['step'] = $this->data['GpsPack']['step'];
                    $question++;
                    
                    $this->GpsData = ClassRegistry::init('GpsData');
                    $this->GpsData->save($gps_data);
                }

                    if($_POST['submit'] == 'Save & Close'){
                        $this->Session->setFlash(__('The GPS Pack data has been saved successfully', true));
                        $this->redirect(array('action' => 'edit_steps',$gps_pack_id));
                    }
                    
                    $next_step = $this->data['GpsPack']['step']+'1';                    
                    if($next_step == '23' || $next_step > '22'){
                        $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                    }else{
                        $this->redirect(array('action' => 'edit_test',$next_step,$gps_pack_id));
                    }
            } else {
                   $next_step = $this->data['GpsPack']['step']+'1';
                   $this->redirect(array('action' => 'edit_test',$next_step,$gps_pack_id));
            }
        }
    }
    
}
//end class