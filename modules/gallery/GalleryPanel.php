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

use dottedBytes\libs\modules\PageNotFoundException;

use dottedBytes\libs\io\FileUtils;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\modules\gallery\helpers\GalleryHelper;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\modules\Panel;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class GalleryPanel extends Panel {
	
	public function buildContent() {
		$content = new Content ( $this->getTitle() );
		
		if (! defined ( 'GALLERY_BASEPATH' ))
			define ( 'GALLERY_BASEPATH', BASEPATH . '/' . $this->getConfigValue( 'images.baseDir' ) );
		
		switch ($this->getOption()) {
			case 'random' :
				$content->addData ( $this->getRandomPicture () );
				break;
		}
		return $content;
	}
	
	public function checkPermissions(PermissionSet $userPermissions) {
		return true;
	}
	
	private function getRandomPicture() {
		$database = DBManager::getInstance ();
		$result = $database->query ( "SELECT id FROM #__gallery WHERE type='image' AND published=1" );
		
		if ($result->rowCount () < 1)
			return '';
		
		$images = $result->fetchAll ();
		
		$imageIndex = rand ( 0, count ( $images ) - 1 );
		
		$image = GalleryHelper::buildElement ( $images [$imageIndex]->id );
		
		if ($image->getParentId () != null) {
			$link = BASEURL . '/index.php?section=gallery&itemid=' . $image->getParentId ();
		} else {
			$link = BASEURL . '/index.php?section=gallery';
		}
		$date = LocaleUtils::time ( $image->getAuthorDate (), '', - 1 );
		return '<a style="overflow:hidden;display:block" href="' . $link . '"><img style="margin-left:auto;margin-right:auto" src="' . BASEURL . FileUtils::stripBasePath ( $image->getThumbPath () ) . '" title="' . $image->getTitle () . ' - ' . $date . '" alt="' . $image->getTitle () . '"/></a>';
	}
}
?>