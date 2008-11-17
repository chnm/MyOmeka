<?php
require_once "PosterItem.php";
require_once "Note.php";
class Poster extends Omeka_Record{
    public $title;
    public $description = '';
    public $user_id;
    public $date_created;
    
    public function getUserPosters($user_id){
        if(is_numeric($user_id)){
            $db = get_db();
            return $db->getTable("Poster")->fetchObjects(" SELECT * 
                                                            FROM {$db->prefix}posters 
                                                            WHERE user_id = $user_id");            
        }
    }
    
    public function getPosters(){
        $db = get_db();
        return $db->getTable("Poster")->fetchObjects(" SELECT p.*, u.username
                                                        FROM {$db->prefix}posters p
                                                        JOIN {$db->prefix}users u ON p.user_id = u.id");
    }
    
    public function getPosterItems($poster_id){
        if(is_numeric($poster_id)){
            $db = get_db();
            $items = $db->getTable("Item")->fetchObjects("  SELECT i.*, pi.annotation, p.user_id
                                                            FROM {$db->prefix}posters_items pi 
                                                            JOIN {$db->prefix}items i ON i.id = pi.item_id
                                                            JOIN {$db->prefix}posters p ON pi.poster_id = p.id
                                                            WHERE pi.poster_id = $poster_id 
                                                            ORDER BY ordernum");
           
           // Go through the items and add in the notes (This could probably be done above in a single query)
           $noteObj = new Note();
           foreach($items as $item){
               $note = $noteObj->getItemNotes($item->user_id, $item->id);
               $item->itemNote = $note[0]->note;
           }
           
           return $items;
        }
    }
    
    public function updateItems(&$params)
    {   
        if(is_numeric($params['itemCount'])){
            $this->deletePosterItems();
            if ($params['itemCount'] > 0) {
                foreach(range(1, $params['itemCount']) as $ordernum){
                    $item = new PosterItem();
                    $item->annotation = $params['annotation-' . $ordernum];
                    $item->poster_id = $this->id;
                    $item->item_id = $params['itemID-' . $ordernum];
                    $item->ordernum = $ordernum;
                    $item->save();
                }
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