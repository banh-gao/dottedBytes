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

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

/**
 * Provide a set structure witch allow only one copy of an element
 *
 */
class ObjectSet implements SetIf {
	
	/**
	 * Container of the objects
	 * @var ListIf
	 */
	private $objList;
	
	public function __construct(Collection $c = null) {
		$this->objList = new ObjectList ( $c );
	}
	
	/**
	 * Add the specified element to this set if is not already contained
	 *
	 * @param Comparable $o
	 * @return boolean - false if the object is already contained, true otherwise
	 */
	public function add(Comparable $o) {
		if ($this->contains ( $o ))
			return false;
		return $this->objList->add ( $o );
	}
	
	/**
	 * Add all the elements in the specified collections  if they are not already contained
	 *
	 * @param Comparable $o
	 * @return boolean - false if some object is already contained, true otherwise
	 */
	public function addAll(Collection $c) {
		$result = true;
		foreach ( $c as $o )
			if ($this->add ( $o ) == false)
				$result = false;
		return $result;
	}
	
	public function contains(Comparable $o) {
		return $this->objList->contains ( $o );
	}
	
	public function containsAll(Collection $c) {
		return $this->objList->containsAll ( $c );
	}
	
	public function removeAll(Collection $c) {
		return $this->objList->removeAll ( $c );
	}
	
	public function retainAll(Collection $c) {
		return $this->objList->retainAll ( $c );
	}
	
	public function remove(Comparable $o) {
		return $this->objList->remove ( $o );
	}
	
	public function equals(Comparable $c = null) {
		if (! ($c instanceof SetIf))
			return false;
		return $this->objList->equals ( $c );
	}
	
	public function isEmpty() {
		return $this->objList->isEmpty ();
	}
	
	public function clear() {
		return $this->objList->clear ();
	}
	
	public function getArrayCopy() {
		return $this->objList->getArrayCopy ();
	}
	
	public function sort($cmp_function = null) {
		return $this->objList->sort ( $cmp_function );
	}
	
	public function compareTo(Comparable $obj = null) {
		return $this->objList->compareTo ( $obj );
	}
	
	public function count() {
		return $this->objList->count ();
	}
	
	public function getIterator() {
		return $this->objList->getIterator ();
	}
	
	/**
	 * Returns a copy of this set with a clone of each element
	 * @return ObjectSet
	 */
	public function __clone() {
		$new = new ObjectSet ();
		foreach ( $this->objList as $elem )
			$new->add ( clone $elem );
		return $new;
	}
	
	public function __toString() {
		return $this->objList->__toString ();
	}
}

?>