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
 * Table tl_bb_forum
 */
$GLOBALS['TL_DCA']['tl_bb_forum'] = array
(

	// Config
	'config'      => array
	(
		'label'            => $GLOBALS['TL_CONFIG']['websiteTitle'],
		'dataContainer'    => 'Table',
		'ctable'           => array('tl_bb_topic'),
		'enableVersioning' => true,
		'sql'              => array
		(
			'keys' => array
			(
				'id'    => 'primary',
				'pid'   => 'index',
				'alias' => 'index',
				'type'  => 'index'
			)
		)
	),

	// List
	'list'        => array
	(
		'sorting'           => array
		(
			'mode'        => 5,
			'panelLayout' => 'filter,search'
		),
		'label'             => array
		(
			'fields' => array(
				'name',
				'type'
			),
			'format' => '%s <span style="color:#b3b3b3;padding-left:3px">[%s]</span>',
		),
		'global_operations' => array
		(
			'toggleNodes' => array
			(
				'label' => &$GLOBALS['TL_LANG']['MSC']['toggleAll'],
				'href'  => 'ptg=all',
				'class' => 'header_toggle'
			),
			'all'         => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations'        => array
		(
			'edit'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_bb_forum']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'copy'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_bb_forum']['copy'],
				'href'  => 'act=copy',
				'icon'  => 'copy.gif'
			),
			'copyChilds' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_bb_forum']['copyChilds'],
				'href'  => 'act=paste&amp;mode=copy&amp;childs=1',
				'icon'  => 'copychilds.gif',
			),
			'cut'        => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_bb_forum']['cut'],
				'href'  => 'act=paste&amp;mode=cut',
				'icon'  => 'cut.gif',
			),
			'delete'     => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_bb_forum']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle'     => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_bb_forum']['toggle'],
				'icon'            => 'visible.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback' => array(
					'tl_bb_forum',
					'toggleIcon'
				)
			),
			'show'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_bb_forum']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		)
	),

	// Select
	'select'      => array
	(
		'buttons_callback' => array()
	),

	// Edit
	'edit'        => array
	(
		'buttons_callback' => array()
	),

	// Palettes
	'palettes'    => array
	(
		'__selector__' => array('type'),
		'default'      => '{title_legend},name,type,alias,jumpTo;{publish_legend},published',
		'category'     => '{title_legend},name,type,alias,jumpTo;{publish_legend},published',
		'forum'        => '{title_legend},name,type,alias,jumpTo;{description_legend},description;{publish_legend},published',
		'link'         => '{title_legend},name,type,url;{description_legend},description;{publish_legend},published'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'' => ''
	),

	// Fields
	'fields'      => array
	(
		'id'             => array
		(
			'sql' => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid'            => array
		(
			'sql' => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp'         => array
		(
			'sql' => "int(10) unsigned NOT NULL default '0'"
		),
		'sorting'        => array
		(
			'sql' => "int(10) unsigned NOT NULL default '0'"
		),
		'type'           => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_bb_forum']['type'],
			'default'   => 'forum',
			'inputType' => 'select',
			'exclude'   => true,
			'sorting'   => true,
			'flag'      => 1,
			'options'   => array(
				'forum',
				'category',
				'link'
			),
			'reference' => &$GLOBALS['TL_LANG']['tl_bb_forum'],
			'eval'      => array(
				'includeBlankOption' => false,
				'submitOnChange'     => true,
				'mandatory'          => true,
				'tl_class'           => 'w50'
			),
			'sql'       => "varchar(32) NOT NULL default ''"
		),
		'jumpTo'         => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_bb_forum']['jumpTo'],
			'exclude'    => true,
			'inputType'  => 'pageTree',
			'foreignKey' => 'tl_page.title',
			'eval'       => array(
				'fieldType' => 'radio',
				'tl_class'  => 'clr'
			),
			'sql'        => "int(10) unsigned NOT NULL default '0'",
			'relation'   => array(
				'type' => 'hasOne',
				'load' => 'lazy'
			)
		),
		'url'            => array
		(
			'label'     => &$GLOBALS['TL_LANG']['MSC']['url'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory'      => true,
				'rgxp'           => 'url',
				'decodeEntities' => true,
				'maxlength'      => 255,
				'fieldType'      => 'radio',
				'tl_class'       => 'w50 wizard'
			),
			'wizard'    => array
			(
				array(
					'tl_bb_forum',
					'pagePicker'
				)
			),
			'sql'       => "varchar(255) NOT NULL default ''"
		),
		'name'           => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_bb_forum']['name'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'maxlength' => 100,
				'tl_class'  => 'w50'
			),
			'sql'       => "varchar(100) NOT NULL default ''"
		),
		'description'    => array
		(
			'label'       => &$GLOBALS['TL_LANG']['tl_bb_forum']['description'],
			'exclude'     => true,
			'search'      => true,
			'inputType'   => 'textarea',
			'eval'        => array(
				'rte'        => 'tinyMCE',
				'helpwizard' => true
			),
			'explanation' => 'insertTags',
			'sql'         => "mediumtext NULL"
		),
		'alias'          => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_bb_forum']['alias'],
			'exclude'       => true,
			'search'        => true,
			'inputType'     => 'text',
			'eval'          => array(
				'rgxp'      => 'alias',
				'unique'    => true,
				'maxlength' => 128,
				'tl_class'  => 'w50'
			),
			'save_callback' => array
			(
				array(
					'tl_bb_forum',
					'generateAlias'
				)
			),
			'sql'           => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
		),
		'published'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_bb_forum']['published'],
			'exclude'   => true,
			'inputType' => 'checkbox',
			'eval'      => array('doNotCopy' => true),
			'sql'       => "char(1) NOT NULL default ''"
		),
		'topics'         => array
		(
			'sql' => "int(10) unsigned NOT NULL default '0'"
		),
		'posts'          => array
		(
			'sql' => "int(10) unsigned NOT NULL default '0'",
		),
		'lastPost'       => array
		(
			'sql'        => "int(10) unsigned NOT NULL default '0'",
			'foreignKey' => 'tl_bb_post.subject',
			'relation'   => array(
				'type' => 'hasOne',
				'load' => 'eager'
			)
		),
		'lastPoster'     => array
		(
			'sql'        => "int(10) unsigned NOT NULL default '0'",
			'foreignKey' => 'tl_member.username',
			'relation'   => array(
				'type' => 'hasOne',
				'load' => 'eager'
			)
		),
		'lastPosterName' => array
		(
			'sql' => "varchar(64) NOT NULL default ''"
		)
	)
);

/**
 * Class tl_bb_forum
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schuman
 * @package    BulletinBoard
 */
class tl_bb_forum extends Backend
{

	/**
	 * Import the back end user object.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Auto-generate the forum alias if it has not been set yet.
	 *
	 * @param mixed
	 * @param DataContainer
	 * @return string
	 * @throws Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize(String::restoreBasicEntities($dc->activeRecord->name));
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_bb_forum WHERE alias=?")->execute($varValue);

		// Check whether the forum alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}


	/**
	 * Return the "toggle visibility" button.
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_bb_forum::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
	}


	/**
	 * Disable/enable a forum.
	 *
	 * @param integer $intId
	 * @param boolean $blnVisible
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_bb_forum::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish forum item ID "' . $intId . '"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objVersions = new Versions('tl_bb_forum', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_bb_forum']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_bb_forum']['fields']['published']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, $this);
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_bb_forum SET tstamp=" . time() . ", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
			->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_bb_forum.id=' . $intId . '" has been created' . $this->getParentEntries('tl_bb_forum', $intId), __METHOD__, TL_GENERAL);
	}


	/**
	 * Return the link picker wizard
	 *
	 * @param \DataContainer
	 * @return string
	 */
	public function pagePicker(DataContainer $dc)
	{
		return ' <a href="contao/page.php?do=' . Input::get('do') . '&amp;table=' . $dc->table . '&amp;field=' . $dc->field . '&amp;value=' . str_replace(array(
																																							   '{{link_url::',
																																							   '}}'
																																						  ), '', $dc->value) . '" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']) . '" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\'' . specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])) . '\',\'url\':this.href,\'id\':\'' . $dc->field . '\',\'tag\':\'ctrl_' . $dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '') . '\',\'self\':this});return false">' . Image::getHtml('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
	}
}
