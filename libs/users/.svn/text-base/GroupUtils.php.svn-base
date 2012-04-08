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

use dottedBytes\libs\utils\collections\ObjectSet;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\users\permissions\PermissionSet;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class GroupUtils {
	/**
	 * The set of all groups
	 *
	 * @var ObjectSet
	 */
	private static $groups = null;
	
	/**
	 * Returns the group corresponding to specified id
	 *
	 * @param int $id
	 * @return Group
	 */
	static public function getGroup($id) {
		self::loadGroups ();
		foreach ( self::$groups as $group ) {
			/* @var $group Group */
			if ($group->getId () == $id)
				return $group;
		}
		throw new GroupException ( 'Group with ID ' . $id . ' not found' );
	}
	
	/**
	 * Returns all the groups
	 *
	 * @return ObjectSet
	 */
	public static function getGroups() {
		self::loadGroups ();
		return self::$groups;
	}
	
	/**
	 * Get the permissions corresponding to a group
	 * @param $gid
	 * @return PermissionSet
	 */
	public static function getGroupPerms($gid) {
		$gid = ( int ) $gid;
		$database = DBManager::getInstance ();
		$perms = new PermissionSet ();
		$res = $database->query ( "SELECT permID FROM #__groups_perms WHERE gid=$gid" );
		
		while ( ($permID = $res->fetchColumn ( 0 )) !== false ) {
			$perms->add ( Permission::getById ( $permID ) );
		}
		return $perms;
	}
	
	static public function getGroupByName($name = null) {
		self::loadGroups ();
		$name = strtolower ( $name );
		foreach ( self::$groups as $group ) {
			/* @var $group Group */
			if (strtolower ( $group->getName () ) == $name)
				return $group;
		}
		throw new GroupException ( 'Group ' . $name . ' not found' );
	}
	
	static private function loadGroups() {
		if (self::$groups != null)
			return;
		self::$groups = new ObjectSet ();
		$database = DBManager::getInstance ();
		$res = $database->query ( 'SELECT * FROM #__groups' );
		foreach ( $res->fetchAll () as $row ) {
			$group = new Group ( $row->gid, $row->name, $row->creation );
			self::$groups->add ( $group );
		}
		self::$groups->add ( new EmptyGroup () );
	}
}
?>