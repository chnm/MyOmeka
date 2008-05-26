<?php 
    head(); 
    echo js('dashboard');
	myomeka_userloggedin_status();
	myomeka_breadcrumb();
?>

<h1>My Archive Dashboard</h1>
<?php echo flash(); ?>

<h2>Your Favorite Items</h2>
<?php if(count($favorites) > 0): ?>
    <ul id="myomeka-favorite-list">            
        <?php foreach($favorites as $favorite): ?>
            <li><a href="<?php echo WEB_DIR.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.'show'.DIRECTORY_SEPARATOR.$favorite->item_id; ?>"><?php if ($favorite->title) { echo $favorite->title; } else { echo "[untitled]"; }?></a></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>You haven't favorited and items yet.</p>
<?php endif; ?>

<h2>Your Posters</h2>
<?php if(count($posters) > 0): ?>
    <ul id="myomeka-poster-list"> 
        <?php foreach($posters as $poster): ?>           
            <li>
                <?php echo $poster->title; ?><br/>
                <a href="<?php echo uri('poster/view/'.$poster->id); ?>">view</a> 
                <a href="<?php echo uri('poster/edit/'.$poster->id); ?>">edit</a> 
                <a href="<?php echo uri('poster/share/'.$poster->id); ?>">share</a>
                <a href="<?php echo uri('poster/delete/'.$poster->id); ?>" class="myomeka-delete-poster-link">delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>You haven't made any posters yet.</p>
<?php endif; ?>

<h3><a href="<?php echo uri('poster/new'); ?>">Create a new poster from your favorite items</a></h3>

<?php foot(); ?>