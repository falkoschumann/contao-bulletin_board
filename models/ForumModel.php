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

use Contao\ModuleEventReader;


/**
 * Class ForumModel manages access to forums and forum categories.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 * @property int              id
 * @property int              pid             reference the parent ForumModel
 * @property int              tstamp
 * @property int              sorting
 * @property string           type            the value <em>category</em>, <em>forum</em> or <em>link</em>
 * @property int              jumpTo          reference PageModel
 * @property string           url             link URL if type is <em>link</em>
 * @property string           name
 * @property string           description
 * @property string           alias
 * @property boolean          published
 * @property int              topics
 * @property int              posts
 * @property int              lastPost        reference PostModel
 * @property int              lastPoster      reference MemberModel
 * @property string           lastPosterName  empty if <code>lastPoster</code> is set
 * @property-read ForumModel  relatedParentForum
 * @property-read PageModel   relatedJumpTo
 * @property-read PostModel   relatedLastPost
 * @property-read MemberModel relatedLastPoster
 */
class ForumModel extends \Model
{

	/**
	 * Name of the table.
	 *
	 * @var string
	 */
	protected static $strTable = 'tl_bb_forum';


	/**
	 * Return an object property
	 *
	 * @param string $strKey The property key
	 *
	 * @return mixed|null The property value or null
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'relatedParentForum':
				return $this->getRelated('pid');
			case 'relatedJumpTo':
				return $this->getRelated('jumpTo');
			case 'relatedLastPost':
				return $this->getRelated('lastPost');
			case 'relatedLastPoster':
				return $this->getRelated('lastPoster');
		}
		return parent::__get($strKey);
	}


	/**
	 * Find all published forums by their parent IDs
	 *
	 * @param array $arrPids    An array of Forum IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return Collection|null A collection of models or null if there are no forums
	 */
	public static function findPublishedForumsByPids($arrPids, array $arrOptions = array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.pid, $t.sorting";
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}


	/**
	 * Find the parent forums of a forum
	 *
	 * @param integer $intId The forum's ID
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no parent forums
	 */
	public static function findParentForumsById($intId)
	{
		$objForums = \Database::getInstance()->prepare("SELECT *, @pid:=pid FROM tl_bb_forum WHERE id=?" . str_repeat(" UNION SELECT *, @pid:=pid FROM tl_bb_forum WHERE id=@pid", 9))->execute($intId);

		if ($objForums->numRows < 1)
		{
			return null;
		}

		return \Model\Collection::createFromDbResult($objForums, 'tl_bb_forum');
	}
}
