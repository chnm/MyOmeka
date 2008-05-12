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
        return $db->getTable("Favorite")->fetchObjects("SELECT f.*, i.*
                                                        FROM {$db->prefix}favorites f
                                                        JOIN {$db->prefix}items i ON i.id = f.item_id
                                                        WHERE f.user_id = $user_id");
    }
    
    
}

?>