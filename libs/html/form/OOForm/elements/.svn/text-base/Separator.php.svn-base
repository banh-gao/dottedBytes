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

use OOForm\elements\HtmlTag;

use OOForm\decorator\FormDecorator;

/**
 * A separator used for separate the form in categories for a better layout
 * @author Daniel Zozin
 *
 */
class Separator extends FormElement {
	
	private $label, $image;
	
	/**
	 * 
	 * A separator used for separate the form in categories for a better layout
	 * @param string $label
	 * @param string $icon
	 */
	public function __construct($label, HtmlTag $image = null) {
		$this->tagName = 'div';
		$this->label = $label;
		$this->image = $image;
		$this->setAttribute ( 'class', 'field separator' );
	}
	
	/**
	 * @return string The separator label 
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * @param string $label The separator label
	 */
	public function setLabel($label) {
		$this->label = $label;
	}
	
	/**
	 * Get the image
	 * @return HtmlTag
	 */
	public function getImage() {
		return $this->image;
	}
	
	/**
	 * Set the image tag
	 * @param HtmlTag $image
	 */
	public function setImage($image) {
		$this->image = $image;
	}
	
	public function renderTag(FormDecorator $decorator) {
		$img = ($this->image != null) ? $decorator->renderTag($this->image) : '';
		return '<span class="label"></span><span class="">' . $img . '<p>' . $this->label . '</p></span>';
	}
}

?>