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

namespace dottedBytes\libs\users\permissions;

// no direct access
use dottedBytes\libs\utils\IllegalArgumentException;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\utils\ObjectUtils;

use dottedBytes\libs\utils\Comparable;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class Permission implements Comparable {
	
	/**
	 * Permission id
	 *
	 * @var int
	 */
	private $id;
	/**
	 * Permission id
	 *
	 * @var int
	 */
	private $name;
	
	/**
	 * The set of all available permissions
	 *
	 * @var PermissionSet
	 */
	private static $permissions = null;
	
	/**
	 * Returns the set with all available permissions
	 *
	 * @return PermissionSet
	 */
	static public function getAll() {
		if (self::$permissions == null) {
			self::$permissions = new PermissionSet ();
			$database = DBManager::getInstance ();
			foreach ( $database->query ( 'SELECT id , name FROM #__perms' )->fetchAll () as $perm )
				self::$permissions->add ( new Permission ( $perm->id, $perm->name ) );
		}
		
		return self::$permissions;
	}
	
	/**
	 * Returns the permission corresponding to specified id
	 *
	 * @param int $id
	 * @return Permission
	 */
	static public function getById($id) {
		foreach ( self::getAll () as $permission ) {
			/* @var $permission Permission */
			if ($permission->getId () == $id)
				return $permission;
		}
		return new Permission ( 0 );
	}
	
	/**
	 * Returns the permission corresponding to specified name
	 *
	 * @param string $name
	 * @return Permission
	 */
	static public function getByName($name) {
		foreach ( self::getAll () as $permission ) {
			/* @var $permission Permission */
			if ($permission->getName () == $name)
				return $permission;
		}
		return new Permission ( 0 , $name);
	}
	
	static public function check($owned, $required) {
		if (is_string ( $owned ))
			$owned = Permission::getByName ( $owned );
		
		if (is_string ( $required ))
			$required = Permission::getByName ( $required );
		
		if ($owned instanceof Permission) {
			$o = new PermissionSet ();
			$o->add ( $owned );
		} elseif ($owned instanceof PermissionSet) {
			$o = $owned;
		} else {
			throw new IllegalArgumentException ( 1, "Permission, PermissionSet or string", $owned );
		}
		
		if ($required instanceof Permission) {
			$r = new PermissionSet ();
			$r->add ( $required );
		} elseif ($required instanceof PermissionSet) {
			$r = $required;
		} else {
			throw new IllegalArgumentException ( 2, "Permission, PermissionSet or string", $required );
		}
		
		return $o->containsAll($r);
	}
	
	private function __construct($id = 0, $name = '') {
		$this->id = $id;
		$this->name = $name;
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
	
	public function compareTo(Comparable $obj = null) {
		ObjectUtils::checkType ( $obj, 'dottedBytes\libs\users\permissions\Permission' );
		/* @var $obj Permission */
		if ($this->id == $obj->getId ())
			return 0;
		if ($this->id < $obj->getId ())
			return - 1;
		return 1;
	}
	
	public function equals(Comparable $obj = null) {
		return ($this->compareTo ( $obj ) == 0);
	}
	
	public function __toString() {
		return "(#$this->id) Permission $this->name";
	}
}
?>