<?php
/**
* MyPoster controller
*/

require_once 'MyOmekaPoster.php';
require_once 'MyOmekaNote.php';
require_once 'MyOmekaTag.php';

class MyOmeka_PosterController extends Omeka_Controller_Action
{    
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
        $posters = new MyOmekaPoster();
        $posters = $posters->getPosters();

        return $this->render('admin-poster',compact("posters"));
    }
    
    public function editAction()
    {   
        $posterId = $this->_getParam('id');
        
        // Get the current user
        if($user = Omeka_Context::getInstance()->getCurrentUser()){            
            // Get the poster object
            $poster = $this->findById($posterId, 'MyOmekaPoster');

            // Get items already part of the poster
            $posterItems = $poster->getPosterItems($poster_id);

            // Doesn't work yet (what did this do in the first place?)
            // Get objects with notes and objects that the user has tagged
            // $noteObj = new Note();
            // $myomekatagObj = new MyomekaTag();
            // $mixedItems = array_merge(
            //                         $noteObj->getNotedItemsByUser($user->id),
            //                         $myomekatagObj->getItemsTaggedByUser($user->id)
            //                     );
            
            // Loop through the items to make sure we only have one of each item
            // $items = array();
            // foreach($mixedItems as $item){
            //     $items[$item->id] = $item;
            // }
            
            $this->view->assign(compact('poster', 'posterItems'));            
        } else {
            var_dump('woooo');exit;
            return $this->redirect->gotoRoute(array(), 'myOmekaDashboard');
        }
        
    }
    
    public function viewAction()
    {        
        // Get the poster object
        $poster = $this->findById(null, 'MyOmekaPoster');
        $this->view->poster = $poster;
    }
    
    public function shareAction()
    {   
        $poster_id = $this->_getParam('id');
        
        // Get the poster object
        $poster = $this->findById(null, 'MyOmekaPoster');
        
        if ($this->getRequest()->isPost()) {
            $validator = new Zend_Validate_EmailAddress();
            $emailTo = $this->getRequest()->getPost('email_to');
            if(Zend_Validate::is($emailTo, 'EmailAddress')){
                $site_title = get_option('site_title');
        		$from = get_option('administrator_email');
        		
                $user = Omeka::loggedIn();
                $subject = $user->username . " shared a poster with you";
                
                $body = $user->username . " shared a poster with you on $site_title. \n\n";
                $body .= "Click here to view the poster:\n";
                
                // Hack to get access to the abs_uri() function in the view helpers.
                require_once HELPER_DIR . DIRECTORY_SEPARATOR . 'all.php';
                $body .= abs_uri(array('action'=>'view', 'id'=>$poster->id), 'myOmekaPosterActionId');
                
                $header = "From: $from\n";
                $header .= "X-Mailer: PHP/" . phpversion();

                mail($emailTo, $subject, $body, $header);
                $emailSent = true;                
            } else {
                $this->flash("Invalid email address");
            }
        }
        
        $this->view->assign(compact("poster", "emailSent","emailTo"));
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
        
        $this->redirect->gotoRoute(array(), 'myOmekaDashboard');
    }


    public function newAction(){

        $user = Omeka_Context::getInstance()->getCurrentUser();
        
        $poster = new MyOmekaPoster();
        $poster->title = 'untitled';
        $poster->user_id = $user->id;
        $poster->description = '';
        $poster->date_created = date( 'Y-m-d H:i:s', time() );
        $poster->save();
        
        return $this->redirect->gotoRoute( array('action'=>'edit', 'id'=>$poster->id), 
        'myOmekaPosterActionId');
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
            return $this->redirect->gotoRoute(array('action'=>'admin-posters'), 'myOmekaPosterAction');
        } else {
            return $this->redirect->gotoRoute(array(), 'myOmekaDashboard');
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