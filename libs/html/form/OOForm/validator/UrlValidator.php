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
 * Validate a url (http, https, ftp)
 * @author Daniel Zozin
 *
 */
class UrlValidator extends InputValidator {
	
	public function __construct() {
		$this->setErrorMessage ( OOFORM_VALIDATOR_URL );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see InputValidator::validate()
	 */
	public function validate($value) {
		if (strlen ( $value ) < 1)
			return false;
		
		$urlregex = '!(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/\+#~]*[\w\-\@?^=%&amp;/~\+#])?!';
		if (preg_match ( $urlregex, $value ) === 1)
			return true;
		
		return false;
	}
}

?>