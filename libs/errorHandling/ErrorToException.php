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

namespace dottedBytes\libs\errorHandling;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ErrorToException extends CmsException {
	
	private $fatalErrors = array (E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR );
	
	private $error, $errno, $errorFile, $errorLine, $exceptionTrace;
	
	public function __construct($in_errstr, $in_errno, $in_errfile, $in_errline, $backtrace = '') {
		$this->error = $in_errstr;
		$this->errno = $in_errno;
		$this->errorFile = $in_errfile;
		$this->errorLine = $in_errline;
		$this->exceptionTrace = ($backtrace == '') ? debug_backtrace () : $backtrace;
		parent::__construct ( $this->error, 0 );
	}
	
	public function isFatal() {
		return in_array ( $this->errno, $this->fatalErrors );
	}
	
	public function getDetails() {
		return $this->error;
	}
	
	public function getType() {
		return $this->resolveErrorType ( $this->errno );
	}
	
	public function getExceptionFile() {
		return $this->errorFile;
	}
	
	public function getExceptionLine() {
		return $this->errorLine;
	}
	
	public function getExceptionTrace() {
		$trace = $this->exceptionTrace;
		array_shift ( $trace );
		return $trace;
	}
	
	public function getExceptionTraceAsString() {
		$traceString = explode ( "\n", $this->getTraceAsString () );
		array_shift ( $traceString );
		return implode ( "\n", $traceString );
	}
	
	/**
	 * Returns the error type corresponding of the errorcode
	 *
	 * @param int $errno
	 * @return string
	 */
	public static function resolveErrorType($errno) {
		$errortype = array (E_ERROR => "Fatal Error", E_WARNING => "Runtime Warning", E_PARSE => "Parsing Error", E_NOTICE => "Notice", E_CORE_ERROR => "Core Error", E_CORE_WARNING => "Core Warning", E_COMPILE_ERROR => "Compile Error", E_COMPILE_WARNING => "Compile Warning", E_USER_ERROR => "User Error", E_USER_WARNING => "User Warning", E_USER_NOTICE => "User Notice", E_STRICT => "Runtime Notice", E_RECOVERABLE_ERROR => "Catchable fatal error", E_ALL => "Error" );
		if (array_key_exists ( $errno, $errortype ))
			return $errortype [$errno];
		
		return 'Error';
	}
}
?>