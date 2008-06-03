<div id="myomeka-register-box">
<h2>Register</h2>
<form id="myomeka-register-form" action="<?php echo uri('myomeka/register');?>" method="post" accept-charset="utf-8">
<?php if(!isset($user)) {
	$user = new User;
	$user->setArray($_POST);
} 
?>


<fieldset>
	
	<ol>
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
	</ol>

	</fieldset>
	<input type="submit" class="register" value="Register" />
</form>

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

