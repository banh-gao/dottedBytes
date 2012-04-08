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
use dottedBytes\libs\users\User;

use \DateTime;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

class FeedItem {
	
	private $title, $link, $description;
	/**
	 * @var User
	 */
	private $author;
	
	/**
	 * @var DateTime
	 */
	private $date;
	private $optionals = array (), $attachments = array ();
	
	public function __construct($title = '', $link = '', $description = '') {
		$this->setTitle ( $title );
		$this->setLink ( $link );
		$this->setDescription ( $description );
	}
	
	public function setTitle($title) {
		$this->title = html_entity_decode ( strip_tags ( $title ), ENT_QUOTES, 'UTF-8' );
	}
	
	public function setLink($link) {
		$this->link = htmlentities($link,ENT_QUOTES,'UTF-8',false);
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function setDate(DateTime $date) {
		$this->date = $date;
	}
	
	public function setAuthor(User $author) {
		$this->author = $author;
	}
	
	public function addField($tag, $value, $attributes = '') {
		$this->optionals [] = array ('tag' => $tag, 'value' => $value, 'attributes' => $attributes );
	}
	
	public function addAttachment($url) {
		$this->attachments [] = $url;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getLink() {
		return $this->link;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * @return DateTime
	 */
	public function getDate() {
		return $this->date;
	}
	
	/**
	 * @return User
	 */
	public function getAuthor() {
		return $this->author;
	}
	
	public function getFields() {
		return $this->optionals;
	}
	
	public function getAttachments() {
		return $this->attachments;
	}
}
?>