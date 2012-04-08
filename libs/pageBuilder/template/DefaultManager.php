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

use dottedBytes\libs\modules\Module;

use dottedBytes\libs\modules\Page;

use dottedBytes\libs\modules\ModUtils;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class DefaultManager extends TemplateManager {
	
	private $emptyTemplate;
	
	private $modules = array ();
	
	public function __construct() {
		$this->emptyTemplate = new EmptyTemplate ($this);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see dottedBytes\libs\pageBuilder\template.TemplateManager::getTemplate()
	 */
	public function buildTemplate($type='') {
		return $this->emptyTemplate;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see dottedBytes\libs\pageBuilder\template.TemplateManager::getTypes()
	 */
	public function getTypes() {
		return array ();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see dottedBytes\libs\pageBuilder\template.TemplateManager::addModule()
	 */
	public function addModule(Module $module) {
		$this->modules [] = $module;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see dottedBytes\libs\pageBuilder\template.TemplateManager::getModules()
	 */
	public function getModules() {
		return $this->modules;
	}
}

class EmptyTemplate extends Template {
	private $content;
	
	public function setPageHeaders($pageHeaders) {
		return;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see dottedBytes\libs\pageBuilder\template.Template::render()
	 */
	public function render() {
		if (count ( $this->modules ) > 0)  {
			$this->content = $this->modules [count ( $this->modules ) - 1]->getContent ();
			echo $this->content->getData ();
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see dottedBytes\libs\pageBuilder\template.Template::getType()
	 */
	public function getType() {
		return '';
	}
}

?>