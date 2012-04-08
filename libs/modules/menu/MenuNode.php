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

namespace dottedBytes\libs\modules\menu;

// no direct access
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

class MenuNode {
	
	/**
	 * The parent node
	 * @var MenuNode
	 */
	private $parent;
	
	private $children = array ();
	
	private $selectedNode = null;
	
	private $_menuClass;
	
	private $label = null;
	private $link = null;
	private $image = null;
	private $tooltip = null;
	
	public function __construct($label = '', $link = '#') {
		$this->label = $label;
		$this->link = $link;
	}
	
	public function setSelected($isSelected) {
		if ($isSelected)
			$this->getRoot ()->setSelectedNode ( $this );
		else
			$this->getRoot ()->setSelectedNode ( null );
	}
	
	/**
	 * Returns the selected path
	 * @return MenuNode
	 */
	public function getSelectedPath() {
		$sel = $this->getSelectedNode ();
		if ($sel != null)
			return $sel->getAncestors ();
		else
			return array ();
	}
	
	public function getRoot() {
		if ($this->parent == null)
			return $this;
		
		$path = $this->getAncestors ();
		
		return $path [0];
	}
	
	public function getChildren() {
		return $this->children;
	}
	
	/**
	 * Add a new child to the node
	 */
	public function addChild(MenuNode $child) {
		$child->setParent ( $this );
		$this->children [] = $child;
	}
	
	/**
	 * Return the structured menu
	 *
	 * @return string generated HTML code
	 */
	public function render($class = 'menu') {
		$out = "";
		
		if ($this->parent == null) {
			$out .= "\n<ul class=\"$class\">\n";	
			if ($this->isVisible ())
				$out .= $this->htmlLink ();
			elseif (count ( $this->children ) == 0)
				return "";
		
		} elseif ($this->getVisibleParent () == null) {
			$out .= "<li>";
			$out .= $this->htmlLink ();
		} else {
			if ($this->isVisible ()) {
				if (count ( $this->children ) > 0) {
					$out .= "<li class=\"child hasChild\">";
				} else {
					$out .= "<li class=\"child\">";
				}
			}
			$out .= $this->htmlLink ();
		}
		
		if (count ( $this->children ) > 0) {
			$out .= "\n<ul>\n";
			foreach ( $this->children as $child ) {
				$out .= $child->render ();
			}
			$out .= "</ul>\n";
		}
		
		if ($this->parent == null) {
			$out .= "</ul>\n";
		} else {
			if ($this->isVisible () || $this->getVisibleParent () != null) {
				$out .= "</li>\n";
			}
		}
		
		return $out;
	}
	
	/**
	 * Generate the link for passed elements
	 *
	 * @param string $nodeName
	 */
	protected function htmlLink() {
		$noLink = ($this->link == '#') ? ' noLink' : '';
		$img = ($this->image == "") ? "" : "<img src=\"{$this->image}\">";
		$tooltip = ($this->tooltip == "") ? "" : "title=\"{$this->tooltip}\"";
		
		if ($this->isSelected ())
			$label = '<span class="active">' . $this->label . '</span>';
		elseif (array_search ( $this, $this->getSelectedPath () ) >= 0)
			$label = '<span class="parentActive">' . $this->label . '</span>';
		else
			$label = '<span>' . $this->label . '</span>';
		
		return "<a $tooltip href=\"{$this->link}\" class=\"child$noLink\">{$img}{$label}</a>";
	}
	
	public function isSelected() {
		return $this->getRoot ()->getSelectedNode () === $this;
	}
	
	/**
	 * @return MenuNode
	 */
	public function getParent() {
		return $this->parent;
	}
	
	public function getVisibleParent() {
		if ($this->parent == null)
			return null;
		
		$parent = $this->parent;
		
		while ( $parent != null ) {
			if ($parent->isVisible ())
				return $parent;
			
			$parent = $parent->getParent ();
		}
		return null;
	}
	
	public function getAncestors() {
		$ancestors = array ();
		
		if ($this->parent == null)
			return $ancestors;
		
		$parent = $this->parent;
		
		while ( $parent != null ) {
			array_unshift ( $ancestors, $parent );
			$parent = $parent->parent;
		}
		return $ancestors;
	}
	
	public function isVisible() {
		return $this->label != "";
	}
	
	/**
	 * @return the $label
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * @return the $link
	 */
	public function getLink() {
		return $this->link;
	}
	
	/**
	 * @return the $image
	 */
	public function getImage() {
		return $this->image;
	}
	
	/**
	 * @return the $tooltip
	 */
	public function getTooltip() {
		return $this->tooltip;
	}
	
	protected function setSelectedNode(MenuNode $node = null) {
		$this->selectedNode = $node;
	}
	
	/**
	 * Get the selected node
	 * @return MenuNode
	 */
	public function getSelectedNode() {
		return $this->selectedNode;
	}
	
	/**
	 * @param field_type $parent
	 */
	protected function setParent($parent) {
		$this->parent = $parent;
	}
	
	/**
	 * @param field_type $label
	 */
	public function setLabel($label) {
		$this->label = $label;
	}
	
	/**
	 * @param field_type $link
	 */
	public function setLink($link) {
		$this->link = $link;
	}
	
	/**
	 * @param field_type $image
	 */
	public function setImage($image) {
		$this->image = $image;
	}
	
	/**
	 * @param field_type $tooltip
	 */
	public function setTooltip($tooltip) {
		$this->tooltip = $tooltip;
	}
	
	/* (non-PHPdoc)
	 * @see libs/utils/Comparable::compareTo()
	 */
	public function compareTo(Comparable $obj = null) {
		if (! ($obj instanceof MenuNode))
			return 0;
		
		return strcmp ( $this->getLabel (), $obj->getLabel () );
	}
	
	public function __toString() {
		return "NODE " . $this->label;
	}
}

?>