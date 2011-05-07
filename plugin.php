<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2011
 * @package MyOmeka
 */

define('MY_OMEKA_PLUGIN_DIR', dirname(__FILE__));
define('MY_OMEKA_HELPERS_DIR', MY_OMEKA_PLUGIN_DIR
                             . DIRECTORY_SEPARATOR
                             . 'helpers');

require_once MY_OMEKA_PLUGIN_DIR . DIRECTORY_SEPARATOR
           . 'MyOmekaPlugin.php';
require_once MY_OMEKA_HELPERS_DIR . DIRECTORY_SEPARATOR
           . 'ThemeHelpers.php';

new MyOmekaPlugin;