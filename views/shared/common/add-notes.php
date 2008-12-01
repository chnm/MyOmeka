<div id="myomeka-add-notes">
    <h2>My Notes</h2>
    <div id="myomeka-edit-note">
        <form action="<?php echo uri(array('action'=>'edit'), 'myOmekaNoteAction'); ?>" id="myomeka-note-form" method="post" accept-charset="utf-8">    
            <div>
                <div class="field">
                    <label for="note">Your Notes:</label>
                    <div>Add notes to this item that you can refer to later.</div>
                    <textarea name="note" rows="5" cols="60"><?php echo $note->note; ?></textarea>
                </div>
                <input type="submit" id="myomeka-submit-note" value="Save Notes"/>
                <input type="hidden" name="item_id" value="<?php print $item->id;?>"/>
            </div>
        </form>
    </div>
</div>