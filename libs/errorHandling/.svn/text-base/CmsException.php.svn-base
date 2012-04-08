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

use dottedBytes\libs\logging\ErrorLog;

use dottedBytes\libs\logging\LogFactory;

use Exception;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class CmsException extends Exception {
	
	private $type;
	private $logInfo;
	private $exceptionTrace;
	private $exceptionTraceAsString;
	private $title;
	
	public function __construct($logInfo = '', $code = 0, $message = '') {
		parent::__construct ( ( string ) $message, ( int ) $code );
		
		$this->logInfo = $logInfo;
		$this->title = (defined ( '_SITE_ERROR_ERROR' )) ? _SITE_ERROR_ERROR : 'Error';
		
		if ($message == '')
			$this->message = (defined ( '_SITE_ERROR_ERROR_EXPLAIN' ) ? _SITE_ERROR_ERROR_EXPLAIN : '');
		
		if ($this->type != 'ErrorToException') {
			$trace = $this->getExceptionTrace ();
			$this->file = (array_key_exists ( 'file', $trace [0] )) ? $trace [0] ['file'] : 'Unknown';
			$this->line = (array_key_exists ( 'line', $trace [0] )) ? $trace [0] ['line'] : 0;
		}
	}
	
	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * @return string
	 */
	public function getType() {
		return get_class ( $this );
	}
	
	public function getDetails() {
		return $this->logInfo;
	}
	
	public function isFatal() {
		return false;
	}
	
	public function getExceptionMessage() {
		return $this->message;
	}
	
	public function getExceptionFile() {
		return $this->getFile ();
	}
	
	public function getExceptionLine() {
		return $this->getLine ();
	}
	
	public function getExceptionTrace() {
		return $this->getTrace ();
	}
	
	public function getExceptionTraceAsString() {
		return $this->getTraceAsString ();
	}
	
	public function useLog() {
		return true;
	}

}
?>