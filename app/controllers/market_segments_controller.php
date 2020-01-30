<?php
class MarketSegmentsController extends AppController {

	var $name = 'MarketSegments';
	var $helpers = array('Html', 'Javascript', 'Session');

        
        function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_range','check_MarketSegment','check_MarketSegment_name'));
    }

	/**
	 * Action to list all the available MarketSegments
	 * 
	 * @access public
	 * @return void
	 */
	function admin_index() {
		if ($this->data['MarketSegment']['value']) {
			$search = trim($this->data['MarketSegment']['value']);
			$this->set('search',$search);
			$this->paginate['conditions'] = array('MarketSegment.name LIKE' => "%$search%", 'MarketSegment.status !=' => 2);
		} else {
			$this->paginate['conditions'] = array('MarketSegment.status !=' => 2);
		}
		$this->MarketSegment->recursive = 0;
		
		$this->set('MarketSegments', $this->paginate());
	}//end admin_index()


	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid admin', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('admin', $this->Admin->read(null, $id));
	}


	/**
	 * Action for admin to add a new MarketSegment name
	 * 
	 * @access public
	 * @return void
	 */
	function admin_add() {
		if (!empty($this->data)) {
			$this->MarketSegment->create();
			if ($this->MarketSegment->save($this->data)) {
				$this->Session->setFlash(__('The MarketSegment name has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The MarketSegment name could not be saved. Please, try again.', true));
			}
		}
	}//end admin_add()


	/**
	 * Action for admin to edit a MarketSegment
	 * 
	 * @param int $id ID of the MarketSegment to be edited
	 * @access public
	 * @return void
	 */
	function admin_edit($id = null) {
		if ($id == null || !$this->MarketSegment->hasAny(array('MarketSegment.id'))) {
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			if ($this->MarketSegment->save($this->data)) {
				$this->Session->setFlash(__('The MarketSegment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The MarketSegment could not be saved. Please, try again.', true));
			}
		}
		else {
			$this->data = $this->MarketSegment->read(null, $id);
		}
	}//end admin_edit()


	/**
	 * Action for admin to delete a MarketSegment
	 * 
	 * @param integer $id Id of the MarketSegment to be deleted
	 * @access public
	 * @return void
	 */
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid MarketSegment id', true));
			$this->redirect(array('action'=>'index'));
		}
$formula_obj = ClassRegistry::init('Formula');
$formula_data = $formula_obj->find('all');
$formula_affected = array();
foreach($formula_data as $formula){

    $findme = "C".$id;
    $pos = strpos($formula['Formula']['formula'], $findme);
    if ($pos === false) {
	
    } else {
	$formula_affected []=$formula;
    }
}
foreach($formula_affected as $frmla){
   $ret = $formula_obj->delete($frmla['Formula']['id']);
  
}
		if ($this->MarketSegment->softDelete($id)) {

			$this->Session->setFlash(__('MarketSegment deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('MarketSegment was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}//end admin_delete()

        
        function admin_range($client_id=null) {
            
            //Configure::write('debug',2);
            
            $MarketSegments = $this->MarketSegment->find('all', array('conditions' => array('MarketSegment.status !=' => 2),'fields'=>array('MarketSegment.name','MarketSegment.id'),'recursive'=>'0'));
            $this->set('MarketSegments',$MarketSegments);
            
             $this->User = ClassRegistry::init('User');
            $userdata = $this->User->find('first', array('fields' => array('Client.hotelname'), 'conditions' => array('User.client_id' => $client_id, 'User.status !=' => 2),'recursive'=>'0'));
            
            $this->set('client_id',$client_id);
           $this->set('hotelname',$userdata['Client']['hotelname']);
		
            if (!empty($this->data)) {
                    //echo '<pre>'; print_r($MarketSegment_range); exit;
                    
                    foreach($this->data['MarketSegmentRange'] as $MarketSegment_range){
                        
                        //echo '<pre>'; print_r($MarketSegment_range); exit;
                        
                        if(!empty($MarketSegment_range['low_value']) && !empty($MarketSegment_range['moderate_value']) && !empty($MarketSegment_range['busy_value'])){
                        $data['MarketSegmentRange'] = $MarketSegment_range;
                        $this->MarketSegmentRange = ClassRegistry::init('MarketSegmentRange');
                        $this->MarketSegmentRange->save($data);
                        }
                    }
                    	
                        $this->Session->setFlash(__('The MarketSegment Range has been saved', true));
                        $this->redirect(array('controller'=>'clients','action' => 'index'));
			
		}
	}//end admin_range()
        
        public function check_MarketSegment($MarketSegment_id=null,$client_id=null){
            $this->layout = false;
            $this->autoRender = false;
        
            $this->MarketSegmentRange = ClassRegistry::init('MarketSegmentRange');
            $MarketSegment_check = $this->MarketSegmentRange->find('first', array('conditions' => array('MarketSegmentRange.client_id' => $client_id,'MarketSegmentRange.MarketSegment_id' => $MarketSegment_id),'recursive'=>'0'));
          
            return $MarketSegment_check;
            
            exit;
        }
        
         public function check_MarketSegment_name($MarketSegment_name=null,$client_id=null){
            $this->layout = false;
            $this->autoRender = false;
        
            $this->MarketSegmentRange = ClassRegistry::init('MarketSegmentRange');
            $MarketSegment_check = $this->MarketSegmentRange->find('first', array('conditions' => array('MarketSegmentRange.client_id' => $client_id,'MarketSegmentRange.MarketSegment_name' => $MarketSegment_name),'recursive'=>'0'));
          
            return $MarketSegment_check;
           
            exit;
        }
        
    function admin_list($client_id) {
        
        if (!empty($this->data)) { 
            //echo '<pre>'; print_r($this->data); exit;
            $client_segments_arr['market_segment_ids'] = implode(',',$this->data['MarketSegment']['Segments']);
            $client_segments_arr['id'] = $this->data['MarketSegment']['client_id'];
            $this->Client = ClassRegistry::init('Client');
            
            if($this->Client->save($client_segments_arr)){
                $this->Session->setFlash(__('MarketSegment has been saved', true));
                $this->redirect(array('action' => 'list',$client_segments_arr['id']));
            } else {
                $this->Session->setFlash(__('MarketSegment could not be saved. Please, try again.', true));
            }
        }
        
        $this->Client = ClassRegistry::init('Client');
        $this->Client->recursive = '-1';
        $data = $this->Client->find('first', array('conditions' => array('Client.id'=>$client_id)));
        $client_segments = explode(',',$data['Client']['market_segment_ids']);
        
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));
        $this->set(compact('client_id','client_segments','marketsegments'));
    }

        
    function client_list() {
        
        $client_id = $this->Auth->user('id');
        
        if (!empty($this->data)) { 
            $client_segments_arr['market_segment_ids'] = implode(',',$this->data['MarketSegment']['Segments']);
            $client_segments_arr['id'] = $this->data['MarketSegment']['client_id'];
            $this->Client = ClassRegistry::init('Client');
            
            if($this->Client->save($client_segments_arr)){
                $this->Session->setFlash(__('MarketSegment has been saved', true));
                $this->redirect(array('action' => 'list',$client_segments_arr['id']));
            } else {
                $this->Session->setFlash(__('MarketSegment could not be saved. Please, try again.', true));
            }
        }
        
        $this->Client = ClassRegistry::init('Client');
        $this->Client->recursive = '-1';
        $data = $this->Client->find('first', array('conditions' => array('Client.id'=>$client_id)));
        $client_segments = explode(',',$data['Client']['market_segment_ids']);
        
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));
        $this->set(compact('client_id','client_segments','marketsegments'));
    }
    
    function staff_list() {
        
        $client_id = $this->Auth->user('client_id');
        
        if (!empty($this->data)) { 
            $client_segments_arr['market_segment_ids'] = implode(',',$this->data['MarketSegment']['Segments']);
            $client_segments_arr['id'] = $this->data['MarketSegment']['client_id'];
            $this->Client = ClassRegistry::init('Client');
            
            if($this->Client->save($client_segments_arr)){
                $this->Session->setFlash(__('MarketSegment has been saved', true));
                $this->redirect(array('action' => 'list',$client_segments_arr['id']));
            } else {
                $this->Session->setFlash(__('MarketSegment could not be saved. Please, try again.', true));
            }
        }
        
        $this->Client = ClassRegistry::init('Client');
        $this->Client->recursive = '-1';
        $data = $this->Client->find('first', array('conditions' => array('Client.id'=>$client_id)));
        $client_segments = explode(',',$data['Client']['market_segment_ids']);
        
        $this->MarketSegment = ClassRegistry::init('MarketSegment');
        $marketsegments = $this->MarketSegment->find('list', array('conditions' => array('MarketSegment.status !=' => 2)));
        $this->set(compact('client_id','client_segments','marketsegments'));
    }
        
}//end class

