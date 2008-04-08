<?php head(); ?>
<?php echo js('poster'); ?>
<style type="text/css" media="screen">
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
        float:left;
        width: 300px;
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
        width: 150px;
        height: 100px;
        float: none;
    }
    
    .annotation input {
        float:right;
    }
    
    .drop-on-spot {
        border: 3px dotted red;
    }
    
</style>
<div id="primary">
<form action="" method="post" accept-charset="utf-8" id="poster-form">
    
    <div class="field">
        <label for="title">Title of Poster:</label>
        <input type="text" name="title" value="" id="title" />
    </div>
    
    <div class="field">
        <label for="description">Description:</label>
        <input type="text" name="description" value="" id="description" />
    </div>
    
    
    <div id="poster-canvas">
    <?php for($i=1; $i<=6; $i++): ?>
        <div class="poster-spot" id="poster-spot-<?php echo $i; ?>">
            <div class="spot-order"><?php echo $i; ?></div>
            
            <div class="item-canvas">
                <div class="item"></div>
                <input type="button" name="choose_item[<?php echo $i; ?>]" value="Choose Item" class="choose-item" />
            </div>
            
            <div class="annotation">
                <textarea name="annotation[<?php echo $i; ?>]" rows="4" cols="10"></textarea>
                <input type="button" name="more_text[<?php echo $i; ?>]" value="+" class="add-more-text" />
            </div>
        </div>
    <?php endfor; ?>
    </div> 
    
    <input type="submit" name="save_poster" value="Save Poster" id="save_poster" />
    <input type="submit" name="preview_poster" value="Preview Your Poster" id="preview_poster" />
    <input type="submit" name="publish_poster" value="Publish Your Poster" id="publish_poster" />
       
</form>
</div>
<?php foot(); ?>