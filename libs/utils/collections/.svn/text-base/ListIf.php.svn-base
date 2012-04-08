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

use \ArrayAccess;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

/**
 * Provide an interface to access a List of objects
 * The list is a Collection and inhereditate all his features
 * The elements can be accessed like an array (e.g. objectList[3]).
 */
interface ListIf extends Collection, ArrayAccess {
	/**
	 * Return an ObjectList that contains the objects between the two index
	 * @param $start - the first element (included)
	 * @param $end - the last element (included)
	 * @return ObjectList
	 */
	public function subList($start = 0, $end = null);
}