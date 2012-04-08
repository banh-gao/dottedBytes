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

use OOForm\elements\basic\File;

use OOForm\Form;
use OOForm\elements\FormElement;
use OOForm\elements\ElementContainer;
use OOForm\elements\InputField;
use OOForm\decorator\FormDecorator;

/**
 * Group generic form elements in a single object.
 * @author Daniel Zozin
 *
 */
class ElementGroup extends InputField implements ElementContainer {
	
	/**
	 * Create a group of input fields
	 * @param string $label
	 */
	public function __construct($label = '') {
		parent::__construct ( '', $label );
		$this->tagName = 'div';
		$this->setAttribute ( 'class', 'group' );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ElementContainer::addElement()
	 */
	public function addElement(FormElement $element) {
		$element->setParentContainer ( $this );
		$this->addChild ( $element );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ElementContainer::removeElement()
	 */
	public function removeElement(FormElement $element) {
		$element->setParentContainer(null);
		return $this->removeChild ( $element );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ElementContainer::getElements()
	 */
	public function getElements() {
		$res = array ();
		foreach ( $this->getChildren () as $child ) {
			if ($child instanceof FormElement)
				$res [] = $child;
		}
		return $res;
	}
	
	public function renderTag(FormDecorator $decorator) {
		if (! $this->isEnabled ())
			$this->setAttribute ( 'class', $this->getAttribute ( 'class' ) . ' disabled' );
		return $decorator->renderTag($this);
	}
}

?>