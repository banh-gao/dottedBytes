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

use ReflectionClass;
use ArrayIterator;

abstract class Enum {
	
	public static function getConstants() {
		$c = new ReflectionClass ( get_called_class () );
		return $c->getConstants ();
	}
	
	public static function values() {
		return array_values ( self::getConstants () );
	}
	
	public static function names() {
		return array_keys ( self::getConstants () );
	}
	
	public static function count() {
		return count ( self::getConstants () );
	}
	
	public static function valueOf($name) {
		foreach ( self::getConstants() as $n => $v ) {
			if ($n == $name)
				return $v;
		}
		return $name;
	}
	
	public static function nameOf($value) {
		foreach ( self::getConstants() as $n => $v ) {
			if ($v == $value)
				return $n;
		}
		return $value;
	}
}

?>