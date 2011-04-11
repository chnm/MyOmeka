<?php
$pageTitle = 'Browse Posters';
head(array('title'=>$pageTitle));

if ($totalPosters == 1) {
    $pageTitle .= ' (' . $totalPosters . ' poster)';
} else {
    $pageTitle .= ' (' . $totalPosters . ' posters)';
}
?>

<h1><?php echo $pageTitle; ?></h1>
<div id="primary">
    <p>Posters created by users are listed below.</p>
    
    <div class="pagination">
    <?php echo pagination_links(array('scrolling_style' => null, 
                                         'page_range'      => null, 
                                         'total_results'   => $totalPosters, 
                                         'page'            => $page, 
                                         'per_page'        => $perPage)) ?>
    </div>
    
    <?php if (count($posters)): ?>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Poster name</th>
                <th>Creator</th>
                <th>Date Modified</th>
                <th>Preview</th>
                <th>Edit?</th>
                <th>Delete?</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($posters as $poster): ?>
                <tr>
                    <td><?php echo html_escape($poster->id); ?></td>
                    <td><?php echo html_escape($poster->title); ?></td>
                    <td><?php echo html_escape($poster->User->username); ?></td>
                    <td><?php echo html_escape($poster->date_modified); ?></td>
                    <td>
                        <a href="<?php echo html_escape(public_uri(array('action'=>'show','id'=>$poster->id), 'myOmekaPosterActionId')); ?>">[Preview]</a> 
                    </td>
                    <td>
                        <a href="<?php echo html_escape(uri(array('action'=>'edit','id'=>$poster->id), 'myOmekaPosterActionId')); ?>" class="edit">Edit</a> 
                    </td>
                    <td>
                        <?php echo delete_button(uri(array('action'=>'delete-confirm','id'=>$poster->id), 'myOmekaPosterActionId')); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        There are no posters.
    <?php endif; ?>
</div>
<?php foot();
