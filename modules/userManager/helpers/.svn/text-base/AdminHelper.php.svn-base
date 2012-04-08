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

namespace dottedBytes\modules\userManager\helpers;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\pageBuilder\Content;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );
	
class AdminHelper {
	public static function getAdminPage() {
		$content = new Content ();
		$page = PageData::getParam ( 'page', '' );
		switch ($page) {
			case 'listUsers' :
				$content->addData ( HTML_userManager_Admin::listUsers () );
				$content->setTitle ( USERMANAGER_USERADMIN );
				PageData::addToBreadcrubs ( USERMANAGER_USERADMIN );
				break;
			case 'editUser' :
				$content->addData ( HTML_userManager_Admin::edit_form () );
				break;
			case 'ipLookup' :
				$content->addData ( HTML_userManager_Admin::ipLookup () );
				$content->setTitle ( USERMANAGER_IPLOOKUP );
				PageData::addToBreadcrubs ( USERMANAGER_IPLOOKUP );
				break;
			case 'userDetails' :
				$content->addData ( HTML_userManager_Admin::userDetails () );
				break;
			case 'pm' :
				$content->addData ( HTML_userManager_Admin::listPM_form () );
				break;
			case 'readPM' :
				$content->addData ( HTML_userManager_Admin::readPM_form () );
				break;
			default :
				$content->addData ( HTML_userManager_Admin::homeAdmin () );
				$content->setTitle ( USERMANAGER_USERADMIN );
				PageData::addToBreadcrubs ( USERMANAGER_USERADMIN );
				break;
		}
		return $content;
	}
}