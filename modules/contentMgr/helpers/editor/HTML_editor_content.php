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

use OOForm\elements\HtmlElement;

use dottedBytes\modules\gallery\helpers\buttons\EditGalleryButton;

use OOForm\elements\LabeledElement;

use OOForm\elements\ajax\Suggestion;

use OOForm\elements\group\YesNoRadio;

use OOForm\elements\editor\Editor;

use OOForm\elements\basic\Text;

use OOForm\elements\basic\Radio;

use OOForm\elements\group\RadioGroup;

use OOForm\elements\basic\Hidden;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\modules\PageNotFoundException;

use dottedBytes\libs\html\toolbar\SubmitButton;

use dottedBytes\libs\html\toolbar\BackButton;

use dottedBytes\modules\contentMgr\helpers\ContentMgrHelper;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\io\PageData;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

class HTML_editor_content {
	
	public static function new_form() {
		$pageContent = new Content ();
		$database = DBManager::getInstance ();
		
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "contentMgr" ) );
		$form->addElement ( new Hidden ( "task", "editor_save" ) );
		
		$titleBox = new Text ( "title", CONTENT_TITLE );
		$form->addElement ( $titleBox );
		
		$subtitleBox = new Text ( "subtitle", CONTENT_SUBTITLE );
		$form->addElement ( $subtitleBox );
		
		$editorBox = new Editor ( "article", '', CONTENT_TEXT );
		$editorBox->setMode ( Editor::MODE_FULL );
		$form->addElement ( $editorBox );
		
		$form->addElement ( new YesNoRadio ( "published", CONTENT_ISPUBLISHED ) );
		$form->addElement ( new YesNoRadio ( "useComments", CONTENT_USECOMMENTS ) );
		
		$tagsSuggestion = new Suggestion ( 'tags', CONTENT_TAGS, new TagsSuggestion () );
		$tagsSuggestion->setTooltip ( CONTENT_TAGS_NEW_TIP );
		$form->addElement ( $tagsSuggestion );
		
		$pageContent->addData ( $form->render () );
		
		PageData::addToolbarButton ( new BackButton () );
		PageData::addToolbarButton ( new SubmitButton ( $form->getFormID () ) );
		
		return $pageContent;
	}
	
	public static function edit_form() {
		$pageContent = new Content ();
		$contentID = ( int ) PageData::getParam ( 'itemid', 0 );
		
		$database = DBManager::getInstance ();
		$result = $database->query ( "SELECT * FROM #__contents WHERE id=$contentID" );
		
		if ($result->rowCount () <= 0) {
			throw new PageNotFoundException ( "Content with ID $contentID not found.", 0, CONTENT_ARTICLE_NOTFOUND );
		}
		$content = ContentMgrHelper::buildArticle ( $result->fetch () );
		
		$pageContent->setIcon ( 'article' );
		PageData::addToBreadcrubs ( CONTENT_ARTICLE_EDIT_TITLE );
		$pageContent->setTitle ( CONTENT_ARTICLE_EDIT_TITLE );
		
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "contentMgr" ) );
		$form->addElement ( new Hidden ( "task", "editor_update" ) );
		$form->addElement ( new Hidden ( "itemid", $content->getId () ) );
		
		$form->addElement ( new HtmlElement ( CONTENT_AUTHOR, $content->getAuthor ()->getName () ) );
		$form->addElement ( new HtmlElement ( CONTENT_CREATION_TIME, LocaleUtils::time ( $content->getAuthorDate () ) ) );
		
		$titleBox = new Text ( "title", CONTENT_TITLE );
		$titleBox->setValue ( $content->getTitle () );
		$form->addElement ( $titleBox );
		
		$subtitleBox = new Text ( "subtitle", CONTENT_SUBTITLE );
		$subtitleBox->setValue ( $content->getSubtitle () );
		$form->addElement ( $subtitleBox );
		
		$form->addElement ( new Editor ( "article", $content->getText (), CONTENT_TEXT ) );
		
		$publishedBox = new YesNoRadio ( "published", CONTENT_ISPUBLISHED );
		$publishedBox->setValue ( $content->isPublished () );
		$form->addElement ( $publishedBox );
		
		$commentsBox = new YesNoRadio ( "useComments", CONTENT_USECOMMENTS );
		$commentsBox->setValue ( $content->useComments () );
		$form->addElement ( $commentsBox );
		
		$tagsSuggestion = new Suggestion ( 'tags', CONTENT_TAGS, new TagsSuggestion () );
		$tagsValue = array ();
		foreach ( $content->getTags () as $t )
			$tagsValue [] = $t->getCanonicalName ();
		$tagsSuggestion->setValue ( implode ( ',', $tagsValue ) );
		$tagsSuggestion->setTooltip ( CONTENT_TAGS_NEW_TIP );
		$form->addElement ( $tagsSuggestion );
		
		PageData::addToolbarButton ( new BackButton () );
		PageData::addToolbarButton ( new SubmitButton ( $form->getFormID () ) );
		
		$pageContent->addData ( $form->render () );
		return $pageContent;
	}
}

?>