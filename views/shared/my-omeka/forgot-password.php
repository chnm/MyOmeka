<?php head(array()); ?>

<div id="myomeka-primary">
<h2>Forgot Password</h2>
<?php echo flash(); ?>
<?php 
	if (!$emailSent) { 
?>
<form method="post" accept-charset="utf-8">
	<label for="email">Please provide your email address:</label>
	<input type="text" name="email" id="email" class="textinput" value="<?php echo @$_POST['email']; ?>" />
	<input type="submit" value="Submit" />
</form>

<?php 
	} // end if
?>
</div>

<?php foot(); ?>