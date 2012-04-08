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

interface ElementContainer {
	/**
	 * Add an element to the container, the element can be only in one container at the same time.
	 * The elements are compared using the {@link FormElement::getFormElementID()} method.
	 * An element can be associated only to one form, if the element is added to another form then it will be removed from the previous one.
	 * However the element is also rendered in the first form if the render call comes before the add to the other form.
	 * @param FormElement $element
	 */
	public function addElement(FormElement $element);
	
	/**
	 * Remove the specified element from the container
	 * @param FormElement $element
	 */
	public function removeElement(FormElement $element);
	
	/**
	 * Returns an array that contains all the elements in the container
	 * @return array
	 */
	public function getElements();
}

?>