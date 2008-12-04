<?php 
head(); 
echo js('dashboard');
?>

<h1>Poster Administration (<?php echo $totalPosters; ?> posters)</h1>

<div id="primary">
    <p>Posters created by users are listed below.</p>
    
    <div class="pagination">
    <?php echo pagination_links(array('scrolling_style' => null, 
                                         'page_range'      => null, 
                                         'total_results'   => $totalPosters, 
                                         'page'            => $page, 
                                         'per_page'        => $perPage)) ?>
    </div>
    
    <?php if(count($posters)): ?>
        <table>
            <tr>
                <th>Poster name</th>
                <th>Creator</th>
                <th>Date modified</th>
                <th>Operations</th>
            </tr>
            <?php foreach ($posters as $poster): ?>
                <tr>
                    <td><?php echo htmlspecialchars($poster->title); ?></td>
                    <td><?php echo $poster->User->username; ?></td>
                    <td><?php echo $poster->date_modified; ?></td>
                    <td>
                        <a href="<?php echo public_uri(array('action'=>'show','id'=>$poster->id), 'myOmekaPosterActionId'); ?>">view</a> 
                        <a href="<?php echo uri(array('action'=>'edit','id'=>$poster->id), 'myOmekaPosterActionId'); ?>">edit</a> 
                        <a href="<?php echo uri(array('action'=>'delete','id'=>$poster->id), 'myOmekaPosterActionId'); ?>" class="myomeka-delete-poster-link">delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        There are no posters.
    <?php endif; ?>
</div>
<?php foot(); ?>