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

namespace dottedBytes\modules\menu;

use dottedBytes\libs\modules\menu\MenuUtils;

use dottedBytes\libs\users\permissions\PermissionSet;

use dottedBytes\libs\modules\menu\MenuNode;

use dottedBytes\libs\modules\menu\Menu;

use dottedBytes\libs\utils\String;

use dottedBytes\modules\contentMgr\helpers\ContentMgrHelper;

use dottedBytes\modules\contentMgr\helpers\HTML_content;

use dottedBytes\libs\errorHandling\ErrorToException;

use dottedBytes\libs\io\PageData;

use dottedBytes\modules\contentMgr\helpers\ArticleContent;

use dottedBytes\libs\database\DBManager;

use dottedBytes\modules\contentMgr\helpers\ContentMenu;

use dottedBytes\libs\modules\Panel;

use dottedBytes\libs\pageBuilder\Content;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class MenuPanel extends Panel {
	
	public function buildContent() {
		$content = new Content ( $this->getTitle() );
		$menu = new MenuNode();
		
		$sub = MenuUtils::getMenu(1);
		
		if($sub != null)
			$menu->addChild($sub);
		
		$content->addData($menu->render());
		return $content;
	}
	
	public function checkPermissions(PermissionSet $userPermissions) {
		return true;
	}
}
?>