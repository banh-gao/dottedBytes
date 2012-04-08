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

use OOForm\elements\HtmlTag;

use OOForm\Form;
use OOForm\elements\ajax\AjaxListener;
use OOForm\decorator\FormDecorator;
use OOForm\elements\basic\Text;

/**
 * Create a textbox that will show suggestion during the digitation,
 * the queries are executed with ajax to a specified AjaxListener
 * @author Daniel Zozin
 * @see AjaxListener
 * 
 */
class Suggestion extends Text {
	
	private $listenerUrl;
	private $wordDelimiter = '/(,)\s*/';
	
	private static $libLoaded = false;
	
	/**
	 * Create a textbox with ajax suggestions
	 * @param string $name
	 * @param string $label
	 * @param AjaxListener $listener
	 */
	public function __construct($name, $label = '', AjaxListener $listener) {
		parent::__construct ( $name, $label );
		$this->setAttribute ( 'autocomplete', 'off' );
		$this->listenerUrl = $listener->getServiceUrl ();
	}
	
	/**
	 * Set the character that delimitate the words for ajax queries
	 * @param string $delimiter
	 */
	public function setWordDelimiter($delimiter) {
		$this->wordDelimiter = $delimiter;
	}
	
	public function renderTag(FormDecorator $decorator) {
		$this->setAttribute ( 'id', $this->getFormElementID() );
		$decorator->addJavascript( Form::getBaseUrl () . '/elements/ajax/jquery.autocomplete.js');
		
		$out = parent::renderTag( $decorator );
		
		$out .= '<script type="text/javascript">var o, a;$(document).ready(function() {
  		o = { serviceUrl:\'' . $this->listenerUrl . '\', delimiter: ' . $this->wordDelimiter . '};
  		a = $(\'#' . $this->getFormElementID() . '\').autocomplete(o);
		});</script>';
		return $out;
	}
}

?>