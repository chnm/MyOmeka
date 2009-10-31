<?php 
    $pageTitle = html_escape(get_option('my_omeka_page_title') . ': Help');
    head(array('title'=>$pageTitle)); 
?>

<div id="primary">
<div id="myomeka-help">
		<h1><?php echo $pageTitle; ?></h1>
        <h2>Your Notes</h2>
        <p>When logged in, you are able to add notes to any of the items on the website. Click on the &quot;Edit Your Notes&quot; link in the &quot;Your Notes&quot; section to begin a new notation or edit an existing one. These notes will be stored and will appear on the item pages any time you are logged in. A list of the items you have annotated will appear on your dashboard. These notes will also be available for use if you choose to make a poster with your items.</p>

        <h2>Your Tags</h2>
        <p>When logged in, you are also able to tag items on the website. Tags are like keywords or labels, allowing you to categorize and group items. Simply type a tag into the &quot;Add a tag&quot; field in &quot;Your Notes,&quot; and click add. Items can have multiple tags. A list of all the tags you use will appear on your dashboard. Clicking on any of these tags will list all of the items to which you have given this tag. For example, clicking on &quot;island&quot; will show you a list of all the items you have tagged &quot;island.&quot;</p>
        
        <h2>Your Posters</h2>
		<p>Once you have installed My Omeka, you may tag any items you would like to include on your poster. You may also include any personal annotations which will also be included on the poster.</p>
        <p>As you explore the site by viewing objects, you may see objects that you wish to include in your poster. When you click on the object, a new page opens with a large version of the object, and two empty fields. The first allows you to make any notes you wish about the item, and to format those notes using simple commands. For example, you may wish to make some notes about how an item is related to another item, or to include some text that you want to include on your poster. The second field allows you to tag the item with a keyword. Once you have filled in those fields, you may click the link at the top right of the page that says &quot;activity&quot;. Click the button that says &quot;Create a Poster&quot;. Assign a title to your poster, and fill in the description field with a description of your project. Click the tab that says &quot;Add an Item,&quot; and select the items that you wish to include in your poster.</p>
	    <p>Be sure to save your poster; you may return to edit your poster at anytime.</p>
</div>
</div> <!-- end primary div -->

<?php foot(); ?>