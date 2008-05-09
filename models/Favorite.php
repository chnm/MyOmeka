<?php
get_db()->addTable('Favorite', 'favorites');

class Favorite extends Omeka_Record{
    public $id;
    public $annotation ='';
    public $user_id;
    public $item_id;
    public $date_modified;
}

?>