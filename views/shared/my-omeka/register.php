<div id="myomeka-register-box">
<h2>Register</h2>

<?php 
	//only show the form if the registration email has not been sent
	if (!$emailSent) { 
?>
	
<form id="myomeka-register-form" action="<?php echo my_omeka_get_path('register/');?>" method="post" accept-charset="utf-8">
<?php if(!isset($user)) {
	$user = new User;
	$user->setArray($_POST);
} 
?>


<fieldset>
	
	<ul>
		<li><label for="username">Username</label> </li>
		<li><?php text(array('name'=>'username', 'class'=>'textinput', 'id'=>'username'),$user->username); ?></li>
		<li><?php echo form_error('username'); ?></li>


		<li><label for="first_name">First Name</label> </li>
		<li><?php text(array('name'=>'first_name', 'class'=>'textinput', 'id'=>'first_name'),not_empty_or($user->first_name, $_POST['first_name'])); ?></li>
		<li><?php echo form_error('first_name'); ?></li>


		<li><label for="last-name">Last Name</label> </li>
		<li><?php text(array('name'=>'last_name', 'class'=>'textinput', 'id'=>'last_name'),not_empty_or($user->last_name, $_POST['last_name'])); ?></li>
		<li><?php echo form_error('last_name'); ?></li>

		<li><label for="email">Email Address</label> </li>
		<li><?php text(array('name'=>'email', 'class'=>'textinput', 'id'=>'email'), not_empty_or($user->email, $_POST['email'])); ?></li>
		<li><?php echo form_error('email'); ?></li>
		
		<?php if ($requireTermsOfService): ?>
			<li>Please review the <a href="<?php echo uri(settings('terms_of_service_tos_page_path')); ?>">Terms of Service</a> and <a href="<?php echo uri(settings('terms_of_service_privacy_policy_page_path')); ?>">Privacy Policy</a></li>
			<li><?php echo terms_of_service_form_input('agreed_to_tos_and_privacy_policy', ''); ?> <label for="agreed_to_tos_and_privacy_policy">I understand and agree to the Terms of Service and Privacy Policy</label></li>
			<li><?php echo form_error('agreed_to_tos_and_privacy_policy'); ?></li>
		<?php endif; ?>
	</ul>
	
	<input type="submit" class="register" value="Register" />

	</fieldset>

</form>
<?php } ?>
<?php /*

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
*/ ?>
</div>