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

require_once MODEL_DIR.DIRECTORY_SEPARATOR.'User.php';
require_once PLUGIN_DIR.DIRECTORY_SEPARATOR."MyOmeka".DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."Poster.php";
require_once PLUGIN_DIR.DIRECTORY_SEPARATOR."MyOmeka".DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."Note.php";
require_once 'Omeka/Controller/Action.php';

class MyOmekaController extends Omeka_Controller_Action
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
            
            // Get the user's noted items
            $noteObj = new Note();
            $notedItems = $noteObj->getNotedItemsByUser($current->id);
            
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
				
		if (!empty($_POST)) {
			
			require_once 'Zend/Session.php';

			$session = new Zend_Session_Namespace;
	
			$auth = $this->_auth;

			$adapter = new Omeka_Auth_Adapter($_POST['username'], $_POST['password']);
	
			$token = $auth->authenticate($adapter);

			if ($token->isValid()) {
				$this->_redirect('/myomeka/dashboard/');
			} else {		
// should throw an exception and not echo error.  I had issues with this, even when trying to flash() the exception on the public-side.  revisit before releasing plugin [DL]
			 	$this->flash('There was an error logging you in.  Please try again, or register a new account.');
			}
		}
		$this->render('myomeka/index.php');
	}
	
	public function logoutAction()
	{
		$auth = $this->_auth;
		//http://framework.zend.com/manual/en/zend.auth.html
		$auth->clearIdentity();
		$this->_redirect('/myomeka');
	}
	
	/**
	 * Register thyself for a user account with Omeka
	 *
	 * @return void
	 **/
	public function registerAction()
	{
	$user = new User();

	$user->role = "MyOmeka";
	
		try {
			if($user->saveForm($_POST)) {

				$user->email = $_POST['email'];
				
				$this->sendActivationEmail($user);
				
				$this->flashSuccess('User was added successfully!');

				//Redirect to the main user browse page
				$this->_redirect('/myomeka/dashboard');
			}
		} catch (Omeka_Validator_Exception $e) {
			$this->flashValidationErrors($e);
			$this->render('myomeka/index.php');
		}
			
//		return $this->_forward('myomeka', 'dashboard');
	
	}

	public function sendActivationEmail($user)
	{
		$ua = new UsersActivations;
		$ua->user_id = $user->id;
		$ua->save();
		
		//send the user an email telling them about their great new user account
				
		$site_title = get_option('site_title');
		$from = get_option('administrator_email');
		
		$body = "Welcome!\n\nYour account for the ".$site_title." archive has been created. Please click the following link to activate your account:\n\n"
		.WEB_ROOT."/myomeka/activate?u={$ua->url}\n\n (or use any other page on the site).\n\nBe aware that we log you out after 15 minutes of inactivity to help protect people using shared computers (at libraries, for instance).\n\n".$site_title." Administrator";
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
			if($_POST['new_password1'] == $_POST['new_password2']) {
				$ua->User->password = $_POST['new_password1'];
				$ua->User->active = 1;
				$ua->User->save();
				$ua->delete();
				$this->_redirect('/myomeka');				
			}
		}
		$user = $ua->User;
		$this->render('myomeka/activate.php', compact('user'));
	}

	public function forgotAction()
	{
		
		//If the user's email address has been submitted, then make a new temp activation url and email it
		if(!empty($_POST)) {
			
			$email = $_POST['email'];
			$ua = new UsersActivations;
			
			$user = $this->getTable('User')->findByEmail($email);
			
			if($user) {
				//Create the activation url
				
			try {	
				$ua->user_id = $user->id;
				$ua->save();
				
				$site_title = get_option('site_title');
				
				//Send the email with the activation url
				$url = WEB_ROOT.'/myomeka/activate?u='.$ua->url;
				$body 	= "Please follow this link to reset your password:\n\n";
				$body  .= $url."\n\n";
				$body  .= "$site_title Administrator";		
				
				$admin_email = get_option('administrator_email');
				$title = "[$site_title] Reset Your Password";
				$header = 'From: '.$admin_email. "\n" . 'X-Mailer: PHP/' . phpversion();
				
				mail($email,$title, $body, $header);
				$this->flash('Your password has been emailed');	
			} catch (Exception $e) {
				  $this->flash('your password has already been sent to your email address');
			}
			
			}else {
				//If that email address doesn't exist
				
				$this->flash('The email address you provided is invalid.');
			}			

		}
		
		return $this->render('myomeka/forgotPassword.php');
	}

}
 
?>