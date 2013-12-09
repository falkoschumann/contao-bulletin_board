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
 * Class NewTopicParser render a new topic formular.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 */
class NewTopicParser extends \Frontend
{

	/**
	 * @var BbForumModel
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
	public function parseNewTopic()
	{
		// TODO create record for new topic
		$objTemplate = new \FrontendTemplate('bb_topic_new');
		$objTemplate->forum = $this->objForum->title;
		$objTemplate->newTopic = 'Post a new Topic';

		// Form fields
		$arrFields = array
		(
			'subject' => array
			(
				'name'      => 'subject',
				'label'     => $GLOBALS['TL_LANG']['MSC']['bb_subject'],
				'inputType' => 'text',
				'eval'      => array('mandatory'=>true, 'maxlength'=>100),
			),
			'text' => array
			(
				'name'      => 'text',
				'label'     => $GLOBALS['TL_LANG']['MSC']['bb_text'],
				'inputType' => 'textarea',
				'eval'      => array('mandatory'=>true, 'rows'=>10, 'cols'=>80, 'preserveTags'=>false),
			),
		);

		$doNotSubmit = false;
		$arrWidgets = array();
		$strFormId = 'bulletin_board';

		// Initialize the widgets
		foreach ($arrFields as $arrField)
		{
			$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];

			// Continue if the class is not defined
			if (!class_exists($strClass))
			{
				continue;
			}

			$arrField['eval']['required'] = $arrField['eval']['mandatory'];
			$objWidget = new $strClass($strClass::getAttributesFromDca($arrField, $arrField['name'], $arrField['value']));

			// Validate the widget
			if (\Input::post('FORM_SUBMIT') == $strFormId)
			{
				$objWidget->validate();

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}
			}

			$arrWidgets[$arrField['name']] = $objWidget;
		}

		$objTemplate->fields = $arrWidgets;
		$objTemplate->submit = $GLOBALS['TL_LANG']['MSC']['bb_submit'];
		$objTemplate->action = ampersand(\Environment::get('request'));
		$objTemplate->formId = $strFormId;
		$objTemplate->hasError = $doNotSubmit;

		return $objTemplate->parse();
	}
}
