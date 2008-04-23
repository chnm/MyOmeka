<?php head(); ?>

<div id="login-box" style="width: 250px; border: 1px; padding: 25px;">
<h1>Login</h1>
	<?php echo flash();?>
<form id="login-form" action="<?php echo uri('myarchive/login');?>" method="post" accept-charset="utf-8">
	<fieldset>
		<div class="field">
	<label for="username">Username</label> 
	<input type="text" name="username" class="textinput" id="username" />
	</div>
	<div class="field">
	<label for="password">Password</label> 
	<input type="password" name="password" class="textinput" id="password" />
	</div>
	</fieldset>
	<input type="submit" class="login" value="Login" />
</form>

<li id="forgotpassword"><a href="<?php echo uri('myarchive/forgot'); ?>">Lost your password?</a></li>
</ul>
</div>

<?php include('register.php'); ?>

<?php foot(); ?>