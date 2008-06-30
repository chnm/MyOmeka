<?php 
/* MyOmeka Plugin */

define('MYOMEKA_PLUGIN_VERSION', '0.2dev');

add_plugin_hook('initialize', 'myomeka_initialize');
add_plugin_hook('install', 'myomeka_install');
add_plugin_hook('theme_header', 'myomeka_css');

add_controllers('controllers');
require_once PLUGIN_DIR."/MyOmeka/models/Note.php";

function myomeka_initialize()
{	
    add_theme_pages('views/public', 'public');
    add_theme_pages('views/admin', 'admin');
	add_theme_pages('views/shared', 'both');
	add_navigation('Posters', 'poster/adminPosters');
	
	//Define some special ACL rules for this plugin
		
	$acl = Zend_registry::get( 'acl' );

	//Come up with some terminology for this
	$acl->addRole(new Zend_Acl_Role('MyOmeka'));

	$acl->registerRule(new Zend_Acl_Resource('MyOmeka'), array('favorite'));

	//The new role and all the existing roles, should be able to list certain items as 'favorites'
	$acl->allow('MyOmeka',	'MyOmeka',array('favorite'));	
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

	// Create Notes table
	$db->exec(  "CREATE TABLE IF NOT EXISTS {$db->prefix}notes ( 
                    `id` BIGINT UNSIGNED NOT NULL auto_increment PRIMARY KEY, 
            	    `note` TEXT NOT NULL, 
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

/**
 * Call this function in your public themes to allow users to add notes to an item.
 */
function myomeka_add_notes($item)
{	
    if($user = current_user()) {
     	// Check if the user has already added notes to the item
    	$noteObj = new Note();
    	$result = $noteObj->getItemNotes($user->id, $item->id);
        if(count($result)){
            $note = $result[0];
        } else{
            $note = null;
        }
        
        // Render the addNotes template
        common("addNotes", compact("note","item"));
    }
}

/**
 * Call this function in your public themes to allow users to add and remove tags.
 */
function myomeka_add_tags($item)
{
    if($user = current_user()) {
        require_once PLUGIN_DIR."/MyOmeka/models/MyomekaTag.php";
        $myomekatag = new MyomekaTag;
        $myomekatag->id = $item->id;
        
        $tags = $myomekatag->entityTags(get_db()->getTable("Entity")->find($user->entity_id));
        
        common("addTags", compact("item","tags"));
    }
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
	if ($user) {
		echo "<p>logged in as <a href=\"" . uri('myomeka/') . "\">$user->username</a> | <a href=\"" . uri('myomeka/logout/') . "\">Logout</a></p>";
	} else {
		echo "<a href=\"" . uri('myomeka/login/') . "\">Login</a>";
	}
}

?>