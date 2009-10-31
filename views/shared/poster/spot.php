<?php set_current_item($posterItem); ?>
<div class="myomeka-poster-spot">

	<div class="myomeka-item-header">
    	<h3 class="myomeka-item-title"><?php echo item('Dublin Core', 'Title'); ?></h3>
    	<div class="myomeka-controls">
            <a href="#" class="myomeka-move-top myomeka-poster-control">
                <img src="<?php echo html_escape(img('arrow_up_up.png')); ?>"  title="Move to the top" alt="Move to the top"/></a>
            <a href="#" class="myomeka-move-up myomeka-poster-control">
                <img src="<?php echo html_escape(img('arrow_up.png')); ?>"  title="Move up" alt="Move up"/></a>
            <a href="#" class="myomeka-move-down myomeka-poster-control">
                <img src="<?php echo html_escape(img('arrow_down.png')); ?>"  title="Move down" alt="Move down"/></a>
            <a href="#" class="myomeka-move-bottom myomeka-poster-control">
                <img src="<?php echo html_escape(img('arrow_down_down.png')); ?>"  title="Move to the bottom" alt="Move to the bottom" /></a>
        	<a href="#" class="myomeka-delete myomeka-poster-control">
        		    <img src="<?php echo html_escape(img('delete.gif')); ?>" title="Remove this item" alt="Remove this item"/>Delete</a>
        </div>    	
    </div>
    
    <div class="myomeka-item-thumbnail">
        <?php echo my_omeka_poster_icon_html(); ?>
    </div>

    <div class="myomeka-item-annotation">
        <h4>My Annotation:</h4>
        <?php echo __v()->formTextarea('annotation-' . $posterItem->ordernum, $posterItem->annotation,
            array(  'id'=>'myomeka-annotation-' . mt_rand(0, 999999999),
                    'rows'=>'6',
                    'cols'=>'10')); ?>
    </div>
    <?php if ($noteText): ?>
        <div class="myomeka-notes">
            <h4>My Notes</h4>
            <?php echo nls2p(html_escape($noteText)); ?>
        </div>
    <?php endif; ?>
    
    <input type="hidden" name="itemID-<?php echo html_escape($posterItem->ordernum); ?>" value="<?php echo html_escape($posterItem->id); ?>" class="myomeka-hidden-item-id" />
</div>