<?php

require_once 'Taggable.php';

class MyomekaTag extends Omeka_Record{
    public $id;
    
	protected function construct()
	{
		$this->_modules[] = new Taggable($this);
	}
	
	public function getItemsTaggedByUser($user_id)
	{
		if(is_numeric($user_id)){
		    $db = get_db();
		    $user = $db->getTable("User")->find($user_id);
            return $db->getTable("Item")->fetchObjects("SELECT i.*
                                                        FROM {$db->prefix}taggings t 
                                                        JOIN {$db->prefix}items i ON t.relation_id = i.id
                                                        WHERE t.type = 'MyomekaTag' AND t.entity_id = $user->entity_id");
		}
	}
}

?>