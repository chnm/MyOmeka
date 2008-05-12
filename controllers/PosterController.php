<?php
/**
* MyPoster controller
*/

// Is there a better way to handle this path?
require_once "plugins/MyOmeka/models/Poster.php";
require_once "plugins/MyOmeka/models/Favorite.php";

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
    
    public function editAction()
    {        
        $poster_id = $this->_getParam('id');
        
        // Get the current user
        $user = Omeka::loggedIn();
        
        // Get the poster object
        $poster = $this->findById();
    
        // Get items already part of the poster
        $posterItems = $poster->getPosterItems($poster_id);
        
        // Get all favorited items
        $favs = new Favorite();
        $items = $favs->getFavoriteItemsByUser($user->id);

        return $this->render('myposter/editPoster.php', compact("poster","posterItems","items"));
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
    
    public function shareAction()
    {   
        $poster_id = $this->_getParam('id');
        
        // Get the poster object
        $poster = $this->findById();
                    
        // If form is being submitted, handle it
        if($emailTo = $this->_getParam('emailTo')){
            $validator = new Zend_Validate_EmailAddress();
            $user = Omeka::loggedIn();
            if($validator->isValid($emailTo)){
                $site_title = get_option('site_title');
        		$from = get_option('administrator_email');
                $subject = $user->username . " shared a poster with you";
                
                $body = $user->username . " shared a poster with you on $site_title. \n\n";
                $body .= "Click here to view the poster:\n";
                $body .= WEB_ROOT . "poster/view/" . $poster_id;
                
                $header = "From: $from\n";
                $header .= "X-Mailer: PHP/" . phpversion();

                mail($emailTo, $subject, $body, $header);
                $emailSent = true;                
            } else {
                $this->flash("Invalid email address");
            }
        }
        
        return $this->render('myposter/sharePoster.php', compact("poster", "emailSent","emailTo"));   
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


    public function newAction(){

        $user = Omeka::loggedIn();
        
        $poster = new Poster();
        $poster->title = 'untitled';
        $poster->user_id = $user->id;
        $poster->description = '';
        $poster->date_created = date( 'Y-m-d H:i:s', time() );
        $poster->save();
        
        return $this->_redirect('myposter/editPoster/' . $poster->id);
        
    }
    
    public function deleteAction()
    {   
        // Get the poster object
        $poster_id = $this->_getParam('id');
        $poster = $this->findById();
        
        // Check to make sure the poster belongs to the logged in user
        $user = Omeka::loggedIn();
        if($user->id === $poster->user_id){
            $poster->delete();
            $this->flash("\"$poster->title\" was successfully deleted");
        }
        return $this->_redirect('myomeka/dashboard');
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