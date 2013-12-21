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
 * Class ForumListParser render a list of forums.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 */
class ForumListParser extends BulletinBoard
{

	/**
	 * @return string
	 */
	public function parseBoard()
	{
		$objTemplate = new \FrontendTemplate('bb_forumlist');
		$objTemplate->labelForum = $GLOBALS['TL_LANG']['MSC']['bb_forum'];
		$objTemplate->labelTopics = $GLOBALS['TL_LANG']['MSC']['bb_topics'];
		$objTemplate->labelPosts = $GLOBALS['TL_LANG']['MSC']['bb_posts'];
		$objTemplate->labelLastPost = $GLOBALS['TL_LANG']['MSC']['bb_last_post'];

		$arrForums = array();
		$objForums = ForumModel::findPublishedForumsByPids(array(0));
		if ($objForums !== null)
		{
			while ($objForums->next())
			{
				if ($objForums->type === 'category')
				{
					$arrForums[] = $this->createCategory($objForums);
					$objSubForums = ForumModel::findPublishedForumsByPids(array($objForums->id));
					if ($objSubForums !== null)
					{
						while ($objSubForums->next())
						{
							if ($objSubForums->type === 'forum')
							{
								$arrForums[] = $this->createForum($objSubForums);
							}
							if ($objSubForums->type === 'link')
							{
								$arrForums[] = $this->createLink($objSubForums);
							}
						}
					}
				}
				else if ($objForums->type === 'forum')
				{
					$arrForums[] = $this->createForum($objForums);
				}
				if ($objForums->type === 'link')
				{
					$arrForums[] = $this->createLink($objForums);
				}
			}
		}
		$objTemplate->forums = $arrForums;

		return $objTemplate->parse();
	}


	/**
	 * @var ForumModel $objForum
	 * @return array
	 */
	private function createCategory($objForum)
	{
		$arrForum = $objForum->row();
		$arrForum['url'] = static::generateForumLink($objForum);
		return $arrForum;
	}


	/**
	 * @var ForumModel $objForum
	 * @return array
	 */
	private function createForum($objForum)
	{
		$arrForum = $objForum->row();
		$arrForum['url'] = static::generateForumLink($objForum);
		if ($objForum->lastPost)
		{
			global $objPage;
			$lastPostTime = \Date::parse($objPage->datimFormat, $objForum->lastPost);
			if ($objForum->lastPoster)
			{
				$lastPoster = sprintf($GLOBALS['TL_LANG']['MSC']['bb_poster'], $objForum->lastPoster->username);
			}
			else
			{
				$lastPoster = $objForum->lastPosterName;
			}
			$arrForum['lastPost'] = '<a href="' . $this->generatePostLink($objForum->lastPost) . '">' . $lastPostTime . '<br>' . $lastPoster . '</a>';
		}
		else
		{
			$arrForum['lastPost'] = $GLOBALS['TL_LANG']['MSC']['bb_no_post'];
		}
		return $arrForum;
	}


	/**
	 * @var ForumModel $objForum
	 * @return array
	 */
	private function createLink($objForum)
	{
		$arrForum = $objForum->row();
		return $arrForum;
	}
}
