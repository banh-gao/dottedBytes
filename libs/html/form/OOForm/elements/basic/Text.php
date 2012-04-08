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

use OOForm\decorator\FormDecorator;

use OOForm\elements\InputField;

/**
 * Defines a text field
 * @author Daniel Zozin
 *
 */
class Text extends InputField {
	
	public function __construct($name, $label = '') {
		parent::__construct ( $name, $label );
		$this->setAttribute ( 'type', 'text' );
	}
	
	/**
	 * Set the maximum number of characters accepted, use 0 to remove any limit
	 * @param int $maxLength
	 */
	public function setMaxLength($maxLength) {
		if ($maxLength == 0) {
			$this->unsetAttribute ( 'maxlength' );
			return;
		}
		
		$this->setAttribute ( 'maxlength', $maxLength );
	}
	
	/**
	 * Get the maximum number of characters accepted, returns 0 if there is no limit
	 * @return int
	 */
	public function getMaxLength() {
		$max = $this->getAttribute ( 'maxlength' );
		return ($max == '') ? 0 : $max;
	}
}

?>