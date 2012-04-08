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

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\errorHandling\CmsException;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class PageNotFoundException extends CmsException {
	
	private $referer;
	
	public function __construct($description = '', $code = 0, $message = '') {
		
		$this->referer = UserUtils::detectReferer ();
		
		if ($message == '')
			$message = _SITE_ERROR_NOTFOUND_EXPLAIN;
		
		parent::__construct ( $description, $code, $message );
		
		header ( "HTTP/1.1 404 Not Found" );
	}
	
	public function getTitle() {
		return _SITE_ERROR_NOTFOUND;
	}
	
	public function getDetails() {
		return "Requested URL: " . $this->referer;
	}
	
	public function useLog() {
		//Log only if referer isn't this page or isn't empty
		return ($this->referer != '' && $this->referer != PageData::getPageUrl ());
	}
}

?>