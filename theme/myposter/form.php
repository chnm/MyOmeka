<?php head(); ?>
<?php echo js('poster'); ?>
<?php echo js('tiny_mce/tiny_mce'); ?>
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
        <input type="text" name="title" value="" id="title" />
    </div>
    
    <div class="field">
        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea>
    </div>
        
    <div id="poster-canvas">
    <?php for($i=1; $i<=0; $i++): ?>
        <?php common('_spot', compact('item', 'i'), 'myposter'); ?>
    <?php endfor; ?>
    </div> 
    
    
        <?php common('_item_widget', compact('items')); ?>
   
    
    <input type="submit" name="save_poster" value="Save Poster" id="save_poster" />
    <input type="submit" name="preview_poster" value="Preview Your Poster" id="preview_poster" />
    <input type="submit" name="publish_poster" value="Publish Your Poster" id="publish_poster" />
       
</form>
</div>
<?php foot(); ?>