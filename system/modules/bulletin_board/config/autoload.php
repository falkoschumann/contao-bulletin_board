<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package BulletinBoard
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Muspellheim\BulletinBoard'
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes

	// Models

	// Modules
	'Muspellheim\BulletinBoard\ModuleBulletinBoard' => 'system/modules/bulletin_board/modules/ModuleBulletinBoard.php'
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_bulletin_board' => 'system/modules/bulletin_board/templates/modules'
));
