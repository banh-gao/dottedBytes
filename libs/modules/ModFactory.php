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

namespace dottedBytes\libs\modules;

use dottedBytes\libs\modules\ModuleBuilder;

use dottedBytes\debug;

use dottedBytes\modules\contentMgr\ContentMgrComponent;

use dottedBytes\libs\utils\ObjectUtils;

use dottedBytes\libs\configuration\ConfigurationItem;

use dottedBytes\libs\io\IOException;

use dottedBytes\libs\configuration\Configuration;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\io\FileUtils;
use PDO;
use dottedBytes\libs\database\DBManager;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ModFactory {
	
	/**
	 * Create a Component module based on the specified name
	 * @return Component
	 */
	public static function createComponent($name) {
		$comp = null;
		
		$className = 'dottedBytes\modules\\' . $name . '\\' . ucfirst ( $name ) . 'Component';
		
		$path = 'modules/' . $name . '/' . ucfirst ( $name ) . 'Component';
		
		try {
			FileUtils::loadFile ( $path );
		} catch ( IOException $e ) {
			throw new ComponentException ( 'Cannot open component file for ' . $name . '.', 404 );
		}
		if (! class_exists ( $className ))
			throw new ComponentException (null, 'Main class for component ' . $name . ' not found.', 404 );
		
		$moduleBuilder = ModFactory::getBuilderByName ( $name );
		
		/* @var $comp Component */
		$comp = new $className ( $moduleBuilder );
		
		if (! ($comp instanceof Component))
			throw new ComponentException (null, 'Main class for component ' . $name . ' isn\'t a component.' );
		
		return $comp;
	}
	
	/**
	 * Create a Panel with the specified name
	 * @param string $name - the panel name
	 * @return Panel
	 */
	public static function createPanel($moduleName, $option) {
		
		$className = 'dottedBytes\modules\\' . $moduleName . '\\' . ucfirst ( $moduleName ) . 'Panel';
		
		$modulePath = 'modules/' . $moduleName . '/' . ucfirst ( $moduleName ) . 'Panel';
		
		try {
			FileUtils::loadFile ( $modulePath );
		} catch ( IOException $e ) {
			throw new PanelException (null, 'Cannot open panel file for ' . $moduleName . '.' );
		}
		
		if (! class_exists ( $className ))
			throw new ComponentException (null, 'Main class for panel ' . $moduleName . ' not found.' );
		
		$panelBuilder = self::getPanelBuilder ( $moduleName, $option );
		
		/* @var $panel Panel */
		$panel = new $className ( $panelBuilder );
		
		if (! ($panel instanceof Panel))
			throw new PanelException (null, 'Main class for panel ' . $moduleName . ' isn\'t a panel.' );
		
		return $panel;
	}
	
	private static function getPanelBuilder($moduleName, $option) {
		$database = DBManager::getInstance ();
		$query = $database->prepare ( "SELECT * FROM #__panels_loaded WHERE moduleID=(SELECT id FROM #__modules WHERE name=:name) AND modOption=:option" );
		$query->bindParam ( ':name', $moduleName, PDO::PARAM_STR );
		$query->bindParam ( ':option', $option, PDO::PARAM_STR );
		$query->execute ();
		
		if ($query->rowCount () < 1)
			return null;
		
		$info = $query->fetch ();
		
		$panelBuilder = new PanelBuilder ( self::getBuilderByName ( $moduleName ) );
		
		$panelBuilder->title($info->title)->panelID ( $info->id )->option ( $info->modOption )->params ( $info->params );
		$panelBuilder->position ( $info->position )->ordering ( $info->ordering );
		return $panelBuilder;
	}
	
	/**
	 *
	 * @param string $moduleName
	 * @return Module
	 */
	public static function getBuilderByID($moduleID) {
		$database = DBManager::getInstance ();
		$query = $database->prepare ( "SELECT * FROM #__modules WHERE id=:id" );
		$query->bindParam ( ':id', $moduleID, PDO::PARAM_INT );
		$query->execute ();
		return self::getModuleBuilder ( $query );
	}
	
	/**
	 *
	 * @param string $moduleName
	 * @return Module
	 */
	public static function getBuilderByName($moduleName) {
		$database = DBManager::getInstance ();
		$query = $database->prepare ( "SELECT * FROM #__modules WHERE name=:name" );
		$query->bindParam ( ':name', $moduleName, PDO::PARAM_STR );
		$query->execute ();
		return self::getModuleBuilder ( $query );
	}
	
	/**
	 * @return ModuleBuilder
	 */
	private static function getModuleBuilder($query) {
		$builder = new ModuleBuilder ();
		
		if ($query->rowCount () < 1)
			return $builder;
		
		$info = $query->fetch ();
		$builder = new ModuleBuilder ();
		$builder->id ( $info->id )->name ( $info->name );
		return $builder;
	}
}

?>