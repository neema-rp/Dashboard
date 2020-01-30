<?php
class ColumnsController extends AppController {

	var $name = 'Columns';
	var $helpers = array('Html', 'Javascript', 'Session');

        
        function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_range','check_column','check_column_name'));
    }

	/**
	 * Action to list all the available columns
	 * 
	 * @access public
	 * @return void
	 */
	function admin_index() {
		if ($this->data['Column']['value']) {
			$search = trim($this->data['Column']['value']);
			$this->set('search',$search);
			$this->paginate['conditions'] = array('Column.name LIKE' => "%$search%", 'Column.status !=' => 2);
		} else {
			$this->paginate['conditions'] = array('Column.status !=' => 2);
		}
		$this->Column->recursive = 0;
		
		$this->set('columns', $this->paginate());
	}//end admin_index()


	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid admin', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('admin', $this->Admin->read(null, $id));
	}


	/**
	 * Action for admin to add a new column name
	 * 
	 * @access public
	 * @return void
	 */
	function admin_add() {
		if (!empty($this->data)) {
			$this->Column->create();
			if ($this->Column->save($this->data)) {
				$this->Session->setFlash(__('The Column name has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Column name could not be saved. Please, try again.', true));
			}
		}
	}//end admin_add()


	/**
	 * Action for admin to edit a column
	 * 
	 * @param int $id ID of the column to be edited
	 * @access public
	 * @return void
	 */
	function admin_edit($id = null) {
		if ($id == null || !$this->Column->hasAny(array('Column.id'))) {
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			if ($this->Column->save($this->data)) {
				$this->Session->setFlash(__('The column has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The column could not be saved. Please, try again.', true));
			}
		}
		else {
			$this->data = $this->Column->read(null, $id);
		}
	}//end admin_edit()


	/**
	 * Action for admin to delete a column
	 * 
	 * @param integer $id Id of the column to be deleted
	 * @access public
	 * @return void
	 */
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid column id', true));
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
		if ($this->Column->softDelete($id)) {

			$this->Session->setFlash(__('Column deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Column was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}//end admin_delete()

        
        function admin_range($client_id=null) {
        
            $columnIds = array('62','63','64','65','66','67','68','69','70','71');
            $columns = $this->Column->find('all', array('conditions' => array('Column.status !=' => 2,'Column.id'=>$columnIds),'fields'=>array('Column.name','Column.id'),'recursive'=>'0'));
            $this->set('columns',$columns);
            
            $this->User = ClassRegistry::init('User');
            $userdata = $this->User->find('first', array('fields' => array('Client.hotelname'), 'conditions' => array('User.client_id' => $client_id, 'User.status !=' => 2),'recursive'=>'0'));
            
            $this->set('client_id',$client_id);
            $this->set('hotelname',$userdata['Client']['hotelname']);
		
            if (!empty($this->data)) {
                    foreach($this->data['ColumnRange'] as $column_range){
                        if(!empty($column_range['low_value']) && !empty($column_range['moderate_value']) && !empty($column_range['busy_value'])){
                        $data['ColumnRange'] = $column_range;
                        $this->ColumnRange = ClassRegistry::init('ColumnRange');
                        $this->ColumnRange->save($data);
                        }
                    }
                    	
                        $this->Session->setFlash(__('The Column Range has been saved', true));
                        $this->redirect(array('controller'=>'clients','action' => 'index'));
			
		}
	}//end admin_range()
        
        
        function client_range() {
            
            $client_id = $this->Auth->user('id');
            
            $columnIds = array('62','63','64','65','66','67','68','69','70','71');
            $columns = $this->Column->find('all', array('conditions' => array('Column.status !=' => 2,'Column.id'=>$columnIds),'fields'=>array('Column.name','Column.id'),'recursive'=>'0'));
            $this->set('columns',$columns);
            
            $this->User = ClassRegistry::init('User');
            $userdata = $this->User->find('first', array('fields' => array('Client.hotelname'), 'conditions' => array('User.client_id' => $client_id, 'User.status !=' => 2),'recursive'=>'0'));
            
            $this->set('client_id',$client_id);
            $this->set('hotelname',$userdata['Client']['hotelname']);
		
            if (!empty($this->data)) {
                    foreach($this->data['ColumnRange'] as $column_range){
                        if(!empty($column_range['low_value']) && !empty($column_range['moderate_value']) && !empty($column_range['busy_value'])){
                        $data['ColumnRange'] = $column_range;
                        $this->ColumnRange = ClassRegistry::init('ColumnRange');
                        $this->ColumnRange->save($data);
                        }
                    }	
                        $this->Session->setFlash(__('The Column Range has been saved', true));
                        $this->redirect(array('prefix' => 'client', 'admin' => false,'controller'=>'clients','action' => 'profile'));
              }
	}//end client_range()
        
        
        public function check_column($column_id=null,$client_id=null){
            $this->layout = false;
            $this->autoRender = false;
        
            $this->ColumnRange = ClassRegistry::init('ColumnRange');
            $column_check = $this->ColumnRange->find('first', array('conditions' => array('ColumnRange.client_id' => $client_id,'ColumnRange.column_id' => $column_id),'recursive'=>'0'));
          
            return $column_check;
            
            exit;
        }
        
         public function check_column_name($column_name=null,$client_id=null){
            $this->layout = false;
            $this->autoRender = false;
        
            $this->ColumnRange = ClassRegistry::init('ColumnRange');
            $column_check = $this->ColumnRange->find('first', array('conditions' => array('ColumnRange.client_id' => $client_id,'ColumnRange.column_name' => $column_name),'recursive'=>'0'));
          
            return $column_check;
           
            exit;
        }
        
        
        function staff_range() {
            
            $userId = $this->Auth->user('id');
            $client_id = $this->Auth->user('client_id');
            
            $columnIds = array('62','63','64','65','66','67','68','69','70','71');
            $columns = $this->Column->find('all', array('conditions' => array('Column.status !=' => 2,'Column.id'=>$columnIds),'fields'=>array('Column.name','Column.id'),'recursive'=>'0'));
            $this->set('columns',$columns);
            
            $this->User = ClassRegistry::init('User');
            $userdata = $this->User->find('first', array('fields' => array('Client.hotelname'), 'conditions' => array('User.client_id' => $client_id, 'User.status !=' => 2),'recursive'=>'0'));
            
            $this->set('client_id',$client_id);
            $this->set('hotelname',$userdata['Client']['hotelname']);
		
            if (!empty($this->data)) {
                    foreach($this->data['ColumnRange'] as $column_range){
                        if(!empty($column_range['low_value']) && !empty($column_range['moderate_value']) && !empty($column_range['busy_value'])){
                        $data['ColumnRange'] = $column_range;
                        $this->ColumnRange = ClassRegistry::init('ColumnRange');
                        $this->ColumnRange->save($data);
                        }
                    }	
                        $this->Session->setFlash(__('The Column Range has been saved', true));
                        $this->redirect(array('prefix' => 'client', 'admin' => false,'controller'=>'clients','action' => 'profile'));
              }
	}//end client_range()
        
        
}//end class

