<div id="myomeka-add-tags">
    <h2>My Tags</h2>
    <?php if (count($tags)): ?>
        <div id="myomeka-my-tags">
            <ul class="hTagcloud">
            <?php foreach ($tags as $tag): ?>
            <li><a href="<?php echo html_escape(uri(array('myTag'=>$tag->id, 'controller'=>'items', 'action'=>'browse'), 'default')); ?>"><?php echo html_escape($tag->name);?></a> 
            [<a href="<?php echo html_escape(uri(array('tag_id'=>$tag->id, 'item_id'=>$item->id), 'myOmekaTagDelete')); ?>" title="Delete this tag">X</a>]</li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="<?php echo html_escape(uri(array(), 'myOmekaAddTag')); ?>" id="myomeka-tag-form" method="post" accept-charset="utf-8">    
        <div class="myomeka-field">
            <div>Enter keywords below to tag this item.</div>
            <input type="text" id="myomeka-new-tags" name="tag" value=""/>
            <input type="submit" id="myomeka-submit-add-tags" value="Add Tags"/> 
        </div>
        <div>
            <input type="hidden" name="item_id" value="<?php echo html_escape($item->id); ?>"/>
        </div>
    </form>
</div>
