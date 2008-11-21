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
        return $this->getDb()->getTable('Item')->fetchObjects($this->getSelectForNotedItems($userId));
    }
    
    public function getSelectForNotedItems($userId)
    {
        $itemTable = $this->getDb()->getTable('Item');
        $iAlias = $itemTable->getTableAlias();
        $nTableName = $this->getTableName();
        $select = $itemTable->getSelect()->joinInner(array('n' => $nTableName),
           "n.item_id = $iAlias.id", array());
        $select->where('n.user_id = ?', $userId);        
        return $select;        
    }
    
    /**
     * @internal This doesn't really belong in this class but would have to find
     * a better place for it.
     * 
     * @param integer
     * @return Omeka_Db_Select
     **/
    public function getSelectForTaggedItems($userId)
    {
        $db = $this->getDb();
        $iTable = $db->getTable('Item');
        $iSelect = $iTable->getSelect()
            ->joinInner(array('tg'=>$db->Taggings), 'tg.relation_id = ' . $iTable->getTableAlias() . '.id', array())
            ->joinInner(array('u'=>$db->User), 'u.entity_id = tg.entity_id', array()) //Ugh, unnecessary.
            ->where('u.id = ?', $userId)
            ->where('tg.type = "MyomekaTag"');
        
        return $iSelect;
    }
    
    /**
     * @internal This follows the original behavior of the MyOmeka plugin
     * (to display noted and tagged items in one fell swoop), but does it 
     * actually behave the way we would want it to?  It probably needs to integrate
     * with the search, which is something it never did.  was it ever possible
     * to search through one's own tagged and/or noted items, or did we abandon
     * that?
     * 
     * @todo Implement pagination in this.
     * @param integer
     * @return array
     **/
    public function findTaggedAndNotedItemsByUserId($userId)
    {
        // UNION of 2 SELECT queries, one for selecting specially tagged items
        // and one for selecting noted items.
        
        $taggedSelect = $this->getSelectForTaggedItems($userId);
        $notedSelect = $this->getSelectForNotedItems($userId);
        
        $mainSelect = "($notedSelect) UNION DISTINCT ($taggedSelect)";
        return $this->getDb()->getTable('Item')->fetchObjects($mainSelect);
    }
}
