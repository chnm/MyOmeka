<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2011
 * @package MyOmeka
 * @subpackage Models
 */

/**
 * Record for individual MyOmeka notes.
 *
 * @package MyOmeka
 * @subpackage Models
 */
class MyOmekaNote extends Omeka_Record {
    public $text = '';
    public $user_id;
    public $item_id;
    public $modified;

    protected function _validate()
    {
        if (empty($this->item_id)) {
            $this->addError('item_id', 'MyOmeka note requires an item id.');
        }

        if (empty($this->user_id)) {
            $this->addError('user_id', 'MyOmeka note requires a user id.');
        }
    }

    /**
     * Set modified timestamp for the collection.
     */
    protected function beforeUpdate()
    {
        $this->modified = Zend_Date::now()->toString(self::DATE_FORMAT);
    }
}