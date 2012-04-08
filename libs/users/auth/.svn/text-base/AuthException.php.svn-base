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

namespace dottedBytes\libs\users\auth;

use dottedBytes\libs\logging\AuthLog;

use dottedBytes\libs\errorHandling\CmsException;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class AuthException extends CmsException {
	private $uid;
	public function __construct($description, $code = 0, $message = '', $uid = 0) {
		$this->uid = $uid;
		AuthLog::log ( $description );
		if ($message == '')
			$message = _SITE_ERROR_NOAUTH_EXPLAIN;
		parent::__construct ( $description, $code, $message );
	}
	
	public function useLog() {
		return false;
	}
	
	public function getTitle() {
		return _SITE_ERROR_NOAUTH;
	}
	
	public function getUid() {
		return $this->uid;
	}
}

?>