<?php head(); ?>

<div id="primary">
<?php echo flash(); ?>
<h1>Sharing &#8220;<?php echo htmlspecialchars($poster->title); ?>&#8221;</h1>
<?php if($emailSent): ?>
    <p>We just sent an email to <?php echo $emailTo; ?> with a link to your poster.</p>
    <a href="<?php echo uri(array(), 'myOmekaDashboard'); ?>">Go back to your dashboard</a>
<?php else: ?>
    <p>Enter an email address below and we'll send them a link to your poster</p>
    <form action="<?php uri(array('action'=>'share', 'id'=>$poster->id), 'myOmekaPosterActionId'); ?>" method="post" accept-charset="utf-8">
        <div class="myomeka-field">
            <label for="myomeka-emailTo">Email</label>
            <input type="text" name="email_to" value="<?php echo $emailTo; ?>" id="myomeka-emailTo" />
        </div>
        <p>
            <input type="submit" name="submit" value="Send email" /> or 
            <a href="<?php echo uri(array(), 'myOmekaDashboard'); ?>">Cancel and return to the dashboard</a>            
        </p>
    </form>
<?php endif; ?>
</div>
<?php foot(); ?>