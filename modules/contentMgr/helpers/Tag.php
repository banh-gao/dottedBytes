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
namespace dottedBytes\modules\contentMgr\helpers;

use dottedBytes\modules\contentMgr\helpers\Tag;

use dottedBytes\libs\modules\ComponentException;

use dottedBytes\libs\utils\IllegalArgumentException;

use dottedBytes\libs\database\DBManager;

use PDO;

class Tag {
	
	private static $tags = array ();
	
	private $id;
	private $parentId = 0;
	private $name;
	
	private $options = "";
	
	public static function getContentTags($contentID) {
		$contentID = ( int ) $contentID;
		$res = array ();
		
		$database = DBManager::getInstance ();
		$dbRes = $database->query ( "SELECT tagID,options FROM #__content_tags WHERE contentID=$contentID" );
		
		if ($dbRes->rowCount () < 1)
			return $res;
		
		foreach ( $dbRes->fetchAll () as $tag ) {
			$res [$tag->tagID] = new Tag ( $tag->tagID );
			$res [$tag->tagID]->setOptions ( $tag->options );
		}
		return $res;
	}
	
	/**
	 * Get tag by the passed id
	 * @param int $tagId
	 * @return Tag
	 */
	public static function getById($tagId) {
		if ($tagId <= 0)
			return null;
		if (! array_key_exists ( $tagId, self::$tags ))
			self::$tags [$tagId] = new Tag ( $tagId );
		return clone self::$tags [$tagId];
	}
	
	/**
	 * Get tag by the passed canonical name
	 * @param string $tagName
	 * @return Tag
	 */
	public static function getByName($tagName) {
		if (strlen ( $tagName ) == 0)
			return null;
		foreach ( self::$tags as $tag ) {
			if ($tag->getCanonicalName () == $tagName)
				return clone $tag;
		}
		
		$parts = explode ( '/', $tagName );
		
		$database = DBManager::getInstance ();
		$lastId = 0;
		foreach ( $parts as $p ) {
			$dbRes = $database->query ( "SELECT id FROM #__tags WHERE name='$p' AND parent=$lastId" );
			if ($dbRes->rowCount () == 0)
				return null;
			$row = $dbRes->fetch ();
			$lastId = $row->id;
		}
		self::$tags [$lastId] = new Tag ( $lastId );
		return clone self::$tags [$lastId];
	}
	
	public static function addTag($canonicalName) {
		$parts = explode ( '/', $canonicalName );
		$database = DBManager::getInstance ();
		
		$lastId = 0;
		foreach ( $parts as $p ) {
			$query = $database->prepare ( "INSERT IGNORE INTO #__tags (parent,name) VALUES(?,?)" );
			$query->bindParam ( 1, $lastId, PDO::PARAM_INT );
			$query->bindParam ( 2, $p, PDO::PARAM_STR );
			$query->execute ();
			$lastId = $database->query ( "SELECT id FROM #__tags WHERE parent=$lastId AND name='$p'" )->fetch ()->id;
		}
	}
	
	private function __construct($tagId) {
		$database = DBManager::getInstance ();
		$dbRes = $database->query ( "SELECT parent,name FROM #__tags WHERE id=$tagId" );
		
		if ($dbRes->rowCount () < 1)
			throw new ComponentException ( "Invalid tag with ID " . $tagId );
		
		$tag = $dbRes->fetchObject ();
		$this->id = $tagId;
		$this->parentId = ($tag->parent == 0) ? null : $tag->parent;
		$this->name = $tag->name;
	}
	
	/**
	 * @return Tag
	 */
	public function getParent() {
		return self::getById ( $this->parentId );
	}
	
	/**
	 * Get the children of this tag
	 * @return Tag[]
	 */
	public function getChildren() {
		$database = DBManager::getInstance ();
		$dbRes = $database->query ( "SELECT id FROM #__tags WHERE parent=$this->id" );
		$res = array ();
		foreach ( $dbRes->fetchAll () as $child ) {
			$res [] = Tag::getById ( $child->id );
		}
		return $res;
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return the $name
	 */
	public function getLocalName() {
		return $this->name;
	}
	
	public function getCanonicalName() {
		$res = $this->name;
		$parent = $this->getParent ();
		while ( $parent != null ) {
			$res = $parent->getLocalName () . '/' . $res;
			$parent = $parent->getParent ();
		}
		return $res;
	}
	/**
	 * @return the $options
	 */
	public function getOptions() {
		return $this->options;
	}
	
	/**
	 * @param field_type $options
	 */
	public function setOptions($options) {
		$this->options = $options;
	}
	
	public function __toString() {
		return $this->name;
	}
}

?>