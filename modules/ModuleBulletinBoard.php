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
 * Class ModuleBulletinBoard render the bulletin board as frontend module.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 */
class ModuleBulletinBoard extends \Module
{

	/**
	 * @var string
	 */
	protected $strTemplate = 'mod_bulletin_board';


	/**
	 * @see Module::generate()
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			return $this->displayWildcard();
		}

		return parent::generate();
	}


	/**
	 * @return string
	 */
	private function displayWildcard()
	{
		$objTemplate = new \BackendTemplate('be_wildcard');
		$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['bulletin_board'][0]) . ' ###';
		$objTemplate->title = $this->headline;
		$objTemplate->id = $this->id;
		$objTemplate->link = $this->name;
		$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
		return $objTemplate->parse();
	}


	/**
	 * @see Module::compile()
	 */
	protected function compile()
	{
		global $objPage;
		if (isset($_GET['items']))
		{
			$forum = \Input::get('items');
		}
		else if ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			$forum = \Input::get('auto_item');
		}

		$objForum = BbForumModel::findByIdOrAlias($forum);
		$parser = new BreadcrumbParser($objForum);
		$this->Template->breadcrumb = $parser->parseBreadcrumb();

		if ($forum) {
			if ($objForum === null)
			{
				$objPage->noSearch = 1;
				$objPage->cache = 0;
				header('HTTP/1.1 404 Not Found');
				$this->Template->content = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['bb_invalid_forum'], $forum) . '</p>';
				return;
			}
		}

		if (isset($_GET['topic']))
		{
			$topic = \Input::get('topic');
			$objTopic = BbTopicModel::findByPk($topic);
			if ($objTopic === null)
			{
				$objPage->noSearch = 1;
				$objPage->cache = 0;
				header('HTTP/1.1 404 Not Found');
				$this->Template->content = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['bb_invalid_topic'], $topic) . '</p>';
				return;
			}
		}

		if ($objTopic)
		{
			$parser = new TopicParser($objTopic);
			$this->Template->content = $parser->parseTopic();
		}
		else if ($objForum)
		{
			$parser = new ForumParser($objForum);
			$this->Template->content = $parser->parseForum();
		}
		else
		{
			$parser = new BoardParser();
			$this->Template->content = $parser->parseBoard();
		}
	}
}
