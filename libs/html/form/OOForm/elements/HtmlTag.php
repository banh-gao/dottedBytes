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

namespace OOForm\elements;

/**
 * Html tag definition that provide tag attributes methods
 * @author Daniel Zozin
 *
 */
use OOForm\decorator\FormDecorator;

class HtmlTag {
	/**
	 * The html attributes for the element
	 * @var array
	 */
	protected $attributes = array ();
	protected $tagName;
	private $content;
	private $children = array ();
	
	public function __construct($tagName = '', $content = '') {
		$this->tagName = $tagName;
		$this->content = $content;
	}
	
	/**
	 * Get the html attribute value for the specified key or null if attribute is not set
	 * @param string $name
	 * @param string $value
	 * @return string
	 */
	public function getAttribute($name) {
		return (array_key_exists ( $name, $this->attributes )) ? $this->attributes [$name] : null;
	}
	
	/**
	 * Set an html attribute for this element,
	 * the old value (if any) will be returned
	 * @param string $name
	 * @param string $value
	 * @return string
	 */
	public function setAttribute($name, $value) {
		$value = str_replace ( '"', '&quot', $value );
		$oldValue = (array_key_exists ( $name, $this->attributes )) ? $this->attributes [$name] : '';
		$this->attributes [$name] = $value;
		return $oldValue;
	}
	
	/**
	 * Remove an html attribute for this element
	 * @param string $name
	 * @return string
	 */
	public function unsetAttribute($name) {
		if (array_key_exists ( $name, $this->attributes ))
			unset ( $this->attributes [$name] );
	}
	
	/**
	 * Returns an array with all the setted attributes
	 * @return array
	 */
	public function getAttributes() {
		return $this->attributes;
	}
	
	/**
	 * Get the tag name
	 * @return string
	 */
	public function getTagName() {
		return $this->tagName;
	}
	
	/**
	 * Add a child tag
	 * @param HtmlTag $tag
	 */
	public function addChild(HtmlTag $tag) {
		$this->children [] = $tag;
	}
	
	/**
	 * Remove a child from chis tag
	 * @param HtmlTag $tag
	 */
	public function removeChild(HtmlTag $tag) {
		foreach ( $this->children as $i => $child ) {
			if ($child == $tag) {
				unset ( $this->children [$i] );
				return;
			}
		}
	}
	
	/**
	 * Get all the children HtmlTag
	 * @return array
	 */
	public function getChildren() {
		return $this->children;
	}
	
	/**
	 * Render the children of this tag
	 * @param FormDecorator $decorator
	 */
	public function renderChildren(FormDecorator $decorator) {
		$out = '';
		foreach ( $this->getChildren () as $child ) {
			$out .= $decorator->render ( $child );
		}
		return $out;
	}
	
	/**
	 * Render the tag, defaults is the decorator that render the tag but can be overrided for custom rendering
	 * @param FormDecorator $decorator
	 */
	public function renderTag(FormDecorator $decorator) {
		return ($this->content == '') ? $decorator->renderTag ( $this ) : $this->content;
	}
	
	public function __toString() {
		$out = $this->getTagName () . '[';
		foreach ( $this->attributes as $name => $value ) {
			$out .= $name . '=' . $value . ' ';
		}
		return $out . ']';
	}
}

?>