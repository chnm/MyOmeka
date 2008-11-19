<?php head(); 
	my_omeka_userloggedin_status();
	my_omeka_breadcrumb();
?>
<?php echo flash(); ?>
<h1>Sharing "<?php echo $poster->title; ?>"</h1>
<?php if($emailSent): ?>
    <p>We just sent an email to <?php echo $emailTo; ?> with a link to your poster.</p>
    <a href="<?php echo uri("myomeka/dashboard"); ?>">Go back to your dashboard</a>
<?php else: ?>
    <p>Enter an email address below and we'll send them a link to your poster</p>
    <form action="<?php uri("poster/share/".$poster->id); ?>" method="post" accept-charset="utf-8">
        <div class="myomeka-field">
            <label for="myomeka-emailTo">Email</label>
            <input type="text" name="emailTo" value="<?php echo $emailTo; ?>" id="myomeka-emailTo" />
        </div>
        <p>
            <input type="submit" name="submit" value="Send email" /> or 
            <a href="<?php echo uri('myomeka/dashboard'); ?>">Cancel and return to the dashboard</a>            
        </p>
    </form>
<?php endif; ?>
<?php foot(); ?>