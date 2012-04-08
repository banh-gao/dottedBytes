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

namespace OOForm\elements\basic;

use OOForm\elements\InputField;

/**
 * Defines an image submit button
 * @author Daniel Zozin
 *
 */
class Image extends InputField {
	
	/**
	 * Create an image submit button, when the image is clicked the form is submitted
	 * @param string $imagesrc The url of the image
	 */
	public function __construct($imagesrc, $label = '') {
		parent::__construct ( '', '', $label );
		$this->setAttribute ( 'src', $imagesrc );
		$this->setAttribute ( 'type', 'image' );
	}
	
	/**
	 * Set the image button url
	 * @param string $imagesrc
	 */
	public function setImageSrc($imagesrc) {
		$this->setAttribute ( 'src', $imagesrc );
	}
	
	/**
	 * Get the image button url
	 * @return string
	 */
	public function getImageSrc() {
		return $this->getAttribute ( 'src' );
	}
}

?>