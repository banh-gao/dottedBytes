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

namespace dottedBytes\libs\configuration;

use dottedBytes\libs\logging\ErrorLog;

use dottedBytes\libs\logging\LogFactory;

use dottedBytes\libs\database\DBManager;

use PDO;

// no direct access
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

/**
 * Provide access to the configuration table
 *
 */
class Configuration {
	
	/**
	 * Get the configuration item corresponding to the specified path
	 * @param string $configPath
	 * @return ConfigItem
	 */
	public static function getConfiguration($configPath) {
		$db = DBManager::getInstance ();
		$elements = explode ( '.', $configPath );
		
		$parentId = self::getRootId ( $elements [0] );
		
		$stm = $db->prepare ( "SELECT * FROM #__configurations WHERE name=? AND parentId=?" );
		$res = null;
		for($i = 1; $i < count ( $elements ); $i ++) {
			$stm->bindParam ( 1, $elements [$i], PDO::PARAM_STR );
			$stm->bindParam ( 2, $parentId, PDO::PARAM_INT );
			
			$stm->execute ();
			
			if ($stm->rowCount () < 1) {
				self::logMissingConfig ( $configPath );
				return null;
			}
			$res = $stm->fetch ();
			$parentId = $res->id;
		}
		if ($res == null) {
			self::logMissingConfig ( $configPath );
			return null;
		}
		
		return new ConfigurationItem ( $res->id, $configPath, $res->value, $res->type, $res->params, $res->comment );
	}
	
	private static function getRootId($rootName) {
		$db = DBManager::getInstance ();
		$stm = $db->prepare ( "SELECT * FROM #__configurations WHERE name=? AND parentId is NULL" );
		$stm->bindParam ( 1, $rootName, PDO::PARAM_STR );
		$stm->execute ();
		if ($stm->rowCount () < 1) {
			return null;
		}
		$res = $stm->fetch ();
		return $res->id;
	}
	
	private static function logMissingConfig($configPath) {
		$logger = LogFactory::getLogger ( 'missingConfig' );
		$bt = debug_backtrace ();
		
		//Ignore class internal calls
		while(count($bt) > 0) {
			if ($bt [0] ['file'] == __FILE__)
				array_shift ( $bt );
			else
				break;
		}
		
		//Ignore module call
		if ($bt [0] ['file'] == BASEPATH . '/libs/modules/Module.php') {
			array_shift ( $bt );
		}
		
		$caller = array_shift ( $bt );
		$file = $caller ['file'];
		$line = $caller ['line'];
		$logger->log ( "Missing configuration value " . $configPath . " (" . $file . " " . $line . ")" );
	}
	
	/**
	 * Get an array of the childern of the specified item
	 * @param string $parentPath
	 * @return array
	 */
	public static function getChildren($parentPath) {
		
		$parent = self::getConfiguration ( $parentPath );
		$result = array ();
		if ($parent == null && $parentPath != '.')
			return $result;
		
		$parentID = ($parentPath == '.') ? 0 : $parent->getId ();
		
		$db = DBManager::getInstance ();
		
		$sqlRes = $db->query ( "SELECT * FROM #__configurations WHERE parentID=$parentID ORDER BY name ASC" );
		
		if ($parentPath != '') {
			$parentPath .= '.';
		}
		
		foreach ( $sqlRes->fetchAll () as $row ) {
			$result [] = new ConfigurationItem ( $row->id, $parentPath . $row->name, $row->value, $row->type, $row->params, $row->comment );
		}
		return $result;
	}
	
	/**
	 * Get the configuration value corresponding to the specified path
	 * @param string $configPath
	 * @return string
	 */
	public static function getValue($configPath, $default = '') {
		$conf = self::getConfiguration ( $configPath );
		if ($conf == null)
			return $default;
		return $conf->getValue ();
	}
	
	/**
	 * Set the value at the specified configuration path, if the path doesn't exist, it will be created
	 * @param string $configPath
	 * @param string $value
	 */
	public static function setValue($configPath, $value) {
		return self::setConfiguration ( new ConfigurationItem ( $configPath, $value ) );
	}
	
	/**
	 * 
	 * Save the specified configuration item, if the item doesn't exist and the create flag
	 * is true a new configuration item will be created
	 * @param ConfigurationItem $item
	 * @param boolean $create
	 */
	public static function setConfiguration(ConfigurationItem $item, $create = true) {
		$db = DBManager::getInstance ();
		$elements = explode ( '.', $item->getName () );
		$parentId = 0;
		
		$stm = $db->prepare ( "SELECT * FROM #__configurations WHERE name=? AND parentId=?" );
		
		for($i = 0; $i < count ( $elements ); $i ++) {
			$stm->bindParam ( 1, $elements [$i], PDO::PARAM_STR );
			$stm->bindParam ( 2, $parentId, PDO::PARAM_INT );
			$stm->execute ();
			if ($stm->rowCount () < 1) {
				if ($create) {
					self::newValue ( $item );
					return true;
				} else {
					return false;
				}
			}
			$res = $stm->fetch ();
			$parentId = $res->id;
		}
		$stm = $db->prepare ( "UPDATE #__configurations SET value=? , type=? , params=? , comment=? WHERE id=?" );
		$value = $item->getValue ();
		$stm->bindParam ( 1, $value, PDO::PARAM_STR );
		$type = $item->getType ();
		$stm->bindParam ( 2, $type, PDO::PARAM_STR );
		$params = $item->getParams ();
		$stm->bindParam ( 3, $params, PDO::PARAM_STR );
		$comment = $item->getComment ();
		$stm->bindParam ( 4, $comment, PDO::PARAM_STR );
		$stm->bindParam ( 5, $res->id, PDO::PARAM_INT );
		$stm->execute ();
		
		return true;
	}
	
	private static function newValue(ConfigurationItem $item) {
		$db = DBManager::getInstance ();
		$elements = explode ( '.', $item->getName () );
		$parentId = 0;
		$stm = $db->prepare ( "SELECT * FROM #__configurations WHERE name=? AND parentId=?" );
		
		for($i = 0; $i < count ( $elements ); $i ++) {
			$stm->bindParam ( 1, $elements [$i], PDO::PARAM_STR );
			$stm->bindParam ( 2, $parentId, PDO::PARAM_INT );
			$stm->execute ();
			if ($stm->rowCount () < 1) {
				if ($i < count ( $elements ) - 1) {
					//Parent item
					$parentId = self::newParent ( $parentId, $elements [$i], $item->getName () );
				} else {
					//Item to insert
					self::newItem ( $item, $parentId, $elements [$i], $item->getName () );
					return;
				}
			} else {
				$res = $stm->fetch ();
				$parentId = $res->id;
			}
		}
	}
	
	private static function newParent($parentId, $name, $completeName) {
		$db = DBManager::getInstance ();
		$parentStm = $db->prepare ( "INSERT INTO #__configurations (parentId,name,comment) VALUES(?,?,?)" );
		$parentStm->bindParam ( 1, $parentId );
		$parentStm->bindParam ( 2, $name, PDO::PARAM_STR );
		$parentStm->bindParam ( 3, 'Located at: ' . $completeName, PDO::PARAM_STR );
		$parentStm->execute ();
		return $db->getInsertId ();
	}
	
	private static function newItem($item, $parentId, $name, $completeName) {
		$db = DBManager::getInstance ();
		$itemStm = $db->prepare ( "INSERT INTO #__configurations (parentId,name,value,type,params,comment) VALUES(?,?,?,?,?,?)" );
		
		$itemStm->bindParam ( 1, $parentId, PDO::PARAM_INT );
		
		$itemStm->bindParam ( 2, $name, PDO::PARAM_STR );
		
		$value = $item->getValue ();
		$itemStm->bindParam ( 3, $value, PDO::PARAM_STR );
		
		$type = $item->getType ();
		$itemStm->bindParam ( 4, $type, PDO::PARAM_STR );
		
		$params = $item->getParams ();
		$itemStm->bindParam ( 5, $params, PDO::PARAM_STR );
		
		$comment = ($item->getComment () == '') ? 'Located at: ' . $completeName : $item->getComment ();
		$itemStm->bindParam ( 6, $comment, PDO::PARAM_STR );
		
		$itemStm->execute ();
	}
}

?>