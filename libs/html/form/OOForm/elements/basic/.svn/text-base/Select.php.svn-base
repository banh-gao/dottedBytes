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

namespace OOForm\elements\basic;

use OOForm\elements\LabeledElement;

use OOForm\elements\HtmlTag;

use OOForm\elements\InputField;
use OOForm\decorator\FormDecorator;

/**
 * Defines a select field (dropdown menu)
 * @author Daniel Zozin
 *
 */
class Select extends InputField {
	
	private $options = array ();
	private $selected;
	
	/**
	 * Create a select field
	 * @param string $name The name of the select field
	 * @param string $selected The selected element. This must be value of an option not a label.
	 * @param string $label The label of the select field
	 * @param string $values The selectable options, for details see {@link Select::setValues()}
	 */
	public function __construct($name, $selected = '', $label = '') {
		parent::__construct ( $name, $label );
		$this->selected = $selected;
		$this->tagName = 'select';
	}
	
	/**
	 * Set the option values that can be selected from this field
	 * Returns false if the option is already in the select
	 * @param SelectOption $option
	 * @return boolean
	 */
	public function addOption(SelectOption $option) {
		$this->addChild($option);
		$option->setParentSelect ( $this );
		return false;
	}
	
	public function setValue($value) {
		$this->selected = $value;
	}
	
	public function getValue() {
		return $this->selected;
	}
	
	/**
	 * Get the options that can be selected from this field
	 * @return SelectOption[]
	 */
	public function getOptions() {
		return $this->getChildren();
	}
	
	/**
	 * Allow multiple options selection
	 * @param boolean $multiselection
	 */
	public function setMultiSelection($multiselection) {
		if ($multiselection)
			$this->setAttribute ( 'multiple', "multiple" );
		else
			$this->unsetAttribute ( 'multiple' );
	}
	
	/**
	 * Returns true if multiple selection is enabled
	 * @return boolean
	 */
	public function isMultiSelection() {
		return ($this->getAttribute ( 'multiple' ) != '');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see OOForm\elements.InputField::resetValue()
	 */
	protected function resetValue() {
		if ($this->getLastValue () != '')
			$this->setValue( $this->getLastValue () );
	}
}

/**
 * Defines an option for a select field
 * @author Daniel Zozin
 *
 */
class SelectOption extends HtmlTag {
	
	private $parentSelect = null;
	private $label;
	
	/**
	 * Create an option for a select field
	 * @param string $label The label showed in the select field
	 * @param string $value The value submitted if the option is selected
	 */
	public function __construct($label, $value = '') {
		$this->setLabel($label);
		$this->setAttribute('value', $value );
	}
	
	/**
	 * @return the $label
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @param field_type $label
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * Set this option as selected
	 * @param boolean $selected
	 */
	public function setSelected($selected) {
		if ($selected)
			$this->setAttribute ( 'selected', 'selected' );
		else
			$this->unsetAttribute ( 'selected' );
	}
	
	/**
	 * Get if this option is selected
	 * @return boolean
	 */
	public function isSelected() {
		return ($this->getAttribute ( 'selected' ) != null);
	}
	
	/**
	 * @return Select $parentSelect
	 */
	public function getParentSelect() {
		return $this->parentSelect;
	}
	
	/**
	 * Set the parent select element
	 * @param Select $parentSelect
	 */
	public function setParentSelect(Select $parentSelect = null) {
		$this->parentSelect = $parentSelect;
	}
	
	/**
	 * Get the value of the input field
	 * @return the $value
	 */
	public function getValue() {
		return $this->getAttribute ( 'value' );
	}
	
	/**
	 * Set the value of the input field
	 * @param string $value
	 */
	public function setValue($value) {
		if ($value != '')
			$this->setAttribute ( 'value', $value );
	}
	
	/**
	 * Returns true if this field is enabled
	 * @return boolean
	 */
	public function isEnabled() {
		return $this->getAttribute ( 'disabled' ) != null;
	}
	
	/**
	 * Specify if this field is enabled
	 * If the field is set to disabled then the value will not be passed to the elaboration page (name attribute removed) 
	 * @param boolean $enabled
	 */
	public function setEnabled($enabled) {
		if ($enabled)
			$this->unsetAttribute ( 'disabled' );
		else
			$this->setAttribute ( 'disabled', 'disabled' );
	}
	
	public function renderTag(FormDecorator $decorator) {
		if (! $this->isEnabled ())
			$this->unsetAttribute ( 'name' );
		$out = '';
		if (count ( $this->getChildren() ) > 0) {
			$this->tagName = 'optGroup';
			$this->setAttribute ( 'label', $this->getLabel () );
		} else {
			$this->tagName = 'option';
			if ($this->getRootSelect () != null && ! $this->getRootSelect ()->isMultiSelection ()) {
				//Select only the option with the value that matches with the select value
				if ($this->getValue () == $this->getRootSelect ()->getValue ()) {
					$this->setAttribute ( 'selected', 'selected' );
				} else {
					$this->unsetAttribute ( 'selected' );
				}
			}
		}
		
		$this->addChild(new HtmlTag('',$this->getLabel()));
		
		return $decorator->renderTag($this);
	}
	
	/**
	 * Get the Select element where this option is contained
	 * @return Select
	 */
	public function getRootSelect() {
		$parent = $this->getParentSelect ();
		while ( $parent != null && $parent instanceof SelectOption ) {
			$parent = $parent->getParentSelect ();
		}
		return $parent;
	}
}
?>