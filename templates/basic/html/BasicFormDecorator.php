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

namespace dottedBytes\templates\basic\html;

use OOForm\elements\HtmlTag;

use OOForm\Form;

use dottedBytes\libs\io\PageData;

use OOForm\decorator\DefaultDecorator;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class BasicFormDecorator extends DefaultDecorator {
	
	public function __construct() {
		parent::__construct();
		$this->clearHeadTags();
		$this->setShowErrorSummary(false);
	}
	
	public function render(HtmlTag $tag) {
		$out = parent::render($tag);
		foreach ($this->getRenderedHeadTags() as $tag)
			PageData::addHeader($tag);
		return $out;
	}
}

?>