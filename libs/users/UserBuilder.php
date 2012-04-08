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
use dottedBytes\libs\users\permissions\PermissionSet;

use \DateTime;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class UserBuilder {
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
	 * Hide in connected users list
	 * 
	 * @var boolean
	 */
	protected $hidden;
	
	/**
	 * Permission of the user
	 * 
	 * @var PermissionSet
	 */
	protected $perms;
	
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
	 * The group this user is member
	 *
	 * @var Group
	 */
	protected $group;
	
	protected $attributes = array ();
	
	public function __construct($uid = 0) {
		$this->id = $uid;
		$this->perms = new PermissionSet();
		$this->group = new Group ( 0 );
	}
	
	/**
	 * @param string $email
	 * @return UserBuilder
	 */
	public function email($email) {
		$this->email = $email;
		return $this;
	}
	
	/**
	 * @param string $language
	 * @return UserBuilder
	 */
	public function language($language) {
		$this->language = $language;
		return $this;
	}
	
	/**
	 * Assign the user to the group
	 * @param Group $group
	 * @return UserBuilder
	 */
	public function group(Group $group) {
		$this->group = $group;
		return $this;
	}
	
	/**
	 * @param string $name
	 * @return UserBuilder
	 */
	public function name($name) {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * @param string $password
	 * @return UserBuilder
	 */
	public function password($password) {
		$this->password = $password;
		return $this;
	}
	
	/**
	 * @param DateTime $regDate
	 * @return UserBuilder
	 */
	public function regDate(DateTime $regDate) {
		$this->regDate = $regDate;
		return $this;
	}
	
	/**
	 * @param string $sessionID
	 * @return UserBuilder
	 */
	public function sessionID($sessionID) {
		$this->sessionID = $sessionID;
		return $this;
	}
	
	/**
	 * @param string $username
	 * @return UserBuilder
	 */
	public function username($username) {
		$this->username = $username;
		return $this;
	}
	
	/**
	 * @param DateTime $visitDate
	 * @return UserBuilder
	 */
	public function visitDate(DateTime $visitDate) {
		$this->visitDate = $visitDate;
		return $this;
	}
	
	/**
	 * @param string $client
	 * @return UserBuilder
	 */
	public function client($client) {
		$this->client = $client;
		return $this;
	}
	
	/**
	 * @param int $expire
	 * @return UserBuilder
	 */
	public function expire($expire) {
		$this->expire = ( int ) $expire;
		return $this;
	}
	
	/**
	 * @param string $IP
	 * @return UserBuilder
	 */
	public function IP($IP) {
		$this->IP = $IP;
		return $this;
	}
	
	/**
	 * @param string $msg
	 * @return UserBuilder
	 */
	public function msg($msg) {
		$this->msg = $msg;
		return $this;
	}
	
	/**
	 * @param string $referer
	 * @return UserBuilder
	 */
	public function referer($referer) {
		$this->referer = $referer;
		return $this;
	}
	
	/**
	 * @param string $url
	 * @return UserBuilder
	 */
	public function url($url) {
		$this->url = $url;
		return $this;
	}
	
	/**
	 * @param boolean $hidden
	 * @return UserBuilder
	 */
	public function hidden($hidden) {
		$this->hidden = $hidden;
		return $this;
	}
	
	/**
	 * @param PermissionSet $perms
	 * @return UserBuilder
	 */
	public function perms(PermissionSet $perms) {
		$this->perms = $perms;
		return $this;
	}
	
	/**
	 * @param string $key
	 * @param string $value
	 * @return UserBuilder
	 */
	public function attribute($key, $value) {
		$this->attributes [$key] = $value;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * Return the complete language name
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
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
	 * @return array
	 */
	public function getAttributes() {
		return $this->attributes;
	}
}