<?php head(); ?>

<div id="myomeka-primary">
	
	<div id="login-registration-box">
	
	<?php
	// show the login and registration form only if the registration email was NOT sent
	// this allows the registration form to indicate that an email was sent (it won't show the login info).
	if (!$emailSent) {
		echo flash();
		include('login.php');
		include('register.php');
	} else {
		include('register.php');
		echo flash();
	}
	?>
	</div>

</div>

<?php foot(); ?>