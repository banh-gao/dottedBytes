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

namespace dottedBytes\modules\contentMgr\helpers\editor;

use dottedBytes\modules\contentMgr\helpers\Tag;

use OOForm\elements\basic\SelectOption;

use OOForm\elements\basic\Select;

use dottedBytes\libs\users\EmptyGroup;

use OOForm\validator\LengthValidator;

use OOForm\validator\ChoiceValidator;

use OOForm\validator\EmptyValidator;

use dottedBytes\libs\html\form\Form;

use dottedBytes\modules\contentMgr\helpers\ContentMgrHelper;

use dottedBytes\libs\io\PageData;
use dottedBytes\libs\utils\String;
use dottedBytes\libs\users\UserUtils;
use dottedBytes\libs\database\DBManager;
use PDO;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class CLASS_content {
	
	public static function save() {
		$request = Form::getRequest ();
		$title = $request->getValue ( 'title', '', new LengthValidator ( 3 ) );
		$subtitle = $request->getValue ( "subtitle", '' );
		
		$article = $request->getValue ( 'article', '', new EmptyValidator () );
		$article = html_entity_decode ( $article, ENT_QUOTES, 'UTF-8' );
		
		$tags = $request->getValue ( 'tags' );
		$published = $request->getValue ( 'published', 0, new EmptyValidator () );
		$useComments = $request->getValue ( "useComments", 0, new EmptyValidator () );
		
		$request->sendErrors ();
		
		$authorID = UserUtils::getCurrentUser ()->getId ();
		
		$database = DBManager::getInstance ();
		$query = "INSERT INTO #__contents (title,subtitle,text,authorID,creation_time,published,useComments) ";
		$query .= "VALUES (?,?,?,?,NOW(),?,?)";
		$database->beginTransaction ();
		$query = $database->prepare ( $query );
		$query->bindParam ( 1, $title, PDO::PARAM_STR );
		$query->bindParam ( 2, $subtitle, PDO::PARAM_STR );
		$query->bindParam ( 3, $article, PDO::PARAM_STR );
		$query->bindParam ( 4, $authorID, PDO::PARAM_INT );
		$query->bindParam ( 5, $published, PDO::PARAM_BOOL );
		$query->bindParam ( 6, $useComments, PDO::PARAM_BOOL );
		$query->execute ();
		$contentID = $database->getInsertId ();
		$database->commit ();
		
		$tags = String::split ( $tags, ',' );
		
		//Add new tags
		self::addTags ( $tags );
		
		//Save tags for the content
		self::saveTags ( $contentID, $tags );
		
		$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $contentID;
		
		$content = ContentMgrHelper::getArticle ( $contentID );
		
		UserUtils::getCurrentUser ()->logActivity ( 'Content ' . $content . ' created', 'Content management' );
		
		PageData::redirect ( $link, CONTENT_ARTICLE_SAVED );
	}
	
	public static function update() {
		$request = Form::getRequest ();
		$contentid = $request->getValue ( 'itemid', '', new EmptyValidator () );
		
		$title = $request->getValue ( 'title', '', new LengthValidator ( 3 ) );
		$subtitle = $request->getValue ( 'subtitle', '' );
		
		$article = $request->getValue ( 'article', '', new EmptyValidator () );
		$article = html_entity_decode ( $article, ENT_QUOTES, 'UTF-8' );
		
		$tags = $request->getValue ( "tags" );
		
		$published = $request->getValue ( "published", 0, new EmptyValidator () );
		$useComments = $request->getValue ( "useComments", 0, new EmptyValidator () );
		
		$request->sendErrors ();
		
		$database = DBManager::getInstance ();
		$query = "UPDATE #__contents SET title=?,subtitle=?,text=?,published=?,useComments=?,editorID=?,editor_time=NOW()";
		$query .= " WHERE id=?";
		$query = $database->prepare ( $query );
		$query->bindParam ( 1, $title, PDO::PARAM_STR );
		$query->bindParam ( 2, $subtitle, PDO::PARAM_STR );
		$query->bindParam ( 3, $article, PDO::PARAM_STR );
		$query->bindParam ( 4, $published, PDO::PARAM_BOOL );
		$query->bindParam ( 5, $useComments, PDO::PARAM_BOOL );
		$uid = UserUtils::getCurrentUser ()->getId ();
		$query->bindParam ( 6, $uid, PDO::PARAM_INT );
		$query->bindParam ( 7, $contentid, PDO::PARAM_INT );
		$query->execute ();
		
		$tags = String::split ( $tags, ',' );
		
		//Insert new tags
		foreach ( $tags as $tag ) {
			Tag::addTag(trim($tag));
		}
		
		//Save tags for the content
		self::saveTags ( $contentid, $tags );
		
		$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $contentid;
		
		$content = ContentMgrHelper::getArticle ( $contentid );
		UserUtils::getCurrentUser ()->logActivity ( 'Content ' . $content . ' updated', 'Content management' );
		
		PageData::redirect ( $link, CONTENT_ARTICLE_SAVED );
		
		return true;
	}
	
	public static function publish() {
		$contentID = ( int ) PageData::getParam ( 'contents', '' );
		
		$database = DBManager::getInstance ();
		$count = 0;
		$result = $database->query ( "UPDATE #__contents SET published=1 WHERE id='$contentID'" );
		$content = ContentMgrHelper::getArticle ( $contentID );
		UserUtils::getCurrentUser ()->logActivity ( 'Content ' . $content . ' published', 'Content management' );
		
		PageData::redirect (BASEURL, CONTENT_PUBLISHED );
	}
	
	public static function unpublish() {
		$contentID = ( int ) PageData::getParam ( 'contents', '' );
		
		$database = DBManager::getInstance ();
		$result = $database->query ( "UPDATE #__contents SET published=0 WHERE id='$contentID'" );
		$content = ContentMgrHelper::getArticle ( $contentID );
		UserUtils::getCurrentUser ()->logActivity ( 'Content ' . $content . ' unpublished', 'Content management' );
		
		PageData::redirect (BASEURL,  CONTENT_UNPUBLISHED );
		return true;
	}
	
	public static function delete() {
		$contentID = ( int ) PageData::getParam ( 'itemid' );
		$content = ContentMgrHelper::getArticle ( $contentID );
		
		$database = DBManager::getInstance ();
		$result = $database->query ( "DELETE FROM #__contents WHERE id={$content->getId()}" );
		
		UserUtils::getCurrentUser ()->logActivity ( 'Content ' . $content . ' deleted', 'Content management' );
		
		PageData::redirect(BASEURL, CONTENT_DELETED );
	}
	
	private static function saveTags($contentID, $tags) {
		$database = DBManager::getInstance ();
		
		$contentID = ( int ) $contentID;
		
		$deleteQuery = array ();
		foreach ( $tags as $tag ) {
			$tag = Tag::getByName(String::trim ( $tag ));
			$id = $tag->getId();
			$query = $database->prepare ( "INSERT INTO #__content_tags (contentID,tagID) VALUES(?,?)" );
			$query->bindParam ( 1, $contentID, PDO::PARAM_INT );
			$query->bindParam ( 2, $id , PDO::PARAM_INT );
			$query->execute ();
			
			$deleteQuery [] = "tagID != $id";
		}
		$delete = implode ( " AND ", $deleteQuery );
		
		$delete = (count ( $deleteQuery ) > 0) ? "AND ($delete)" : '';
		
		//Delete tags not specified in save request
		$database->query ( "DELETE FROM #__content_tags WHERE contentID=$contentID $delete" );
	}
}

?>