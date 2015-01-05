<?php
/**
 * Omeka Assignments Plugin: Assignment Assertion
 *
 * @author John Kloor <kloor@bgsu.edu>
 * @copyright 2015 Bowling Green State University Libraries
 * @license MIT
 */

/**
 * Omeka Assignments Plugin: Assignment Assertion Class
 *
 * @package Assignments
 */
class AssignmentAssertion implements Zend_Acl_Assert_Interface
{
    /**
     * Returns true if and only if the assertion conditions are met.
     *
     * @param Zend_Acl $acl The current ACL.
     * @param Zend_Acl_Role_Interface $role The user being tested.
     * @param Zend_Acl_Resource_Interface $resource The resource being tested.
     * @param string $privilege The privelage being tested.
     *
     * @return boolean True if the user has been assigned to the exhibit.
     */
    public function assert(
        Zend_Acl $acl,
        Zend_Acl_Role_Interface $role = null,
        Zend_Acl_Resource_Interface $resource = null,
        $privilege = null
    ) {
        if ($role instanceof User && $resource instanceof Exhibit) {
            $assignments = get_db()->getTable('Assignment')
                ->findBy(array(
                    'user_id' => $role->id,
                    'exhibit_id' => $resource->id
                ));

            if (!empty($assignments)) {
                return true;
            }
        }

        return false;
    }
}
