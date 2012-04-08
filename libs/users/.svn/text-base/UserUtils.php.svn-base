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

namespace dottedBytes\libs\users;

use dottedBytes\libs\users\permissions\Permission;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\logging\AuthLog;

use dottedBytes\libs\users\permissions\PermissionSet;

use dottedBytes\libs\database\DatabaseException;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\configuration\Configuration;

use dottedBytes\libs\database\DBManager;

use PDO;

use DateTime;

// no direct access
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class UserUtils {
	
	private static $ignoreUserUpdate;
	
	/**
	 * @var User
	 */
	private static $currentUser = null;
	
	/**
	 * Get the current user that request the page
	 * @return User
	 */
	static public function getCurrentUser() {
		if (self::$currentUser == null) {
			self::buildCurrentUser ();
		}
		return self::$currentUser;
	}
	
	static public function reloadCurrentUser() {
		self::buildCurrentUser ();
	}
	
	private static function buildCurrentUser() {
		$builder = self::fetchCurrentUserData ();
		self::$currentUser = new User ( $builder );
		self::removeExpiredSessions ();
		$_SESSION ['isEditorUser'] = self::$currentUser->getPermissions ()->contains ( Permission::getByName ( 'editor' ) );
	}
	
	private static function fetchCurrentUserData() {
		$sessionID = session_id ();
		$database = DBManager::getInstance ();
		
		$result = $database->query ( "SELECT * FROM #__session WHERE sessionID='$sessionID'" );
		
		if ($result->rowCount () < 1) { //New user (first opened page)
			$sessionID = self::createNewSession ();
			$userBuilder = new GuestBuilder ();
			$userBuilder->sessionID ( $sessionID );
			$userBuilder->language ( LocaleUtils::getBrowserLanguage () );
			$userBuilder->IP ( self::detectIP () );
			$userBuilder->client ( self::detectClient () );
			$userBuilder->referer ( self::detectReferer () );
			return $userBuilder;
		}
		
		$sessionRes = $result->fetch ();
		
		if ($sessionRes->uid == null) {
			$userBuilder = new GuestBuilder ();
			$userBuilder->language ( LocaleUtils::getBrowserLanguage () );
		} else {
			$userBuilder = new UserBuilder ( $sessionRes->uid );
			self::fetchUserData ( $userBuilder );
		}
		
		self::fetchSessionData ( $userBuilder );
		
		$expireTime = Configuration::getValue ( 'system.users.sessionExpire' ) + time ();
		
		$userBuilder->IP ( self::detectIP () )->sessionID ( $sessionID )->client ( self::detectClient () )->hidden ( $sessionRes->hidden );
		$userBuilder->expire ( $expireTime )->url ( self::detectUrl () )->referer ( $sessionRes->url )->msg ( $sessionRes->msg );
		
		return $userBuilder;
	}
	
	/**
	 * Tell to not update the user information for the current request
	 * @param boolean $ignore
	 */
	public static function setIgnoreUserUpdate($ignore) {
		self::$ignoreUserUpdate = $ignore;
	}
	
	public static function isIgnoreUserUpdate() {
		return self::$ignoreUserUpdate;
	}
	
	public static function updateCurrentUserInfo() {
		if(self::isIgnoreUserUpdate())
			return;
			
		$user = self::getCurrentUser ();
		$database = DBManager::getInstance ();
		$query = $database->prepare ( "UPDATE #__session SET msg='' , addr = ? , client = ? , expire = ? , url = ? , referer = ? , hidden=0  WHERE sessionID = ?" );
		$ip = $user->getIP ();
		$query->bindParam ( 1, $ip, PDO::PARAM_STR );
		$client = $user->getClient ();
		$query->bindParam ( 2, $client );
		$expireTime = $user->getExpire ();
		$query->bindParam ( 3, $expireTime );
		$url = $user->getUrl ();
		$query->bindParam ( 4, $url );
		$referer = $user->getReferer ();
		$query->bindParam ( 5, $referer );
		$sessionID = $user->getSessionID ();
		$query->bindParam ( 6, $sessionID );
		$query->execute ();
	}
	
	private static function createNewSession() {
		$sessionID = session_id ();
		
		$database = DBManager::getInstance ();
		$new = $database->prepare ( "INSERT INTO #__session (uid,sessionID,addr,expire,client,url,referer) VALUES(null,:sessionID,:addr,:expire,:client,:url,:referer)" );
		$new->bindParam ( ':sessionID', $sessionID, PDO::PARAM_STR );
		$ip = self::detectIP ();
		$new->bindParam ( ':addr', $ip, PDO::PARAM_STR );
		$expireTime = self::expireTime ();
		$new->bindParam ( ':expire', $expireTime, PDO::PARAM_INT );
		$client = self::detectClient ();
		$new->bindParam ( ':client', $client, PDO::PARAM_STR );
		$url = self::detectUrl ();
		$new->bindParam ( ':url', $url, PDO::PARAM_STR );
		$referer = self::detectReferer ();
		$new->bindParam ( ':referer', $referer, PDO::PARAM_STR );
		$new->execute ();
		return $sessionID;
	}
	
	private static function removeExpiredSessions() {
		$database = DBManager::getInstance ();
		$new = $database->prepare ( "DELETE FROM #__session WHERE expire < ?" );
		$new->execute ( array (time () ) );
	}
	
	private static function expireTime() {
		return Configuration::getValue ( 'system.users.sessionExpire' ) + time ();
	}
	
	/**
	 * Returns the user corresponding to the specified id, if isGuest is true the function assume id is the sessionID
	 *
	 * @param $id
	 * @return User
	 */
	public static function getUser($id) {
		$id = ( int ) $id;
		
		if ($id == 0)
			$builder = new GuestBuilder ();
		else
			$builder = new UserBuilder ( $id );
		
		try {
			self::fetchUserData ( $builder );
		} catch ( UserException $e ) {
			//Ignore because maybe is a guest
		}
		
		return new User ( $builder );
	}
	
	private static function fetchUserData(UserBuilder $builder) {
		$uid = $builder->getId ();
		try {
			$database = DBManager::getInstance ();
			$result = $database->query ( 'SELECT * FROM #__users WHERE uid=' . $uid );
		} catch ( DatabaseException $e ) {
			throw new UserException ( "Cannot find user with id " . $uid );
		}
		
		if ($result->rowCount () < 1)
			throw new UserException ( "Cannot find user with id " . $uid );
		
		$res = $result->fetch ();
		
		$builder->name ( $res->name )->username ( $res->username )->email ( $res->email );
		$builder->password ( $res->password )->language ( $res->language )->regDate ( new DateTime ( $res->regDate ) )->attribute ( 'activation', $res->activation );
		$group = GroupUtils::getGroup ( ( int ) $res->gid );
		
		$builder->visitDate ( new DateTime ( $res->visitDate ) )->perms ( self::getUserPerms ( $res->uid ) )->group ( $group );
	}
	
	private static function fetchSessionData(UserBuilder $builder) {
		$database = DBManager::getInstance ();
		if ($builder->getId () == 0)
			$query = $database->query ( "SELECT * FROM #__session WHERE sessionID='{$builder->getSessionID()}'" );
		else
			$query = $database->query ( "SELECT * FROM #__session WHERE uid='{$builder->getId()}'" );
		
		if ($query->rowCount () < 1) {
			//User offline
			$builder->sessionID ( 0 )->IP ( 0 )->expire ( - 1 )->url ( '' )->hidden ( false );
			$builder->referer ( '' )->client ( '' );
		} else {
			$sessionRes = $query->fetch ();
			$builder->sessionID ( $sessionRes->sessionID )->IP ( $sessionRes->addr )->msg ( $sessionRes->msg );
			$builder->expire ( $sessionRes->expire )->url ( $sessionRes->url )->hidden ( $sessionRes->hidden );
			$builder->referer ( $sessionRes->referer )->client ( $sessionRes->client );
		}
	}
	
	public static function getConnectedUser($sessionID) {
		$database = DBManager::getInstance ();
		$query = $database->query ( "SELECT * FROM #__session WHERE sessionID='$sessionID'" );
		
		$res = $query->fetch ();
		
		if ($res !== false && $res->uid != 0)
			$user = self::getUser ( $res->uid );
		else {
			$builder = new GuestBuilder ();
			$builder->sessionID ( $sessionID );
			self::fetchSessionData ( $builder );
			$user = new User ( $builder );
		}
		return $user;
	}
	
	/**
	 * Returns the permissions for the specified userID
	 *
	 * @return PermissionSet
	 */
	public static function getUserPerms($uid) {
		$uid = ( int ) $uid;
		$database = DBManager::getInstance ();
		$perms = new PermissionSet ();
		$res = $database->query ( "SELECT permID FROM #__users_perms WHERE uid=$uid" );
		while ( ($permID = $res->fetchColumn ( 0 )) !== false ) {
			$perms->add ( Permission::getById ( $permID ) );
		}
		
		return $perms;
	}

	public static function hasPermission(User $user,$required) {
		if(!($required instanceof Permission))
			$required = Permission::getByName($required);
		$perms = $user->getPermissions ();
		return $perms->contains ( $required );
	}
	
	public static function hasPermissions(User $user,PermissionSet $required) {
		$perms = $user->getPermissions ();
		return $perms->containsAll ( $required );
	}
	
	public static function hideConnectedUser($sessionID) {
		$database = DBManager::getInstance ();
		$result = $database->query ( "UPDATE #__session SET hidden=1 WHERE sessionID='$sessionID'" );
		return ($result->rowCount () == 1);
	}
	
	public static function unhideConnectedUser($sessionID) {
		$database = DBManager::getInstance ();
		$result = $database->query ( "UPDATE #__session SET hidden=0 WHERE sessionID='$sessionID'" );
		return ($result->rowCount () == 1);
	}
	
	/**
	 * Logout specified user
	 *
	 * @param User $user
	 * @return boolean
	 */
	public static function logout(User $user) {
		$database = DBManager::getInstance ();
		$result = $database->query ( "UPDATE #__session SET uid=null WHERE sessionID='{$user->getSessionID()}'" );
		if ($result->rowCount () < 1)
			return false;
		
		AuthLog::log ( "Logout", false, $user );
		return true;
	}
	
	/**
	 * Generate a pair of salt and hashed password in the form:   genSalt:hashedPassword
	 * If no salt is specified then it will be random generated
	 * @param string $plainPassword
	 * @param string $salt - Optional the salt to use
	 * @return string
	 */
	public static function generatePassword($plainPassword, $salt = '') {
		if ($salt == '')
			$salt = String::rand ( 8, 8 );
		$hashPassword = sha1 ( $salt . $plainPassword );
		return $salt . ':' . $hashPassword;
	}
	
	/**
	 * Check if the plain password corresponds to the hashed by hashing it with the salt from the second parameter
	 * @param string $plainPassword - The password to check
	 * @param string $saltAndHash - The salt and password hash in the form:  salt:hashedPassword
	 * @return boolean
	 */
	public static function checkPassword($plainPassword, $saltAndHash) {
		$salt = substr ( $saltAndHash, 0, strpos ( $saltAndHash, ':' ) );
		$generatedSaltHash = self::generatePassword ( $plainPassword, $salt );
		return $generatedSaltHash == $saltAndHash;
	}
	
	/**
	 * Detect the user ip address
	 *
	 * @return string
	 */
	public static function detectIP() {
		if (! empty ( $_SERVER ['HTTP_CLIENT_IP'] )) {
			$ip = $_SERVER ['HTTP_CLIENT_IP'];
		} elseif (! empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
			$ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER ['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	public static function detectClient() {
		return $_SERVER ['HTTP_USER_AGENT'];
	}
	
	public static function detectUrl() {
		return PageData::getPageUrl ();
	}
	
	public static function detectReferer() {
		return (isset ( $_SERVER ['HTTP_REFERER'] )) ? $_SERVER ['HTTP_REFERER'] : '';
	}
}
?>