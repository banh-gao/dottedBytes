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

use dottedBytes\libs\users\auth\PermissionException;

use dottedBytes\libs\users\permissions\Permission;

use dottedBytes\libs\users\permissions\PermissionSet;

use dottedBytes\libs\modules\menu\MenuNode;

use dottedBytes\libs\modules\menu\Menu;

use dottedBytes\libs\io\FileUtils;

use OOForm\elements\HtmlTag;

use OOForm\elements\secure\ChallengeField;

use OOForm\elements\secure\ChallengeForm;

use OOForm\elements\basic\Submit;

use OOForm\elements\basic\Password;

use OOForm\elements\basic\Text;

use OOForm\elements\basic\Hidden;

use OOForm\elements\HtmlElement;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\html\form\Form;

use dottedBytes\modules\userManager\helpers\UserHelper;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\modules\Panel;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class UserManagerPanel extends Panel {
	public function buildContent() {
		$content = new Content ( $this->getTitle () );
		
		switch ($this->getOption ()) {
			case 'login' :
				$content->addData ( $this->getLoginPanel () );
				break;
			case 'online' :
				$content->addData ( $this->getOnlineUsers () );
				break;
		}
		
		return $content;
	}
	
	public function checkPermissions(PermissionSet $userPermissions) {
		if ($this->getOption () == 'login' && ! Permission::check ( $userPermissions, UserManagerComponent::GUESTACCESS_PERM ))
			return false;
		else
			return true;
	}
	
	private function getLoginPanel() {
		$form = new Form ( 'login' );
		$form->setAttribute ( 'class', '' );
		$form->setAttribute ( 'onSubmit', 'return checkLogin(this)' );
		$incorrect = PageData::JSMessage ( USERMANAGER_LOGIN_INCORRECT );
		$html = <<< HTML
<script type="text/javascript">
function checkLogin(f)
{
if ( f.username.value.length==0) {
	alert('$incorrect');
	f.username.focus();
	return false;
	} else if ( f.password.value.length==0 ) {
	alert('$incorrect');
	f.password.focus();
	return false;
	}
return true;
}
</script>
HTML;
		$form->addElement ( new HtmlElement ( '', $html ) );
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "login" ) );
		$form->addElement ( new Hidden ( 'referer', urlencode ( PageData::getPageUrl () ) ) );
		$form->addElement ( new Text ( 'username', USERMANAGER_USERNAME . '<br/>' ) );
		$form->addElement ( new Password ( 'password', USERMANAGER_PASSWORD . '<br/>' ) );
		
		$form->addElement ( new Submit ( USERMANAGER_LOGIN ) );
		
		if ($this->getConfigValue ( 'registration.enableRecovery', false ))
			$form->addElement ( new HtmlElement ( '', '<br /><a href="' . BASEURL . '/index.php?section=userManager&task=pwdforgot">' . USERMANAGER_REGISTRATION_FORGOT . '</a>' ) );
		
		if ($this->getConfigValue ( 'registration.enable', false )) {
			$form->addElement ( new HtmlElement ( '', '<br /><a href="' . BASEURL . '/index.php?section=userManager&task=registration">' . USERMANAGER_REGISTRATION . '</a>' ) );
		}
		
		if ($this->getConfigValue ( 'account.enableOpenID', false )) {
			$form->addElement ( new HtmlElement ( '', '<br /><a href="' . BASEURL . '/index.php?section=userManager">' . USERMANAGER_OPENID_LOGIN . '</a>' ) );
		}
		return $form->render ();
	}
	
	private function getOnlineUsers() {
		$database = DBManager::getInstance ();
		
		// Count connected users
		$usersCount = UserHelper::getDistinctUsersCount ();
		$guestsCount = UserHelper::getDistinctGuestsCount ();
		$stats = "";
		
		if ($usersCount == 1 && $guestsCount == 0) {
			$stats = USERMANAGER_ONLINE_HAVE . " 1 " . USERMANAGER_ONLINE_REGISTER . " " . USERMANAGER_ONLINE_ONLINE;
		}
		if ($usersCount == 0 && $guestsCount == 1) {
			$stats = USERMANAGER_ONLINE_HAVE . " 1 " . USERMANAGER_ONLINE_GUEST . " " . USERMANAGER_ONLINE_ONLINE;
		}
		if ($usersCount > 1 && $guestsCount == 0) {
			$stats = USERMANAGER_ONLINE_HAVE . " $usersCount " . USERMANAGER_ONLINE_REGISTERS . " " . USERMANAGER_ONLINE_MOREONLINE;
		}
		if ($usersCount == 0 && $guestsCount > 1) {
			$stats = USERMANAGER_ONLINE_HAVE . " $guestsCount " . USERMANAGER_ONLINE_GUESTS . " " . USERMANAGER_ONLINE_MOREONLINE;
		}
		if ($usersCount == 1 && $guestsCount == 1) {
			$stats = USERMANAGER_ONLINE_HAVE . " 1 " . USERMANAGER_ONLINE_REGISTER . " " . USERMANAGER_ONLINE_AND . " 1 " . USERMANAGER_ONLINE_GUEST . " " . USERMANAGER_ONLINE_MOREONLINE;
		}
		if ($usersCount == 1 && $guestsCount > 1) {
			$stats = USERMANAGER_ONLINE_HAVE . " 1 " . USERMANAGER_ONLINE_REGISTER . " " . USERMANAGER_ONLINE_AND . " $guestsCount " . USERMANAGER_ONLINE_GUESTS . " " . USERMANAGER_ONLINE_MOREONLINE;
		}
		if ($usersCount > 1 && $guestsCount == 1) {
			$stats = USERMANAGER_ONLINE_HAVE . " $usersCount " . USERMANAGER_ONLINE_REGISTERS . " " . USERMANAGER_ONLINE_AND . " 1 " . USERMANAGER_ONLINE_GUEST . " " . USERMANAGER_ONLINE_MOREONLINE;
		}
		if ($usersCount > 1 && $guestsCount > 1) {
			$stats = USERMANAGER_ONLINE_HAVE . " $usersCount " . USERMANAGER_ONLINE_REGISTERS . " " . USERMANAGER_ONLINE_AND . " $guestsCount " . USERMANAGER_ONLINE_GUESTS . " " . USERMANAGER_ONLINE_MOREONLINE;
		}
		
		if ($usersCount < 1 || ! $this->getConfigValue ( 'showConnectedUsers', false ))
			return $stats;
		
		$html = $stats;
		
		// Connected user list
		$html .= "<br/><ul>";
		$showUserInfo = $this->getConfigValue ( 'showUsersInfo', false );
		foreach ( UserHelper::getConnectedUsers () as $user ) {
			if ($showUserInfo)
				$html .= "<li><a href=\"" . BASEURL . "/index.php?section=userManager&task=userdetails&itemid={$user->getId()}\">{$user->getUsername()}</a></li>";
			else
				$html .= "<li>{$user->getUsername()}</li>";
		}
		$html .= "</ul>";
		return $html;
	}
}

?>