<?php
/**
 * @package		OOForm
 * @copyright	Copyright (C) 2010 OOForm. All rights reserved.
 * @author		Daniel Zozin
 * @license		GNU/GPL, see COPYING file
 *
 * This file is part of OOForm.
 * OOForm is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * OOForm is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OOForm.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OOForm\elements\group;

use OOForm\elements\HtmlTag;

use OOForm\decorator\FormDecorator;

use OOForm\elements\group\TreeNode;

use OOForm\elements\LabeledElement;

class TreeElement extends LabeledElement {
	
	/**
	 * The tree to render
	 * @var TreeNode
	 */
	private $tree;
	/**
	 * The form decorator
	 * @var FormDecorator
	 */
	private $decorator;
	
	public function __construct($label, TreeNode $tree) {
		parent::__construct ( $label );
		$this->tagName = 'ul';
		$this->tree = $tree;
	}
	
	public function renderTag(FormDecorator $decorator) {
		return $this->renderNode($this->tree);
	}
	
	private function renderNode(TreeNode $node,FormDecorator $decorator) {
		$out = $decorator->renderTag($node);
		if (count ( $node->getChildren () ) == 0) {
			return $out;
		}
		$out .= '<ul>';
		foreach ( $node->getChildren () as $child ) {
			$out .= $this->renderNode ( $child );
		}
		$out .= "</ul>\n";
	}
}

class TreeNode extends HtmlTag {
	
	private $children = array ();
	private $name, $label, $link;
	
	public function __construct($name, $label, $link = '') {
		parent::__construct('li');
		$this->name = $name;
		$this->label = $label;
		$this->link = link;
	}
	
	public function addChild(TreeNode $node) {
		$this->children [] = $node;
	}
	
	/**
	 * @return array
	 */
	public function getChildren() {
		return $this->children;
	}
	
	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * @return string
	 */
	public function getLink() {
		return $this->link;
	}

}

?>