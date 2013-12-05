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
	'Muspellheim\BulletinBoard',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Muspellheim\BulletinBoard\BulletinBoard'       => 'system/modules/bulletin_board/classes/BulletinBoard.php',
	'Muspellheim\BulletinBoard\BreadcrumbParser'    => 'system/modules/bulletin_board/classes/BreadcrumbParser.php',
	'Muspellheim\BulletinBoard\BoardParser'         => 'system/modules/bulletin_board/classes/BoardParser.php',
	'Muspellheim\BulletinBoard\ForumParser'         => 'system/modules/bulletin_board/classes/ForumParser.php',
	'Muspellheim\BulletinBoard\TopicParser'         => 'system/modules/bulletin_board/classes/TopicParser.php',
	'Muspellheim\BulletinBoard\NewTopicParser'      => 'system/modules/bulletin_board/classes/NewTopicParser.php',

	// Models
	'Muspellheim\BulletinBoard\BbForumModel'        => 'system/modules/bulletin_board/models/BbForumModel.php',
	'Muspellheim\BulletinBoard\BbPostModel'         => 'system/modules/bulletin_board/models/BbPostModel.php',
	'Muspellheim\BulletinBoard\BbTopicModel'        => 'system/modules/bulletin_board/models/BbTopicModel.php',

	// Modules
	'Muspellheim\BulletinBoard\ModuleBulletinBoard' => 'system/modules/bulletin_board/modules/ModuleBulletinBoard.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_bulletin_board' => 'system/modules/bulletin_board/templates/modules',
	'bb_board'           => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_board_category'  => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_board_forum'     => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_breadcrumb'      => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_forum'           => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_forum_subforum'  => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_forum_topic'     => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_topic'           => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_topic_new'       => 'system/modules/bulletin_board/templates/bulletin_board',
	'bb_topic_post'      => 'system/modules/bulletin_board/templates/bulletin_board',
));
