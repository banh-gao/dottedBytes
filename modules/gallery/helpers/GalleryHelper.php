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

use OOForm\elements\basic\SelectOption;

use OOForm\elements\basic\Select;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\utils\collections\ObjectList;

use dottedBytes\libs\errorHandling\ErrorToException;

use dottedBytes\libs\io\IOException;

use dottedBytes\libs\io\FileUtils;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\modules\PageNotFoundException;

use dottedBytes\libs\io\PageData;

use PclZip;

use DateTime;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class GalleryHelper {
	
	public static function publish() {
		$elementID = PageData::getParam ( 'itemid', - 1 );
		$elementID = ( int ) $elementID;
		
		try {
			$element = self::buildElement ( $elementID );
		} catch ( PageNotFoundException $e ) {
			return;
		}
		
		$database = DBManager::getInstance ();
		$database->query ( "UPDATE #__gallery SET published=1 WHERE id=$elementID OR parentID=$elementID" );
		
		if ($element->getType () == 'album') {
			UserUtils::getCurrentUser ()->logActivity ( 'Album ' . $element . ' published', 'Gallery management' );
			PageData::back ( sprintf ( GALLERY_ALBUM_PUBLISHED, $element->getTitle () ) );
		} else {
			UserUtils::getCurrentUser ()->logActivity ( 'Image ' . $element . ' published', 'Gallery management' );
			PageData::back ( sprintf ( GALLERY_IMAGE_PUBLISHED, $element->getTitle () ) );
		}
	}
	
	/**
	 * Generate a select box that contains all the albums
	 * @param array $excludedIDs
	 * @return Select
	 */
	public static function getAlbumSelect($name, $excludedIDs = array()) {
		$database = DBManager::getInstance ();
		$result = array ();
		$result = $database->query ( "SELECT id , title FROM #__gallery WHERE type='album' ORDER BY title ASC" );
		$select = new Select ( $name );
		$select->addOption ( new SelectOption ( GALLERY_ROOT, 0 ) );
		
		if ($result->rowCount () < 1)
			return $select;
		
		foreach ( $result->fetchAll () as $row ) {
			if (! in_array ( $row->id, $excludedIDs ))
				$select->addOption ( new SelectOption ( $row->title, $row->id ) );
		}
		return $select;
	}
	
	public static function unpublish() {
		$elementID = PageData::getParam ( 'itemid', - 1 );
		$elementID = ( int ) $elementID;
		
		try {
			$element = self::buildElement ( $elementID );
		} catch ( PageNotFoundException $e ) {
			return;
		}
		
		$database = DBManager::getInstance ();
		$database->query ( "UPDATE #__gallery SET published=0 WHERE id=$elementID  OR parentID=$elementID" );
		if ($element->getType () == 'album') {
			UserUtils::getCurrentUser ()->logActivity ( 'Album ' . $element . ' unpublished', 'Gallery management' );
			PageData::back ( sprintf ( GALLERY_ALBUM_UNPUBLISHED, $element->getTitle () ) );
		} else {
			UserUtils::getCurrentUser ()->logActivity ( 'Image ' . $element . ' published', 'Gallery management' );
			PageData::back ( sprintf ( GALLERY_IMAGE_UNPUBLISHED, $element->getTitle () ) );
		}
	}
	
	public static function delete() {
		$elementID = PageData::getParam ( 'itemid', - 1 );
		$elementID = ( int ) $elementID;
		
		try {
			$element = self::buildElement ( $elementID );
		} catch ( PageNotFoundException $e ) {
			return;
		}
		
		//Delete elements on disk
		if ($element->getType () == 'album') {
			try {
				FileUtils::delTree ( $element->getPath () );
			} catch ( IOException $e ) {
			
			}
			UserUtils::getCurrentUser ()->logActivity ( 'Album ' . $element . ' deleted', 'Gallery management' );
			$msg = sprintf ( GALLERY_ALBUM_DELETED, $element->getTitle () );
		} else {
			try {
				unlink ( $element->getPath () );
				unlink ( $element->getThumbPath () );
			} catch ( ErrorToException $e ) {
			
			}
			UserUtils::getCurrentUser ()->logActivity ( 'Image ' . $element . ' deleted', 'Gallery management' );
			$msg = sprintf ( GALLERY_IMAGE_DELETED, $element->getTitle () );
		}
		$database = DBManager::getInstance ();
		$database->query ( "DELETE FROM #__gallery WHERE id=$elementID" );
		$link = BASEURL . '/index.php?section=gallery&itemid=' . $element->getParentId ();
		
		PageData::redirect ( $link, $msg );
	}
	
	public static function regenerateThumbs() {
		$elementID = ( int ) PageData::getParam ( 'itemid', 0 );
		$regenerated = 0;
		$parent = self::buildElement ( $elementID );
		//Delete all old thumbs
		$dir = opendir ( $parent->getPath () );
		for($image = readdir ( $dir ); $image != false; $image = readdir ( $dir )) {
			if (substr ( $image, 0, 6 ) == 'thumb_')
				unlink ( $parent->getPath () . '/' . $image );
		}
		
		foreach ( self::getChildren ( $parent ) as $child ) {
			if (ImageHelper::createThumb ( $child ))
				$regenerated ++;
		}
		PageData::back ( sprintf ( GALLERY_ALBUM_REGENERATED, $regenerated ) );
	}
	
	public static function exportGallery() {
		$elementID = ( int ) PageData::getParam ( 'itemid', 0 );
		$element = GalleryHelper::buildElement ( $elementID );
		
		$fileName = GalleryHelper::getCleanedFilename ( $element->getTitle () ) . '_' . date ( "Y-m-d", time () ) . '.zip';
		$savePath = BASEPATH . '/modules/gallery/tmp/' . $fileName;
		$zip = new PclZip ( $savePath );
		$zip->create ( $element->getPath (), PCLZIP_OPT_REMOVE_PATH, GALLERY_BASEPATH );
		FileUtils::sendFile ( $savePath, $fileName, true );
		unlink ( $savePath );
		exit ();
	}
	
	/**
	 * Return the album corresponding to the specified id
	 * @param int $elementID
	 * @return GalleryElement
	 */
	public static function buildElement($elementID) {
		$elementID = ( int ) $elementID;
		
		//Load root album
		if ($elementID == 0) {
			$element = new RootAlbum ();
			return $element;
		}
		
		$database = DBManager::getInstance ();
		$result = $database->query ( "SELECT * FROM #__gallery WHERE id=$elementID" );
		if ($result->rowCount () < 1) {
			throw new PageNotFoundException ( 'Element with ID ' . $elementID . ' not found.', GALLERY_NOTFOUND, GALLERY_NOTFOUND_EXPLAIN );
		}
		
		$res = $result->fetch ();
		$element = new GalleryElement ();
		$element->setId ( $res->id );
		$element->setParentId ( ($res->parentID == null) ? 0 : $res->parentID );
		$element->setType ( $res->type );
		$element->setTitle ( $res->title );
		$element->setAuthor ( UserUtils::getUser ( $res->authorID ) );
		$element->setAuthorDate ( new DateTime ( $res->date ) );
		$element->setPublished ( $res->published );
		$albumPath = self::getElementPath ( $elementID );
		
		if (count ( $albumPath ) < 1)
			throw new PageNotFoundException ( 'Element with ID ' . $elementID . ' is not published.', GALLERY_NOTFOUND, GALLERY_NOTFOUND_EXPLAIN );
		
		$filePath = GALLERY_BASEPATH;
		for($i = 0; $i < count ( $albumPath ) - 1; $i ++) {
			$filePath .= '/' . $albumPath [$i]->fileName;
		}
		
		if ($element->getType () == "album") {
			$element->setFileName ( $albumPath [$i]->fileName );
			$element->setPath ( $filePath . '/' . $albumPath [$i]->fileName );
			$child = self::getChildren ( $element, 0, 1 );
			if ($child->count () < 1)
				$element->setThumbPath ( BASEPATH . '/modules/gallery/style/folder.png' );
			else
				$element->setThumbPath ( $child [0]->getThumbPath () );
		} else {
			$element->setFileName ( $albumPath [$i]->fileName );
			$element->setPath ( $filePath . '/' . $albumPath [$i]->fileName );
			$element->setThumbPath ( $filePath . '/' . 'thumb_' . $albumPath [$i]->fileName );
		}
		return $element;
	}
	
	/**
	 * Get the children of an element
	 * @param GalleryElement $element
	 * @param int $start
	 * @param int $limit
	 * @return ObjectList
	 */
	public static function getChildren(GalleryElement $element, $start = false, $limit = false) {
		$parentID = ( int ) $element->getId ();
		$start = ( int ) $start;
		$limit = ( int ) $limit;
		$lim = ($start >= 0 && $limit > 0) ? "LIMIT $start , $limit" : '';
		
		$database = DBManager::getInstance ();
		
		$published = (UserUtils::getCurrentUser()->hasPermission( 'editor' )) ? '' : "AND published=1";
		
		if ($parentID == 0)
			$result = $database->query ( "SELECT * FROM #__gallery WHERE parentID is NULL $published ORDER BY date DESC ,title ASC $lim" );
		else
			$result = $database->query ( "SELECT * FROM #__gallery WHERE parentID=$parentID $published ORDER BY date DESC ,title ASC $lim" );
		
		$children = new ObjectList ();
		
		if ($result->rowCount () < 1) {
			return $children;
		}
		
		while ( ($row = $result->fetch ()) !== false ) {
			$article = self::buildElement ( $row->id );
			$children->add ( $article );
		}
		
		return $children;
	}
	
	public static function getParentID($galleryID) {
		$galleryID = ( int ) $galleryID;
		$query = "SELECT parentID FROM #__gallery WHERE id=$galleryID";
		$database = DBManager::getInstance ();
		$result = $database->query ( $query );
		if ($result->rowCount () < 1)
			return null;
		$row = $result->fetch ();
		$result = array ();
		$result = $row->parentID;
		return $result;
	}
	
	private static function getElementPath($searchID) {
		$database = DBManager::getInstance ();
		$searchID = ( int ) $searchID;
		
		if ($searchID == null) {
			return array ();
		}
		
		$result = $database->query ( "SELECT * FROM #__gallery WHERE id='$searchID'" );
		
		if ($result->rowCount () < 1)
			return array ();
		
		$row = $result->fetch ();
		
		$path [] = $row;
		$path = array_merge ( self::getElementPath ( $row->parentID ), $path );
		
		return $path;
	}
}

?>
