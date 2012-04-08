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

// no direct access
use dottedBytes\libs\users\permissions\Permission;

use dottedBytes\libs\users\permissions\PermissionSet;

use dottedBytes\libs\users\auth\AuthSystem;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\utils\ObjectUtils;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\errorHandling\ErrorToException;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\logging\LogFactory;

use dottedBytes\libs\utils\Comparable;

use \PDO;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

/**
 * Describe a user.
 *
 */
class User implements Comparable {
	
	/**
	 * User ID
	 *
	 * @var int
	 */
	protected $id;
	
	/**
	 * Name
	 *
	 * @var string
	 */
	protected $name;
	/**
	 * Username
	 *
	 * @var string
	 */
	protected $username;
	/**
	 * Email address
	 *
	 * @var string
	 */
	protected $email;
	/**
	 * Hashed password
	 *
	 * @var string
	 */
	protected $password;
	/**
	 * Registration date
	 *
	 * @var int
	 */
	protected $regDate;
	/**
	 * Last visit date
	 *
	 * @var int
	 */
	protected $visitDate;
	/**
	 * SessionID
	 *
	 * @var string
	 */
	protected $sessionID = '';
	/**
	 * IP address
	 *
	 * @var string
	 */
	protected $IP;
	/**
	 * Message
	 *
	 * @var string
	 */
	protected $msg;
	/**
	 * Session expire time
	 *
	 * @var int
	 */
	protected $expire;
	/**
	 * Url
	 *
	 * @var string
	 */
	protected $url;
	
	/**
	 * @var boolean
	 */
	protected $hidden;
	/**
	 * Referer
	 *
	 * @var string
	 */
	protected $referer;
	/**
	 * Client
	 *
	 * @var string
	 */
	protected $client;
	
	/**
	 * The preferred language
	 * @var string
	 */
	protected $language;
	
	/**
	 * The preferred language (short notation)
	 * @var string
	 */
	protected $isoLanguage;
	
	/**
	 * The group this user is member
	 *
	 * @var Group
	 */
	protected $group;
	
	/**
	 * The permissions of this user
	 *
	 * @var PermissionSet
	 */
	protected $perms;
	
	protected $attributes = array ();
	
	public function __construct(UserBuilder $builder) {
		$this->id = $builder->getId ();
		$this->buildUser ( $builder );
	}
	
	protected function buildUser(UserBuilder $builder) {
		$this->id = $builder->getId ();
		$this->name = $builder->getName ();
		$this->username = $builder->getUsername ();
		$this->email = $builder->getEmail ();
		$this->password = $builder->getPassword ();
		$this->regDate = $builder->getRegDate ();
		$this->visitDate = $builder->getVisitDate ();
		$this->sessionID = $builder->getSessionID ();
		$this->IP = $builder->getIP ();
		$this->msg = $builder->getMsg ();
		$this->expire = $builder->getExpire ();
		$this->url = $builder->getUrl ();
		$this->hidden = $builder->getHidden ();
		$this->referer = $builder->getReferer ();
		$this->client = $builder->getClient ();
		$this->setLanguage ( $builder->getLanguage () );
		$this->perms = $builder->getPermissions ();
		$this->group = $builder->getGroup ();
		
		$this->perms->addAll ( $this->group->getPermissions () );
		foreach ( $builder->getAttributes () as $key => $value ) {
			$this->setAttribute ( $key, $value );
		}
	}
	
	public function grantPermission(Permission $perm) {
		$this->perms->add ( $perm );
	}
	
	public function revokePermission(Permission $perm) {
		$this->perms->remove ( $perm );
	}
	
	public function mergePerms(PermissionSet $perms) {
		$this->perms->addAll ( $perms );
	}
	
	public function hasPermission($required) {
		return UserUtils::hasPermission($this, $required);
	}
	
	public function hasPermissions(PermissionSet $required) {
		return UserUtils::hasPermissions($this, $required);
	}
	
	public function getAttribute($key) {
		if (! array_key_exists ( $key, $this->attributes ))
			return null;
		return $this->attributes [$key];
	}
	
	public function setAttribute($key, $value) {
		$this->attributes [$key] = $value;
	}
	
	/**
	 * Logout current user
	 *
	 * @return boolean
	 */
	public function logout() {
		return UserUtils::logout ( $this );
	}
	
	/**
	 * Log activity for current user
	 *
	 * @param string $message 	Description of the activity
	 * @param string $title 	Title of the activity
	 * @return boolean
	 */
	public function logActivity($message, $page = '') {
		if ($page == '')
			$page = PageData::getParam ( 'section' );
		
		$value = array ($this->__toString (), $page, $message );
		
		$activityLogger = LogFactory::getLogger ( "activityLog" );
		$activityLogger->log ( $value );
	}
	
	public function logConnection() {
		try {
			$host = gethostbyaddr ( UserUtils::detectIP () );
		} catch ( ErrorToException $_ ) {
			$host = '----';
		}
		$value = array ("hostname=" . $host, "user=" . $this->__toString (), "url=" . UserUtils::detectUrl (), "referer=" . UserUtils::detectReferer (), "browser=" . UserUtils::detectClient () );
		$connectionLogger = LogFactory::getLogger ( "connectionLog" );
		$connectionLogger->log ( $value );
	}
	
	/**
	 * Return a 2 letter language code
	 * @return string
	 */
	public function getShortLanguage() {
		return substr ( $this->isoLanguage, 0, 2 );
	}
	
	/**
	 * Return a 3 letter ISO 4217 language code
	 * @return string
	 */
	public function getISOLanguage() {
		return $this->isoLanguage;
	}
	
	/**
	 * Return the complete language name
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}
	
	/**
	 * @param string $language the 3 letter ISO 4217 language code
	 */
	private function setLanguage($language) {
		if (strlen ( $language ) > 3) {
			$language = substr ( $language, 0, 3 );
		}
		$langs = LocaleUtils::langlist();
		if (array_key_exists ( $language, $langs ))
			$this->language = $langs [$language];
		$this->isoLanguage = $language;
	}
	
	public function equals(Comparable $obj = null) {
		ObjectUtils::checkType ( $obj, 'dottedBytes\libs\users\User' );
		/* @var $obj User */
		return ($this->id == $obj->getId ());
	}
	
	public function compareTo(Comparable $obj = null) {
		ObjectUtils::checkType ( $obj, 'dottedBytes\libs\users\User' );
		/* @var $obj User */
		if ($this->id == $obj->getId ())
			return 0;
		if ($this->id < $obj->getId ())
			return - 1;
		return 1;
	}
	
	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * @return Group
	 */
	public function getGroup() {
		return $this->group;
	}
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * @return DateTime
	 */
	public function getRegDate() {
		return $this->regDate;
	}
	
	/**
	 * @return string
	 */
	public function getSessionID() {
		return $this->sessionID;
	}
	
	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @return DateTime
	 */
	public function getVisitDate() {
		return $this->visitDate;
	}
	
	/**
	 * @return string
	 */
	public function getClient() {
		return $this->client;
	}
	
	/**
	 * @return int
	 */
	public function getExpire() {
		return $this->expire;
	}
	
	/**
	 * @return string
	 */
	public function getIP() {
		return $this->IP;
	}
	
	/**
	 * @return string
	 */
	public function getMsg() {
		return $this->msg;
	}
	
	/**
	 * Return previous page inside the site, cannot detect referer from other pages (use detectReferer() instead)
	 * @return string
	 */
	public function getReferer() {
		return $this->referer;
	}
	
	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * @return boolean
	 */
	public function getHidden() {
		return $this->hidden;
	}
	
	/**
	 * @return PermissionSet
	 */
	public function getPermissions() {
		return $this->perms;
	}
	
	/**
	 * Login current user and if success update this user object with user information
	 *
	 * @param AuthSystem 	The system to use for login
	 * @return int		 	the userID of the logged user or throw an AuthException in other cases
	 */
	public function login(AuthSystem $authSystem) {
		return $authSystem->login ();
	}
	
	public function sendMessage($msg) {
		$database = DBManager::getInstance ();
		$query = $database->prepare ( "UPDATE #__session SET msg=:msg WHERE sessionID=:sessionID" );
		$query->bindParam ( ':msg', $msg, PDO::PARAM_STR );
		$query->bindParam ( ':sessionID', $this->sessionID, PDO::PARAM_STR );
		$query->execute ();
	}
	
	public function __toString() {
		if ($this->id != 0)
			return $this->username . '(#' . $this->id . ')';
		else
			return 'Guest(#0)';
	}
}

?>