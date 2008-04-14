<?php 

define('MYARCHIVE_PLUGIN_VERSION', '0.1dev');
add_theme_pages('theme', 'public');
add_controllers();
/**
 * THE COMMENTED OUT STUFF IS OLD CODE, MAY HELP OR JUST IGNORE IT - [KBK]
 *
 * @package MyArchive plugin
 **/
//add_plugin_hook('initialize', 'myarchive_init');

/*function myarchive_init()
{
	//Define some special ACL rules for this plugin
		

		$acl = Zend::Registry( 'acl' );

	//Come up with some terminology for this
	$acl->addRole(new Zend_Acl_Role('myarchive'));

	$acl->registerRule(new Zend_Acl_Resource('MyArchive'), array('favorite'));

	//The new role and all the existing roles, should be able to list certain items as 'favorites'
	$acl->allow('myarchive',	'MyArchive',array('favorite'));	
	$acl->allow('researcher',	'MyArchive',array('favorite'));
	$acl->allow('admin',		'MyArchive',array('favorite'));
	$acl->allow('contributor',	'MyArchive',array('favorite'));			
	
	add_theme_pages('theme', 'both');
	add_controllers();
}

add_plugin_hook('item_browse_sql', 'myarchive_show_only_my_items');

/**
 * This allows the MyArchive controller to pass arbitrary parameters when 
 * retrieving items so that we only retrieve items that were added by a user, etc.
 *
 * @return void
 **/
/*
function myarchive_show_only_my_items($select, $params)
{
	$user = current_user();
	
	if($user) {
		$entity_id = (int) $user->entity_id;
		
		//If the controller sets this parameter, we are retrieving items that were added by this user
		if(isset($params['added_by_me']) or isset($params['favorited_by_me'])) {
		
			//Join against the entities_relations table
			$select->innerJoin('entities_relations my_e','my_e.relation_id = i.id');
			$select->innerJoin('entity_relationships my_er', 'my_er.id = my_e.relationship_id');
			$select->where('my_e.type = "Item" AND my_e.entity_id = ' . $entity_id);
			
			if( isset($params['added_by_me']) ) {
				$select->where('my_er.name = "added"');
			}
			elseif( isset($params['favorited_by_me']) ) {
				$select->where('my_er.name = "favorite"');
			}	
		}
		
		if(isset($params['tagged_by_me'])) {
			
			$select->innerJoin('taggings my_tg', 'my_tg.relation_id = i.id');
			
			$select->where('my_tg.type = "Item" AND my_tg.entity_id = ' . $entity_id);
		}	
	}
}

add_plugin_hook('install', 'myarchive_install');

function myarchive_install()
{
	set_option('myarchive_plugin_version', MYARCHIVE_PLUGIN_VERSION);
	
	//We want to add the 'favorite' entity relationship to the entity_relationships table if needed
	
}*/

function mystuff_favorite_link()
{
	?>
	<style type="text/css" media="screen">
		#favoriting input {font-size: 2em;}
		#favoriting label {clear:both;}
		#favoriting {
		    display:block;
		    clear:both;
		    background-color: #f1c8ba;
		    border: 1px dotted black;
		    margin-bottom:50px;
		    padding-left:30px;
		    padding-top: 30px;
		    padding-bottom: 20px;}
		#favoriting textarea {float: none;clear:both;}
		#saved-annotation {font-style: italic;font-size: 2em;clear:both;}
	</style>
	
	<div id="favoriting">
		<a href="#" id="favorite-off"><img src="<?php echo img('favorite-off.gif'); ?>" /></a>
	</div>
	
	<script type="text/javascript" charset="utf-8">
	    var container = $('favoriting');
	    
		var makeFavorite = function() {
			var url = "<?php echo uri('_favorite_form'); ?>";
			new Ajax.Updater(container, url, {
				onSuccess: function(t) {
					Effect.Appear(container);					
				},
				onComplete: function(t) {
					Event.observe('save-annotation', 'click', saveAnnotation);
				}
			});
			
			return false;
		}
		
		var saveAnnotation = function() {
			var annotation = $('annotation').value;
			var tags = $('tags').value;
			
			//Make a spot on the page for the saved annotation
			
			new Ajax.Updater(container, "<?php echo uri('_favorite_saved'); ?>", {
			    parameters: {
			        annotation: annotation,
			        tags: tags
			    },
			    method: 'get',
			    onComplete: function(t) {
			        Event.observe('edit-annotation', 'click', makeFavorite);
			    }
			});
						
			return false;
		}

		Event.observe('favorite-off', 'click', makeFavorite);
	</script>
	
<?php
}

function poster_icon_html($item) {
    //If we can get a square thumbnail out of it, use that
    if($thumbnail = square_thumbnail($item)) {
        return $thumbnail;
    }
    
    switch ($item->Type->name) {
        case 'Document':
            return 'Document';
            break;
        
        default:
            return 'No Type given';
            break;
    }
}

