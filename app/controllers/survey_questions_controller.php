<?php
class SurveyQuestionsController extends AppController {

	var $name = 'SurveyQuestions';
        var $components = array('RequestHandler', 'Sendemail','Session','Email','Cookie');

	function beforeFilter() {
		parent::beforeFilter();
                $this->Auth->allow('copy_questions');
        }
        
	function admin_index($client_id='83') {
		$this->SurveyQuestion->recursive = 0;
                if ($this->data['SurveyQuestion']['value']) {
                        $search = trim($this->data['SurveyQuestion']['value']);
                        $this->set('search',$search);
                        $condition = array('SurveyQuestion.title LIKE' => "%$search%",'SurveyQuestion.client_id' => $client_id);
                } else {
                    $condition = array('SurveyQuestion.client_id' => $client_id);
                }
                $this->SurveyQuestion->recursive = 0;
		$this->paginate['conditions'] = $condition;
		$questions = $this->paginate();
                
                $this->set('client_id', $client_id);
		$this->set('questions', $questions);
	}
        
        function admin_add($client_id=null,$id=null){
            if (!empty($this->data)) {
                    if ($this->SurveyQuestion->save($this->data)) {
                            $this->Session->setFlash(__('Question has been saved', true));
                            $this->redirect(array('action' => 'index',$this->data['SurveyQuestion']['client_id']));
                    } else {
                            $this->Session->setFlash(__('Guest could not be saved. Please, try again.', true));
                    }
            }else{
                $this->set('client_id',$client_id);
                if(!empty($id)){
                    $this->data = $this->SurveyQuestion->read(null, $id);
                }else{
                    $this->data = array();
                }
            }
        }
        
        function admin_delete($id = null) {
                $refURL = $this->referer();
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Question', true));
			$this->redirect($refURL);
		}
		if ($this->SurveyQuestion->delete($id)) {
			$this->Session->setFlash(__('Question deleted', true));
			$this->redirect($refURL);
		}
		$this->Session->setFlash(__('Question was not deleted', true));
		$this->redirect($refURL);
	}
        
        
        function client_index($client_id='83') {
		$this->SurveyQuestion->recursive = 0;
                if ($this->data['SurveyQuestion']['value']) {
                        $search = trim($this->data['SurveyQuestion']['value']);
                        $this->set('search',$search);
                        $condition = array('SurveyQuestion.title LIKE' => "%$search%",'SurveyQuestion.client_id' => $client_id);
                } else {
                    $condition = array('SurveyQuestion.client_id' => $client_id);
                }
                $this->SurveyQuestion->recursive = 0;
		$this->paginate['conditions'] = $condition;
		$questions = $this->paginate();
                
                $this->set('client_id', $client_id);
		$this->set('questions', $questions);
	}
        
        function client_add($client_id=null,$id=null){
            if (!empty($this->data)) {
                    if ($this->SurveyQuestion->save($this->data)) {
                            $this->Session->setFlash(__('Question has been saved', true));
                            $this->redirect(array('action' => 'index',$this->data['SurveyQuestion']['client_id']));
                    } else {
                            $this->Session->setFlash(__('Guest could not be saved. Please, try again.', true));
                    }
            }else{
                $this->set('client_id',$client_id);
                if(!empty($id)){
                    $this->data = $this->SurveyQuestion->read(null, $id);
                }else{
                    $this->data = array();
                }
            }
        }
        
        function client_delete($id = null) {
		 $refURL = $this->referer();
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Question', true));
			$this->redirect($refURL);
		}
		if ($this->SurveyQuestion->delete($id)) {
			$this->Session->setFlash(__('Question deleted', true));
			$this->redirect($refURL);
		}
		$this->Session->setFlash(__('Question was not deleted', true));
		$this->redirect($refURL);
	}
        
        function copy_questions(){
            $parent_id = '68';
            $this->Client = ClassRegistry::init('Client');
            $child_data = $this->Client->find('list', array('conditions' => array('Client.parent_id' => $parent_id, 'Client.status' => 1),'fields' => array('Client.id', 'Client.id'), 'recursive' => '0'));
           echo '<pre>'; print_r($child_data);
            if(!empty($child_data)){
                 $questions = $this->SurveyQuestion->find('all',array('conditions'=>array('SurveyQuestion.client_id'=>$parent_id)));
                 if(!empty ($questions)){
                     foreach($child_data as $clientId ){
                         foreach($questions as $que){
                             $newData = array();
                             $newData['SurveyQuestion'] = $que['SurveyQuestion'];
                             $newData['SurveyQuestion']['id'] = '';
                             $newData['SurveyQuestion']['client_id'] = $clientId;
                             print_r($newData);
                             $this->SurveyQuestion->save($newData);
                        }
                     }
                     //exit;
                 }
            }
            exit;
        }
        
}
?>