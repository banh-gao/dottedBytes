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
 * Validate an IP address
 * @author Daniel Zozin
 *
 */
class IPValidator extends InputValidator {
	
	private $ipVersion;
	
	/**
	 * Create a validator for the specified ip version
	 * @param int $ipVersion
	 */
	public function __construct($ipVersion = 4) {
		$this->ipVersion = 4;
		$this->setErrorMessage ( OOFORM_VALIDATOR_IP );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see InputValidator::validate()
	 */
	public function validate($value) {
		
		if ($this->ipVersion == 4) {
			if (filter_var ( $value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) !== false)
				return true;
		} elseif ($this->ipVersion == 6) {
			if (filter_var ( $value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) !== false)
				return true;
		}
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see InputValidator::getErrorMessage()
	 */
	public function getErrorMessage() {
		return sprintf ( parent::getErrorMessage (), $this->ipVersion );
	}
	
	/**
	 * Get the IP version to use for the validation
	 * @return int $ipVersion
	 */
	public function getIpVersion() {
		return $this->ipVersion;
	}
	
	/**
	 * Set the IP version to use for the validation
	 * @param int $ipVersion
	 */
	public function setIpVersion($ipVersion) {
		$this->ipVersion = $ipVersion;
	}

}

?>