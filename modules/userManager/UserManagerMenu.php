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

namespace dottedBytes\modules\userManager;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\modules\menu\MenuNode;

use dottedBytes\libs\users\permissions\Permission;
class UserManagerMenu {
	private function getUserMenu() {
		$menu = new MenuNode();
		$html = '<ul class="menu">';
		
		if (UserUtils::getCurrentUser()->hasPermission( 'userAdmin' )) {
			$adminNode = new MenuNode();
			$adminNode->setLabel ( USERMANAGER_USERADMIN );
			$adminNode->setLink ( BASEURL . '/index.php?section=userManager&task=admin' );
			$menu->addChild ( $adminNode );
		}
		
		if (UserUtils::getCurrentUser()->hasPermission( 'siteConfig' )) {
			$configNode = new MenuNode ();
			$configNode->setLabel ( USERMANAGER_SITECONFIG );
			$configNode->setLink ( BASEURL . '/index.php?section=siteConfig' );
			$menu->addChild ( $configNode );
		}
		
		if ($this->getConfigValue ( 'pm.enable', false )) {
			$user = UserUtils::getCurrentUser ();
			$database = DBManager::getInstance ();
			$result = $database->query ( "SELECT COUNT(*) FROM #__users_pm WHERE toID = '" . $user->getId () . "' AND isread = 0 " );
			
			$newpm = $result->fetchColumn ( 0 );
			$pmmenu = ($newpm > 0) ? "<strong>" . USERMANAGER_ACCOUNT_PM . "($newpm)</strong>" : USERMANAGER_ACCOUNT_PM;
			$pmNode = new MenuNode ();
			$pmNode->setLabel ( $pmmenu );
			$pmNode->setLink ( BASEURL . '/index.php?section=userManager&task=pm' );
			$menu->addChild ( $pmNode );
		}
		
		$homeNode = new MenuNode ();
		$homeNode->setLabel ( USERMANAGER_ACCOUNT );
		$homeNode->setLink ( BASEURL . '/index.php?section=userManager' );
		$menu->addChild ( $homeNode );
		
		$logoutNode = new MenuNode ();
		$logoutNode->setLabel ( USERMANAGER_LOGOUT . ' <b>' . $user->getUsername () . '</b>' );
		$logoutNode->setLink ( BASEURL . '/index.php?section=userManager&task=logout' );
		$menu->addChild ( $logoutNode );
		
		//Set selected item
		if (ModUtils::getActiveComponent ()->getName () == 'userManager') {
			switch (PageData::getParam ( 'task', '' )) {
				case 'pm_new' :
				case 'pm_read' :
				case 'pm' :
					$pmNode->setSelected(true);
					break;
				case 'admin' :
					$adminNode->setSelected(true);
					break;
				default :
					$homeNode->setSelected(true);
					break;
			}
		} elseif (ModUtils::getActiveComponent ()->getName () == 'siteConfig') {
			$configNode->setSelected(true);
		}
		return $menu->render ();
	}
}

?>