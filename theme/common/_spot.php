<div class="poster-spot">
    <div class="controls">
        <img src="<?php echo img('delete.gif'); ?>" class="delete poster-control" title="Remove this item"/>
        <img src="<?php echo img('arrow_up_up.png'); ?>" class="move-top poster-control" title="Move to the top"/>
        <img src="<?php echo img('arrow_up.png'); ?>" class="move-up poster-control" title="Move up"/>
        <img src="<?php echo img('arrow_down.png'); ?>" class="move-down poster-control" title="Move down"/>
        <img src="<?php echo img('arrow_down_down.png'); ?>" class="move-bottom poster-control" title="Move to the bottm"/>
    </div>

    <div class="item-canvas">
        <div class="item">
            <?php echo square_thumbnail($posterItem); ?>
        </div>
    </div>

    <div class="annotation">
        <h2><?php echo $posterItem->title; ?></h2>
        <textarea   name="annotation-<?php echo $posterItem->ordernum; ?>" 
                    id="annotation-<?php echo $posterItem->ordernum; ?>"
                    rows="4" 
                    cols="10">
            <?php echo $posterItem->annotation; ?>
        </textarea>
    </div>
    <input  type="hidden" 
            name="itemID-<?php echo $posterItem->ordernum; ?>" 
            value="<?php echo $posterItem->id; ?>" 
            class="hidden-item-id"/>
</div>