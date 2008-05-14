<div class="poster-spot">
    <div class="controls">
        <a href="#" class="delete poster-control"><img src="<?php echo img('delete.gif'); ?>" title="Remove this item"/></a>
        <a href="#" class="move-top poster-control"><img src="<?php echo img('arrow_up_up.png'); ?>"  title="Move to the top"/></a>
        <a href="#" class="move-up poster-control"><img src="<?php echo img('arrow_up.png'); ?>"  title="Move up"/></a>
        <a href="#" class="move-down poster-control"><img src="<?php echo img('arrow_down.png'); ?>"  title="Move down"/></a>
        <a href="#" class="move-bottom poster-control"><img src="<?php echo img('arrow_down_down.png'); ?>"  title="Move to the bottm"/></a>
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