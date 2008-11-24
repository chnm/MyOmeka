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
define('MY_OMEKA_PLUGIN_VERSION', '0.3');
define('MY_OMEKA_PAGE_PATH', 'myomeka/');

define('MYOMEKA_USER_ROLE', 'my-omeka');

require_once 'MyOmekaNote.php';
// Current hack, controllers require access to some of the view helpers
// for generating URLs in emails.  Need to refactor helpers to allow access
// within controllers.
require_once HELPER_DIR . DIRECTORY_SEPARATOR . 'all.php';


// Add plugin hooks.
add_plugin_hook('install', 'my_omeka_install');
add_plugin_hook('config', 'my_omeka_config');
add_plugin_hook('config_form', 'my_omeka_config_form');
add_plugin_hook('define_acl', 'my_omeka_setup_acl');
add_plugin_hook('define_routes', 'my_omeka_define_routes');
add_plugin_hook('public_theme_header', 'my_omeka_css');
add_plugin_hook('item_browse_sql', 'my_omeka_show_only_my_items');

add_plugin_hook('initialize', 'my_omeka_add_controller_plugin');

// Add filters.
add_filter('admin_navigation_main', 'my_omeka_admin_nav');

/**
 * Install the plugin.
 */

function my_omeka_install()
{	
	set_option('my_omeka_plugin_version', MY_OMEKA_PLUGIN_VERSION);
	set_option('my_omeka_page_path', my_omeka_clean_path(MY_OMEKA_PAGE_PATH));
	
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
 * Define the routes, wrapping them in my_omeka_add_route()
 */

function my_omeka_define_routes($router) 
{
	// get the base path
	$bp = get_option('my_omeka_page_path');
	
	if (empty($bp)) {
	   $bp = MY_OMEKA_PAGE_PATH;
	}
	    
    $routes = array();
    
    // We may be able to condense this list even more.
    $routes['myOmekaDashboard'] = array('', array('controller'=>'my-omeka'));
    $routes['myOmekaAction'] = array(':action', array('controller'=>'my-omeka'));
    $routes['myOmekaPosterAction'] = array('poster/:action', array('controller'=>'poster'));
    $routes['myOmekaPosterActionId'] = array('poster/:action/:id', array('controller'=>'poster'));
    $routes['myOmekaAddTag'] = array('tags/add', array('controller'=>'tag', 'action'=>'add'));
    $routes['myOmekaTagBrowse'] = array('tags/browse/:id', array('controller'=>'tag', 'action'=>'browse'));
    $routes['myOmekaTagDelete'] = array('tags/delete/:tag/:item_id', array('controller'=>'tag', 'action'=>'delete'));
    $routes['myOmekaNoteAction'] = array('note/:action', array('controller'=>'note'));
    
    foreach ($routes as $routeName => $routeValues) {
        list($routePath, $routeVars) = $routeValues;
        // All of these routes are for the 'my-omeka' module.
        $routeVars = array_merge(array('module'=>'my-omeka'), $routeVars);
        $router->addRoute(
            $routeName, 
            new Zend_Controller_Router_Route(
                // Attach the base path to every defined route.
                $bp . $routePath, 
                $routeVars));
    }    
}

/**
 * Add the Poster Administration link to the admin main navigation.
 * 
 * @param array Navigation array.
 * @return array Filtered navigation array.
 */

function my_omeka_admin_nav($navArray)
{
    return $navArray += array('Posters'=> uri(array('action'=>'browse'), 'myOmekaPosterAction'));
}

function my_omeka_css()
{
	echo "<link rel=\"stylesheet\" media=\"screen\" href=\"".css('myomeka')."\" />";
}

/**
 * This allows the MyOmeka controller to pass arbitrary parameters when 
 * retrieving items so that we only retrieve items that were added by a user, etc.
 *
 * @todo What is the deal with this?  Does this get used anywhere?
 * @return void
 **/
function my_omeka_show_only_my_items($select, $params)
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
function my_omeka_add_notes($item)
{	
    if($user = current_user()) {
		// Check if the user has already added notes to the item
    	$note = get_db()->getTable('MyOmekaNote')->findByUserIdAndItemId($user->id, $item->id);
        
        // Render the addNotes template
        common("add-notes", compact("note","item"));
    }
}

/**
 * Echo this function in your items/show.php of your public themes to allow users to add and remove notes and tags
 */
function my_omeka_embed_notes_and_tags() 
{
	 $item = get_current_item();
	 
	$user = current_user(); 
	$html = '';
	 if ($user) {
        $html .= '<div id="myomeka-notes-tags">';
        $html .= my_omeka_add_notes($item);
        $html .= my_omeka_add_tags($item);
        $html .= '</div>';
	}
	return $html;
}

/**
 * Call this function in your public themes to allow users to add and remove tags.
 */
function my_omeka_add_tags($item)
{
    $user = current_user();
    $tags = get_db()->getTable('Tag')->findBy(array('user'=>$user->id, 'type'=>'MyomekaTag'));
    common("add-tags", compact("item","tags"));
}

function poster_icon_html() 
{
    $html = item_thumbnail();
    if (!$html) {
        $html = "<img alt='no image available' src='".img('noThumbnail.png')."'/>";
    }
    return $html;
}

function my_omeka_breadcrumb() 
{
	
}

function my_omeka_user_status() 
{
	$user = current_user();
	if ($user) {
		echo "<p>logged in as <a href=\"" . uri(array(), 'myOmekaDashboard') . "\">$user->username</a> | <a href=\"" . uri(array('action'=>'logout', 'controller'=>'users'), 'default') . "\">Logout</a></p>";
	} else {
		echo '<p><a href="' . uri(array(), 'myOmekaDashboard') . '">Login</a> | <a href="' . uri(array('action'=>'register'), 'myOmekaAction') . '">Register</a></p>';
	}
}

function my_omeka_clean_path($path)
{
	return rtrim($path, '/ ') . '/';
}

function my_omeka_config($post) 
{
	set_option('my_omeka_page_path', my_omeka_clean_path($post['my_omeka_page_path']));
	
	//if the page path is empty then make it the default page path
	if (trim(get_option('my_omeka_page_path')) == '') {
		set_option('my_omeka_page_path', my_omeka_clean_path(MYOMEKA_PAGE_PATH));
	}

	$requireTOS = (strtolower($post['my_omeka_require_terms_of_service']) == 'checked') ? 1 : 0;
	set_option('my_omeka_require_terms_of_service', $requireTOS);
}

function my_omeka_config_form() 
{
        	my_omeka_settings_css(); //this styling needs to be associated with appropriate hook
			$textInputSize = 30;
			$textAreaRows = 10;
			$textAreaCols = 50;
			$requireTOS = settings('my_omeka_require_terms_of_service');
		
		?>
		<div id="myomeka_settings">
			<label for="myomeka_page_path">Relative Page Path From Project Root:</label>
			<p class="instructionText">Please enter the relative page path from the project root where you want the MyOmeka page to be located. Use forward slashes to indicate subdirectories, but do not begin with a forward slash.</p>
			<input type="text" name="my_omeka_page_path" value="<?php echo settings('my_omeka_page_path') ?>" />
			<label for="my_omeka_require_terms_of_service">Require Terms of Service And Privacy Policy:</label>
			<p class="instructionText">Check box if you require registrants to agree to the Terms of Service and Privacy Policy.</p>
			<input type="checkbox" name="my_omeka_require_terms_of_service" value="CHECKED" <?php if (!empty($requireTOS)) { echo 'CHECKED'; } ?> />
		</div>
	<?php
}

// the css style for the configure settings
function my_omeka_settings_css() 
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

function my_omeka_setup_acl($acl)
{
    $acl->addRole(new Zend_Acl_Role(MYOMEKA_USER_ROLE));
    // $acl->loadResourceList(array('MyOmeka_MyOmeka'=>array('dashboard', 'index')));
    
    // Have to hard code all the roles that allow access rather than just saying
    // that all logged in users have access.
    // $acl->allow(array(MYOMEKA_USER_ROLE, 'admin', 'super', 'researcher', 'contributor'), 'MyOmeka_MyOmeka', array('index', 'dashboard'));
}

function my_omeka_add_controller_plugin()
{
    require_once 'MyOmekaControllerPlugin.php';
    Zend_Controller_Front::getInstance()->registerPlugin(new MyOmekaControllerPlugin);
}