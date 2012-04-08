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

namespace dottedBytes\modules\userManager\helpers;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use OOForm\elements\table\TableModel;

class ArticlesTableModel implements TableModel {
	
	private $rows;
	
	public function __construct($uid) {
		$db = DBManager::getInstance ();
		$result = $db->query ( "SELECT * FROM #__contents WHERE authorID={$uid} ORDER BY creation_time DESC , title ASC" );
		$this->rows = $result->fetchAll ();
	}
	
	public function getHeaders() {
		return array (USERMANAGER_ACCOUNT_ARTICLES_TITLE, USERMANAGER_ACCOUNT_ARTICLES_AUTHORTIME, USERMANAGER_ACCOUNT_ARTICLES_EDITORTIME, USERMANAGER_ACCOUNT_ARTICLES_COMMENTS, USERMANAGER_ACCOUNT_ARTICLES_READED );
	}
	
	public function getRowCount() {
		return count ( $this->rows );
	}
	
	public function getRow($i) {
		$row = $this->rows [$i];
		$editorTime = LocaleUtils::time ( $row->editor_time );
		if ($editorTime == false)
			$editorTime = '---';
		$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $row->id;
		PageData::setSefReplaceRule ( $link, array ('articles' => 0, $row->title => $row->id ) );
		if ($row->type == 'article') {
			$db = DBManager::getInstance ();
			$comments = $db->query ( "SELECT COUNT(*) FROM #__contents_comments WHERE contentID={$row->id}" );
			$comments = $comments->fetchColumn ( 0 );
		} else {
			$comments = '---';
		}
		
		$res = array ();
		$res [] = "<a href=\"$link\">$row->title</a>";
		$res [] = LocaleUtils::time ( $row->creation_time );
		$res [] = $editorTime;
		$res [] = $comments;
		$res [] = $row->readed;
		return $res;
	}
}

?>