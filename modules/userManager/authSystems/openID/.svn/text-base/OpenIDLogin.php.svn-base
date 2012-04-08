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

namespace dottedBytes\modules\userManager\authSystems\openID;

// no direct access
use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\users\auth\AuthException;

use dottedBytes\modules\userManager\authSystems\openID\OpenIDException;

use dottedBytes\modules\userManager\authSystems\openID\OpenIDUtils;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\users\auth\AuthSystem;

use dottedBytes\libs\users\UserUtils;

use \PDO;

use \PDOStatement;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class OpenIDLogin extends AuthSystem {
	
	protected function validateLogin() {
		$referer = urlencode ( UserUtils::getCurrentUser ()->getReferer () );
		$return = BASEURL . '/index.php?section=userManager&task=login&act=openID_confirm&referer=' . $referer;
		if (PageData::getParam ( 'act', '' ) == 'openID_confirm') {
			try {
				$result = OpenIDUtils::catchOpenIDResponse ( $return );
			} catch ( OpenIDException $e ) {
				switch ($e->getDetails ()) {
					case Auth_OpenID_CANCEL :
						throw new AuthException ( 'OpenID login cancel', 0, USERMANAGER_OPENID_LOGIN_CANCELED , $this->getLoginUserID());
						break;
					case Auth_OpenID_FAILURE :
						throw new AuthException ( 'OpenID login failed', 0, $e->getMessage () , $this->getLoginUserID());
						break;
				}
			}
			return true;
		}
		
		if (! ModUtils::getCurrentModule ()->getConfigValue( 'account.enableOpenID', false ))
			throw new AuthException ( 'OpenID service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$identifier = PageData::getParam ( 'openid_identifier' );
		
		//Check if the openID identifier is registered for a user
		$database = DBManager::getInstance ();
		$query = $database->prepare ( "SELECT * FROM #__users_openID WHERE identifier=? AND active=1" );
		
		$query->bindParam ( 1, $identifier, PDO::PARAM_STR );
		$query->execute ();
		
		//If the corrisponding user doesn't exist return login error
		if ($query->rowCount () != 1) {
			throw new AuthException ( 'Wrong OpenID identifier', 0, USERMANAGER_OPENID_LOGIN_INCORRECT );
		}
		OpenIDUtils::sendOpenIDUserRequest ( $identifier, $return );
	}
	
	protected function getLoginUserID() {
		$database = DBManager::getInstance ();
		$identifier = PageData::getParam ( 'openid_identifier' );
		
		//The user is valid because this is a response coming from the identity provider
		$uid = $this->catchResponse ();
		if ($uid != 0) {
			$this->ignoreAttept = true;
			return $uid;
		}
		$query = $database->prepare ( "SELECT uid FROM #__users_openID WHERE identifier=? AND active=1" );
		$query->bindParam ( 1, $identifier, PDO::PARAM_STR );
		$query->execute ();
		
		if ($query->rowCount () < 1) {
			return 0;
		}
		return $query->fetchColumn ( 0 );
	}
	
	private function catchResponse() {
		//Ignore attept because this is the identity provider response
		try {
			$referer = urlencode ( UserUtils::getCurrentUser ()->getReferer () );
			$return = BASEURL . '/index.php?section=userManager&task=login&act=openID_confirm&referer=' . $referer;
			$result = OpenIDUtils::catchOpenIDResponse ( $return );
		} catch ( OpenIDException $e ) {
			return 0;
		}
		return $result->getId ();
	}
}

?>