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

namespace OOForm\elements\ajax;

/**
 * Define a class that will answer to incoming ajax request
 * @author Daniel Zozin
 * 
 */
abstract class AjaxListener {
	/**
	 * Get the url where the listener will respond
	 * @return string
	 */
	public abstract function getServiceUrl();
	
	/**
	 * Returns an array of string results
	 * @return array
	 */
	protected abstract function getResponse($query);
	
	/**
	 * Process the incoming ajax request and print the json response
	 * then the program will exit
	 * @return string
	 */
	public function processRequest() {
		if (array_key_exists ( 'query', $_REQUEST )) {
			$query = htmlentities ( $_REQUEST ['query'] , ENT_COMPAT , 'UTF-8');
			$responses = $this->getResponse ( $query );
		} else {
			echo "{query:'',suggestions:[]}";
			exit ();
		}
		if (! is_array ( $responses ))
			$responses = array ($responses );
		
		$responses = implode ( "','", $responses );
		
		echo "{query:'$query',suggestions:['" . $responses . "']}";
		exit ();
	}
}

?>