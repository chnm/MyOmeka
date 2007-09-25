<?php 
/**
* MyArchive controller
*/
class MyArchiveController extends Kea_Controller_Action
{
		
	/**
	 * Duplication of the Omeka login mechanism
	 * Warning: this will go out of date when we upgrade the Zend Framework
	 *
	 * @return void
	 **/
	public function loginAction()
	{
		if(!empty($_POST)) {
			//Authenticate the login and redirect to the myarchive page
			$auth = Zend::Registry( 'auth' );
			
			$options = array('username' => $_POST['username'],
							 'password' => $_POST['password']);

			$token = $auth->authenticate($options);
			
			if($token->isValid()) {
				$this->_redirect('myarchive/');
			}else {
				$this->flash($token->getMessage());
			}
		}
		//Otherwise render the login page
		
		$this->render('myarchive/login.php');
	}
	
	public function logoutAction()
	{
		$auth = Zend::Registry( 'auth' );
		$auth->logout();
		
		$this->_redirect('/');
	}
	
	public function forgotAction()
	{
		$this->_forward('users', 'forgotPassword', array('renderPage'=>'myarchive/forgot.php'));
	}
	
	/**
	 * Register thyself for a user account with Omeka
	 *
	 * @return void
	 **/
	public function registerAction()
	{
		
	}
	
	/**
	 * Main page for the MyArchive section
	 *
	 * @return void
	 **/
	public function indexAction()
	{
		//If the user is not logged in, redirect to the login page 
		if( !($user = Kea::loggedIn()) ) {
			$this->_redirect('myarchive/login');
		}
		
		$num_items_to_show = 3;
		
		//Only display public items that are related to the user
		$params = array('public'=>true, 'user'=>$user, 'per_page'=>$num_items_to_show, 'added_by_me'=>true);
		
		$items = Doctrine_Manager::getInstance()->getTable('Item')->findBy($params);
		
		return $this->render('myarchive/index.php', compact('items'));
	}
}
 
?>
