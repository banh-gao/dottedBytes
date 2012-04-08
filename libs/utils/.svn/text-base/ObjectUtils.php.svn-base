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

class ObjectUtils {
	static public function getHash($o) {
		return md5 ( serialize ( $o ) );
	}
	
	/**
	 * Check if the specified object is of the expected type
	 *
	 * @param mixed $object
	 * @param string $expected - the expected type
	 * @param boolean $exception - if true(default) the method will throw a TypeException on check failure
	 * @param boolean $acceptNull - if true(default) accept null values
	 * @return boolean - returns a value only if exception thrown is disabled
	 */
	static public function checkType($object, $expected, $exception = true, $acceptNull = true) {
		if ($object == null && $acceptNull)
			return true;
		if (! is_object ( $object )) {
			if ($exception)
				throw new TypeException ( 'Expected variable must be an object, ' . gettype ( $object ) . ' given' );
			else
				return false;
		}
		
		if (! ($object instanceof $expected)) {
			if ($exception)
				throw new TypeException ( 'Expected object must be an instance of ' . $expected . ', ' . get_class ( $object ) . ' given' );
			else
				return false;
		}
		return true;
	}
	
	/**
	 * Check if the argument in the specified position is of the expected type
	 *
	 * @param int $argumentPos - the argument position starting by 1
	 * @param string $expected - the expected type
	 * @param boolean $exception - if true(default) the method will throw a TypeException on check failure
	 * @param boolean $acceptNull - if true(default) accept null values
	 * @return boolean - returns a value only if exception thrown is disabled
	 */
	static public function checkArgument($argumentPos, $expected, $exception = true, $acceptNull = true) {
		$backtrace = debug_backtrace ();
		$caller = $backtrace [1] ['function'];
		if (! array_key_exists ( $argumentPos - 1, $backtrace [1] ['args'] ))
			throw new IllegalArgumentException ( $argumentPos, $expected, null );
		
		$value = $backtrace [1] ['args'] [$argumentPos - 1];
		
		if ($value == null && $acceptNull != true)
			throw new IllegalArgumentException ( $argumentPos, $expected, null );
		switch ($expected) {
			case 'int' :
				$passed = is_int ( $value );
				break;
			case 'string' :
				$passed = is_string ( $value );
				break;
			case 'bool' :
				$passed = is_bool ( $value );
				break;
			case 'array' :
				$passed = is_array ( $value );
				break;
			case 'double' :
				$passed = is_double ( $value );
				break;
			case 'float' :
				$passed = is_float ( $value );
				break;
			default :
				$passed = self::checkType ( $value, $expected, false );
				break;
		}
		
		if ($passed)
			return true;
		
		if (! $passed && $exception == true)
			throw new IllegalArgumentException ( $argumentPos, $expected, $value );
		else
			return false;
	}
	
	public static function compare($o1, $o2) {
		if ($o1 instanceof Comparable && $o2 instanceof Comparable) {
			return $o1->compareTo ( $o2 );
		} else
			throw new IllegalArgumentException ( "Can compare only Comparable objects" );
	}
	
	public function __toString() {
		return parent::__toString ();
	}
}

?>