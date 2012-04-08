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

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\logging\ErrorLog;

use Exception;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ExceptionHandler {
	
	public static function handleFatal() {
		$error = error_get_last ();
		
		if ($error !== NULL) {
			ob_clean ();
			PageData::sendException ( new ErrorToException ( $error ['message'], $error ['type'], $error ['file'], $error ['line'] ) );
		}
	}
	
	public static function errorAdapter($in_errno, $in_errstr, $in_errfile, $in_errline) {
		//Ignore specified errors
		if (in_array ( $in_errno, unserialize ( CMS_HIDDENERRORS ) ))
			return true;
		throw new ErrorToException ( $in_errstr, $in_errno, $in_errfile, $in_errline );
		return true;
	}
	
	public static function handler(Exception $in_exeption, $return = false) {
		$code = $in_exeption->getCode ();
		if ($in_exeption instanceof CmsException) {
			$file = $in_exeption->getExceptionFile ();
			$line = $in_exeption->getExceptionLine ();
			$type = $in_exeption->getType ();
			$message = $in_exeption->getExceptionMessage ();
			$details = $in_exeption->getDetails ();
			$trace = $in_exeption->getExceptionTrace ();
			$traceString = $in_exeption->getExceptionTraceAsString ();
			$fatal = $in_exeption->isFatal ();
			$logException = $in_exeption->useLog ();
		} else {
			$trace = $in_exeption->getTrace ();
			$file = $trace [0] ['file'];
			$line = $trace [0] ['line'];
			$type = get_class ( $in_exeption );
			$message = '';
			$details = $in_exeption->getMessage ();
			$trace = $in_exeption->getTrace ();
			$traceString = $in_exeption->getTraceAsString ();
			$fatal = false;
			$logException = true;
		}
		
		$codeStr = ($code == 0) ? '' : ' ' . $code;
		
		if ($logException) {
			$logMessage = str_replace ( "\t", " ", $details );
			ErrorLog::log ( $type . ':', $logMessage, $file, $line );
		}
		
		$details = "<b>" . $type . $codeStr . ": " . $details . "</b><br/>\n";
		$details .= '<b>Exception was thrown in ' . $file . ' at line ' . $line . "</b><br/>\n";
		$details .= 'Detailed stack trace:<br/>' . "\n";
		$details .= nl2br ( $traceString );
		
		if ($return)
			return $details;
		
		PageData::sendException ( $in_exeption );
	}
	
	public function __toString() {
		return parent::__toString ();
	}
}

?>