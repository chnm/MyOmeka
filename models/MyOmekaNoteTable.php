<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2011
 * @package MyOmeka
 * @subpackage Models
 */

/**
 * Table for MyOmekaNote objects.
 *
 * @package MyOmeka
 * @subpackage Models
 */
class MyOmekaNoteTable extends Omeka_Db_Table
{
    public function findByUserIdAndItemId($userId, $itemId)
    {
        $select = $this->getSelect()->where('user_id = ?', $userId)
            ->where('item_id = ?', $itemId);

        return $this->fetchObject($select);
    }
}