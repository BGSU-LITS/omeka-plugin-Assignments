<?php
/**
 * Omeka Assignments Plugin: Index Controller
 *
 * @author John Kloor <kloor@bgsu.edu>
 * @copyright 2015 Bowling Green State University Libraries
 * @license MIT
 */

/**
 * Omeka Assignments Plugin: Index Controller Class
 *
 * @package Assignments
 */
class Assignments_IndexController extends Omeka_Controller_AbstractActionController
{
    /**
     * Initialize object.
     *
     * Set the default model for this controller to assignment. Set the
     * number of records to browse per page to the standard administrative
     * interface setting.
     */
    public function init()
    {
        $this->_helper->db->setDefaultModelName('Assignment');

        $this->_browseRecordsPerPage = get_option('per_page_admin');
    }

    /**
     * Add an instance of a record to the database.
     *
     * Adds a form to the view allowing the selection of a user and an exhibit.
     */
    public function addAction()
    {
        // Create a new admin form for the assignment record.
        $form = new Omeka_Form_Admin(array('type' => 'Assignment'));

        // Add a field to select a user of the "student" role.
        $form->addElementToEditGroup(
            'select',
            'user_id',
            array(
                'id' => 'assignment-user-id',
                'label' => 'Student',
                'required' => true,
                'multiOptions' => get_table_options(
                    'User',
                    null,
                    array('sort_field' => 'name', 'role' => 'student')
                )
            )
        );

        // Add a field to select an exhibit.
        $form->addElementToEditGroup(
            'select',
            'exhibit_id',
            array(
                'id' => 'assignment-exhibit-id',
                'label' => 'Exhibit',
                'required' => true,
                'multiOptions' => get_table_options(
                    'Exhibit',
                    null,
                    array('sort_field' => 'title')
                )
            )
        );

        // Add the form to the view.
        $this->view->form = $form;

        // Perform standard add action code.
        parent::addAction();
    }

    /**
     * Return the success message for adding a record.
     *
     * @param Omeka_Record_AbstractRecord $record
     * @return string Note that a user has been assigned to an exhibit.
     */
    protected function _getAddSuccessMessage($record)
    {
        return __(
            '%s has been assigned to the %s exhibit.',
            $record->User->name,
            $record->Exhibit->title
        );
    }

    /**
     * Return the success message for deleting a record.
     *
     * @param Omeka_Record_AbstractRecord $record
     * @return string Note that a user has been deleted from an exhibit.
     */
    protected function _getDeleteSuccessMessage($record)
    {
        return __(
            '%s has been deleted from the %s exhibit.',
            $record->User->name,
            $record->Exhibit->title
        );
    }

    /**
     * Return the delete confirm message for deleting a record.
     *
     * @param Omeka_Record_AbstractRecord $record
     * @return string Note that a user will be deleted from an exhibit.
     */
    protected function _getDeleteConfirmMessage($record)
    {
        return __(
            '%s will be deleted from the %s exhibit.',
            $record->User->name,
            $record->Exhibit->title
        );
    }
}
