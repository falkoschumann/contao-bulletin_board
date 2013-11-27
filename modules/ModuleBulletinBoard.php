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

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			Input::setGet('items', Input::get('auto_item'));
		}

		if (Input::get('items'))
			return Input::get('items');

		return parent::generate();
	}


	/**
	 * @return string
	 */
	private function displayWildcard()
	{
		$objTemplate = new BackendTemplate('be_wildcard');
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
		$this->Template->categories = $this->parseForums(BbForumModel::findPublishedForumsByPids(array(0)));
	}


	/**
	 * @param Collection $objForums collection of BbForumModel
	 * @return array array of string
	 */
	protected function parseForums($objForums)
	{
		$limit = $objForums != null ? $objForums->count() : 0;
		$count = 0;
		$arrForums = array();
 		while ($objForums->next())
 		{
			$arrForums[] = $this->parseForum($objForums, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'));
 		}
 		return $arrForums;
	}


	/**
	 * @param BbForumModel $objForum
	 * @return string
	 */
	protected function parseForum($objForum, $strClass='')
	{
		$objTemplate = new FrontendTemplate('bb_board_forum');
		$objTemplate->setData($objForum->row());

		$objTemplate->class = $strClass;
		$objTemplate->title = $objForum->title;
		$objTemplate->link = $this->generateForumLink($objForum);
		$objTemplate->description = $objForum->description;

		$count = 0;
		$objSubforums = BbForumModel::findPublishedForumsByPids(array($objForum->id));
		$limit = $objSubforums != null ? $objSubforums->count() : 0;
		$arrSubforums= array();
		while ($objSubforums->next())
		{
			$strClass = ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even');
			$arrSubforums[] = array(
				'class'          => $strClass,
				'title'          => $objSubforums->title,
				'link'           => $this->generateForumLink($objSubforums),
				'description'    => $objSubforums->description,
			);
		}
		$objTemplate->subforums = $arrSubforums;

		return $objTemplate->parse();
	}


	/**
	 * @param object $objForum
	 * @return string
	 */
	private function generateForumLink($objForum)
	{
		$itemPrefix = $GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/items/';
		$item = $this->isAliasSetAndEnabled($objForum) ? $objForum->alias : $objForum->id;
		return $this->generateFrontendUrl($GLOBALS['objPage']->row(), $itemPrefix . $item);
	}


	/**
	 * @param object $objForum
	 * @return boolean
	 */
	private function isAliasSetAndEnabled($objForum)
	{
		return $objForum->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias'];
	}
}
