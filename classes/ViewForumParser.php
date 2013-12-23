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
 * Class ViewForumParser render a forum.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 */
class ViewForumParser extends BulletinBoard
{

	/**
	 * @val ForumModel
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
		$objTemplate = new \FrontendTemplate('bb_viewforum');
		$objTemplate->setData($this->objForum->row());
		$objTemplate->title = $this->objForum->title;
		if (ForumModel::findPublishedForumsByPids(array($this->objForum->id)) !== null)
		{
			$forumListParser = new ForumListParser(array($this->objForum->id));
			$objTemplate->subforums = $forumListParser->parseForumList();
		}
		$objTemplate->labelTopics = $GLOBALS['TL_LANG']['MSC']['bb_topics'];
		$objTemplate->labelAuthor = $GLOBALS['TL_LANG']['MSC']['bb_author'];
		$objTemplate->labelReplies = $GLOBALS['TL_LANG']['MSC']['bb_replies'];
		$objTemplate->labelViews = $GLOBALS['TL_LANG']['MSC']['bb_views'];
		$objTemplate->labelLastPost = $GLOBALS['TL_LANG']['MSC']['bb_last_post'];
		$objTemplate->labelNoTopics = $GLOBALS['TL_LANG']['MSC']['bb_no_topics'];
		if ($this->objForum->type == 'forum' && (BE_USER_LOGGED_IN || FE_USER_LOGGED_IN))
		{
			$objTemplate->newTopic = '<p class="new_topic"><a href="' . $this->generateNewTopicLink() . '">' . $GLOBALS['TL_LANG']['MSC']['bb_new_topic'] . '</a></p>';
		}

		$arrTopics = array();
		$objTopics = TopicModel::findTopicsByForumId($this->objForum->id);
		while ($objTopics->next())
		{
			$arrTopic = $objTopics->row();
			$arrTopic['author'] = $objTopics->getRelated('firstPoster')->username;
			$arrTopic['url'] = $this->generateTopicLink($objTopics);
			$arrTopic['lastPost'] = $this->generateLastPost($objTopics);
			$arrTopics[] = $arrTopic;
		}
		$objTemplate->topics = $arrTopics;

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
