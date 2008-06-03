<?php echo js('addTags'); ?>
<div id="myomeka-add-tags">
    <h2>Your Tags</h2>
    <?php if (count($tags)): ?>
        <div id="myomeka-tags">
            <?php foreach ($tags as $tag): ?>
            <a href="#<?php print $tag;?>"><?php print $tag;?></a> [<a href="#">X</a>] 
            <?php endforeach ?>
        </div>
    <?php endif; ?>
    <!-- START SAMPLE TAG LAYOUT -->
    <div id="myomeka-tags">
        <a href="#">Sample1</a> [<a href="#">X</a>] 
        <a href="#">Sample2</a> [<a href="#">X</a>] 
        <a href="#">Sample3</a> [<a href="#">X</a>] 
        <a href="#">Sample4</a> [<a href="#">X</a>]
    </div>
    <!-- END SAMPLE TAG LAYOUT -->
    <form action="<?php print uri("note/submitTag"); ?>" id="myomeka-tag-form" method="post" accept-charset="utf-8">    
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