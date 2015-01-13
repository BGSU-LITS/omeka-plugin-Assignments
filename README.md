omeka-plugin-Assignments
========================
Omeka plugin to create a new student role and allow assignment of students to exhibits. The student role will not be able to create or delete exhibits. Students will also not be allowed to edit an exhibit unless they have been assigned to it.

An new item will be added to the admin interface menu, named Assignments. Both super and admin users will be able to use this interface to assign a student to an exhibit, or remove an exhisiting assignment. 

## Installing Releases
Released versions of this plugin are [available for download](https://github.com/BGSU-LITS/omeka-plugin-Assignments/releases). You may extract the archive to your Omeka plugin directory. [Installing the plugin in Omeka.](http://omeka.org/codex/Managing_Plugins_2.0)

## Installing Source Code
You will need to place the source code within a directory named CentralAuth in your Omeka plugin directory. Then, you need to use [Composer](http://getcomposer.org/) to execute the following command from the Central Auth directory: 

`composer install` 

After that, if you update the source code to a newer version, execute the following command: 

`composer update`

## Development
This plugin was developed by the [Bowling Green State University Libraries](http://www.bgsu.edu/library.html). Development is [hosted on GitHub](https://github.com/BGSU-LITS/omeka-plugin-Assignments).
