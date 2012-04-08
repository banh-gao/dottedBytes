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

use OOForm\elements\HtmlElement;

use OOForm\decorator\FormDecorator;

use OOForm\elements\FormElement;

use OOForm\elements\group\ElementGroup;

use OOForm\elements\LabeledElement;

class Table extends LabeledElement {
	
	/**
	 * the table model
	 * @var TableModel
	 */
	private $model;
	
	private $emptyMessage = _SITE_EMPTY;
	
	public function __construct($label = '', TableModel $model) {
		parent::__construct ( $label );
		$this->tagName = 'table';
		$this->model = $model;
		$this->setAttribute ( 'class', 'list' );
	}
	
	public function setEmptyMessage($message) {
		$this->emptyMessage = $message;
	}
	
	/**
	 * @return string
	 */
	public function getEmptyMessage() {
		return $this->emptyMessage;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see OOForm\elements.HtmlTag::renderChildren()
	 */
	public function renderChildren(FormDecorator $decorator) {
		$ret = $this->renderHeaders ( $decorator );
		if ($this->model->getRowCount () == 0) {
			$ret .= '<tr class="empty"><td colspan="' . count ( $this->model->getHeaders () ) . '">' . $this->emptyMessage . "</td></tr>";
		} else {
			for($i = 0; $i < $this->model->getRowCount (); $i ++) {
				$ret .= $this->renderRow ( $i, $decorator );
			}
		}
		return $ret;
	}
	
	private function renderHeaders(FormDecorator $decorator) {
		$ret = '<tr>';
		foreach ( $this->model->getHeaders () as $header ) {
			if (! ($header instanceof FormElement))
				$header = new HtmlElement ( '', $header );
			$ret .= '<th>' . $decorator->render ( $header ) . '</th>';
		}
		return $ret . "</tr>\n";
	}
	
	private function renderRow($i, FormDecorator $decorator) {
		$row = $this->model->getRow ( $i );
		$cssRow = ($i % 2) ? 'row0' : 'row1';
		$ret = "<tr class=\"$cssRow\">";
		foreach ( $row as $element ) {
			if (! ($element instanceof FormElement))
				$element = new HtmlElement ( '', $element );
			$ret .= '<td>' . $decorator->render ( $element ) . '</td>';
		}
		return $ret . "</tr>\n";
	}
}

?>