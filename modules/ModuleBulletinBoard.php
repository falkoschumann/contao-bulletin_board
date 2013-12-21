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
	 * @var forum ID or forum alias
	 */
	private $forum;


	/**
	 * @var BBForumModel
	 */
	private $objForum;


	/**
	 * @var topic ID
	 */
	private $topic;


	/**
	 * @var BBTopicModel
	 */
	private $objTopic;


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
		try
		{
			$this->getForum();
			$this->parseBreadcrumb();
			$this->checkForum();
			$this->getTopic();
			$this->checkTopic();
			$this->parseContent();
		} catch (\Exception $ex)
		{
			$this->log($ex->getMessage(), __METHOD__, TL_ERROR);
		}
	}


	private function getForum()
	{
		if (isset($_GET['items']))
		{
			$this->forum = \Input::get('items');
		}
		else if ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			$this->forum = \Input::get('auto_item');
		}
		$this->objForum = ForumModel::findByIdOrAlias($this->forum);
	}


	private function parseBreadcrumb()
	{
		$parser = new BreadcrumbParser($this->objForum);
		$this->Template->breadcrumb = $parser->parseBreadcrumb();
	}


	private function checkForum()
	{
		if ($this->forum && is_null($this->objForum))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			header('HTTP/1.1 404 Not Found');
			$this->Template->content = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['bb_invalid_forum'], $this->forum) . '</p>';
			throw new \Exception(sprintf($GLOBALS['TL_LANG']['MSC']['bb_invalid_forum'], $this->forum));
		}
	}


	private function getTopic()
	{
		$this->topic = \Input::get('topic');
		$this->objTopic = TopicModel::findByPk($this->topic);
	}


	private function checkTopic()
	{
		if ($this->topic === "new")
		{
			return;
		}

		if ($this->topic && is_null($this->objTopic))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			header('HTTP/1.1 404 Not Found');
			$this->Template->content = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['bb_invalid_topic'], $this->topic) . '</p>';
			throw new \Exception(sprintf($GLOBALS['TL_LANG']['MSC']['bb_invalid_topic'], $this->topic));
		}
	}


	private function parseContent()
	{
		if ($this->objTopic)
		{
			$parser = new TopicParser($this->objTopic);
			$this->Template->content = $parser->parseTopic();
		}
		else if ($this->objForum)
		{
			if ($this->topic === "new")
			{
				$parser = new NewTopicParser($this->objForum);
				$this->Template->content = $parser->parseNewTopic();
			}
			else
			{
				$parser = new ForumParser($this->objForum);
				$this->Template->content = $parser->parseForum();
			}
		}
		else
		{
			$parser = new ForumListParser();
			$this->Template->content = $parser->parseBoard();
		}
	}
}
