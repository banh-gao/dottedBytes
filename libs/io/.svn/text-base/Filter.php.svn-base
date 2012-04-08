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

namespace dottedBytes\libs\io;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

/**
 * Check string formats and filter dangerous string
 *
 */
class Filter {
	
	/**
	 * Escape dangerous characters from string like html markup and percents symbol
	 *
	 * @param string $input
	 * @param boolean[optional] $markup
	 * @param boolean[optional] $removePct
	 * @return string
	 */
	public static function escapeString($input, $markup = false, $removePct = false) {
		$str = ereg_replace ( '(\')', '\\\1', $input );
		
		if ($removePct)
			$str = ereg_replace ( '(%)', '\\\1', $str );
		
		if ($markup)
			$str = htmlspecialchars ( $str, ENT_NOQUOTES, 'UTF-8' );
		
		return $str;
	}
	
	/**
	 * Escape dangerous characters from string used in SQL queries
	 *
	 * @param string $input
	 * @return string An escaped string
	 */
	public static function escapeSQL($input) {
		$str = ereg_replace ( '(%)', '\\\1', $input );
		$str = htmlentities ( $str , ENT_COMPAT , 'UTF-8');
		return $str;
	}
	
	/**
	 * Check for valid email format
	 *
	 * @param string $input
	 * @return bool
	 */
	public static function email($input) {
		if (preg_match ( '/^[_A-z0-9-]+(\.[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,})$/', $input ) === 1) {
			return true;
		}
		return false;
	}
	
	/**
	 * Check the length of a string
	 *
	 * @param string $input
	 * @param int $minlength
	 * @param int $maxlength
	 * @return bool
	 */
	public static function length($input, $minlength = false, $maxlength = false) {
		
		$length = strlen ( $input );
		if ($minlength <= $length || $minlength == false) {
			if ($length <= $maxlength || $maxlength == false)
				return true;
		}
		return false;
	}
	
	/**
	 * Check for valid IP address
	 *
	 * @param string $input
	 * @param int $IPversion
	 * @return bool
	 */
	public static function IP($input, $IPversion = false) {
		if ($IPversion === false) {
			if (filter_var ( $input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ))
				return true;
			else
				return false;
		}
		
		if ($IPversion == 6) {
			if (filter_var ( $input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) !== false)
				return true;
		} else {
			if (filter_var ( $input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) !== false)
				return true;
		}
		return false;
	}
	
	/**
	 * Check for valid url
	 *
	 * @param string $input
	 * @return bool
	 */
	public static function url($input) {
		
		if (strlen ( $input ) < 1) {
			return false;
		}
		$input = (substr ( $input, 0, 7 ) != 'http://') ? 'http://' . $input : $input;
		
		$urlregex = '^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$';
		if (eregi ( $urlregex, $input ))
			return true;
		
		return false;
	}
	
	/**
	 * Replace multiple spaces inside a string with a single space and remove start and end spaces
	 *
	 * @param string $input
	 * @return string
	 */
	public static function trimString($input) {
		$input = trim ( $input );
		return preg_replace ( "/[ ]{2,}/", " ", $input );
	}
}

?>