<div class="field">
    <label for="my_omeka_page_path">Relative Page Path</label>
    <div class="inputs">
        <input type="text" class="textinput" name="my_omeka_page_path" value="<?php echo settings('my_omeka_page_path') ?>" />
        <p class="explanation">Please enter the relative page path from the project root where you want the MyOmeka page to be located. Use forward slashes to indicate subdirectories, but do not begin with a forward slash.</p>
    </div>
</div>
<div class="field">
    <label for="my_omeka_page_title">Page Title:</label>
    <div class="inputs">
        <input type="text" class="textinput" name="my_omeka_page_title" value="<?php echo settings('my_omeka_page_title'); ?>" />
        <p class="explanation">Please enter the title you'd like to use for your MyOmeka installation.</p>
    </div>
</div>