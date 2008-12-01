<?php
require_once 'Omeka/Controller/Action.php';

class MyOmeka_TagController extends Omeka_Controller_Action
{    
	public function addAction()
	{
	    $user = Omeka_Context::getInstance()->getCurrentUser();
	    
	    $itemId = (int)$this->getRequest()->getPost('item_id');
	    if (!$itemId) {
	       throw new Exception('Item ID must be an integer!');
	    }
	    
	    $taggable = $this->_getTaggable($itemId);
	    
	    $taggedEntity = $this->getTable("Entity")->find($user->entity_id);	
	    $tagsToAdd = $this->getRequest()->getPost('tag');
	    
	    $taggable->addTags($tagsToAdd, $taggedEntity);
	    
	    // And redirect back to the item.
	    $this->redirect->gotoRoute(array('controller'=>'items', 'action'=>'show', 'id'=>$itemId), 'id');
	}
	
	/**
	 * This hack depends on the current behavior of Omeka in 0.10. It uses the
     * Taggable mixin on its own (in a way unintended by the existing API), which
     * indicates that the Taggable mixin should probably just be using some static
     * methods so that this behavior can be implemented elsewhere as in this case.
     * 
     * Note that this will likely break when a new version of Omeka comes out. 
	 */
	protected function _getTaggable($itemId)
	{
	    $item = $this->getTable('Item')->find($itemId);
	    
	    // This also seems like a hack.
	    $taggable = new Taggable($item);
	    
	    // And here is the money.
	    $taggable->type = MYOMEKA_TAG_TYPE;
	    
	    return $taggable;
	}
	
	protected function _deleteTaggings($tagId, $itemId, $entityId)
	{
	    $taggings = $this->getTable('Taggings')->findBySql(
	        'tag_id = ? AND relation_id = ? AND entity_id = ? AND `type` = ?', 
	        array($tagId, $itemId, $entityId, MYOMEKA_TAG_TYPE));
	    
	    foreach ($taggings as $tagging) {
	        $tagging->delete();
	    }
	}
	
	/**
	 * Delete a tag associated with MyOmeka.
	 **/
	public function deleteAction()
	{
		$tagId = $this->_getParam('tag_id');
		$itemId = $this->_getParam('item_id');
	    if (($user = current_user()) && !empty($itemId) && !empty($tagId)){
            $this->_deleteTaggings($tagId, $itemId, $user->entity_id);
            return $this->redirect->gotoRoute(array('controller'=>'items', 'action'=>'show', 'id'=>$itemId), 'id');
        }
	}
}