<?php 
    //Provide some dummy data to this partial
//    $items = items(array('per_page'=>9)); 
    $currentItem = current($items);
?>
<style type="text/css" media="screen">
    #item-widget {
        display:block;
        width: 800px;
        height: 540px;
    }
    
    #choose-item {
        display:block;
        width: 430px;
        height: 520px;
        border: 1px solid #999;
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
        
    #choose-item .item {
        display: block;
        width: 125px;
        height: 140px;
        float:left;
        margin: 5px;
    }
    
    #choose-item img {
        margin:10px;
    }
    
    #pagination {float:right; display:block;width:472px; padding:0.75em 0 0;text-align:right;clear:both;}
    #pagination ul {text-align:right; line-height:1em; margin-bottom:0;}
	#pagination li {display:inline;}
	#pagination li {display:inline; padding: 0 .5em;}
</style>
<div id="item-widget" style="display:none;">
    <div id="item-info">
        <h2>View Item Info</h2>
        <div id="item-view">
            <?php common('_item_view', array('item'=>$currentItem)); ?>
        </div>
    </div>
    
    <div id="choose-item">
        <h2>Choose From Among Your Annotated Items</h2>

        <?php foreach($items as $key => $item):?>
        <div class="item">
            <?php //@testing RENAME THIS URL TO A PROPER CONTROLLER-BASED URL ?>
        	<a href="<?php echo uri('common/_item_view', array('id'=>$item->id)); ?>"><?php //echo poster_icon_html($item); ?></a>
        	<div class="item-title"><em><?php echo h(snippet($item->title, 0, 150)); ?></em></div>
        </div>
        <?php endforeach; ?>
        
        <div id="pagination">
            <?php //@testing RENAME THIS URL TO A PROPER ONE ?>
            <?php echo pagination_links(5,null,null,null,null,uri('common/_item_widget'), 'page'); ?>
        </div>
    </div>
</div>