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

use dottedBytes\libs\users\permissions\PermissionSet;

use dottedBytes\libs\users\auth\AuthException;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\pageBuilder\template\Position;

use dottedBytes\libs\utils\Comparable;

use dottedBytes\libs\utils\ObjectUtils;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );
	
class Panel extends Module {
	
	private $title;
	private $panelID;
	private $position;
	private $ordering;
	private $option;
	private $params;
	
	private $component;
	
	public function __construct(PanelBuilder $builder = null) {
		parent::__construct ( $builder );
		if ($builder instanceof PanelBuilder)
			$this->buildPanel ( $builder );
	}
	
	public function checkPermissions(PermissionSet $userPermissions) {
		throw new PanelException($this,"Unimplemented permission policy for panel ".$this->getName());
	}
	
	public function buildContent() {
		return new Content($this->title);
	}

	private function buildPanel(PanelBuilder $builder) {
		$this->panelID = $builder->getPanelID();
		$this->setTitle ( $builder->getTitle () );
		$this->option = $builder->getOption ();
		$this->params = $builder->getParams ();
		$this->position = $builder->getPosition ();
		$this->ordering = $builder->getOrdering ();
	}
	
	public function setCurrentComponent(Component $component) {
		$this->component = $component;
	}
	
	public function getCurrentComponent() {
		return $this->component;
	}
	
	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * @param field_type $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * @return string
	 */
	public function getOption() {
		return $this->option;
	}
	
	/**
	 * @return int
	 */
	public function getOrdering() {
		return $this->ordering;
	}
	
	/**
	 * @return int
	 */
	public function getPosition() {
		return $this->position;
	}
	
	/**
	 * @return the $panelID
	 */
	public function getPanelID() {
		return $this->panelID;
	}
	
	public function getParams() {
		return $this->params;
	}
	
	/**
	 * Compare panels by ordering
	 *
	 * @param Panel $obj
	 * @return int
	 */
	public function compareTo(Comparable $panel = null) {
		/* @var $panel Panel */
		ObjectUtils::checkType ( $panel, 'dottedBytes\libs\modules\Panel' );
		//Sort by ordering
		if ($this->ordering < $panel->getOrdering ())
			return - 1;
		elseif ($this->ordering > $panel->getOrdering ())
			return 1;
		else
			return 0;
	}
	
	public function equals(Comparable $panel = null) {
		return ($this->compareTo ( $panel ) == 0);
	}
	
	public function __toString() {
		$string = "Type: Panel\nName: " . $this->name . "\nTitle: " . $this->title . "\nPosition: " . Position::nameOf ( $this->position ) . "\n";
		$string .= "Ordering: " . $this->ordering . "\nOption: " . $this->option . "\nPermission:\n" . $this->perm;
		return $string;
	}
}

class PanelBuilder extends ModuleBuilder {
	private $panelID;
	private $title;
	private $position;
	private $ordering;
	private $option;
	private $params;
	
	public function __construct(ModuleBuilder $builder = null) {
		if ($builder == null)
			return;
		
		$this->id ( $builder->getId () )->name ( $builder->getName () );
	}
	
	public function getPanelID() {
		return $this->panelID;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getPosition() {
		return $this->position;
	}
	
	public function getOrdering() {
		return $this->ordering;
	}
	
	public function getOption() {
		return $this->option;
	}
	
	public function getParams() {
		return $this->option;
	}
	
	public function panelID($panelID) {
		$this->panelID = (int) $panelID;
		return $this;
	}
	
	public function title($title) {
		$this->title = (string) $title;
		return $this;
	}
	
	public function position($position) {
		$this->position = (int) $position;
		return $this;
	}
	
	public function ordering($ordering) {
		$this->ordering = (int) $ordering;
		return $this;
	}
	
	public function option($option) {
		$this->option = (string) $option;
		return $this;
	}
	
	public function params($params) {
		$this->params = (string) $params;
		return $this;
	}
}
?>