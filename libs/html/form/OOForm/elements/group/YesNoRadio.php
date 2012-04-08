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

namespace OOForm\elements\group;

use OOForm\decorator\FormDecorator;
use OOForm\elements\basic\Radio;

class YesNoRadio extends RadioGroup {
	
	private $yesRadio, $noRadio;
	
	public function __construct($name, $label = '') {
		parent::__construct ( $name, $label );
		$this->yesRadio = new Radio ('yes');
		$this->yesRadio->setValue(1);
		$this->addElement ( $this->yesRadio );
		
		$this->noRadio = new Radio ('no');
		$this->noRadio->setValue(0);
		$this->addElement ( $this->noRadio );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see OOForm\elements\group.RadioGroup::renderTag()
	 */
	public function renderTag(FormDecorator $renderer) {
		$this->yesRadio->setBoxLabel(OOFORM_YES);
		$this->noRadio->setBoxLabel(OOFORM_NO);
		if ($this->getValue () !== null) {
			if ($this->getValue () == false)
				$this->noRadio->setChecked ( true );
			else
				$this->yesRadio->setChecked ( true );
		}
		return parent::renderTag( $renderer );
	}
}

?>