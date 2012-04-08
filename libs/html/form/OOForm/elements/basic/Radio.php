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

namespace OOForm\elements\basic;

use OOForm\elements\group\RadioGroup;

use OOForm\elements\InputField;
use OOForm\decorator\FormDecorator;

/**
 * Defines a radio button
 * @author Daniel Zozin
 *
 */
class Radio extends InputField {
	
	private $radioLabel;
	
	/**
	 * Create a radio button
	 * @param string $name The name of the radio button
	 * @param string $radioLabel The label attached to the radio button
	 */
	public function __construct($name, $radioLabel = '') {
		parent::__construct ( $name );
		$this->radioLabel = $radioLabel;
	}
	
	/**
	 * Set if the radio button is checked or not
	 * @param boolean $checked
	 */
	public function setChecked($checked) {
		if ($checked)
			$this->setAttribute ( 'checked', 'checked' );
		else
			$this->unsetAttribute ( 'checked' );
	}
	
	/**
	 * Returns true if the radio button is checked
	 * @return boolean
	 */
	public function isChecked() {
		return ($this->getAttribute ( 'checked' ) != '');
	}
	
	/**
	 * Get the label attached to the radio button
	 * @return string
	 */
	public function getRadioLabel() {
		return $this->radioLabel;
	}
	
	/**
	 * Set the label attached to the radio button
	 * @param string $radioLabel
	 */
	public function setBoxLabel($radioLabel) {
		$this->radioLabel = $radioLabel;
	}
	
	public function renderTag(FormDecorator $decorator) {
		$this->resetValue ();
		$label = ($this->radioLabel != '') ? $this->radioLabel : '';
		if ($this->isRequired ())
			$label .= '<span class="requiredMark">*</span>';
		
		$this->setAttribute ( 'type', 'radio' );
		return '<label>' . $decorator->renderTag($this) . $label . '</label>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see OOForm\elements.InputField::resetValue()
	 */
	protected function resetValue() {
		if ($this->getLastValue () !== null && $this->getLastValue () == $this->getValue ())
			$this->setChecked ( true );
	}
}

?>