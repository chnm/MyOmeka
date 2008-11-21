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
    
    /**
     * @todo Pagination through posters?
     **/
    public function browseAction()
    {
        // Get all of the posters on the site
        $posters = new MyOmekaPoster();
        $posters = $posters->getPosters();
        $this->view->posters = $posters;
    }

    /**
     * @todo Poster editing has not been blocked for users who did not build the poster.
     **/
    public function editAction()
    {   
        $posterId = $this->_getParam('id');

        // Get the poster object
        $poster = $this->findById($posterId, 'MyOmekaPoster');

        // Get items already part of the poster
        $posterItems = $poster->getPosterItems($poster_id);
        
        // Retrieve items that were noted and tagged by users
        $currentUser = Omeka_Context::getInstance()->getCurrentUser();
        $items = $this->getTable('MyOmekaNote')->findTaggedAndNotedItemsByUserId($currentUser->id);
        
        $this->view->assign(compact('poster', 'posterItems', 'items'));            
    }
    
    /**
     * @todo Should poster viewing be limited to users who have not been given access,
     * or is it OK for anyone to have access if they can guess the URL?  One
     * possible solution is to block access to people who haven't built the poster
     * unless they are given a unique URL via the 'share' component.
     * 
     * @todo Are annotations HTML?  If not, they need to be properly escaped
     * when displayed.  If they are HTML, we need to pretty much require use
     * of the HtmlPurifier plugin alongside MyOmeka.
     */
    public function viewAction()
    {        
        // Get the poster object
        $poster = $this->findById(null, 'MyOmekaPoster');
        $this->view->poster = $poster;
    }
    
    /**
     * @todo Use view scripts to render the body of emails sent with MyOmeka
     * (which should be done within core Omeka as well).  This allows email
     * writers to use all of the available view helpers.  The rest of the email
     * should then be composed using Zend_Mail instead of the way we have been
     * doing it.
     */
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