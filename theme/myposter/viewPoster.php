<?php head(); ?>

<div id="myomeka-primary">
<h2><?php echo $poster->title;?></h2>
<?php echo $poster->description;?>
<?php foreach($posterItems as $posterItem): ?>
    <h3><?php echo $posterItem->title; ?></h3>
    <?php echo $posterItem->annotation; ?>
<?php endforeach; ?>
</div>

<?php foot(); ?>