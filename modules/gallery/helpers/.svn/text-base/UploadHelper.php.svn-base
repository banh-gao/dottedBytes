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

use OOForm\elements\file\UploadedFile;

use OOForm\FormRequest;

use dottedBytes\libs\logging\LogFactory;

use dottedBytes\libs\logging\Logger;

use OOForm\elements\file\MimeValidator;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\io\FileUtils;

use OOForm\validator\EmptyValidator;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\errorHandling\ErrorToException;

use dottedBytes\libs\io\IOException;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\modules\PageNotFoundException;

use PclZip;

use PDO;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class UploadHelper {
	
	private static $newAlbumDir = false;
	private static $published = false;
	
	public static function newAlbum() {
		$request = Form::getRequest ();
		$parentID = $request->getValue ( 'parentID', 0 );
		$title = $request->getValue ( 'title', '', new EmptyValidator () );
		self::$published = $request->getValue ( 'published', false );
		
		$request->sendErrors ();
		
		//Create new album directory
		$albumName = GalleryHelper::getCleanedFilename ( $title );
		$parent = GalleryHelper::buildElement ( $parentID );
		$albumPath = $parent->getPath () . '/' . $albumName;
		
		if (! self::createDir ( $albumPath ))
			$request->setError ( 'title', GALLERY_FORM_ERROR_PERMISSION );
		
		$request->sendErrors ();
		
		$database = DBManager::getInstance ();
		
		if ($parentID == 0)
			$parentID = null;
		
		$database->beginTransaction ();
		$query = "INSERT INTO #__gallery (parentID,type,title,filename,published,authorID,date) ";
		$query .= "VALUES (?,'album',?,?,?,?,NOW())";
		$query = $database->prepare ( $query );
		$query->bindParam ( 1, $parentID, PDO::PARAM_INT );
		$query->bindParam ( 2, $title, PDO::PARAM_STR );
		$query->bindParam ( 3, $albumName, PDO::PARAM_STR );
		$query->bindParam ( 4, self::$published, PDO::PARAM_BOOL );
		$uid = UserUtils::getCurrentUser ()->getId ();
		$query->bindParam ( 5, $uid, PDO::PARAM_INT );
		$query->execute ();
		$albumID = $database->getInsertId ();
		
		$album = GalleryHelper::buildElement ( $albumID );
		
		self::insertZip ( $request, $album );
		self::insertImages ( $request, $album );
		
		//If there is some error rollback before notification
		if (count ( $request->getErrors () ) > 0) {
			try {
				if (self::$newAlbumDir)
					rmdir ( $albumPath );
			} catch ( ErrorToException $e ) {
			}
			$database->rollBack ();
		} else {
			$database->commit ();
		}
		
		$request->sendErrors ();
		
		UserUtils::getCurrentUser ()->logActivity ( 'Album ' . $album . ' created', 'Gallery management' );
		
		$link = BASEURL . '/index.php?section=gallery&itemid=' . $albumID;
		PageData::redirect ( $link, sprintf ( GALLERY_ALBUM_SAVED, $title ) );
	}
	
	public static function updateElement() {
		$request = Form::getRequest ();
		$elementID = ( int ) $request->getValue ( 'itemid', 0, new EmptyValidator () );
		$title = $request->getValue ( 'title', '', new EmptyValidator () );
		$published = ( int ) $request->getValue ( 'published', 0, new EmptyValidator () );
		$newParent = ( int ) $request->getValue ( 'parentID', 0, new EmptyValidator () );
		
		$request->sendErrors ();
		
		$element = GalleryHelper::buildElement ( $elementID );
		
		$database = DBManager::getInstance ();
		
		//Move element
		if ($newParent != $element->getParentId ()) {
			$newParent = GalleryHelper::buildElement ( $newParent );
			self::moveElement ( $element, $newParent );
		}
		
		$query = $database->prepare ( "UPDATE #__gallery SET title=? , published=? , parentID=? WHERE id=?" );
		$query->bindParam ( 1, $title, PDO::PARAM_STR );
		$query->bindParam ( 2, $published, PDO::PARAM_BOOL );
		if ($newParent == 0) {
			$null = null;
			$query->bindParam ( 3, $null, PDO::PARAM_NULL );
		} else {
			$query->bindParam ( 3, $newParent, PDO::PARAM_INT );
		}
		$query->bindParam ( 4, $elementID, PDO::PARAM_INT );
		$query->execute ();
		
		$database->beginTransaction ();
		$database->query ( "UPDATE #__gallery SET published=$published WHERE parentID=$elementID" );
		
		if ($element->getType () == 'album') {
			
			self::insertZip ( $request, $element );
			self::insertImages ( $request, $element );
			
			//If there is some error rollback before notification
			if (count ( $request->getErrors () ) > 0) {
				$database->rollBack ();
			} else {
				$database->commit ();
			}
			
			$request->sendErrors ();
			
			UserUtils::getCurrentUser ()->logActivity ( 'Album ' . $element . ' updated', 'Gallery management' );
			
			$link = BASEURL . '/index.php?section=gallery&itemid=' . $element->getId ();
			PageData::redirect ( $link, sprintf ( GALLERY_ALBUM_UPDATED, $element->getTitle () ) );
		
		} else {
			
			UserUtils::getCurrentUser ()->logActivity ( 'Image ' . $element . ' updated', 'Gallery management' );
			
			$link = BASEURL . '/index.php?section=gallery&itemid=' . $element->getParentId ();
			PageData::redirect ( $link, sprintf ( GALLERY_IMAGE_UPDATED, $element->getTitle () ) );
		}
	
	}
	
	private static function insertZip(FormRequest $request, GalleryElement $album) {
		$zipImages = $request->getFiles ( 'zipImages', new MimeValidator ( 'application/zip' ) );
		if (count ( $zipImages ) < 1) {
			//Ignore field
			$request->unsetError ( 'zipImages' );
			return;
		}
		
		$zipPath = $zipImages [0]->getTempPath ();
		
		FileUtils::loadFile ( 'libs/io/pclzip.lib' );
		$archive = new PclZip ( $zipPath );
		$tmpPath = BASEPATH . '/modules/gallery/tmp/album' . $album->getId ();
		
		if ($archive->extract ( PCLZIP_OPT_PATH, $tmpPath ) == 0) {
			throw new IOException ( GALLERY_FORM_ERROR_ZIP );
		}
		
		$fileTree = FileUtils::fileTree ( $tmpPath, '*', false, true, 1 );
		$imageElement = new GalleryElement ();
		$imageElement->setParentId ( $album->getId () );
		
		$accepted = ModUtils::getCurrentModule ()->getConfigValue ( 'images.allowedTypes', array () );
		$accepted = explode ( ",", $accepted );
		foreach ( $fileTree as $path => $fileName ) {
			if ($fileName != '') {
				$imageElement->setTitle ( $fileName );
				try {
					$mime = FileUtils::getMime ( $path );
					if (in_array ( $mime, $accepted ))
						UploadHelper::insertImage ( $path, $imageElement );
				} catch ( GalleryException $e ) {
					//Ignore invalid image exception
				}
			}
		}
		
		FileUtils::delTree ( $tmpPath );
	}
	
	private static function insertImages(FormRequest $request, GalleryElement $parent) {
		
		$accepted = ModUtils::getCurrentModule ()->getConfigValue ( 'images.allowedTypes', array () );
		$accepted = explode ( ",", $accepted );
		$images = $request->getFiles ( 'images', new MimeValidator ( $accepted ) );
		
		if (count ( $images ) < 1) {
			//Ignore fields
			$request->unsetError ( 'images' );
			return false;
		}
		
		$imageElement = new GalleryElement ();
		$imageElement->setParentId ( $parent->getId () );
		foreach ( $images as $image ) {
			$imageElement->setTitle ( $image->getName () );
			try {
				UploadHelper::insertImage ( $image->getTempPath (), $image->getMime (), $imageElement );
			} catch ( GalleryException $e ) {
				return false;
			}
		}
	}
	
	private static function insertImage($tmpPath, GalleryElement $element) {
		$parent = GalleryHelper::buildElement ( $element->getParentId () );
		
		$filename = GalleryHelper::getCleanedFilename ( $element->getTitle () );
		
		$imagePath = $parent->getPath () . '/' . $filename;
		$thumbPath = $parent->getPath () . '/thumb_' . $filename;
		
		while ( file_exists ( $imagePath ) ) {
			$filename .= 'C';
			$imagePath = $parent->getPath () . '/' . $filename;
			$thumbPath = $parent->getPath () . '/thumb_' . $filename;
		}
		
		$thumb = new GalleryElement ();
		$thumb->setPath ( $imagePath );
		$thumb->setThumbPath ( $thumbPath );
		
		if (! copy ( $tmpPath, $imagePath ))
			return false;
		
		self::createThumb ( $thumb );
		
		$database = DBManager::getInstance ();
		$query = "INSERT INTO #__gallery (parentID,type,title,filename,published,authorID,date) ";
		$query .= "VALUES (?,'image',?,?,?,?,NOW())";
		$query = $database->prepare ( $query );
		$parentID = $element->getParentId ();
		$query->bindParam ( 1, $parentID, PDO::PARAM_INT );
		$title = $element->getTitle ();
		$query->bindParam ( 2, $title, PDO::PARAM_STR );
		$query->bindParam ( 3, $filename, PDO::PARAM_STR );
		$query->bindParam ( 4, self::$published, PDO::PARAM_BOOL );
		$uid = UserUtils::getCurrentUser ()->getId ();
		$query->bindParam ( 5, $uid, PDO::PARAM_INT );
		$query->execute ();
	}
	
	public static function createThumb(GalleryElement $element) {
		if (! file_exists ( $element->getThumbPath () )) {
			ImageHelper::resizeOriginal ( $element );
			ImageHelper::createThumb ( $element );
		}
	}
	
	private static function createDir($albumPath) {
		try {
			if (! file_exists ( $albumPath )) {
				self::$newAlbumDir = true;
				mkdir ( $albumPath );
			}
		} catch ( ErrorToException $e ) {
			return false;
		}
		return true;
	}
	
	private static function moveElement(GalleryElement $element, GalleryElement $newParent) {
		if ($newParent->getType () != 'album')
			throw new GalleryException ( 'The specified new location ' . $newParent . ' must be an album' );
		
		$newDirectory = $newParent->getPath ();
		
		$children = GalleryHelper::getChildren ( $element );
		if ($children->contains ( $newParent ))
			throw new GalleryException ( 'The specified new location ' . $newParent . ' cannot be a child of the current location' );
		
		if ($element->getType () == 'album') {
			if (FileUtils::copyDir ( $element->getPath (), $newDirectory )) {
				FileUtils::delTree ( $element->getPath () );
			} else {
				return false;
			}
		} else {
			$newImagePath = $newDirectory . '/' . $element->getFileName ();
			if (! rename ( $element->getPath (), $newImagePath ))
				return false;
			
			$newThumbPath = $newDirectory . '/thumb_' . $element->getFileName ();
			if (! rename ( $element->getThumbPath (), $newThumbPath ))
				return false;
		}
		return true;
	}
}

?>