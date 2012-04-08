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

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\utils\collections\ObjectSet;

use dottedBytes\libs\modules\ModUtils;

use OOForm\elements\HtmlElement;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\html\PageNavigation;

use OOForm\elements\basic\Hidden;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\utils\collections\ObjectList;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\utils\String;

use PDO;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class SearchHelper {
	
	const ALLOWED_SEARCHFORMAT = '[-\*a-zA-Z0-9\/]{3,}';
	
	static public function get_search_content() {
		
		//Extract valid keywords
		$keywords = self::parseSearchWords ( PageData::getParam ( 'keywords', '', false ) );
		
		$content = new Content ();
		
		//Redisplay search field
		$content->addData ( HTML_content::search_form ( implode ( ' ', $keywords ) ) );
		
		//To short keywords error
		if (count ( $keywords ) < 1) {
			$html = '<table class="list">';
			$html .= '<tr class="empty"><td colspan="1">' . CONTENT_SEARCH_LENGTH . "</td></tr>\n";
			$html .= '</table>';
			$content->addData ( $html );
			return $content;
		}
		
		$result = self::searchStandard ( $keywords );
		
		//No result
		if ($result->isEmpty ()) {
			$html = '<table width="100%" class="list">';
			$html .= '<tr class="empty"><td colspan="1">' . sprintf ( CONTENT_SEARCH_NONE, implode ( ' ', $keywords ) ) . "</td></tr>\n";
			$html .= '</table>';
			$content->addData ( $html );
			return $content;
		}
		
		$content->setTitle ( CONTENT_SEARCH_RESULT );
		
		$content->addData ( self::paginateResult ( $result, $keywords ) );
		
		return $content;
	}
	
	private static function parseSearchWords($searchString) {
		$keywords = array ();
		
		//Match normal search keywords
		preg_match_all ( '|' . self::ALLOWED_SEARCHFORMAT . '|', $searchString, $matches );
		$keywords = array_merge ( $keywords, $matches [0] );
		
		$keywords = array_map ( array (__CLASS__, 'replaceJolly' ), $keywords );
		return $keywords;
	}
	
	private static function replaceJolly($keyword) {
		return str_replace ( '*', '%', $keyword );
	}
	
	private static function paginateResult($result, $keywords) {
		$form = new Form ();
		
		$form->setAttribute ( 'class', '' );
		$form->addElement ( new Hidden ( "section", "contentMgr" ) );
		$form->addElement ( new Hidden ( "task", "search" ) );
		$form->addElement ( new Hidden ( 'keywords', implode ( '+', $keywords ) ) );
		$form->addElement ( new Hidden ( "search", 1 ) );
		$link = 'index.php?section=contentMgr&task=search&search=1&keywords=' . implode ( '+', $keywords );
		$pn = new PageNavigation ( $result->count (), $link );
		
		$html = $pn->getHeader ();
		$html .= '<table width="100%" class="list">';
		
		//Show only results for the current page
		

		$result = array_slice ( $result->getArrayCopy (), $pn->getGlobalStart (), $pn->getRowLimit () );
		
		if (count ( $result ) == 1) {
			$article = $result [0];
			
			$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $article->getId () . '&highlighted=' . implode ( '+', $keywords );
			PageData::redirect ( $link );
		}
		
		foreach ( $result as $article ) {
			/* @var $article ArticleContent */
			/* @var $pageRow Row */
			$pageRow = $pn->current ();
			
			$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $article->getId () . '&highlighted=' . implode ( '+', $keywords );
			$html .= "<tr " . $pageRow->getStyleID () . "><td  align=\"left\">";
			$html .= '<h1><a href="' . $link . '">' . $article->getTitle () . '</a></h1>';
			if ($article->getSubtitle () != '')
				$html .= '<b>' . $article->getSubtitle () . '</b> - ';
			$html .= '<span class="date">' . LocaleUtils::time ( $article->getAuthorDate (), 0, - 1 ) . '</span><br/>';
			$html .= '<br /><br /><span class="tags">Tags: ';
			$html .= ContentMgrHelper::highlightText ( HTML_content::tagToString ( $article->getTags (), true ), $keywords );
			$html .= '</span><br /></td></tr>';
			$pn->next ();
		}
		$html .= '<tr class="menu"><td colspan="1">' . $pn->getMenu () . '</td></tr>';
		$html .= '</table>';
		$form->addElement ( new HtmlElement ( '', $html ) );
		return $form->render ();
	}
	
	/**
	 * Returns a set of articles that corresponds to the keywords
	 * @param string|array $keywords
	 * @return ObjectSet
	 */
	public static function searchStandard($keywords) {
		$searchResult = new ObjectSet ();
		
		$relevanceQuery = SearchRelevancy::getQuery ( $keywords );
		
		$query = "SELECT * FROM #__contents RIGHT JOIN (" . $relevanceQuery . ") AS #__contents USING (id) WHERE published=1";
		
		$database = DBManager::getInstance ();
		
		$result = $database->query ( $query );
		
		//Build the found articles and add to the list
		foreach ( $result->fetchAll () as $row ) {
			$article = ContentMgrHelper::buildArticle ( $row, $keywords );
			$searchResult->add ( $article );
		}
		
		$searchResult->addAll ( self::searchTags ( $keywords ) );
		return $searchResult;
	}
	
	public static function searchTags($searchTags) {
		$searchResult = new ObjectSet ();
		
		$database = DBManager::getInstance ();
		
		//Search in tag list and return matching tagIDs
		$searchTagsID = array ();
		foreach ( $searchTags as $searchTag ) {
			
			$parts = array_reverse(explode ( '/', $searchTag ));
			
			$partialParent = $part = array_pop($parts);
			$existingParent = null;
			
			while ( count($parts) > 0 ) {
				if (Tag::getByName ( $partialParent ) != null) {
					$existingParent = Tag::getByName ( $partialParent );
					$partialParent .= '/' . $part;
					$searchTag = implode(' ', $parts);
					$part = array_pop($parts);
				} else {
					break;
				}
			}
			
			if ($existingParent != null)
				$query = $database->prepare ( "SELECT id FROM #__tags WHERE name LIKE ? AND parent=" . $existingParent->getId () );
			else
				$query = $database->prepare ( "SELECT id FROM #__tags WHERE name LIKE ?" );
			
			$searchTag = '%' . $searchTag . '%';
			
			$query->bindParam ( 1, $searchTag, PDO::PARAM_STR );
			$query->execute ();
			
			if ($query->rowCount () < 1)
				continue;
			
			foreach ( $query->fetchAll () as $tagRow ) {
				$tag = Tag::getById ( $tagRow->id );
				$searchTagsID [$tag->getId ()] = $tag->getId ();
				self::children_tags_rec ( $tag, $searchTagsID );
			}
		}
		
		//If no tag matched return empty
		if (count ( $searchTagsID ) < 1)
			return $searchResult;
		
		$i = 0;
		$SQLsearch = "tagID=";
		$SQLsearch .= implode ( ' OR tagID=', $searchTagsID );
		$query = "SELECT * FROM #__contents RIGHT JOIN ";
		$query .= "(SELECT COUNT(contentID) as rank , contentID as id FROM #__content_tags WHERE $SQLsearch GROUP BY contentID) AS #__contents ";
		$query .= "USING(id) WHERE published=1 ORDER BY rank DESC";
		
		//Search in database for associated tags
		$result = $database->query ( $query );
		
		if ($result->rowCount () < 1)
			return $searchResult;
		
		//Build the found articles and add to the list
		while ( ($row = $result->fetchObject ()) !== false ) {
			$article = ContentMgrHelper::buildArticle ( $row, $searchTags );
			$searchResult->add ( $article );
		}
		return $searchResult;
	}
	
	private static function children_tags_rec($parentTag, &$searchTags) {
		$children = $parentTag->getChildren ();
		foreach ( $children as $child ) {
			$searchTags [$child->getId ()] = $child->getId ();
			self::children_tags_rec ( $child, $searchTags );
		}
	}
}

?>