<?php 
head(); 
echo js('adminPosters');
?>
<div id="primary">
    <h1>Poster Administration</h1>
    <p>Posters created by users are listed below.</p>
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
                    <td><?php echo $poster->title; ?></td>
                    <td><?php echo $poster->username; ?></td>
                    <td><?php echo $poster->date_modified; ?></td>
                    <td>
                        <a href="<?php echo myomeka_get_path('/poster/view/'.$poster->id); ?>">view</a> 
                        <a href="<?php echo myomeka_get_path('poster/edit/'.$poster->id.'?return=admin'); ?>">edit</a> 
                        <a href="<?php echo myomeka_get_path('poster/delete/'.$poster->id.'?return=admin'); ?>" class="myomeka-delete-poster-link">delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        There are no posters.
    <?php endif; ?>
</div>
<?php foot(); ?>