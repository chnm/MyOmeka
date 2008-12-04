<?php head(); ?>

<div id="primary">
	<div id="myomeka-user-activation">
		<h1>User Activation</h1>
		<?php echo flash(); ?>
	    <h2>Hello, <?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?>. Your username is: <?php echo htmlspecialchars($user->username); ?></h2>
		<form method="post">
			<div class="field">
			<?php echo label('new_password1', 'Create a Password'); ?>
				<div class="inputs">
					<input type="password" name="new_password1" id="new_password1" />
				</div>
			</div>
			<div class="field">
				<label for="new_password2">Re-type the Password:</label>
				<div class="inputs">
					<input type="password" name="new_password2" id="new_password2" />
				</div>
			</div>
			<input type="submit" class="submit submit-medium" name="submit" value="Activate" />
		</form>
	</div>
</div>
<?php foot(); ?>
