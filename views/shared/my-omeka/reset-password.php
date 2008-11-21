<?php head(); ?>
<div id="primary">
<div id="myomeka-primary">
	<h2>Reset Password</h2>
	<p>Hello, <?php echo h($user->first_name . ' ' . $user->last_name); ?>.</p>
	<p>Your username is: <?php echo h($user->username); ?></p>
	<?php echo flash(); ?>
	<form method="post">
		<fieldset>
		<div class="field">
		<label for="new_password1">Create a Password (at least 6 characters long):</label>
		<input type="password" name="new_password1" id="new_password1" />
		</div>
		<div class="field">
		<label for="new_password2">Re-type the Password:</label>
		<input type="password" name="new_password2" id="new_password2" />
		</div>
		<input type="submit" name="submit" value="Activate your account"/>
		</fieldset>
	</form>
</div>
</div>
<?php foot(); ?>
