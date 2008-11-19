<?php

class MyOmekaNoteTable extends Omeka_Db_Table
{
    protected $_name = 'notes';
    
    public function findByUserIdAndItemId($userId, $itemId)
    {
        $select = $this->getSelect()->where('user_id = ?', $userId)
            ->where('item_id = ?', $itemId);
        
        return $this->fetchObject($select);
    }
    
    public function findItemsByUserId($userId)
    {
        $itemTable = $this->getDb()->getTable('Item');
        $iAlias = $itemTable->getTableAlias();
        $nTableName = $this->getTableName();
        $select = $itemTable->getSelect()->joinInner(array('n' => $nTableName),
           "n.item_id = $iAlias.id", array());
          
        return $itemTable->fetchObjects($select);
    }
}
