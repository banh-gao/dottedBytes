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

namespace dottedBytes\libs\pageBuilder;

use dottedBytes\libs\html\form\Form;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class Content {
	
	/**
	 * The content title
	 *
	 * @var string
	 */
	protected $title = '';
	/**
	 * The mime type
	 *
	 * @var string
	 */
	protected $mimeType = 'text/html';
	
	/**
	 * The charset
	 * @var string
	 */
	protected $charset = 'utf-8';
	
	/**
	 * The content data
	 *
	 * @var string
	 */
	protected $data = '';
	/**
	 * The content icon
	 *
	 * @var string
	 */
	protected $icon = '';
	
	public function __construct($title = '', $icon = '') {
		$this->setTitle ( $title );
		$this->setIcon ( $icon );
	}
	
	/**
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}
	
	/**
	 * @return string
	 */
	public function getCharset() {
		return $this->charset;
	}
	
	/**
	 * @return string
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 * @return string
	 */
	public function getIcon() {
		return $this->icon;
	}
	
	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * @param string $mimeType
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
	}
	
	/**
	 * @param string $charset
	 */
	public function setCharset($charset) {
		$this->charset = $charset;
	}
	
	/**
	 * @param string $data
	 */
	public function addData($data) {
		if ($data instanceof Content) {
			$this->title = ($data->getTitle () != '') ? $data->getTitle () : $this->title;
			$this->mimeType = ($data->getMimeType () != '') ? $data->getMimeType () : $this->mimeType;
			$this->icon = ($data->getIcon () != '') ? $data->getIcon () : $this->icon;
			$this->data .= $data->getData ();
		} else {
			$this->data .= $data;
		}
	}
	
	public function setData($data) {
		$this->data = '';
		$this->addData ( $data );
	}
	
	/**
	 * @param string $icon
	 */
	public function setIcon($icon) {
		$path = Resources::getBigIcon ( $icon );
		if ($path != '')
			$this->icon = $path;
		else
			$this->icon = $icon;
	}
	
	public function __toString() {
		return $this->getTitle () . " (" . $this->getMimeType () . ")";
	}
}

?>