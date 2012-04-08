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

use dottedBytes\libs\pageBuilder\Resources;

use dottedBytes\libs\utils\ObjectUtils;

use dottedBytes\libs\utils\Comparable;

use dottedBytes\libs\html\form\Form;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ToolbarButton implements Comparable {
	
	private $label;
	private $icon;
	private $iconPath;
	private $link;
	
	public function __construct($label, $link = '', $icon = '') {
		$this->iconPath = $icon;
		
		$this->label = $label;
		
		if ($link == '')
			$link = 'javascript:void(0)';
		
		$this->link = $link;
	}
	
	public function setIconSize($iconSize) {
		switch ($iconSize) {
			case Toolbar::ICON_SMALL :
				$this->icon = Resources::getSysIcon ( $this->iconPath, 1, $this->label );
				break;
			case Toolbar::ICON_MEDIUM :
				$this->icon = Resources::getMediumIcon ( $this->iconPath, 1, $this->label );
				break;
			case Toolbar::ICON_BIG :
				$this->icon = Resources::getBigIcon ( $this->iconPath, 1, $this->label );
				break;
		}
	}
	
	/**
	 * @param Comparable $obj
	 */
	public function compareTo(Comparable $obj = null) {
		ObjectUtils::checkType ( $obj, 'dottedBytes\libs\html\toolbar\ToolbarButton', true, false );
		return strcmp ( $this->label, $obj->getLabel () );
	}
	
	/**
	 * @param Comparable $obj
	 */
	public function equals(Comparable $obj = null) {
		return false;
	}
	
	public function getHTML() {
		return "<li><a href=\"$this->link\"><div class=\"icon\">$this->icon</div><div class=\"label\">$this->label</div></a></li>";
	}
	
	public function getLabelHTML() {
		return "<li><a href=\"$this->link\"><div class=\"label\">$this->label</div></a></li>";
	}
	
	public function getIconHTML() {
		return "<li><a href=\"$this->link\"><div class=\"icon\">$this->icon</div></a></li>";
	}
	
	public function __toString() {
		return "LABEL:$this->label\nLINK:$this->link\nICON:$this->iconPath\n";
	}
	
	/**
	 * @return the $label
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * @return the $icon
	 */
	public function getIcon() {
		return $this->icon;
	}
	
	/**
	 * @return the $iconPath
	 */
	public function getIconPath() {
		return $this->iconPath;
	}
	
	/**
	 * @return the $link
	 */
	public function getLink() {
		return $this->link;
	}

}

?>