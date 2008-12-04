<?php 
/**
*  MyOmeka Controller
*/

require_once 'User.php';
require_once 'TagTable.php';
require_once 'MyOmekaPoster.php';
require_once 'MyOmekaNote.php';

class MyOmeka_MyOmekaController extends Omeka_Controller_Action
{
		
	public function indexAction()
	{		
        $this->_forward('dashboard');
	}
	
	/**
	 * The starting point for all MyOmeka interactions.
	 **/
	public function dashboardAction()
	{		
		$current = Omeka_Context::getInstance()->getCurrentUser();

	    // Get the user's existing posters
        $posters = $this->getTable('MyOmekaPoster')->findByUserId($current->id);

        // Get tagged and noted items
        $items = $this->getTable('MyOmekaNote')->findTaggedAndNotedItemsByUserId($current->id);
        
        // Get tags made by the user viewing the dashboard.
        $tags = $this->getTable('Tag')->findBy(array('user'=>$current->id, 'type'=>'MyOmekaTag'));
        
        $this->view->assign(compact("posters","items","tags"));
	}
	
	/**
	 * Register thyself for a user account with Omeka
	 *
	 * @return void
	 **/
	public function registerAction()
	{
		$emailSent = false; //true only if an registration email has been sent 
		
		$user = new User();
		$user->role = MYOMEKA_USER_ROLE;
		$requireTermsOfService = get_option('my_omeka_require_terms_of_service');

		try {
		    if ($this->getRequest()->isPost()) {		        
		        if (!$requireTermsOfService || terms_of_service_checked_form_input()) {
		            // Do not allow anyone to manipulate the role on this form.
		            unset($_POST['role']);
    				$user->saveForm($_POST);
					$this->sendActivationEmail($user);
					$this->flashSuccess('Thank for registering for a user account.  To complete your registration, please check your email and click the provided link to activate your account.');
					$emailSent = true;
    			} else {
    			 	$this->flash('You cannot register unless you understand and agree to the Terms Of Service and Privacy Policy.');
    			}
		    }			
		} catch (Omeka_Validator_Exception $e) {
			$this->flashValidationErrors($e);
		}
		
		$this->view->assign(compact('emailSent', 'requireTermsOfService', 'user'));
	}	

	public function sendActivationEmail($user)
	{
		$ua = new UsersActivations;
		$ua->user_id = $user->id;
		$ua->save();
		
        $toEmail = $user->Entity->email;
        $toName = $user->Entity->first_name . ' ' . $user->Entity->last_name;
        //send the user an email telling them about their great new user account

        $this->view->user = $user;
        $this->view->activationSlug = $ua->url;
        $this->view->siteTitle = get_option('site_title');

        $mail = new Zend_Mail();
        $mail->setBodyText($this->view->render('my-omeka/register.mail.php'));
        $mail->setFrom(get_option('administrator_email'), $this->view->siteTitle . ' Administrator');
        $mail->addTo($toEmail, $toName);
        $mail->setSubject("Activate your account with the {$this->view->siteTitle} Archive");
        $mail->send();
	}
	
	public function helpAction()
	{
	    
	}
}