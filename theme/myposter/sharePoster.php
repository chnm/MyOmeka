<?php head(); ?>
<?php echo flash(); ?>
<h1>Sharing "<?php echo $poster->title; ?>"</h1>
<?php if($emailSent): ?>
    <p>We just sent an email to <?php echo $emailTo; ?> with a link to your poster.</p>
    <a href="<?php echo uri("myomeka/dashboard"); ?>">Go back to your dashboard</a>
<?php else: ?>
    <p>Enter an email address below and we'll send them a link to your poster</p>
    <form action="<?php uri("poster/share/".$poster->id); ?>" method="post" accept-charset="utf-8">
        <div class="field">
            <label for="email">Email</label>
            <input type="text" name="emailTo" value="<?php echo $emailTo; ?>" id="emailTo" />
        </div>    
        <input type="submit" name="submit" value="Send email" /> or 
        <a href="<?php echo uri('myomeka/dashboard'); ?>">Cancel and return to the dashboard</a>
    </form>
<?php endif; ?>
<?php foot(); ?>