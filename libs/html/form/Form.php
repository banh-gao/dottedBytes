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

use dottedBytes\libs\io\FileUtils;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\pageBuilder\template\TemplateException;

use dottedBytes\libs\pageBuilder\template\HtmlTemplate;

use dottedBytes\libs\pageBuilder\PageBuilder;

FileUtils::loadFile ( 'libs/html/form/OOForm/Form' );

use OOForm\elements\recaptcha\Recaptcha;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

class Form extends \OOForm\Form {
	public function __construct($formID='content') {
		parent::__construct ( $formID );
		$this->setAttribute('class', 'form');
		try {
			$template = PageBuilder::getInstance ()->getTemplateManager ()->getTemplate ( 'text/html' );
			
			if ($template instanceof HtmlTemplate)
				$this->setDecorator ( $template->getFormDecorator () );
		} catch ( TemplateException $e ) {
			
		}
	}
	
	public static function checkCaptcha() {
		return Recaptcha::validate ( "6Le9xMASAAAAAMJsvYkTGwzgBJJq5OH1ITP-CPc3" );
	}
}

?>