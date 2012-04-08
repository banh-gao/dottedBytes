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

use OOForm\elements\file\FileValidator;

class SizeValidator extends FileValidator {
	
	private $minSize;
	private $maxSize;
	
	/**
	 * Create a validator that check for file size in byte
	 * @param int $minSize - Minimum size in byte
	 * @param int $maxSize - Maximum size in byte
	 */
	public function __construct($minSize = false, $maxSize = false) {
		$this->minSize = $minSize;
		$this->maxSize = $maxSize;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see OOForm\elements\file.FileValidator::validate()
	 */
	public function validate(UploadedFile $file) {
		if ($this->size ( $file->getSize() ))
			return true;
		
		//Build min max error message
		if ($this->minSize || $this->maxSize) {
			$minMaxMsg = array ();
			if ($this->minSize != false)
				$minMaxMsg [] = sprintf ( OOFORM_VALIDATOR_FILE_SIZE_MIN, $this->minSize );
			if ($this->maxSize != false)
				$minMaxMsg [] = sprintf ( OOFORM_VALIDATOR_FILE_SIZE_MAX, $this->maxSize );
			$minMaxMsg = '(' . implode ( ',', $minMaxMsg ) . ')';
		} else {
			$minMaxMsg = '';
		}
		$this->setErrorMessage ( sprintf ( OOFORM_VALIDATOR_FILE_SIZE, $minMaxMsg ) );
		return false;
	
	}
	
	private function size($size) {
		if ($this->minSize <= $size || $this->minSize == false) {
			if ($size <= $this->maxSize || $this->maxSize == false)
				return true;
		}
		return false;
	}
	/**
	 * Get the minimum file size accepted in byte
	 * @return int $minSize
	 */
	public function getMinSize() {
		return $this->minSize;
	}
	
	/**
	 * Set the minimum file size accepted in byte
	 * @param int $minSize
	 */
	public function setMinSize($minSize) {
		$this->minSize = $minSize;
	}
	
	/**
	 * Get the maximum file size accepted in byte
	 * @return int $maxSize
	 */
	public function getMaxSize() {
		return $this->maxSize;
	}
	
	/**
	 * Set the maximum file size accepted in byte
	 * @param int $maxSize
	 */
	public function setMaxSize($maxSize) {
		$this->maxSize = $maxSize;
	}
}

?>