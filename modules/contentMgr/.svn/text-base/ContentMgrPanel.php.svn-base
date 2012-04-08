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

class ContentMgrPanel extends Panel {
	
	public function buildContent() {
		$content = new Content ( $this->getTitle() );
		switch ($this->getOption()) {
			
			case 'tagCloud' :
				$content->addData ( $this->getTagCloud () );
				break;
			case 'search' :
				$content->addData ( HTML_content::search_form ( PageData::getParam ( 'keywords' ) ) );
				$content->setIcon(null);
				break;
			case 'popular' :
				$content->addData ( $this->getPopularArticles () );
				break;
		}
		return $content;
	}
	
	public function checkPermissions(PermissionSet $userPermissions) {
		return true;
	}
	
	private function getPopularArticles() {
		$database = DBManager::getInstance ();
		$maxArticles = $this->getConfigValue ( 'category.popularArticles', 10 );
		$result = $database->query ( "SELECT * FROM #__contents WHERE published=1 ORDER BY readed DESC LIMIT 0,$maxArticles" );
		
		if ($result->rowCount () < 1)
			return '';
		$menu = new MenuNode ();
		while ( ($row = $result->fetch ()) !== false ) {
			$article = ContentMgrHelper::buildArticle ( $row );
			$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $article->getId ();
			
			//Wrap long titles
			$label = String::wordSplit ( $article->getTitle (), 0, HTML_content::TITLE_MAXLENGTH );
			if (strlen ( $article->getTitle () ) > HTML_content::TITLE_MAXLENGTH)
				$label .= '...';
			$tooltip = ' - ' . $article->getTitle ();
			
			$child = new MenuNode ( $article->getId () );
			$child->setLabel ( $label );
			$child->setLink ( $link );
			$child->setTooltip ( $article->getReaded () . ' ' . CONTENT_READED . $tooltip );
			
			$menu->addChild ( $child );
		}
		return $menu->render ();
	}
	
	private function getTagCloud() {
		//FIXME: enable tag cloud
		return;
		$database = DBManager::getInstance ();
		$minSize = 10;
		$maxSize = 25;
		$v = array ();
		$query = "SELECT count , tag FROM #__tags RIGHT JOIN ";
		
		//TagIDs of published contents
		$query .= "(SELECT COUNT(tagID) AS count , tagID AS id FROM #__content_tags ";
		$query .= "WHERE contentID=(SELECT id FROM #__contents WHERE id=contentID AND published=1) GROUP by tagID) ";
		
		$query .= "AS #__content_tags USING(id) ";
		$query .= "ORDER BY count DESC";
		
		$result = $database->query ( $query );
		
		//Show only if there are more that 5 tags
		if ($result->rowCount () < 5)
			return '';
		
		foreach ( $result->fetchAll () as $row ) {
			$v [$row->tag] = $row->count;
		}
		
		//Sort the array by tag name
		ksort ( $v );
		
		//Difference between the minvalue and the maxvalue
		try {
			$minValue = min ( array_values ( $v ) );
			$maxValue = max ( array_values ( $v ) );
		} catch ( ErrorToException $e ) {
		
		}
		$difference = $maxValue - $minValue;
		
		//Avoid division by 0
		if ($difference == 0)
			$difference = 1;
		
		$box = '';
		$tags = array ();
		foreach ( $v as $val => $i ) {
			$size = $minSize + ($i - $minValue) * ($maxSize - $minSize) / $difference;
			
			//The link for the search with the current tag
			$link = BASEURL . '/index.php?section=contentMgr&task=search&search=1&keywords=tag%3A' . $val;
			try {
				$tags [] = '<a style="font-size: ' . floor ( $size ) . 'px' . '" href="' . $link . '">' . htmlspecialchars ( stripslashes ( $val ) ) . '</a>';
			} catch ( ErrorToException $e ) {
			
			}
		}
		
		# valore di ritorno
		$box = implode ( "\n", $tags ) . "\n";
		return '<div class="box">' . $box . '</div>';
	}

}
?>