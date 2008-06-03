<?php 
    head(); 
    echo js('dashboard');
	myomeka_breadcrumb();
?>

<div id="myomeka-primary">

<h2>My Omeka Dashboard</h2>
<?php echo flash(); ?>


<div id="myomeka-posters">

<h3>Your Posters</h3>
<?php if(count($posters) > 0): ?>
    <ul id="myomeka-poster-list"> 
        <?php foreach($posters as $poster): ?>           

			<li class="poster-title"><?php echo $poster->title; ?>
				<ul class="myomeka-poster-nav">
                <li><a href="<?php echo uri('poster/view/'.$poster->id); ?>" class="myomeka-view-poster-link">view</a> </li>
                <li><a href="<?php echo uri('poster/edit/'.$poster->id); ?>" class="myomeka-edit-poster-link">edit</a> </li>
                <li><a href="<?php echo uri('poster/share/'.$poster->id); ?>" class="myomeka-share-poster-link">share</a></li>
                <li><a href="<?php echo uri('poster/delete/'.$poster->id); ?>" class="myomeka-delete-poster-link">delete</a></li>
				</ul>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>You haven't made any posters yet.</p>
<?php endif; ?>

</div>

<div id="create-poster">
<h4><a href="<?php echo uri('poster/new'); ?>">Create a new poster &rarr;</a></h4>
</div>

<div id="myomeka-notedItems">
<h3>Items with your notes</h3>
<?php if(count($notedItems) > 0): ?>

	<?php foreach($notedItems as $notedItem): ?>
	<div class="myomeka-notedItems-list-box">
    <ul id="myomeka-notedItems-list">            

			<li><?php echo square_thumbnail($notedItem)?></li>
            <li>
                <a href="<?php print uri("items/show/".$notedItem->item_id); ?>">
                    <?php if ($notedItem->title):?><?php print $notedItem->title; ?>
                    <?php else: ?>[untitled]
                    <?php endif; ?>
                </a>
            </li>
	</ul>
	</div>


        <?php endforeach; ?>

<?php else: ?>
    <p>You have not added notes to any items yet.</p>
<?php endif; ?>

</div >

<div id="myomeka-tags">
<h3>Items you've tagged</h3>
<?php if(count($tags) > 0): ?>
    <ul class="hTagcloud" id="myomeka-tags-list">
        <?php foreach($tags as $tag): ?>
            <li>
                <a href="<?php ?>"><?php print $tag->name; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <!-- <p>You have not added notes to any items yet.</p> -->
    <!-- Start Sample tag list -->
    <ul class="hTagcloud" id="myomeka-tags-list">
        <li><a href="#">Sample1</a></li>
        <li><a href="#">Sample2</a></li>
        <li><a href="#">Sample3</a></li>
        <li><a href="#">Sample4</a></li>
        <li><a href="#">Sample5</a></li>
    </ul>
    <!-- End Sample tag list -->
<?php endif; ?>
</div>

</div><!-- end myomeka-primary -->

<?php foot(); ?>