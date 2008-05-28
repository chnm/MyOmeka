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
        return $db->getTable("Item")->fetchObjects("   SELECT p.*, i.*,f.annotation as 'favoriteAnnotation'
                                                        FROM {$db->prefix}posters_items p 
                                                        JOIN {$db->prefix}items i ON i.id = p.item_id
                                                        JOIN {$db->prefix}favorites f ON f.item_id = p.item_id
                                                        WHERE p.poster_id = $poster_id
                                                        ORDER BY ordernum");
    }
    
    public function updateItems(&$params)
    {   
        if(is_numeric($params['itemCount'])){
            $this->deletePosterItems();
            foreach(range(1, $params['itemCount']) as $ordernum){
                $item = new PosterItem();
                $item->annotation = $params['annotation-' . $ordernum];
                $item->poster_id = $this->id;
                $item->item_id = $params['id-' . $ordernum];
                $item->ordernum = $ordernum;
                $item->save();
            }
        }        
    }
    
    private function deletePosterItems()
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
    
    public function _delete()
    {
        $this->deletePosterItems();
    }
}

?>