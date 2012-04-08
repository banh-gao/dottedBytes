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
use dottedBytes\libs\utils\Comparable;

use dottedBytes\libs\utils\collections\Collection;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\utils\collections\ObjectSet;

use dottedBytes\libs\utils\ObjectUtils;

use dottedBytes\libs\utils\collections\SetIf;

use \Serializable;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

/**
 * Provide a list structure
 *
 */
class PermissionSet implements SetIf, Serializable {
	
	private $objSet;
	
	public function __construct(Collection $c = null) {
		ObjectUtils::checkType ( $c, 'dottedBytes\libs\users\permissions\PermissionSet' );
		$this->objSet = new ObjectSet ( $c );
	}
	
	/**
	 * Add the specified permission to this set
	 *
	 * @param Permission $perm
	 * @return boolean
	 */
	public function add(Comparable $perm) {
		ObjectUtils::checkType ( $perm, 'dottedBytes\libs\users\permissions\Permission' );
		return $this->objSet->add ( $perm );
	}
	
	/**
	 * Adds all permissions in the specified permissionSet to this set
	 *
	 * @param PermissionSet $c
	 * @return boolean
	 */
	public function addAll(Collection $c) {
		ObjectUtils::checkType ( $c, 'dottedBytes\libs\users\permissions\PermissionSet' );
		return $this->objSet->addAll ( $c );
	}
	
	/**
	 * Returns true if this set contains the specified permission
	 *
	 * @param Permission $perm
	 * @return boolean
	 */
	public function contains(Comparable $perm) {
		ObjectUtils::checkType ( $perm, 'dottedBytes\libs\users\permissions\Permission' );
		return $this->objSet->contains ( $perm );
	}
	
	/**
	 * Returns true if this set contains all of the permissions in the specified set
	 *
	 * @param PermissionSet $c
	 * @return boolean
	 */
	public function containsAll(Collection $c) {
		ObjectUtils::checkType ( $c, 'dottedBytes\libs\users\permissions\PermissionSet' );
		return $this->objSet->containsAll ( $c );
	}
	
	/**
	 * Removes the specified permission from this set, if present
	 *
	 * @param Permission $perm
	 * @return boolean
	 */
	public function remove(Comparable $perm) {
		ObjectUtils::checkType ( $perm, 'dottedBytes\libs\users\permissions\Permission' );
		return $this->objSet->remove ( $perm );
	}
	
	/**
	 * Removes all objects of this collection that are also contained in the specified collection
	 *
	 * @param PermissionSet $c
	 * @return boolean
	 */
	public function removeAll(Collection $c) {
		ObjectUtils::checkType ( $c, 'dottedBytes\libs\users\permissions\PermissionSet' );
		return $this->objSet->removeAll ( $c );
	}
	
	/**
	 * Retains only the permissions in this set that are contained in the specified set
	 *
	 * @param PermissionSet $c
	 * @return boolean
	 */
	public function retainAll(Collection $c) {
		ObjectUtils::checkType ( $c, 'dottedBytes\libs\users\permissions\PermissionSet' );
		return $this->objSet->retainAll ( $c );
	}
	
	/**
	 * Returns true if this set contains no permission
	 *
	 * @return boolean
	 */
	public function isEmpty() {
		return $this->objSet->isEmpty ();
	}
	
	/**
	 * Empty this set
	 *
	 * @return boolean
	 */
	public function clear() {
		return $this->objSet->clear ();
	}
	
	/**
	 * Returns an iterator over the permissions of this set
	 *
	 * @return Iterator
	 */
	public function getIterator() {
		return $this->objSet->getIterator ();
	}
	
	/**
	 * Returns an array containing permissions of this set
	 *
	 * @return array
	 */
	public function getArrayCopy() {
		return $this->objSet->getArrayCopy ();
	}
	
	public function sort($cmp_function = null) {
		return $this->objSet->sort ( $cmp_function );
	}
	
	public function compareTo(Comparable $obj = null) {
		return $this->objSet->compareTo ( $obj );
	}
	
	public function count() {
		return $this->objSet->count ();
	}
	
	/**
	 * Compares the specified PermissionSet with this PermissionSet for equality
	 *
	 * @param PermissionSet $a
	 * @return boolean
	 */
	public function equals(Comparable $c = null) {
		if (! ($c instanceof PermissionSet))
			return false;
			/* @var $c PermissionSet */
		if ($this->count () != $c->count ())
			return false;
		$tmp = clone $this;
		foreach ( $c as $perm ) {
			if ($tmp->contains ( $perm ))
				$tmp->remove ( $perm );
		}
		return $tmp->isEmpty ();
	}
	
	/**
	 * Returns a string representation of this list
	 *
	 * @return string
	 */
	public function __toString() {
		if ($this->isEmpty ())
			return "None\n";
		return $this->objSet->__toString ();
	}
	
	/**
	 * Returns a copy of this list with a clone of each element
	 * @return PermissionSet
	 */
	
	public function __clone() {
		$new = new PermissionSet ();
		foreach ( $this->objSet as $elem )
			$new->add ( clone $elem );
		return $new;
	}
	
	public function serialize() {
		$serialized = "";
		foreach ( $this as $permission ) {
			/* @var $permission Permission */
			$serialized .= "|" . $permission->getId ();
		}
		$serialized .= "|";
		return $serialized;
	}
	
	/**
	 * @param string $serialized
	 */
	public function unserialize($serialized) {
		$permissions = String::split ( $serialized );
		if (count ( $permissions ) < 1)
			return false;
		$this->clear ();
		foreach ( $permissions as $id ) {
			$permission = Permission::getById ( $id );
			if ($permission != null)
				$this->add ( $permission );
		}
		return true;
	}

}

?>