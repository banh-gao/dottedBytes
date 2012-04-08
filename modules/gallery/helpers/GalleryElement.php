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

namespace dottedBytes\modules\gallery\helpers;

use dottedBytes\libs\utils\ObjectUtils;

use dottedBytes\libs\utils\Comparable;

use \DateTime;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class GalleryElement implements Comparable {
	private $parentId;
	private $id;
	private $type;
	private $title;
	private $isPublished;
	private $path;
	private $thumbPath;
	private $fileName;
	private $params = array ();
	/**
	 * @var User
	 */
	private $author;
	/**
	 * @var DateTime
	 */
	private $authorDate;
	
	public function __construct($databaseRow = null) {
		$this->authorDate = new DateTime ();
		if ($databaseRow != null)
			$this->initialize ( $databaseRow );
	}
	
	private function initialize($row) {
		$this->id = $row->id;
		$this->parentId = $row->parentID;
		$this->type = $row->type;
		$this->title = $row->title;
	}
	
	/**
	 * @param Comparable $obj
	 */
	public function compareTo(Comparable $obj = null) {
		/* @var $obj GalleryElement */
		ObjectUtils::checkType ( $obj, 'GalleryElement', true, false );
		return strcmp ( $this->title, $obj->getTitle () );
	}
	
	/**
	 * @param Comparable $obj
	 */
	public function equals(Comparable $obj = null) {
		/* @var $obj GalleryElement */
		ObjectUtils::checkType ( $obj, 'GalleryElement' );
		if ($obj == null)
			return false;
		return ($this->id == $obj->getId ());
	}
	
	/**
	 * @return the $parentId
	 */
	public function getParentId() {
		return $this->parentId;
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return User
	 */
	public function getAuthor() {
		return $this->author;
	}
	
	/**
	 * @return DateTime $authorDate
	 */
	public function getAuthorDate() {
		return $this->authorDate;
	}
	
	/**
	 * @param $title the $title to set
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * @param $isPublished the $isPublished to set
	 * @return the $isPublished
	 */
	public function setPublished($isPublished) {
		$this->isPublished = ($isPublished == 0) ? false : true;
	}
	
	public function isPublished() {
		return $this->isPublished;
	}
	
	/**
	 * @param - the name of the parameter
	 * @return mixed
	 */
	public function getParam($name) {
		if (! array_key_exists ( $name, $this->params ))
			return false;
		return $this->params [$name];
	}
	
	/**
	 * @param mixed the param name to set
	 * @param mixed the param value to set
	 */
	public function setParam($name, $value) {
		$this->params [$name] = $value;
	}
	/**
	 * @return the $path
	 */
	public function getPath() {
		return $this->path;
	}
	
	/**
	 * @return the $thumbPath
	 */
	public function getThumbPath() {
		return $this->thumbPath;
	}
	
	/**
	 * @return the $fileName
	 */
	public function getFileName() {
		return $this->fileName;
	}
	
	/**
	 * @return the $params
	 */
	public function getParams() {
		return $this->params;
	}
	/**
	 * @param $parentId the $parentId to set
	 */
	public function setParentId($parentId) {
		$this->parentId = $parentId;
	}
	
	/**
	 * @param $id the $id to set
	 */
	public function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * @param $type the $type to set
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * @param $path the $path to set
	 */
	public function setPath($path) {
		$this->path = $path;
	}
	
	/**
	 * @param $thumbPath the $thumbPath to set
	 */
	public function setThumbPath($thumbPath) {
		$this->thumbPath = $thumbPath;
	}
	
	/**
	 * @param $fileName the $fileName to set
	 */
	public function setFileName($fileName) {
		$this->fileName = $fileName;
	}
	
	/**
	 * @param $params the $params to set
	 */
	public function setParams($params) {
		$this->params = $params;
	}
	
	/**
	 * @param $author the $author to set
	 */
	public function setAuthor($author) {
		$this->author = $author;
	}
	
	/**
	 * @param $authorDate the $authorDate to set
	 */
	public function setAuthorDate($authorDate) {
		$this->authorDate = $authorDate;
	}
	
	public function __toString() {
		return $this->title . '(#' . $this->id . ')';
	}

}

?>