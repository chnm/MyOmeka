<?php 

/**
* 
*/
class MyOmekaPosterTable extends Omeka_Db_Table
{
    // Original table name was 'posters', not 'my_omeka_posters'
    protected $_name = 'posters';
    
    const POSTERS_PER_PAGE = 10;
    
    public function findByUserId($userId)
    {
        $select = $this->getSelect()->where('user_id = ?', $userId);
        return $this->fetchObjects($select);
    }
    
    public function findBy($params)
    {
        $select = $this->getSelectForFindBy($params);

        $resultsPage = $params['page'];      
        $select->limitPage($resultsPage, self::POSTERS_PER_PAGE);
        
        $select->order('id ASC');
        
        return $this->fetchObjects($select);        
    }
}
