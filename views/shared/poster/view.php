<?php head(); ?>

<div id="primary">
<div id="myomeka-primary">

<div id="myomeka-poster-info">
<h2><?php echo htmlspecialchars($poster->title);?></h2>
<?php echo $poster->description;?>
</div>

<?php set_items_for_loop($poster->Items); ?>
<?php while ($item = loop_items()): ?>
	<div id="myomeka-poster-item">
	<?php if (item_has_thumbnail()) echo link_to_item(item_thumbnail()); ?>
    <h3><?php echo link_to_item(); ?></h3>
    <?php echo $item->annotation; ?>
	</div>
<?php endwhile; ?>

<?php $disclaimer = get_option('my_omeka_disclaimer'); if(!empty($disclaimer)): ?>
<div id="myomeka-disclaimer">
	<p><strong>Disclaimer</strong></p>
	<?php echo nls2p($disclaimer); ?>
</div>
<?php endif; ?>
</div>
</div> <!-- end primary div -->
<?php foot(); ?>