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

use OOForm\elements\InputField;
use OOForm\decorator\FormDecorator;

/**
 * Defines a checkbox field
 * @author Daniel Zozin
 *
 */
class Checkbox extends InputField {
	
	private $boxLabel;
	
	/**
	 * Create a new checkbox
	 * @param string $name The name of the checkbox
	 * @param string $boxLabel The label attached to the checkbox
	 */
	public function __construct($name, $boxLabel = '') {
		parent::__construct ( $name );
		$this->setAttribute ( 'type', 'checkbox' );
		$this->boxLabel = $boxLabel;
	}
	
	/**
	 * Set if the checkbox is checked or not
	 * @param boolean $checked
	 */
	public function setChecked($checked) {
		if ($checked)
			$this->setAttribute ( 'checked', 'checked' );
		else
			$this->unsetAttribute ( 'checked' );
	}
	
	/**
	 * Returns true if the checkbox is checked
	 * @return boolean
	 */
	public function isChecked() {
		return ($this->getAttribute ( 'checked' ) != '');
	}
	
	/**
	 * Get the label attached to the checkbox
	 * @return string
	 */
	public function getBoxLabel() {
		return $this->boxLabel;
	}
	
	/**
	 * Set the label attached to the checkbox 
	 * @param string $boxLabel
	 */
	public function setBoxLabel($boxLabel) {
		$this->boxLabel = $boxLabel;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see OOForm\elements.InputField::resetValue()
	 */
	protected function resetValue() {
		if($this->getLastValue() !== null)
			$this->setChecked(true);
	}
	
	public function renderTag(FormDecorator $decorator) {
		$label = ($this->boxLabel != '') ? $this->boxLabel : '';
		if ($this->isRequired())
			$label .= '<span class="requiredMark">*</span>';
		
		$field = '<label>' .  parent::renderTag($decorator) . $label . '</label>';
		return $field;
	}
}

?>