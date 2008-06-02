<?php head(); ?>
<div id="primary">
	<h2>User Activation</h2>
<p>Hello, <?php echo h($user->first_name . ' ' . $user->last_name); ?>. Your username is: <?php echo h($user->username); ?></p>

<form method="post">
	<fieldset>
	<div class="field">
	<label for="new_password1">Create a Password:</label>
	<input type="password" name="new_password1" id="new_password1" />
	</div>
	<div class="field">
	<label for="new_password2">Re-type the Password:</label>
	<input type="password" name="new_password2" id="new_password2" />
	</div>
	</fieldset>
	<input type="submit" name="submit" value="Activate your account"/>
</form>

</div>
<?php foot(); ?>
