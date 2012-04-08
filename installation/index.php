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

define ( 'VALID_REQUEST', 1 );

//Site paths
define ( "BASEPATH", dirname ( $_SERVER ['SCRIPT_FILENAME'] ) );
define ( "BASEURL", dirname ( "http://" . $_SERVER ['HTTP_HOST'] . $_SERVER ['PHP_SELF'] ) );

if (session_id () == '')
	session_start ();

require_once './Installation.php';
	
try {
	Installation::loadNextPage();
} catch ( Exception $e ) {
	//Catch all uncaught exceptions
	header ( 'Location: ' . BASEURL . '/pages/error.php?cause='+$e->getMessage() );
}