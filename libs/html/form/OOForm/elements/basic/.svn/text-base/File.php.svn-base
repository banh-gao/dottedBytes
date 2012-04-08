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

/**
 * Defines a file field
 * @author Daniel Zozin
 *
 */
class File extends InputField {
	
	/**
	 * Create a file field, in most browsers the initial value is ignored for security reasons
	 * When a file field is added to a form, the form enctype will automatically change to multipart/form-data
	 * @param string $name
	 * @param string $label
	 */
	public function __construct($name, $label = '') {
		parent::__construct ( $name, $label );
		$this->setAttribute ( 'type', 'file' );
	}
	
	/**
	 * Set the accept file types based on the mime types, this function doesn't work on all browsers
	 * @param array $mimes
	 */
	public function setAcceptedMimes($mimes) {
		$this->setAttribute ( 'accept', implode ( ',', $mimes ) );
	}
	
	/**
	 * Get the accepted mimes
	 * @return array
	 */
	public function getAcceptedMimes() {
		return ($this->getAttribute ( 'accept' ) == '') ? array () : explode ( ',', $this->getAttribute ( 'accept' ) );
	}
}

?>