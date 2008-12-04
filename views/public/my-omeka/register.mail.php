Welcome!

Your account for the <?php echo $siteTitle; ?> archive has been created. Your username is "<?php echo $user->username; ?>". Please click the following link to activate your account:

<?php echo abs_uri(array('controller'=>'users', 'action'=>'activate'), 'default'); ?>?u=<?php echo $activationSlug; ?> 

Be aware that we log you out after 15 minutes of inactivity to help protect people using shared computers (at libraries, for instance).

<?php echo $siteTitle; ?> Administrator