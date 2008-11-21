<?php
require_once 'MyOmekaPosterItemTable.php';
class MyOmekaPosterItem extends Omeka_Record{
    public $annotation = '';
    public $poster_id;
    public $item_id;
    public $ordernum;
}