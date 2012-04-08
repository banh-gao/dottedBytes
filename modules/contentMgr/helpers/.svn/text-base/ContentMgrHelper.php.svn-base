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

use dottedBytes\libs\html\form\Captcha;

use OOForm\validator\EmptyValidator;

use OOForm\validator\EmailValidator;

use OOForm\elements\HtmlElement;

use OOForm\elements\basic\Hidden;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\modules\ModFactory;

use dottedBytes\libs\users\User;

use dottedBytes\libs\users\UserBuilder;

use dottedBytes\libs\modules\PageNotFoundException;

use dottedBytes\libs\utils\collections\ObjectSet;

use dottedBytes\libs\html\PageNavigation;

use dottedBytes\libs\utils\collections\ObjectList;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\io\PageData;

use PDO;

use DateTime;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ContentMgrHelper {
	
	static public function saveComment() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'comments.enable', false ))
			throw new ContentMgrException ( 'Comments service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$request = Form::getRequest ();
		
		$user = UserUtils::getCurrentUser ();
		if ($user->getId () == 0) {
			$name = $request->getValue ( 'sender', '' );
			$email = $request->getValue ( 'email', '', new EmailValidator () );
			$userID = null;
		} else {
			$name = null;
			$email = null;
			$userID = $user->getId ();
		}
		$contentID = $request->getValue ( 'itemid', '' );
		$comment = $request->getValue ( 'comment', '', new EmptyValidator () );
		if (UserUtils::getCurrentUser ()->getId () == 0)
			Captcha::validate ();
		
		$request->sendErrors ();
		
		//Remove from comment all unauthorized html tags
		$allowed = '<p><ul><ol><li><span><strong><em>';
		$comment = strip_tags ( $comment, $allowed );
		
		$database = DBManager::getInstance ();
		
		$query = $database->prepare ( 'INSERT INTO #__contents_comments (contentID,uid,comment,date,addr,name,email)  VALUES(?,?,?,NOW(),?,?,?)' );
		$query->bindParam ( 1, $contentID, PDO::PARAM_INT );
		$query->bindParam ( 2, $userID, PDO::PARAM_INT );
		$query->bindParam ( 3, $comment, PDO::PARAM_STR );
		$ip = UserUtils::getCurrentUser ()->getIP ();
		$query->bindParam ( 4, $ip, PDO::PARAM_STR );
		$query->bindParam ( 5, $name, PDO::PARAM_STR );
		$query->bindParam ( 6, $email, PDO::PARAM_STR );
		$query->execute ();
		$content = self::getArticle ( $contentID );
		$user->logActivity ( 'Comment for ' . $content . ' posted', 'Content management' );
		
		PageData::redirect ( UserUtils::getCurrentUser ()->getReferer (), CONTENT_COMMENT_INSERT );
	}
	
	public static function deleteComment() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'comments.enable', false ))
			throw new ContentMgrException ( 'Comments service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$database = DBManager::getInstance ();
		$commentID = ( int ) PageData::getParam ( 'itemid', 0 );
		$database->query ( "DELETE FROM #__contents_comments WHERE id=$commentID" );
		$user = UserUtils::getCurrentUser ();
		$contentID = ( int ) PageData::getParam ( 'contentid', 0 );
		
		$content = self::getArticle ( $contentID );
		$user->logActivity ( 'Comment #' . $commentID . ' for ' . $content . ' deleted', 'Content management' );
		
		$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $contentID;
		PageData::redirect ( $link, CONTENT_COMMENT_DELETED );
	}
	
	public static function highlightText($text, $keywords) {
		if (! is_array ( $keywords ))
			$keywords = array ($keywords );
		
		foreach ( $keywords as $word ) {
			$patterns [] = "|(" . preg_quote ( $word ) . ")|";
		}
		return preg_replace ( $patterns, "<span class=\"highlight\">$1</span>", $text );
	}
	
	/**
	 * Return an ArticleContent if success or thrown a PageNotFoundException on failed
	 * @param int $id
	 * @return ArticleContent
	 */
	public static function getArticle($id) {
		$database = DBManager::getInstance ();
		$id = ( int ) $id;
		if ($id == 0)
			return new ArticleContent ();
		$res = $database->query ( "SELECT * FROM #__contents WHERE id=$id" );
		if ($res->rowCount () < 1)
			throw new PageNotFoundException ( "Article with ID " . $id . " not found.", 0, CONTENT_ARTICLE_NOTFOUND );
		
		return self::buildArticle ( $res->fetch () );
	}
	
	public static function getComments(ArticleContent $content) {
		$contentID = $content->getId ();
		$result = array ();
		$database = DBManager::getInstance ();
		$comments = $database->query ( "SELECT * FROM #__contents_comments WHERE contentID=$contentID ORDER BY date DESC" );
		if ($comments->rowCount () < 1)
			return $result;
		
		foreach ( $comments->fetchAll () as $commentRow ) {
			$comment = new Comment ();
			
			$comment->id = $commentRow->id;
			$comment->contentId = $commentRow->contentID;
			$comment->text = $commentRow->comment;
			$comment->IPAddress = $commentRow->addr;
			$comment->date = new DateTime ( $commentRow->date );
			
			$sender = UserUtils::getUser ( $commentRow->uid );
			if ($sender->getId () == 0) {
				$builder = new UserBuilder ();
				$builder->username ( "nr:" . $commentRow->name )->email ( $commentRow->email );
				$comment->author = new User ( $builder );
			} else {
				$comment->author = $sender;
			}
			
			$result [] = $comment;
		}
		return $result;
	}
	
	/**
	 * Returns an ArticleContent from the passed database row, all row must be selected in the query
	 * @param Object $databaseRow
	 * @return ArticleContent
	 */
	public static function buildArticle($databaseRow, $keyword = null) {
		$previewLength = ModUtils::getModuleInfoByName ( 'contentMgr' )->getConfigValue ( 'search.previewLength' );
		$article = new ArticleContent ( $databaseRow );
		if ($keyword != null) {
			$article->addText ( self::textPreview ( $databaseRow->text, $keyword, $previewLength ) );
		}
		return $article;
	}
	
	public static function textPreview($text, $searchWord = false, $length = 0, $link = '') {
		$text = strip_tags ( $text );
		if (! is_array ( $searchWord ))
			$searchWord = array ($searchWord );
		
		if ($length == 0)
			$length = ModUtils::getCurrentModule ()->getConfigValue ( 'category.previewLength', 50 );
		
		$highlighted = substr ( $text, 0, $length ) . $link;
		
		foreach ( $searchWord as $word ) {
			if ($word != false && stripos ( $text, $word ) !== false) {
				$wordPosition = stripos ( $text, $word );
				$endPosition = $wordPosition + strlen ( $word );
				
				if ($wordPosition < $length) {
					//Get all text before
					$beforePreview = substr ( $text, 0, $wordPosition );
				} else {
					//Cut text before
					$beforePreview = "..." . substr ( $text, $wordPosition - $length - 3, $wordPosition );
				}
				
				//Get the foundword in the text
				$wordPreview = substr ( $text, $wordPosition, strlen ( $word ) );
				
				if (strlen ( $text ) - $endPosition > $length) {
					//Cut text after
					$afterPreview = substr ( $text, $endPosition, $length - 3 ) . "...";
				} else {
					//Get all text after
					$afterPreview = substr ( $text, $endPosition );
				}
				
				//Highlight searched word
				$highlighted = $beforePreview . $wordPreview . $afterPreview;
			}
		}
		return $highlighted;
	}
	
	public static function getUsername($uid) {
		$user = UserUtils::getUser ( ( int ) $uid );
		$username = ($user->getId () == 0) ? '-----' : $user->getUsername ();
		return $username;
	}
}

?>
