<div class="poster-spot" id="poster-spot-<?php echo $i; ?>">
    <div class="controls">
        <input type="image" src="<?php echo img('delete.gif'); ?>" class="delete" />
        <input type="image" src="<?php echo img('arrow_down.png'); ?>" class="move-down" />
        <input type="image" src="<?php echo img('arrow_up.png'); ?>" class="move-up" />
        <a href="#" class="move-top">Move to Top</a>
        <a href="#" class="move-bottom">Move to Bottom</a>
    </div>
    
    
    
    <input type="hidden" name="items[]" value="<?php echo $item->id; ?>" />
        
    <div class="item-canvas">
        <div class="item">
            <?php echo square_thumbnail($item); ?>
        </div>
    </div>
    
    <div class="annotation">
        <textarea name="annotation[]" rows="4" cols="10" id="poster-annotation-<?php echo $i; ?>"></textarea>
    </div>
</div>