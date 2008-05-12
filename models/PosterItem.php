<?php
get_db()->addTable('PosterItem', 'posters_items');
class PosterItem extends Omeka_Record{
    public $annotation = '';
    public $poster_id;
    public $item_id;
    public $ordernum;
}

?>
