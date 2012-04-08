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

namespace dottedBytes\libs\utils\collections;

use dottedBytes\libs\utils\Comparable;

use \ArrayAccess;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

/**
 * Provide an interface to access a Map of objects
 * The map is a Collection and inhereditate all his features
 * The elements can be accessed like an array (e.g. objectMap[3]).
 */
interface MapIf extends Collection, ArrayAccess {
	/**
	 * Add an object to the map with the specified key. The key must be an integer
	 * If there is already an object at the specified key, it will replaced with the new one.
	 * It return the old object or null
	 * @param $key - the key of the object
	 * @param $obj - the object to insert
	 * @return Comparable - the old object if any, null otherwise
	 */
	public function put($key, Comparable $obj);
	
	/**
	 * Delete an object from the map with the specified key. The key must be an integer
	 * It return the old object or null
	 * @param $key - the key of the object to remove
	 * @return Comparable - the old object if any, null otherwise
	 */
	public function delete($key);
	
	/**
	 * Returns the object corresponding to the key, null if there is no match
	 * @param $key - the key of the object to retrieve
	 * @return Comparable
	 */
	public function get($key);
	
	/**
	 * Returns true if the specified key exists, false otherwise
	 * @param $key - the key of the object
	 * @return boolean
	 */
	public function containsKey($key);
}