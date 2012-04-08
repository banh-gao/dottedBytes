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

namespace OOForm\decorator;

use OOForm\elements\basic\Radio;

use OOForm\elements\HtmlElement;

use OOForm\elements\HtmlTag;

use OOForm\elements\LabeledElement;

use OOForm\Form;
use OOForm\elements\InputField;
use OOForm\elements\FormElement;

class DefaultDecorator extends FormDecorator {
	private $showErrorSummary = true;
	
	public function __construct() {
		$this->addCss ( Form::getBaseUrl () . '/decorator/defaultStyle.css' );
		$this->addCss ( Form::getBaseUrl () . '/decorator/autocomplete.css' );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see FormDecorator::render()
	 */
	public function render(HtmlTag $tag) {
		if ($tag instanceof LabeledElement)
			return $this->renderLabeledElement ( $tag );
		
		//Render error summary and add to the form
		if ($tag instanceof Form) {
			if ($this->showErrorSummary)
				$out .= $this->renderErrorSummary ( $tag );
		}
		
		//Render without style
		return $tag->renderTag($this);
	}
	
	private function renderLabeledElement(LabeledElement $element) {
		$out = '<div class="field">';
		$out .= '<a class="label"><span class="label">' . $element->renderLabel ( $this ) . '</span></a>';
		$out .= '<span class="value">';
		$out .= $element->renderTag($this);
		$out .= '</span></div>';
		return $out;
	}
	
	private function renderErrorSummary(Form $form) {
		if (! $form->isError ())
			return '';
		
		$message = ($form->getErrorMessage () == '') ? OOFORM_ERROR : $form->getErrorMessage ();
		
		$out = '<div class="error"><span class="title">' . $message . '</span><br/>';
		$allErrors = $form->getErrors ();
		foreach ( $form->getElements () as $element ) {
			if ($element instanceof InputField && $element->getLastError () != '') {
				$out .= '<a class="inputError" href="#' . $element->getForm ()->getFormID () . '_' . $element->getName () . '" title="' . OOFORM_ERROR_TIP . '">';
				if ($element->getLabel () != '')
					$out .= '<b>' . $element->getLabel () . ':</b> ';
				
				$out .= $element->getLastError () . '</a>' . "\n";
				unset ( $allErrors [$element->getName ()] );
			}
		}
		
		//Show all unassociated errors
		foreach ( $allErrors as $error ) {
			$out .= '<a class="inputError">' . $error . '</a>';
		}
		
		return $out . '</div>' . "\n";
	}
	
	/**
	 * Set if the error summary must be rendered
	 * @param boolean $showErrorSummary
	 */
	public function setShowErrorSummary($showErrorSummary) {
		$this->showErrorSummary = $showErrorSummary;
	}
	
	/**
	 * Get if the error summary will be rendered
	 * @return boolean
	 */
	public function isShowErrorSummary() {
		return $this->showErrorSummary;
	}
}
?>