<?php
/**
 * @package		OOForm
 * @copyright	Copyright (C) 2010 OOForm. All rights reserved.
 * @author		Daniel Zozin
 * @license		GNU/GPL, see COPYING file
 *
 * This file is part of OOForm.
 * OOForm is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * OOForm is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OOForm.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OOForm\elements\file;

class UploadedFile {
	
	private $filename, $tempPath, $mime, $size, $errno,$error;
	
	public function __construct($filename, $tempPath, $mime, $size, $errno) {
		$this->filename = $filename;
		$this->tempPath = $tempPath;
		$this->mime = $mime;
		$this->size = $size;
		$this->errno = $errno;
		$this->error = $this->stringError ( $errno );
	}
	
	private function stringError($errno) {
		switch ($errno) {
			case UPLOAD_ERR_INI_SIZE :
				return "The uploaded file exceeds the upload_max_filesize directive in php.ini";
			case UPLOAD_ERR_FORM_SIZE :
				return "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
			case UPLOAD_ERR_PARTIAL :
				return "The uploaded file was only partially uploaded";
			case UPLOAD_ERR_NO_FILE :
				return "No file was uploaded";
			case UPLOAD_ERR_NO_TMP_DIR :
				return "Missing a temporary folder";
			case UPLOAD_ERR_CANT_WRITE :
				return "Failed to write file to disk";
			case UPLOAD_ERR_EXTENSION :
				return "File upload stopped by extension";
			default :
				return "";
		}
	}
	
	/**
	 * Move the uploaded file to the destination file path, return false on failure
	 * @param string $destination
	 * @return boolean
	 */
	public function moveFile($destination) {
		if ($this->errno != UPLOAD_ERR_OK)
			return false;
		
		return @move_uploaded_file ( $this->tempPath, $destination );
	}
	
	/**
	 * Get the original filename
	 * @return string
	 */
	public function getFilename() {
		return $this->filename;
	}
	
	/**
	 * Get the path where the file is temporary saved
	 * @return string
	 */
	public function getTempPath() {
		return $this->tempPath;
	}
	
	/**
	 * Get the mime type
	 * @return string
	 */
	public function getMime() {
		return $this->mime;
	}
	
	/**
	 * Get the filesize in byte
	 * @return int
	 */
	public function getSize() {
		return $this->size;
	}
	
	/**
	 * Get the error code when the upload failed
	 * @return int
	 */
	public function getErrorCode() {
		return $this->errno;
	}
	
	/**
	 * Get the error message when the upload failed
	 * @return string
	 */
	public function getError() {
		return $this->error;
	}
}

?>