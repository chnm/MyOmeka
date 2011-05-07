<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2011
 * @package Contribution
 */

/**
 * MyOmeka plugin class
 *
 * @package MyOmeka
 */
class MyOmekaPlugin
{
    private static $_hooks = array(
        'install',
        'uninstall',
        'upgrade',
        'config',
        'config_form',
        'define_routes',
        'public_append_to_items_show'
    );

    public static $options = array(
        'my_omeka_page_path'    => 'my-omeka',
        'my_omeka_page_title'   => 'My Omeka'
    );

    private $_db;

    /**
     * Initializes instance properties and hooks the plugin into Omeka.
     */
    public function __construct()
    {
        $this->_db = get_db();
        self::addHooksAndFilters();
    }

    /**
     * Centralized location where plugin hooks and filters are added
     */
    public function addHooksAndFilters()
    {
        foreach (self::$_hooks as $hookName) {
            $functionName = Inflector::variablize($hookName);
            add_plugin_hook($hookName, array($this, $functionName));
        }
    }

    /**
     * Install the plugin.
     */
    public function install()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->_db->prefix}my_omeka_notes` (
                        `id` BIGINT UNSIGNED NOT NULL auto_increment PRIMARY KEY,
                        `text` TEXT NOT NULL,
                        `user_id` BIGINT UNSIGNED NOT NULL,
                        `item_id` BIGINT UNSIGNED NOT NULL,
                        `modified` TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
                    ) ENGINE = MYISAM;";
        $this->_db->query($sql);

        // Add options and default values
        foreach (self::$options as $option => $value) {
            set_option($option, $value);
        }
    }

    /**
     * Uninstall the plugin
     */
    public function uninstall() 
    {
        $sql = "DROP TABLE IF EXISTS `{$this->_db->prefix}my_omeka_notes`";
        $this->_db->query($sql);

        // Delete all the Contribution options
        foreach (self::$options as $option) {
            delete_option($option);
        }
    }

    /**
     * Upgrade the plugin if necessary.
     *
     * @todo Remove unneeded options ('disclaimer' and 'terms of service' used
     * in previous version.
     */
    public function upgrade($oldVersion, $newVersion)
    {
        // Catch-all for pre-1.0 versions
        if (version_compare($oldVersion, '1.0-dev', '<=')) {
            $sql = "RENAME TABLE `{$this->_db->prefix}notes` TO `{$this->_db->prefix}my_omeka_notes`";
            $this->_db->query($sql);

            // Rename the 'note' column to 'text'.
            $sql = "ALTER TABLE `{$this->_db->prefix}my_omeka_notes` CHANGE COLUMN `note` `text` TEXT NOT NULL";
            $this->_db->query($sql);

            // Rename the 'date_modified' column to 'modified' for consistency with other Omeka records.
            $sql = "ALTER TABLE `{$this->_db->prefix}my_omeka_notes` CHANGE COLUMN `date_modified` `modified` TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP";
            $this->_db->query($sql);
        }
    }

    public function config($post)
    {
        $pagePath = !empty($post['my_omeka_page_path']) ? trim($post['my_omeka_page_path']) : self::$options['my_omeka_page_path'];
        set_option('my_omeka_page_path', $pagePath);

        $pageTitle = !empty($post['my_omeka_page_title']) ? trim($post['my_omeka_page_title']) : self::$options['my_omeka_page_title'];
        set_option('my_omeka_page_title', $pageTitle);
    }

    public function configForm()
    {
        $pagePath = get_option('my_omeka_page_path');
        $pageTitle = get_option('my_omeka_page_title');

        include 'forms/config_form.php';
    }

    /**
     * Define the routes.
     */
    public function defineRoutes($router)
    {
        $router->addRoute(
            'my_omeka_default_route',
            new Zend_Controller_Router_Route(
                'my-omeka/:action',
                array(
                    'module'        => 'my-omeka',
                    'controller'    => 'index',
                    'action'        => 'index'
                    )
            )
        );
        
        if ($bp = get_option('my_omeka_page_path')) {
            $router->addRoute(
                'my_omeka_custom_route',
                new Zend_Controller_Router_Route("{$bp}/:action/*",
                    array('module'     => 'my-omeka',
                          'controller' => 'index',
                          'action'     => 'index')));
        }
    }

    public function publicAppendToItemsShow()
    {
        if ($user = current_user()):
    ?>
<div id="my-omeka-form">
    <h2><?php echo get_option('my_omeka_page_title'); ?></h2>
    <form action="<?php echo uri('my-omeka/save-item-data'); ?>" method="post">
        <p>
            <label for="my_omeka_note_text">Notes</label><br />
            <textarea rows="10" cols="40" name="my_omeka_note_text"><?php echo my_omeka_get_user_note_for_item(); ?></textarea>
        </p>
        <p>
            <label for="my_omeka_tags">Tags</label><br />
            <input type="text" class="textinput" name="my_omeka_tags" value="<?php echo tag_string(current_user_tags_for_item()); ?>" />
        </p>
        <input type="hidden" name="item_id" value="<?php echo item('id'); ?>">
        <p><input type="submit" class="submit" value="Save"></p>
    </form>
</div>
<?php
    endif;
    }
}