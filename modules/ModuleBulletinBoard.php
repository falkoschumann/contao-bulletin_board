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


/**
 * Class ModuleBulletinBoard render the bulletin board as frontend module.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 */
class ModuleBulletinBoard extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_bulletin_board';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['bulletin_board'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module.
	 */
	protected function compile()
	{
		$this->Template->categories = $this->parseCategories(BbForumModel::findPublishedCategories());
	}


	/**
	 * Parse collection of category objects and return them as array of strings.
	 *
	 * @param Collection $objCategories
	 * @return array
	 */
	protected function parseCategories($objCategories)
	{

		$limit = $objCategories != null ? $objCategories->count() : 0;

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$arrForums = array();

		while ($objCategories->next())
		{
			$arrForums[] = $this->parseCategory($objCategories, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'));
		}

		return $arrForums;
	}


	/**
	 * Parse a category object and return it as string.
	 *
	 * @param object $objCategory
	 * @param string $strClass
	 * @return string
	 */
	protected function parseCategory($objCategory, $strClass='')
	{
		$objTemplate = new FrontendTemplate('bb_board_category');
		$objTemplate->setData($objCategory->row());

		$objTemplate->class = $strClass;
		$objTemplate->title = $objCategory->title;
		$objTemplate->forums = $this->parseForums(BbForumModel::findPublishedForumsByCategory($objCategory));

		return $objTemplate->parse();
	}


	/**
	 * Parse collection of forum objects and return them as array of strings.
	 *
	 * @param Collection $objForums
	 * @return array
	 */
	protected function parseForums($objForums)
	{

		$limit = $objForums != null ? $objForums->count() : 0;

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$arrForums = array();

		while ($objForums->next())
		{
			$arrForums[] = $this->parseForum($objForums, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'));
		}

		return $arrForums;
	}


	/**
	 * Parse a forum object and return it as string.
	 *
	 * @param object $objForum
	 * @param string $strClass
	 * @return string
	 */
	protected function parseForum($objForum, $strClass='')
	{
		$objTemplate = new FrontendTemplate('bb_board_forum');
		$objTemplate->setData($objForum->row());

		$objTemplate->class = $strClass;
		$objTemplate->title = $objForum->title;
		$objTemplate->description = $objForum->description;

		return $objTemplate->parse();
	}
}
