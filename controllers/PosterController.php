<?php
/**
* MyPoster controller
*/

require_once PLUGIN_DIR.DIRECTORY_SEPARATOR."MyOmeka".DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."Poster.php";
require_once PLUGIN_DIR.DIRECTORY_SEPARATOR."MyOmeka".DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."Note.php";
require_once PLUGIN_DIR.DIRECTORY_SEPARATOR."MyOmeka".DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."MyomekaTag.php";

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
    
    public function adminPostersAction()
    {   
        // Get all of the posters on the site
        $posters = new Poster();
        $posters = $posters->getPosters();
        
        return $this->render('myposter/adminPoster.php',compact("posters"));
    }
    
    public function editAction()
    {   
        $poster_id = $this->_getParam('id');
        
        // Get the current user
        if($user = Omeka::loggedIn()){            
            // Get the poster object
            $poster = $this->findById();

            // Get items already part of the poster
            $posterItems = $poster->getPosterItems($poster_id);

            // Get objects with notes and objects that the user has tagged
            $noteObj = new Note();
            $myomekatagObj = new MyomekaTag();
            $items = array_merge(
                                    $noteObj->getNotedItemsByUser($user->id),
                                    $myomekatagObj->getItemsTaggedByUser($user->id)
                                );

            return $this->render('myposter/editPoster.php', compact("poster","posterItems","items"));
        } else {
            return $this->_redirect('myomeka/dashboard');
        }
        
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
                $body .= WEB_ROOT . "/poster/view/" . $poster_id;
                
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
        $params = $this->getRequest()->getParams();
        
        // Get the poster object
        $poster = $this->findById();
        $poster->title = $params['title'];
        $poster->description = $params['description'];
        $poster->updateItems($params);
        $poster->save();
        // var_dump($params);
        $this->_redirect("myomeka/dashboard");
    }


    public function newAction(){

        $user = Omeka::loggedIn();
        
        $poster = new Poster();
        $poster->title = 'untitled';
        $poster->user_id = $user->id;
        $poster->description = '';
        $poster->date_created = date( 'Y-m-d H:i:s', time() );
        $poster->save();
        
        return $this->_redirect('poster/edit/' . $poster->id);
        
    }
    
    public function deleteAction()
    {   
        // Get the poster object
        $poster_id = $this->_getParam('id');
        $returnDestination = $this->_getParam('return');
        
        $poster = $this->findById();
        
        // Check to make sure the poster belongs to the logged in user
        $user = Omeka::loggedIn();
        if($user->id === $poster->user_id){
            $poster->delete();
            $this->flash("\"$poster->title\" was successfully deleted");
        }
        if ($returnDestination) {
            return $this->_redirect('poster/adminPosters');
        } else {
            return $this->_redirect('myomeka/dashboard');
        }
    }

    public function addPosterItemAction()
    {   
        $params = $this->getRequest()->getParams();
        $id = $params['item-id'];
        $posterItem = get_db()->getTable('Item')->find((int) $id);
       return $this->render('common/_spot.php', compact("posterItem"));
    }
}