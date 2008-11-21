<?php head(); ?>

<div id="myomeka-primary">

<div id="myomeka-poster-info">
<h2><?php echo $poster->title;?></h2>
<?php echo $poster->description;?>
</div>

<?php set_items_for_loop($poster->Items); ?>
<?php while ($item = loop_items()): ?>
	<div id="myomeka-poster-item">
	<?php echo link_to_item(item_thumbnail()); ?>
    <h3><?php echo link_to_item(); ?></h3>
    <?php echo $item->annotation; ?>
	</div>
<?php endwhile; ?>

<div id="myomeka-disclaimer">
    <p>This page contains user generated content and does not necessarily reflect the opinions of this website. For more information please refer to our Terms and Conditions. If you would like to report the content of this page as objectionable, please contact us.</p>
</div>
</div>

<?php foot(); ?>