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

namespace dottedBytes\libs\pageBuilder\template;

use dottedBytes\libs\utils\collections\Collection;

use dottedBytes\libs\utils\Comparable;

use dottedBytes\libs\modules\PanelException;

use dottedBytes\libs\utils\ObjectUtils;

use dottedBytes\libs\utils\collections\ObjectList;

use dottedBytes\libs\utils\collections\ListIf;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class PanelList implements ListIf {
	
	private $pos;
	/**
	 * @var ObjectList
	 */
	private $panelList;
	
	/**
	 * Create a list of panels
	 *
	 * @param array $pos Array of possible positions
	 */
	public function __construct($pos) {
		$this->panelList = new ObjectList ();
		$this->pos = (! is_array ( $pos )) ? array ($pos ) : $pos;
	}
	
	/**
	 * Add a panel to this panelList
	 *
	 * @param Panel $panel
	 * @return boolean
	 */
	public function add(Comparable $panel) {
		ObjectUtils::checkType ( $panel, 'dottedBytes\libs\modules\Panel' );
		/* @var $panel Panel */
		$position = $panel->getPosition ();
		if (array_search ( $position, $this->pos ) !== false) {
			return $this->panelList->add ( $panel );
			return true;
		}
		throw new PanelException ( "Invalid position for panel " . $panel->getTitle () . " (" . $panel->getName () . ")" );
	}
	
	public function addAll(Collection $c) {
		$result = true;
		foreach ( $c as $o )
			if ($this->add ( $o ) == false)
				$result = false;
		return $result;
	}
	
	/**
	 * Get an array containing panels for specified position
	 *
	 * @param int $position
	 * @return PanelList
	 */
	public function getPanelsByPos($position) {
		$this->sort ();
		if (array_search ( $position, $this->pos ) !== false) {
			$res = new PanelList ( $position );
			foreach ( $this->panelList as $panel ) {
				if ($panel->getPosition () == $position)
					$res->add ( $panel );
			}
			return $res;
		} else {
			throw new PanelException ( "Position " . $position . " is not a valid position." );
		}
	}
	
	public function getPositions() {
		return $this->pos;
	}
	
	/**
	 * Return the size of the list
	 *
	 * @param int $pos Optionally filter by position
	 * @return int
	 */
	public function size($position = null) {
		if (is_null ( $position ))
			return $this->count();
		
		if (array_search ( $position, $this->pos ) !== false) {
			return count ( $this->getPanelsByPos ( $position ) );
		} else {
			throw new PanelException ( "Position " . $position . " is not a valid position." );
		}
	
	}
	
	public function equals(Comparable $c = null) {
		if (! ($c instanceof PanelList))
			return false;
		return $this->panelList->equals ( $c );
	}
	
	public function __toString() {
		$res = '';
		$remains = new PanelList ( $this->pos );
		$remains->addAll ( $this->panelList );
		
		foreach ( $this->pos as $pos ) {
			if ($this->size ( $pos ) > 0)
				$res .= "\n--- POSITION: " . Position::nameOf ( $pos ) . " ---\n";
			foreach ( $this->getPanelsByPos ( $pos ) as $panel ) {
				$res .= $panel->__toString () . "\n\n";
				//$remains->remove ( $panel );
			}
		}
		if (count ( $remains ) > 0)
			$res .= "\n--- POSITION: ????? ---\n";
		
		foreach ( $remains as $panel ) {
			$res .= $panel->__toString () . "\n";
		}
		
		return $res;
	}
	
	//The following methods are all delegation methods not modified
	

	public function contains(Comparable $o) {
		return $this->panelList->contains ( $o );
	}
	
	public function containsAll(Collection $c) {
		return $this->panelList->containsAll ( $c );
	}
	
	public function removeAll(Collection $c) {
		return $this->panelList->removeAll ( $c );
	}
	
	public function retainAll(Collection $c) {
		return $this->panelList->retainAll ( $c );
	}
	
	public function remove(Comparable $o) {
		return $this->panelList->remove ( $o );
	}
	
	public function isEmpty() {
		return $this->panelList->isEmpty ();
	}
	
	public function clear() {
		return $this->panelList->clear ();
	}
	
	public function getArrayCopy() {
		return $this->panelList->getArrayCopy ();
	}
	
	public function sort($cmp_function = null) {
		return $this->panelList->sort ( $cmp_function );
	}
	
	public function compareTo(Comparable $obj = null) {
		return $this->panelList->compareTo ( $obj );
	}
	
	public function subList($start = 0, $end = null) {
		return $this->panelList->subList ( $start, $end );
	}
	
	public function count() {
		return $this->panelList->count ();
	}
	
	public function getIterator() {
		return $this->panelList->getIterator ();
	}
	
	public function offsetExists($offset) {
		return $this->panelList->offsetExists ( $offset );
	}
	
	public function offsetGet($offset) {
		return $this->panelList->offsetGet ( $offset );
	}
	
	public function offsetSet($offset, $value) {
		return $this->panelList->offsetSet ( $offset, $value );
	}
	
	public function offsetUnset($offset) {
		$this->panelList->offsetUnset ( $offset );
	}
	
	public function __clone() {
		$clone = new PanelList ( $this->pos );
		foreach ( $this->panelList as $elem )
			$clone->add ( clone $elem );
		return $clone;
	}
}
?>