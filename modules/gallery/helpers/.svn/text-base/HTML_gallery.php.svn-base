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

namespace dottedBytes\modules\gallery\helpers;

use dottedBytes\libs\html\form\Separator;

use OOForm\elements\group\Fieldset;

use OOForm\elements\group\ElementGroup;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\modules\gallery\helpers\buttons\RssButton;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\database\DBManager;

use dottedBytes\modules\gallery\helpers\buttons\ExportGalleryButton;

use dottedBytes\modules\gallery\helpers\buttons\RegenerateThumbsButton;

use dottedBytes\modules\gallery\helpers\buttons\PublishGalleryButton;

use dottedBytes\modules\gallery\helpers\buttons\UnpublishGalleryButton;

use dottedBytes\modules\gallery\helpers\buttons\DeleteGalleryButton;

use dottedBytes\modules\gallery\helpers\buttons\EditGalleryButton;

use dottedBytes\modules\gallery\helpers\buttons\NewGalleryButton;

use dottedBytes\libs\modules\PageNotFoundException;

use dottedBytes\libs\pageBuilder\Content;

use OOForm\elements\basic\File;

use dottedBytes\libs\html\toolbar\SubmitButton;

use dottedBytes\libs\html\toolbar\BackButton;

use OOForm\elements\group\YesNoRadio;

use dottedBytes\modules\gallery\GalleryComponent;

use OOForm\elements\basic\Text;

use OOForm\elements\basic\Hidden;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\html\PageNavigation;
use dottedBytes\libs\io\FileUtils;
use dottedBytes\libs\modules\ModUtils;
use dottedBytes\libs\io\PageData;
use dottedBytes\libs\pageBuilder\Resources;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class HTML_gallery {
	
	public static function new_form() {
		$parentID = PageData::getParam ( 'parentID', 0 );
		$gallery = GalleryHelper::buildElement ( $parentID );
		
		$form = new Form ();
		$form->addElement ( new Hidden ( 'section', 'gallery' ) );
		$form->addElement ( new Hidden ( 'task', 'save' ) );
		$form->addElement ( new Hidden ( 'parentID', $parentID ) );
		
		$tip = sprintf ( GALLERY_TITLE_MAXLENGTH, GalleryComponent::GALLERY_TITLELENGTHLIMIT );
		$titleField = new Text ( 'title', GALLERY_TITLE );
		$titleField->setTooltip ( $tip );
		$form->addElement ( $titleField );
		
		$form->addElement ( new YesNoRadio ( 'published', GALLERY_PUBLISH ) );
		
		if ($gallery->getType () == 'album')
			self::image_upload ( $form );
		
		PageData::addToolbarButton ( new BackButton () );
		PageData::addToolbarButton ( new SubmitButton ( $form->getFormID () ) );
		return $form->render ();
	}
	
	public static function edit_form() {
		$galleryID = PageData::getParam ( 'itemid', 0 );
		
		$form = new Form ();
		$form->addElement ( new Hidden ( 'section', 'gallery' ) );
		$form->addElement ( new Hidden ( 'task', 'update' ) );
		$form->addElement ( new Hidden ( 'itemid', $galleryID ) );
		
		$gallery = GalleryHelper::buildElement ( $galleryID );
		$excluded = array ();
		foreach ( GalleryHelper::getChildren ( $gallery ) as $child ) {
			$excluded [] = $child->getId ();
		}
		
		$excluded [] = $gallery->getId ();
		$albumSelect = GalleryHelper::getAlbumSelect ( 'parentID', $excluded );
		$albumSelect->setLabel ( GALLERY_ALBUM_MOVE );
		$albumSelect->setValue ( $gallery->getParentId () );
		$form->addElement ( $albumSelect );
		
		$titleField = new Text ( 'title', GALLERY_TITLE );
		$titleField->setValue ( $gallery->getTitle () );
		$titleField->setTooltip ( sprintf ( GALLERY_TITLE_MAXLENGTH, GalleryComponent::GALLERY_TITLELENGTHLIMIT ) );
		$form->addElement ( $titleField );
		
		$publishedBox = new YesNoRadio ( 'published', GALLERY_PUBLISH );
		$publishedBox->setValue ( $gallery->isPublished () );
		$form->addElement ( $publishedBox );
		
		$content = new Content ();
		
		GalleryHelper::setBreadcrubs ( $gallery->getParentId () );
		
		if ($gallery->getType () == 'album') {
			$title = sprintf ( GALLERY_ALBUM_EDIT, $gallery->getTitle () );
			PageData::addToBreadcrubs ( $title );
			$content->setTitle ( $title );
			self::image_upload ( $form );
		} else {
			$title = sprintf ( GALLERY_IMAGE_EDIT, $gallery->getTitle () );
			PageData::addToBreadcrubs ( $title );
			$content->setTitle ( $title );
		}
		
		PageData::addToolbarButton ( new BackButton () );
		PageData::addToolbarButton ( new SubmitButton ( $form->getFormID () ) );
		$content->addData ( $form->render () );
		
		$content->setIcon ( $gallery->getThumbPath () );
		return $content;
	}
	
	private static function image_upload(Form $form) {
		$maxSize = FileUtils::humanSize ( FileUtils::getMaxUploadSize (), 1 );
		
		$group = new Fieldset ( sprintf ( GALLERY_FORM_LOAD, $maxSize ) );
		$group->addElement ( new Separator( GALLERY_FORM_ZIP , 'archive' ));
		$group->addElement ( new File ( 'zipImages', GALLERY_FORM_ZIP ) );
		
		$accepted = ModUtils::getCurrentModule ()->getConfigValue ( 'images.allowedTypes' );
		$group->addElement ( new Separator ( sprintf ( GALLERY_FORM_ACCEPTED, $accepted ) ) );
		
		for($i = 1; $i <= 10; $i ++)
			$group->addElement ( new File ( 'images[]', sprintf ( GALLERY_FORM_IMAGE, $i ) ) );
			
		$form->addElement ( $group );
	}
	
	public static function get_gallery($galleryID = false) {
		$galleryID = ( int ) PageData::getParam ( 'itemid', 0 );
		
		$gallery = GalleryHelper::buildElement ( $galleryID );
		
		//Check for published elements
		if (! UserUtils::getCurrentUser()->hasPermission( 'editor' )) {
			$isEditor = false;
			//Check for album published status
			if ($gallery->isPublished () == false)
				throw new PageNotFoundException ( 'Element ' . $gallery->getTitle () . ' (#' . $galleryID . ') not published.', GALLERY_NOTFOUND, GALLERY_NOTFOUND_EXPLAIN );
		} else {
			//Load Special Editor controls and show all elements
			$isEditor = true;
			PageData::addToolbarButton ( new NewGalleryButton ( $gallery ) );
			if ($galleryID != 0) {
				PageData::addToolbarButton ( new EditGalleryButton ( $gallery ) );
				PageData::addToolbarButton ( new DeleteGalleryButton ( $gallery ) );
				if ($gallery->isPublished ())
					PageData::addToolbarButton ( new UnpublishGalleryButton ( $gallery ) );
				else
					PageData::addToolbarButton ( new PublishGalleryButton ( $gallery ) );
				
				PageData::addToolbarButton ( new RegenerateThumbsButton ( $gallery ) );
			}
			PageData::addToolbarButton ( new ExportGalleryButton ( $gallery ) );
		}
		
		$content = new Content ( $gallery->getTitle () );
		
		$database = DBManager::getInstance ();
		
		self::addRSSLink ( $gallery );
		
		$published = ($isEditor) ? '' : "published=1 AND";
		
		if ($galleryID != false) {
			$query = "SELECT * FROM #__gallery WHERE $published parentID=$galleryID ORDER BY type,title ASC";
		} else {
			$query = "SELECT * FROM #__gallery WHERE $published parentID is NULL ORDER BY type,title ASC";
		}
		
		$result = $database->query ( $query );
		$pageLimit = ModUtils::getCurrentModule ()->getConfigValue ( 'pageElements', 10 );
		$pn = new PageNavigation ( $result->rowCount (), 'index.php?section=gallery&itemid=' . $galleryID, $pageLimit );
		
		PageData::addHeader ( '<link rel="stylesheet" type="text/css" href="' . BASEURL . '/modules/gallery/style/style.css" />
		<link rel="stylesheet" type="text/css" href="' . BASEURL . '/modules/gallery/lightbox/css/jquery.lightbox-0.5.css" />
		<script>
		var lightboxImagePath = \'' . BASEURL . '/modules/gallery/lightbox/\';
		var lightboxTxtImage = \'' . GALLERY_IMAGE . '\';
		var lightboxTxtOf = \'' . GALLERY_OF . '\';
		function deleteElement(elementID,msg) {
		if(confirm(msg))
			location.href = \'' . BASEURL . '/index.php?section=gallery&task=delete&itemid=\'+elementID;
		}
		</script>
		<script type="text/javascript" src="' . BASEURL . '/modules/gallery/lightbox/jquery.lightbox-0.5.js"></script>' );
		
		$content->addData ( "<!-- Start Gallery --><script type=\"text/javascript\">$(function() { $('#gallery a.image').lightBox(); });</script>" );
		$content->addData ( '<div id="gallery"><ul>' );
		
		$children = GalleryHelper::getChildren ( $gallery, $pn->getGlobalStart (), $pn->getRowLimit () );
		
		if (count ( $children ) == 0) {
			$content->setData ( '<h3>' . sprintf ( GALLERY_EMPTY_EXPLAIN, $gallery->getTitle () ) . '</h3>' );
			return $content;
		}
		
		foreach ( $children as $child ) {
			/* @var $child GalleryElement */
			
			if ($child->getType () == "album") {
				$url = BASEURL . '/index.php?section=gallery&itemid=' . $child->getId ();
			} else {
				$url = BASEURL . FileUtils::stripBasePath ( $child->getPath () );
			}
			$thumbUrl = BASEURL . FileUtils::stripBasePath ( $child->getThumbPath () );
			$title = $child->getTitle ();
			$class = ($child->getType () == "album") ? "folder" : "image";
			if ($child->isPublished ())
				$content->addData ( "<li class=\"module\">" );
			else
				$content->addData ( "<li class=\"module unpublished\">" );
			
			if ($isEditor)
				$content->addData ( self::getAdminControls ( $child ) );
			
			$date = LocaleUtils::time ( $child->getAuthorDate (), '', - 1 );
			
			$content->addData ( "<a class=\"$class\" href=\"$url\" title=\"$title - $date\"><div class=\"body\">" );
			
			$alt = (file_exists ( $child->getPath () )) ? $title : 'Element not found';
			$content->addData ( "<img src=\"$thumbUrl\" alt=\"$alt\"/></div>" );
			$content->addData ( "<div class=\"footer\">" . $title . "</div></a></li>\n" );
		}
		
		$content->addData ( '</ul></div>' );
		$content->addData ( $pn->getMenu () );
		debug ( $content->getIcon (), 1 );
		return $content;
	}
	
	private static function addRSSLink(GalleryElement $gallery) {
		if ($gallery->getId () != false) {
			//Get link for current gallery RSS
			$rssUrl = BASEURL . '/index.php?section=gallery&task=rss&itemid=' . $gallery->getId ();
		} else {
			//Get link for Main gallery RSS
			$rssUrl = BASEURL . '/index.php?section=gallery&task=rss';
			PageData::setSefReplaceRule ( $rssUrl, array ('gallery' => 0, 'feed' => 0 ), 'xml' );
		}
		PageData::addHeader ( '<link rel="alternate" type="application/rss+xml" title="' . $gallery->getTitle () . '" href="' . $rssUrl . '" />' );
		
		PageData::addToolbarButton ( new RssButton ( $rssUrl ) );
	}
	
	public static function load_rss() {
		$database = DBManager::getInstance ();
		$elementID = ( int ) PageData::getParam ( 'itemid', 0 );
		
		//Hide rss clients
		$sessionID = UserUtils::getCurrentUser ()->getSessionID ();
		UserUtils::hideConnectedUser ( $sessionID );
		
		$gallery = GalleryHelper::buildElement ( $elementID );
		
		if (! $gallery->isPublished () && ! UserUtils::getCurrentUser()->hasPermission( 'editor' )) {
			throw new PageNotFoundException ( 'Element ' . $gallery->getTitle () . ' (#' . $elementID . ') not published.', GALLERY_NOTFOUND, GALLERY_NOTFOUND_EXPLAIN );
		}
		
		$link = BASEURL . '/index.php?section=gallery&itemid=' . $gallery->getId ();
		$rss = new GalleryRss ( $gallery );
		
		$children = GalleryHelper::getChildren ( $gallery );
		foreach ( $children as $child ) {
			$link = BASEURL . '/index.php?section=gallery&itemid=' . $child->getId ();
			$rss->addElement ( $child );
		}
		return $rss->Output ();
	}
	
	private static function getAdminControls(GalleryElement $element) {
		$html = '<div class="head">';
		if ($element->isPublished ())
			$html .= '<a href="' . BASEURL . '/index.php?section=gallery&task=unpublish&itemid=' . $element->getId () . '">' . Resources::getSysIcon ( 'world_delete', 1, GALLERY_UNPUBLISH ) . '</a>';
		else
			$html .= '<a href="' . BASEURL . '/index.php?section=gallery&task=publish&itemid=' . $element->getId () . '">' . Resources::getSysIcon ( 'world_add', 1, GALLERY_PUBLISH ) . '</a>';
		
		if ($element->getType () == 'album') {
			$html .= '<a href="' . BASEURL . '/index.php?section=gallery&task=edit&itemid=' . $element->getId () . '">' . Resources::getSysIcon ( 'picture_edit', 1, sprintf ( GALLERY_ALBUM_EDIT, $element->getTitle () ) ) . '</a>';
			$html .= '<a href="javascript:deleteElement(' . $element->getId () . ',\'' . PageData::JSMessage ( sprintf ( GALLERY_ALBUM_DELETE_ALERT, $element->getTitle () ) ) . '\')">' . Resources::getSysIcon ( 'picture_delete', 1, sprintf ( GALLERY_ALBUM_DELETE, $element->getTitle () ) ) . '</a>';
		} else {
			$html .= '<a href="' . BASEURL . '/index.php?section=gallery&task=edit&itemid=' . $element->getId () . '">' . Resources::getSysIcon ( 'picture_edit', sprintf ( GALLERY_IMAGE_EDIT, $element->getTitle () ) ) . '</a>';
			$html .= '<a href="javascript:deleteElement(' . $element->getId () . ',\'' . PageData::JSMessage ( sprintf ( GALLERY_IMAGE_DELETE_ALERT, $element->getTitle () ) ) . '\')">' . Resources::getSysIcon ( 'picture_delete', 1, sprintf ( GALLERY_IMAGE_DELETE, $element->getTitle () ) ) . '</a>';
		}
		$html .= '</div>';
		return $html;
	}
}
?>