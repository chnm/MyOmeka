<?php
get_db()->addTable('Favorite', 'favorites');

class Favorite extends Omeka_Record{
    public $id;
    public $annotation ='';
    public $user_id;
    public $item_id;
    public $date_modified;
    
    public function getFavoriteItemsByUser($user_id){
        /*
            TODO do something about sql injection potential
        */
        $db = get_db();
		$sql = "SELECT f.*, i.* FROM {$db->prefix}favorites f
                    JOIN {$db->prefix}items i ON i.id = f.item_id
                    WHERE f.user_id = $user_id";

        return $db->getTable("Item")->fetchObjects($sql);
    }

    public function getFavoriteByItemId($user_id, $item_id){

        $db = get_db();
		$sql = "SELECT f.* FROM {$db->prefix}favorites f 
                    WHERE f.user_id = $user_id
					AND f.item_id = $item_id";
					
        return $db->getTable("Favorite")->fetchObjects($sql);
    }
}

?>