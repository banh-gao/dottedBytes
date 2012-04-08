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

namespace dottedBytes\libs\html\rss;

// no direct access
use dottedBytes\libs\io\PageData;

use dottedBytes\libs\io\FileUtils;

use dottedBytes\libs\configuration\Configuration;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );
	
class RSSGenerator {
	private $Articles = array ();
	
	// Channel info
	private $title = '';
	private $link = '';
	private $description = '';
	private $namespaces = array ();
	private $optionals = array ();
	
	private $lastModified = 0;
	
	public function __construct($title = '', $link = '', $description = '') {
		$this->setTitle($title);
		$this->setLink($link);
		$this->setDescription($description);
		
		$this->addNamespace ( 'atom', 'http://www.w3.org/2005/Atom' );
	}
	
	public function setTitle($title) {
		$this->title = html_entity_decode ( $title, ENT_QUOTES, 'UTF-8' );
	}
	
	public function setLink($link) {
		$this->link = htmlentities ( $link , ENT_QUOTES , 'UTF-8');
	}
	
	public function setDescription($description) {
		$this->description = htmlentities ( $description, ENT_QUOTES, 'UTF-8' );
	}
	
	public function addNamespace($name, $url) {
		$this->namespaces [$name] = $url;
	}
	
	public function addField($tag, $value, $attributes = '') {
		$this->optionals [] = array ('tag' => $tag, 'value' => $value, 'attributes' => $attributes );
	}
	
	public function addElement(FeedItem $article) {
		
		$date = $article->getDate ()->format ( 'U' );
		if ($date > $this->lastModified)
			$this->lastModified = $date;
		
		$this->Articles [] = $article;
	}
	
	public function getOutput() {
		$out = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
		$out .= '<rss version="2.0"';
		foreach ( $this->namespaces as $name => $url ) {
			$out .= ' xmlns:' . $name . '="' . $url . '"';
		}
		
		$out .= '>' . "\n<channel>\n";
		
		$out .= '<atom:link href="' . htmlentities ( PageData::getPageUrl (), ENT_QUOTES, 'UTF-8' ) . '" rel="self" type="application/rss+xml" />' . "\n";
		
		$out .= "<lastBuildDate>" . date ( DATE_RFC2822, $this->lastModified ) . "</lastBuildDate>\n";
		$out .= "<title>{$this->title}</title>\n";
		$out .= "<link>{$this->link}</link>\n";
		$out .= "<description>$this->description</description>\n";
		
		//Channel optionals
		foreach ( $this->optionals as $field ) {
			$out .= $this->getFieldTag ( $field );
		}
		
		//Articles
		foreach ( $this->Articles as $article ) {
			/* @var $article Article */
			$out .= "<item>\n";
			$out .= "<guid>" . $article->getLink () . "</guid>\n";
			$out .= "<title>" . $article->getTitle () . "</title>\n";
			$out .= "<link>" . $article->getLink () . "</link>\n";
			$fromMail = Configuration::getValue ( 'system.email.fromMail' );
			$out .= "<author>" . $fromMail . " (" . $article->getAuthor ()->getName () . ")</author>\n";
			$out .= "<description>" . $article->getDescription () . "</description>\n";
			$out .= "<pubDate>" . $article->getDate ()->format ( DATE_RFC2822 ) . "</pubDate>\n";
			
			foreach ( $article->getAttachments () as $attachment ) {
				$out .= $this->getAttachmentTag ( $attachment );
			}
			
			foreach ( $article->getFields () as $field ) {
				$out .= $this->getFieldTag ( $field );
			}
			$out .= "</item>\n";
		}
		
		$out .= "</channel>\n</rss>";
		
		return $out;
	}
	
	private function getFieldTag($field) {
		if ($field ['attributes'] != '')
			$field ['attributes'] = ' ' . $field ['attributes'];
		
		return ($field ['value'] == '') ? "<{$field['tag']}{$field['attributes']}/>\n" : "<{$field['tag']}{$field['attributes']}>{$field['value']}</{$field['tag']}>\n";
	}
	
	private function getAttachmentTag($url) {
		$type = FileUtils::getMime ( $url );
		$mime = $type ['mime'];
		$size = filesize ( BASEPATH . FileUtils::stripBaseUrl ( $url ) );
		return '<enclosure url="' . $url . '" length="' . $size . '" type="' . $mime . '"/>' . "\n";
	}
}
?>