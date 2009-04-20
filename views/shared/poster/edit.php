<?php 
    head();
    echo js('tiny_mce/tiny_mce'); 
    echo js('ibox/ibox');
    echo js('poster');
?>
<script type="text/javascript" charset="utf-8">
    // Make the items-widget div a modal pop-up
    iBox.setPath('<?php echo WEB_ROOT; ?>/plugins/MyOmeka/views/shared/javascripts/ibox/');
    // Set the initial Item Count
    Poster.itemCount = <?php echo count($poster->Items); ?>;
</script>
<div id="primary">
<div id="myomeka-poster">
	<div id="myomeka-poster-info">
	<h2>Edit Your Poster</h2>
	<a href="<?php echo uri(array('action'=>'help'), 'myOmekaAction'); ?>" class="myomeka-help-link">Help</a>
    <form action="<?php echo uri(array('action'=>'save', 'id'=>$poster->id), 'myOmekaPosterActionId'); ?>" method="post" accept-charset="utf-8" id="myomeka-poster-form">
        <div class="myomeka-field">
            <label for="myomeka-title">Title of Poster:</label>
            <?php echo $this->formText('title', $poster->title, array('id'=>'myomeka-title')); ?>
        </div>
    
        <div class="myomeka-field">
            <label for="myomeka-description">Description:</label>
            <?php echo $this->formTextarea('description', $poster->description, 
            array('id'=>'myomeka-description', 'rows'=>'8', 'cols'=>'20')); ?>
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
            if(count($poster->Items)){
                foreach($poster->Items as $posterItem){
                    $noteObj = my_omeka_get_note_for_item($posterItem);
                    common('spot', array('posterItem'=>$posterItem, 'noteText'=>$noteObj->note), 'poster');
                }   
            }
        ?>
        </div>
        <div id="myomeka-submit-poster">
            <input type="submit" name="save_poster" value="Save Poster" /> or 
            <?php if (is_admin_theme()): ?>
                <a href="<?php echo uri(array('action'=>'discard'), 'myOmekaPosterAction'); ?>">Discard changes and return to poster administration</a>
            <?php else: ?>
                <a href="<?php echo uri(array('action'=> 'discard'), 'myOmekaPosterAction'); ?>">Discard changes and return to the dashboard</a>
            <?php endif ?>
            <input type="hidden" name="itemCount" value="<?php echo count($poster->Items);?>" id="myomeka-itemCount"/>
        </div>
    </form>
    
    <!-- Hidden div for modal pop-up -->
    <div id="myomeka-additem-modal" style="display:none;">
        <?php if(count($items)):?>
            <?php while ($item = loop_items()):?>
                <div class="myomeka-additem-item">
                    <div class="myomeka-additem-image">
                        <?php echo poster_icon_html(); ?>

                    </div>
                    <div class="myomeka-additem-details">
                        <dl>
                                <dt>Title:</dt>
                                <dd><?php echo item('Dublin Core', 'Title');?></dd>
                                <dt>Description:</dt>
                                <dd><?php echo item('Dublin Core', 'Description');?></dd>
                                <dt>Creator:</dt>
                                <dd><?php echo item('Dublin Core', 'Creator');?></dd>
                            <?php if($item->annotation): ?>
                                <dt>My Notes:</dt>
                                <dd><?php echo $item->annotation;?></dd>
                            <?php endif ?>
                        </dl>
                    </div>
<br />
                    <form action="<?php echo uri(array('action'=>'add-poster-item'), 'myOmekaPosterAction'); ?>" method="post" accept-charset="utf-8" class="myomeka-additem-form">
                    	<input type="submit" name="submit" value="Add this item" class="myomeka-additem-submit"/>
                    	<input type="hidden" name="item-id" value="<?php echo $item->id; ?>" class="myomeka-additem-item-id"/>
                	</form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You have you favorite items before you can add those items to a poster</p>
        <?php endif; ?>
    </div>
</div>
</div>
<?php foot(); ?>