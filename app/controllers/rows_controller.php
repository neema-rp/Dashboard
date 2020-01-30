<?php
class RowsController extends AppController {

	var $name = 'Rows';
	var $helpers = array('Html', 'Javascript', 'Session');


	/**
	 * Action to list all the available rows
	 *
	 * @access public
	 * @return void
	 */
	function admin_index() {
		if ($this->data['Row']['value']) {
			$search = trim($this->data['Row']['value']);
			$this->set('search',$search);
			$this->paginate['conditions'] = array('Row.name LIKE' => "%$search%", 'Row.status !=' => 2);
		} else {
			$this->paginate['conditions'] = array('Row.status !=' => 2);
		}
		$this->Row->recursive = 0;

		$this->set('rows', $this->paginate());
	}//end admin_index()


	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid admin', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('admin', $this->Admin->read(null, $id));
	}


	/**
	 * Action for admin to add a new row name
	 *
	 * @access public
	 * @return void
	 */
	function admin_add() {
		if (!empty($this->data)) {
			$this->Row->create();
			if ($this->Row->save($this->data)) {
				$this->Session->setFlash(__('The Row name has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Row name could not be saved. Please, try again.', true));
			}
		}
	}//end admin_add()


	/**
	 * Action for admin to edit a row
	 *
	 * @param int $id ID of the row to be edited
	 * @access public
	 * @return void
	 */
	function admin_edit($id = null) {
		if ($id == null || !$this->Row->hasAny(array('Row.id'))) {
			$this->redirect(array('action' => 'index'));
		}

		if (!empty($this->data)) {
			if ($this->Row->save($this->data)) {
				$this->Session->setFlash(__('The row has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The row could not be saved. Please, try again.', true));
			}
		}
		else {
			$this->data = $this->Row->read(null, $id);
		}
	}//end admin_edit()


	/**
	 * Action for admin to delete a row
	 *
	 * @param integer $id Id of the row to be deleted
	 * @access public
	 * @return void
	 */
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid row id', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Row->softDelete($id)) {
			$this->Session->setFlash(__('Row deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Row was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}//end admin_delete()

}//end class

