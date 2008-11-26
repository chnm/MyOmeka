<?php 
/**
*  MyOmeka Controller
*/

require_once 'User.php';
require_once 'TagTable.php';
require_once 'MyOmekaPoster.php';
require_once 'MyOmekaNote.php';
require_once 'MyOmekaTag.php';

/**
 * @param string
 * @return void
 **/
class MyOmeka_MyOmekaController extends Omeka_Controller_Action
{
		
	public function indexAction()
	{		
        $this->_forward('dashboard');
	}
	
	/**
	 * @todo Block access via the ACL (instead of forwarding to login within the action).
	 * 
	 * @param string
	 * @return void
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
		
		//send the user an email telling them about their great new user account
				
		$site_title = get_option('site_title');
		$from = get_option('administrator_email');
		
		$body = "Welcome!\n\nYour account for the ".$site_title." archive has been created. Your username is ".$user->username.". Please click the following link to activate your account:\n\n"
		. uri( get_option('my_omeka_page_path') . "activate?u={$ua->url}") . "\n\n (or use any other page on the site).\n\nBe aware that we log you out after 15 minutes of inactivity to help protect people using shared computers (at libraries, for instance).\n\n".$site_title." Administrator";
		$title = "Activate your account with the ".$site_title." Archive";
		$header = 'From: '.$from. "\n" . 'X-Mailer: PHP/' . phpversion();
		return mail($user->email, $title, $body, $header);
	}
	
	public function helpAction()
	{
	    
	}
}