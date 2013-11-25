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
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes

	// Models
	'BbForumModel' => 'system/modules/bulletin_board/models/BbForumModel.php',

	// Modules
	'ModuleBulletinBoard' => 'system/modules/bulletin_board/modules/ModuleBulletinBoard.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_bulletin_board' => 'system/modules/bulletin_board/templates/modules',
	'bb_board_category' => 'system/modules/bulletin_board/templates/board',
	'bb_board_forum' => 'system/modules/bulletin_board/templates/board',
));
