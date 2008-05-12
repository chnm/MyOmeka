<?php head(); ?>

<?php 
	$user = current_user();
	echo "<p>logged in as $user->username | <a href=\"" . uri('myomeka/logout/') . "\">Logout</a></p>";
?>

<h1>My Archive Dashboard</h1>
<?php echo flash(); ?>
<h2>Your Favorite Items</h2>

<p>No items have been favorited yet!  Bummer dude.</p>
<?php //display_item_list($items); ?>

<h2>Your Posters</h2>
<?php if(count($posters) > 0): ?>
    <?php foreach($posters as $poster): ?>
        <ul id="poster-list">            
            <li>
                <?php echo $poster->title; ?><br/>
                <a href="<?php echo uri('poster/view/'.$poster->id); ?>">view</a> 
                <a href="<?php echo uri('poster/edit/'.$poster->id); ?>">edit</a> 
                <a href="<?php echo uri('poster/share/'.$poster->id); ?>">share</a>
                <a href="<?php echo uri('poster/delete/'.$poster->id); ?>">delete</a>
            </li>
        </ul>
    <?php endforeach; ?>
<?php else: ?>
    You haven't made any posters yet.
<?php endif; ?>

<h3><a href="<?php echo uri('poster/new'); ?>">Create a new poster from your favorite items</a></h3>

<?php foot(); ?>