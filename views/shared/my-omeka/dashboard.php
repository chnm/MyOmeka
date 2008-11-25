<?php 
    head(); 
    echo js('dashboard'); ?>

<div id="primary">
    
<div id="myomeka-primary">

	<h2>My Omeka Dashboard</h2>
	
	<?php echo flash(); ?>


	<div id="myomeka-posters">

		<h3>Your Posters</h3>
		<?php if(count($posters) > 0): ?>

	        <?php foreach($posters as $poster): ?>           
				<div>
					<h4 class="poster-title"><?php echo $poster->title; ?></h4>
					<ul class="myomeka-poster-meta">
						<li class="poster-date"><?php echo $poster->date_created; ?></li>	
						<li class="post-description"><?php echo snippet($poster->description,0,250); ?></li>
					</ul>
					<ul class="myomeka-poster-nav">
					
	               		<li><a href="<?php echo uri(array('action'=>'view','id'=>$poster->id), 'myOmekaPosterActionId'); ?>" class="myomeka-view-poster-link">view</a> </li>
	               		<li><a href="<?php echo uri(array('action'=>'edit','id'=>$poster->id), 'myOmekaPosterActionId'); ?>" class="myomeka-edit-poster-link">edit</a> </li>
	               		<li><a href="<?php echo uri(array('action'=>'share','id'=>$poster->id), 'myOmekaPosterActionId'); ?>" class="myomeka-share-poster-link">share</a></li>
	               		<li><a href="<?php echo uri(array('action'=>'delete','id'=>$poster->id), 'myOmekaPosterActionId'); ?>" class="myomeka-delete-poster-link">delete</a></li>
					</ul>
				</div>
	      	<?php endforeach; ?>

	
		<?php else: ?>
			<p>You haven't made any posters yet.</p>
		<?php endif; ?>
	</div>

	<div id="create-poster">
	<h4><a href="<?php echo uri(array('action'=>'new'), 'myOmekaPosterAction'); ?>">Create a new poster &rarr;</a></h4>
	</div>

	<div id="myomeka-notedItems">
	<h3>Items with your notes and tags</h3>
	<?php if (has_items_for_loop()): ?>

		<?php while (loop_items()): ?>
		<div class="myomeka-notedItems-list-box">
	    	<ul id="myomeka-notedItems-list">            
				<li><?php echo link_to_item(item_thumbnail()); ?></li>	            
                <li><?php echo link_to_item(); ?></li>
				<li><?php echo item('Dublin Core', 'Description', array('snippet'=>200));?>
			</ul>
		</div>
		<?php endwhile; ?>

		<?php else: ?>
		   <p>You have not added notes to any items yet.</p>
		<?php endif; ?>
	</div >

	<div id="myomeka-tags">
	<h3>Your Tags</h3>
	<?php if(count($tags)): ?>
	    <ul class="hTagcloud" id="myomeka-tags-list">
	        <?php foreach ($tags as $tag): ?>
	           <li><a href="<?php echo uri(array('myTag'=>$tag->id, 'controller'=>'items', 'action'=>'browse'), 'default'); ?>"><?php echo htmlspecialchars($tag['name']); ?></a></li>
	        <?php endforeach; ?>
	    </ul>
	<?php else: ?>
	    <p>You have not Tagged any items yet.</p>
	<?php endif; ?>
	</div>

</div><!-- end myomeka-primary -->

</div>
<?php foot(); ?>