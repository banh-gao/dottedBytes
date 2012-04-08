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
 * Validate a domain name (mail.mydomain.com)
 * @author Daniel Zozin
 *
 */
use OOForm\validator\InputValidator;

class ChoiceValidator extends InputValidator {
	
	private $choices = array ();
	
	/**
	 * This validator accept only the specified predefined values
	 * @param array $choices
	 */
	public function __construct($choices) {
		$this->setChoices($choices);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see OOForm\validator.InputValidator::validate()
	 */
	public function validate($value) {
		return array_search($value, $this->choices) !== false;
	}
	
	/**
	 * Get the accepted choices
	 * @return the $choices
	 */
	public function getChoices() {
		return $this->choices;
	}

	/**
	 * Set the accepted values
	 * @param array $choices
	 */
	public function setChoices($choices) {
		if (! is_array ( $choices ))
			$choices = array ($choices );
		$this->choices = $choices;
	}

}

?>