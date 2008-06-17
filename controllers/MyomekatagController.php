<?php
require_once "plugins/MyOmeka/models/MyomekaTag.php";
require_once "Omeka/Controller/Action.php";

class MyomekaTagController extends Omeka_Controller_Action
{
    protected $_modelClass = "MyomekaTag";

	public function indexAction()
	{
		echo "Index Action";
	}
	
	public function addAction()
	{
	    if($user = Omeka::loggedIn() && is_numeric($_POST['item_id'])){
            $user = current_user();
            $myomekatag = new MyomekaTag;
            $myomekatag->id = $_POST['item_id'];
            $myomekatag->addTags($_POST['tag'], get_db()->getTable("Entity")->find($user->entity_id));
            
            return parent::_redirect('/items/show/'.$_POST['item_id']);
        } else {
            print "Error in params";
        }
	}
	
	public function deleteAction()
	{
	    if($user = Omeka::loggedIn() && is_numeric($_GET['item_id']) && isset($_GET['tag'])){
            $user = current_user();
            $myomekatag = new MyomekaTag;
            $myomekatag->id = $_GET['item_id'];
            $myomekatag->deleteTags($_GET['tag'], get_db()->getTable("Entity")->find($user->entity_id));
            
            return $this->_redirect('/items/show/'.$_GET['item_id']);
        } else {
            print "Error in params";
        }
	}
	
	public function browseAction()
	{
	    if($user = Omeka::loggedIn() && is_numeric($this->_getParam('id'))){
	        $user = current_user();
	        $db = get_db();
            $items = $db->getTable("Item")->fetchObjects("SELECT i.*
                                                            FROM {$db->prefix}taggings t 
                                                            JOIN {$db->prefix}items i ON t.relation_id = i.id
                                                            WHERE t.entity_id = $user->entity_id
                                                                AND t.tag_id = ".$this->_getParam('id')."
                                                                AND t.type = 'MyomekaTag'");
            $tag = $db->getTable("Tag")->find($this->_getParam('id'));
            $this->render('items/browse.php', compact("items", "tag"));
        } else {
            print "Error in params";
        }
	}
}
?>





















