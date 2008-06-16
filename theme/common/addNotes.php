<?php echo js('addNotes'); ?>
<div id="myomeka-add-notes">
    <h2>Your Notes</h2>
    <div id="myomeka-display-note">
        <?php if($note): ?>
        <div id="myomeka-note"><?php echo nls2p($note->note);?></div>
        <?php endif; ?>
        <div><a id="myomeka-edit-link" href="#">Edit your notes</a></div>
    </div>
    <div id="myomeka-edit-note">
        <form action="<?php print uri("note/submit"); ?>" id="myomeka-note-form" method="post" accept-charset="utf-8">    
            <div>
                <div class="field">
                    <label>Enter your notes:</label>
                    <textarea name="note" rows="5" cols="60"><?php echo $note->note; ?></textarea>
                </div>
                <input type="submit" id="myomeka-submit-note" value="Save Notes"/> 
                or <a href="#" id="myomeka-cancel-edit">Cancel</a>
                <input type="hidden" name="item_id" value="<?php print $item->id;?>"/>
            </div>
        </form>
    </div>
</div>