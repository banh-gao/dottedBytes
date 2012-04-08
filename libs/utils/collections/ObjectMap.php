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
class ObjectMap implements MapIf {
	
	public function __construct() {
		$this->collection = new ArrayObject ();
	}
	
	/**
	 * Add the specified object to this collection
	 *
	 * @param Comparable $o
	 * @return boolean
	 */
	public function put($key, Comparable $obj) {
		$key = ( int ) $key;
		$old = $this->get ( $key );
		$this->collection->offsetSet ( $key, $obj );
		return $old;
	}
	
	public function get($key) {
		$key = ( int ) $key;
		if ($this->collection->offsetExists ( $key ))
			return $this->collection->offsetGet ( $key );
		else
			return null;
	}
	
	public function containsKey($key) {
		$key = ( int ) $key;
		return $this->collection->offsetExists ( $key );
	}
	
	public function delete($key) {
		$key = ( int ) $key;
		$this->collection->offsetUnset ( $key );
	}
	
	/**
	 * Returns true if this collection contains the specified object
	 *
	 * @param ObjectList $o
	 * @return boolean
	 */
	public function contains(Comparable $o) {
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
			$cmp_function = array ('ObjectUtils', 'compare' );
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
		return $this->containsKey ( $offset );
	}
	
	/**
	 * Alias of get (ArrayAccess implementation)
	 * @see ObjectMap::get()
	 * @param int $offset
	 * @return mixed
	 */
	public function offsetGet($offset) {
		return $this->get ( $offset );
	}
	
	/**
	 * Alias of put (ArrayAccess implementation)
	 *
	 * @see ObjectMap::put()
	 * @param int $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value) {
		$this->put ( $offset, $value );
	}
	
	/**
	 * Alias of remove (ArrayAccess implementation)
	 *
	 * @see ObjectMap::remove()
	 * @param in $offset
	 */
	public function offsetUnset($offset) {
		$this->collection->offsetUnset ( $offset );
	}
	
	/**
	 * Compares the specified collection with this map for equality
	 *
	 * @param MapIf $a
	 * @return boolean
	 */
	public function equals(Comparable $c = null) {
		if (! ($c instanceof MapIf))
			return false;
		if ($this->count () != count ( $c ))
			return false;
		$tmp = clone $this;
		foreach ( $c as $elem ) {
			if ($tmp->contains ( $elem ))
				$tmp->remove ( $elem );
		}
		return $tmp->isEmpty ();
	}
	
	/**
	 * Returns a copy of this map with a clone of each element
	 * @return MapIf
	 */
	public function __clone() {
		$new = new ObjectMap ();
		foreach ( $this as $key => $elem )
			$new->put ( $key, clone $elem );
		return $new;
	}
	
	public function __toString() {
		$string = '';
		foreach ( $this as $key => $elem )
			$string .= '[' . $key . ']' . '=>' . strval ( $elem ) . "\n";
		return $string;
	}
	
	/**
	 * Not supported by map
	 * @ignore
	 */
	public function add(Comparable $o) {
		throw new CollectionException ( "Map doesn't support add() method" );
	}
	
	/**
	 * Not supported by map
	 * @ignore
	 */
	public function addAll(Collection $o) {
		throw new CollectionException ( "Map doesn't support addAll() method" );
	}
	
	/**
	 * Not supported by map
	 * @see ObjectMap::delete()
	 * @ignore
	 */
	public function remove(Comparable $o) {
		throw new CollectionException ( "Map doesn't support remove() method" );
	}
	
	/**
	 * Not supported by map
	 * @ignore
	 */
	public function removeAll(Collection $c) {
		throw new CollectionException ( "Map doesn't support removeAll() method" );
	}
	
	/**
	 * Not supported by map
	 * @ignore
	 */
	public function retainAll(Collection $c) {
		throw new CollectionException ( "Map doesn't support retainAll() method" );
	}

}

?>