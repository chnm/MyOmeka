<?php head(); ?>

<a href="<?php uri('myarchive/logout'); ?>">Logout</a>
<h1>My Archive</h1>

<?php 
	$user = current_user();
	
	echo $user->username;
?>

<h2>Your Items</h2>

<?php display_item_list($items); ?>

<?php foot(); ?>