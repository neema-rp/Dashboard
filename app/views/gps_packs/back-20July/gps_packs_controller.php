<?php
class GpsPacksController extends AppController {

    var $name = 'GpsPacks';
    var $helpers = array('Html', 'Javascript', 'Session');

    function beforeFilter() {
       // Configure::write('debug',2);
        //echo 'in before filter'; exit;
        parent::beforeFilter(); 
        
        $this->Auth->allow('get_activity_data','get_channels_vals','get_child_list','get_gps_data','get_total_segment_vals','get_roomtype_values','get_webform_monthly_score','get_last_gps_data','get_country_list');
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
            $this->Session->setFlash(__('Invalid GpsPackS ID', true));
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
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        
        $marketsegments = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        $this->set('marketsegments',$marketsegments);

    }
    
    
    function admin_edit($step=null,$GpsPackId = null) {
        
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
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        
        $marketsegments = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        $this->set('marketsegments',$marketsegments);
        
        if (!empty($this->data)){     
            
            $gps_pack_id = $this->data['GpsPack']['pack_id'];
            if(!empty($this->data['GpsPack']['value'])){
                foreach($this->data['GpsPack']['value'] as $val_key=>$values){
                    $gps_data['GpsData']['id'] = $this->data['GpsPack']['id'][$val_key];
                    $gps_data['GpsData']['value'] = $values;
                    $this->GpsData = ClassRegistry::init('GpsData');
                    $this->GpsData->save($gps_data);
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
        $gpsDatas = $this->GpsData->find('all', array('conditions' => array('GpsData.gps_pack_id'=>$gps_pack_id,'GpsData.step'=>$step)));
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
                $condition = array('GpsPack.client_id'=>$client_id,'GpsPack.id !='=>$gps_pack_id,'GpsPack.month'=>$chk_month,'GpsPack.year'=>$year);
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
        $this->layout = '';
        $this->autoRender = false;
        
        $this->Sheet = ClassRegistry::init('Sheet');
        
         //Get Rooms Department ID
        $dept_ids = $this->requestAction('/Clients/get_room_department/'.$client_id);

        $this->User = ClassRegistry::init('User');
        $users = $this->User->find('list',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.id'),'recursive'=>'-1'));
        
        $this->Datum = ClassRegistry::init('Datum');
        
        $loop = '0'; $sum_col_vals = array();
        for($month_loop=1;$month_loop <= 12; $month_loop++){
            
             $check_month = $month+$loop > '12' ? '1' : $month+$loop;
             $check_year = $month+$loop > '12' ? $year+'1' : $year;
            
            $all_sheets = $this->Sheet->find('all',array('conditions'=>array('Sheet.status'=>1,'Sheet.department_id'=>$dept_ids,'Sheet.year'=>$check_year,'Sheet.month'=>$check_month),'fields'=>array('Sheet.id'),'order'=>'Sheet.modified DESC','recursive'=>'0'));
           
            if(!empty ($all_sheets)){
                $sheetId = $all_sheets[0]['Sheet']['id'];
                $cols_data = $this->Datum->find('all', array(
                'conditions' => array(
                'sheet_id' => $sheetId,'column_id' =>$fsct_col_id,'row_id'=>0),
                'fields' => array('sum(Datum.value) as val_sum'
                )));
                $sum_col_vals[$month_loop] = $cols_data[0][0]['val_sum'];

            }else{
                $sum_col_vals[$month_loop] = '';
            }
            $loop++;
        }
        
        return $sum_col_vals;

    }


    function get_roomtype_values($month=null,$roomType=null,$client_id=null,$gps_pack_id=null){
        $this->layout = '';
        $this->autoRender = false;
  
        $this->GpsData = ClassRegistry::init('GpsData');
        $roomData = array();
        if($month == 'year'){
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
                'conditions' =>array('GpsPack.client_id'=>$client_id,'GpsData.text'=>$roomType,'GpsData.sub_text'=>'RN','GpsData.question'=>'1'),
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
                'conditions' =>array('GpsPack.client_id'=>$client_id,'GpsData.text'=>$roomType,'GpsData.sub_text'=>'ADR','GpsData.question'=>'4'),
                'fields' => array('GpsPack.month', 'GpsData.value')
            ));
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
                'conditions' =>array('GpsPack.client_id'=>$client_id,'GpsPack.month'=>$month,'GpsData.text'=>$roomType,'GpsData.sub_text'=>'RN','GpsData.question'=>'1'),
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
                'conditions' =>array('GpsPack.client_id'=>$client_id,'GpsPack.month'=>$month,'GpsData.text'=>$roomType,'GpsData.sub_text'=>'ADR','GpsData.question'=>'4'),
                'fields' => array('GpsPack.month', 'GpsData.value')
            ));
        }
        
        return $roomData;
    }
    
    

    function admin_new($client_id='106') {
        $this->set('client_id',$client_id);
        if (!empty($this->data)) {
            if ($this->GpsPack->save($this->data)) {
            $gps_pack_id = $this->GpsPack->getLastInsertId();
              $this->redirect(array('action' => 'add','1',$gps_pack_id));
            } else {
                $this->Session->setFlash(__('The Gps Pack could not be saved. Please, try again.', true));
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
                $this->Session->setFlash(__('The GpsPackS could not be saved. Please, try again.', true));
            }
        }else{
            
            $this->data = $this->GpsPack->find('first', array('conditions' => array('GpsPack.id' => $gps_pack_id, 'GpsPack.status' => 1)));
            $client_id = $this->data['GpsPack']['client_id'];
            $this->set('client_id',$client_id);
            $this->set('GpsPack', $this->data);
            
            $this->GpsSetting = ClassRegistry::init('GpsSetting');
            $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
            $this->set('gps_settings',$gps_settings);
            
            $marketsegments = array();
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            if(!empty($gps_settings['GpsSetting']['market_segments'])){
                $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
            }
            $this->set('marketsegments',$marketsegments);
            
        }
    }

    function admin_delete($GpsPackId = null) {
        if (!$GpsPackId) {
            $this->Session->setFlash(__('Invalid GpsPackS id', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->GpsPack->softDelete($GpsPackId)) {
            $this->Session->setFlash(__('GpsPackS deleted successfully', true));
            $this->redirect(array('action' => 'index',$GpsPackId));
        }
        $this->Session->setFlash(__('GpsPackS was not deleted, please try again.', true));
        $this->redirect(array('action' => 'index',$GpsPackId));
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
            $this->Session->setFlash(__('Invalid GpsPackS ID', true));
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
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms'];
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
        if (!empty($this->data)) {
            if ($this->GpsPack->save($this->data)) {
            $gps_pack_id = $this->GpsPack->getLastInsertId();
              $this->redirect(array('action' => 'add','1',$gps_pack_id));
            } else {
                $this->Session->setFlash(__('The Gps Pack could not be saved. Please, try again.', true));
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
                $this->Session->setFlash(__('The GpsPackS could not be saved. Please, try again.', true));
            }
        }else{
            
            $this->data = $this->GpsPack->find('first', array('conditions' => array('GpsPack.id' => $gps_pack_id, 'GpsPack.status' => 1)));
            $client_id = $this->data['GpsPack']['client_id'];
            $this->set('client_id',$client_id);
            $this->set('GpsPack', $this->data);
            
            $this->GpsSetting = ClassRegistry::init('GpsSetting');
            $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
            $this->set('gps_settings',$gps_settings);
            
            $marketsegments = array();
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            if(!empty($gps_settings['GpsSetting']['market_segments'])){
                $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
            }
            $this->set('marketsegments',$marketsegments);
            
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
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        $marketsegments = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        $this->set('marketsegments',$marketsegments);
        
        if (!empty($this->data)){     
            
            $gps_pack_id = $this->data['GpsPack']['pack_id'];
            if(!empty($this->data['GpsPack']['value'])){
                foreach($this->data['GpsPack']['value'] as $val_key=>$values){
                    $gps_data['GpsData']['id'] = $this->data['GpsPack']['id'][$val_key];
                    $gps_data['GpsData']['value'] = $values;
                    $this->GpsData = ClassRegistry::init('GpsData');
                    $this->GpsData->save($gps_data);
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
        
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));
        $this->set('marketsegments',$marketsegments);
        
        $country_array = $this->requestAction('/GpsPacks/get_country_list/');
        
        $this->set('client_id',$client_id);
        $this->set('country_array',$country_array);

        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        if(!empty($this->data)){
            
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
                $gps_settings_data['GpsSetting']['market_segments'] = implode(',',$this->data['GpsPack']['MarketSegment']);
                $gps_settings_data['GpsSetting']['countries'] = implode(',',$this->data['GpsPack']['Country']);
                
                //Room Types- Standard|Executive|Deluxe|Suites
                $gps_settings_data['GpsSetting']['roomtypes'] = implode('|',$this->data['GpsPack']['roomtypes']);
                
                if ($this->GpsSetting->save($gps_settings_data)) {
                    $this->Session->setFlash(__('Gps Settings saved successfully.', true));
                    $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                } else {
                    $this->Session->setFlash(__('The Gps Settings could not be saved. Please, try again.', true));
                }
        }
    }
    
    
    function client_settings($client_id=null){
        
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));
        $this->set('marketsegments',$marketsegments);
        
        $country_array = $this->requestAction('/GpsPacks/get_country_list/');
        
        $this->set('client_id',$client_id);
        $this->set('country_array',$country_array);

        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        if(!empty($this->data)){
            
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
                $gps_settings_data['GpsSetting']['market_segments'] = implode(',',$this->data['GpsPack']['MarketSegment']);
                $gps_settings_data['GpsSetting']['countries'] = implode(',',$this->data['GpsPack']['Country']);
                
                //Room Types- Standard|Executive|Deluxe|Suites
                $gps_settings_data['GpsSetting']['roomtypes'] = implode('|',$this->data['GpsPack']['roomtypes']);
                
                if ($this->GpsSetting->save($gps_settings_data)) {
                    $this->Session->setFlash(__('Gps Settings saved successfully.', true));
                    $this->redirect(array('action' => 'index',$this->data['GpsPack']['client_id']));
                } else {
                    $this->Session->setFlash(__('The Gps Settings could not be saved. Please, try again.', true));
                }
        }
    }
    
    function admin_edit_steps($gps_id=null){
        $this->set('gps_id',$gps_id);
        $GpsPack = $this->GpsPack->read(null, $gps_id);
        $this->set('GpsPack', $GpsPack);
    }
    
    function client_edit_steps($gps_id=null){
        $this->set('gps_id',$gps_id);
        $GpsPack = $this->GpsPack->read(null, $gps_id);
        $this->set('GpsPack', $GpsPack);
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
        $country_array['Cameroon'] = 'Cameroon';
        $country_array['Cape Verde'] = 'Cape Verde';
        $country_array['Congo'] = 'Congo';
        $country_array['DRC'] = 'DRC';
        $country_array['Egypt'] = 'Egypt';
        $country_array['Ethiopia'] = 'Ethiopia';
        $country_array['Gabon'] = 'Gabon';
        $country_array['Gambia'] = 'Gambia';
        $country_array['Ghana'] = 'Ghana';
        $country_array['Guinea'] = 'Guinea';
        $country_array['Iran'] = 'Iran';
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
        $country_array['Other'] = 'Other';
        
        return $country_array;
    }
 
    function staff_index($client_id=null) {
        $conditions = array('GpsPack.status !=' => 2,'GpsPack.client_id'=>$client_id);
        $userGpsPacks = $this->GpsPack->find('all', array('conditions' => $conditions));
        $this->set('userGpsPacks', $userGpsPacks);
    }

    function staff_edit_steps($gps_id=null){
        $this->set('gps_id',$gps_id);
        $GpsPack = $this->GpsPack->read(null, $gps_id);
        $this->set('GpsPack', $GpsPack);

        $user_id = $this->Auth->user('id');
        $this->GpsUser = ClassRegistry::init('GpsUser');
        $gps_user_data = $this->GpsUser->find('first',array('conditions'=>array('GpsUser.user_id'=>$user_id)));
        $step_module = explode(',',$gps_user_data['GpsUser']['step_module']);    
        $this->set('step_module', $step_module);
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
        
        $this->GpsSetting = ClassRegistry::init('GpsSetting');
        $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
        $this->set('gps_settings',$gps_settings);
        
        $number_of_rooms = '0';
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms'];
        $this->set('number_of_rooms', $number_of_rooms);
        
        $marketsegments = array();
        if(!empty($gps_settings['GpsSetting']['market_segments'])){
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
            $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
        }
        $this->set('marketsegments',$marketsegments);
        
        if (!empty($this->data)){     
            
            $gps_pack_id = $this->data['GpsPack']['pack_id'];
            if(!empty($this->data['GpsPack']['value'])){
                foreach($this->data['GpsPack']['value'] as $val_key=>$values){
                    $gps_data['GpsData']['id'] = $this->data['GpsPack']['id'][$val_key];
                    $gps_data['GpsData']['value'] = $values;
                    $this->GpsData = ClassRegistry::init('GpsData');
                    $this->GpsData->save($gps_data);
                }

                $this->redirect(array('action' => 'edit_steps',$gps_pack_id));
            } else {
                $this->redirect(array('action' => 'edit_steps',$gps_pack_id));
            }
        }
    }
    
    function staff_view($GpsPackId = null) {
        if (!$GpsPackId) {
            $this->Session->setFlash(__('Invalid GpsPackS ID', true));
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
        $number_of_rooms = $number_of_rooms + $gps_settings['GpsSetting']['standard_rooms'] + $gps_settings['GpsSetting']['executive_rooms'] + $gps_settings['GpsSetting']['deluxe_rooms'] + $gps_settings['GpsSetting']['suites_rooms'];
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
                $this->Session->setFlash(__('The GpsPackS could not be saved. Please, try again.', true));
            }
        }else{
            
            $this->data = $this->GpsPack->find('first', array('conditions' => array('GpsPack.id' => $gps_pack_id, 'GpsPack.status' => 1)));
            $client_id = $this->data['GpsPack']['client_id'];
            $this->set('client_id',$client_id);
            $this->set('GpsPack', $this->data);
            
            $this->GpsSetting = ClassRegistry::init('GpsSetting');
            $gps_settings = $this->GpsSetting->find('first',array('conditions'=>array('GpsSetting.client_id'=>$client_id)));
            $this->set('gps_settings',$gps_settings);
            
            $marketsegments = array();
            $this->MarketSegment = ClassRegistry::init('MarketSegment');
            if(!empty($gps_settings['GpsSetting']['market_segments'])){
                $marketsegment_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
                $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2,'MarketSegment.id'=>$marketsegment_ids)));
            }
            $this->set('marketsegments',$marketsegments);
            
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
    
}
//end class