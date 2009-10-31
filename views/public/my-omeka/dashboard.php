<?php
    $pageTitle = html_escape(get_option('my_omeka_page_title') . ': Dashboard');
    head(array('title'=>$pageTitle)); 
    echo js('dashboard'); 
?>

<div id="primary">
<div id="myomeka-dashboard">
	<h1><?php echo $pageTitle; ?></h1>
    <?php echo flash(); ?>
	    
	<div id="myomeka-posters">
		<h2>Your Posters</h2>
		<?php if (count($posters) > 0): ?>

	        <?php foreach($posters as $poster): ?>           
				<div class="myomeka-poster">
					<h3 class="myomeka-poster-title">
					    <a href="<?php echo html_escape(uri(array('action'=>'show','id'=>$poster->id), 'myOmekaPosterActionId')); ?>" class="myomeka-view-poster-link"><?php echo html_escape($poster->title); ?></a>	               		
					</h3>
					<ul class="myomeka-poster-meta">
						<li class="myomeka-poster-date"><?php echo html_escape($poster->date_created); ?></li>	
						<li class="myomeka-poster-description"><?php echo html_escape(snippet($poster->description,0,250)); ?></li>
					</ul>
					<ul class="myomeka-poster-nav">
	               		<li><a href="<?php echo html_escape(uri(array('action'=>'edit','id'=>$poster->id), 'myOmekaPosterActionId')); ?>" class="myomeka-edit-poster-link">edit</a> </li>
	               		<li><a href="<?php echo html_escape(uri(array('action'=>'share','id'=>$poster->id), 'myOmekaPosterActionId')); ?>" class="myomeka-share-poster-link">share</a></li>
	               		<li><a href="<?php echo html_escape(uri(array('action'=>'delete','id'=>$poster->id), 'myOmekaPosterActionId')); ?>" class="myomeka-delete-poster-link">delete</a></li>
					</ul>
				</div>
	      	<?php endforeach; ?>

	
		<?php else: ?>
			<p>You haven't created any posters yet.</p>
		<?php endif; ?>
		
		<div id="myomeka-create-poster">
    	    <form action="<?php echo html_escape(uri(array('action'=>'new'), 'myOmekaPosterAction'));?>" method="post">
    	        <input type="submit" name="myomeka_create_poster" value="Create a Poster &rarr;" />
    	    </form>
    	</div>
		
	</div>

	<div id="myomeka-noted-items">
	<h2>Your Items</h2>
	<?php if (has_items_for_loop()): ?>

		<?php while (loop_items()): ?>
		<div class="myomeka-noted-items-list">
	    	<ul>            
				<li class="myomeka-item">
				<h3 class="myomeka-poster-title"><?php echo link_to_item(); ?></h3>
				<?php
				    if (item_has_thumbnail()):
				        echo link_to_item(item_thumbnail(array('class'=>'myomeka-item-thumbnail')));
				    endif;	            
                ?>
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
	<h2>Your Tags</h2>
	<?php if (count($tags)): ?>
	    <ul class="hTagcloud" id="myomeka-tags">
	        <?php foreach ($tags as $tag): ?>
	           <li><a href="<?php echo html_escape(uri(array('myTag'=>$tag->id, 'controller'=>'items', 'action'=>'browse'), 'default')); ?>"><?php echo html_escape($tag['name']); ?></a></li>
	        <?php endforeach; ?>
	    </ul>
	<?php else: ?>
	    <p>You have not tagged any items yet.</p>
	<?php endif; ?>
	</div>

</div>

</div>
<?php foot(); ?>