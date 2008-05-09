<div id="myomeka-register-box">
<h1>Register</h1>
<form id="myomeka-register-form" action="<?php echo uri('myarchive/register');?>" method="post" accept-charset="utf-8">
<?php if(!isset($user)) {
	$user = new User;
	$user->setArray($_POST);
} 
?>

<?php echo flash(); ?>
<fieldset>
<div class="field">
	<?php text(array('name'=>'username', 'class'=>'textinput', 'id'=>'username'),$user->username, 'Username'); ?>
	<?php echo form_error('username'); ?>
</div>

<div class="field">
	<?php text(array('name'=>'first_name', 'class'=>'textinput', 'id'=>'first_name'),not_empty_or($user->first_name, $_POST['first_name']), 'First Name'); ?>
	<?php echo form_error('first_name'); ?>
</div>

<div class="field">
	<?php text(array('name'=>'last_name', 'class'=>'textinput', 'id'=>'last_name'),not_empty_or($user->last_name, $_POST['last_name']), 'Last Name'); ?>
	<?php echo form_error('last_name'); ?>
</div>

<div class="field">
	<?php text(array('name'=>'email', 'class'=>'textinput', 'id'=>'email'), not_empty_or($user->email, $_POST['email']), 'Email'); ?>
	<?php echo form_error('email'); ?>
</div>

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

