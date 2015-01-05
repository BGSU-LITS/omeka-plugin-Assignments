<?php
/**
 * Omeka Assignments Plugin: Admin Interface Add View
 *
 * @author John Kloor <kloor@bgsu.edu>
 * @copyright 2015 Bowling Green State University Libraries
 * @license MIT
 * @package Assignments
 */

echo head(array(
    'title' => __('Add an Assignment'),
    'bodyclass' => 'assignments add'
));

echo flash();
echo $form;
echo foot();
