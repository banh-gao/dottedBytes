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

use OOForm\decorator\FormDecorator;

use OOForm\elements\FormElement;

abstract class LabeledElement extends FormElement {
	private $label = '';
	
	public function __construct($label) {
		parent::__construct('');
		$this->setLabel($label);
	}
	
	/**
	 * Get the label of the field
	 * @return string $label
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * Set the label to display in the form
	 * @param string $label
	 */
	public function setLabel($label) {
		$this->label = $label;
	}
	
	/**
	 * Render the label
	 * @param FormDecorator $decorator
	 * @return string
	 */
	public function renderLabel(FormDecorator $decorator) {
		return $this->getLabel ();
	}
}

?>