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

use dottedBytes\libs\pageBuilder\template\HtmlTemplate;
use dottedBytes\libs\pageBuilder\template\Template;
use dottedBytes\libs\pageBuilder\PageBuilder;

use dottedBytes\libs\modules\Module;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

abstract class TemplateManager {
	
	private $templates = array ();
	
	/**
	 * Get the template associated with the specified template type
	 * @param string $type
	 * @return Template
	 */
	public final function getTemplate($type = '', $charset = '') {
		if (! array_key_exists ( $type, $this->templates )) {
			$templates [$type] = $this->buildTemplate ( $type, $charset );
		}
		return $templates [$type];
	}
	
	/**
	 * Get the template associate with the current TemplateManager
	 * @return Template
	 */
	public static function getCurrentTemplate() {
		return PageBuilder::getInstance ()->getCurrentTemplate ();
	}
	
	/**
	 * Build the template of the specified type
	 * @param string $type
	 * @return Template
	 */
	protected abstract function buildTemplate($type = '');
	
	/**
	 * Get an array that contains the types of available templates
	 * @return array
	 */
	public abstract function getTypes();
}

?>