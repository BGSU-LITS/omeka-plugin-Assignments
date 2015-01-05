<?php
/**
 * Omeka Assignments Plugin: Assignment Record
 *
 * @author John Kloor <kloor@bgsu.edu>
 * @copyright 2015 Bowling Green State University Libraries
 * @license MIT
 */

/**
 * Omeka Assignments Plugin: Assignment Record Class
 *
 * @package Assignments
 */
class Assignment extends Omeka_Record_AbstractRecord implements Zend_Acl_Resource_Interface
{
    /**
     * @var int ID of the assignment.
     */
    public $id;

    /**
     * @var int ID of the User being assigned.
     */
    public $user_id;

    /**
     * @var int Id of the Exhibit being assigned.
     */
    public $exhibit_id;

    /**
     * @var array Map related records to the methods that get those records.
     */
    protected $_related = array(
        'User' => 'getUser',
        'Exhibit' => 'getExhibit'
    );

    /**
     * Get the related User record for this Assignment.
     *
     * @return object An object repressenting the User in the assignment.
     */
    public function getUser()
    {
        return $this->getTable('User')->find($this->user_id);
    }

    /**
     * Get the related Exhibit record for this Assignment.
     *
     * @return object An object repressenting the Exhibit in the assignment.
     */
    public function getExhibit()
    {
        return $this->getTable('Exhibit')->find($this->exhibit_id);
    }

    /**
     * Get the routing parameters to this record.
     *
     * @param $action string The action to get the routing parameters for.
     *
     * @return array Routing array for the "index" controller.
     */
    public function getRecordUrl($action = 'show')
    {
        return array(
            'module' => 'assignments',
            'controller' => 'index',
            'action' => $action,
            'id' => $this->id
        );
    }

    /**
     * Returns the resource identifier for this record.
     *
     * @return string The "Assignment" resource.
     */
    public function getResourceId()
    {
        return 'Assignment';
    }


    /**
     * Template method for defining record validation rules.
     *
     * The record must have a numeric user_id and exhibit_id.
     */
    protected function _validate()
    {
        if (empty($this->user_id) || !is_numeric($this->user_id)) {
            $this->addError('user_id', __('Must be specified.'));
        }

        if (empty($this->exhibit_id) || !is_numeric($this->exhibit_id)) {
            $this->addError('exhibit_id', __('Must be specified.'));
        }
    }
}
