<?php 
/**
*  MyOmeka Controller
*
*  An incredible amount of this controller is a modified version of what's currently
*  found in the UsersController.  Because the routes are currently hard-coded 
*  (as of 0.9.1.1 release) there's no way around this.  A more agile Omeka core will
*  make this much simpler [DL]
*
*  PS: doing registration through an external controller currently stinks
*
*/

require_once 'User.php';
require_once 'TagTable.php';
require_once 'MyOmekaPoster.php';
require_once 'MyOmekaNote.php';
require_once 'MyOmekaTag.php';

/**
 * @todo Try to use built-in users controller to manage access to MyOmeka.
 * If you add a view script for users/login on the public interface it will load 
 * just like the admin side.  After login it will forward to whatever action was
 * blocked, i.e. the MyOmeka dashboard.  
 * 
 * This means get rid of all the extra code for logins that was duplicated from
 * the old Omeka.
 * 
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
		if($current = Omeka_Context::getInstance()->getCurrentUser()) {

		    // Get the user's existing posters
            $posters = $this->getTable('MyOmekaPoster')->findByUserId($current->id);

            // Get tagged and noted items
            
            // Should combine these 2 queries into a single query to obviate the
            // need for extra processing.
            $notedItems = $this->getTable('MyOmekaNote')->findItemsByUserId($current->id);
            $taggedItems = MyOmekaTag::getItemsTaggedByUser($current->id);
            
            // Loop through the items to make sure we only have one of each item
            $notedItems = array();
            $mixedItems = $notedItems + $taggedItems;
            foreach($mixedItems as $item){
                $notedItems[$item->id] = $item;
            }
            
            $tags = $this->getTable('Tag')->findBy(array('user'=>$current->id),"MyOmekaTag");
            
			$this->render('dashboard', compact("posters","notedItems","tags"));
		} else {
        	$this->_forward('login');
		}
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
		$user->role = "MyOmeka";
		$requireTermsOfService = get_option('myomeka_require_terms_of_service');

		try {
			 $agreedToTermsOfService = terms_of_service_checked_form_input();
			 
			if ($agreedToTermsOfService || !$requireTermsOfService) {
				if($user->saveForm($_POST)) {

					$user->email = $_POST['email'];
				
					$this->sendActivationEmail($user);
				
					$this->flashSuccess('Thank for registering for a user account.  To complete your registration, please check your email and click the provided link to activate your account.');
					$emailSent = true;
				}
			} else {
			 	$this->flash('You cannot register unless you understand and agree to the Terms Of Service and Privacy Policy.');
			}
		} catch (Omeka_Validator_Exception $e) {
			$this->flashValidationErrors($e);
		}
		
		$this->render('index', compact('emailSent', 'requireTermsOfService'));
	}	
	
	public function helpPageAction() {
	    $this->render('help-page');
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
		. uri( get_option('myomeka_page_path') . "activate?u={$ua->url}") . "\n\n (or use any other page on the site).\n\nBe aware that we log you out after 15 minutes of inactivity to help protect people using shared computers (at libraries, for instance).\n\n".$site_title." Administrator";
		$title = "Activate your account with the ".$site_title." Archive";
		$header = 'From: '.$from. "\n" . 'X-Mailer: PHP/' . phpversion();
		return mail($user->email, $title, $body, $header);
	}

	public function resetPasswordAction()
	{
		$hash = $this->_getParam('u');
		$ua = $this->getTable('UsersActivations')->findBySql("url = ?", array($hash), true);
		
		if(!$ua) {
			$this->errorAction();
			return;
		}
		
		if(!empty($_POST)) {
			if (strlen($_POST['new_password1']) >= 6) {	
				if($_POST['new_password1'] == $_POST['new_password2']) {
					$ua->User->password = $_POST['new_password1'];
					$ua->User->active = 1;
					$ua->User->save();
					$ua->delete();
					$this->_redirect(myomeka_get_path());				
				} else {
					$this->flash('Please enter the same passwords twice.');
				}
			} else {
				$this->flash('Please enter a password that has at least 6 characters.');
			}
		}
		$user = $ua->User;
		$this->render('reset-password', compact('user'));
	}
}
 
?>