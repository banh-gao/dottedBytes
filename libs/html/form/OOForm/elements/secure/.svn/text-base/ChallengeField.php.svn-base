<?php
/**
 * @package		OOForm
 * @copyright	Copyright (C) 2010 OOForm. All rights reserved.
 * @author		Daniel Zozin
 * @license		GNU/GPL, see COPYING file
 *
 * This file is part of OOForm.
 * OOForm is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * OOForm is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OOForm.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OOForm\elements\secure;

use OOForm\elements\JavascriptTag;

use OOForm\Form;

use OOForm\elements\LabeledElement;

use OOForm\elements\InputField;

use OOForm\decorator\FormDecorator;

use OOForm\elements\HtmlTag;
use OOForm\elements\FormElement;
use OOForm\elements\basic\Hidden;
use OOForm\elements\group\ElementGroup;

class ChallengeField extends LabeledElement {
	
	private $challengeElement;
	private $challengeCode;
	
	public function __construct(InputField $challengeElement, $customChallenge = false) {
		$this->tagName = 'span';
		$this->challengeElement = $challengeElement;
		$this->challengeCode = ($customChallenge != null) ? $customChallenge : $this->randChallenge ();
	}
	
	public function setForm(Form $form) {
		$form->setSessionValue ( 'challenge', $this->challengeCode );
		
		$form->addChild ( $this->getJavascriptTag () );
		return parent::setForm ( $form );
	}
	
	public function renderTag(FormDecorator $decorator) {
		$decorator->addJavascript ( Form::getBaseUrl () . '/elements/secure/jquery.md5.js' );
		
		$form = $this->getForm ();
		
		if ($form->getAttribute ( 'onSubmit' ) != null)
			$form->setAttribute ( 'onSubmit', 'challengeSubmit();' . $this->getAttribute ( 'onSubmit' ) );
		else
			$form->setAttribute ( 'onSubmit', 'challengeSubmit()' );
		return $decorator->render ( $this->challengeElement );
	}
	
	private function randChallenge() {
		list ( $usec, $sec ) = explode ( ' ', microtime () );
		mt_srand ( ( float ) $sec + (( float ) $usec * 100000) );
		
		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		
		$genString = "";
		for($i = 0; $i < mt_rand ( 32, 32 ); $i ++) {
			$genString .= substr ( $salt, mt_rand () % strlen ( $salt ), 1 );
		}
		return $genString;
	}
	
	private function getJavascriptTag() {
		$id = $this->challengeElement->getFormElementID ();
		$js = 'function challengeSubmit() {var f=document.getElementById("' . $id . '");';
		$js .= 'f.value = $.md5("' . $this->challengeCode . '" + f.value);}';
		return new JavascriptTag ( '', $js );
	}
}

?>