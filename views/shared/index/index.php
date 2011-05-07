<?php
    $pageTitle = 'MyOmeka : Dashboard';
    head(array('title'=>$pageTitle));
?>

<div id="primary">
<h1><?php echo $pageTitle; ?></h1>
<?php echo flash(); ?>
</div>
<?php foot(); ?>