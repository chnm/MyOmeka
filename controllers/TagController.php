<?php
require_once 'MyomekaTag.php';
require_once 'Omeka/Controller/Action.php';

class MyOmeka_TagController extends Omeka_Controller_Action
{
	public function addAction()
	{
	    $user = Omeka_Context::getInstance()->getCurrentUser();
	    
	    // This hack depends on the current behavior of Omeka in 0.10.
	    // It uses the Taggable mixin on its own (in a way unintended by the
	    // existing API), which indicates that the Taggable mixin should probably
	    // just be using some static methods so that this behavior can be 
	    // implemented elsewhere as in this case.
	    // 
	    // Note that this will likely break when a new version of Omeka comes out.
	    
	    $itemId = (int)$this->getRequest()->getPost('item_id');
	    if (!$itemId) {
	       throw new Exception('Item ID must be an integer!');
	    }
	    
	    $tagsToAdd = $this->getRequest()->getPost('tag');
	    $item = $this->getTable('Item')->find($itemId);
	    
	    // This also seems like a hack.
	    $taggedEntity = $this->getTable("Entity")->find($user->entity_id);	    
	    $taggable = new Taggable($item);
	    
	    // And here is the money.
	    $taggable->type = 'MyomekaTag';
	    
	    // This should probably be applyTagString().
	    $taggable->addTags($tagsToAdd, $taggedEntity);
	    
	    // And redirect back to the item.
	    $this->redirect->gotoRoute(array('controller'=>'items', 'action'=>'show', 'id'=>$itemId), 'id');
	}
	
	public function deleteAction()
	{
		$tag = $this->_getParam('tag');
		$itemId = $this->_getParam('item_id');
		
	    if($user = Omeka::loggedIn() && is_numeric($itemId) && !empty($tag)){
            $user = current_user();
            $myomekatag = new MyomekaTag;
    
        	$myomekatag->id = $itemId;
	
            $myomekatag->deleteTags($tag, get_db()->getTable("Entity")->find($user->entity_id));
            
            return $this->_redirect('/items/show/'.$itemId);
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