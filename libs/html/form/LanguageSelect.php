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

namespace dottedBytes\libs\html\form;

require_once dirname ( __FILE__ ) . '/OOForm/Form.php';

use OOForm\elements\basic\SelectOption;
use dottedBytes\libs\pageBuilder\LocaleUtils;
use dottedBytes\libs\users\UserUtils;
use OOForm\elements\basic\Select;
use dottedBytes\libs\pageBuilder\PageBuilder;
use OOForm\elements\recaptcha\Recaptcha;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

class LanguageSelect extends Select {
	public function __construct($name, $label, $selected = '') {
		if ($selected == '')
			$selected = UserUtils::getCurrentUser ()->getISOLanguage ();
		parent::__construct ( $name, $selected, $label );
		foreach ( LocaleUtils::langlist () as $value => $label ) {
			$this->addOption ( new SelectOption ( ucfirst ( $label ), $value ) );
		}
	}
}

?>