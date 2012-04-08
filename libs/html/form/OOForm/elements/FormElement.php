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
use OOForm\Form;

/**
 * A generic element of the form
 * @author Daniel Zozin
 *
 */
abstract class FormElement extends HtmlTag {
	
	private static $lastElementID = 1;
	
	/**
	 * The parent form
	 * @var OOForm
	 */
	private $form;
	
	/**
	 * The parent container
	 * @var ElementContainer
	 */
	private $parent;
	
	public function __construct($tagName, $content = '') {
		parent::__construct ( $tagName, $content );
		$this->setAttribute ( 'id', "el" . self::$lastElementID ++ );
	}
	
	/**
	 * Returns the unique formElement identifier of this element
	 * @return int
	 */
	public final function getFormElementId() {
		return $this->getAttribute ( 'id' );
	}
	
	/**
	 * @ignore
	 * !! DO NOT CALL DIRECTLY !!
	 * This method is automatically called when the element is added to a form 
	 * @param OOForm $form
	 */
	public function setForm(Form $form) {
		$this->form = $form;
	}
	
	/**
	 * Get the form that contains this element
	 * @return OOForm
	 */
	public function getForm() {
		return $this->form;
	}
	
	/**
	 * Set the elementContainer in witch this element is contained
	 * This method is automatically called when the element is added to a container
	 * @param $parent ElementContainer
	 */
	public final function setParentContainer(ElementContainer $parent = null) {
		if ($this->parent != null) {
			$this->parent->removeElement ( $this );
		}
		
		$this->parent = $parent;
	}
	
	/**
	 * Get the elementContainer in witch this element is contained
	 * This method will return a valid container only after the element was added to a form
	 * @return ElementContainer
	 */
	public function getParentContainer() {
		return $this->parent;
	}
	
	/**
	 * Returns a string representation of the element
	 * @return string
	 */
	public function __toString() {
		$out = get_class ( $this ) . '(#' . $this->getFormElementID ();
		return $out . ')';
	}
}
?>