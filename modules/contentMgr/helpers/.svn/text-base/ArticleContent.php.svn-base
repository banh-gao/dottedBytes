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

use dottedBytes\libs\utils\ObjectUtils;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\users\permissions\PermissionSet;

use dottedBytes\libs\utils\Comparable;

use dottedBytes\libs\pageBuilder\Content;

use DateTime;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ArticleContent extends Content implements Comparable {
	
	private $id = 0;
	private $subtitle;
	private $tags;
	private $isPublished = true;
	private $useComments = false;
	private $readed = 0;
	private $params = array ();
	/**
	 * @var User
	 */
	private $author;
	/**
	 * @var User
	 */
	private $editor;
	/**
	 * @var DateTime
	 */
	private $authorDate;
	/**
	 * @var DateTime
	 */
	private $editorDate;
	/**
	 * @var PermissionSet
	 */
	private $permissions;
	
	public function __construct($databaseRow = null) {
		$this->authorDate = new DateTime ();
		$this->editorDate = new DateTime ();
		$this->title = CONTENT_ROOT;
		$this->permissions = new PermissionSet ();
		if ($databaseRow != null)
			$this->initialize ( $databaseRow );
	}
	
	private function initialize($row) {
		$this->id = $row->id;
		$this->title = $row->title;
		$this->subtitle = $row->subtitle;
		$this->data = $row->text;
		$this->author = UserUtils::getUser ( $row->authorID );
		$this->editor = UserUtils::getUser ( $row->editorID );
		$this->authorDate = new DateTime ( $row->creation_time );
		$this->editorDate = new DateTime ( $row->editor_time );
		$this->isPublished = ($row->published == 0) ? false : true;
		$this->useComments = ($row->useComments == 0) ? false : true;
		$this->readed = $row->readed;
	}
	
	/**
	 * @return the $tags
	 */
	public function getTags() {
		if($this->tags == null)
			$this->tags = Tag::getContentTags( $this->id );
		return $this->tags;
	}
	
	/**
	 * @param Comparable $obj
	 */
	public function compareTo(Comparable $obj = null) {
		/* @var $obj ArticleContent */
		ObjectUtils::checkType ( $obj, 'ArticleContent', true, false );
		
		return strcmp ( $this->title, $obj->getTitle () );
	}
	
	/**
	 * @param Comparable $obj
	 */
	public function equals(Comparable $obj = null) {
		if ($obj == null)
			return false;
		return ($this->id == $obj->getId ());
	}
	
	/**
	 * @return the $text
	 */
	public function getText() {
		return $this->data;
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return the $subtitle
	 */
	public function getSubtitle() {
		return $this->subtitle;
	}
	
	/**
	 * @return User
	 */
	public function getAuthor() {
		return $this->author;
	}
	
	/**
	 * @return User
	 */
	public function getEditor() {
		return $this->editor;
	}
	
	/**
	 * @return DateTime $authorDate
	 */
	public function getAuthorDate() {
		return $this->authorDate;
	}
	
	/**
	 * @return DateTime $editorDate
	 */
	public function getEditorDate() {
		return $this->editorDate;
	}
	
	/**
	 * @param $subtitle the $subtitle to set
	 */
	public function setSubtitle($subtitle) {
		$this->subtitle = $subtitle;
	}
	
	/**
	 * @param &alias addData
	 */
	public function addText($text) {
		$this->data = $text;
	}
	
	/**
	 * @return the $isPublished
	 */
	public function isPublished() {
		return $this->isPublished;
	}
	
	/**
	 * @return the $readed
	 */
	public function getReaded() {
		return $this->readed;
	}
	
	/**
	 * @return the $permissions
	 */
	public function getPermissions() {
		return $this->permissions;
	}
	
	/**
	 * @param $permissions the $permissions to set
	 */
	public function setPermissions($permissions) {
		$this->permissions = $permissions;
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
	
	public function __toString() {
		return $this->title . '(#' . $this->id . ')';
	}
	public function useComments() {
		return $this->useComments;
	}
}

?>