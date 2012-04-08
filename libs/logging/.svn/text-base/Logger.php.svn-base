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

namespace dottedBytes\libs\logging;

use dottedBytes\libs\users\UserUtils;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

abstract class Logger {
	
	protected $resource;
	protected $options;
	
	public function __construct($resource, $options = array()) {
		$this->resource = $resource;
		$this->options = $options;
	}
	
	/**
	 * Create formatted logfiles with passed values, the values can be passed as method arguments
	 *
	 * @param mixed $values - the values to add
	 * @return boolean - true if the log was writed
	 */
	public final function log($values) {
		if (! is_array ( $values ))
			$values = func_get_args();
		array_unshift ( $values, "[client " . UserUtils::detectIP () . "]" );
		array_unshift ( $values, date ( "Y-m-d H:i:s", time () ) );
		return $this->writeLog($values);
	}
	
	/**
	 * Write the log, the passed values already contains in the first position the ip address of the client,
	 * and in the second the date of the log formatted with: Y-m-d H:i:s
	 * @param array $values
	 */
	protected abstract function writeLog($values);
}