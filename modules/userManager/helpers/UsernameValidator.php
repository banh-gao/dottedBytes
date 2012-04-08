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

namespace dottedBytes\modules\userManager\helpers;

use OOForm\validator\RegexValidator;

use \PDO;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\database\DBManager;

class UsernameValidator extends RegexValidator {
	
	const USERNAME_PATTERN = '/([-a-zA-Z0-9\.]){4,30}/';
	
	public function __construct() {
		parent::__construct(self::USERNAME_PATTERN);
	}
	
	public function validate($value) {
		return parent::validate($value) && $this->validateUniqueUsername($value);
	}
	
	private function validateUniqueUsername($user) {
		$db = DBManager::getInstance();
		$query = $db->prepare ( "SELECT username FROM #__users WHERE username=?" );
		$query->bindParam ( 1, $user, PDO::PARAM_STR );
		$query->execute ();
		
		if ($query->rowCount () > 0) {
			$this->setErrorMessage(sprintf ( USERMANAGER_REGISTRATION_UNOTAVAILABLE, $user ));
			return false;
		}
		return true;
	}
}

?>