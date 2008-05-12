<?php
get_db()->addTable('Poster', 'posters');
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
        // return $db->query("")->fetchAll();
        return $db->getTable("Poster")->fetchObjects("  SELECT * 
                                                        FROM {$db->prefix}posters 
                                                        WHERE user_id = $user_id");
    }
    
    public function getPosterItems($poster_id){
        /*
            TODO do something about sql injection potential
        */
        $db = get_db();
        return $db->getTable("Item")->fetchObjects("SELECT p.*, i.*
                                                    FROM {$db->prefix}posters_items p 
                                                    JOIN {$db->prefix}items i ON i.id = p.item_id
                                                    WHERE p.poster_id = $poster_id
                                                    ORDER BY ordernum");
    }
}

?>
