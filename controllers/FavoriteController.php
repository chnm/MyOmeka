<?php
/**
*  Favorite Controller
*  probably silly to separate this from MyOmeka, but in the long-term
*  it may make sense.  Maybe not.  [DL]
*
**/

require_once "plugins/MyOmeka/models/Favorite.php";
require_once "Omeka/Controller/Action.php";

class FavoriteController extends Omeka_Controller_Action
{
	public function indexAction()
	{
		echo "something";
	}
	
	public function addAction()
	{
	if ($item_id = ($_GET['item_id'])) {
		if($current = Omeka::loggedIn()) {	
			
			$annotation = $_GET['annotation'];
			
			$favorite = new Favorite();
			
			$favorite->user_id = current_user()->id;
			$favorite->annotation = $annotation;
			$favorite->item_id = $item_id;
			$favorite->save();
			
		$this->render('favorite/_favorite_saved.php');
		
		} else {
			echo "not logged in";
		}
	} else {
		echo "item_id is necessary";
	}
	}
	
	public function editAction()
	{
		
	}
	
	public function deleteAction()
	{
		
	}
}
?>