<?php
/**
 * @package		DottedBytes
 * @copyright	Copyright (C) 2009 -2010 DottedBytes. All rights reserved.
 * @license		GNU/GPL, see COPYING file
 * @author		Daniel Zozin
 *
 * This file is part of DottedBytes.
 * DottedBytes is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * DottedBytes is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DottedBytes.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace dottedBytes\libs\html\form;

use dottedBytes\libs\io\FileUtils;

use dottedBytes\libs\configuration\Configuration;

FileUtils::loadFile ( 'libs/html/form/OOForm/Form' );

use OOForm\elements\recaptcha\Recaptcha;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

class Captcha extends Recaptcha {
	
	/**
	 * Create a captcha field, the field is automatically initialized
	 */
	public function __construct() {
		$publicKey = Configuration::getValue('system.common.recaptcha.publicKey');
		parent::__construct($publicKey);
	}
	
	public static function checkCaptcha() {
		return Recaptcha::validate ( "6Le9xMASAAAAAMJsvYkTGwzgBJJq5OH1ITP-CPc3" );
	}
}

?>