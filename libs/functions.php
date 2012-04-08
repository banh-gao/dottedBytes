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

use dottedBytes\libs\html\form\Form;
use dottedBytes\libs\errorHandling\ExceptionHandler;

use dottedBytes\libs\io\FileUtils;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

use dottedBytes\libs\errorHandling\ErrorToException;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\logging\ErrorLog;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\pageBuilder\PageBuilder;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

function dottedBytes_autoload($class) {
	if (substr ( $class, 0, 11 ) != 'dottedBytes')
		return;
	$class = str_replace ( 'dottedBytes\\', '', $class );
	$path = dirname ( dirname ( __FILE__ ) ) . '/' . str_replace ( '\\', '/', $class ) . '.php';
	if (file_exists ( $path ))
		require_once ($path);
}

function debug($var, $die = false, $return = false, $html = true) {
	
	$out = "";
	
	if (is_object ( $var ) && method_exists ( $var, '__toString' )) {
		$out .= $var->__toString ();
	} else {
		$oldOB = ob_get_clean ();
		
		ob_start ();
		var_dump ( $var );
		$out .= ob_get_contents ();
		
		ob_clean ();
		if ($oldOB !== false)
			echo $oldOB;
	}
	
	if ($html) {
		$value = "<pre>\n" . $out . "\n</pre>\n";
	} else {
		$value = strip_tags ( $out );
	}
	
	if (! $return) {
		echo $value;
	}
	
	if ($die)
		exit ();
	
	return $value;
}
?>