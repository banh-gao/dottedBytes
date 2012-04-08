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

// no direct access
use dottedBytes\libs\modules\Module;
use dottedBytes\libs\pageBuilder\listener\ModuleListener;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class EmailProtect implements ModuleListener {
	
	public function process(Module $module) {
		$text = preg_replace_callback ( '/([a-zA-Z0-9_]*)@([a-zA-Z0-9_\.]*)/', array ($this, 'replaceEmail' ), $module->getContent()->getData() );
		$module->getContent()->setData($text);
	}
	
	private function replaceEmail($matches) {
		$output = "";
		for($i = 0; $i < strlen ( $matches [0] ); $i ++)
			$output .= '&#' . ord ( $matches [0] [$i] ) . ';';
		return $output;
	}
}
?>