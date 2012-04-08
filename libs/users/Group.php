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
use dottedBytes\libs\utils\ObjectUtils;

use dottedBytes\libs\users\permissions\PermissionSet;

use dottedBytes\libs\utils\Comparable;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class Group implements Comparable {
	
	private $id;
	private $name;
	private $creation;
	protected $perms;
	
	public function __construct($id = null, $name = null, $creation = null) {
		$this->id = $id;
		$this->name = $name;
		$this->creation = $creation;
		if ($id > 0)
			$this->perms = GroupUtils::getGroupPerms ( $id );
		else
			$this->perms = new PermissionSet ();
	}
	
	public function equals(Comparable $obj = null) {
		ObjectUtils::checkType ( $obj, 'dottedBytes\libs\users\Group' );
		/* @var $obj Group */
		return ($this->id == $obj->id);
	}
	
	public function compareTo(Comparable $obj = null) {
		ObjectUtils::checkType ( $obj, 'dottedBytes\libs\users\Group' );
		/* @var $obj Group */
		return strcmp ( $this->name, $obj->getName () );
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
	public function getCreation() {
		return $this->creation;
	}
	
	/**
	 * @return PermissionSet
	 */
	public function getPermissions() {
		return $this->perms;
	}
	
	public function __toString() {
		return "(#$this->id) $this->name\n";
	}
}

?>