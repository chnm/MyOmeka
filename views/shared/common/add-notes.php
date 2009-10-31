<div id="myomeka-add-notes">
    <h2>My Notes</h2>
    <div id="myomeka-edit-note">
        <form action="<?php echo html_escape(uri(array('action'=>'edit'), 'myOmekaNoteAction')); ?>" id="myomeka-note-form" method="post" accept-charset="utf-8">    
            <div>
                <div class="myomeka-field">
                    <div>Add notes to this item that you can refer to later.</div>
                    <textarea id="myomeka-note" name="myomeka-note" rows="5" cols="60"><?php echo html_escape($note->note);?></textarea>
                </div>
                
                <div class="myomeka-field">
                    <input type="submit" id="myomeka-submit-note" value="Save Notes"/>
                </div>
                
                <input type="hidden" name="item_id" value="<?php echo html_escape($item->id); ?>"/>
            </div>
        </form>
    </div>
</div>