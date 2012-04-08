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

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\io\Filter;

use dottedBytes\libs\users\User;

use dottedBytes\libs\users\UserBuilder;

use dottedBytes\libs\io\FileUtils;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\users\auth\AuthException;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\io\PageData;

use DateTimeZone;

use PDO;

use Auth_OpenID;
use Auth_OpenID_FileStore;
use Auth_OpenID_Consumer;
use Auth_OpenID_SRegRequest;
use Auth_OpenID_SRegResponse;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

$oldDir = getcwd ();
chdir ( dirname ( __FILE__ ) );
FileUtils::loadFile ( 'libs/errorHandling/ExceptionHandler' );
FileUtils::loadFile ( 'libs/errorHandling/ErrorToException' );
FileUtils::loadFile ( 'modules/userManager/authSystems/openID/Auth/OpenID' );
FileUtils::loadFile ( 'modules/userManager/authSystems/openID/Auth/OpenID/FileStore' );
FileUtils::loadFile ( 'modules/userManager/authSystems/openID/Auth/OpenID/Consumer' );
FileUtils::loadFile ( 'modules/userManager/authSystems/openID/Auth/OpenID/SReg' );
chDir ( $oldDir );

class OpenIDUtils {
	
	/**
	 * Send a request to a openID provider, throw an OpenIDException if the request fails
	 * @param string $identifier - The url of the provider to contact
	 * @param string $return_to - The url where the provider must send the response
	 * @throws OpenIDException
	 */
	public static function sendOpenIDUserRequest($identifier, $return_to) {
		$identifier = strtolower ( trim ( $identifier ) );
		
		$store = new Auth_OpenID_FileStore ( '/tmp' );
		$consumer = new Auth_OpenID_Consumer ( $store );
		$auth_request = $consumer->begin ( $identifier );
		
		if (! $auth_request) {
			throw new OpenIDException ( 'Invalid OpenID identifier', 0, USERMANAGER_OPENID_LOGIN_INCORRECT );
		}
		
		$sreg_request = Auth_OpenID_SRegRequest::build ( array ('nickname', 'fullname', 'email', 'language' ) );
		
		if ($sreg_request) {
			$auth_request->addExtension ( $sreg_request );
		}
		
		$redirect_url = $auth_request->redirectURL ( BASEURL, $return_to );
		
		if (Auth_OpenID::isFailure ( $redirect_url )) {
			throw new OpenIDException ( 'Connection to identity provider failed', 0, USERMANAGER_OPENID_LOGIN_DOWN );
		} else {
			// Send redirect.
			header ( "Location: " . $redirect_url );
			exit ();
		}
	}
	
	/**
	 * Returns a User object with the values setted on the response from the OpenID provider
	 * If the user is registered, the returned user will have all user informations,
	 * otherwise a guest user is returned filled only with values provided by OpenID
	 *
	 * @param string $return_to - The same url specified at the moment of the request
	 * @return User
	 */
	public static function catchOpenIDResponse($return_to) {
		$store = new Auth_OpenID_FileStore ( '/tmp' );
		$consumer = new Auth_OpenID_Consumer ( $store );
		$response = $consumer->complete ( $return_to );
		// Check the response status.
		if ($response->status == Auth_OpenID_CANCEL) {
			throw new OpenIDException ( $response->message, 0, USERMANAGER_OPENID_LOGIN_CANCELED );
		} else if ($response->status == Auth_OpenID_FAILURE) {
			throw new OpenIDException ( Auth_OpenID_FAILURE, 0, $response->message );
		} else if ($response->status == Auth_OpenID_SUCCESS) {
			$identifier = $response->getDisplayIdentifier ();
			
			$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse ( $response );
			
			$sreg = $sreg_resp->contents ();
			
			$email = (array_key_exists('email', $sreg)) ? $sreg ['email'] : '';
			$username = (array_key_exists('nickname', $sreg)) ?$sreg ['nickname'] : '';
			$name = (array_key_exists('fullname', $sreg)) ? $sreg ['fullname'] : '';
			$language = (array_key_exists('language', $sreg)) ? strtolower ( $sreg ['language'] ) : LocaleUtils::getBrowserLanguage(true);
			
			$database = DBManager::getInstance ();
			$identifier = parse_url ( $identifier, PHP_URL_HOST );
			$query = $database->prepare ( "SELECT uid FROM #__users_openID WHERE identifier=?" );
			$query->bindParam ( 1, $identifier, PDO::PARAM_STR );
			$query->execute ();
			
			$openID = new OpenID ();
			$openID->setUrl ( $identifier );
			$openID->setActive ( true );
			
			if ($query->rowCount () < 1) {
				//User not registered
				$builder = new UserBuilder ();
				$builder->email ( $email )->username ( $username )->name ( $name )->language ( $language );
				$builder->attribute ( 'openID', $openID );
				$user = new User ( $builder );
			} else {
				$uid = $query->fetchColumn ( 0 );
				$user = UserUtils::getUser ( ( int ) $uid );
			}
			$user->setAttribute ( 'openID', $openID );
			return $user;
		}
	}
}

?>