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

namespace dottedBytes\libs\modules;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\configuration\Configuration;
use dottedBytes\libs\modules\ModuleBuilder;

class ModuleInfo {
	
	private $ID;
	private $name;
	
	private $isCore;
	private $isActive;
	private $options;
	private $website;
	private $author;
	private $email;
	private $version;
	private $licence;
	
	
	public function __construct(ModuleBuilder $builder) {
		$this->ID = $builder->getId();
		$this->name = $builder->getName();
		$this->buildDetails($builder->getId());
	}
	
	private function buildDetails($id) {
		$database = DBManager::getInstance();
		$res = $database->query("SELECT * FROM #__modules WHERE id=$id");
		if($res->rowCount() < 1)
			return;
			
		$row = $res->fetch();
		$this->isCore = (boolean) $row->is_core;
		$this->isActive = (boolean) $row->active;
		$this->options = explode('|', $row->options);
		$this->website = $row->site;
		$this->author = $row->author;
		$this->email = $row->email;
		$this->version = $row->version;
		$this->licence = $row->licence;
	}
	
	public function getConfigValue($relativePath, $default = '') {
		return Configuration::getValue ( 'module.' . $this->getName () . '.' . $relativePath, $default );
	}
	
	/**
	 * The base path of the module directory
	 */
	public function getBasePath() {
		return BASEPATH . '/modules/' . $this->getName ();
	}
	
	/**
	 * The base url of the module directory
	 */
	public function getBaseUrl() {
		return BASEURL . '/modules/' . $this->getName ();
	}
	
	/**
	 * The id of the module
	 * @return int
	 */
	public function getID() {
		return $this->ID;
	}
	
	/**
	 * The name of the module
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	public function isCore() {
		return $this->isCore;
	}

	public function isActive() {
		return $this->isActive;
	}

	public function getOptions() {
		return $this->options;
	}

	public function getWebsite() {
		return $this->website;
	}

	public function getAuthor() {
		return $this->author;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getVersion() {
		return $this->version;
	}

	public function getLicence() {
		return $this->licence;
	}

	
	
}

?>