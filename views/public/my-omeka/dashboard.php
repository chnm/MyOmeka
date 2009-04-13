<?php 
    head(); 
    echo js('dashboard'); ?>

<div id="primary">
    
<div id="myomeka-dashboard">

	<h1><?php echo get_option('my_omeka_page_title'); ?>: Dashboard</h1>
	
	<?php echo flash(); ?>


	<div id="myomeka-posters">

		<h2>Your Posters</h2>
		<?php if(count($posters) > 0): ?>

	        <?php foreach($posters as $poster): ?>           
				<div class="myomeka-poster">
					<h3 class="poster-title"><?php echo htmlspecialchars($poster->title); ?></h3>
					<ul class="myomeka-poster-meta">
						<li class="myomeka-poster-date"><?php echo $poster->date_created; ?></li>	
						<li class="myomeka-poster-description"><?php echo snippet($poster->description,0,250); ?></li>
					</ul>
					<ul class="myomeka-poster-nav">
					
	               		<li><a href="<?php echo uri(array('action'=>'show','id'=>$poster->id), 'myOmekaPosterActionId'); ?>" class="myomeka-view-poster-link">view</a> </li>
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

	<div id="myomeka-create-poster"><a href="<?php echo uri(array('action'=>'new'), 'myOmekaPosterAction'); ?>">Create a new poster &rarr;</a>
	</div>

	<div id="myomeka-noted-items">
	<h3>Items with your notes and tags</h3>
	<?php if (has_items_for_loop()): ?>

		<?php while (loop_items()): ?>
		<div class="myomeka-noted-items-list">
	    	<ul>            
				<li>
				<?php 
				    if (item_has_thumbnail()):
				        echo link_to_item(item_thumbnail());
				        echo '<br />';
				    endif;	            
                    echo link_to_item(); 
                ?>
                <br/>
                <?php echo item('Dublin Core', 'Description', array('snippet'=>200));?>
                </li>
			</ul>
		</div>
		<?php endwhile; ?>
		<?php else: ?>
		   <p>You have not added notes to any items yet.</p>
		<?php endif; ?>
	</div>
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

</div>

</div>
<?php foot(); ?>