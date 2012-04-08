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

namespace OOForm\elements;

use OOForm\Form;
use OOForm\decorator\FormDecorator;

/**
 * Defines a field with a value that will be submit to the elaboration page
 * @author Daniel Zozin
 *
 */
abstract class InputField extends LabeledElement {
	
	private $name = null;
	private $required = false;
	private $lastError = null;
	private $colspan = false;
	private $redisplayValue = true;
	private $lastValue = null;
	private $value = null;
	private $readonly = false;
	private $enabled = true;
	private $tooltip;
	
	/**
	 * Create a new InputField that will be send to the elaboration page
	 * @param string $name
	 * @param string $label
	 */
	public function __construct($name, $label = '') {
		parent::__construct ( $label );
		$this->tagName = 'input';
		$this->setName ( $name );
	}
	
	private function detectLastValue() {
		if (! ($this->getForm () instanceof Form))
			return null;
		
		$formID = $this->getForm ()->getFormID ();
		if (! array_key_exists ( "forms", $_SESSION )) {
			return null;
		}
		if (! array_key_exists ( $formID, $_SESSION ["forms"] )) {
			return null;
		}
		if (! array_key_exists ( "VALUES", $_SESSION ["forms"] [$formID] )) {
			return null;
		}
		if (! array_key_exists ( $this->getName (), $_SESSION ["forms"] [$formID] ["VALUES"] )) {
			return null;
		}
		
		$this->lastValue = $_SESSION ["forms"] [$formID] ["VALUES"] [$this->getName ()];
	}
	
	private function detectLastError() {
		if (! ($this->getForm () instanceof Form))
			return '';
		$formID = $this->getForm ()->getFormID ();
		if (! array_key_exists ( "forms", $_SESSION ))
			return '';
		if (! array_key_exists ( $formID, $_SESSION ["forms"] ))
			return '';
		if (! array_key_exists ( "USER_ERRORS", $_SESSION ["forms"] [$formID] ))
			return '';
		if (! array_key_exists ( $this->getName (), $_SESSION ["forms"] [$formID] ["USER_ERRORS"] ))
			return '';
		
		return $_SESSION ["forms"] [$formID] ["USER_ERRORS"] [$this->getName ()];
	}
	
	/**
	 * Return the last error of the form if it was sent but was not correct
	 *
	 * @return string
	 */
	public function getLastError() {
		if ($this->lastError === null)
			return $this->detectLastError ();
		return $this->lastError;
	}
	
	/**
	 * Set an error message to display
	 * @param string $error
	 */
	public function setLastError($error) {
		$this->lastError = $error;
	}
	
	/**
	 * Returns true if this field is required
	 * @return boolean
	 */
	public function isRequired() {
		return $this->required;
	}
	
	/**
	

	 * Specify if this field is required
	 * @param boolean $required
	 */
	public function setRequired($required) {
		$this->required = $required;
	}
	
	/**
	 * Returns true if this field is enabled
	 * @return boolean
	 */
	public function isEnabled() {
		return $this->getAttribute ( 'disabled' ) == null;
	}
	
	/**
	 * Specify if this field is enabled
	 * If the field is set to disabled then the value will not be passed to the elaboration page (name attribute removed) 
	 * @param boolean $enabled
	 */
	public function setEnabled($enabled) {
		if ($enabled)
			$this->unsetAttribute ( 'disabled' );
		else
			$this->setAttribute ( 'disabled', 'disabled' );
	}
	
	/**
	 * Returns true if this field is readonly
	 * @return boolean
	 */
	public function isReadonly() {
		return $this->getAttribute ( 'readonly' ) != null;
	}
	
	/**
	 * Specify if this field is readonly
	 * If the field is set to readonly then the value will not be passed to the elaboration page (name attribute removed)
	 * @param boolean $readonly
	 */
	public function setReadonly($readonly) {
		if ($readonly)
			$this->setAttribute ( 'readonly', 'readonly' );
		else
			$this->unsetAttribute ( 'readonly' );
	}
	
	/**
	 * Set if redisplay the value of the field after the submit was not valid
	 * @param boolean $redisplay
	 */
	public function setRedisplayValue($redisplay) {
		$this->redisplayValue = $redisplay;
	}
	
	/**
	 * Get if the value will be redisplayed in the field after the submit was not valid
	 * @return boolean
	 */
	public function isRedisplayValue() {
		return $this->redisplayValue;
	}
	
	/**
	 * Set a tooltip for the input
	 * @param string $tooltip
	 */
	public function setTooltip($tooltip) {
		if ($tooltip != null)
			$this->setAttribute ( 'title', $tooltip );
		else
			$this->unsetAttribute ( 'title' );
	}
	
	/**
	 * Get the tooltip
	 * @return string
	 */
	public function getTooltip() {
		return $this->getAttribute ( 'title' );
	}
	
	/**
	 * Get the name of the input field
	 * @return the $name
	 */
	public function getName() {
		return $this->getAttribute ( 'name' );
	}
	
	/**
	 * Set the name of the input field
	 * @param string $name
	 */
	public function setName($name) {
		$this->setAttribute ( 'name', $name );
	}
	
	/**
	 * Get the value of the input field
	 * @return the $value
	 */
	public function getValue() {
		return $this->getAttribute ( 'value' );
	}
	
	/**
	 * Set the value of the input field
	 * @param string $value
	 */
	public function setValue($value) {
		$this->setAttribute ( 'value', $value );
	}
	
	/**
	 * Returns true if the colspan is enabled
	 * @return boolean
	 */
	public function isColspan() {
		return $this->colspan;
	}
	
	/**
	 * With the colspan enabled the field value width will override the label, so the label is not shown
	 * @param boolean $colspan
	 */
	public function setColspan($colspan) {
		$this->colspan = $colspan;
	}
	
	/**
	 * Render the label of the field
	 * @param FormDecorator $decorator
	 * @return string
	 */
	public function renderLabel(FormDecorator $decorator) {
		if ($this->getLabel () == '')
			return '';
		
		$label = $this->getLabel ();
		
		if ($this->isRequired ())
			$label .= '<span class="requiredMark">*</span>';
		
		return $label;
	}
	
	public function renderTag(FormDecorator $decorator) {
		if ($this->isReadonly () || ! $this->isEnabled ()) {
			$this->unsetAttribute ( 'name' );
		}
		$this->resetValue ();
		$out = '';
		
		$className = substr ( get_class ( $this ), strrpos ( get_class ( $this ), '\\' ) + 1 );
		
		$out .= '<div class="field"><div class="' . $className . '">';
		
		$out .= $decorator->renderTag($this);
		
		if ($this->getLastError () != '')
			$out .= '<span class="error">' . $this->getLastError () . '</span>';
		
		if ($this->getTooltip () != '')
			$out .= '<span class="tooltip"><em>' . $this->getTooltip () . '</em></span>';
		
		return $out . '</div></div>';
	}
	
	/**
	 * @return the $lastValue
	 */
	public function getLastValue() {
		if ($this->lastValue === null)
			$this->detectLastValue ();
		return $this->lastValue;
	}
	
	/**
	 * This method is called during the rendering,
	 * when there is to redisplay a previous value
	 */
	protected function resetValue() {
		if ($this->getValue () === null && $this->getLastValue () != '')
			$this->setValue ( $this->getLastValue () );
	}
}

?>