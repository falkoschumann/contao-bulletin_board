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
 * Class TopicModel manages access to topics.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 * @property int               id
 * @property int               pid             reference ForumModel
 * @property int               tstamp
 * @property string            title
 * @property int               views
 * @property int               replies
 * @property int               firstPost       reference PostModel
 * @property int               firstPoster     reference MemberModel
 * @property string            firstPosterName empty if <code>firstPoster</code> is set
 * @property int               lastPost        reference PostModel
 * @property int               lastPoster      reference MemberModel
 * @property string            lastPosterName  empty if <code>lastPoster</code> is set
 * @property-read ForumModel   relatedForum
 * @property-read PostModel    relatedFirstPost
 * @property-read MemberModel  relatedFirstPoster
 * @property-read PostModel    relatedLastPost
 * @property-read MemberModel  relatedLastPoster
 */
class TopicModel extends \Model
{

	/**
	 * Name of the table
	 *
	 * @var string
	 */
	protected static $strTable = 'tl_bb_topic';


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
			case 'relatedForum':
				return $this->getRelated('pid');
			case 'relatedFirstPost':
				return $this->getRelated('firstPost');
			case 'relatedFirstPoster':
				return $this->getRelated('firstPoster');
			case 'relatedLastPost':
				return $this->getRelated('lastPost');
			case 'relatedLastPoster':
				return $this->getRelated('lastPoster');
		}
		return parent::__get($strKey);
	}


	/**
	 * Find all topics by forum ID
	 *
	 * @param int   $forumId    forum ID
	 * @param array $arrOptions An optional options array
	 * @return Collection|null A collection of models or null if there are no topics
	 */
	public static function findTopicsByForumId($forumId, array $arrOptions = array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=$forumId");

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.tstamp DESC";
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}
}
