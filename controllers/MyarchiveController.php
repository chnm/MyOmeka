<?php 
/**
* MyArchive controller
*/

/*require_once MODEL_DIR.DIRECTORY_SEPARATOR.'User.php';
require_once 'Omeka/Controller/Action.php';
require_once 'Zend/Filter/Input.php';
*/

class MyArchiveController extends Omeka_Controller_Action
{
		
	public function indexAction()
	{		
        $this->_forward('dashboard');
	}
	
	public function dashboardAction()
	{
		if($current = Omeka::loggedIn()) {		
			$this->render('myarchive/dashboard.php');
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
				$this->_redirect('myarchive/dashboard/');
			} else {		
// should throw an exception and not echo error.  I had issues with this, even when trying to flash() the exception on the public-side.  revisit before releasing plugin [DL]
			 	echo ('There was an error logging you in.  Please try again, or register a new account.');
			}
		}
		$this->render('myarchive/login.php');
	}
	
	public function logoutAction()
	{
		$auth = $this->_auth;
		//http://framework.zend.com/manual/en/zend.auth.html
		$auth->clearIdentity();
		$this->_redirect('myarchive');
	}
	
	public function forgotAction()
	{
//  fix this [DL]
//		$this->_forward('users', 'forgotPassword', array('renderPage'=>'myarchive/forgot.php'));
	}
	
	/**
	 * Register thyself for a user account with Omeka
	 *
	 * @return void
	 **/
	public function registerAction()
	{
		$user = new User();
		
		try {
			if($user->saveForm($_POST)) {
				
				$user->email = $_POST['email'];
				$this->sendActivationEmail($user);
				
				$this->flashSuccess('User was added successfully!');

				//Redirect to the main user browse page
				$this->_redirect('dashboard');
			}
		} catch (Omeka_Validator_Exception $e) {
			//$this->flashValidationErrors($e);
			echo "error!";
		}
			
//		return $this->_forward('myarchive', 'dashboard');	

//	echo "test!";
	}

}
 
?>
