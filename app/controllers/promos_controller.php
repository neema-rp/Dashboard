<?php
class PromosController extends AppController {

    var $name = 'Promos';
    var $helpers = array('Html', 'Javascript', 'Session');

    function beforeFilter() {
        //Configure::write('debug',2);
        parent::beforeFilter();
        $this->Auth->allow('getMondays','get_promo_data');
   }

    function admin_index($client_id=null,$selected_child_hotel=null) {
        
        if(!empty($selected_child_hotel)){
                $conditions = array('Promo.status !=' => 2,'Promo.client_id'=>$selected_child_hotel);
        }else{
                $conditions = array('Promo.status !=' => 2,'Promo.client_id'=>$client_id);
        }
        
        $this->Promo->recursive = '0';
        $userPromos = $this->Promo->find('all', array('conditions' => $conditions));
        $this->set('userPromos', $userPromos);
        
        $this->set('selected_child_hotel', $selected_child_hotel);
        $this->set('client_id', $client_id);
        
        $child_data = $this->requestAction('/GpsPacks/get_child_list/'.$client_id);
        $this->set('child_data', $child_data);
    }

    function admin_view($PromoId = null) {
        if (!$PromoId) {
            $this->Session->setFlash(__('Invalid PromoS ID', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Promo->recursive = '0';
        $Promo = $this->Promo->read(null, $PromoId);
        $this->set('Promo', $Promo);
    }
    
    
    function admin_edit($month=null,$PromoId = null) {
        
        $this->Promo->recursive = '0';
        $Promo = $this->Promo->read(null, $PromoId);
        $client_id = $Promo['Promo']['client_id'];
        $this->set('client_id',$client_id);
        $this->set('Promo', $Promo);
        $this->set('month',$month);
        
            if (!empty($this->data)){
                    if ($this->Promo->saveAll($this->data)) {
                      $this->Session->setFlash(__('Promotions Calendar data added successfully.', true));
                      $client_id = $this->data['Promo']['client_id'];
                      $this->redirect(array('action' => 'edit_steps',$this->data['Promo']['id']));
                    } else {
                        $this->Session->setFlash(__('Data not be saved. Please, try again.', true));
                    }
            }
    }
    
    function admin_new($client_id=null) {
        $this->set('client_id',$client_id);
        if (!empty($this->data)) {
            
            $this->data['Promo']['ota_categories'] = implode('|',$this->data['Promo']['ota_categories']);
            $this->data['Promo']['general_categories'] = implode('|',$this->data['Promo']['general_categories']);
            
            if ($this->Promo->save($this->data)) {
              $this->Session->setFlash(__('Promotions Calendar added successfully.Please go to edit and start updating Calendar.', true));
              $client_id = $this->data['Promo']['client_id'];
              $this->redirect(array('action' => 'index',$client_id));
            } else {
                $this->Session->setFlash(__('The Gps Pack could not be saved. Please, try again.', true));
            }
        }
    }
    
    function admin_settings($id=null) {
        
        if (!empty($this->data)) {

            //echo '<pre>'; print_r($this->data); exit;
            $this->data['Promo']['ota_categories'] = implode('|',$this->data['Promo']['ota_categories']);
            $this->data['Promo']['general_categories'] = implode('|',$this->data['Promo']['general_categories']);
            
            if ($this->Promo->save($this->data)) {
              $this->Session->setFlash(__('Promotions Calendar Updated successfully.', true));
              $client_id = $this->data['Promo']['client_id'];
              $this->redirect(array('action' => 'index',$client_id));
            } else {
                $this->Session->setFlash(__('The Gps Pack could not be saved. Please, try again.', true));
            }
        }else{
            $this->Promo->recursive = '0';
            $this->data = $this->Promo->read(null, $id);
        }
    }

    function admin_edit_steps($PromoId = null){
            $this->set('PromoId',$PromoId);
            $this->Promo->recursive = '0';
            $this->data = $this->Promo->read(null, $PromoId);
    }
 
    function getMondays($year, $month)
    {
        $this->layout = false;
        $this->autoRender = false;
        
        $mondays = array();
        # First weekday in specified month: 1 = monday, 7 = sunday
        $firstDay = date('N', mktime(0, 0, 0, $month, 1, $year));
        //check if 1st day is monday
        if(date('D', mktime(0, 0, 0, $month, $firstDay, $year)) == 'Mon'){
            $mondays[] = date('D, d M Y', mktime(0, 0, 0, $month, $firstDay, $year));
        }
        
        /* Add 0 days if monday ... 6 days if tuesday, 1 day if sunday
            to get the first monday in month */
        $addDays = (8 - $firstDay); //&#37; 7;
        //$mondays[] = date('r', mktime(0, 0, 0, $month, 1 + $addDays, $year));
        $mondays[] = date('D, d M Y', mktime(0, 0, 0, $month, 1 + $addDays, $year));

        $nextMonth = mktime(0, 0, 0, $month + 1, 1, $year);

        # Just add 7 days per iteration to get the date of the subsequent week
        for ($week = 1, $time = mktime(0, 0, 0, $month, 1 + $addDays + $week * 7, $year);
            $time < $nextMonth;
            ++$week, $time = mktime(0, 0, 0, $month, 1 + $addDays + $week * 7, $year))
        {
            //$mondays[] = date('r', $time);
                $mondays[] = date('D, d M Y', $time);
        }
        return $mondays;
    } 
    
    public function get_promo_data_back($id=null,$month=null,$is_offer='0'){
        $this->layout = false;
        $this->autoRender = false;

        $this->PromoData = ClassRegistry::init('PromoData');
        if($is_offer == '1'){
            $condition = array('PromoData.promo_id'=>$id,'PromoData.month'=>$month,'PromoData.category'=>'Offers');
        }else{
            $condition = array('PromoData.promo_id'=>$id,'PromoData.month'=>$month,'PromoData.category !='=>'Offers');
        }
        
        $promoData = $this->PromoData->find('all', array('conditions' => $condition));
        return $promoData;
    }
    
    public function get_promo_data($id=null,$month=null){
        $this->layout = false;
        $this->autoRender = false;

        $this->PromoData = ClassRegistry::init('PromoData');
        $condition = array('PromoData.promo_id'=>$id,'PromoData.month'=>$month);
        $promoData = $this->PromoData->find('all', array('conditions' => $condition));
        return $promoData;
    }

}
//end class
?>