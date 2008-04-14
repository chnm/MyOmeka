<?php
//@testing Only here for testing purposes, REMOVE THIS CODE
if(!$item) {
    $item = item($_GET['id']);
}

?>

<input type="button" name="add_item" value="Add This Item" id="item-widget-add-item">

<h3><?php echo h($item->title); ?></h3>

<div class="item-id">
    <?php echo htmlentities($item->id); ?>
</div>

<div class="files">
    <?php echo display_files($item->Files[0]); ?>
</div>

<div class="field">
<?php echo nls2p(h($item->description)); ?>
</div>