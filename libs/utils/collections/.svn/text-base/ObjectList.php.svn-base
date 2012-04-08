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

use \ArrayObject;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

/**
 * Provide a list structure
 *
 */
class ObjectList implements ListIf {
	
	public function __construct(Collection $c = null) {
		$this->collection = new ArrayObject ();
		if ($c != null) {
			$this->addAll ( $c );
		}
	}
	
	/**
	 * Add the specified object to this collection
	 *
	 * @param Comparable $o
	 * @return boolean
	 */
	public function add(Comparable $obj) {
		$this->collection->append ( $obj );
		return true;
	}
	
	/**
	 * Adds all objects in the specified collection to this collection
	 *
	 * @param Collection $a
	 * @return boolean
	 */
	public function addAll(Collection $c) {
		if (is_null ( $c ))
			return false;
		$res = true;
		if ($c->isEmpty ())
			return false;
		
		foreach ( $c as $object )
			$this->add ( $object );
		return $res;
	}
	
	/**
	 * Returns true if this collection contains the specified object
	 *
	 * @param ObjectList $o
	 * @return boolean
	 */
	public function contains(Comparable $o) {
		if ($this->collection->offsetExists ( spl_object_hash ( $o ) ))
			return true;
		
		foreach ( $this->collection as $object ) {
			if ($o->equals ( $object )) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Returns true if this collection contains all of the objects in the specified collection
	 *
	 * @param Collection $a
	 * @return boolean
	 */
	public function containsAll(Collection $c) {
		$all = count ( $c );
		$count = 0;
		foreach ( $c as $elem ) {
			if ($this->contains ( $elem ))
				$count ++;
			
			if ($all == $count)
				return true;
		}
		return false;
	}
	
	/**
	 * Return an ObjectList that contains the objects between the two index
	 * @param $start - the first element (included)
	 * @param $end - the last element (excluded)
	 * @return ObjectList
	 */
	public function subList($start = 0, $end = null) {
		if ($end === null || $end > $this->collection->count ())
			$end = $this->collection->count ();
		
		if ($start < 0 || $start > $end)
			throw new CollectionException ( "The start index $start must be smaller of the end index $end" );
		
		$result = new ObjectList ();
		for($i = $start; $i < $end; $i ++) {
			$result->add ( $this->collection->offsetGet ( $i ) );
		}
		return $result;
	}
	
	/**
	 * Removes a specified object from this collection, if present
	 *
	 * @param Comparable $o
	 * @return boolean
	 */
	public function remove(Comparable $o) {
		if ($this->contains ( $o )) {
			$this->collection->offsetUnset( $o );
			return true;
		}
		return false;
	}
	
	/**
	 * Removes all objects of this collection that are also contained in the specified collection
	 *
	 * @param Collection $a
	 * @return boolean
	 */
	public function removeAll(Collection $c) {
		$changed = false;
		foreach ( $c as $elem ) {
			if ($this->remove ( $elem ))
				$changed = true;
		}
		return $changed;
	}
	
	/**
	 * Retains only the objects in this collection that are contained in the specified collection
	 *
	 * @param Collection $a
	 * @return boolean
	 */
	public function retainAll(Collection $c) {
		$changed = false;
		$tmp = clone $this;
		foreach ( $c as $elem ) {
			if ($this->contains ( $elem )) {
				$tmp->add ( $elem );
				$changed = true;
			}
		}
		$this->clear ();
		$this->addAll ( $tmp );
		return $changed;
	}
	
	public function compareTo(Comparable $obj = null) {
		return ( int ) $this->equals ( $obj );
	}
	
	public function isEmpty() {
		return ($this->collection->count () < 1) ? true : false;
	}
	
	/**
	 * Empty this collection
	 *
	 * @return boolean
	 */
	public function clear() {
		$this->collection = new ArrayObject ();
		return true;
	}
	
	/**
	 * Returns an iterator over the objects of this collection
	 *
	 * @return Iterator
	 */
	public function getIterator() {
		return $this->collection->getIterator ();
	}
	
	/**
	 * Returns an array containing objects of this collection
	 *
	 * @return array
	 */
	public function getArrayCopy() {
		$res = array ();
		foreach ( $this as $key => $object )
			$res [$key] = $object;
		return $res;
	}
	
	/**
	 * Sort the elements of the collection using a comparition function or compareTo if collection contains Comparable objects
	 * @param callback $cmp_function
	 * @return boolean
	 */
	public function sort($cmp_function = null) {
		if ($this->isEmpty ())
			return false;
		
		if ($cmp_function == null) {
			$cmp_function = array ('\dottedBytes\libs\utils\ObjectUtils', 'compare' );
		}
		return $this->collection->uasort ( $cmp_function );
	}
	
	/**
	 * Returns the number of the objects contained in this collection
	 *
	 * @return int
	 */
	public function count() {
		return $this->collection->count ();
	}
	
	/**
	 * Alias of contains (ArrayAccess implementation)
	 *
	 * @param int $offset
	 * @return boolean
	 */
	public function offsetExists($offset) {
		return $this->collection->offsetExists ( $offset );
	}
	
	/**
	 * Returns the data associated with an object in the collection (ArrayAccess implementation)
	 *
	 * @param int $offset
	 * @return mixed
	 */
	public function offsetGet($offset) {
		return $this->collection->offsetGet ( $offset );
	}
	
	/**
	 * Alias of add (ArrayAccess implementation)
	 *
	 * @param int $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value) {
		$this->collection->offsetGet ( $offset, $value );
	}
	
	/**
	 * Alias of remove (ArrayAccess implementation)
	 *
	 * @param int $offset
	 */
	public function offsetUnset($offset) {
		$this->collection->offsetUnset ( $offset );
	}
	
	/**
	 * Compares the specified collection with this list for equality
	 *
	 * @param ObjectList $a
	 * @return boolean
	 */
	public function equals(Comparable $c = null) {
		if (! ($c instanceof ListIf))
			return false;
		if ($this->count () != count ( $c ))
			return false;
		$tmp = new ObjectList($this);
		foreach ( $c as $elem ) {
			if ($tmp->contains ( $elem ))
				$tmp->remove ( $elem );
		}
		return $tmp->isEmpty ();
	}
	
	/**
	 * Returns a copy of this list with a clone of each element
	 * @return ObjectList
	 */
	public function __clone() {
		$new = new ObjectList ();
		foreach ( $this as $elem )
			$new->add ( clone $elem );
		return $new;
	}
	
	public function __toString() {
		$string = "";
		foreach ( $this as $elem )
			$string .= strval ( $elem );
		return $string;
	}
}

?>