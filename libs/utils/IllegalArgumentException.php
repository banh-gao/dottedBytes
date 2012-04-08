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

use dottedBytes\libs\errorHandling\CmsException;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class IllegalArgumentException extends CmsException {
	
	private $backtrace;
	
	public function __construct($argPos, $expected, $value) {
		
		$this->backtrace = debug_backtrace ();
		
		$caller = $this->backtrace [2] ['function'];
		$valueType = (gettype ( $value ) == "object") ? get_class ( $value ) : gettype ( $value );
		
		$description = "Argument " . $argPos . " passed to " . $caller . "() must be of type " . $expected . ", " . $valueType . " given.";
		parent::__construct ( $description );
	}
	
	public function getExceptionFile() {
		return $this->backtrace [2] ['file'];
	}
	
	public function getExceptionLine() {
		return $this->backtrace [2] ['line'];
	}
	
	public function getExceptionTrace() {
		$trace = $this->getTrace ();
		//remove the trace of the function that throw the exception
		array_shift ( $trace );
		$this->exceptionTrace = $trace;
	}
	
	public function getExceptionTraceAsString() {
		$traceString = explode ( "\n", $this->getTraceAsString () );
		array_shift ( $traceString );
		return implode ( "\n", $traceString );
	}
}

?>