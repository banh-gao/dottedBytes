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

// no direct access
use dottedBytes\libs\errorHandling\ErrorToException;
use dottedBytes\libs\io\PageData;
use dottedBytes\libs\errorHandling\ExceptionHandler;
use dottedBytes\libs\logging\ErrorLog;
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );
	
/* 
 * THIS FILE CONTAINS THE BASIC CONFIGURATION FOR THE WEBSITE 
 */

//Site paths
define ( "BASEPATH", dirname ( $_SERVER ['SCRIPT_FILENAME'] ) );
define ( "BASEURL", dirname ( "http://" . $_SERVER ['HTTP_HOST'] . $_SERVER ['PHP_SELF'] ) );

//Database configuration
define ( "CMS_DB_DSN", 'mysql:host=localhost;dbname=dottedBytes;port=3306' );
define ( "CMS_DB_USERNAME", 'root' );
define ( "CMS_DB_PASSWORD", 'password' );
define ( "CMS_DB_PREFIX", '' );

//Url rewriting options
define ( "REWRITE_ENABLE", true );
define ( "REWRITE_SYMBOL", "/" );
define ( "REWRITE_EXTENSION", "html" );

//Error handling
define ( "LOGDIR", BASEPATH . '/logs' );
define ( "CMS_HIDDENERRORS", serialize ( array () ) );
define ( "CMS_DEBUGMODE", true );
error_reporting ( E_ALL );

//Locale settings
date_default_timezone_set ( 'Europe/Rome' );
setlocale ( LC_ALL, "it_IT.utf8" );
?>