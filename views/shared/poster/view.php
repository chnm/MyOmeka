<?php head(); ?>

<div id="myomeka-primary">

<div id="myomeka-poster-info">
<h2><?php echo $poster->title;?></h2>
<?php echo $poster->description;?>
</div>

<?php foreach($poster->Items as $posterItem): ?>
	<div id="myomeka-poster-item">
	<?php echo link_to_thumbnail($posterItem); ?>
    <h3><?php echo link_to_item($posterItem); ?></h3>
    <?php echo $posterItem->annotation; ?>
	</div>
<?php endforeach; ?>

<div id="myomeka-disclaimer">
    <p>This page contains user generated content and does not necessarily reflect the opinions of this website. For more information please refer to our Terms and Conditions. If you would like to report the content of this page as objectionable, please contact us.</p>
</div>
</div>

<?php foot(); ?>