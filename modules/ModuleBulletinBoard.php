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

namespace BulletinBoard;

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
	 * @return string
	 */
	private $strItem;


	/**
	 * @see Module::generate()
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			return $this->displayWildcard();
		}

		if (isset($_GET['items']))
		{
			$this->strItem = \Input::get('items');
		}
		else if ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			$this->strItem = \Input::get('auto_item');
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
		$this->Template->breadcrumb = $this->parseBreadcrumb();
		if ($this->strItem)
		{
			$parser = new ForumParser($this->strItem);
			$this->Template->content = $parser->parseForum();
		}
		else {
			$parser = new BoardParser();
			$this->Template->content = $parser->parseBoard();
		}
	}


	/**
	 * @return string
	 */
	private function parseBreadcrumb()
	{
		$items = array();
		if ($this->strItem) {
			$objForum = BbForumModel::findByIdOrAlias($this->strItem);
			$objForums = BbForumModel::findParentsById($objForum->id);
			if ($objForums)
			{
				// add current forum
				$items[] = array
				(
					'isActive' => true,
					'title'    => $objForums->title,
					'class'    => '',
				);

				// add parent forums
				while ($objForums->next())
				{
					if (!$objForums->published && !BE_USER_LOGGED_IN)
					{
						continue;
					}

					$items[] = array
					(
						'isActive' => false,
						'href'     => BulletinBoard::generateForumLink($objForums),
						'title'    => $objForums->title,
						'class'    => '',
					);
				}
			}
		}

		// add board index
		$items[] = array
		(
			'isActive' => false,
			'href'     => static::generateFrontendUrl($GLOBALS['objPage']->row()),
			'title'    => $GLOBALS['TL_LANG']['MSC']['bb_board_index'],
			'class'    => 'first',
		);

		$objTemplate = new \FrontendTemplate('bb_breadcrumb');
		$objTemplate->items = array_reverse($items);
		return $objTemplate->parse();
	}
}
