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

use dottedBytes\libs\modules\ModuleInfo;

use dottedBytes\libs\pageBuilder\listener\InitListener;

use dottedBytes\libs\pageBuilder\listener\ModuleListener;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\database\DBManager;
use dottedBytes\libs\pageBuilder\PageBuilder;
use dottedBytes\libs\io\FileUtils;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ModUtils {
	
	private static $moduleListeners;
	private static $currentModule;
	
	/**
	 * Return an array of all active panels
	 *
	 * @param string $type
	 * @return Panel[]
	 */
	static public function getPanels() {
		$database = DBManager::getInstance ();
		
		$result = $database->query ( "SELECT mi.name , pl.modOption FROM #__modules AS mi JOIN #__panels_loaded AS pl ON mi.id=pl.moduleID WHERE mi.active=1" );
		
		$res = array ();
		foreach ( $result->fetchAll () as $row ) {
			try {
				$module = ModFactory::createPanel ( $row->name, $row->modOption );
			} catch ( PanelException $e ) {
				$module = new Panel ();
				$module->setInitializeException ( $e );
			}
			$res [] = $module;
		}
		return $res;
	}
	
	/**
	 * Return the current page active component
	 * @return Component
	 */
	public static function getActiveComponent() {
		return PageBuilder::getInstance ()->getComponent ();
	}
	
	/**
	 * Return informations of all the installed modules
	 * @return ModuleInfo[]
	 */
	public static function getModulesInfo() {
		$database = DBManager::getInstance ();
		$result = $database->query ( "SELECT id,name FROM #__modules" );
		$res = array ();
		foreach ( $result->fetchAll () as $row ) {
			$builder = new ModuleBuilder ();
			$builder->id ( $row->id )->name ( $row->name );
			$res [] = new ModuleInfo ( $builder );
		}
		return $res;
	}
	
	public static function getModuleInfoByName($moduleName) {
		return new ModuleInfo ( ModFactory::getBuilderByName ( $moduleName ) );
	}
	
	public static function getModuleInfoById($moduleId) {
		return new ModuleInfo ( ModFactory::getBuilderById ( $moduleId ) );
	}
	
	/**
	 * Return the current page active component
	 * @return Module
	 */
	public static function getCurrentModule() {
		return self::$currentModule;
	}
	
	public static function setCurrentModule(Module $currentModule) {
		self::$currentModule = $currentModule;
	}
	
	public static function loadLanguage(Module $module) {
		$languages = FileUtils::fileTree ( 'language/' . $module->getName (), '*.php' );
		
		$user = UserUtils::getCurrentUser ();
		$path = array_search ( $user->getLanguage () . '.php', $languages );
		if ($path == false) {
			foreach ( $languages as $file => $language ) {
				if (preg_match ( '/(.*)*(_default\.php)+/', $language )) {
					$path = $file;
				}
			}
			if ($path == false)
				return false;
		}
		$path = substr ( $path, 0, strrpos ( $path, '.' ) );
		FileUtils::loadFile ( $path );
		return true;
	}
	
	/**
	 * Return package for specified module name
	 *
	 * @param string $name
	 * @return string
	 */
	private function getModPackage($name) {
		if (file_exists ( BASEPATH . '/modules/' . $name . '.php' ))
			return BASEPATH . '/modules/' . $name;
		
		return false;
	}
	
	private static function loadModuleListeners() {
		$database = DBManager::getInstance ();
		
		$query = "SELECT #__modules.name as moduleName, #__listeners.name as listenerName , #__listeners.pattern ";
		$query .= "FROM #__listeners JOIN #__modules ON #__listeners.moduleID=#__modules.id ";
		$query .= "WHERE #__listeners.active=1 AND #__listeners.type='module' ORDER BY ordering ASC";
		$result = $database->query ( $query );
		$rows = $result->fetchAll ();
		$textProcessors = array ();
		$moduleListeners = array ();
		foreach ( $rows as $row ) {
			if (preg_match ( '/' . $row->pattern . '/', PageData::getPageUrl () ) == 0)
				continue;
			
			if (! FileUtils::file_exists ( BASEPATH . '/modules/' . $row->moduleName . '/listeners/' . $row->listenerName . '.php' ))
				throw new ModuleException ( 'Listener ' . $row->listenerName . ' of module ' . $row->moduleName . ' not found' );
			
			FileUtils::loadFile ( 'modules/' . $row->moduleName . '/listeners/' . $row->listenerName );
			
			if (! class_exists ( $row->listenerName ))
				throw new ModuleException ( 'Listener class ' . $row->listenerName . ' of module ' . $row->moduleName . ' does not exist' );
			
			$listener = new $row->listenerName ();
			
			if (! ($listener instanceof ModuleListener))
				throw new ModuleException ( 'Listener class ' . $row->listenerName . ' of module ' . $row->moduleName . ' is not a ModuleListener' );
			self::$moduleListeners [] = $listener;
		}
	}
	
	private static function getInitListeners() {
		$database = DBManager::getInstance ();
		
		$query = "SELECT #__modules.name as moduleName, #__listeners.name as initName , #__listeners.pattern ";
		$query .= "FROM #__listeners JOIN #__modules ON #__listeners.moduleID=#__modules.id ";
		$query .= "WHERE #__listeners.active=1 AND #__listeners.type='init' ORDER BY ordering ASC";
		$result = $database->query ( $query );
		$rows = $result->fetchAll ();
		
		$initListeners = array ();
		
		if ($result->rowCount () < 1)
			return $initListeners;
		foreach ( $rows as $row ) {
			if (preg_match ( '/' . $row->pattern . '/', PageData::getPageUrl () ) == 0)
				continue;
			
			if (! FileUtils::file_exists ( BASEPATH . '/modules/' . $row->moduleName . '/init/' . $row->initName . '.php' ))
				throw new ModuleException ( 'Init ' . $row->initName . ' of module ' . $row->moduleName . ' not found' );
			
			FileUtils::loadFile ( 'modules/' . $row->moduleName . '/listeners/' . $row->initName );
			
			if (! class_exists ( $row->initName ))
				throw new ModuleException ( 'Init class ' . $row->initName . ' of module ' . $row->moduleName . ' does not exist' );
			
			$module = ModUtils::getModuleInfoByName ( $row->moduleName );
			
			$script = new $row->initName ( $module );
			
			if (! ($script instanceof InitListener))
				throw new ModuleException ( 'Init class ' . $row->loaderName . ' of module ' . $row->moduleName . ' is not an InitListener' );
			
			$initListeners [] = $script;
		}
		return $initListeners;
	}
	
	/**
	 * Return an array that contain all the module listeners
	 *
	 * @return ModuleListener[]
	 */
	private static function getModuleListeners() {
		if (self::$moduleListeners != null)
			return self::$moduleListeners;
		
		self::loadModuleListeners ();
		
		return self::$moduleListeners;
	}
	
	public static function notifyInitListeners() {
		foreach ( self::getInitListeners () as $listener ) {
			/* @var $listener InitListener */
			$listener->process ();
		}
	}
	
	public static function notifyModuleListeners(Module $module) {
		$listeners = self::getModuleListeners ();
		
		foreach ( $listeners as $listener ) {
			/* @var $listener ModuleListener */
			$listener->process ( $module );
		}
	}
}

?>