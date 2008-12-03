<?php head(); ?>

<div id="primary">
<div id="myomeka-login-box">
<h1><?php echo get_option('my_omeka_page_title'); ?>: Login</h1>

<a href="<?php echo uri(array('action'=>'register'), 'myOmekaAction'); ?>">Sign up for a new account</a>

<form id="myomeka-login-form" method="post" accept-charset="utf-8">
	<div class="field">
	<label for="username">Username</label>
	<div class="inputs">
		<input type="text" name="username" class="textinput" id="username" />
	</div>
	</div>
	
	<div class="field">
	<label for="password">Password</label>
	<div class="inputs">
		<input type="password" name="password" class="textinput" id="password" />
	</div>
	</div>
	<input type="submit" class="login" value="Login" />
</form>

<p id="myomeka-forgot-password"><a href="<?php echo uri('users/forgot-password'); ?>">Lost your password?</a></p>

<?php 
// Possible spot for a hook that other plugins could use to append data 
// (like a link to register for an account with MyOmeka). ?>

</div>
</div>
<?php foot(); ?>