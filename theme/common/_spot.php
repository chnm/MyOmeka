<div class="myomeka-poster-spot">
    <div class="myomeka-controls">
        <a href="#" class="myomeka-move-top myomeka-poster-control">
            <img src="<?php echo img('arrow_up_up.png'); ?>"  title="Move to the top" alt="Move to the top"/></a>
        <a href="#" class="myomeka-move-up myomeka-poster-control">
            <img src="<?php echo img('arrow_up.png'); ?>"  title="Move up" alt="Move up"/></a>
        <a href="#" class="myomeka-move-down myomeka-poster-control">
            <img src="<?php echo img('arrow_down.png'); ?>"  title="Move down" alt="Move down"/></a>
        <a href="#" class="myomeka-move-bottom myomeka-poster-control">
            <img src="<?php echo img('arrow_down_down.png'); ?>"  title="Move to the bottm" alt="Move to the bottm"/></a>
        <a href="#" class="myomeka-delete myomeka-poster-control">
            <img src="<?php echo img('delete.gif'); ?>" title="Remove this item" alt="Remove this item"/></a>
    </div>

    <div class="myomeka-item-thumbnail">
        <?php echo poster_icon_html($posterItem); ?>
    </div>

    <div class="myomeka-annotation">
        <h2><?php echo $posterItem->title; ?></h2>
        <textarea   name="annotation-<?php echo $posterItem->ordernum; ?>" 
                    id="myomeka-annotation-<?php echo $posterItem->ordernum; ?>"
                    rows="6" 
                    cols="10">
            <?php echo $posterItem->annotation; ?>
        </textarea>
    </div>
    <?php if ($posterItem->favoriteAnnotation): ?>
        <div class="myomeka-notes">
            <h3>My Notes</h3>
            <?php echo $posterItem->favoriteAnnotation; ?>
        </div>
    <?php endif ?>
    <input  type="hidden" 
            name="itemID-<?php echo $posterItem->ordernum; ?>" 
            value="<?php echo $posterItem->id; ?>" 
            class="myomeka-hidden-item-id"/>
</div>