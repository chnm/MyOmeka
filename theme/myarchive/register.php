<div id="register-box">
<h1>Register</h1>
<form id="register-form" action="<?php echo uri('myarchive/register');?>" method="post" accept-charset="utf-8">
	<fieldset>
		<div class="field">
	<label for="username">Username</label> 
	<input type="text" name="username" class="textinput" id="username" />
	</div>
	<div class="field">
	<label for="password">Email</label> 
	<input type="password" name="email" class="textinput" id="email" />
	</div>
	</fieldset>
	<input type="submit" class="register" value="Register" />
</form>
</div>