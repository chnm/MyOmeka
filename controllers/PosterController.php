<?php
/**
* MyPoster controller
*/

// Is there a better way to handle this path?
require_once "plugins/MyArchive/models/Poster.php";

class PosterController extends Omeka_Controller_Action
{
    public function indexAction()
    {
        $this->_forward('browse');
    }
    
    public function browseAction()
    {
        echo 'see all the posters done by people';
    }
    
    /**
     * Filter the items down to whatever items were annotated by the current user
     * 
     * @return void
     **/
    public function chooseItemAction()
    {
        /* $items = get_db()->getTable('Item')->findBy();
        return $this->render('myposter/_choose_item.php', compact('items')); */
        
        $this->_forward('browse', 'items', null, array('renderPage'=>'myposter/_choose_item.php'));
    }
    
    public function addAction()
    {
        return $this->render('myposter/form.php');
    }
    
    public function saveAction()
    {   
        $poster = new Poster;
        $params = $this->getRequest()->getParams();
        
        var_dump( $this->getRequest()->getParams() );
    }

    ////////////////////////////////////////
    ////// AJAX PARTIALS ////////
    ///////////////////////////////////////
    
    public function placeholderAction()
    {
        $item = $this->getItemForDisplay();
        
        //@testing Retrieve this from the database and not from the query
        $order = $this->_getParam('i');

        return $this->render('myposter/_spot.php', array('i'=>$order, 'item'=>$item));
    }
    
    /**
     * Form will POST an item_id
     * 
     * @param string
     * @return void
     **/
    protected function getItemForDisplay(Item $defaultItem=null)
    {
        $id = $this->_getParam('item_id');

        if(!$id) {
            return $defaultItem;
        }else {
            return get_db()->getTable('Item')->find((int) $id);
        }
    }
}