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

namespace OOForm\decorator;

use OOForm\elements\JavascriptTag;

use OOForm\elements\HtmlTag;

use OOForm\Form;
use OOForm\elements\FormElement;

/**
 * This class defines the rendering methods offered by a FormDecorator
 * @author Daniel Zozin
 *
 */
class FormDecorator {
	
	private $headTags = array ();
	
	/**
	 * Render a tag and with the style markup of the decorator
	 * Warning! To avoid infinite calls don't call this method from inside a renderTag method, passing the corrent or a parent tag as argument
	 * @param HtmlTag $tag
	 * @return string
	 */
	public function render(HtmlTag $tag) {
		return $this->renderTag($tag);
	}
	
	/**
	 * Render a tag without external style markup, this will render the children recursively using the renderChildren method of HtmlTag
	 * @param HtmlTag $tag
	 * @return string
	 * @see HtmlTag::renderChildren()
	 */
	public final function renderTag(HtmlTag $tag) {
		$content = $tag->renderChildren($this);
		
		if($tag->getTagName() == '') {
			return $content;
		}
		
		if ($content != '') {
			$out = '<' . $tag->getTagName () . $this->renderAttributes ( $tag->getAttributes () ) . ">" . $content . "</{$tag->getTagName()}>\n";
		} else {
			$out = '<' . $tag->getTagName () . $this->renderAttributes ( $tag->getAttributes () ) . "/>\n";
		}
		return $out;
	}
	
	/**
	 * Render an array of attributes
	 * @param array $attributes
	 * @return string the concatenation of attributes in standard tag style
	 */
	public function renderAttributes($attributes) {
		$out = "";
		foreach ( $attributes as $key => $value ) {
			$out .= " $key=\"$value\"";
		}
		return $out;
	}
	
	/**
	 * Shortcut to add external css to the head
	 * @param string $cssUrl
	 */
	public function addCss($cssUrl, $media = "screen") {
		$tag = new HtmlTag ( 'link' );
		$tag->setAttribute ( 'href', $cssUrl );
		$tag->setAttribute ( 'media', $media );
		$tag->setAttribute ( 'rel', 'stylesheet' );
		$tag->setAttribute ( 'type', "text/css" );
		return $this->addHeadTag ( $tag );
	}
	
	/**
	 * Shortcut to add external javascript to the head
	 * @param string $javascriptUrl
	 */
	public function addJavascript($javascriptUrl) {
		$tag = new JavascriptTag( $javascriptUrl );
		return $this->addHeadTag ( $tag );
	}
	
	/**
	 * Add an html tag to the head section of the page
	 * @param string $tagName
	 * @param array $attributes
	 */
	public function addHeadTag(HtmlTag $tag) {
		$this->headTags [sha1 ( $tag->__toString () )] = $tag;
	}
	
	public function clearHeadTags() {
		$this->headTags = array ();
	}
	
	public function getHeadTags() {
		return $this->headTags;
	}
	
	public function getRenderedHeadTags() {
		$tags = array ();
		foreach ( $this->getHeadTags() as $tag ) {
			$tags [] = '<' . $tag->getTagName () . $this->renderAttributes ( $tag->getAttributes () ) . '></'.$tag->getTagName().'>';
		}
		return $tags;
	}
}

?>