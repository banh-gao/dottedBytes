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

namespace dottedBytes\modules\contentMgr\helpers;

use dottedBytes\libs\logging\DebugLog;

use dottedBytes\libs\logging\ErrorLog;

use dottedBytes\libs\logging\LogFactory;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\html\rss\FeedItem;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\html\rss\RSSGenerator;

use dottedBytes\libs\configuration\Configuration;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ContentRss {
	private $rssGenerator;
	private $lastModified;
	private $fromMail;
	
	public function __construct() {
		$title = Configuration::getValue ( 'system.site.name' ) . ' - ' . CONTENT_NEWS;
		$link = BASEURL;
		$subtitle = Configuration::getValue ( 'system.site.description' );
		
		$this->rssGenerator = new RSSGenerator ( $title, $link, $subtitle );
		
		$this->rssGenerator->addNamespace ( 'wfw', 'http://wellformedweb.org/CommentAPI/' );
		
		$language = strtolower ( Configuration::getValue ( 'system.site.languageCode', 'en' ) );
		$this->rssGenerator->addField ( 'language', $language );
		
		$this->rssGenerator->addField ( 'generator', 'DottedBytes' );
		
		$copyright = 'Copyright ' . date ( 'Y' ) . ', ' . Configuration::getValue ( 'system.site.name' );
		$this->rssGenerator->addField ( 'copyright', $copyright );
	}
	
	public function addArticle(ArticleContent $article) {
		$previewLength = ModUtils::getCurrentModule ()->getConfigValue ( 'category.previewLength' );
		$description = html_entity_decode ( strip_tags ( $article->getText () ), ENT_QUOTES, 'UTF-8' );
		if (strlen ( $description ) > $previewLength)
			$description = String::wordSplit ( $description, 0, $previewLength ) . '...';
		
		$link = htmlentities ( BASEURL . '/index.php?section=contentMgr&itemid=' . $article->getId (), ENT_QUOTES, 'UTF-8' );
		
		$rssElement = new FeedItem ( $article->getTitle (), $link, $description );
		
		$rssElement->setAuthor ( $article->getAuthor () );
		$rssElement->setDate ( $article->getAuthorDate () );
		
		if ($article->useComments ())
			$this->addCommentsLink ( $article, $rssElement );
		
		$this->rssGenerator->addElement ( $rssElement );
	}
	
	private function addCommentsLink(ArticleContent $content, FeedItem $rssElement) {
		$commentsLink = htmlentities ( BASEURL . '/index.php?section=contentMgr&itemid=' . $content->getId (). '&amp;task=commentRss', ENT_QUOTES, 'UTF-8' );
		$rssElement->addField ( 'wfw:commentRss', $commentsLink );
	}
	
	public function getOutput() {
		$content = new Content ();
		$content->setMimeType ( 'application/rss+xml' );
		$content->addData ( $this->rssGenerator->getOutput () );
		return $content;
	}
}

?>