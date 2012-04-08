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

namespace dottedBytes\modules\userManager\authSystems;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\users\auth\AuthException;

use dottedBytes\libs\users\auth\AuthSystem;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\io\PageData;

use \PDO;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

/**
 * Provide an authentication system using the information coming from an http login request
 *
 */
class HTTPLogin extends AuthSystem {
	
	protected function getLoginUserID() {
		return $this->getUserID ( PageData::getParam ( 'username' ) );
	}
	
	protected function validateLogin() {
		return $this->validateUser ( PageData::getParam ( 'username' ), PageData::getParam ( 'password' ) );
	}
	
	protected function getUserID($username) {
		$database = DBManager::getInstance ();
		$query = $database->prepare ( "SELECT uid FROM #__users WHERE username=:user" );
		$query->bindParam ( ':user', $username, PDO::PARAM_STR );
		$query->execute ();
		
		if ($query->rowCount () < 1)
			throw new AuthException ( 'Login with unexistent username', 0, USERMANAGER_LOGIN_INCORRECT );
		
		return $query->fetchColumn ( 0 );
	}
	
	protected function validateUser($username, $submittedPassword) {
		$database = DBManager::getInstance ();
		$query = $database->prepare ( "SELECT password FROM #__users WHERE username=:user" );
		$query->bindParam ( ':user', $username, PDO::PARAM_STR );
		$query->execute ();
		
		if ($query->rowCount () < 1)
			throw new AuthException ( 'Invalid username', 0, USERMANAGER_LOGIN_INCORRECT, $this->uid );
		
		$saltAndHash = $query->fetchColumn(0);
		if(UserUtils::checkPassword($submittedPassword, $saltAndHash) == false)
			throw new AuthException ( 'Invalid login password', 0, USERMANAGER_LOGIN_INCORRECT, $this->uid );
			
		return true;
	}
}
?>