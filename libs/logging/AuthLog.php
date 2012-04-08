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

namespace dottedBytes\libs\logging;

use dottedBytes\libs\users\User;

use dottedBytes\libs\users\UserUtils;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class AuthLog {
	
	private static $logger;
	
	private static function init() {
		if (self::$logger == null)
			self::$logger = LogFactory::getLogger ( "authenticationLog" );
	}
	
	/**
	 * Write a line in authentication log
	 *
	 * @param string $action
	 * @param string $result
	 * @return boolean
	 */
	public static function log($action, $isFailed = true, User $user = null) {
		self::init ();
		if ($user == null)
			$user = UserUtils::getCurrentUser ();
		$username = ($user->getId () == 0) ? 'Guest' : $user;
		
		$status = ($isFailed) ? 'Failed' : 'Success';
		
		$values = array ($username, "action=" . $action, "result=" . $status, "url=" . $user->getUrl (), "referer=" . $user->getReferer (), "browser=" . $user->getClient (), "language=" . $user->getLanguage () );
		return self::$logger->log ( $values );
	}
}

?>