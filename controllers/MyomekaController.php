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

require_once MODEL_DIR.'/User.php';
require_once PLUGIN_DIR."/MyOmeka/models/Poster.php";
require_once PLUGIN_DIR."/MyOmeka/models/Note.php";
require_once PLUGIN_DIR."/MyOmeka/models/MyomekaTag.php";
require_once 'Omeka/Controller/Action.php';

class MyOmeka_MyOmekaController extends Omeka_Controller_Action
{
		
	public function indexAction()
	{		
        $this->_forward('dashboard');
	}
	
	public function dashboardAction()
	{		
		if($current = Omeka::loggedIn()) {

		    // Get the user's existing posters
            $posters = new Poster();
            $posters = $posters->getUserPosters($current->id);
            
            // Get tagged and noted items
            $noteObj = new Note();
            $myomekatagObj = new MyomekaTag();
            $mixedItems = array_merge(
                                    $noteObj->getNotedItemsByUser($current->id),
                                    $myomekatagObj->getItemsTaggedByUser($current->id)
                                );
            
            // Loop through the items to make sure we only have one of each item
            $notedItems = array();
            foreach($mixedItems as $item){
                $notedItems[$item->id] = $item;
            }
            
            // Get the user's tags
            $tagTable = new TagTable(null);
            $options = array('entity'=>$current->entity_id);
            $tags = $tagTable->findBy($options,"MyomekaTag");
            
			$this->render('myomeka/dashboard.php', compact("posters","notedItems","tags"));
		} else {			
        	$this->_forward('login');			
		}
	}

	public function loginAction()
	{	
		$emailSent = false;
		$requireTermsOfService = get_option('myomeka_require_terms_of_service');
					
		if (!empty($_POST)) {
			
			require_once 'Zend/Session.php';

			$session = new Zend_Session_Namespace;
	
			$auth = $this->_auth;

			$adapter = new Omeka_Auth_Adapter($_POST['username'], $_POST['password']);
	
			$token = $auth->authenticate($adapter);

			if ($token->isValid()) {
				$this->_redirect(myomeka_get_path('dashboard/'));
			} else {		
			 	$this->flash('There was an error logging you in.  Please try again, or register a new account.');
			}
		}
		$this->render('myomeka/index.php', compact('emailSent', 'requireTermsOfService'));
	}
	
	public function logoutAction()
	{
		$auth = $this->_auth;
		//http://framework.zend.com/manual/en/zend.auth.html
		$auth->clearIdentity();
		$this->_redirect(myomeka_get_path());
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
		
		$this->render('myomeka/index.php', compact('emailSent', 'requireTermsOfService'));
	}	
	
	public function helpPageAction() {
	    $this->render('helpPage.php');
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

	public function activateAction()
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
		$this->render('myomeka/activate.php', compact('user'));
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
		$this->render('myomeka/resetPassword.php', compact('user'));
	}

	public function forgotAction()
	{
		//whether the forgot password email was sent or not
		$emailSent = false;
		
		//If the user's email address has been submitted, then make a new temp activation url and email it
		if(!empty($_POST)) {
			
			$email = $_POST['email'];
			$ua = new UsersActivations;
			
			$user = $this->getTable('User')->findByEmail($email);
			
			if($user) {
				//Create the reset password url
				
			try {	
				$ua->user_id = $user->id;
				$ua->save();
				
				$site_title = get_option('site_title');
				
				//Send the email with the reset password url
				$url = uri(get_option('myomeka_page_path') . 'resetPassword?u='.$ua->url);
				$body   = "You have requested to change you password for ".$site_title.". Your username is ".$user->username.". ";
				$body  .= "Please follow this link to reset your password:\n\n";
				$body  .= $url."\n\n";
				$body  .= "$site_title Administrator";		
				
				$admin_email = get_option('administrator_email');
				$title = "[$site_title] Reset Your Password";
				$header = 'From: '.$admin_email. "\n" . 'X-Mailer: PHP/' . phpversion();
				
				mail($email,$title, $body, $header);
				$this->flashSuccess('Your password has been emailed.');
				$emailSent = true;	
			} catch (Exception $e) {
				$this->flash('Your password has already been sent to your email address');
			}
			
			}else {
				//If that email address doesn't exist
				$this->flash('The email address you provided is invalid.');
			}			
		}
		
		return $this->render('myomeka/forgotPassword.php', compact('emailSent'));
	}

}
 
?>