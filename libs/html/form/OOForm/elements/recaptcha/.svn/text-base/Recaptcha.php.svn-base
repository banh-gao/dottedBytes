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

use OOForm\Form;

use OOForm\elements\InputField;
use OOForm\decorator\FormDecorator;

require_once dirname ( __FILE__ ) . '/recaptchalib.php';

/**
 * Create a captcha field for the form, the value is automatically checked in the call of {@link OOFormRequest::detectRequest()} method
 * <b>Use only one captcha field per form or else the validation doesn't work correctly</b>
 * 
 */
class Recaptcha extends InputField {
	
	private $publicKey;
	
	/**
	 * Create a captcha field for the form, the value is automatically checked in the call of {@link OOFormRequest::detectRequest()} method
	 * <b>Use only one captcha field per form or else the validation doesn't work correctly</b>
	 * @param string $publicKey The public key from your recaptcha website account 
	 */
	public function __construct($publicKey) {
		parent::__construct ( 'recaptcha_response_field' );
		$this->publicKey = $publicKey;
		$this->tagName = 'div';
	}
	
	public function renderTag(FormDecorator $decorator) {
		return recaptcha_get_html ( $this->publicKey );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see InputField::setLastValue()
	 */
	public function setLastValue($lastValue) {
		//Ignore last value because it is no more valid
		return;
	}
	
	/**
	 * Validate the captcha field
	 * @param string $privateKey the private key provided on the account of the recaptcha website
	 */
	public static function validate($privateKey) {
		$request = Form::getRequest ();
		$request->getValue( 'recaptcha_response_field', new RecaptchaValidator ( $privateKey ) );
	}
}

?>