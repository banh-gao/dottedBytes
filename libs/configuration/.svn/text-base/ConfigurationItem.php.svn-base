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

namespace dottedBytes\libs\configuration;

// no direct access
use dottedBytes\libs\utils\String;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ConfigurationItem {
	private $id;
	private $name;
	private $value;
	private $type;
	private $params;
	private $comment;
	
	public function __construct($id, $name, $value = '', $type = '', $params = '', $comment = '') {
		$this->id = $id;
		$this->name = $name;
		$this->type = strtolower ( $type );
		$this->params = $params;
		$this->comment = $comment;
		switch ($type) {
			case 'string' :
				$this->value = ( string ) $value;
				break;
			case 'int' :
				$this->value = ( int ) $value;
				break;
			case 'boolean' :
				$this->value = ( boolean ) $value;
				break;
			case 'float' :
				$this->value = ( float ) $value;
				break;
			case 'array' :
				$this->value = ( array ) $value;
				break;
			case 'object' :
				$this->value = ( object ) $value;
				break;
			default :
				$this->value = $value;
				break;
		}
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @return mixed $value
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * @return the $params
	 */
	public function getParams() {
		return $this->params;
	}
	
	/**
	 * @return the $comment
	 */
	public function getComment() {
		return $this->comment;
	}

}

?>