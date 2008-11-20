<?php

/**
* 
*/
class MyOmekaControllerPlugin extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // If we're in the MyOmeka plugin, force us to log in for most of the actions.
        
        if ('my-omeka' == $request->getModuleName()) {
            $user = Omeka_Context::getInstance()->getCurrentUser();
            
            $loginRequiredActions = array('dashboard', 'index');
            
            // If the user needs to login before accessing an action, then redirect to the login page.
            if (!$user and in_array($request->getActionName(), $loginRequiredActions)) {
                
                // The following code piggybacks off the current (0.10) 
                // implementation of UsersController::loginAction().  May need 
                // to change in the future.
                $session = new Zend_Session_Namespace;
                $session->redirect = $request->getPathInfo();
                $this->_getRedirect()->goto('login', 'users', 'default');
            }
        }
    }
    
    protected function _getRedirect()
    {
        return Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
    }
}
