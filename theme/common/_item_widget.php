<style type="text/css" media="screen">
    #item-widget {
        display:block;
        width: 800px;
        height: 540px;
    }
    
    #choose-item {
        display:block;
        width: 825px;
        height: 520px;
        overflow:auto;
        padding:10px;
    }
        
    #item-info {
        clear:none;
        display:block;
        border: 1px solid #999;
        float:right;
        width: 320px;
        height: 520px;
        overflow:auto;
        padding: 10px;
    }
    
    #item-info .field {
        width: 280px;
    }
        
    #item-info .item-id {
        display:none;
    }    
    
    #item-widget-add-item {
        margin: 10px 50px 10px 50px;
    }
        
    #choose-item .favorite-item {
        display: block;
        width: 125px;
        height: 140px;
        float:left;
        margin: 5px;
    }
    
    #choose-item img {
        margin:10px;
    }

</style>
<div id="item-widget" style="display:none;">
    <div id="choose-item">
        <h2>Choose From Among Your Annotated Items</h2>
        <?php if(count($items)):?>
            <?php foreach($items as $item):?>
            <div class="favorite-item">
                <form action="<?php echo uri('poster/addPosterItem'); ?>" method="POST" accept-charset="utf-8">
                    <?php echo poster_icon_html($item); ?>
                	<div class="item-title"><em><?php echo h(snippet($item->title, 0, 150)); ?></em></div>
                	<input type="hidden" name="item-id" value="<?php echo $item->id; ?>" class="favorite-item-id"/>
                   <input type="submit" name="submit" value="Add this item"/>
                </form>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have you favorite items before you can add those items to a poster</p>
        <?php endif; ?>
    </div>
</div>