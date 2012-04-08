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

namespace OOForm\validator;

/**
 * A validator that check for string length
 * @author Daniel Zozin
 *
 */
class LengthValidator extends InputValidator {
	
	private $minLength;
	private $maxLength;
	
	/**
	 * Create a validator that check for string length
	 * @param int $minLength
	 * @param int $maxLength
	 */
	public function __construct($minLength = false, $maxLength = false) {
		$this->minLength = $minLength;
		$this->maxLength = $maxLength;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see InputValidator::validate()
	 */
	public function validate($value) {
		$checkValue = trim ( $value );
		
		if ($this->length ( $value ))
			return true;
		
		//Build min max error message
		if ($this->minLength || $this->maxLength) {
			$minMaxMsg = array ();
			if ($this->minLength != false)
				$minMaxMsg [] = sprintf ( OOFORM_VALIDATOR_LENGTH_MIN, $this->minLength );
			if ($this->maxLength != false)
				$minMaxMsg [] = sprintf ( OOFORM_VALIDATOR_LENGTH_MAX, $this->maxLength );
			$minMaxMsg = '(' . implode ( ',', $minMaxMsg ) . ')';
		} else {
			$minMaxMsg = '';
		}
		$this->setErrorMessage ( sprintf ( OOFORM_VALIDATOR_LENGTH, $minMaxMsg ) );
		return false;
	
	}
	
	private function length($input) {
		$length = strlen ( $input );
		if ($this->minLength <= $length || $this->minLength == false) {
			if ($length <= $this->maxLength || $this->maxLength == false)
				return true;
		}
		return false;
	}
	/**
	 * Get the minimum string length accepted
	 * @return int $minLength
	 */
	public function getMinLength() {
		return $this->minLength;
	}
	
	/**
	 * Set the minimum string length accepted
	 * @param int $minLength
	 */
	public function setMinLength($minLength) {
		$this->minLength = $minLength;
	}
	
	/**
	 * Get the maximum string length accepted
	 * @return int $maxLength
	 */
	public function getMaxLength() {
		return $this->maxLength;
	}
	
	/**
	 * Set the maximum string length accepted
	 * @param int $maxLength
	 */
	public function setMaxLength($maxLength) {
		$this->maxLength = $maxLength;
	}
}

?>