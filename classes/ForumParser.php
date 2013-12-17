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
 * Class ForumParser render a forum.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 */
class ForumParser extends \Frontend
{

	/**
	 * @val BbForumModel
	 */
	private $objForum;


	/**
	 *
	 * @param BbForumModel $objForum
	 */
	public function __construct($objForum)
	{
		parent::__construct();
		$this->objForum = $objForum;
	}


	/**
	 * @return string
	 */
	public function parseForum()
	{
		$objTemplate = new \FrontendTemplate('bb_forum');
		$objTemplate->title = $this->objForum->title;
		$objTemplate->subforums = $this->parseSubforums(BbForumModel::findPublishedForumsByPids(array($this->objForum->id)));
		$objTemplate->topics = $this->parseTopics(BbTopicModel::findTopicsByForumId($this->objForum->id));
		if ($this->objForum->type == 'forum' && (BE_USER_LOGGED_IN || FE_USER_LOGGED_IN))
		{
			$objTemplate->newTopic = '<p><a href="' . $this->generateNewTopicLink() . '">' . $GLOBALS['TL_LANG']['MSC']['bb_new_topic'] . '</a></p>';
		}
		else
		{
			$objTemplate->newTopic = '';
		}

		$objTemplate->labelNoTopics = $GLOBALS['TL_LANG']['MSC']['bb_no_topics'];
		return $objTemplate->parse();
	}


	/**
	 * @param Collection $objForums collection of BbForumModel
	 * @return array array of string
	 */
	public function parseSubforums($objForums)
	{
		$limit = $objForums != null ? $objForums->count() : 0;
		$count = 0;
		$arrForums = array();
		while ($limit && $objForums->next())
		{
			$arrForums[] = $this->parseSubforum($objForums, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'));
		}
		return $arrForums;
	}


	/**
	 * @param BbForumModel $objForum
	 * @return string
	 */
	public function parseSubforum($objForum, $strClass = '')
	{
		$objTemplate = new \FrontendTemplate('bb_forum_subforum');
		$objTemplate->setData($objForum->row());
		$objTemplate->class = $strClass;
		$objTemplate->title = $objForum->title;
		$objTemplate->link = BulletinBoard::generateForumLink($objForum);
		$objTemplate->description = $objForum->description;
		return $objTemplate->parse();
	}


	/**
	 * @param Collection $objTopic collection of BbTopicModel
	 * @return array array of string
	 */
	public function parseTopics($objTopics)
	{
		$limit = $objTopics != null ? $objTopics->count() : 0;
		$count = 0;
		$arrTopics = array();
		while ($limit && $objTopics->next())
		{
			$arrTopics[] = $this->parseTopic($objTopics, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'));
		}
		return $arrTopics;
	}


	/**
	 * @param BbTopicModel $objTopic
	 * @return string
	 */
	public function parseTopic($objTopic, $strClass = '')
	{
		$objTemplate = new \FrontendTemplate('bb_forum_topic');
		$objTemplate->setData($objTopic->row());
		$objTemplate->class = $strClass;
		$objTemplate->title = $objTopic->subject;
		$objTemplate->link = $this->generateTopicLink($objTopic);
		return $objTemplate->parse();
	}


	/**
	 * @param object $objTopic
	 * @return string
	 */
	private function generateTopicLink($objTopic)
	{
		return $this->addToUrl('topic=' . $objTopic->id);
	}


	/**
	 * @return string
	 */
	private function generateNewTopicLink()
	{
		return $this->addToUrl('topic=new');
	}
}
