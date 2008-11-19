<?php 

/**
* 
*/
class MyOmekaPosterTable extends Omeka_Db_Table
{
    // Original table name was 'posters', not 'my_omeka_posters'
    protected $_name = 'posters';
    
    public function findByUserId($userId)
    {
        $select = $this->getSelect()->where('user_id = ?', $userId);
        return $this->fetchObjects($select);
    }
}
