<?php
/**
* MyPoster controller
*/

// Is there a better way to handle this path?
require_once "plugins/MyArchive/models/Poster.php";
require_once "plugins/MyArchive/models/Favorite.php";

class PosterController extends Omeka_Controller_Action
{
    protected $_modelClass = "Poster";
    
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
    
    public function editPosterAction()
    {
        $poster_id = $this->_getParam('id');
        
        if ($poster_id == "new") {
            $poster = newPoster();
        } else {
            // Get the poster object
            $poster = $this->findById();
        
            // Get items already part of the poster
            $posterItems = $poster->getPosterItems($poster_id);
            
            // Get all favorited items
            $favs = new Favorite();
            $items = $favs->getFavoriteItemsByUser(1);
        }
        return $this->render('myposter/form.php', compact("poster","posterItems","items"));
    }
    
    public function viewAction()
    {
        $poster_id = $this->_getParam('id');
        
        // Get the poster object
        $poster = $this->findById();
    
        // Get items already part of the poster
        $posterItems = $poster->getPosterItems($poster_id);
        
        return $this->render('myposter/viewPoster.php', compact("poster","posterItems"));
    }
    
    public function saveAction()
    {
        $poster = new Poster();
        $poster->title = "lalala";
        $poster->user_id = 232;
        $poster->date_created = date( 'Y-m-d H:i:s', time() );
        
        $poster->save();
        
        $params = $this->getRequest()->getParams();
        var_dump( $poster );
    }


    private function newPoster($user_id, $title = 'untitled', $description = ''){
        $poster = new Poster();
        $poster->title = $title;
        $poster->user_id = $user_id;
        $poster->description = $description;
        $poster->date_created = date( 'Y-m-d H:i:s', time() );
        $poster->save();        
        return $poster;
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