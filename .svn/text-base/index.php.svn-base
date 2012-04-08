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

namespace dottedBytes;

use dottedBytes\libs\pageBuilder\template\Position;

use dottedBytes\libs\errorHandling\ExceptionHandler;
use dottedBytes\libs\io\FileUtils;
use dottedBytes\libs\io\PageData;
use dottedBytes\libs\configuration\Configuration;
use dottedBytes\libs\users\UserUtils;
use dottedBytes\libs\pageBuilder\PageBuilder;
use Exception;

define ( '_DEBUG_START_TIME', microtime ( 1 ) );
define ( '_DEBUG_START_MEMORY', memory_get_usage () );

define ( 'VALID_REQUEST', 1 );

if (session_id () == '')
	session_start ();

	//Load basic configuration
require_once (dirname ( __FILE__ ) . '/configuration.php');

//Load file utils to automatic include namespace refered files
require_once (dirname ( __FILE__ ) . '/libs/functions.php');

spl_autoload_register ( 'dottedBytes_autoload' );

try {
	//Error handling
	register_shutdown_function ( array ('dottedBytes\libs\errorHandling\ExceptionHandler', 'handleFatal' ) );
	set_exception_handler ( array ('dottedBytes\libs\errorHandling\ExceptionHandler', 'handler' ) );
	set_error_handler ( array ('dottedBytes\libs\errorHandling\ExceptionHandler', 'errorAdapter' ) );
	
	//Load user page request
	UserUtils::getCurrentUser ()->logConnection ();
	
	//Get page builder
	$builder = PageBuilder::getInstance ();
	
	//Set the template
	$template = Configuration::getValue ( 'system.site.template' );
	$builder->setTemplateName ( $template );
	
	//Generate page
		$renderedPage = $builder->generatePage ();
	
	//Update current user info for the current request
	UserUtils::updateCurrentUserInfo ();
	
	//Send page to the client
	PageData::sendPage ( $renderedPage, $builder->getMimeType (), $builder->getCharset () );
} catch ( Exception $e1 ) {
	//Catch all uncaught exceptions
	try {
		ExceptionHandler::handler ( $e1 );
	} catch ( Exception $_ ) {
		header ( 'Location: ' . BASEURL . '/error.php' );
	}
}
?>
