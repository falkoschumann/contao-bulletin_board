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
 * Class BbForumModel manages access to forums and forum categories.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 */
class BbForumModel extends Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_bb_forum';


	/**
	 * Find published category items.
	 *
	 * @param array $arrOptions An optional options array
	 * @return Collection|null A collection of models or null if there are no forums
	 */
	public static function findPublishedCategories(array $arrOptions=array())
	{
		$table = static::$strTable;
		$arrColumns = array("$table.pid=0");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$table.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$table.sorting";
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}

	/**
	 * Find published forum items by given category.
	 *
	 * @param object $objCategory A category object
	 * @param array $arrOptions An optional options array
	 * @return Collection|null A collection of models or null if there are no forums
	 */
	public static function findPublishedForumsByCategory($objCategory, array $arrOptions=array())
	{
		$table = static::$strTable;
		$arrColumns = array("$table.pid=$objCategory->id");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$table.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$table.sorting";
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}
}
