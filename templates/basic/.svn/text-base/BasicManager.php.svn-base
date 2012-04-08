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

namespace dottedBytes\templates\basic;

use dottedBytes\libs\pageBuilder\template\TemplateException;

use dottedBytes\templates\basic\html\TemplateHtml;

use dottedBytes\libs\pageBuilder\template\TemplateManager;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class BasicManager extends TemplateManager {
	
	public function buildTemplate($type = '', $charset = '') {
		switch ($type) {
			case 'text/html' :
				return new TemplateHtml ();
			default :
				throw new TemplateException ( 'Basic', $type );
		}
	}
	
	public function getTypes() {
		return array ("text/html" );
	}
}

?>