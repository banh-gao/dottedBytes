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

namespace dottedBytes\libs\html\toolbar;

use dottedBytes\libs\utils\collections\ObjectList;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class Toolbar {
	private $iconSize;
	private $items;
	
	const ICON_SMALL = 1;
	const ICON_MEDIUM = 2;
	const ICON_BIG = 3;
	
	public function __construct($iconSize = self::ICON_MEDIUM) {
		$this->iconSize = $iconSize;
		$this->items = new ObjectList ();
	}
	
	public function addItem(ToolbarButton $item) {
		$item->setIconSize ( $this->iconSize );
		$this->items->add ( $item );
	}
	
	/**
	 * Return the buttons in this toolbar
	 * @return ObjectList
	 */
	public function getButtons() {
		return $this->items;
	}
	
	public function getHTML() {
		if ($this->items->count () < 1)
			return '';
		$html = "<ul>";
		foreach ( $this->items as $item ) {
			$html .= $item->getHTML ();
		}
		$html .= "</ul>";
		return $html;
	}
	
	public function getIconHTML() {
		if ($this->items->count () < 1)
			return '';
		$html = "<ul>";
		foreach ( $this->items as $item ) {
			$html .= $item->getIconHTML ();
		}
		$html .= "</ul>";
		return $html;
	}
	
	public function getLabelHTML() {
		if ($this->items->count () < 1)
			return '';
		$html = "<ul>";
		foreach ( $this->items as $item ) {
			$html .= $item->getLabelHTML ();
		}
		$html .= "</ul>";
		return $html;
	}
	
	public function __toString() {
		$string = "";
		foreach ( $this->items as $item ) {
			$string .= strval ( $item );
		}
		return $string;
	}
}

?>