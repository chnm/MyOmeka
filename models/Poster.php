<?php
get_db()->addTable('Poster', 'posters');
require_once "PosterItem.php";
class Poster extends Omeka_Record{
    public $title;
    public $description = '';
    public $user_id;
    public $date_created;
    
    public function getUserPosters($user_id){
        /*
            TODO do something about sql injection potential
        */
        $db = get_db();
        return $db->getTable("Poster")->fetchObjects(" SELECT * 
                                                        FROM {$db->prefix}posters 
                                                        WHERE user_id = $user_id");
    }
    
    public function getPosterItems($poster_id){
        /*
            TODO do something about sql injection potential
        */
        $db = get_db();
        return $db->getTable("Item")->fetchObjects("   SELECT p.*, i.*
                                                        FROM {$db->prefix}posters_items p 
                                                        JOIN {$db->prefix}items i ON i.id = p.item_id
                                                        WHERE p.poster_id = $poster_id
                                                        ORDER BY ordernum");
    }
    
    public function _delete()
    {
        // Delete entries from posters_items table
        $db = get_db();
        $posters_items =  $db->getTable("PosterItem")->fetchObjects("SELECT * 
                                                                    FROM {$db->prefix}posters_items p
                                                                    WHERE p.poster_id = $this->id");
        foreach($posters_items as $poster_item){
            $poster_item->delete();
        }
    }
}

?>