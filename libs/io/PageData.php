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

namespace dottedBytes\libs\io;

// no direct access
use dottedBytes\libs\errorHandling\CmsException;

use dottedBytes\libs\html\toolbar\ToolbarButton;

use dottedBytes\libs\errorHandling\ErrorToException;

use dottedBytes\libs\html\toolbar\Toolbar;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\pageBuilder\PageBuilder;

use dottedBytes\libs\configuration\Configuration;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

PageData::init ();
class PageData {
	
	private static $metadata = array ();
	
	private static $breadcrubs = null;
	/**
	 * Current element position in template
	 *
	 * @var string
	 */
	private static $screenPosition = false;
	
	/**
	 * @var Toolbar
	 */
	private static $toolbar = null;
	
	/**
	 * @var Toolbar
	 */
	private static $smallToolbar = null;
	
	private static $siteTitle = null;
	
	private static $sefPath = array ();
	
	/**
	 * Html to insert between the html head tags
	 * @var array
	 */
	private static $htmlHeaders = array ();
	
	/**
	 * Get site title
	 *
	 * @return string
	 */
	
	public static function init() {
		self::setMetadata ( 'robots', 'index,follow' );
		self::setMetadata ( 'generator', self::getSiteTitle () . ' - Copyright (C) 2008 - ' . date ( 'o' ) );
		self::setMetadata ( 'keywords', Configuration::getValue ( 'system.site.keywords' ) );
		
		$description = Configuration::getValue ( 'system.site.description' );
		if ($description == '')
			$description = self::getSiteTitle ();
		self::setMetadata ( 'description', $description );
	}
	
	public static function getSiteTitle() {
		if (self::$siteTitle == null)
			self::$siteTitle = Configuration::getValue ( 'system.site.name' );
		
		return self::$siteTitle;
	}
	
	public static function setSiteTitle($title) {
		self::$siteTitle = $title;
	}
	
	public static function addHeader($header) {
		return self::$htmlHeaders [sha1 ( $header )] = $header;
	}
	
	public static function getHeaders() {
		return self::$htmlHeaders;
	}
	
	public static function setMetadata($name, $value) {
		$name = strtolower ( $name );
		self::$metadata [$name] = $value;
	}
	
	public static function getMetadata($name = null) {
		if ($name == null)
			return self::$metadata;
		$name = strtolower ( $name );
		if (array_key_exists ( $name, self::$metadata ))
			return self::$metadata [$name];
		return null;
	}
	
	public static function sendPage($renderedPage, $mimeType, $charset) {
		if (strpos ( $_SERVER ['HTTP_ACCEPT_ENCODING'], 'gzip' ) !== false && CMS_DEBUGMODE != true) {
			ob_start ( "ob_gzhandler" );
			header ( 'Content-Encoding: gzip' );
		} else {
			ob_start ();
		}
		
		if ($charset != '')
			$charset = ";charset=$charset";
		
		header ( 'Content-type: ' . $mimeType . $charset );
		header ( 'Cache-Control: max-age=21600' );
		
		echo $renderedPage;
		
		if (CMS_DEBUGMODE && $mimeType == 'text/html')
			echo self::getDebugBenchmark ();
		
		ob_end_flush ();
	}
	
	public static function JSMessage($text) {
		$result = html_entity_decode ( $text, ENT_QUOTES, 'UTF-8' );
		$result = str_replace ( '"', '\"', $result );
		$result = str_replace ( "'", '\\\'', $result );
		return $result;
	}
	
	/**
	 * Clear the breadcrubs
	 */
	public static function clearBreadcrubs() {
		self::$breadcrubs = array ();
		self::$breadcrubs [] = array (PageData::getSiteTitle (), BASEURL . '/index.php' );
	}
	
	/**
	 * Add a link to the breadcrubs
	 * @param string $label
	 * @param string $link
	 * @return string
	 */
	public static function addToBreadcrubs($label, $link = '') {
		if (self::$breadcrubs == null)
			self::clearBreadcrubs ();
		
		if ($link == '')
			$link = '#';
		else {
			if (substr ( $link, 0, 7 ) != 'http://')
				$link = BASEURL . '/' . $link;
		}
		self::$breadcrubs [] = array ($label, $link );
		return true;
	}
	
	public static function getBreadcrubs() {
		if (self::$breadcrubs == null)
			self::clearBreadcrubs ();
		
		return self::$breadcrubs;
	}
	
	/**
	 * Clear the toolbar
	 */
	public static function clearToolbar() {
		self::$toolbar = new Toolbar ();
	}
	
	public static function clearSmallToolbar() {
		self::$smallToolbar = new Toolbar ( Toolbar::ICON_SMALL );
	}
	
	/**
	 * Add a ToolbarButton to the Toolbar
	 * @param ToolbarButton $item
	 */
	public static function addToolbarButton(ToolbarButton $item) {
		if (self::$toolbar == null)
			self::clearToolbar ();
		
		self::$toolbar->addItem ( $item );
	}
	
	public static function addSmallToolbarButton(ToolbarButton $item) {
		if (self::$smallToolbar == null)
			self::clearSmallToolbar ();
		
		self::$smallToolbar->addItem ( $item );
	}
	
	/**
	 * Return the main toolbar
	 * @return Toolbar
	 */
	public static function getToolbar() {
		if (self::$toolbar == null)
			self::clearToolbar ();
		
		return self::$toolbar;
	}
	
	/**
	 * Return the small toolbar
	 * @return Toolbar
	 */
	public static function getSmallToolbar() {
		if (self::$smallToolbar == null)
			self::clearSmallToolbar ();
		
		return self::$smallToolbar;
	}
	
	/**
	 * @return string $screenPosition
	 */
	public static function getScreenPosition() {
		return self::$screenPosition;
	}
	
	/**
	 * @param string the screenPosition to set
	 */
	public static function setScreenPosition($screenPosition) {
		self::$screenPosition = $screenPosition;
	}
	
	public static function getPageUrl() {
		$pageURL = (isset ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] == 'on') ? 'https://' : 'http://';
		$pageURL .= $_SERVER ['SERVER_PORT'] != '80' ? $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"] : $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'];
		return $pageURL;
	}
	
	/**
	 * Replace url in the page with Search Engine Friendly using Url rewriting engine
	 *
	 * @param string $search Url to replace
	 * @param array $replaceValues array(value1=>id1,value2=>0,value3=>id3) : http://...../id1/value1/value2/id3/value3/
	 * @return string the rewrited url
	 */
	public static function setSefReplaceRule($search, $replaceValues = array(), $extension = REWRITE_EXTENSION) {
		if (! REWRITE_ENABLE)
			return $search;
		
		$search = (substr ( $search, 0, 7 ) != 'http://') ? BASEURL . '/' . $search : $search;
		$rewrite = (substr ( $search, 0, 7 ) != 'http://') ? BASEURL : substr ( $search, 0, strpos ( $search, 'index.php' ) );
		$remains = count ( $replaceValues ) - 1;
		foreach ( $replaceValues as $value => $id ) {
			
			if ($id !== 0) {
				$rewrite .= $id . REWRITE_SYMBOL;
			}
			$value = self::getSefCleanText ( $value );
			
			$rewrite .= $value;
			if ($remains == 0) {
				if (substr ( $rewrite, - 1, 1 ) != REWRITE_SYMBOL)
					$rewrite .= '.' . $extension;
				else
					$rewrite = substr ( $rewrite, 0, strlen ( $rewrite ) - 1 );
			} else {
				$rewrite .= REWRITE_SYMBOL;
			}
			
			$remains --;
		}
		self::$sefPath [$search] = $rewrite;
		return $rewrite;
	}
	
	public static function getSefCleanText($text) {
		$text = html_entity_decode ( $text, ENT_QUOTES, 'UTF-8' );
		$text = String::wordSplit ( $text, 0, 64 );
		$text = preg_replace ( array ('/[^a-zA-Z0-9- ]+/', '/[ ]+/' ), array ('', '-' ), $text );
		return strtolower ( $text );
	}
	
	public static function getRewriteRules() {
		return self::$sefPath;
	}
	
	/**
	 * Get filtered querystring params
	 *
	 * @param string $name
	 * name of the param
	 *
	 * @param string[optional] $default
	 *
	 * @param string[optional] $method
	 *
	 * @param string[optional] $filter
	 *
	 * @return mixed
	 */
	public static function getParam($name, $default = false, $method = false, $filterType = false) {
		
		if (is_string ( $default ))
			$default = stripslashes ( $default );
		
		//Get the value with the selected method
		if ($method == false) {
			$value = (array_key_exists ( $name, $_REQUEST )) ? $_REQUEST [$name] : $default;
		} elseif (is_array ( $method )) {
			$value = (array_key_exists ( $name, $method )) ? $method [$name] : $default;
		} else {
			switch ($method) {
				case "get" :
					$value = (array_key_exists ( $name, $_GET )) ? $_GET [$name] : $default;
					break;
				case "post" :
					$value = (array_key_exists ( $name, $_POST )) ? $_POST [$name] : $default;
					break;
				case "cookie" :
					$value = (array_key_exists ( $name, $_COOKIE )) ? $_COOKIE [$name] : $default;
					break;
				case "session" :
					$value = (array_key_exists ( $name, $_SESSION )) ? $_SESSION [$name] : $default;
					break;
				case "server" :
					$value = (array_key_exists ( $name, $_SERVER )) ? $_SERVER [$name] : $default;
					break;
				case "env" :
					$value = (array_key_exists ( $name, $_ENV )) ? $_ENV [$name] : $default;
					break;
				case "files" :
					$value = (array_key_exists ( $name, $_FILES )) ? $_FILES [$name] : $default;
					return $value;
					break;
				default :
					$value = (array_key_exists ( $name, $_REQUEST )) ? $_REQUEST [$name] : $default;
					break;
			}
		}
		//Filter the input value
		if (is_string ( $value )) {
			
			if (strlen ( $value ) < 1)
				return $default;
			
			$value = stripslashes ( $value );
			switch ($filterType) {
				case 'html' :
					$value = htmlentities ( $value, ENT_QUOTES, 'UTF-8' );
					break;
				case 'sql' :
					$value = Filter::escapeSQL ( $value );
					break;
				case 'pure' :
					$value = stripslashes ( $value );
					break;
				default :
					$value = htmlentities ( $value, ENT_QUOTES, 'UTF-8' );
					if (! ini_get ( "magic_quotes_gpc" )) {
						$value = addslashes ( $value );
					}
					break;
			}
		}
		
		return $value;
	}
	
	public static function sendError($message = '', $details = '') {
		$message = ($message == '') ? '' : 'message=' . urlencode ( $message );
		$details = ($details == '') ? '' : '&details=' . urlencode ( $details );
		if (CMS_DEBUGMODE) {
			$url = BASEURL . '/error.php?' . $message . $details;
		} else {
			$url = BASEURL . '/error.php?' . $message;
		}
		header ( "Location: " . " $url" );
		return true;
	}
	
	public static function sendException(CmsException $e) {
		die(PageBuilder::getExceptionPage($e)->getData());
	}
	
	/**
	 * Return to previous page with a message
	 *
	 * @param string $msg
	 * @return bool
	 */
	public static function back($msg = NULL) {
		
		$user = UserUtils::getCurrentUser ();
		
		if (! is_null ( $msg )) {
			$user->sendMessage ( $msg );
		}
		if (headers_sent ()) {
			echo "<script type=\"text/javascript\">document.location.href='{$user->getReferer()}';</script>\n";
		} else {
			header ( "Location: {$user->getReferer()}" );
		}
		exit ();
	}
	
	/**
	 * Redirect with to a specified page with a message
	 *
	 * @param string $url
	 * @param string $msg
	 * @return bool
	 */
	public static function redirect($url = '', $msg = NULL) {
		$user = UserUtils::getCurrentUser ();
		if (! is_null ( $msg )) {
			$user->sendMessage ( $msg );
		}
		
		if (REWRITE_ENABLE) {
			$sefRules = PageData::getRewriteRules ();
			if (array_key_exists ( $url, $sefRules ))
				$url = $sefRules [$url];
		}
		if (headers_sent ()) {
			echo "<script type=\"text/javascript\">document.location.href='" . $url . "';</script>\n";
		} else {
			header ( "Location: " . " $url" );
		}
		exit ();
	}
	
	public static function getDebugBenchmark() {
		$return = "Execution time: " . sprintf ( "%.3f", ((microtime ( 1 ) - _DEBUG_START_TIME) * 1000) ) . " ms | ";
		$usedMemory = FileUtils::humanSize ( memory_get_usage () - _DEBUG_START_MEMORY, 1 );
		$return .= "Used memory: " . $usedMemory;
		return $return;
	}
}

?>