<div class="field">
<label for="my_omeka_page_path">Relative Page Path From Project Root:</label>
<div class="inputs">
<input type="text" class="textinput" name="my_omeka_page_path" value="<?php echo settings('my_omeka_page_path') ?>" />
<p class="explanation">Please enter the relative page path from the project root where you want the MyOmeka page to be located. Use forward slashes to indicate subdirectories, but do not begin with a forward slash.</p>
</div>
</div>
<div class="field">
<label for="my_omeka_page_title">MyOmeka Title:</label>
<div class="inputs">
<input type="text" class="textinput" name="my_omeka_page_title" value="<?php echo settings('my_omeka_page_title'); ?>" />
<p class="explanation">Please enter the title you'd like to use for your MyOmeka installation.</p>
</div>
</div>

<div class="field">
<label for="my_omeka_disclaimer">MyOmeka Disclaimer:</label>
<div class="inputs">
<textarea name="my_omeka_disclaimer" rows="10" cols="60"><?php echo settings('my_omeka_disclaimer'); ?></textarea>
<p class="explanation">The disclaimer text appears below every public poster created by MyOmeka users.</p>
</div>
</div>

<div class="field">
<label for="my_omeka_require_terms_of_service">Require Terms of Service And Privacy Policy:</label>
<div class="inputs">

<input type="checkbox" name="my_omeka_require_terms_of_service" value="checked" <?php $requireTOS = settings('my_omeka_require_terms_of_service');
if (!empty($requireTOS)) { echo 'checked="checked"'; } ?> />
<p class="explanation">Check box if you require registrants to agree to the Terms of Service and Privacy Policy.</p>
</div>
</div>