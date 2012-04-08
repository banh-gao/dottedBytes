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

namespace dottedBytes\libs\logging;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class DebugLog {
	
	private static $logger;
	
	const DEBUG = 1;
	const INFO = 2;
	const WARNING = 3;
	const ERROR = 4;
	
	private static function init() {
		if (self::$logger == null)
			self::$logger = LogFactory::getLogger ( "debug" );
	}
	
	public static function d($message) {
		return self::log ( self::DEBUG, $message );
	}
	
	public static function i($message) {
		return self::log ( self::INFO, $message );
	}
	
	public static function w($message) {
		return self::log ( self::WARNING, $message );
	}
	
	public static function e($message) {
		return self::log ( self::ERROR, $message );
	}
	
	private static function log($level, $message) {
		self::init ();
		switch ($level) {
			case self::DEBUG :
				return self::$logger->log ( array ("DEBUG", $message ) );
				break;
			case self::INFO :
				return self::$logger->log ( array ("INFO", $message ) );
				break;
			case self::WARNING :
				return self::$logger->log ( array ("WARNING", $message ) );
				break;
			case self::ERROR :
				return self::$logger->log ( array ("ERROR", $message ) );
				break;
		}
	}
}

?>