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
 * Class TopicParser render a topic.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 */
class TopicParser extends BulletinBoard
{

	/**
	 * @var BbTopicModel
	 */
	private $objTopic;


	/**
	 *
	 * @param BbTopicModel $objTopic
	 */
	public function __construct($objTopic)
	{
		parent::__construct();
		$this->objTopic = $objTopic;
	}


	/**
	 * @return string
	 */
	public function parseTopic()
	{
		$objTemplate = new \FrontendTemplate('bb_topic');
		$objTemplate->subject = $this->objTopic->subject;
		$objTemplate->topic = $this->parsePost($this->objTopic, 'topic');
		$objTemplate->posts = $this->parsePosts(PostModel::findPostsByTopicId($this->objTopic->id));
		if (BE_USER_LOGGED_IN || FE_USER_LOGGED_IN)
		{
			$objTemplate->postReply = '<p><a href="' . $this->generatePostReplayLink() . '">' . $GLOBALS['TL_LANG']['MSC']['bb_post_reply'] . '</a></p>';
		}
		else
		{
			$objTemplate->postReply = '';
		}
		return $objTemplate->parse();
	}


	/**
	 * @param Collection $objPosts collection of PostModel
	 * @return array array of string
	 */
	public function parsePosts($objPosts)
	{
		$limit = $objPosts != null ? $objPosts->count() : 0;
		$count = 0;
		$arrForums = array();
		while ($limit && $objPosts->next())
		{
			$arrForums[] = $this->parsePost($objPosts, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'));
		}
		return $arrForums;
	}


	/**
	 * @param BbPostModel $objPost
	 * @return string
	 */
	public function parsePost($objPost, $strClass = '')
	{
		global $objPage;
		$member = \MemberModel::findByPk($objPost->author);
		$objTemplate = new \FrontendTemplate('bb_topic_post');
		$objTemplate->setData($objPost->row());
		$objTemplate->class = $strClass;
		$objTemplate->subject = $objPost->subject;
		$objTemplate->message = $objPost->message;
		$objTemplate->author = sprintf($GLOBALS['TL_LANG']['MSC']['bb_poster'], $member->username);
		$objTemplate->timestamp = \Date::parse($objPage->datimFormat, $objPost->tstamp);
		return $objTemplate->parse();
	}


	/**
	 * @return string
	 */
	private function generatePostReplayLink()
	{
		return '#';
	}
}
