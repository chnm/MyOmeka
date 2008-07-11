<?php echo js('addTags'); ?>
<div id="myomeka-add-tags">
    <h2>My Tags</h2>
    <?php if (count($tags)): ?>
        <div id="myomeka-tags">
            <?php foreach ($tags as $tag): ?>
            <a href="<?php echo myomeka_get_path("tags/browse/" . $tag->id );?>"><?php print $tag->name;?></a> 
            [<a href="<?php echo myomeka_get_path("tags/delete/" . $tag . '/' . $item->id); ?>" title="Delete this tag">X</a>] 
            <?php endforeach ?>
        </div>
    <?php endif; ?>
    <form action="<?php echo myomeka_get_path("tags/add"); ?>" id="myomeka-tag-form" method="post" accept-charset="utf-8">    
        <div>
            <div class="field">
                <label>Add tags:</label>
                <div>Enter keywords below to tag this item.</div>
                <input type="text" name="tag" value=""/>
                <input type="submit" id="myomeka-submit-tag" value="Add"/> 
            </div>
            <input type="hidden" name="item_id" value="<?php print $item->id;?>"/>
        </div>
    </form>
</div>
