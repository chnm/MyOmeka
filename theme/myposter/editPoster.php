<?php 
    head();
    echo js('tiny_mce/tiny_mce'); 
    echo js('ibox/ibox');
    echo js('poster');

	myomeka_userloggedin_status();
	myomeka_breadcrumb();

?>
<script type="text/javascript" charset="utf-8">
    // Make the items-widget div a modal pop-up
    iBox.setPath('<?php echo WEB_ROOT; ?>/plugins/MyOmeka/theme/javascripts/ibox/');
    // Set the initial Item Count
    Poster.itemCount = <?php echo count($posterItems); ?>;
</script>
<div id="myomeka-primary">
    <form action="<?php echo uri('poster/save/'.$poster->id); ?>" method="post" accept-charset="utf-8" id="myomeka-poster-form">
        <div class="myomeka-field">
            <label for="myomeka-title">Title of Poster:</label>
            <input type="text" name="title" value="<?php echo $poster->title;?>" id="myomeka-title" />
        </div>
    
        <div class="myomeka-field">
            <label for="myomeka-description">Description:</label>
            <textarea name="description" id="myomeka-description" rows="8" cols="20"><?php echo $poster->description;?></textarea>
        </div>
    
        <h2>Poster Items</h2>
        <div>
            <a href="#myomeka-item-widget" rel="ibox&amp;width=850" title="Chose on of your favorites to add">Add an item</a>
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
            <a href="<?php echo uri('myomeka/dashboard'); ?>">Discard changes and return to the dashboard</a>
            <input type="hidden" name="itemCount" value="<?php echo count($posterItems);?>" id="myomeka-itemCount"/>
        </div>
    </form>
    <?php common('_item_widget', compact('items')); ?>
</div>
<?php foot(); ?>