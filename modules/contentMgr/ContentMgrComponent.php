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

use dottedBytes\modules\contentMgr\helpers\SearchHelper;

use dottedBytes\libs\users\permissions\Permission;

use dottedBytes\libs\users\permissions\PermissionSet;

use dottedBytes\modules\contentMgr\helpers\editor\ContentMgr_editor;

use dottedBytes\modules\contentMgr\helpers\ContentMgrException;

use dottedBytes\libs\users\auth\PermissionException;
use dottedBytes\modules\contentMgr\helpers\ContentMgrHelper;
use dottedBytes\libs\users\UserUtils;
use dottedBytes\modules\contentMgr\helpers\HTML_content;
use dottedBytes\libs\io\PageData;
use dottedBytes\libs\pageBuilder\Content;
use dottedBytes\libs\modules\Component;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ContentMgrComponent extends Component {
	
	public function buildContent() {
		$content = new Content ();
		
		$task = PageData::getParam ( 'task' );
		$itemid = ( int ) PageData::getParam ( 'itemid', 0 );
		switch ($task) {
			case 'commentRss' :
				$content->addData ( HTML_content::load_comments_rss () );
				break;
			
			case 'rss' :
				if (PageData::getParam ( 'tags', 0 ) == 0) {
					$content->addData ( HTML_content::load_news_rss () );
				} else {
					$content->addData ( HTML_content::load_filtered_rss () );
				}
				break;
			
			case 'comment' :
				if (! $this->getConfigValue ( 'comments.enableGuestPost' ) && UserUtils::getCurrentUser ()->getId () == 0)
					throw new ContentMgrException ( 'Guest cannot post comments', 0, CONTENT_COMMENT_GUESTPOST );
				
				ContentMgrHelper::saveComment ();
				break;
			
			case 'comment_delete' :
				ContentMgrHelper::deleteComment ();
				break;
			
			case 'search' :
				$content->addData ( SearchHelper::get_search_content () );
				break;
			
			case 'sitemap' :
				$content->addData ( HTML_content::load_sitemap () );
				break;
			
			default :
				//Editor mode
				if (substr ( $task, 0, strpos ( $task, '_' ) )) {
					$editor = new ContentMgr_editor ();
					$content->addData ( $editor->getContent () );
				} else {
					$content->addData ( HTML_content::get_content () );
				}
				break;
		}
		return $content;
	}
	
	public function checkPermissions(PermissionSet $userPermissions) {
		$task = PageData::getParam ( 'task' );
		if (! substr ( $task, 0, strpos ( $task, '_' ) ))
			return true;
			
		if (!$userPermissions->contains ( Permission::getByName ( 'editor' ) ))
			throw new PermissionException ( Permission::getByName ( 'editor' ) );
		else
			return true;
	}
}

?>