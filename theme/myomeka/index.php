<?php head(); ?>

<div id="myomeka-primary">
	
	<div id="login-registration-box">
	
	<?php
	// show the login and registration form only if the registration email was NOT sent 
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