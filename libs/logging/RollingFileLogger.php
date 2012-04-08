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

use dottedBytes\libs\users\UserUtils;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class RollingFileLogger extends Logger {
	
	const HOUR_LOG = 1;
	const DAY_LOG = 2;
	const MOUNTH_LOG = 4;
	const YEAR_LOG = 8;
	
	public function __construct($resource, $options) {
		parent::__construct ( $resource, $options );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see dottedBytes\libs\logging.Logger::writeLog()
	 */
	public function writeLog($values) {
		//Create the log filename
		$logPath = $this->getLogPath ();
		foreach ( $values as $val ) {
			if (is_object ( $val )) {
				$val = (method_exists ( $val, '__toString' )) ? strval ( $val ) : get_class ( $val );
			}
			$tempValues [] = str_replace ( "\t", " ", $val );
		}
		$values = $tempValues;
		
		$headers = "";
		
		//Write headers if is new file
		if (! file_exists ( $logPath )) {
			$logfile = fopen ( $logPath, "a+" );
			$headers .= "#<?php exit; ?>\n";
			$headers .= "# This file was automatically created on the site\n";
			$headers .= "# " . BASEURL . "\n";
			$headers .= "# at " . date ( "r" ) . "\n";
			$headers .= "#\n";
			
			if (array_key_exists ( 'comments', $this->options ))
				$headers .= "# " . str_replace ( "\n", "\n# ", $this->options ['comments'] ) . "\n#\n";
			
			if (! fwrite ( $logfile, $headers )) {
				return false;
			}
		} else {
			if (! is_writable ( $logPath ))
				return false;
			$logfile = fopen ( $logPath, "a" );
		}
		
		$old = umask ( 033 );
		//Write log data in file
		fwrite ( $logfile, implode ( "\t", $values ) . "\n" );
		umask ( $old );
		
		fclose ( $logfile );
		return true;
	}
	
	private function getLogPath() {
		if (! array_key_exists ( 'rollTime', $this->options ))
			$this->options ['rollTime'] = self::MOUNTH_LOG;
		
		$logPath = LOGDIR . '/' . $this->resource;
		
		switch ($this->options ['rollTime']) {
			case self::HOUR_LOG :
				$logPath .= '_' . date ( "Y", time () );
				$logPath .= '-' . date ( "m", time () );
				$logPath .= '-' . date ( "d", time () );
				$logPath .= '-' . date ( "H", time () );
				break;
			case self::DAY_LOG :
				$logPath .= '_' . date ( "Y", time () );
				$logPath .= '-' . date ( "m", time () );
				$logPath .= '-' . date ( "d", time () );
				break;
			case self::MOUNTH_LOG :
				$logPath .= '_' . date ( "Y", time () );
				$logPath .= '-' . date ( "m", time () );
				break;
			case self::YEAR_LOG :
				$logPath .= '_' . date ( "Y", time () );
				break;
		}
		
		$logPath .= ".php";
		return $logPath;
	}
}