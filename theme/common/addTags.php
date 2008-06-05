<?php echo js('addTags'); ?>
<div id="myomeka-add-tags">
    <h2>Your Tags</h2>
    <?php if (count($tags)): ?>
        <div id="myomeka-tags">
            <?php foreach ($tags as $tag): ?>
            <a href="<?php print uri("myomekatag/browse/?id=".$tag->id);?>"><?php print $tag->name;?></a> 
            [<a href="<?php print uri("myomekatag/delete/?tag=".urlencode($tag)."&item_id=".$item->id); ?>" title="Delete this tag">X</a>] 
            <?php endforeach ?>
        </div>
    <?php endif; ?>
    <form action="<?php print uri("MyomekaTag/add"); ?>" id="myomeka-tag-form" method="post" accept-charset="utf-8">    
        <div>
            <div class="field">
                <label>Add tags:</label>
                <input type="text" name="tag" value=""/>
                <input type="submit" id="myomeka-submit-tag" value="Add"/> 
            </div>
            <input type="hidden" name="item_id" value="<?php print $item->id;?>"/>
        </div>
    </form>
</div>
