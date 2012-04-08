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

namespace dottedBytes\modules\contentMgr\helpers\editor;

use dottedBytes\modules\contentMgr\helpers\Tag;

use dottedBytes\libs\io\FileUtils;

use dottedBytes\libs\database\DBManager;

use OOForm\elements\ajax\AjaxListener;

use PDO;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

FileUtils::loadFile ( 'libs/html/form/OOForm/Form' );

class TagsSuggestion extends AjaxListener {
	public function getResponse($query) {
		
		$database = DBManager::getInstance ();
		
		$parentTag = Tag::getByName ( substr ( $query, 0, strrpos ( $query, '/' ) ) );
		
		if ($parentTag != null) {
			$stm = $database->prepare ( "SELECT * FROM #__tags WHERE name LIKE ? AND parent=".$parentTag->getId() );
			$query = substr ( $query, strrpos ( $query, '/' ) + 1 );
		} else
			$stm = $database->prepare ( "SELECT * FROM #__tags WHERE name LIKE ?" );
			
		$param = $query . '%';
		$stm->bindParam ( 1, $param, PDO::PARAM_STR );
		$stm->execute ();
		
		$suggestions = array ();
		if ($stm->rowCount () > 0) {
			foreach ( $stm->fetchAll () as $suggestion ) {
				if ($suggestion->parent != 0) {
					$suggestions [] = Tag::getById ( $suggestion->id )->getCanonicalName ();
				} else
					$suggestions [] = $suggestion->name;
			}
		}
		return $suggestions;
	}
	
	public function getServiceUrl() {
		return BASEURL . '/index.php?section=contentMgr&task=editor_tags';
	}
}

?>