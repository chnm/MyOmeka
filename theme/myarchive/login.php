

<div id="myomeka-login-box">
<h1>Login</h1>
	<?php echo flash();?>
<form id="myomeka-login-form" action="<?php echo uri('myarchive/login');?>" method="post" accept-charset="utf-8">
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

<li id="myomeka-forgotpassword"><a href="<?php echo uri('myarchive/forgot'); ?>">Lost your password?</a></li>
</ul>
</div>