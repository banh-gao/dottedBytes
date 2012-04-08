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

namespace OOForm\elements\table;

use OOForm\elements\FormElement;

interface TableModel {
	
	/**
	 * Get an array of the FormElement used as table headers, all non FormElement will converted in HtmlElement
	 * @return array
	 */
	public function getHeaders();
	
	/**
	 * Get an array of the FormElement of the row $i, all non FormElement will converted in HtmlElement
	 * @param int $i
	 * @return array
	 */
	public function getRow($i);
	
	/**
	 * Get the total number of rows
	 * @return int
	 */
	public function getRowCount();
}
?>