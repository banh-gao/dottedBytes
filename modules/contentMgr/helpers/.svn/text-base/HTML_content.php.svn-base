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

use dottedBytes\libs\users\permissions\Permission;

use dottedBytes\libs\pageBuilder\template\Template;

use OOForm\elements\HtmlElement;

use dottedBytes\libs\html\form\Captcha;

use OOForm\elements\editor\Editor;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use OOForm\elements\basic\Submit;

use OOForm\elements\basic\Text;

use OOForm\elements\basic\Hidden;

use dottedBytes\libs\html\rss\FeedItem;

use dottedBytes\libs\html\rss\RSSGenerator;

use dottedBytes\modules\contentMgr\toolbar\RssButton;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\html\PageNavigation;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\modules\ModFactory;

use dottedBytes\libs\html\toolbar\PrintButton;

use dottedBytes\modules\contentMgr\toolbar\DeleteContentButton;

use dottedBytes\modules\contentMgr\toolbar\EditContentButton;

use dottedBytes\modules\contentMgr\toolbar\NewContentButton;

use dottedBytes\libs\modules\PageNotFoundException;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\pageBuilder\Resources;
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class HTML_content {
	
	const TITLE_MAXLENGTH = 38;
	
	public static function get_content() {
		$content = new Content ();
		
		if (UserUtils::getCurrentUser ()->hasPermission ( 'editor' )) {
			PageData::addToolbarButton ( new NewContentButton () );
		}
		
		$id = ( int ) PageData::getParam ( 'itemid', 0 );
		if ($id <= 0) {
			$content->addData ( self::get_news_content () );
			return $content;
		}
		
		$database = DBManager::getInstance ();
		
		$published = (UserUtils::getCurrentUser ()->hasPermission ( 'editor' )) ? '1' : "published=1";
		
		$result = $database->query ( "SELECT * FROM #__contents WHERE id='$id' AND $published" );
		
		if ($result->rowCount () < 1)
			throw new PageNotFoundException ( "Content with ID " . $id . " not found.", 0, CONTENT_ARTICLE_NOTFOUND );
		
		$row = $result->fetchAll ();
		$row = $row [0];
		
		if (UserUtils::getCurrentUser ()->hasPermission ( 'editor' )) {
			PageData::addToolbarButton ( new EditContentButton () );
			PageData::addToolbarButton ( new DeleteContentButton () );
			$scripts = "<script type=\"text/javascript\">
								function deleteElement(contentID) {
									if(confirm('" . PageData::JSMessage ( CONTENT_DELETE_ALERT ) . "'))
										location.href = '" . BASEURL . "/index.php?section=contentMgr&task=editor_delete&itemid='+contentID;
								}
							</script>";
			$content->addData ( $scripts );
		}
		$content->addData ( self::get_article_content ( $row ) );
		
		return $content;
	}
	
	private static function get_article_content($row) {
		$database = DBManager::getInstance ();
		
		$article = ContentMgrHelper::buildArticle ( $row );
		
		//Increment readed counter if is currently published
		if ($article->isPublished ())
			$database->query ( "UPDATE #__contents SET readed=readed+1 WHERE id={$article->getId()}" );
		
		PageData::addSmallToolbarButton ( new PrintButton () );
		
		self::addRSSLink ( $article );
		
		$tags = $article->getTags ();
		
		$plainTags = implode ( ",", array_map ( function ($t) {
			return $t->getLocalName ();
		}, $tags ) );
		
		if ($plainTags != '')
			PageData::setMetadata ( 'keywords', $article->getTitle () . ',' . $plainTags );
		else
			PageData::setMetadata ( 'keywords', $article->getTitle () );
		
		if ($article->getSubtitle () != '')
			PageData::setMetadata ( 'description', $article->getSubtitle () );
		
		if ($article->getAuthor ()->getId () != 0)
			PageData::setMetadata ( 'author', $article->getAuthor ()->getName () );
		
		$title = ($article->isPublished ()) ? $article->getTitle () : '[' . $article->getTitle () . ']';
		
		$content = new Content ( $title );
		
		$smallToolbar = '<span class="smallToolbar">' . PageData::getSmallToolbar ()->getIconHTML () . '</span>';
		
		$html = '<div class="subtitle">' . $article->getSubtitle () . $smallToolbar . '</div>';
		
		if ($article instanceof ArticleContent) {
			$html .= '<span class="date">' . LocaleUtils::time ( $article->getAuthorDate (), 0, - 1 );
			$html .= '<span class="readed"> - ' . $article->getReaded () . ' ' . CONTENT_READED . '</span></span>';
		}
		
		$text = html_entity_decode ( $article->getText (), ENT_QUOTES, 'UTF-8' );
		
		if (PageData::getParam ( 'highlighted', false ) !== false) {
			$keywords = explode ( " ", PageData::getParam ( 'highlighted', false ) );
			$text = ContentMgrHelper::highlightText ( $text, $keywords );
		}
		
		$html .= '<div class="text">' . $text . '</div>';
		
		if ($article instanceof ArticleContent) {
			$html .= '<span class="author">' . CONTENT_WRITEBY . ' ' . $article->getAuthor ()->getName () . '</span><br />';
			if ($article->getEditor ()->getId () != 0) {
				$editorDate = LocaleUtils::time ( $article->getEditorDate (), 0, - 1 );
				if ($article->getEditor ()->equals ( $article->getAuthor () ))
					$html .= '<span class="lastEdit">' . CONTENT_EDITOR_TIME . ' - ' . $editorDate . '</span><br />';
				else
					$html .= '<span class="lastEdit">' . CONTENT_EDITOR_TIME . ' ' . CONTENT_EDITOR_FROM . ' ' . $article->getEditor ()->getName () . ' - ' . $editorDate . '</span><br />';
			}
		}
		
		if (count ( $article->getTags () ) > 0) {
			$html .= '<br /><br /><span class="tags">Tags: ' . self::tagToString ( $article->getTags () ) . '</span><br />';
		}
		
		$html .= self::getSocialButton ();
		
		$content->addData ( $html );
		$content->addData ( self::get_comment_content ( $article ) );
		return $content;
	}
	
	public static function tagToString($tags, $noHtml = false) {
		$HTMLtags = array ();
		foreach ( $tags as $tag ) {
			$tag = trim ( $tag->getCanonicalName () );
			if ($noHtml)
				$tagsLink [] = $tag;
			else
				$tagsLink [] = '<a href="' . BASEURL . '/index.php?task=search&keywords=' . $tag . '">' . $tag . '</a>';
		}
		return implode ( ", ", $tagsLink );
	}
	
	public static function getSocialButton() {
		PageData::addHeader ( ModUtils::getCurrentModule ()->getConfigValue ( 'social.buttonScript' ) );
		if (ModUtils::getCurrentModule ()->getConfigValue ( 'social.useButton', false ))
			return ModUtils::getCurrentModule ()->getConfigValue ( 'social.buttonHTML' );
	}
	
	public static function get_news_content() {
		$database = DBManager::getInstance ();
		$content = new Content ();
		
		$maxCols = ModUtils::getModuleInfoByName ( 'contentMgr' )->getConfigValue ( 'news.columns', 2 );
		$query = "SELECT * FROM #__contents WHERE published=1 ORDER BY creation_time DESC";
		
		$result = $database->query ( $query );
		$pageLimit = ModUtils::getCurrentModule ()->getConfigValue ( 'category.pageElements', 5 );
		$pn = new PageNavigation ( $result->rowCount (), '?section=contentMgr', $pageLimit );
		$query = "SELECT * FROM #__contents WHERE  published=1 ORDER BY creation_time DESC LIMIT {$pn->getGlobalStart()},{$pn->getRowLimit()}";
		//Get infos for RSS link
		self::addRSSLink ();
		
		$html = "";
		
		$result = $database->query ( $query );
		
		if ($result->rowCount () < 1) {
			$content->addData ( $html );
			return $content;
		}
		
		$html .= '<div class="news"><ul>';
		while ( ($row = $result->fetch ()) !== false ) {
			$article = ContentMgrHelper::buildArticle ( $row, '' );
			
			//Wrap long titles
			$label = String::wordSplit ( $article->getTitle (), 0, self::TITLE_MAXLENGTH + 30 );
			if (strlen ( $article->getTitle () ) > self::TITLE_MAXLENGTH + 30)
				$label .= '...';
			$tooltip = $article->getTitle ();
			
			$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $article->getId ();
			
			$readAll = "<a href=\"$link\"> ..." . CONTENT_READALL . "</a>";
			$html .= "<li class=\"module\"><div class=\"head\"><span class=\"title\"><a href=\"$link\" title=\"$tooltip\">" . $label . "</a></span>";
			$html .= '<span class="date">' . LocaleUtils::time ( $article->getAuthorDate (), 0, - 1 ) . '</span></div>';
			$text = ContentMgrHelper::textPreview ( $article->getText (), false );
			$html .= "<div class=\"body\">" . $text . "</div>";
			$html .= "<div class=\"footer\"><span class=\"author\">";
			$html .= CONTENT_WRITEBY . " " . $article->getAuthor ()->getName ();
			$html .= "<span class=\"readed\"> - " . $article->getReaded () . " " . CONTENT_READED;
			$html .= "</span></span></div></li>\n";
		}
		$html .= '</ul>' . $pn->getMenu () . '</div>';
		
		$content->addData ( $html );
		return $content;
	}
	
	public static function get_comment_content(ArticleContent $article) {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'comments.enable', false ))
			return '';
		
		if (! $article->useComments ())
			return '';
		
		$database = DBManager::getInstance ();
		
		$comments = ContentMgrHelper::getComments ( $article );
		
		$html = "<div class=\"comments\"><a name=\"comments\"></a>\n";
		//Display comments
		if (count ( $comments ) > 0) {
			$html .= "<ul class=\"comment\">";
			foreach ( $comments as $comment ) {
				/* @var $comment Comment */
				$date = LocaleUtils::time ( $comment->date );
				$html .= '<li class="module">';
				$html .= "<a name=\"comment$comment->id\"></a><div class=\"head\">";
				$html .= '<span class="title">' . self::getCommentControls ( $comment ) . "</span><span class=\"date\">" . $date . "</span></div>\n";
				$html .= '<div class="body"><div class="icon">' . Resources::getGravatar ( $comment->author, 100 ) . '<span class="username">' . $comment->author->getUsername () . "</span></div>\n";
				$html .= '<div class="commentText">' . html_entity_decode ( $comment->text, ENT_QUOTES, 'UTF-8' ) . "</div>\n";
				$html .= "</div></li>\n";
			}
			$html .= "</ul>\n";
		} else {
			$html .= "<h3 align=\"center\">" . CONTENT_COMMENT_NOCOMMENT . "</h3>\n";
		}
		
		$html .= "</div>\n";
		
		$content = new Content ();
		
		$html .= "<div class=\"commentForm\"><li class=\"module\">\n";
		$html .= "<div class=\"head\">" . CONTENT_COMMENT_NEWCOMMENT . "</div><div class=\"body\">\n";
		
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'comments.enableGuestPost' ) && UserUtils::getCurrentUser ()->getId () == 0) {
			//Only users can post comments
			$form = new Form ();
			$form->addElement ( new HtmlElement ( CONTENT_COMMENT_GUESTPOST ) );
			$html .= $form->render ();
			$html .= '</div>';
			$content->addData ( $html );
			return $content;
		}
		
		//Display new comment form
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "contentMgr" ) );
		$form->addElement ( new Hidden ( "task", "comment" ) );
		$form->addElement ( new Hidden ( "itemid", $article->getId () ) );
		
		if (UserUtils::getCurrentUser ()->getId () == 0) {
			$form->addElement ( new Text ( 'sender', CONTENT_COMMENT_NAME ) );
			$emailBox = new Text ( 'email', CONTENT_COMMENT_EMAIL );
			$emailBox->setTooltip ( CONTENT_COMMENT_EMAIL_TIP );
			$form->addElement ( $emailBox );
			$form->addElement ( new Editor ( 'comment', '', CONTENT_COMMENT ) );
			$form->addElement ( new Captcha () );
		} else {
			$form->addElement ( new Editor ( 'comment', '', CONTENT_COMMENT ) );
		}
		
		$form->addElement ( new Submit ( CONTENT_COMMENT_SEND ) );
		$html .= $form->render ();
		$html .= '</div></li></div>';
		$content->addData ( $html );
		return $content;
	}
	
	private static function getCommentControls(Comment $comment) {
		if (! UserUtils::getCurrentUser ()->hasPermission ( 'editor' ))
			return '';
		
		$info = $comment->author->getUsername () . '(' . $comment->author->getEmail () . '): ' . $comment->IPAddress;
		
		$controls = Resources::getSysIcon ( 'information', 1, $info );
		$controls .= '<a href="' . BASEURL . '/index.php?section=contentMgr&task=comment_delete&itemid=' . $comment->id . '&contentid=' . $comment->contentId . '">' . Resources::getSysIcon ( 'comment_delete', 1, sprintf ( CONTENT_COMMENT_DELETE, $comment->author->getUsername () ) ) . '</a>';
		
		return $controls;
	}
	
	private static function addRSSLink(ArticleContent $article = null) {
		//Add always news feed
		$newsUrl = BASEURL . '/index.php?section=contentMgr&task=rss';
		PageData::addHeader ( '<link rel="alternate" type="application/rss+xml" title="' . CONTENT_NEWS . '" href="' . $newsUrl . '" />' );
		PageData::setSefReplaceRule ( $newsUrl, array ('articles' => 0, 'feed' => 0 ), 'xml' );
		
		//Homepage
		if ($article == null)
			return;
		
		//Add content feed and button
		$rssUrl = BASEURL . "/index.php?section=contentMgr&task=rss";
		PageData::addSmallToolbarButton ( new RssButton ( $rssUrl ) );
	}
	
	public static function load_news_rss() {
		$database = DBManager::getInstance ();
		
		//Hide rss clients
		$sessionID = UserUtils::getCurrentUser ()->getSessionID ();
		UserUtils::hideConnectedUser ( $sessionID );
		
		$rss = new ContentRss ();
		
		$result = $database->query ( "SELECT * FROM #__contents WHERE published=1 ORDER BY creation_time DESC" );
		$rows = $result->fetchAll ();
		
		foreach ( $rows as $row ) {
			$rss->addArticle ( ContentMgrHelper::buildArticle ( $row ) );
		}
		
		return $rss->getOutput ();
	}
	
	public static function load_sitemap() {
		$database = DBManager::getInstance ();
		
		$res = $database->query ( "SELECT MAX(readed) FROM #__contents WHERE published=1" );
		$maxViews = $res->fetchColumn ( 0 );
		
		$result = $database->query ( "SELECT * FROM #__contents WHERE published=1 ORDER BY readed DESC , creation_time DESC" );
		$rows = $result->fetchAll ();
		
		$content = new Content ();
		$content->setMimeType ( 'text/xml' );
		
		$content->addData ( '<?xml version="1.0" encoding="UTF-8"?>' . "\n" );
		$content->addData ( '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n" );
		
		foreach ( $rows as $row ) {
			$article = ContentMgrHelper::buildArticle ( $row );
			
			$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $article->getId ();
			
			if ($article->getEditor ()->getId () != 0)
				$lastMod = $article->getEditorDate ()->format ( DATE_W3C );
			else
				$lastMod = $article->getAuthorDate ()->format ( DATE_W3C );
			
			if ($article->getReaded () == 0)
				$priority = '0.1';
			else
				$priority = round ( ($article->getReaded ()) / $maxViews, 3 );
			
			$priority = str_replace ( ',', '.', $priority );
			
			$content->addData ( "<url><loc>$link</loc><lastmod>$lastMod</lastmod>" );
			$content->addData ( "<changefreq>daily</changefreq><priority>$priority</priority></url>\n" );
		}
		$content->addData ( '</urlset>' );
		return $content;
	}
	
	public static function load_comments_rss() {
		//Hide rss clients
		$sessionID = UserUtils::getCurrentUser ()->getSessionID ();
		UserUtils::hideConnectedUser ( $sessionID );
		
		$database = DBManager::getInstance ();
		$contentID = ( int ) PageData::getParam ( 'itemid', 0 );
		$content = ContentMgrHelper::getArticle ( $contentID );
		
		if (! $content->useComments ())
			throw new ContentMgrException ( "Comments disabled for article with ID " . $contentID . ".", 0, CONTENT_FEED_NOTFOUND );
		
		if (! $content->isPublished ())
			throw new PageNotFoundException ( "Article with ID " . $contentID . " not found.", 0, CONTENT_ARTICLE_NOTFOUND );
		
		$comments = ContentMgrHelper::getComments ( $content );
		
		$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $content->getId () . '#comments';
		$rss = new RSSGenerator ( sprintf ( CONTENT_COMMENTS_FOR, $content->getTitle () ), $link );
		
		foreach ( $comments as $comment ) {
			$commentElement = new FeedItem ();
			$commentElement->setTitle ( sprintf ( CONTENT_COMMENTS_BY, $comment->author->getUsername () ) );
			$commentElement->setAuthor ( $comment->author );
			$commentElement->setDate ( $comment->date );
			$commentElement->setDescription ( $comment->text );
			
			$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $comment->contentId . '#comment' . $comment->id;
			$commentElement->setLink ( $link );
			
			$rss->addElement ( $commentElement );
		
		}
		return $rss->getOutput ();
	}
	
	/**
	 * @param $keywords
	 * @return Content
	 */
	public static function search_form($keywords = "") {
		$form = new Form ( 'search' );
		$form->setAttribute ( 'class', '' );
		$form->addElement ( new Hidden ( 'section', 'contentMgr' ) );
		$form->addElement ( new Hidden ( 'task', 'search' ) );
		$submit = '/><input type="submit" value="' . CONTENT_SEARCH . '"';
		$searchBox = new Text ( 'keywords' );
		$searchBox->setValue ( $keywords );
		$form->addElement ( $searchBox );
		$form->addElement ( new Submit ( CONTENT_SEARCH ) );
		
		$content = new Content ( CONTENT_SEARCH, 'search' );
		$content->addData ( $form->render () );
		return $content;
	}
}
?>