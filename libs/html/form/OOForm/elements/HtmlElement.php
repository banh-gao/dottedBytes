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

/**
 * Defines a form element that can include generic html code in the form
 * @author Daniel Zozin
 *
 */
class HtmlElement extends LabeledElement {
	
	/**
	 * The html content of the element
	 * @var string
	 */
	private $html;
	
	public function __construct($label = '', $value = '') {
		parent::__construct ( $label );
		$this->tagName = 'span';
		$this->html = $value;
	}
	
	/**
	 * Get the html content
	 * @return the $value
	 */
	public function getValue() {
		return $this->html;
	}
	
	/**
	 * Set the html content
	 * @param string $value
	 */
	public function setValue($value) {
		$this->html = $value;
	}
	
	public function renderTag(FormDecorator $decorator) {
		$this->addChild(new HtmlTag('',$this->html));
		return $decorator->renderTag($this);
	}
}

?>