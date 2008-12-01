<?php

/**
* 
*/
class MyOmekaControllerPlugin extends Zend_Controller_Plugin_Abstract
{
    protected $_loginRequiredActions = array(
        array('my-omeka', 'dashboard'), 
        array('my-omeka', 'index'),
        array('poster', 'edit'),
        array('poster', 'share'),
        array('poster', 'delete'),
        array('poster', 'save'),
        array('poster', 'new'),
        array('note', 'edit'),
        array('tag', 'add'),
        array('tag', 'delete'));
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->_preventAdminAccess($request);
        // If we're in the MyOmeka plugin, force us to log in for most of the actions.
        $this->_forceLogin($request);
    }
    
    protected function _forceLogin($request)
    {
        if ('my-omeka' == $request->getModuleName()) {
            $user = Omeka_Context::getInstance()->getCurrentUser();
            
            // If the user needs to login before accessing an action, then redirect to the login page.
            if (!$user and in_array(array($request->getControllerName(), $request->getActionName()), $this->_loginRequiredActions)) {
                
                // The following code piggybacks off the current (0.10) 
                // implementation of UsersController::loginAction().  May need 
                // to change in the future.
                $session = new Zend_Session_Namespace;
                $session->redirect = $request->getPathInfo();
                $this->_getRedirect()->goto('login', 'users', 'default');
            }
        }        
    }
    
    protected function _preventAdminAccess($request)
    {
        $user = Omeka_Context::getInstance()->getCurrentUser();
        // If we're logged in, then prevent access to the admin for MyOmeka users
        if ($user and $user->role == MYOMEKA_USER_ROLE and is_admin_theme()) {
            exit;
        }
    }
    
    protected function _getRedirect()
    {
        return Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
    }
}
