<form action="" id="annotation-form" method="post" accept-charset="utf-8">
    
<div class="field">
<label for="annotation">Enter your annotation:</label>
<textarea name="annotation" id="annotation" rows="10" cols="20"><?php echo $favorite->annotation ?></textarea>
</div>

<div class="field">
<label for="tags">Enter your tags:</label>
<input type="text" name="tags" id="tags" value="" />
</div>

<button type="button" id="save-annotation">Save Your Annotation</button>

</form>

<!-- if item already favorited, allow user to delete it

<a href="#" id="delete-annotation">Delete Annotation</a> -->