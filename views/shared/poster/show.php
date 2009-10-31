<?php

$pageTitle = 'Poster: &quot;' . html_escape($poster->title) . '&quot;';
head(array('title'=>$pageTitle));

?>

<div id="primary">
    <h1><?php echo $pageTitle; ?></h1>

	<div id="myomeka-poster">
		<div id="myomeka-poster-info">
		<?php echo $poster->description; ?>
		</div>

		<?php set_items_for_loop($poster->Items); ?>
		<?php while ($item = loop_items()): ?>
		<div class="myomeka-poster-item">
	        <h2><?php echo link_to_item(); ?></h2>
			<?php if (item_has_thumbnail()) echo link_to_item(item_thumbnail()); ?>
			<div class="myomeka-poster-item-annotation">
		        <?php echo $item->annotation; ?>
			</div>
		</div>
		<?php endwhile; ?>

		<?php 
		    $disclaimer = get_option('my_omeka_disclaimer');
		    if (!empty($disclaimer)): 
		?>
		<div id="myomeka-disclaimer">
			<h2 id="myomeka-disclaimer-title">Disclaimer</h2>
			<?php echo nls2p(html_escape($disclaimer)); ?>
		</div>
		<?php endif; ?>
	</div>
</div> <!-- end primary div -->
<?php foot(); ?>