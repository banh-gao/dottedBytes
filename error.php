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
header ( "HTTP/1.1 500 Internal Server Error" );
echo '<title>Interal Server Error</title>';
echo '<h1>Internal Server Error</h1>';
echo '<h3><p>';
if (array_key_exists ( "message", $_REQUEST ) && $_REQUEST ["message"] != '') {
	$message = htmlentities ( $_REQUEST ["message"], ENT_QUOTES, 'UTF-8' );
	echo $message;
} else {
	echo "We are sorry but an error was occurred by processing your request.<br/>\n";
	echo "Please retry again later.";
}
echo '</p></h3>';
echo '<a href="index.php">[';
echo "Return to Homepage";
echo ']</a>';
if (array_key_exists ( "details", $_REQUEST ) && $_REQUEST ["details"] != '') {
	$details = htmlentities ( $_REQUEST ["details"], ENT_QUOTES, 'UTF-8' );
	echo '<br/><br/><br/><br/><p>';
	echo '<div style="border:1px dashed #000000;font-size:12px;padding:5px;background-color:#efefef;height:110px;overflow:auto;">';
	echo $details;
	echo '</div></p>';
}
?>
