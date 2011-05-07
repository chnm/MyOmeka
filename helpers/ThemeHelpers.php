<?php

/**
 * Returns the note for a given item and user.
 *
 * @param Item
 * @param User
 * @return string MyOmekaNote note text.
 */
function my_omeka_get_user_note_for_item($user = null, $item = null)
{
    if (!$item) {
        $item = get_current_item();
    }

    if (!$user) {
        $user = current_user();
    }

    $note = get_db()->getTable('MyOmekaNote')->findByUserIdAndItemId($user->id, $item->id);

    return $note->text;
}