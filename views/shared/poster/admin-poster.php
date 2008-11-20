<?php 
head(); 
echo js('adminPosters');
?>

<h1>Poster Administration</h1>

<div id="primary">
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
                        <a href="<?php echo uri(array('action'=>'view','id'=>$poster->id), 'myOmekaPosterActionId'); ?>">view</a> 
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