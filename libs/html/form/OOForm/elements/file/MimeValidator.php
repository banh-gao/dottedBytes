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

/**
 * Validate a file by the mime type
 * @author Daniel Zozin
 *
 */
class MimeValidator extends FileValidator {
	
	private $validMimes;
	
	/**
	 * Validate a file by the mime type
	 * @param array $acceptedMimes
	 */
	public function __construct($validMimes) {
		$this->setErrorMessage(OOFORM_VALIDATOR_FILE_MIME);
		if(!is_array($validMimes))
			$validMimes = array($validMimes);
		$this->validMimes = $validMimes;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see OOForm\elements\file.FileValidator::validate()
	 */
	public function validate(UploadedFile $file) {
		return in_array($file->getMime(),$this->validMimes);
	}
}

?>