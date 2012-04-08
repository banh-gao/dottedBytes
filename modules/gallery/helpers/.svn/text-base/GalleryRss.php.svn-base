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

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\html\rss\FeedItem;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\io\FileUtils;

use dottedBytes\libs\html\rss\RSSGenerator;

use dottedBytes\libs\configuration\Configuration;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class GalleryRss {
	private $rssGenerator;
	private $lastModified;
	
	public function __construct(GalleryElement $category = null) {
		if ($category == null) {
			$title = Configuration::getValue ( 'system.site.name' ) . ' - ' . GALLERY_NEWS;
			$link = BASEURL;
			$subtitle = Configuration::getValue ( 'system.site.description' );
		} else {
			$title = Configuration::getValue ( 'system.site.name' ) . ' - ' . $category->getTitle ();
			$link = BASEURL . '/index.php?section=gallery&itemid=' . $category->getId ();
			$subtitle = GALLERY_ROOT;
		}
		
		$this->rssGenerator = new RSSGenerator ( $title, $link, $subtitle );
		
		$this->rssGenerator->addField ( 'generator', 'DottedBytes' );
		
		$language = strtolower ( Configuration::getValue ( 'system.site.languageCode', 'en' ) );
		$this->rssGenerator->addField ( 'language', $language );
		
		$copyright = 'Copyright ' . date ( 'Y' ) . ', ' . Configuration::getValue ( 'system.site.name' );
		$this->rssGenerator->addField ( 'copyright', $copyright );
	}
	
	public function addElement(GalleryElement $element) {
		
		if ($element->getType () == 'album')
			$link = BASEURL . '/index.php?section=gallery&itemid=' . $element->getId ();
		else
			$link = BASEURL . FileUtils::stripBasePath ( $element->getPath () );
		
		$article = new FeedItem ( $element->getTitle (), $link );
		
		$article->setAuthor ( $element->getAuthor () );
		
		$article->setDate ( $element->getAuthorDate () );
		
		$description = '<![CDATA[<img alt="" src="' . BASEURL . FileUtils::stripBasePath ( $element->getThumbPath () ) . '"
                 title="' . $element->getTitle () . '" />]]>';
		
		$article->setDescription ( $description );
		
		if ($element->getType () == 'image')
			$article->addAttachment ( $link );
		
		$parent = GalleryHelper::buildElement ( $element->getParentId () );
		$parentTitle = $parent->getTitle ();
		if ($parentTitle != '')
			$article->addField ( 'category', "<![CDATA[{$parentTitle}]]>" );
		
		$this->rssGenerator->addElement ( $article );
	}
	
	public function Output() {
		$gallery = new Content ();
		$gallery->setMimeType( 'application/rss+xml' );
		$gallery->addData ( $this->rssGenerator->getOutput () );
		return $gallery;
	}
}

?>