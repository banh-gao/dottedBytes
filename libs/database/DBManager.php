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

namespace dottedBytes\libs\database;

use PDO;
use PDOStatement;
use Exception;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class DBManager {
	
	/**
	 * @var PDO
	 */
	private $pdo;
	
	private $prefix;
	private $prefixNotation = '#__';
	private $errors = array ();
	private static $instance = null;
	
	/**
	 * Singleton class don't use this constructor, use getInstance() instead
	 *
	 */
	private function __construct() {
		$this->pdo = new PDO ( CMS_DB_DSN, CMS_DB_USERNAME, CMS_DB_PASSWORD, array (PDO::ATTR_PERSISTENT ) );
		$this->pdo->setAttribute ( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ );
		$this->prefix = CMS_DB_PREFIX;
	}
	
	/**
	 * Returns an instance of the database
	 *
	 * @return DBManager
	 */
	static public function getInstance() {
		if (is_null ( self::$instance )) {
			self::$instance = new DBManager ();
		}
		return self::$instance;
	}
	
	/**
	 *
	 * @param query $query
	 * @return int
	 */
	public function exec($query) {
		$query = $this->addPrefix ( $query );
		$query = $this->useUTCTime ( $query );
		return parent::exec ( $query );
	}
	
	/**
	 *
	 * @param statement $statement
	 * @return PDOStatement
	 */
	
	public function query($statement) {
		$statement = $this->addPrefix ( $statement );
		$statement = $this->useUTCTime ( $statement );
		$result = $this->pdo->query ( $statement );
		if ($result == false) {
			throw new DatabaseException ( $this->pdo->errorInfo (), $statement );
		}
		return $result;
	}
	
	public function beginTransaction() {
		return $this->pdo->beginTransaction ();
	}
	
	public function commit() {
		return $this->pdo->commit ();
	}
	
	public function rollback() {
		return $this->pdo->rollBack ();
	}
	
	/**
	 *
	 * @param statement $statement
	 * @return PDOStatement
	 */
	public function prepare($statement, $options = array()) {
		$statement = $this->addPrefix ( $statement );
		$statement = $this->useUTCTime ( $statement );
		return $this->pdo->prepare ( $statement, $options );
	}
	
	private function addPrefix($query) {
		return str_replace ( $this->prefixNotation, $this->prefix, $query );
	}
	
	/**
	 * Make sure that the time will be stored in database using the GMT timezone
	 * @param string $query
	 * @return mixed
	 */
	private function useUTCTime($query) {
		return preg_replace ( '/NOW\([ ]*\)/', 'UTC_TIMESTAMP()', $query );
	}
	
	/**
	 * @param string $sql
	 * @return array the splitted queries
	 */
	public function splitSql($sql) {
		$sql = trim ( $sql );
		$sql = preg_replace ( '/\n\#[^\n]*/', '', "\n" . $sql );
		$buffer = array ();
		$ret = array ();
		$in_string = false;
		
		for($i = 0; $i < strlen ( $sql ) - 1; $i ++) {
			if ($sql [$i] == ";" && ! $in_string) {
				$ret [] = substr ( $sql, 0, $i );
				$sql = substr ( $sql, $i + 1 );
				$i = 0;
			}
			
			if ($in_string && ($sql [$i] == $in_string) && $buffer [1] != "\\") {
				$in_string = false;
			} elseif (! $in_string && ($sql [$i] == '"' || $sql [$i] == "'") && (! isset ( $buffer [0] ) || $buffer [0] != "\\")) {
				$in_string = $sql [$i];
			}
			if (isset ( $buffer [1] )) {
				$buffer [0] = $buffer [1];
			}
			$buffer [1] = $sql [$i];
		}
		
		if (! empty ( $sql )) {
			$ret [] = $sql;
		}
		return ($ret);
	}
	
	/**
	 * Check if a table exist in the database
	 *
	 * @access public
	 * @param string $tablename
	 * @return bool
	 */
	public function tableExists($tablename) {
		$res = $this->query ( "SHOW TABLES" );
		
		try {
			$tables = $res->fetchAll ( PDO::FETCH_NUM );
			;
		} catch ( Exception $e ) {
			$this->errors [] = "SQL ERROR " . $e->getCode () . ": " . $e->getMessage ();
			throw new DatabaseException ( $e->getMessage (), $e->getCode () );
		}
		
		foreach ( $tables as $table ) {
			if ($table == $tablename)
				return true;
		}
		return false;
	}
	
	public function getInsertId($name = '') {
		return $this->pdo->lastInsertId ( $name );
	}
	
	public function __toString() {
		array_map ( function ($l) { return $l . "\n"; }, $this->errors );
	}
}

?>