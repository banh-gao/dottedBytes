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

namespace dottedBytes\libs\utils;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class String implements Comparable {
	private $string;
	
	public function __construct($string) {
		$this->string = $string;
	}
	
	/**
	 * @return String
	 */
	public function getString() {
		return $this->string;
	}
	
	/**
	 * Compare this string with the specified string for alphabetic order
	 *
	 * @param String $object
	 * @return int
	 */
	public function compareTo(Comparable $obj = null) {
		return strcmp ( strval ( $this ), strval ( $obj ) );
	}
	
	public function equals(Comparable $obj = null) {
		return $this->string == strval ( $obj );
	}
	
	/**
	 * Generate random string
	 *
	 * @param int $minLength
	 * @param int $maxLength
	 * @param string $salt The salt where to pick chars
	 * @return string
	 */
	public static function rand($minLength = false, $maxLength = false, $salt = false) {
		//Inizialize random generator
		list ( $usec, $sec ) = explode ( ' ', microtime () );
		mt_srand ( ( float ) $sec + (( float ) $usec * 100000) );
		
		if ($salt == false || ! is_string ( $salt )) {
			$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		}
		
		$genString = "";
		if (! is_numeric ( $minLength )) {
			for($i = 0; $i < strlen ( $salt ); $i ++) {
				$genString .= substr ( $salt, mt_rand () % strlen ( $salt ), 1 );
			}
			return $genString;
		}
		if (! is_numeric ( $maxLength )) {
			for($i = 0; $i < mt_rand ( $minLength ); $i ++) {
				$genString .= substr ( $salt, mt_rand () % strlen ( $salt ), 1 );
			}
			return $genString;
		}
		for($i = 0; $i < mt_rand ( $minLength, $maxLength ); $i ++) {
			$genString .= substr ( $salt, mt_rand () % strlen ( $salt ), 1 );
		}
		return $genString;
	}
	
	/**
	 * Better explode implementation witch ignore first and last elements if they are the delimiter
	 *
	 * @param string $value
	 * @param string $delimiter
	 * @param int $limit
	 * @return array
	 */
	public static function split($value, $delimiter = '|', $limit = null) {
		$value = trim ( $value );
		
		if (strstr ( $value, $delimiter ) === false)
			return array ($value );
		
		if ($value [0] == $delimiter)
			$value = substr ( $value, 1 );
		
		if ($value [strlen ( $value ) - 1] == $delimiter)
			$value = substr ( $value, 0, strlen ( $value ) - 1 );
		
		if (strlen ( $value ) < 1)
			return array ();
		
		return (is_null ( $limit )) ? explode ( $delimiter, $value ) : explode ( $delimiter, $value, $limit );
	}
	
	public static function wordSplit($string, $minpos, $maxpos) {
		
		$string = self::trim ( $string );
		
		if (strlen ( $string ) < $maxpos - $minpos)
			return $string;
		
		$string = substr ( $string, $minpos, $maxpos );
		
		$last_space = strrpos ( $string, " " );
		
		return substr ( $string, 0, $last_space );
	}
	
	public static function trim($string) {
		$string = trim ( $string );
		return preg_replace ( "/[ ]{2,}/", " ", $string );
	}
	
	public function __toString() {
		return $this->string;
	}
}

?>