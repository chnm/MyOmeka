<?php

require_once 'MyOmekaTaggable.php';

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
			$query = "SELECT DISTINCT i.*
                      FROM {$db->prefix}taggings AS t 
                      JOIN {$db->prefix}items AS i ON t.relation_id = i.id
                      WHERE t.type = 'MyomekaTag' AND t.entity_id = $user->entity_id";
            return $db->getTable("Item")->fetchObjects($query);
		}
	}
}

?>