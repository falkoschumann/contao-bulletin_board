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
 * Class BreadcrumbParser render forum breadcrumb.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 */
class BreadcrumbParser extends BulletinBoard
{

	/**
	 * @var BbForumModel
	 */
	private $objForum;


	/**
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
	public function parseBreadcrumb()
	{
		$items = array();
		if ($this->objForum)
		{
			$objForums = ForumModel::findParentForumsById($this->objForum->id);
			if ($objForums !== null)
			{
				// add current forum
				$items[] = array
				(
					'isActive' => true,
					'href'     => static::generateForumLink($objForums),
					'title'    => $objForums->name,
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
						'href'     => static::generateForumLink($objForums),
						'title'    => $objForums->name,
						'class'    => '',
					);
				}
			}
		}

		// add board index
		$items[] = array
		(
			'isActive' => $this->objForum === null,
			'href'     => static::generateFrontendUrl($GLOBALS['objPage']->row()),
			'title'    => $GLOBALS['TL_LANG']['MSC']['bb_board_index'],
			'class'    => 'first',
		);

		$objTemplate = new \FrontendTemplate('bb_breadcrumb');
		$objTemplate->items = array_reverse($items);
		return $objTemplate->parse();
	}
}
