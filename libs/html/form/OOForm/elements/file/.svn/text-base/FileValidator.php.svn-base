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

namespace OOForm\elements\file;

/**
 * Abstract class for file validators
 * @author Daniel Zozin
 *
 */
abstract class FileValidator {
	protected $message = OOFORM_VALIDATOR_GENERIC;
	/**
	 * Validate the file with this validator
	 * @param UploadedFile $file
	 * @return boolean
	 */
	public abstract function validate(UploadedFile $file);
	
	/**
	 * Set the error message used when the validation fails
	 * @param string $message
	 */
	public function setErrorMessage($message) {
		$this->message = $message;
	}
	
	/**
	 * Get the error message used when the validation fails
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->message;
	}
}

?>