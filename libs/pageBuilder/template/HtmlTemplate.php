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

namespace dottedBytes\libs\pageBuilder\template;

use dottedBytes\libs\modules\PanelException;

use OOForm\decorator\FormDecorator;
use dottedBytes\libs\modules\Component;

use dottedBytes\libs\modules\Panel;

use dottedBytes\libs\modules\Module;

use dottedBytes\libs\io\PageData;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

abstract class HtmlTemplate extends Template {
	const CENTER = 0;
	const LEFT = 1;
	const TOP = 2;
	const HEAD = 3;
	const RIGHT = 4;
	const BOTTOM = 5;
	const FOOT = 6;
	const DEBUG = 7;
	
	/**
	 * The list of panels
	 *
	 * @var PanelList
	 */
	protected $panelList;
	
	/**
	 * The page content
	 *
	 * @var Component
	 */
	protected $component;
	
	/**
	 * Return the decorator to render the forms
	 * @return FormDecorator
	 */
	public abstract function getFormDecorator();
	
	public function __construct() {
		$this->panelList = new PanelList ( Position::values() );
	}
	
	public function addModule(Module $module) {
		if ($module instanceof Panel)
			$this->panelList->add ( $module );
		elseif ($module instanceof Component)
			$this->component = $module;
	}
	
	/**
	 * Get current template content
	 *
	 * @return Content
	 */
	public function getContent() {
		if($this->component != null)
			return $this->component->getContent();
	}
	
	/**
	 * Return the number of the panels in the passed position
	 *
	 * @param int $position
	 * @return int
	 */
	public function countPanels($position) {
		try {
			return $this->panelList->size ( $position );
		} catch ( PanelException $e ) {
			return 0;
		}
	}
	
	public function getType() {
		return 'html';
	}
	
	public static function loadHtmlHeaders() {
		foreach ( PageData::getHeaders () as $header )
			echo $header . "\n";
	}
	
	public static function loadMetadata() {
		foreach ( PageData::getMetadata () as $name => $value )
			echo '<meta name="' . $name . '" content="' . $value . '" />' . "\n";
	}
}

?>