<?php 
/* MyOmeka Plugin */

define('MYOMEKA_PLUGIN_VERSION', '0.2dev');

add_plugin_hook('initialize', 'myomeka_initialize');
add_plugin_hook('install', 'myomeka_install');
add_plugin_hook('theme_header', 'myomeka_css');
add_plugin_hook('config_form', 'myomeka_configForm');
add_plugin_hook('config', 'myomeka_config');

add_controllers('controllers');
require_once PLUGIN_DIR."/MyOmeka/models/Favorite.php";

function myomeka_initialize()
{	
    add_theme_pages('theme', 'public');
    add_theme_pages('theme', 'admin');
	add_theme_pages('shared', 'both');
	add_navigation('Posters', 'poster/adminPosters');
	
	//Define some special ACL rules for this plugin
		
	$acl = Zend_registry::get( 'acl' );

	//Come up with some terminology for this
	$acl->addRole(new Zend_Acl_Role('myomeka'));

	$acl->registerRule(new Zend_Acl_Resource('MyOmeka'), array('favorite'));

	//The new role and all the existing roles, should be able to list certain items as 'favorites'
	$acl->allow('myomeka',	'MyOmeka',array('favorite'));	
	$acl->allow('researcher',	'MyOmeka',array('favorite'));
	$acl->allow('admin',		'MyOmeka',array('favorite'));
	$acl->allow('contributor',	'MyOmeka',array('favorite'));		
}

function myomeka_install()
{	
	set_option('myomeka_plugin_version', MYOMEKA_PLUGIN_VERSION);
	
	// Create new tables to support poster building
	$db = get_db();
	$db->exec(  "CREATE TABLE IF NOT EXISTS {$db->prefix}posters ( 
                    `id` BIGINT UNSIGNED NOT NULL auto_increment PRIMARY KEY, 
            	    `title` VARCHAR(255) NOT NULL, 
            	    `description` TEXT, 
            		`user_id` BIGINT UNSIGNED NOT NULL,
            		`date_created` TIMESTAMP NOT NULL default '0000-00-00 00:00:00',
            		`date_modified` TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
            	) ENGINE = MYISAM;");
	
	$db->exec(  "CREATE TABLE IF NOT EXISTS {$db->prefix}posters_items ( 
                    `id` BIGINT UNSIGNED NOT NULL auto_increment PRIMARY KEY, 
            	    `annotation` TEXT, 
            		`poster_id` BIGINT UNSIGNED NOT NULL,
            		`item_id` BIGINT UNSIGNED NOT NULL,
            		`ordernum` INT NOT NULL
            	) ENGINE = MYISAM;");

	// Create Favorites table
	$db->exec(  "CREATE TABLE IF NOT EXISTS {$db->prefix}favorites ( 
                    `id` BIGINT UNSIGNED NOT NULL auto_increment PRIMARY KEY, 
            	    `annotation` TEXT, 
            		`user_id` BIGINT UNSIGNED NOT NULL,
            		`item_id` BIGINT UNSIGNED NOT NULL,
            		`date_modified` TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
            	) ENGINE = MYISAM;");
}

function myomeka_css()
{
	echo "<link rel=\"stylesheet\" media=\"screen\" href=\"".css('myomeka')."\" />";
}

add_plugin_hook('item_browse_sql', 'myomeka_show_only_my_items');

/**
 * This allows the MyOmeka controller to pass arbitrary parameters when 
 * retrieving items so that we only retrieve items that were added by a user, etc.
 *
 * @return void
 **/

function myomeka_show_only_my_items($select, $params)
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

function myomeka_favorite_link($item)
{
	?>
	<div id="myomeka-favoriting">
		<?php

		$user = current_user();
		$favorite = new Favorite;
		
		if ($favorite->getFavoriteByItemId($user->id, $item->id) > 0){
			include(uri('favorite/_favorite_form'));
		} else { ?>
			<img src="<?php echo img('add.png'); ?>" /> <a href="#" id="favorite-off">Mark as Favorite</a>
		<?php } ?>
	</div>
	
	<script type="text/javascript" charset="utf-8">
	    var container = $('myomeka-favoriting');
	    
		var makeFavorite = function() {
			var url = "<?php echo uri('favorite/_favorite_form'); ?>";
			new Ajax.Updater(container, url, {
				onSuccess: function(t) {
					Effect.Appear(container);					
				},
				onComplete: function(t) {
					Event.observe('save-annotation', 'click', saveFavorite);
				}
			});
			
			return false;
		}
		
		var saveFavorite = function() {
			var annotation = $('annotation').value;
			var tags = $('tags').value;
			var item_id = <?php echo $item->id; ?>;
			
			//Make a spot on the page for the saved annotation
			
			new Ajax.Updater(container, "<?php echo uri('favorite/add/'.$item->id); ?>", {
			    parameters: {
			        annotation: annotation,
			        tags: tags,
					item_id: item_id
			    },
			    method: 'get',
			    onComplete: function(t) {
			        Event.observe('edit-annotation', 'click', editFavorite);
			    }
			});
						
			return false;
		}
		
		var editFavorite = function() {
			new Ajax.Updater(container, "<?php echo uri('favorite/edit'.$item->id); ?>", {
				onSuccess: function(t) {
					Effect.Appear(container);					
				}
			});
		}

		var deleteFavorite = function() {
			new Ajax.Updater(container, "<?php echo uri('favorite/delete'.$item->id); ?>", {
				onSuccess: function(t) {
					Effect.Appear(container);					
				}
			});
		}

		Event.observe('favorite-off', 'click', makeFavorite);
	</script>
	
<?php
}

function myomeka_configForm() {
?>
<label for="myomeka_favname">The default name for the "favorites" feature is favorites.  This configuration page allows you to change favorites to something else if you wish:</label>
<input type="text" name="myomeka_favname" size="90" value="<?php echo get_option('myomeka_favname'); ?>" id="map_key" />
<?php
}

function myomeka_config() {
	set_option('myomeka_favname', $_POST['myomeka_favname']);
}

function poster_icon_html($item) {
    //If we can get a square thumbnail out of it, use that
    if($thumbnail = square_thumbnail($item)) {
        return $thumbnail;
    } else {
        return "<img alt='no image available' src='".img('noThumbnail.png')."'/>";
    }
}

function myomeka_breadcrumb() {
	
}

function myomeka_userloggedin_status() {
	$user = current_user();
	echo "<p>logged in as $user->username | <a href=\"" . uri('myomeka/logout/') . "\">Logout</a></p>";
}

?>