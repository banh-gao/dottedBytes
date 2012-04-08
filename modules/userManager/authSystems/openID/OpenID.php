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

namespace dottedBytes\modules\userManager\authSystems\openID;

// no direct access
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class OpenID {
	private $isActive;
	private $url;
	/**
	 * @return the $isActive
	 */
	public function isActive() {
		return $this->isActive;
	}
	
	/**
	 * @return the $url
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * @param $isActive the $isActive to set
	 */
	public function setActive($isActive) {
		$this->isActive = $isActive;
	}
	
	/**
	 * @param $url the $url to set
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

}

?>