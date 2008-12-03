<?php head(array('title'=>'Register for ' . get_option('my_omeka_page_title'))); ?>
<div id="primary">
    
<div id="myomeka-register">
<h2><?php echo get_option('my_omeka_page_title'); ?>: Register</h2>

<?php
echo flash(); 
//only show the form if the registration email has not been sent
if (!$emailSent): 
?>
	
<form id="myomeka-register-form" action="<?php echo uri(array('action'=>'register'), 'myOmekaAction');?>" method="post" accept-charset="utf-8">
<fieldset>
	
	<div class="field">
    	<?php echo label('username','User Name'); ?>
    	<div class="inputs">
    	<?php echo text(array('name'=>'username', 'class'=>'textinput', 'size'=>'30','id'=>'username'), $_POST['username']); ?>
    	</div>
    	<?php echo form_error('username'); ?>
    </div>

    <div class="field">
    	<?php echo label('first_name','First Name'); ?>

    	<div class="inputs">	
    		<?php echo text(array('name'=>'first_name', 'size'=>'30', 'class'=>'textinput', 'id'=>'first_name'), $_POST['first_name']); ?>
    	</div>

    	<?php echo form_error('first_name'); ?>

    </div>

    <div class="field">
    	<?php echo label('last_name','Last Name'); ?>
    	<div class="inputs">
    		<?php echo text(array('name'=>'last_name', 'size'=>'30', 'class'=>'textinput', 'id'=>'last_name'), $_POST['last_name']); ?>
    	</div>
    	<?php echo form_error('last_name'); ?>
    </div>

    <div class="field">
    	<?php echo label('email','Email'); ?>
    	<div class="inputs">
    	<?php echo text(array('name'=>'email', 'class'=>'textinput', 'size'=>'30', 'id'=>'email'), $_POST['email']); ?>
    	</div>
    	<?php echo form_error('email'); ?>
    </div>
	
	<?php if (get_option('my_omeka_require_terms_of_service') && function_exists('terms_of_service_link')): ?>
	    <div class="field">	
			Please review the <?php echo terms_of_service_link() ?> and <?php echo terms_of_service_privacy_policy_link(); ?>
			<?php echo terms_of_service_form_input('agreed_to_tos_and_privacy_policy', 'I understand and agree to the Terms of Service and Privacy Policy'); ?>
			<?php echo form_error('agreed_to_tos_and_privacy_policy'); ?>
		<?php endif; ?>
	
	<input type="submit" class="register" value="Register" />

	</fieldset>

</form>
<?php endif; ?>
</div>

</div>
<?php foot(); ?>