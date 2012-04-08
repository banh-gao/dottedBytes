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

namespace dottedBytes\libs\html;

use OOForm\elements\basic\SelectOption;

use OOForm\elements\basic\Select;

use dottedBytes\libs\io\PageData;

use Iterator;

use dottedBytes\libs\html\form\Form;

// no direct access
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

/**
 * Class for result paging
 *
 */
class PageNavigation implements Iterator {
	/**
	 * The root url to generate the pages
	 *
	 * @var string
	 */
	private $baseurl = "";
	
	/**
	 * Total number of records
	 *
	 * @var int
	 */
	private $totalRows = 0;
	
	/**
	 * Record per page
	 *
	 * @var int
	 */
	private $limit;
	
	/**
	 * The row index limitated to the current page
	 *
	 * @var int
	 */
	private $pageRowIndex;
	
	/**
	 * Current page
	 *
	 * @var int
	 */
	private $page;
	/**
	 * Total number of pages
	 *
	 * @var int
	 */
	private $totalPages;
	
	private $_checkBoxes, $_limitAll = false;
	
	/**
	 * Inizialize page navigation class
	 *
	 * @param int $total total number of records
	 * @param string $baseurl url of the current page
	 * @param int $limit record limit per page
	 */
	public function __construct($total, $baseurl, $limit = 50) {
		$this->totalRows = $total;
		$this->limit = PageData::getParam ( 'limit', $limit );
		//Display all records
		if ($this->limit == 'all') {
			$this->limit = $this->totalRows;
			$this->_limitAll = true;
		}
		if ($this->limit < 1)
			$this->limit = 1;
		
		if (substr ( $baseurl, 0, 7 ) != 'http://')
			$this->baseurl = BASEURL . '/' . $baseurl;
		else
			$this->baseurl = $baseurl;
		
		$this->page = (PageData::getParam ( 'pageNavigation_page', 1 ) < 1) ? 1 : PageData::getParam ( 'pageNavigation_page', 1 );
		$this->totalPages = ( int ) ceil ( $this->totalRows / $this->limit );
		$this->pageRowIndex = 1;
		
		if (($this->totalPages < $this->page) && ($this->totalPages > 0)) {
			PageData::redirect ( $this->baseurl . "&pageNavigation_page=" . $this->totalPages . "&limit=" . $this->limit );
		}
		return true;
	}
	
	/**
	 * Get first row index in the current page. This index is referred to the overall records.
	 *
	 * @return int
	 */
	private function getLocalStart() {
		if ($this->totalRows < $this->limit)
			return 0;
		return $this->limit * ($this->page - 1);
	}
	
	/**
	 *
	 */
	public function getGlobalStart() {
		return $this->getLocalStart () + $this->pageRowIndex - 1;
	}
	
	/**
	 *
	 */
	public function getRowLimit() {
		return $this->limit;
	}
	
	/**
	 * Returns a Row object for the current row
	 * @return Row
	 */
	public function current() {
		return new Row ( $this->pageRowIndex, $this->getGlobalStart () + 1 );
	}
	
	/**
	 * Returns the row index for the current page
	 * @return int
	 */
	public function key() {
		return $this->pageRowIndex;
	}
	
	/**
	 * Move the internal pointer to the next row
	 */
	public function next() {
		$this->pageRowIndex ++;
	}
	
	/**
	 * Reset the current row to the first of the page
	 */
	public function rewind() {
		$this->pageRowIndex = 1;
	}
	
	/**
	 * @return boolean
	 */
	public function valid() {
		return ($this->pageRowIndex < $this->limit && $this->getGlobalStart () < $this->totalRows);
	}
	
	/**
	 * Get html menu for page navigation
	 *
	 * @return string
	 */
	public function getMenu() {
		$first = "&laquo;&laquo; " . _SITE_FIRST;
		$prev = "&laquo; " . _SITE_PREV;
		$next = _SITE_NEXT . " &raquo;";
		$last = _SITE_LAST . " &raquo;&raquo;";
		if ($this->totalRows <= $this->limit || $this->_limitAll == true) {
			return "<span class=\"paginationMenu\">$first $prev <b>1</b> $next $last</span>\n";
		}
		
		//First - Prev
		if ($this->page > 1) {
			$first = "<a href=\"{$this->baseurl}&pageNavigation_page=1&limit=$this->limit\" title=\"" . _SITE_PAGE . " 1 " . _SITE_OF . " $this->totalPages\" class=\"tablePagenav\">&laquo;&laquo; " . _SITE_FIRST . "</a> ";
			$prev = "<a href=\"{$this->baseurl}&pageNavigation_page=" . ($this->page - 1) . "&limit=$this->limit\" title=\"" . _SITE_PAGE . " " . ($this->page - 1) . " " . _SITE_OF . " $this->totalPages\" class=\"tablePagenav\">&laquo; " . _SITE_PREV . "</a>";
		}
		
		//Next - Last
		if ($this->page < $this->totalPages) {
			$next = "<a href=\"{$this->baseurl}&pageNavigation_page=" . ($this->page + 1) . "&limit=$this->limit\" title=\"" . _SITE_PAGE . " " . ($this->page + 1) . " " . _SITE_OF . " $this->totalPages\" class=\"tablePagenav\">" . _SITE_NEXT . " &raquo;</a>";
			$last = "<a href=\"{$this->baseurl}&pageNavigation_page={$this->totalPages}&limit=$this->limit\" title=\"" . _SITE_PAGE . " $this->totalPages " . _SITE_OF . " $this->totalPages\" class=\"tablePagenav\">" . _SITE_LAST . " &raquo;&raquo;</a>";
		}
		$menu = "<span class=\"paginationMenu\">";
		$menu .= "$first $prev <b>$this->page</b> $next $last";
		$menu .= "</span>\n";
		return $menu;
	}
	
	/**
	 * Get html header
	 *
	 * @return boolean
	 */
	public function getHeader() {
		$pageLimit = new Form ();
		$pageLimit->unsetAttribute ( 'class' );
		$limitSelect = $this->getLimitSelect ();
		$pageLimit->addElement ( $limitSelect );
		if ($this->totalRows < 1) {
			$html = '<table width="100%" style="table-layout:fixed;border-collapse:collapse;border:0px;margin-bottom:5px;"><tr>';
			$html .= '<td align="left">' . _SITE_EMPTY;
			$html .= '</td><td align="right">';
			$limitSelect->setEnabled(false);
			$html .= $pageLimit->render();
			$html .= '</td></tr></table>';
			return $html;
		} elseif ($this->page < $this->totalPages) {
			$pageRows = $this->limit;
			$html = "";
		} else {
			$current = $this->limit * ($this->page - 1);
			$pageRows = $this->totalRows - $current;
			$html = "";
		}
		$html .= '<table width="100%" style="table-layout:fixed;border-collapse:collapse;border:0px;margin-bottom:5px;"><tr>';
		$html .= '<td align="left">' . _SITE_FROM . ' ' . ($this->getLocalStart () + 1) . ' ' . _SITE_TO . ' ' . ($this->getLocalStart () + $pageRows) . ' ' . _SITE_OF . ' ' . $this->totalRows;
		$html .= '</td><td align="right">';
		$html .= '<input type="hidden" name="pageNavigation_page" value="' . $this->page . '"/>';
		$html .= $pageLimit->render();
		$html .= '</td></tr></table>';
		return $html;
	}
	
	/**
	 * Returns the form that changes the rows per page
	 * @return Form
	 */
	private function getLimitSelect() {
		$selected = ($this->_limitAll) ? 'all' : $this->limit;
		$selectBox = new Select ( 'limit', $selected );
		for($i = 10; $i <= 50; $i += 10) {
			$selectBox->addOption ( new SelectOption ( $i, $i ) );
		}
		$selectBox->addOption ( new SelectOption ( _SITE_ALL, "all" ) );
		$selectBox->setAttribute ( 'onChange', 'submit()' );
		$selectBox->setAttribute ( 'title', _SITE_LIMIT );
		return $selectBox;
	}
	
	/**
	 * Return a checkbox for check all listed items
	 *
	 * @param string $name
	 * @param boolean $checked
	 * @param string $params
	 * @return string
	 */
	public function checkAllBox($name, $checked = false, $params = "") {
		$checked = ($checked == true) ? ' checked="checked"' : "";
		
		if ($this->totalRows < 1)
			$params = 'disabled="disabled" ' . $params;
		else
			$params = 'onchange="checkAll(\'' . $name . '[]\')" title="' . _SITE_CHECKALL . '" ' . $params;
		$field = "<input type=\"checkbox\" " . $checked . " $params/>";
		$this->_checkBoxes = array ("name" => $name, "checked" => $checked );
		return $field;
	}
	
	/**
	 * Return a checkbox depending of a previous defined checkAllBox
	 *
	 * @param string $value
	 * @param string $params
	 * @return string
	 */
	public function checkBox($value, $disabled = false, $params = "") {
		
		$disabled = ($disabled == false) ? "" : ' disabled="disabled"';
		
		$field = "<input type=\"checkbox\" name=\"" . $this->_checkBoxes ["name"] . "[]\" value=\"$value\"" . $this->_checkBoxes ["checked"] . "$disabled $params/>";
		return $field;
	}
}

/* @property Row */
final class Row {
	private $pageID;
	private $globalID;
	
	public function __construct($pageID, $globalID) {
		$this->pageID = $pageID;
		$this->globalID = $globalID;
	}
	
	/**
	 * Returns the row index limitated to the current page
	 *
	 * @return int
	 */
	public function getPageID() {
		return $this->pageID;
	}
	
	/**
	 * Returns the row index with the overall records
	 *
	 * @return int
	 */
	public function getGlobalID() {
		return $this->globalID;
	}
	
	/**
	 * Returns the html id attribute for CSS style
	 *
	 * @return string
	 */
	public function getStyleID() {
		return (($this->pageID % 2) == 0) ? 'class="row0"' : 'class="row1"';
	}
}

?>