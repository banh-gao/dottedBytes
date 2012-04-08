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

// no direct access
use dottedBytes\libs\users\permissions\Permission;

use dottedBytes\libs\configuration\Configuration;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\logging\AuthLog;

use dottedBytes\libs\users\UserUtils;

use \PDO;

use \Exception;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

/**
 * The implementations of this abstract class will be used for the login using the method provided by the subclass implementation
 */
abstract class AuthSystem {
	
	/**
	 * This method should return the userID associated with the login request or an AuthException when there is no association
	 * @return int
	 */
	protected abstract function getLoginUserID();
	
	/**
	 * This method should return true if the login is correct or thrown an appropriate AuthException if failed
	 * @return boolean
	 */
	protected abstract function validateLogin();
	
	/**
	 * If true the login will ignore multiple login attepts (DISABLE BRUTEFORCE PROTECTION)
	 * @var boolean
	 */
	protected $ignoreAttept = false;
	
	protected $uid = 0;
	
	private $lastAttept;
	
	/**
	 * Perform login of the current user. To perform the login this class must be subclassed in order to implement abstract methods getLoginUserID and validateLogin
	 * @return int the userID if success or throw an AuthException if failed
	 */
	public final function login() {
		$this->uid = $this->getLoginUserID ();
		
		//Check if the user have all the permissions to login
		$this->checkLoginPerms ();
		
		//Perform the effective login
		try {
			$this->validateLogin ();
		} catch ( Exception $e ) {
			//Login failed, log the attept and throw the security exception
			$this->logAttept ();
			throw $e;
		}
		
		//Success login - update user information
		$this->updateUser ();
		UserUtils::reloadCurrentUser();
		AuthLog::log ( "Login using " . $this->__toString (), false , UserUtils::getCurrentUser());
		return $this->uid;
	}
	
	private function checkLoginPerms() {
		$user = UserUtils::getUser ( $this->uid );
		$accessPerm = Permission::getByName ( 'access' );
		if (! $user->getPermissions ()->contains ( $accessPerm )) {
			throw new PermissionException ( $accessPerm, USERMANAGER_LOGIN_NOPRIV, $this->uid );
		}
	}
	
	private function logAttept() {
		if ($this->ignoreAttept != true)
			$this->addAttept ();
		
		if (! $this->checkAttepts ( $this->uid )) {
			throw new AuthException ( "Potential bruteforce attack detected using " . $this->__toString (), 0, USERMANAGER_LOGIN_MAXATTEPTS, $this->uid );
		}
	}
	
	private function updateUser() {
		$uid = $this->uid;
		$database = DBManager::getInstance ();
		
		$query = $database->prepare ( "SELECT * FROM #__session WHERE uid=:uid" );
		$query->bindParam ( ':uid', $uid, PDO::PARAM_INT );
		$query->execute ();
		
		if ($query->rowCount () < 1) {
			$query = $database->prepare ( "DELETE FROM #__users_login WHERE uid=:uid" );
			$query->bindParam ( ':uid', $uid, PDO::PARAM_INT );
			$query->execute ();
		} else {
			throw new AuthException ( 'Login with account of ' . UserUtils::getUser ( $uid ) . ' in use', 0, USERMANAGER_LOGIN_INUSE, $uid );
		}
		
		$query = $database->prepare ( "UPDATE #__session SET uid=:uid WHERE sessionID=:session_id" );
		$query->bindParam ( ':uid', $uid, PDO::PARAM_INT );
		$sid = session_id ();
		$query->bindParam ( ':session_id', $sid, PDO::PARAM_STR );
		$query->execute ();
		
		// Refresh the last visit date in users table
		$query = $database->prepare ( "UPDATE #__users SET visitDate=NOW() WHERE uid=:uid" );
		$query->bindParam ( ':uid', $uid, PDO::PARAM_INT );
		$query->execute ();
	}
	
	private function addAttept() {
		$uid = $this->uid;
		$database = DBManager::getInstance ();
		$query = $database->prepare ( "SELECT * FROM #__users_login WHERE uid=:uid" );
		$query->bindParam ( ':uid', $uid, PDO::PARAM_INT );
		$query->execute ();
		
		if ($query->rowCount () > 0) {
			$row = $query->fetch ();
			$this->lastAttept = $row->lastAttept;
			
			//Update the attepts counter
			$query = $database->prepare ( "UPDATE #__users_login SET loginAttepts=loginAttepts+1 , lastAttept=:last WHERE uid=:uid" );
			$time = time ();
			$query->bindParam ( ':last', $time, PDO::PARAM_INT );
			$query->bindParam ( ':uid', $uid, PDO::PARAM_INT );
			$query->execute ();
		} else {
			//First attept
			$query = $database->prepare ( "INSERT INTO #__users_login VALUES(:uid,'1',:last)" );
			$time = time ();
			$query->bindParam ( ':last', $time, PDO::PARAM_INT );
			$query->bindParam ( ':uid', $uid, PDO::PARAM_INT );
			$query->execute ();
		}
		return true;
	}
	
	private function checkAttepts() {
		$enableAttepts = Configuration::getValue ( "system.users.atteptControl.enable" );
		if ($enableAttepts == false)
			return true;
		
		$uid = $this->uid;
		
		$maxAttepts = Configuration::getValue ( "system.users.atteptControl.maxAttepts" );
		$database = DBManager::getInstance ();
		$query = $database->prepare ( "SELECT * FROM #__users_login WHERE uid=?" );
		$query->bindParam ( 1, $uid, PDO::PARAM_INT );
		$query->execute ();
		
		if($query->rowCount() < 1)
			return true;
		
		$row = $query->fetch ();
		
		if ($row->loginAttepts >= $maxAttepts) {
			return $this->checkAtteptDelayExpirationTime ( $this->lastAttept );
		}
		return true;
	}
	
	private function checkAtteptDelayExpirationTime($lastAttept) {
		$uid = $this->uid;
		
		$database = DBManager::getInstance ();
		$atteptDelay = Configuration::getValue ( "system.users.atteptControl.retryingDelay" );
		$currentTime = time ();
		if (($lastAttept + $atteptDelay < $currentTime) && $atteptDelay != 0) {
			//Attept delay expired, restart attept counter
			$query = $database->prepare ( "UPDATE #__users_login SET loginAttepts=0 , lastAttept=:last WHERE uid=:uid" );
			$query->bindParam ( ':last', $currentTime, PDO::PARAM_INT );
			$query->bindParam ( ':uid', $uid, PDO::PARAM_INT );
			$query->execute ();
			return true;
		} else {
			//Attept delay not expired, update last attept date and refuse login request
			$query = $database->prepare ( "UPDATE #__users_login SET lastAttept=:last WHERE uid=:uid" );
			$query->bindParam ( ':last', $currentTime, PDO::PARAM_INT );
			$query->bindParam ( ':uid', $uid, PDO::PARAM_INT );
			$query->execute ();
			return false;
		}
	}
	
	public function __toString() {
		return get_class ( $this );
	}
}

?>