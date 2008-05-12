<?php 
    head();
    echo js('poster');
    echo js('tiny_mce/tiny_mce'); 
    echo js('ibox/ibox'); 
?>
<style type="text/css" media="screen">
    #description {
        height:200px;
    }
    
    .item {
        display:block;
        width: 100px;
        height: 100px;
        border: 1px dotted #999;
        margin-right: 20px;
    }
    
    .item-canvas {
        float: left;
    }
    
    .poster-spot {
        display:block;
        width: 650px;
        border: 1px solid #999;
        margin: 20px 0px 20px 10px;
        padding: 10px 5px 10px 5px;
    }
    
    #poster-canvas {
        display:block;
        width: 700px;
        margin-top: 30px;
    }
    
    .annotation {
        display:block;
        clear:none;
        padding-left: 20px;
    }
    
    .annotation textarea {
        width: 470px;
        height: 100px;
        float: none;
    }
    
    .annotation input {
        float:right;
    }
    
    .drop-on-spot {
        border: 3px dotted red;
    }
    
    .controls {
        clear:both;
        margin-bottom: 10px;
    }
    
    .delete {
        float:right;
    }    
</style>

<script type="text/javascript" charset="utf-8">
    //@testing CAN ANYONE THINK OF A BETTER WAY TO EMBED PHP into the Javascript?
    Poster.placeholderUrl = "<?php echo uri('poster/placeholder'); ?>";
</script>
<div id="primary">
<form action="<?php echo uri('poster/save'); ?>" method="post" accept-charset="utf-8" id="poster-form">
    <div class="field">
        <label for="title">Title of Poster:</label>
        <input type="text" name="title" value="<?php echo $poster->title;?>" id="title" />
    </div>
    
    <div class="field">
        <label for="description">Description:</label>
        <textarea name="description" id="description"><?php echo $poster->description;?></textarea>
    </div>
    
    <h2>Poster Items</h2>
    <a href="#item-widget" rel="ibox&amp;width=850" title="Chose on of your favorites to add">Add an item</a>
    <div id="poster-canvas">
    <?php if(count($posterItems)): ?>
        <?php foreach($posterItems as $posterItem): ?>
            <div class="poster-spot" id="poster-spot-<?php echo $posterItem->ordernum; ?>">
                <div class="controls">
                    <input type="image" src="<?php echo img('delete.gif'); ?>" class="delete" />
                    <input type="image" src="<?php echo img('arrow_down.png'); ?>" class="move-down" />
                    <input type="image" src="<?php echo img('arrow_up.png'); ?>" class="move-up" />
                    <a href="#" class="move-top">Move to Top</a>
                    <a href="#" class="move-bottom">Move to Bottom</a>
                </div>

                <input type="hidden" name="items[]" value="<?php echo $posterItem->id; ?>" />

                <div class="item-canvas">
                    <div class="item">
                        <?php echo square_thumbnail($posterItem); ?>
                    </div>
                </div>

                <div class="annotation">
                    <textarea name="annotation[]" rows="4" cols="10" id="poster-annotation-<?php echo $posterItem->ordernum; ?>"><?php echo $posterItem->annotation; ?></textarea>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </div> 
    <?php common('_item_widget', compact('items')); ?>
    
    <input type="submit" name="save_poster" value="Save Poster" id="save_poster" /> or <a href="<?php echo uri('myarchive/dashboard'); ?>">Discard changes and return to the dashboard</a>
</form>
</div>
<?php foot(); ?>