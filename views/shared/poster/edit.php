<?php
    $pageTitle = 'Edit Poster: &quot;' . html_escape($poster->title) . '&quot;';
    queue_js(array('tiny_mce/tiny_mce', 'poster'));
    head(array('title'=>$pageTitle));
?>
<script type="text/javascript">
    // Set the initial Item Count
    Omeka.Poster.itemCount = <?php echo count($poster->Items); ?>;

    jQuery(window).load(Omeka.Poster.init);
</script>
<div id="primary">
    <h1><?php echo $pageTitle; ?></h1>
    <div id="myomeka-poster">
	    <div id="myomeka-poster-info">
            <form action="<?php echo html_escape(uri(array('action'=>'save', 'id'=>$poster->id), 'myOmekaPosterActionId')); ?>" method="post" accept-charset="utf-8" id="myomeka-poster-form">
                <div class="myomeka-field">
                    <label for="myomeka-title">Title of Poster:</label>
                    <?php echo $this->formText('title', $poster->title, array('id'=>'myomeka-title')); ?>
                </div>

                <div class="myomeka-field">
                    <label for="myomeka-description">Description:</label>
                    <?php echo $this->formTextarea('description', $poster->description, 
                    array('id'=>'myomeka-description', 'rows'=>'8', 'cols'=>'20')); ?>
                </div>

                <h2>Poster Items</h2>
                <?php if (!count($poster->Items)): ?>
                    <p id="myomeka-poster-no-items-yet">You have not added any items to this poster yet.</p>
                <?php endif; ?>
                
                <div id="myomeka-poster-canvas">
                <?php
                    if (count($poster->Items)):
                        foreach ($poster->Items as $posterItem):
                            $noteObj = my_omeka_get_note_for_item($posterItem);
                            common('spot', array('posterItem'=>$posterItem, 'noteText'=>$noteObj->note), 'poster');
                        endforeach;
                    endif;
                ?>
                </div>
        
                <div id="myomeka-poster-additem">
                    <?php if (count($items)): ?>
                        <button type="button">Add an Item &rarr;</button>
                    <?php else: ?>
                        <button type="button" disabled="disabled">Add an item &rarr;</button>
                        <p>You have to add notes or tags to an item before adding them to a poster</p>
                    <?php endif; ?>
                </div>
        
                <div id="myomeka-submit-poster">
                    <input type="submit" name="save_poster" value="Save Poster" /> or 
                    <?php if (is_admin_theme()): ?>
                        <a href="<?php echo html_escape(uri(array('action'=>'discard'), 'myOmekaPosterAction')); ?>">Discard Changes and Return to Poster Administration</a>
                    <?php else: ?>
                        <a href="<?php echo html_escape(uri(array('action'=> 'discard'), 'myOmekaPosterAction')); ?>">Discard Changes and Return to the Dashboard</a>
                    <?php endif ?>
                    <input type="hidden" name="itemCount" value="<?php echo count($poster->Items); ?>" id="myomeka-itemCount"/>

                    <div id="myomeka-help">
                	    <p><a href="<?php echo html_escape(uri(array('action'=>'help'), 'myOmekaAction')); ?>" class="myomeka-help-link">Help</a></p>
                	</div>

                </div>
            
            </form>

            <!-- Hidden div for modal pop-up -->
            <div id="myomeka-additem-modal">
            <?php if (count($items)):?>
                <?php while ($item = loop_items()):?>
                    <div class="myomeka-additem-item">
                        <div class="myomeka-additem-image">
                            <?php echo my_omeka_poster_icon_html(); ?>
                        </div>
                        <div class="myomeka-additem-details">
                            <dl>
                                <dt>Title:</dt>
                                <dd><?php echo item('Dublin Core', 'Title'); ?></dd>
                                <dt>Description:</dt>
                                <dd><?php echo item('Dublin Core', 'Description'); ?></dd>
                                <dt>Creator:</dt>
                                <dd><?php echo item('Dublin Core', 'Creator'); ?></dd>
                                <?php if ($item->annotation): ?>
                                <dt>My Notes:</dt>
                                <dd><?php echo $item->annotation; ?></dd>
                                <?php endif ?>
                            </dl>
                        </div>
                        <br />
                        <form action="<?php echo html_escape(uri(array('action'=>'add-poster-item'), 'myOmekaPosterAction')); ?>" method="post" accept-charset="utf-8" class="myomeka-additem-form">
                    	    <div>
                    	        <input type="submit" name="submit" value="Add this Item" class="myomeka-additem-submit"/>
                    	        <input type="hidden" name="item-id" value="<?php echo html_escape($item->id); ?>" class="myomeka-additem-item-id"/>
                	        </div>
                	    </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You must tag or take notes on items before you can add those items to a poster.</p>
            <?php endif; ?>
            </div> <!-- end modal popup div -->
        </div> <!-- end myomeka-poster-info div -->
    </div> <!-- end myomeka-poster div -->
</div> <!-- end primary div -->
<?php foot(); ?>
