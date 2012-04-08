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

namespace OOForm\elements\recaptcha;

use OOForm\validator\EmptyValidator;

use OOForm\validator\InputValidator;

use OOForm\Form;

require_once dirname ( __FILE__ ) . '/recaptchalib.php';

/**
 * This validator is intended to be used to check a recaptcha challenge
 * @author Daniel Zozin
 *
 */
class RecaptchaValidator extends InputValidator {
	
	private $privateKey;
	
	/**
	 * This validator is intended to be used to check a recaptcha challenge
	 * @param string $privateKey The private key from your recaptcha website account 
	 */
	public function __construct($privateKey) {
		$this->privateKey = $privateKey;
		$this->setErrorMessage ( OOFORM_VALIDATOR_CAPTCHA );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see InputValidator::validate()
	 */
	public function validate($value = '') {
		if(!array_key_exists('recaptcha_response_field', $_POST) || !array_key_exists('recaptcha_challenge_field', $_POST))
			return false;
		$resp = recaptcha_check_answer ( $this->privateKey, $_SERVER ["REMOTE_ADDR"], $_POST ["recaptcha_challenge_field"], $_POST ["recaptcha_response_field"] );
		unset ( $_POST ['recaptcha_challenge_field'] );
		unset ( $_POST ['recaptcha_response_field'] );
		return $resp->is_valid;
	}
}

?>