<?php head(); ?>

<div id="primary">
	<div id="myomeka-forgot-password">
<h1>Forgot Password</h1>
<?php echo flash(); ?>
<form method="post" accept-charset="utf-8">
	<div class="field">
	<label for="email">Please provide your email address:</label>
	<div class="inputs">
	<input type="text" name="email" id="email" class="textinput" value="<?php echo @$_POST['email']; ?>" />
	</div>
	</div>
	<input type="submit" class="submitinput" value="Submit" />
</form>
<p>Back to <a href="<?php echo uri('users/login'); ?>">login</a>.</p>
</div>
</div>
<?php foot(); ?>