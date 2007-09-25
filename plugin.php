<?php 

define('MYARCHIVE_PLUGIN_VERSION', 1);

add_plugin_hook('initialize', 'myarchive_init');

function myarchive_init()
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

add_plugin_hook('append_to_page', 'myarchive_append_stuff');

function myarchive_append_stuff($page, $options) {
	switch ($page) {
		case 'items/show':
			myarchive_favorite_button($options['item']);
			break;
		
		default:
			break;
	}
}

function myarchive_show_favorite_button($item) {
	
}

add_plugin_hook('install', 'myarchive_install');

function myarchive_install()
{
	set_option('myarchive_plugin_version', MYARCHIVE_PLUGIN_VERSION);
	
	//We want to add the 'favorite' entity relationship to the entity_relationships table if needed
	
}
?>
