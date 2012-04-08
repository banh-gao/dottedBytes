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

use \Countable;
use \IteratorAggregate;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

/**
 * This interface define standard Collection API
 * This class can be used in the foreach construct and the size can be read by using the count() function
 */
interface Collection extends Comparable, Countable, IteratorAggregate {
	
	/**
	 * Add the specified element to the Collection
	 *
	 * @param Comparable $o
	 * @return boolean
	 */
	public function add(Comparable $o);
	
	/**
	 * Adds all the elements in the specified Collection to the Collection
	 *
	 * @param Collection $a
	 * @return boolean
	 */
	public function addAll(Collection $c);
	
	/**
	 * Returns true if the Collection contains the specified element
	 *
	 * @param Comparable $o
	 * @return boolean
	 */
	public function contains(Comparable $o);
	
	/**
	 * Returns true if the Collection contains all of the elements in the specified Collection
	 *
	 * @param Collection $a
	 * @return boolean
	 */
	public function containsAll(Collection $c);
	
	/**
	 * Remove the specified element from this Collection, return true if it is present, false otherwise
	 *
	 * @param Comparable $o
	 * @return boolean
	 */
	public function remove(Comparable $o);
	
	/**
	 * Removes all the Collection's elements that are also contained in the specified Collection
	 *
	 * @param Collection $a
	 * @return boolean
	 */
	public function removeAll(Collection $c);
	
	/**
	 * Retains only the elements in the Collection that are contained in the specified Collection
	 *
	 * @param Collection $a
	 * @return boolean
	 */
	public function retainAll(Collection $c);
	
	/**
	 * Returns true if the collection contains no objects
	 *
	 * @return boolean
	 */
	public function isEmpty();
	
	/**
	 * Empty the collection
	 *
	 * @return boolean
	 */
	public function clear();
	
	/**
	 * Returns an array containing objects of the collection
	 *
	 * @return array
	 */
	public function getArrayCopy();
	
	/**
	 * Sort the elements of the collection using compareTo of Comparable interface or a comparition function
	 * @param callback $cmp_function
	 * @return boolean
	 */
	public function sort($cmp_function = null);
	
	/**
	 * Returns a copy of the collection with a clone of each element
	 * @return object
	 */
	public function __clone();
}

?>