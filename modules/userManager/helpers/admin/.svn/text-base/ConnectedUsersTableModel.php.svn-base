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

namespace dottedBytes\modules\userManager\helpers\admin;

use dottedBytes\modules\userManager\helpers\UserHelper;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\html\PageNavigation;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\pageBuilder\Resources;

use dottedBytes\libs\database\DBManager;

use OOForm\elements\table\TableModel;

class ConnectedUsersTableModel implements TableModel {
	
	private $rows;
	/**
	 * 
	 * @var PageNavigation
	 */
	private $pn;
	
	public function __construct(PageNavigation $pn) {
		$database = DBManager::getInstance ();
		$query = "SELECT uid FROM #__users LIMIT {$pn->getGlobalStart()}, {$pn->getRowLimit()}";
		$result = $database->query ( $query );
		$total = $result->rowCount ();
		$users = UserHelper::getConnectedUsers ( true );
		$users->addAll ( UserHelper::getConnectedGuests ( true ) );
		$this->rows = $users->getArrayCopy();
		$this->pn = $pn;
	}
	
	public function getRow($i) {
		$user = $this->rows[$i];
		$username = ($user->getId () == 0) ? 'Guest ' . $i ++ : $user->getUsername ();
		if ($user->getHidden () == 1)
			$username .= ' (Hidden)';
		$res [] = $this->pn->getGlobalStart () + $i + 1;
		$res [] = "<a href=\"" . BASEURL . "/index.php?section=userManager&task=admin&page=userDetails&sid={$user->getSessionID()}\">{$username}</a>";
		$res [] = $user->getGroup ()->getName ();
		$res [] = "<a href=\"" . BASEURL . "/index.php?section=userManager&task=admin&page=ipLookup&ip={$user->getIP()}\">" . $user->getIP () . "</a>";
		$res [] = "<a href=\"{$user->getUrl()}\">{$user->getUrl()}</a>";
		return $res;
	}
	
	public function getRowCount() {
		return count ( $this->rows );
	}
	
	public function getHeaders() {
		return array ('#', USERMANAGER_USERNAME, USERMANAGER_GROUP, USERMANAGER_IP, USERMANAGER_CURRENTPAGE );
	}
}

?>