<?php 
    head(); 
    echo js('dashboard');
	myomeka_breadcrumb();
?>

<div id="myomeka-primary">

<h2>My Omeka Dashboard</h2>
<?php echo flash(); ?>

<h4><a href="<?php echo uri('poster/new'); ?>">Create a new poster</a></h4>

<h3>Your Posters</h3>
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

<h3>Items with your notes</h3>
<?php if(count($notedItems) > 0): ?>
    <ul id="myomeka-notedItems-list">            
        <?php foreach($notedItems as $notedItem): ?>
            <li>
                <a href="<?php print uri("items/show/".$notedItem->item_id); ?>">
                    <?php if ($notedItem->title):?><?php print $notedItem->title; ?>
                    <?php else: ?>[untitled]
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>You have not added notes to any items yet.</p>
<?php endif; ?>

<h3>Items you've tagged</h3>
<?php if(count($tags) > 0): ?>
    <ul id="myomeka-tags-list">
        <?php foreach($tags as $tag): ?>
            <li>
                <a href="<?php ?>"><?php print $tag->name; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <!-- <p>You have not added notes to any items yet.</p> -->
    <!-- Start Sample tag list -->
    <ul id="myomeka-tags-list">
        <li><a href="#">Sample1</a></li>
        <li><a href="#">Sample2</a></li>
        <li><a href="#">Sample3</a></li>
        <li><a href="#">Sample4</a></li>
        <li><a href="#">Sample5</a></li>
    </ul>
    <!-- End Sample tag list -->
<?php endif; ?>

</div><!-- end dashboard -->

<?php foot(); ?>