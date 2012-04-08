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

namespace dottedBytes\modules\gallery;

use dottedBytes\libs\users\permissions\PermissionSet;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\users\permissions\Permission;

use dottedBytes\modules\gallery\helpers\UploadHelper;

use dottedBytes\modules\gallery\helpers\HTML_gallery;

use dottedBytes\modules\gallery\helpers\GalleryHelper;

use dottedBytes\libs\users\auth\PermissionException;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\modules\Component;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class GalleryComponent extends Component {
	
	const GALLERY_TITLELENGTHLIMIT = 32;
	
	const EDITOR_PERM = 'editor';
	
	public function buildContent() {
		
		$content = new Content ();
		
		if (! defined ( 'GALLERY_BASEPATH' ))
			define ( 'GALLERY_BASEPATH', BASEPATH . '/' . $this->getConfigValue ( 'images.baseDir' ) );
		
		$task = PageData::getParam ( 'task' );
		
		PageData::addToBreadcrubs ( GALLERY_ROOT, BASEURL . '/index.php?section=gallery' );
		
		switch ($task) {
			case 'new' :
				GalleryHelper::setBreadcrubs ( PageData::getParam ( 'parentID', false ) );
				
				$content->setIcon ( 'add' );
				PageData::addToBreadcrubs ( GALLERY_ALBUM_NEW );
				$content->setTitle ( GALLERY_ALBUM_NEW );
				$content->addData ( HTML_gallery::new_form () );
				break;
			case 'edit' :
				$content->addData ( HTML_gallery::edit_form () );
				break;
			case 'save' :
				UploadHelper::newAlbum ();
				break;
			case 'update' :
				UploadHelper::updateElement ();
				break;
			case 'publish' :
				GalleryHelper::publish ();
				break;
			case 'unpublish' :
				GalleryHelper::unpublish ();
				break;
			case 'delete' :
				GalleryHelper::delete ();
				break;
			case 'regenerateThumbs' :
				GalleryHelper::regenerateThumbs ();
				break;
			case 'rss' :
				$content->addData ( HTML_gallery::load_rss () );
				break;
			case 'export' :
				GalleryHelper::exportGallery ();
				break;
			default :
				GalleryHelper::setBreadcrubs ( PageData::getParam ( 'itemid', 0 ) );
				$content->addData ( HTML_gallery::get_gallery () );
				break;
		}
		return $content;
	}
	
	public function checkPermissions(PermissionSet $userPermissions) {
		$task = PageData::getParam ( 'task' );
		switch ($task) {
			case 'new' :
			case 'edit' :
			case 'save' :
			case 'update' :
			case 'publish' :
			case 'unpublish' :
			case 'delete' :
			case 'regenerateThumbs' :
			case 'export' :
				if (! Permission::check ( $userPermissions, self::EDITOR_PERM ))
					throw new PermissionException ( self::EDITOR_PERM );
		}
		return true;
	}

}
?>