<?php
    $pageTitle = 'Share Poster: &quot;' . html_escape($poster->title) . '&quot;';
    head(array('title'=>$pageTitle));
?>

<div id="primary">
<?php echo flash(); ?>
<h1><?php echo $pageTitle; ?></h1>
<?php if ($emailSent): ?>
    <p>We just sent an email to <?php echo html_escape($emailTo); ?> with a link to your poster.</p>
    <a href="<?php echo html_escape(uri(array(), 'myOmekaDashboard')); ?>">Go back to your dashboard</a>
<?php else: ?>
    <p>Enter an email address below and we'll send them a link to your poster</p>
    <form action="<?php echo html_escape(uri(array('action'=>'share', 'id'=>$poster->id), 'myOmekaPosterActionId')); ?>" method="post" accept-charset="utf-8">
        <div class="myomeka-field">
            <label for="myomeka-emailTo">Email</label>
            <input type="text" name="email_to" value="<?php echo html_escape($emailTo); ?>" id="myomeka-emailTo" />
        </div>
        <div class="myomeka-field">
            <input type="submit" name="submit" value="Send Email" /> or 
            <a href="<?php echo html_escape(uri(array(), 'myOmekaDashboard')); ?>">Cancel and Return to the Dashboard</a>            
        </div>
    </form>
<?php endif; ?>
</div>
<?php foot(); ?>