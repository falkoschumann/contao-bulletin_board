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
	'BoardParser'                     => 'system/modules/bulletin_board/classes/BoardParser.php',
	'ForumParser'                     => 'system/modules/bulletin_board/classes/ForumParser.php',

	// Models
	'BbForumModel'                    => 'system/modules/bulletin_board/models/BbForumModel.php',
	'BbTopicModel'                    => 'system/modules/bulletin_board/models/BbTopicModel.php',

	// Modules
	'ModuleBulletinBoard'             => 'system/modules/bulletin_board/modules/ModuleBulletinBoard.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_bulletin_board'              => 'system/modules/bulletin_board/templates/modules',
	'bb_board'                        => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_board_category'               => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_board_forum'                  => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_forum'                        => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_forum_subforum'               => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_forum_topic'                  => 'system/modules/bulletin_board/templates/bulletin_board',
));
