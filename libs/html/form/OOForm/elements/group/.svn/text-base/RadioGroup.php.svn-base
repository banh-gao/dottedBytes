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
use OOForm\elements\basic\Radio;
use OOForm\decorator\FormDecorator;

/**
 * Defines a group of radio buttons. The buttons have all the group name, so only a radio button can be selected.
 * In the elaboration page only the checked button value will be viewed.
 * The <i>required field</i> constraint for single radio button will be ignored, it must be explicitly defined on the group object.
 * @author Daniel Zozin
 *
 */
class RadioGroup extends ElementGroup {
	
	public function __construct($name, $label = '') {
		parent::__construct ( $label );
		$this->setName ( $name );
		$this->setAttribute ( 'class', 'radioGroup' );
	}
	
	/**
	 * Add a radio button to the group
	 * @param Radio $radio
	 * @see ElementGroup::addElement()
	 */
	public function addElement(FormElement $radio) {
		if (! ($radio instanceof Radio))
			throw new InvalidArgumentException ( 'Expected object of type Radio, object of type ' . get_class ( $radio ) . ' given' );
		parent::addElement ( $radio );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see OOForm\elements\group.ElementGroup::renderTag()
	 */
	public function renderTag(FormDecorator $decorator) {
		foreach ( $this->getElements() as $field ) {
			$field->setName ( $this->getName () );
			$field->setRequired ( false );
			if($field->getValue() == $this->getValue())
				$field->setChecked(true);
		}
		return parent::renderTag($decorator);
	}
}

?>