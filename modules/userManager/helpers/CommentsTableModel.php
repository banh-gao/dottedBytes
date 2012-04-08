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

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use OOForm\elements\table\TableModel;

class CommentsTableModel implements TableModel {
	
	private $rows;
	
	public function __construct($uid) {
		$query = "SELECT * FROM #__contents_comments JOIN ";
		$query .= "(SELECT id as contentID , title , creation_time , readed FROM #__contents) as c USING (contentID) WHERE uid={$uid} ORDER BY date DESC , title ASC";
		$db = DBManager::getInstance ();
		$result = $db->query ( $query );
		$this->rows = $result->fetchAll ();
	}
	
	public function getHeaders() {
		return array (USERMANAGER_ACCOUNT_COMMENTS_VIEW, USERMANAGER_ACCOUNT_COMMENTS_DATE, USERMANAGER_ACCOUNT_ARTICLES_TITLE, USERMANAGER_ACCOUNT_ARTICLES_AUTHORTIME );
	}
	
	public function getRowCount() {
		return count($this->rows);
	}
	
	public function getRow($i) {
		$row = $this->rows [$i];
		//Orphan comment
		$res = array ();
		if ($row->contentID == NULL) {
			$res [] = "<a href=\"" . BASEURL . "/index.php?section=contentMgr&commentid=$row->id\">" . USERMANAGER_ACCOUNT_COMMENTS_VIEW . "</a>";
			$res [] = LocaleUtils::time ( $row->date );
			$res [] = USERMANAGER_ACCOUNT_OPRHAN;
			$res [] = " -- ";
		} else {
			$title = $row->title;
			$creation_time = $row->creation_time;
			$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $row->contentID;
			PageData::setSefReplaceRule ( $link, array ('articles' => 0, $title => $row->contentID ) );
			
			$res [] = "<a href=\"$link#comment$row->id\">" . USERMANAGER_ACCOUNT_COMMENTS_VIEW . "</a>";
			$res [] = LocaleUtils::time ( $row->date );
			$res [] = "<a href=\"$link\">$title</a>";
			$res [] = LocaleUtils::time ( $creation_time );
		}
		return $res;
	}
}

?>