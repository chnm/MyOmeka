<?php head(); ?>
<h1><?php echo $poster->title;?></h1>
<?php echo $poster->description;?>
<?php foreach($posterItems as $posterItem): ?>
    <h2><?php echo $posterItem->title; ?></h2>
    <?php echo $posterItem->annotation; ?>
<?php endforeach; ?>

<?php foot(); ?>