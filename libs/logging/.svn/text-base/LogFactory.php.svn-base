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

class LogFactory {
	
	private static $rollingLoggers = array ();
	
	/**
	 * Return a logger
	 * @param string $resource the location to create the log, depends of the logger type (eg. file path for file loggers)
	 * @param string $loggerType specify the logger type to return (default is dailyLog) 
	 * @param array $options the options to pass to the logger
	 * @return Logger
	 */
	public static function getLogger($resource, $type = 'rollingLog', $options = array()) {
		switch ($type) {
			case 'rollingLog' :
				if (! array_key_exists ( $resource, self::$rollingLoggers ))
					self::$rollingLoggers [$resource] = new RollingFileLogger( $resource, $options );
				return self::$rollingLoggers [$resource];
				break;
		}
		return null;
	}
}

?>