<?php head(); ?>

<?php 
	$user = current_user();
	echo "<p>logged in as $user->username | <a href=\"" . uri('myarchive/logout/') . "\">Logout</a></p>";
?>

<h1>My Archive Dashboard</h1>

<h2>Your Favorite Items</h2>

<p>No items have been favorited yet!  Bummer dude.</p>
<?php //display_item_list($items); ?>

<h2>Your Posters</h2>

<h3><a href="<?php echo uri('poster/add/'); ?>">Create A Poster From Your Favorite Items</a></h3>

<?php foot(); ?>