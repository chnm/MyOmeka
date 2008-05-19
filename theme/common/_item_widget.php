<div id="myomeka-item-widget" style="display:none;">
    <div id="myomeka-choose-item">
        <h2>Choose From Among Your Annotated Items</h2>
        <?php if(count($items)):?>
            <?php foreach($items as $item):?>
            <div class="myomeka-favorite-item">
                <form action="<?php echo uri('poster/addPosterItem'); ?>" method="post" accept-charset="utf-8">
                    <?php echo poster_icon_html($item); ?>
                	<div class="myomeka-item-title"><em><?php echo h(snippet($item->title, 0, 150)); ?></em></div>
                	<div>
                    	<input type="submit" name="submit" value="Add this item"/>
                    	<input type="hidden" name="item-id" value="<?php echo $item->id; ?>" class="myomeka-favorite-item-id"/>                	   
                	</div>
                </form>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have you favorite items before you can add those items to a poster</p>
        <?php endif; ?>
    </div>
</div>