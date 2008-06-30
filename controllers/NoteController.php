<?php
require_once PLUGIN_DIR."/MyOmeka/models/Note.php";
require_once "Item.php";
require_once "Omeka/Controller/Action.php";

class NoteController extends Omeka_Controller_Action
{
    protected $_modelClass = "Note";

	public function indexAction()
	{
		echo "Index Action";
	}
	
	public function submitAction()
	{
    	if (is_numeric($_POST['item_id']) && $user = Omeka::loggedIn()) {
			$noteObj = new Note();
		    
		    // Check if this user has already added a note to this item
		    $result = $noteObj->getItemNotes($user->id, $_POST['item_id']);
            if(count($result)){
                $existingNote = $result[0];
                // Delete the note if it's an empty string
                if($_POST['note'] == ""){
                    $existingNote->delete();
                } else {
                    $existingNote->note = $_POST['note'];
                    $existingNote->save();
                }
            } elseif(!$_POST['note'] == ""){
                // Save the new, non-blank note
                $noteObj->user_id = $user->id;
    			$noteObj->note = $_POST['note'];
    			$noteObj->item_id = $_POST['item_id'];
    			$noteObj->save();
            }
    		return $this->_redirect('/items/show/'.$_POST['item_id']);
    	} else{
    		echo "Error in params";
    	}
	}
}
?>





















