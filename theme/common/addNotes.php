<?php
    echo js('tiny_mce/tiny_mce'); 
    echo js('addNotes'); 
?>

<div id="myomeka-add-notes">
	<a class="dashboard-link" href="<?php echo uri('myomeka'); ?>">Go to My Dashboard</a>
   <h2>Your Notes</h2>
    <div id="myomeka-edit-note">
        <form action="<?php print uri("note/submit"); ?>" id="myomeka-note-form" method="post" accept-charset="utf-8">    
            <div>
                <div class="field">
                    <label>Enter your notes:</label>
                    <textarea name="note" rows="5" cols="60"><?php echo $note->note; ?></textarea>
                </div>
                <input type="submit" id="myomeka-submit-note" value="Save Notes"/>
                <input type="hidden" name="item_id" value="<?php print $item->id;?>"/>
            </div>
        </form>
    </div>
</div>