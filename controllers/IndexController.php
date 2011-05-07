<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2011
 * @package MyOmeka
 */

/**
 * MyOmeka controller
 *
 * @package MyOmeka
 */
class MyOmeka_IndexController extends Omeka_Controller_Action
{
    public function indexAction() {}

    /**
     * Saves favorites, notes, and tags for an item.
     */
    public function saveItemDataAction()
    {
        if (($user = $this->getCurrentUser())) {
            $userId = $user->id;
            $itemId = (int)$this->getRequest()->getPost('item_id');

            if (!$itemId) {
               throw new Exception('Item ID must be an integer!');
            }

            // Save notes.
            $note = $this->getTable('MyOmekaNote')->findByUserIdAndItemId($userId, $itemId);
            $noteText = $this->getRequest()->getPost('my_omeka_note_text');

            if (!empty($noteText)) {
               if (!$note) {
                   $note = new MyOmekaNote;
                   $note->user_id = $userId;
                   $note->item_id = $itemId;
               }

               $note->text = $noteText;
               $note->save();
            } else {
                // Delete empty notes from the db.
               if ($note instanceof MyOmekaNote) {
                   $note->delete();
               }
            }

            // Save tags.
            $tags = $this->getRequest()->getPost('my_omeka_tags');
            $item = $this->getTable('Item')->find($itemId);
            $item->applyTagString($tags, $user->Entity);

            $this->redirect->gotoRoute(array('controller'=>'items', 'action'=>'show', 'id'=>$itemId), 'id');
        }
    }
}