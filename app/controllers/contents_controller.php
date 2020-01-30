<?php
class ContentsController extends AppController {
	var $name = 'Contents';
	var $helpers = array ('Html' ,'Form', 'Javascript', 'Session');
	var $components = array ('Sendemail','Session');
		
	function admin_index()
	{
		$this->Contents->recursive = 0;
		$this->set('contents', $this->paginate());
	}
	
	function admin_view($id = null) 
	{
		if (!$id)
		{
			$this->Session->setFlash(__('Invalid id', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('contents', $this->Content->read(null, $id));
	}
		
	function admin_edit($id = null) 
	{
		if (!$id)
		{
			$this->Session->setFlash(__('Invalid id', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Content->save($this->data)) {
				$this->Session->setFlash(__('The contents has been updated successfully', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The contents could not be saved . Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->set('contents', $this->Content->read(null, $id));
			$this->data = $this->Content->read(null, $id);
		}
		
	}

	
}//end class