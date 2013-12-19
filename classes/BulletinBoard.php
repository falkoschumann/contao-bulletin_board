<?php

/**
 * Bulletin Board for Contao
 *
 * Copyright (c) 2013, Falko Schumann
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *   Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 *   Redistributions in binary form must reproduce the above copyright notice, this
 *   list of conditions and the following disclaimer in the documentation and/or
 *   other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   BulletinBoard
 * @author    Falko Schumann
 * @license   BSD-2-Clause
 * @copyright Falko Schumann 2013
 */


namespace Muspellheim\BulletinBoard;


/**
 * Class BulletinBoard.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 */
class BulletinBoard extends \Frontend
{

	/**
	 * @param BbForumModel $objForum
	 * @return string
	 */
	public static function generateForumLink($objForum)
	{
		$itemPrefix = $GLOBALS['TL_CONFIG']['useAutoItem'] ? '/' : '/items/';
		$item = static::isAliasSetAndEnabled($objForum) ? $objForum->alias : $objForum->id;
		return static::generateFrontendUrl($GLOBALS['objPage']->row(), $itemPrefix . $item);
	}


	/**
	 * @param object $objForum
	 * @return boolean
	 */
	private static function isAliasSetAndEnabled($objForum)
	{
		return $objForum->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias'];
	}

	/**
	 * @param BbPostModel $objPost
	 * @return string
	 */
	public static function generatePostLink($objPost)
	{
		$objTopic = $objPost->pid;
		$objForum = $objTopic->pid;
		$itemPrefix = $GLOBALS['TL_CONFIG']['useAutoItem'] ? '/' : '/items/';
		$item = static::isAliasSetAndEnabled($objForum) ? $objForum->alias : $objForum->id;
		return static::generateFrontendUrl($GLOBALS['objPage']->row(), $itemPrefix . $item);
	}
}
