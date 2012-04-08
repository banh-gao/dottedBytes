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

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\io\FileUtils;

use dottedBytes\libs\database\DBManager;
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class RecoveryHelper {
	
	public static function mapOrphanImages() {
		$database = DBManager::getInstance ();
		
		$files = FileUtils::fileTree ( GALLERY_BASEPATH, '', true, false );
		
		$result = $database->query ( "SHOW TABLE STATUS LIKE '#__gallery'" );
		$result = $result->fetch ();
		$nextInsertID = $result->Auto_increment;
		
		$parentID = 0;
		
		$structure = self::buildDataStructure ( $files, $nextInsertID, $parentID );
		
		$query = "INSERT INTO #__gallery (id,parentID,type,title,fileName,published,authorID,date) VALUES \n";
		foreach ( $structure as $node ) {
			if ($node ['parentID'] == 0)
				$node ['parentID'] = 'NULL';
			$query .= "({$node['id']},{$node['parentID']},'{$node['type']}','{$node['title']}','{$node['fileName']}',0," . UserUtils::getCurrentUser ()->getId () . ",NOW()),\n";
		}
		
		//Remove last comma
		$query = substr ( $query, 0, strlen ( $query ) - 2 );
		
		$database->query ( $query );
		
		self::thumbOrphanImages ( $structure, GALLERY_BASEPATH, 0 );
	}
	
	private static function buildDataStructure($filetree, &$id, $parentID) {
		$structure = array ();
		
		foreach ( $filetree as $path => $file ) {
			//Is directory
			if (is_array ( $file )) {
				$currentID = $id ++;
				
				$title = substr ( $path, strrpos ( $path, '/' ) + 1 );
				
				//Add current directory to structure
				$fileName = strtolower ( str_replace ( ' ', '_', String::trim ( $title ) ) );
				$structure [] = array ('id' => $currentID, 'parentID' => $parentID, 'type' => 'album', 'title' => $title, 'fileName' => $fileName, 'extension' => '' );
				
				//Recursive build into current directory
				$structure = array_merge ( $structure, self::buildDataStructure ( $file, $id, $currentID ) );
			
		//Is valid file
			} elseif (self::isOrphanFile ( $file )) {
				$currentID = $id ++;
				
				$dotPos = strrpos ( $file, '.' );
				$title = substr ( $file, 0, $dotPos );
				$extension = substr ( $file, $dotPos + 1 );
				
				$fileName = strtolower ( str_replace ( ' ', '_', String::trim ( $title ) ) );
				
				//Add current file to structure
				$structure [] = array ('id' => $currentID, 'parentID' => $parentID, 'type' => 'image', 'title' => $title, 'fileName' => $fileName, 'extension' => $extension );
			
			}
		}
		return $structure;
	}
	
	/**
	 * Check if the file is of the allowed type and isn't a normal image (not thumb)
	 * @param string $file
	 * @return boolean
	 */
	private static function isOrphanFile($file) {
		$allowedExt = ModUtils::getCurrentModule ()->getConfigValue ( 'images.allowedTypes', array () );
		$ext = substr ( $file, strrpos ( $file, '.' ) + 1 );
		return (array_search ( $ext, $allowedExt ) !== false && preg_match ( '/^thumb_/', $file ) == 0);
	}
	
	public static function thumbOrphanImages($dataStructure, $parentPath, $parentID) {
		foreach ( $dataStructure as $arrayID => $node ) {
			if ($node ['parentID'] == $parentID) {
				if ($node ['type'] == 'album') {
					unset ( $dataStructure [$arrayID] );
					rename ( $parentPath . '/' . $node ['title'], $parentPath . '/' . $node ['fileName'] );
					self::thumbOrphanImages ( $dataStructure, $parentPath . '/' . $node ['fileName'], $node ['id'] );
				} else {
					rename ( $parentPath . '/' . $node ['title'] . '.' . $node ['extension'], $parentPath . '/' . $node ['fileName'] . '.' . $node ['extension'] );
					$albumNode = new GalleryElement ();
					$albumNode->path = $parentPath . '/' . $node ['fileName'] . '.' . $node ['extension'];
					$albumNode->thumbPath = $parentPath . '/thumb_' . $node ['fileName'] . '.' . $node ['extension'];
					$albumNode->extension = $node ['extension'];
					ImageHelper::createThumb ( $albumNode );
				}
			}
		}
	}
}

?>