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

use dottedBytes\libs\logging\LogFactory;
use dottedBytes\libs\io\IOException;
use dottedBytes\libs\utils\String;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class FileUtils {
	
	private static $BASEPACKAGE = null;
	
	public static function getBasePackage() {
		if (self::$BASEPACKAGE == null)
			self::$BASEPACKAGE = self::getPackageNotation ( BASEPATH );
		return self::$BASEPACKAGE;
	}
	
	/**
	 * Remove the base path from a path
	 *
	 * @param string $path
	 * @return string
	 */
	public static function stripBasePath($path) {
		if (($start = strpos ( $path, BASEPATH )) !== false)
			return substr ( $path, $start + strlen ( BASEPATH ) );
		return $path;
	}
	
	/**
	 * Remove the base url from a url
	 *
	 * @param string $url
	 * @return string
	 */
	public static function stripBaseUrl($url) {
		if (($start = strpos ( $url, BASEURL )) !== false)
			return substr ( $url, $start + strlen ( BASEURL ) );
		return $url;
	}
	
	/**
	 * Remove the base package from a package
	 *
	 * @param string $package
	 * @return string
	 */
	public static function stripBasePackage($package) {
		if (($start = strpos ( $package, self::getBasePackage () . '.' )) !== false)
			return substr ( $package, $start + strlen ( self::getBasePackage () . '.' ) );
		return $package;
	}
	
	/**
	 * Convert from package notation to the real system path
	 *
	 * @param  string $package
	 * @return string
	 */
	public static function getRealPath($package) {
		//detect escaped dots
		$package = str_replace ( '\.', '#', $package );
		
		$dirs = explode ( '.', $package );
		
		if (count ( $dirs ) < 1) {
			$path = $package;
		} else {
			$path = '';
			$i = 0;
			while ( $i < count ( $dirs ) ) {
				$path .= '/' . $dirs [$i];
				$i ++;
			}
		}
		
		$path = str_replace ( '#', '.', $path );
		return $path;
	}
	
	public static function getPackageNotation($realPath) {
		$path = String::split ( $realPath, '/' );
		if (! is_dir ( $realPath )) {
			//Path is file
			$last = array_pop ( $path );
			$filename = substr ( $last, 0, strrpos ( $last, '.' ) );
			//If file is not php add file extension
			array_push ( $path, $filename );
		}
		
		return implode ( '.', $path );
	}
	
	/**
	 * Load a php file and throw an IOException if the file doesn't exist
	 *
	 * @param string $path
	 * @throws IOException
	 */
	public static function loadFile($path) {
		$path = BASEPATH . '/' . $path . '.php';
		if (file_exists ( $path ) && is_readable($path)) {
			require_once $path;
			return;
		}
		throw new IOException ( 'Cannot open file ' . $path );
	}
	
	private static function getLocalPath($package) {
		if (substr ( $package, - 1, 1 ) == '*')
			$package = substr ( $package, 0, strlen ( $package ) - 2 );
		
		//Absolute scope
		if ($package != '') {
			$path = BASEPATH . self::getRealPath ( $package );
			if (file_exists ( $path . '.php' ) || is_dir ( $path )) {
				return $path;
			}
		}
		
		//Relative scope
		$caller = debug_backtrace ();
		$caller = $caller [1] ['file'];
		$currentDir = dirname ( $caller );
		$path = $currentDir . self::getRealPath ( $package );
		
		if (file_exists ( $path . '.php' ) || is_dir ( $path ))
			return $path;
		return false;
	}
	
	/**
	 * Load all php files in specified directory
	 *
	 * @param string $dir
	 */
	private static function loadDir($dir) {
		$cwd = getcwd ();
		chdir ( $dir );
		foreach ( glob ( '*.php' ) as $file ) {
			
			if (file_exists ( $dir . '/' . $file )) {
				$loadPath = $dir . '/' . $file;
				if (! is_readable ( $loadPath ))
					throw new IOException ( 'Cannot load file ' . $loadPath . ': Read permission denied' );
				require_once ($loadPath);
			}
		
		}
		chdir ( $cwd );
	}
	
	/**
	 * Get the size of a file
	 *
	 * @param string $filename
	 * @return int
	 */
	public static function filesize($filename) {
		//Detect if the file is local or remote
		if (substr ( $filename, 0, 4 ) == 'http') { //Remote
			$headers = get_headers ( $filename, 1 );
			if ((! array_key_exists ( "Content-Length", $headers )) === false) {
				throw new IOException ( 'Cannot get size for remote file ' . $filename );
			}
			$filesize = $headers ["Content-Length"];
		} else { //Local
			$filesize = filesize ( $filename );
			if ($filesize === false) {
				throw new IOException ( 'Cannot get size for local file ' . $filename );
			}
		}
		return $filesize;
	}
	
	public static function copyUploaded($tmp_path, $target_path) {
		if (! file_exists ( $tmp_path ))
			return false;
		
		return move_uploaded_file ( $tmp_path, $target_path );
	}
	
	public static function getMaxUploadSize() {
		return min ( self::literalToByteSize ( ini_get ( 'post_max_size' ) ), self::literalToByteSize ( ini_get ( 'upload_max_filesize' ) ) );
	}
	
	public static function literalToByteSize($v) {
		$l = substr ( $v, - 1 );
		$ret = substr ( $v, 0, - 1 );
		switch (strtoupper ( $l )) {
			case 'P' :
				$ret *= 1000;
			case 'T' :
				$ret *= 1000;
			case 'G' :
				$ret *= 1000;
			case 'M' :
				$ret *= 1000;
			case 'K' :
				$ret *= 1000;
				break;
		}
		return $ret;
	}
	
	public static function getUploadError($errorCode) {
		switch ($errorCode) {
			case UPLOAD_ERR_INI_SIZE :
				$error = "Server configuration doesn't allow the file size";
				break;
			case UPLOAD_ERR_PARTIAL :
				$error = "The file was not transferred completly";
				break;
			case UPLOAD_ERR_NO_FILE :
				$error = "File to upload not found";
				break;
			case UPLOAD_ERR_FORM_SIZE :
				$error = "The size of the file is to large";
				break;
			case UPLOAD_ERR_NO_TMP_DIR :
				$error = "Temporary directory not found";
				break;
			case UPLOAD_ERR_CANT_WRITE :
				$error = "Writing of file in temporary directory failed";
				break;
			case UPLOAD_ERR_OK :
				$error = "";
				break;
			default :
				$error = "Some error occured while uploading the file";
				break;
		}
		return $error;
	}
	
	/**
	 * Check if a file exist
	 *
	 * @param string $filename
	 * @return boolean
	 */
	public static function file_exists($filename) {
		//Detect if the file is local or remote
		if (substr ( $filename, 0, 4 ) == 'http') { //Remote
			$fp = fopen ( $filename, 'r' );
			if ($fp === false) {
				fclose ( $fp );
				return false;
			}
			fclose ( $fp );
			return true;
		} else {
			if (file_exists ( $filename ) === false) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Read recursive the base directory
	 *
	 * @param string $baseDir
	 * @return array [path]=>filename
	 */
	public static function fileTree($baseDir, $pattern = '', $includeDir = false, $linear = true, $deepLevel = 0) {
		//Remove last slash if any
		if (substr ( $baseDir, - 1, 1 ) == '/')
			$baseDir = substr ( $baseDir, 0, strlen ( $baseDir ) - 1 );
		
		if (! is_dir ( $baseDir ))
			return array ();
		
		if ($pattern == '')
			$pattern = '*';
		
		$structure = array ();
		$cwd = getcwd ();
		chdir ( $baseDir );
		foreach ( glob ( $pattern ) as $handle ) {
			if (is_dir ( $baseDir . '/' . $handle ) && $deepLevel >= 0) { //Is subdir
				if ($linear) {
					if ($includeDir)
						$structure [$baseDir . '/' . $handle] = $handle;
					$structure = array_merge ( $structure, self::fileTree ( $baseDir . '/' . $handle, $pattern, $includeDir, $linear, $deepLevel -- ) );
				} else {
					if ($includeDir)
						$structure [$baseDir . '/' . $handle] = self::fileTree ( $baseDir . '/' . $handle, $pattern, $includeDir, $linear, $deepLevel -- );
				}
			} else { //Is file
				$structure [$baseDir . '/' . $handle] = $handle;
			}
		}
		ksort ( $structure );
		chdir ( $cwd );
		
		return $structure;
	}
	
	/**
	 * Delete the passed directory and all its content !! USE CAREFULLY !!
	 *
	 * @param string $baseDir
	 * @return boolean
	 */
	public static function delTree($baseDir) {
		if (! is_dir ( $baseDir ) || ! is_writable ( $baseDir ) || ! is_readable ( $baseDir ))
			throw new IOException ( 'Cannot accessing directory ' . $baseDir );
		
		$status = true;
		$handle = opendir ( $baseDir );
		while ( ($FolderOrFile = readdir ( $handle )) !== false ) {
			if ($FolderOrFile != "." && $FolderOrFile != "..") {
				if (is_dir ( $baseDir . '/' . $FolderOrFile )) {
					// recursive delete files into directories
					$status = self::delTree ( $baseDir . '/' . $FolderOrFile );
				} else {
					if (is_writable ( $baseDir )) {
						unlink ( $baseDir . '/' . $FolderOrFile );
					} else {
						$status = false;
					}
				}
			}
		}
		closedir ( $handle );
		
		if ($status == false)
			return false;
		
		//Delete empty base directory
		$rootDir = dirname ( $baseDir );
		if (is_writable ( $rootDir ) && is_readable ( $rootDir ) && $status) {
			rmdir ( $baseDir );
		}
		
		return true;
	}
	
	/**
	 * Copy the passed directory with all content
	 *
	 * @param string $sourceDir
	 * @param string $destDir
	 * @return boolean
	 */
	public static function copyDir($sourceDir, $destDir) {
		if (is_file ( $sourceDir )) {
			$c = copy ( $sourceDir, $destDir );
			return $c;
		}
		
		$pos = strrpos ( $sourceDir, '/' );
		$dirname = ($pos === false) ? $sourceDir : substr ( $sourceDir, $pos + 1 );
		$destDir = $destDir . '/' . $dirname;
		
		// Make destination directory
		if (! is_dir ( $destDir )) {
			mkdir ( $destDir );
		}
		
		// Loop through the folder
		/* @var $dir Directory */
		$dir = dir ( $sourceDir );
		
		while ( false !== $entry = $dir->read () ) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}
			// Deep copy directories
			if ($destDir !== $sourceDir . '/' . $entry) {
				self::copyDir ( $sourceDir . '/' . $entry, $destDir . '/' . $entry );
			}
		
		}
		// Clean up
		$dir->close ();
		
		return true;
	
	}
	
	/**
	 * Force the browser to download the passed string
	 *
	 * @param string $string
	 * @param string $filename
	 * @return mixed
	 */
	public static function sendString($string, $filename = 'file') {
		if (headers_sent ()) {
			return false;
		}
		
		ob_clean ();
		header ( "Content-Type: application/octet-stream name=" . $filename );
		header ( "Content-encoding: text/html" );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Content-Length: " . strlen ( $string ) * 4 );
		header ( "Content-Disposition: inline; filename=" . $filename );
		header ( "Expires: 0" );
		header ( "Cache-Control: no-cache, must-revalidate" );
		header ( "Cache-Control: private" );
		header ( "Pragma: public" );
		
		echo $string;
		exit ();
	}
	
	/**
	 * Force the browser to download the passed file
	 *
	 * @param string $path
	 * @param string $filename
	 * @param boolean $return if true the function will return instead of interrupt the execution
	 * @return boolean
	 */
	public static function sendFile($path, $filename = null, $return = false) {
		if (! file_exists ( $path ) || headers_sent ()) {
			throw new IOException ( $path );
		}
		$filename = (! is_null ( $filename )) ? $filename : basename ( $path );
		
		$size = self::filesize ( $path );
		ob_clean ();
		header ( "Content-Type: application/octet-stream name=" . $filename );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Content-Length: " . $size );
		header ( "Content-Disposition: inline; filename=" . $filename );
		header ( "Expires: 0" );
		header ( "Cache-Control: no-cache, must-revalidate" );
		header ( "Cache-Control: private" );
		header ( "Pragma: public" );
		
		readfile ( $path );
		
		ob_end_flush ();
		if ($return)
			return true;
		else
			exit ();
	}
	
	/**
	 * Get the mime type of the specified file
	 * @param string $filePath
	 * @return string
	 */
	public static function getMime($filePath) {
		$finfo = finfo_open ( FILEINFO_MIME_TYPE );
		$mime = finfo_file ( $finfo, $filePath );
		finfo_close ( $finfo );
		return $mime;
	}
	
	/**
	 * Returns a human readable filesize
	 *
	 * @var int $bytes The size in bytes
	 * @var boolean $asString - return the value as string
	 *
	 * @return array keys:value,unit
	 */
	public static function humanSize($bytes, $asString = false) {
		$mod = 1000;
		
		$units = array ('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
		for($i = 0; $bytes > $mod - 1; $i ++) {
			$bytes /= $mod;
		}
		if ($asString == true)
			return round ( $bytes, 2 ) . " " . $units [$i];
		
		return array ("value" => round ( $bytes, 2 ), "unit" => $units [$i] );
	}
}

?>