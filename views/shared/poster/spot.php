<?php set_current_item($posterItem); ?>
<div class="myomeka-poster-spot">

		        <h3 class="myomeka-item-title"><?php echo item('Dublin Core', 'Title'); ?></h3>
    <div class="myomeka-controls">
        <a href="#" class="myomeka-move-top myomeka-poster-control">
            <img src="<?php echo img('arrow_up_up.png'); ?>"  title="Move to the top" alt="Move to the top"/></a>
        <a href="#" class="myomeka-move-up myomeka-poster-control">
            <img src="<?php echo img('arrow_up.png'); ?>"  title="Move up" alt="Move up"/></a>
        <a href="#" class="myomeka-move-down myomeka-poster-control">
            <img src="<?php echo img('arrow_down.png'); ?>"  title="Move down" alt="Move down"/></a>
        <a href="#" class="myomeka-move-bottom myomeka-poster-control">
            <img src="<?php echo img('arrow_down_down.png'); ?>"  title="Move to the bottom" alt="Move to the bottom" /></a>

    </div>

    <div class="myomeka-item-thumbnail">
        <?php echo poster_icon_html(); ?>
    </div>

    <div class="myomeka-annotation">
        <?php echo __v()->formTextarea('annotation-' . $posterItem->ordernum, $posterItem->annotation,
            array(  'id'=>'myomeka-annotation-' . mt_rand(0, 999999999),
                    'rows'=>'6',
                    'cols'=>'10')); ?>
    </div>
    <?php if ($noteText): ?>
        <div class="myomeka-notes">
            <h3>My Notes</h3>
            <?php echo nls2p(htmlspecialchars($noteText)); ?>
        </div>
    <?php endif; ?>

	<div class="myomeka-controls-delete">
		<a href="#" class="myomeka-delete myomeka-poster-control">
            <img src="<?php echo img('delete.gif'); ?>" title="Remove this item" alt="Remove this item"/>Delete</a>
	</div>
    <input type="hidden" name="itemID-<?php echo $posterItem->ordernum; ?>" value="<?php echo $posterItem->id; ?>" class="myomeka-hidden-item-id" />
</div>