<?php 
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 * @subpackage MyOmeka
 **/
// note: MyOmeka can optionally be used in conjunction with the TermsOfService plugin

// Define the plugin version and page path.
define('MY_OMEKA_PLUGIN_VERSION', get_plugin_ini('MyOmeka', 'version'));
define('MY_OMEKA_PAGE_PATH', 'myomeka/');
define('MY_OMEKA_PAGE_TITLE', 'MyOmeka');
define('MY_OMEKA_DISCLAIMER', 'This page contains user generated content and does not necessarily reflect the opinions of this website. For more information please refer to our Terms and Conditions. If you would like to report the content of this page as objectionable, please contact us.');

define('MYOMEKA_USER_ROLE', 'my-omeka');
define('MYOMEKA_TAG_TYPE', 'MyomekaTag');

require_once 'MyOmekaNote.php';
// Current hack, controllers require access to some of the view helpers
// for generating URLs in emails.  Need to refactor helpers to allow access
// within controllers.
require_once HELPER_DIR . DIRECTORY_SEPARATOR . 'all.php';

// Add plugin hooks.
add_plugin_hook('install', 'my_omeka_install');
add_plugin_hook('uninstall', 'my_omeka_uninstall');
add_plugin_hook('config', 'my_omeka_config');
add_plugin_hook('config_form', 'my_omeka_config_form');
add_plugin_hook('define_acl', 'my_omeka_setup_acl');
add_plugin_hook('define_routes', 'my_omeka_define_routes');
add_plugin_hook('public_theme_header', 'my_omeka_css');
add_plugin_hook('item_browse_sql', 'my_omeka_show_only_my_items');
add_plugin_hook('public_append_to_items_show', 'my_omeka_embed_notes_and_tags');
add_plugin_hook('initialize', 'my_omeka_add_controller_plugin');
add_plugin_hook('before_delete_item', 'my_omeka_delete_myomeka_taggings');

// Special hooks.
add_plugin_hook('html_purifier_form_submission', 'my_omeka_xss_filter');

// Add filters.
add_filter('admin_navigation_main', 'my_omeka_admin_nav');

/**
 * Install the plugin.
 */
function my_omeka_install()
{	
	set_option('my_omeka_plugin_version', MY_OMEKA_PLUGIN_VERSION);
	set_option('my_omeka_page_path', my_omeka_clean_path(MY_OMEKA_PAGE_PATH));
	set_option('my_omeka_page_title', MY_OMEKA_PAGE_TITLE);
	set_option('my_omeka_disclaimer', MY_OMEKA_DISCLAIMER);
	
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
 * Uninstall the plugin
 */
function my_omeka_uninstall() 
{
    delete_option('my_omeka_plugin_version');
	delete_option('my_omeka_page_path');
	delete_option('my_omeka_page_title');
	delete_option('my_omeka_disclaimer');

	$db = get_db();
	$db->query("DROP TABLE {$db->prefix}posters");
	$db->query("DROP TABLE {$db->prefix}posters_items");
	$db->query("DROP TABLE {$db->prefix}notes");	
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
    $routes['myOmekaPosterAction'] = array('posters/:action', array('controller'=>'poster'));
    $routes['myOmekaPosterActionId'] = array('posters/:action/:id', array('controller'=>'poster'));
    $routes['myOmekaPosterBrowse'] = array('posters/browse/:page', array('controller'=>'poster', 'action'=>'browse', 'page'=>1));
    $routes['myOmekaAddTag'] = array('tags/add', array('controller'=>'tag', 'action'=>'add'));
    $routes['myOmekaTagDelete'] = array('tags/delete/:tag_id/:item_id', array('controller'=>'tag', 'action'=>'delete'));
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

function my_omeka_css($request)
{
    // Don't output the myomeka.css file on pages made by other plugins.
    // This may be a bug that needs to be fixed in the next version of Omeka.
    if (in_array($request->getModuleName(), array('my-omeka', 'default', ''))) {
        echo "<link rel=\"stylesheet\" media=\"screen\" href=\"".css('myomeka')."\" />";
    }
}

/**
 * This allows the user to pass arbitrary parameters in the query string when 
 * browsing items so that we only retrieve items that were tagged using MyOmeka.
 * 
 * @return void
 **/
function my_omeka_show_only_my_items($select, $params)
{
    $request = Zend_Controller_Front::getInstance()->getRequest();
    
	if( ($user = current_user()) and ($myTagId = (int)$request->getParam('myTag'))) {
		$entity_id = (int) $user->entity_id;
        $db = get_db();
        
        // Join against the taggings table to only select items that have been
        // tagged using the MyOmeka interface.
        $select->joinInner(array('my_tg'=>$db->Taggings), 'my_tg.relation_id = i.id', array());
        $select->where('my_tg.type = "' . MYOMEKA_TAG_TYPE . '" AND my_tg.tag_id = ?', $myTagId);
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
        $html .= my_omeka_items_show_navigation();
        $html .= '</div>';
	}
	return $html;
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
 * Call this function in your public themes to allow users to add and remove tags.
 */
function my_omeka_add_tags($item)
{
    $user = current_user();
    
    // Select MyomekaTag tags.
    $tagSelect = get_db()->getTable('Tag')->getSelectForFindBy(array('user'=>$user->id, 'type'=>'MyomekaTag'));
    
    // A hackaround for retrieving MyOmeka tags.
    $tagSelect->where('tg.relation_id = ?', $item->id);
    
    $tags = get_db()->getTable('Tag')->fetchObjects($tagSelect);
    common("add-tags", compact("item","tags"));
}

function my_omeka_items_show_navigation()
{
    $helpUrl = uri(array('action'=>'help'), 'myOmekaAction');
    $dashboardUrl = uri(array(), 'myOmekaDashboard');
?>
    <ul class="navigation"><li><a href="<?php echo $helpUrl; ?>" class="myomeka-help-link">Help</a></li>
    <li><a class="dashboard-link" href="<?php echo $dashboardUrl; ?>">Go to My Dashboard</a></li></ul>

<?php
}

function poster_icon_html() 
{
    $html = item_thumbnail();
    if (!$html) {
        $html = "<img alt='no image available' src='".img('noThumbnail.png')."'/>";
    }
    return $html;
}

function my_omeka_user_status() 
{
	$user = current_user();
	if ($user) {
		$html = "<p>logged in as <a href=\"" . uri(array(), 'myOmekaDashboard') . "\">$user->username</a> | <a href=\"" . uri(array('action'=>'logout', 'controller'=>'users'), 'default') . "\">Logout</a></p>";
	} else {
		$html = '<p><a href="' . uri(array(), 'myOmekaDashboard') . '">Login</a> | <a href="' . uri(array('action'=>'register'), 'myOmekaAction') . '">Register</a></p>';
	}
	return $html;
}

function my_omeka_clean_path($path)
{
	return rtrim($path, '/ ') . '/';
}

function my_omeka_config($post) 
{
	set_option('my_omeka_page_path', my_omeka_clean_path($post['my_omeka_page_path']));
	set_option('my_omeka_page_title', $post['my_omeka_page_title']);
	set_option('my_omeka_disclaimer', $post['my_omeka_disclaimer']);
	
	//if the page path is empty then make it the default page path
	if (trim(get_option('my_omeka_page_path')) == '') {
		set_option('my_omeka_page_path', my_omeka_clean_path(MY_OMEKA_PAGE_PATH));
	}

	if(get_option('my_omeka_page_title') == '') {
		set_option('my_omeka_page_title', MY_OMEKA_PAGE_TITLE);
	}
	$requireTOS = (strtolower($post['my_omeka_require_terms_of_service']) == 'checked') ? 1 : 0;
	set_option('my_omeka_require_terms_of_service', $requireTOS);
}

function my_omeka_config_form() 
{
	include "config_form.php";       	
}

/**
 * Define the ACL.
 * 
 * @param Omeka_Acl
 */
function my_omeka_setup_acl($acl)
{
    $acl->addRole(new Zend_Acl_Role(MYOMEKA_USER_ROLE));
    // All logged in users have permission to delete their own posters.
    // As of 0.10, admin & super are automatically given new permissions, so
    // they will be able to edit and delete other users' posters by default.
    // 
    // Note that privileges must be 'editAny' instead of 'edit' b/c the latter
    // will control all access to the editAction, whereas we want to allow partial
    // access based on criteria such as ownership.
    $acl->loadResourceList(array('MyOmeka_Poster'=>array('editAny', 'deleteAny')));
}

function my_omeka_add_controller_plugin()
{
    require_once 'MyOmekaControllerPlugin.php';
    Zend_Controller_Front::getInstance()->registerPlugin(new MyOmekaControllerPlugin);
}

function my_omeka_xss_filter($request, $purifier)
{
    if ($request->getModuleName() == 'my-omeka') {
        if ($request->getActionName() == 'save') {
            $post = $request->getPost();
            foreach ($post as $key => $value) {
                // Filter the description, annotation- and itemID- fields.
                switch (true) {
                    case $key == 'description':
                        $post['description'] = $purifier->purify($value);
                        break;
                    case strstr($key, 'annotation'):
                        $post[$key] = $purifier->purify($value);
                        break;
                }
            }
            $request->setPost($post);
        }
    }
}

function my_omeka_get_note_for_item($item)
{
    $user = current_user();
    return get_db()->getTable('MyOmekaNote')->findByUserIdAndItemId($user->id, $item->id);
}

/* 
 * Since, MyOmeka hacks the record type for taggings from 'Item' to MYOMEKA_TAG_TYPE, 
 * the core will not automatically remove MYOMEKA_TAG_TYPE taggings when an Item is deleted.
 * So we need to delete MyOmeka taggings when items are deleted.  Hence, this is another hack.  
 * All of this should be removed when we remake MyOmeka for collaborative tagging between users.
**/
function my_omeka_delete_myomeka_taggings($item) 
{

    $taggings = get_db()->getTable('Taggings')->findBySql(
                'relation_id = ? AND type = ?', 
                array($item->id, MYOMEKA_TAG_TYPE));
    
    foreach ($taggings as $tagging) {
        $tagging->delete();
    }
}