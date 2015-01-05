<?php
/**
 * Omeka Assignments Plugin: Admin Interface Browse View
 *
 * @author John Kloor <kloor@bgsu.edu>
 * @copyright 2015 Bowling Green State University Libraries
 * @license MIT
 * @package Assignments
 */

echo head(array(
    'title' => __('Browse Assignments'),
    'bodyclass' => 'assignments browse'
));

echo flash();
?>

<a class="small green button" href="<?php echo url('assignments/index/add'); ?>">
    <?php echo __('Add an Assignment'); ?>
</a>

<?php if ($total_results): ?>
    <?php echo pagination_links(); ?>
    <table id="assignments">
        <thead>
            <tr>
                <?php
                echo browse_sort_links(
                    array(
                        __('Student') => 'user.name',
                        __('Email') => 'user.email',
                        __('Exhibit') => 'exhibit.title'
                    ),
                    array(
                        'link_tag' => 'th scope="col"',
                        'list_tag' => ''
                    )
                );
                ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assignments as $key => $assignment): ?>
                <tr class="<?php echo $key % 2 ? 'even' : 'odd'; ?>">
                    <td>
                        <?php echo html_escape($assignment->User->name); ?>
                        <ul class="action-links group">
                            <li>
                                <a class="delete" href="<?php echo record_url($assignment, 'delete-confirm'); ?>">
                                    <?php echo __('Delete'); ?>
                                </a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <a href="mailto:<?php echo html_escape($assignment->User->email); ?>">
                            <?php echo html_escape($assignment->User->email); ?>
                        </a>
                    </td>
                    <td><?php echo html_escape($assignment->Exhibit->title); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo pagination_links(); ?>
<?php else: ?>
    <p><?php echo __('No students have been assigned to exhibits.'); ?></p>
<?php endif; ?>

<?php
echo foot();
