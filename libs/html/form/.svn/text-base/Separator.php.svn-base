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

namespace dottedBytes\libs\html\form;

require_once dirname ( __FILE__ ) . '/OOForm/elements/Separator.php';

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

use dottedBytes\libs\pageBuilder\Resources;
use OOForm\elements\HtmlTag;

class Separator extends \OOForm\elements\Separator {
	/**
	 * A separator used for separate the form in categories for a better layout, uses resource images
	 * @param string $label
	 * @param string $imageName
	 * @see Resources::getBigIcon()
	 */
	public function __construct($label, $imageName = '') {
		parent::__construct ( $label );
		$this->setImage($imageName);
	}
	
	/**
	 * Set the separator image using a resource image
	 * @see Resources::getBigIcon()
	 */
	public function setImage($imageName) {
		$image = null;
		if ($imageName != '') {
			$path = Resources::getBigIcon ( $imageName );
			if ($path != '') {
				$image = new HtmlTag ( 'img' );
				$image->setAttribute ( 'src', $path );
			}
		}
		return parent::setImage($image);
	}
}

?>