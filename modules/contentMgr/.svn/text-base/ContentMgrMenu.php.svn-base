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

namespace dottedBytes\modules\contentMgr;

use dottedBytes\libs\modules\menu\ModuleMenu;

use dottedBytes\modules\contentMgr\helpers\ArticleContent;

use dottedBytes\libs\database\DBManager;

use dottedBytes\modules\contentMgr\helpers\ContentMenu;

class ContentMgrMenu implements ModuleMenu {
	
	public function getMenu($params) {
		$menu = new ContentMenu ();
		
		$database = DBManager::getInstance ();
		
		$result = $database->query ( "SELECT * FROM #__contents WHERE published=1 ORDER BY title ASC" );
		
		foreach ( $result->fetchAll () as $row ) {
			$content = new ArticleContent ( $row );
			$menu->addArticle ( $content );
		}
		
		return $menu;
	}
}

?>