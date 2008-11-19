<?php 
    head();
    echo js('tiny_mce/tiny_mce'); 
    echo js('ibox/ibox');
    echo js('poster');

	my_omeka_breadcrumb();

?>
<script type="text/javascript" charset="utf-8">
    // Make the items-widget div a modal pop-up
    iBox.setPath('<?php echo WEB_ROOT; ?>/plugins/MyOmeka/views/shared/javascripts/ibox/');
    // Set the initial Item Count
    Poster.itemCount = <?php echo count($posterItems); ?>;
</script>
<div id="myomeka-primary">
	<div id="myomeka-poster-info">
	<h2>Edit Your Poster</h2>
	<a href="<?php echo my_omeka_get_path('help'); ?>" class="myomeka-help-link">Help</a>
    <form action="<?php echo my_omeka_get_path('poster/save/'.$poster->id); ?>" method="post" accept-charset="utf-8" id="myomeka-poster-form">
        <div class="myomeka-field">
            <label for="myomeka-title">Title of Poster:</label>
            <input type="text" name="title" value="<?php echo $poster->title;?>" id="myomeka-title" />
        </div>
    
        <div class="myomeka-field">
            <label for="myomeka-description">Description:</label>
            <textarea name="description" id="myomeka-description" rows="8" cols="20">
                <?php echo $poster->description;?>
            </textarea>
        </div>
    
        <h2>Poster Items</h2>
        <div id="myomeka-poster-additem">
            <?php if(count($items)): ?>
                <button type="button">Add an item &rarr;</button>
            <?php else: ?>
                <button type="button" disabled="disabled">Add an item &rarr;</button>
                <p>You have to add notes or tags to an item before adding them to a poster</p>
            <?php endif; ?>
        </div>
        <div id="myomeka-poster-canvas">
        <?php
            if(count($posterItems)){
                foreach($posterItems as $posterItem){
                    common('_spot', array('posterItem'=>$posterItem));
                }   
            }
        ?>
        </div>
        <div id="myomeka-submit-poster">
            <input type="submit" name="save_poster" value="Save Poster" /> or 
            <?php if (isset($_GET['return']) && $_GET['return'] == "admin"): ?>
                <a href="<?php echo my_omeka_get_path('poster/adminPosters'); ?>">Discard changes and return to poster administration</a>
            <?php else: ?>
                <a href="<?php echo my_omeka_get_path('dashboard'); ?>">Discard changes and return to the dashboard</a>
            <?php endif ?>
            <input type="hidden" name="itemCount" value="<?php echo count($posterItems);?>" id="myomeka-itemCount"/>
        </div>
    </form>
    
    <!-- Hidden div for modal pop-up -->
    <div id="myomeka-additem-modal" style="display:none;">
        <h2>Choose an item</h2>
        <?php if(count($items)):?>
            <?php foreach($items as $item):?>
                <div class="myomeka-additem-item">
                    <div class="myomeka-additem-image">
                        <?php echo poster_icon_html($item); ?>
                        <form action="<?php echo my_omeka_get_path('poster/addPosterItem'); ?>" method="post" accept-charset="utf-8" class="myomeka-additem-form">
                        	<input type="submit" name="submit" value="Add this item" class="myomeka-additem-submit"/>
                        	<input type="hidden" name="item-id" value="<?php echo $item->id; ?>" class="myomeka-additem-item-id"/>
                    	</form>
                    </div>
                    <div class="myomeka-additem-details">
                        <dl>
                            <?php if($item->title): ?>
                                <dt>Title:</dt>
                                <dd><?php echo $item->title;?></dd>
                            <?php endif ?>
                            <?php if($item->description): ?>
                                <dt>Description:</dt>
                                <dd><?php echo $item->description;?></dd>
                            <?php endif ?>
                            <?php if($item->creator): ?>
                                <dt>creator:</dt>
                                <dd><?php echo $item->creator;?></dd>
                            <?php endif ?>
                            <?php if($item->annotation): ?>
                                <dt>My Notes:</dt>
                                <dd><?php echo $item->annotation;?></dd>
                            <?php endif ?>
                        </dl>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have you favorite items before you can add those items to a poster</p>
        <?php endif; ?>
    </div>
</div>
<?php foot(); ?>