<?php
get_db()->addTable('Note', 'notes');

class Note extends Omeka_Record{
    public $id;
    public $note ='';
    public $user_id;
    public $item_id;
    public $date_modified;
    
    public function getNotedItemsByUser($user_id){
        if(is_numeric($user_id)){
            $db = get_db();
    		$sql = "SELECT n.*, i.* FROM {$db->prefix}notes n
                        JOIN {$db->prefix}items i ON i.id = n.item_id
                        WHERE n.user_id = $user_id";

            return $db->getTable("Item")->fetchObjects($sql);
        }
    }

    public function getItemNotes($user_id, $item_id){
        if(is_numeric($user_id) && is_numeric($item_id)){
            $db = get_db();
    		$sql = "SELECT n.* FROM {$db->prefix}notes n 
    		        WHERE n.user_id = $user_id 
    		        AND n.item_id = $item_id
    		        LIMIT 1";
			return $db->getTable("Note")->fetchObjects($sql);
        }
    }
}

?>