<?php head(); ?>

<div id="myomeka-primary">

<div id="myomeka-poster-info">
<h2><?php echo $poster->title;?></h2>
<?php echo $poster->description;?>
</div>


<?php foreach($posterItems as $posterItem): ?>
	<div id="myomeka-poster-item">
	<?php echo link_to_square_thumbnail($posterItem); ?>
    <h3><?php echo link_to_item($posterItem); ?></h3>
    <?php echo $posterItem->annotation; ?>
	</div>
<?php endforeach; ?>
</div>

<?php foot(); ?>