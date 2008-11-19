

<div id="myomeka-login-box">
<h2>Login</h2>

<form id="myomeka-login-form" action="<?php echo my_omeka_get_path('login/');?>" method="post" accept-charset="utf-8">
	<fieldset>
	<ol>
	<li><label for="username">Username</label> </li>
	<li><input type="text" name="username" class="textinput" id="username" /></li>

	<li><label for="password">Password</label> </li>
	<li><input type="password" name="password" class="textinput" id="password" /></li>
	</ol>
	<input type="submit" class="login" value="Login" />
	</fieldset>
</form>

<p id="myomeka-forgotpassword"><a href="<?php echo my_omeka_get_path('forgot/'); ?>">Lost your password?</a></p>

</div>