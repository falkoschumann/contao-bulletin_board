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

use Contao\UserModel;


/**
 * Class NewTopicParser render a new topic formular.
 *
 * @copyright  Falko Schumann 2013
 * @author     Falko Schumann
 * @package    BulletinBoard
 */
class NewTopicParser extends BulletinBoard
{

	/**
	 * @var BbForumModel
	 */
	private $objForum;

	/**
	 * @var array
	 */
	private $arrWidgets = array();

	/**
	 * @var boolean
	 */
	private $doNotSubmit = false;

	/**
	 * @var string
	 */
	private $strFormId = 'bulletin_board';


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
		$this->import('FrontendUser', 'User');

		$objTemplate = new \FrontendTemplate('bb_topic_new');
		$objTemplate->forum = $this->objForum->title;
		$objTemplate->newTopic = 'Post a new Topic';

		$this->createForm();

		$objTemplate->fields = $this->arrWidgets;
		$objTemplate->submit = $GLOBALS['TL_LANG']['MSC']['bb_submit'];
		$objTemplate->action = ampersand(\Environment::get('request'));
		$objTemplate->formId = $this->strFormId;
		$objTemplate->hasError = $this->doNotSubmit;

		$this->storeMessage();

		return $objTemplate->parse();
	}


	private function createForm()
	{
		// Form fields
		$arrFields = array
		(
			'subject' => array
			(
				'name'      => 'subject',
				'label'     => $GLOBALS['TL_LANG']['MSC']['bb_subject'],
				'inputType' => 'text',
				'eval'      => array(
					'mandatory' => true,
					'maxlength' => 100
				),
			),
			'message' => array
			(
				'name'      => 'message',
				'label'     => $GLOBALS['TL_LANG']['MSC']['bb_message'],
				'inputType' => 'textarea',
				'eval'      => array(
					'mandatory'    => true,
					'rows'         => 10,
					'cols'         => 80,
					'preserveTags' => false
				),
			),
		);

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
			if (\Input::post('FORM_SUBMIT') == $this->strFormId)
			{
				$objWidget->validate();

				if ($objWidget->hasErrors())
				{
					$this->doNotSubmit = true;
				}
			}

			$this->arrWidgets[$arrField['name']] = $objWidget;
		}
	}


	private function storeMessage()
	{
		// Store the message
		if (!$this->doNotSubmit && \Input::post('FORM_SUBMIT') == $this->strFormId)
		{
			// Do not parse any tags in the comment
			$strMessage = htmlspecialchars(trim($this->arrWidgets['message']->value));
			$strMessage = str_replace(array(
										   '&amp;',
										   '&lt;',
										   '&gt;'
									  ), array(
											  '[&]',
											  '[lt]',
											  '[gt]'
										 ), $strMessage);

			// Remove multiple line feeds
			$strMessage = preg_replace('@\n\n+@', "\n\n", $strMessage);

			// Parse BBCode
			$strMessage = $this->parseBbCode($strMessage);

			// Prevent cross-site request forgeries
			$strMessage = preg_replace('/(href|src|on[a-z]+)="[^"]*(contao\/main\.php|typolight\/main\.php|javascript|vbscri?pt|script|alert|document|cookie|window)[^"]*"+/i', '$1="#"', $strMessage);

			$time = time();

			// Prepare the record and store the post
			$objPost = new PostModel();
			//$objPost->pid = $objTopic->id;
			$objPost->tstamp = $time;
			$objPost->poster = $this->User->id;
			$objPost->posterIp = \System::anonymizeIp(\Environment::get('ip'));
			$objPost->subject = $this->arrWidgets['subject']->value;
			$objPost->text = $this->arrWidgets['text']->value;
			$objPost->save();

			// Prepare the record and store the topic
			$objTopic = new TopicModel();
			$objTopic->pid = $this->objForum->id;
			$objTopic->tstamp = $time;
			$objTopic->title = $objPost->subject;
			$objTopic->firstPost = $objPost->id;
			$objTopic->firstPoster = $objPost->poster;
			$objTopic->lastPost = $objTopic->firstPost;
			$objTopic->lastPoster = $objTopic->firstPoster;
			$objTopic->save();

			// Update the record and store the post
			$objPost->pid = $objTopic->id;
			$objPost->save();

			// Update the record and store the forum
			$this->objForum->topics = $this->objForum->topics + 1;
			$this->objForum->posts = $this->objForum->posts + 1;
			$this->objForum->lastPost = $objPost->id;
			$this->objForum->lastPoster = $objPost->poster;
			$this->objForum->save();

			// Update the record and store the user
			$this->User->bb_posts = $this->User->bb_posts + 1;
			$this->User->bb_lastPostTime = $time;
			$this->User->save();


			$this->redirect($this->generateTopicLink($objTopic));
		}
	}


	/**
	 * Replace bbcode and return the HTML string
	 *
	 * Supports the following tags:
	 * - [b][/b] bold
	 * - [i][/i] italic
	 * - [u][/u] underline
	 * - [img][/img]
	 * - [code][/code]
	 * - [color=#ff0000][/color]
	 * - [quote][/quote]
	 * - [quote=tim][/quote]
	 * - [url][/url]
	 * - [url=http://][/url]
	 * - [email][/email]
	 * - [email=name@example.com][/email]
	 *
	 * @param string
	 * @return string
	 */
	public function parseBbCode($strComment)
	{
		$arrSearch = array
		(
			'@\[b\](.*)\[/b\]@Uis',
			'@\[i\](.*)\[/i\]@Uis',
			'@\[u\](.*)\[/u\]@Uis',
			'@\s*\[code\](.*)\[/code\]\s*@Uis',
			'@\[color=([^\]" ]+)\](.*)\[/color\]@Uis',
			'@\s*\[quote\](.*)\[/quote\]\s*@Uis',
			'@\s*\[quote=([^\]]+)\](.*)\[/quote\]\s*@Uis',
			'@\[img\]\s*([^\[" ]+\.(jpe?g|png|gif|bmp|tiff?|ico))\s*\[/img\]@i',
			'@\[url\]\s*([^\[" ]+)\s*\[/url\]@i',
			'@\[url=([^\]" ]+)\](.*)\[/url\]@Uis',
			'@\[email\]\s*([^\[" ]+)\s*\[/email\]@i',
			'@\[email=([^\]" ]+)\](.*)\[/email\]@Uis',
			'@href="(([a-z0-9]+\.)*[a-z0-9]+\.([a-z]{2}|asia|biz|com|info|name|net|org|tel)(/|"))@i'
		);

		$arrReplace = array
		(
			'<strong>$1</strong>',
			'<em>$1</em>',
			'<span style="text-decoration:underline">$1</span>',
			"\n\n" . '<div class="code"><p>' . $GLOBALS['TL_LANG']['MSC']['com_code'] . '</p><pre>$1</pre></div>' . "\n\n",
			'<span style="color:$1">$2</span>',
			"\n\n" . '<div class="quote">$1</div>' . "\n\n",
			"\n\n" . '<div class="quote"><p>' . sprintf($GLOBALS['TL_LANG']['MSC']['com_quote'], '$1') . '</p>$2</div>' . "\n\n",
			'<img src="$1" alt="" />',
			'<a href="$1">$1</a>',
			'<a href="$1">$2</a>',
			'<a href="mailto:$1">$1</a>',
			'<a href="mailto:$1">$2</a>',
			'href="http://$1'
		);

		$strComment = preg_replace($arrSearch, $arrReplace, $strComment);

		// Encode e-mail addresses
		if (strpos($strComment, 'mailto:') !== false)
		{
			$strComment = \String::encodeEmail($strComment);
		}

		return $strComment;
	}


	/**
	 * Convert line feeds to <br /> tags
	 *
	 * @param string
	 * @return string
	 */
	public function convertLineFeeds($strMessage)
	{
		global $objPage;
		$strMessage = nl2br_pre($strMessage, ($objPage->outputFormat == 'xhtml'));

		// Use paragraphs to generate new lines
		if (strncmp('<p>', $strMessage, 3) !== 0)
		{
			$strMessage = '<p>' . $strMessage . '</p>';
		}

		$arrReplace = array
		(
			'@<br>\s?<br>\s?@' => "</p>\n<p>",
			// Convert two linebreaks into a new paragraph
			'@\s?<br></p>@'    => '</p>',
			// Remove BR tags before closing P tags
			'@<p><div@'        => '<div',
			// Do not nest DIVs inside paragraphs
			'@</div></p>@'     => '</div>'
			// Do not nest DIVs inside paragraphs
		);

		return preg_replace(array_keys($arrReplace), array_values($arrReplace), $strMessage);
	}


	/**
	 * @param object $objTopic
	 * @return string
	 */
	private function generateTopicLink($objTopic)
	{
		return $this->addToUrl('topic=' . $objTopic->id);
	}
}
