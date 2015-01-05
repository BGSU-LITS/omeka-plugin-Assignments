<?php
/**
 * Omeka Assignments Plugin: Assignment Table
 *
 * @author John Kloor <kloor@bgsu.edu>
 * @copyright 2015 Bowling Green State University Libraries
 * @license MIT
 */

/**
 * Omeka Assignments Plugin: Assignment Table Class
 *
 * @package Assignments
 */
class Table_Assignment extends Omeka_Db_Table
{
    /**
     * Apply default column-based sorting for a table.
     *
     * @param Omeka_Db_Select $select Select statement to modify.
     * @param string $sortField Field to sort on.
     * @param string $sortDir Direction to sort.
     */
    public function applySorting($select, $sortField, $sortDir)
    {
        // Perform standard sorting by default.
        parent::applySorting($select, $sortField, $sortDir);

        // Split sort field into table name and field name if possible.
        list($table, $field) = explode('.', $sortField, 2);

        // Check that the table is either for Exhibit or User records.
        if (in_array($table, array('exhibit', 'user'))) {
            // If so, join to related records.
            $db = $this->getDb();

            $select->joinLeft(
                array($table => $db->{$table}),
                $table. '.id = assignments.'. $table. '_id',
                array()
            );

            // Sort by field in related record.
            $select->order("$sortField $sortDir");
        }
    }
}
