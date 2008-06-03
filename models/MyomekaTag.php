<?php

require_once 'Taggable.php';

class MyomekaTag extends Omeka_Record{
    public $id;
    
	protected function construct()
	{
		$this->_modules[] = new Taggable($this);
	}
}

?>