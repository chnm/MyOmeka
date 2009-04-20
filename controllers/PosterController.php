<?php
/**
* MyPoster controller
*/

require_once 'MyOmekaPoster.php';
require_once 'MyOmekaNote.php';

class MyOmeka_PosterController extends Omeka_Controller_Action
{        
    
    const UNTITLED_POSTER_TITLE = 'Untitled';
    
    public function init()
    {
        $this->_currentUser = Omeka_Context::getInstance()->getCurrentUser();
    }
    
    /**
     * This action should only be available on the admin interface.
     **/
    public function browseAction()
    {
        //clear the new poster id for discard
        unset($_SESSION['my_omeka_new_poster_id']);
        
        $requestParams = $this->_request->getParams();
        
        // Make sure we're on the first page if 'page' isn't set.
        $requestParams['page'] = (int)$requestParams['page'] or $requestParams['page'] = 1;

        $posters = $this->getTable('MyOmekaPoster')->findBy($requestParams);
        $posterCount = $this->getTable('MyOmekaPoster')->count($requestParams);
        $this->view->page = $requestParams['page'];
        $this->view->perPage = MyOmekaPosterTable::POSTERS_PER_PAGE;
        $this->view->posters = $posters;
        $this->view->totalPosters = $posterCount;
    }

    public function editAction()
    {           
        // Get the poster object
        $poster = $this->findById(null, 'MyOmekaPoster');
        
        $this->_verifyAccess($poster, 'edit');
        
        // Retrieve items that were noted and tagged by users
        $items = $this->getTable('MyOmekaNote')->findTaggedAndNotedItemsByUserId($this->_currentUser->id);
        
        $this->view->assign(compact('poster', 'posterItems', 'items'));            
    }
    
    protected function _verifyAccess($poster, $action)
    {
        // Block access for users who didn't make the poster, or people who don't
        // have permission.
        if ($poster->user_id != $this->_currentUser->id and !$this->isAllowed($action . 'Any')) {
            throw new Omeka_Controller_Exception_403();
        } 
    }
        
    /**
     * @todo Should poster viewing be limited to users who have not been given access,
     * or is it OK for anyone to have access if they can guess the URL?  One
     * possible solution is to block access to people who haven't built the poster
     * unless they are given a unique URL via the 'share' component.
     */
    public function showAction()
    {
        //clear the new poster id for discard
        unset($_SESSION['my_omeka_new_poster_id']);
                
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
        //clear the new poster id for discard
        unset($_SESSION['my_omeka_new_poster_id']);
                
        // Get the poster object
        $poster = $this->findById(null, 'MyOmekaPoster');
        
        if ($this->getRequest()->isPost()) {
            $validator = new Zend_Validate_EmailAddress();
            $emailTo = $this->getRequest()->getPost('email_to');
            if(Zend_Validate::is($emailTo, 'EmailAddress')){
                $site_title = get_option('site_title');
        		$from = get_option('administrator_email');
        		
                $user = Omeka_Context::getInstance()->getCurrentUser();
                $subject = $user->username . " shared a poster with you";
                
                $body = $user->username . " shared a poster with you on $site_title. \n\n";
                $body .= "Click here to view the poster:\n";
                
                // Hack to get access to the abs_uri() function in the view helpers.
                require_once HELPER_DIR . DIRECTORY_SEPARATOR . 'all.php';
                $body .= abs_uri(array('action'=>'show', 'id'=>$poster->id), 'myOmekaPosterActionId');
                
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
        //clear the new poster id for discard
        unset($_SESSION['my_omeka_new_poster_id']);
                   
        // Get the poster object
        $poster = $this->findById(null, 'MyOmekaPoster');
        
        $this->_verifyAccess($poster, 'edit');
        
        $params = $this->getRequest()->getParams();
        $poster->title = !empty($params['title']) ? $params['title'] : self::UNTITLED_POSTER_TITLE;
        $poster->description = $params['description'];
        $poster->updateItems($params);
        $poster->save();
        
        if (is_admin_theme()) {
            $this->redirect->gotoRoute(array('action'=>'browse'), 'myOmekaPosterAction');
        } else {
            $this->redirect->gotoRoute(array(), 'myOmekaDashboard');
        }
    }

    
    public function discardAction()
    {        
        if (isset($_SESSION['my_omeka_new_poster_id'])) {
            // if the poster was just created and not yet saved by the edit form, then delete it
            $poster = $this->findById($_SESSION['my_omeka_new_poster_id'], 'MyOmekaPoster');
            // Check to make sure the poster belongs to the logged in user
            $this->_verifyAccess($poster, 'delete');
            // delete the poster
            $poster->delete();
            //clear the new poster id for discard
            unset($_SESSION['my_omeka_new_poster_id']);
        }
        
        if (is_admin_theme()) {
            $this->redirect->gotoRoute(array('action'=>'browse'), 'myOmekaPosterAction');
        } else {
            $this->redirect->gotoRoute(array(), 'myOmekaDashboard');
        }
    }

    public function newAction()
    {        
        $poster = new MyOmekaPoster();
        $poster->title = self::UNTITLED_POSTER_TITLE;
        $poster->user_id = $this->_currentUser->id;
        $poster->description = '';
        $poster->date_created = date( 'Y-m-d H:i:s', time() );
        $poster->save();
        
        // set the new poster id for discard
        $_SESSION['my_omeka_new_poster_id'] = $poster->id;
        
        return $this->redirect->gotoRoute( array('action'=>'edit','id'=>$poster->id), 'myOmekaPosterActionId');        
    }
    
    public function deleteAction()
    {
        //clear the new poster id for discard
        unset($_SESSION['my_omeka_new_poster_id']);
        
        $poster = $this->findById(null, 'MyOmekaPoster');
        
        // Check to make sure the poster belongs to the logged in user
        $this->_verifyAccess($poster, 'delete');
        
        $poster->delete();
        $this->flash("\"$poster->title\" was successfully deleted");
        
        // Try to redirect to the HTTP Referer, otherwise go back to the dashboard.
        $redirectUrl = $_SERVER['HTTP_REFERER'];
        if ($redirectUrl) {
            return $this->redirect->gotoUrl($redirectUrl);
        } else {
            return $this->redirect->gotoRoute(array(), 'myOmekaDashboard');
        }
    }

    public function addPosterItemAction()
    {   
        $params = $this->getRequest()->getParams();
        $itemId = $params['item-id'];
        $posterItem = $this->getTable('Item')->find((int) $itemId);
        $noteObj = $this->getTable('MyOmekaNote')->findByUserIdAndItemId($this->_currentUser->id, $itemId);
        if ($noteObj) {
            $this->view->noteText = $noteObj->note;
        }
        $this->view->posterItem = $posterItem;
        $this->render('spot');
    }
}