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

namespace OOForm\elements\group;

use InvalidArgumentException;
use OOForm\elements\FormElement;
use OOForm\elements\basic\Checkbox;
use OOForm\decorator\FormDecorator;

/**
 * Defines a group of checkboxes, all checkboxes in this group will have the name of the group.
 * In the elaboration page they are viewed as an array that contains the checked boxes values.
 * The <i>required field</i> constraint for single checkboxes will be ignored, it must be explicitly defined on the group object.
 * @author Daniel Zozin
 *
 */
class CheckboxGroup extends ElementGroup {
	
	public function __construct($name, $label = '') {
		parent::__construct ( $label );
		$this->setName ( $name );
		$this->setAttribute ( 'class', 'checkboxGroup' );
	}
	
	/**
	 * Add a checkbox to the group
	 * @param Checkbox $field
	 * @see ElementGroup::addElement()
	 */
	public function addElement(FormElement $field) {
		if (! ($field instanceof Checkbox))
			throw new InvalidArgumentException ( 'Expected object of type Checkbox, object of type ' . get_class ( $field ) . ' given' );
		parent::addElement ( $field );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see OOForm\elements\group.ElementGroup::renderTag()
	 */
	public function renderTag(FormDecorator $decorator) {
		foreach ( $this->elements as $field ) {
			$field->setRequired ( false );
			$field->setName ( $this->getName () . '[]' );
			if($field->getValue() == $this->getValue())
				$field->setChecked(true);
		}
		return parent::renderTag( $decorator );
	}
}

?>