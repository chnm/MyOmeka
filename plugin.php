<?php 
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 * @subpackage MyOmeka
 **/
// note: MyOmeka currently requires the TermsOfService plugin

// Define the plugin version and page path.
define('MYOMEKA_PLUGIN_VERSION', '0.3alpha');
define('MYOMEKA_PAGE_PATH', 'myomeka/');

require_once 'MyOmekaNote.php';

// Add plugin hooks.
add_plugin_hook('install', 'myomeka_install');
add_plugin_hook('config', 'myomeka_config');
add_plugin_hook('config_form', 'myomeka_config_form');
//add_plugin_hook('define_acl', 'myomeka_setup_acl');
add_plugin_hook('define_routes', 'myomeka_define_routes');
add_plugin_hook('public_theme_header', 'myomeka_css');
add_plugin_hook('item_browse_sql', 'myomeka_show_only_my_items');

// Add filters.
add_filter('admin_navigation_main', 'myomeka_admin_nav');

// when I call a function defined in a controller, such as myomeka_get_path, which uses uri or settings, the following helper functions have not 
// yet been loaded, hence i need to add these here.  I wish these could be loaded somewhere else before the controller is loaded [JL]
// I'm unsure if this applies to the 0.10 API, so I'm commenting it out for now.. [DL]
// require_once HELPER_DIR.'/Functions.php';
// require_once HELPER_DIR.'/UnicodeFunctions.php';

/**
 * Install the plugin.
 */

function myomeka_install()
{	
	set_option('myomeka_plugin_version', MYOMEKA_PLUGIN_VERSION);
	set_option('myomeka_page_path', myomeka_clean_path(MYOMEKA_PAGE_PATH));
	
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

/**
 * Define the routes, wrapping them in myomeka_add_route()
 */

function myomeka_define_routes($router) 
{
	// get the base path
	$bp = get_option('myomeka_page_path');
	
	//add the myomeka page route
	myomeka_add_route($bp, 'my-omeka', 'index', $router);
	
	//add the login page route
	myomeka_add_route($bp . 'login', 'my-omeka', 'login', $router);
	
	//add the logout page route
	myomeka_add_route($bp . 'logout', 'my-omeka', 'logout', $router);

	//add the register page route
	myomeka_add_route($bp . 'register', 'my-omeka', 'register', $router);

	//add the activate page route
	myomeka_add_route($bp . 'activate', 'my-omeka', 'activate', $router);

	//add the reset password page route
	myomeka_add_route($bp . 'resetPassword', 'my-omeka', 'reset-password', $router);
	
	//add the forget page route
	myomeka_add_route($bp . 'forgot', 'my-omeka', 'forgot', $router);
		
	//add the dashboard page route
	myomeka_add_route($bp . 'dashboard', 'my-omeka', 'dashboard', $router);
	
	//add the help page route
	myomeka_add_route($bp . 'help', 'myomeka', 'help-page',$router);

	//add the poster share page route
	myomeka_add_route($bp . 'poster/share/:id', 'poster', 'share', $router);

	//add the poster view page route
	myomeka_add_route($bp . 'poster/view/:id', 'poster', 'view', $router);
	
	//add the poster edit page route
	myomeka_add_route($bp . 'poster/edit/:id', 'poster', 'edit', $router);

	//add the poster addPosterItem page route
	myomeka_add_route($bp . 'poster/addPosterItem', 'poster', 'add-poster-item', $router);
	
	//add the poster save page route
	myomeka_add_route($bp . 'poster/save/:id', 'poster', 'save', $router);

	//add the poster delete page route
	myomeka_add_route($bp . 'poster/delete/:id', 'poster', 'delete', $router);

	//add the poster admin page route
	myomeka_add_route($bp . 'poster/admin-posters', 'poster', 'admin-posters', $router);
	
	//add the tag add page route
	myomeka_add_route($bp . 'tags/add', 'my-omeka-tag', 'add', $router);

	//add the tag browse page route
	myomeka_add_route($bp . 'tags/browse/:id', 'my-omeka-tag', 'browse', $router);
	
	//add the tag delete page route
	myomeka_add_route($bp . 'tags/delete/:tag/:item_id', 'my-omeka-tag', 'delete', $router);

	//add the notes edit page route
	myomeka_add_route($bp . 'notes/edit', 'note', 'edit', $router);
}

/**
 * Add the defined routes.
 * 
 * @param Zend_Controller_Router_Rewrite
 */

function myomeka_add_route($routeName, $controllerName, $actionName, $router) 
{
	$router->addRoute(
	    $routeName, 
	    new Zend_Controller_Router_Route(
	        $routeName, 
	        array(
	            'module'        =>  'my-omeka', 
	            'controller'    =>  $controllerName, 
	            'action'        =>  $actionName
	        )
	    )
	);
}

/**
 * Add the Poster Administration link to the admin main navigation.
 * 
 * @param array Navigation array.
 * @return array Filtered navigation array.
 */

function myomeka_admin_nav($navArray)
{
    return $navArray += array('Posters'=> uri('poster/admin-posters'));
}

function myomeka_css()
{
	echo "<link rel=\"stylesheet\" media=\"screen\" href=\"".css('myomeka')."\" />";
}

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
        } else {
            $note = null;
        }
        
        // Render the addNotes template
        common("addNotes", compact("note","item"));
    }
}

/**
 * Echo this function in your items/show.php of your public themes to allow users to add and remove notes and tags
 */
function myomeka_embed_notes_and_tags($item) 
{
	 
	$user = current_user(); 
	$html = '';
	 if ($user) {
        $html .= '<div id="myomeka-notes-tags">';
        $html .= myomeka_add_notes($item);
        $html .= myomeka_add_tags($item);
        $html .= '</div>';
	}
	return $html;
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

function poster_icon_html($item) 
{
    //If we can get a square thumbnail out of it, use that
    if($thumbnail = square_thumbnail($item)) {
        return $thumbnail;
    } else {
        return "<img alt='no image available' src='".img('noThumbnail.png')."'/>";
    }
}

function myomeka_breadcrumb() 
{
	
}

function myomeka_get_path($p='') 
{
	return uri(settings('myomeka_page_path') . $p);
}

function myomeka_userloggedin_status() 
{
	$user = current_user();
	if ($user) {
		echo "<p>logged in as <a href=\"" . myomeka_get_path() . "\">$user->username</a> | <a href=\"" . myomeka_get_path('logout/') . "\">Logout</a></p>";
	} else {
		echo "<p><a href=\"" . myomeka_get_path('login/') . "\">Login</a></p>";
	}
}

function myomeka_clean_path($path)
{
	return trim(trim($path), '/') . '/';
}

function myomeka_config($post) 
{
	set_option('myomeka_page_path', myomeka_clean_path($post['myomeka_page_path']));
	
	//if the page path is empty then make it the default page path
	if (trim(get_option('myomeka_page_path')) == '') {
		set_option('myomeka_page_path', myomeka_clean_path(MYOMEKA_PAGE_PATH));
	}

	$requireTOS = (strtolower($post['myomeka_require_terms_of_service']) == 'checked') ? 1 : 0;
	set_option('myomeka_require_terms_of_service', $requireTOS);
}

function myomeka_config_form() 
{
        	myomeka_settings_css(); //this styling needs to be associated with appropriate hook
			$textInputSize = 30;
			$textAreaRows = 10;
			$textAreaCols = 50;
			$requireTOS = settings('myomeka_require_terms_of_service');
		
		?>
		<div id="myomeka_settings">
			<label for="myomeka_page_path">Relative Page Path From Project Root:</label>
			<p class="instructionText">Please enter the relative page path from the project root where you want the MyOmeka page to be located. Use forward slashes to indicate subdirectories, but do not begin with a forward slash.</p>
			<input type="text" name="myomeka_page_path" value="<?php echo settings('myomeka_page_path') ?>" />
			<label for="myomeka_require_terms_of_service">Require Terms of Service And Privacy Policy:</label>
			<p class="instructionText">Check box if you require registrants to agree to the Terms of Service and Privacy Policy.</p>
			<input type="checkbox" name="myomeka_require_terms_of_service" value="CHECKED" <?php if (!empty($requireTOS)) { echo 'CHECKED'; } ?> />
		</div>
	<?php
}

// the css style for the configure settings
function myomeka_settings_css() 
{
	$html = '';
	$html .= '<style type="text/css" media="screen">';
		
	$html .= '#myomeka_settings label, #myomeka_settings input, #myomeka_settings textarea {';
	$html .= 'display:block;';
	$html .= 'float:none;';
	$html .= '}';
	
	$html .= '#myomeka_settings input, #myomeka_settings textarea {';
	$html .= 'margin-bottom:1em;';
	$html .= '}';
	
	$html .= '</style>';
	
	echo $html;
}

/**
 * Define the ACL.
 * 
 * @param Omeka_Acl
 */

function myomeka_setup_acl($acl)
{
    // This ACL code was copied directly from the 0.9.x exhibit builder
    // previously the ACL had to be defined upon initializing the plugin
    // the new 0.10 plugin API no longer requires this, and should be cleaned up
    
    //Defined some special ACL rules for this plugin
    // $acl = Zend_Registry::get( 'acl' );
    // 
    // //Come up with some terminology for this
    // $acl->addRole(new Zend_Acl_Role('MyOmeka'));
    // 
    // $acl->registerRule(new Zend_Acl_Resource('MyOmeka'), array('favorite'));
    // 
    // //The new role and all the existing roles, should be able to list certain items as 'favorites'
    // $acl->allow('MyOmeka', 'MyOmeka',array('favorite')); 
    // $acl->allow('researcher', 'MyOmeka',array('favorite'));
    // $acl->allow('admin', 'MyOmeka',array('favorite'));
    // $acl->allow('contributor', 'MyOmeka',array('favorite'));
      
}

?>