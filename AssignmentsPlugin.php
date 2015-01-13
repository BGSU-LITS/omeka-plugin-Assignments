<?php
/**
 * Omeka Assignments Plugin
 *
 * @author John Kloor <kloor@bgsu.edu>
 * @copyright 2015 Bowling Green State University Libraries
 * @license MIT
 */

/**
 * Omeka Assignments Plugin: Plugin Class
 *
 * @package Assignments
 */
class AssignmentsPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Plugin hooks.
     */
    protected $_hooks = array(
        'install',
        'uninstall',
        'define_acl',
        'before_delete_user',
        'before_delete_exhibit',
        'exhibits_browse_sql'
    );

    /**
     * @var array Plugin filters.
     */
    protected $_filters = array(
        'admin_navigation_main'
    );

    /**
     * Plugin constructor.
     *
     * Requires class autoloader, and calls parent constructor.
     */
    public function __construct()
    {
        require 'vendor/autoload.php';
        parent::__construct();
    }

    /**
     * Hook to plugin installation.
     *
     * Creates table for the Assignment record to associate user with exhibit.
     */
    public function hookInstall()
    {
        $db = $this->_db;
        $db->query(
            "CREATE TABLE IF NOT EXISTS `{$db->Assignment}` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(10) unsigned NOT NULL,
                `exhibit_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `assignment` (`user_id`, `exhibit_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
        );
    }

    /**
     * Hook to plugin uninstallation.
     *
     * Drops table for the Assignment record.
     */
    public function hookUninstall()
    {
        $db = $this->_db;
        $db->query(
            "DROP TABLE IF EXISTS `{$db->Assignment}`;"
        );
    }

    /**
     * Hook to define ACL.
     *
     * Creates a new resource for the Assignment record. Creates a new role of
     * "student" based on "contributor", but limited to specific exhibits.
     *
     * @param array $args Provides "acl".
     */
    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];

        // Create new resource for Assignment record.
        $acl->addResource('Assignment');

        // Create new student role based upon contributor.
        $acl->addRole('student', 'contributor');

        // Deny students the ability to add, edit or delete an exhibit.
        $acl->deny(
            'student',
            'ExhibitBuilder_Exhibits',
            array('add', 'edit', 'delete')
        );

        // Allow students to edit exhibits they are assigned to.
        $acl->allow(
            'student',
            'ExhibitBuilder_Exhibits',
            array('edit'),
            new Assignments_AssignmentAssertion
        );

        // Allow students to view non-public exhibits. We'll limit the list to
        // those they are assigned to with hookExhibitsBrowseSql().
        $acl->allow(
            'student',
            'ExhibitBuilder_Exhibits',
            array('showNotPublic')
        );
    }

    /**
     * Hook an action to occur before a user is deleted.
     *
     * Drop assignments belonging to a user before that user is deleted.
     *
     * @param array $args Provides "record".
     */
    public function hookBeforeDeleteUser($args)
    {
        $user = $args['record'];

        $assignments = $this->_db->getTable('Assignment')
            ->findBy(array('user_id' => $user->id));

        foreach ($assignments as $assignment) {
            $assignment->delete();
        }
    }

    /**
     * Hook an action to occur before an exhibit is deleted.
     *
     * Drop assignments belonging to an exhibit before that exhibit is deleted.
     *
     * @param array $args Provides "record".
     */
    public function hookBeforeDeleteExhibit($args)
    {
        $exhibit = $args['record'];

        $assignments = $this->_db->getTable('Assignment')
            ->findBy(array('exhibit_id' => $exhibit->id));

        foreach ($assignments as $assignment) {
            $assignment->delete();
        }
    }

    /**
     * Hook to modify SQL used when browsing exhibits.
     *
     * If the current user is a student, limit which exhibits are shown to only
     * those that are public, or that the student has been assigned to.
     *
     * @param array $args Provides "select".
     */
    public function hookExhibitsBrowseSql($args)
    {
        // Get current user, and check that they are a student.
        $user = current_user();

        if ($user && $user->role == 'student') {
            // Get the IDs of all exhibits assigned to the user.
            $assignments = $this->_db->getTable('Assignment')
                ->findBy(array('user_id' => $user->id));

            foreach ($assignments as $assignment) {
                $ids[] = $assignment->exhibit_id;
            }

            // If there are no IDs, only select public exhibits, otherwise
            // select public exhibts or those assigned to the student.
            if (empty($ids)) {
                $args['select']->where('`public` = 1');
            } else {
                $args['select']->where(
                    "(`id` IN (?) OR `public` = 1)",
                    $ids
                );
            }
        }
    }

    /**
     * Filter the admin interface main navigagtion.
     *
     * Add a link to the Assignments controller.
     *
     * @param array $nav Admin interface main navigation.
     */
    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('Assignments'),
            'uri' => url('assignments'),
            'resource' => 'Assignment'
        );

        return $nav;
    }
}
