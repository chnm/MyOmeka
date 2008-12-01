<?php
require_once 'MyOmekaNoteTable.php';
class MyOmekaNote extends Omeka_Record{
    public $id;
    public $note ='';
    public $user_id;
    public $item_id;
    public $date_modified;
        
    // Remove this when possible.
    public function getItemNotes($user_id, $item_id){
        return $this->getTable()->findByUserIdAndItemId($user_id, $item_id);
    }
}