<?php
require_once 'MyOmekaNote.php';
require_once 'Item.php';
require_once 'Omeka/Controller/Action.php';

class MyOmeka_NoteController extends Omeka_Controller_Action
{
    // protected $_modelClass = "MyOmekaNote";
	
	public function editAction()
	{
    	$currentUser = Omeka_Context::getInstance()->getCurrentUser();
    	$userId = $currentUser->id;
    	$itemId = (int)$this->getRequest()->getPost('item_id');
    	
    	if (!$itemId) {
    	   throw new Exception('Item ID must be an integer!');
    	}
    	    	
    	$note = $this->getTable('MyOmekaNote')->findByUserIdAndItemId($userId, $itemId);
    	
    	$noteText = $this->getRequest()->getPost('note');
    	
    	if (!empty($noteText)) {
    	   if (!$note) {
    	       $note = new MyOmekaNote;
    	       $note->user_id = $userId;
          	   $note->item_id = $itemId;
    	   }
    	   
   	       // This field should really be called 'text' but dunno if it's 
   	       // worth writing a data migration just for that.
   	       $note->note = $noteText;
   	       $note->forceSave();
    	} else {
    	    // Delete empty notes from the db.
    	   if ($note instanceof MyOmekaNote) {
    	       $note->delete();
    	   }
    	}
    	
    	$this->redirect->gotoRoute(array('controller'=>'items', 'action'=>'show', 'id'=>$itemId), 'id');
	}
}