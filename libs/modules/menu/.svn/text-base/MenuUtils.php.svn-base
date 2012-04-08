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

namespace dottedBytes\libs\modules\menu;

// no direct access
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

use dottedBytes\libs\errorHandling\CmsException;

use dottedBytes\libs\io\IOException;

use dottedBytes\libs\io\FileUtils;

use dottedBytes\libs\modules\ModFactory;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\modules\ModuleException;

use dottedBytes\libs\database\DBManager;

use PDO;

class MenuUtils {
	const MENU_TOP = 1;
	const MENU_LEFT = 2;
	const MENU_RIGHT = 3;
	
	public static function addModule($menuID, $moduleID, $params, $ordering) {
		$database = DBManager::getInstance ();
		$stm = $database->prepare ( 'INSERT INTO #__menu (menuID,moduleID,params,ordering)  VALUES(?,?,?,?)' );
		$stm->bindParam ( 1, $menuID, PDO::PARAM_INT );
		$stm->bindParam ( 2, $moduleID, PDO::PARAM_INT );
		$stm->bindParam ( 3, $params, PDO::PARAM_STR );
		$stm->bindParam ( 4, $ordering, PDO::PARAM_INT );
		$stm->execute ();
	}
	
	public static function getMenu($menuID) {
		$menuID = ( int ) $menuID;
		$database = DBManager::getInstance ();
		//FIXME: recursive query
		$res = $database->query ( "SELECT mo.name,me.params FROM #__menu AS me JOIN #__modules AS mo ON me.moduleID=mo.id WHERE me.parentId=$menuID ORDER BY ordering ASC" );
		
		if ($res->rowCount () < 1)
			return null;
		
		$menu = new MenuNode ();
		
		foreach ( $res->fetchAll () as $row ) {
			$modMenu = self::createModuleMenu ( $row->name );
			$menu->addChild ( $modMenu->getMenu($row->params) );
		}
		return $menu;
	}
	
	public static function getRootsIds() {
		$menuID = ( int ) $menuID;
		$database = DBManager::getInstance ();
		$res = $database->query ( "SELECT * FROM #__menu WHERE menuID=$menuID ORDER BY ordering ASC" );
	
		//TODO
	}
	
	/**
	 * Create a Component module based on the specified name
	 * @return ModuleMenu
	 */
	public static function createModuleMenu($name) {
		$menu = null;
		
		$className = 'dottedBytes\modules\\' . $name . '\\' . ucfirst ( $name ) . 'Menu';
		
		$path = 'modules/' . $name . '/' . ucfirst ( $name ) . 'Menu';
		
		try {
			FileUtils::loadFile ( $path );
		} catch ( IOException $e ) {
			throw new CmsException ( 'Cannot open menu file for ' . $name . '.', 404 );
		}
		if (! class_exists ( $className ))
			throw new CmsException ( 'Main class for menu ' . $name . ' not found.', 404 );
		
		$menu = new $className ();
		
		if (! ($menu instanceof ModuleMenu))
			throw new CmsException ( 'Main class for ' . $name . ' menu isn\'t a ModuleMenu.' );
		
		return $menu;
	}
}

?>