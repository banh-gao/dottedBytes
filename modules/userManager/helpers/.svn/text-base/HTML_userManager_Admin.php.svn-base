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

use OOForm\elements\table\Table;

use dottedBytes\modules\userManager\helpers\admin\ConnectedUsersTableModel;

use dottedBytes\libs\html\form\HtmlArea;

use OOForm\elements\LabeledElement;

use OOForm\elements\basic\SelectOption;

use OOForm\elements\basic\Select;

use OOForm\elements\basic\Checkbox;

use dottedBytes\libs\html\form\LanguageSelect;

use OOForm\elements\basic\Password;

use OOForm\elements\basic\Text;

use OOForm\elements\basic\Hidden;

use dottedBytes\libs\html\toolbar\ToolbarButton;

use dottedBytes\libs\html\toolbar\SubmitButton;

use dottedBytes\libs\users\GroupUtils;

use dottedBytes\libs\modules\ComponentException;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\pageBuilder\Resources;

use dottedBytes\libs\modules\ModUtils;

use OOForm\elements\HtmlElement;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\utils\collections\ObjectList;

use dottedBytes\libs\html\PageNavigation;

use dottedBytes\libs\html\form\Separator;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\io\FileUtils;

use PDO;

use geolocation;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

FileUtils::loadFile ( 'libs/users/geolocation' );

class HTML_userManager_Admin {
	
	public static function homeAdmin() {
		$database = DBManager::getInstance ();
		$form = new Form ();
		
		//// Count connected users ////////////////////////
		$totalUsers = UserHelper::getDistinctUsersCount ( true ) + UserHelper::getDistinctGuestsCount ( true );
		$pn = new PageNavigation ( $totalUsers, "index.php?section=userManager&task=admin" );
		
		$tableModel = new ConnectedUsersTableModel ( $pn );
		
		$table = new Table ( '', $tableModel );
		
		$form->addElement ( $table );
		$form->addElement ( new HtmlElement ( '', $pn->getMenu () ) );
		
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}
	
	public static function ipLookup() {
		$geolocation = new geolocation ( true );
		$ip = PageData::getParam ( 'ip', 0 );
		$geolocation->setIP ( $ip );
		$geolocation->setDomain ( $ip );
		
		$geolocation->setTimeout ( 5 );
		
		$locations = $geolocation->getGeoLocation ();
		$errors = $geolocation->getErrors ();
		$content = new Content ( USERMANAGER_IPLOOKUP );
		$form = new Form ();
		
		$form->addElement ( new HtmlElement ( USERMANAGER_IP, $locations [0] ['Ip'] ) );
		$form->addElement ( new HtmlElement ( USERMANAGER_HOSTNAME, gethostbyaddr ( $locations [0] ['Ip'] ) ) );
		$form->addElement ( new HtmlElement ( USERMANAGER_COUNTRYNAME, $locations [0] ['CountryName'] ) );
		$form->addElement ( new HtmlElement ( USERMANAGER_COUNTRYCODE, $locations [0] ['CountryCode'] ) );
		$form->addElement ( new HtmlElement ( USERMANAGER_REGIONNAME, $locations [0] ['RegionName'] ) );
		$form->addElement ( new HtmlElement ( USERMANAGER_REGIONCODE, $locations [0] ['RegionCode'] ) );
		$form->addElement ( new HtmlElement ( USERMANAGER_CITY, $locations [0] ['City'] ) );
		$form->addElement ( new HtmlElement ( USERMANAGER_ZIPCODE, $locations [0] ['ZipPostalCode'] ) );
		$form->addElement ( new HtmlElement ( USERMANAGER_LATITUDE, $locations [0] ['Latitude'] ) );
		$form->addElement ( new HtmlElement ( USERMANAGER_LONGITUDE, $locations [0] ['Longitude'] ) );
		$form->addElement ( new HtmlElement ( 'Google Map', '<div style="width: 500px; height: 300px" id="ip_map">Your browser doesn\'t support Google Maps or Javascript is turned off.</div>' ) );
		
		if (! empty ( $errors ) && is_array ( $errors )) {
			$form->addElement ( new Separator ( USERMANAGER_LOOKUP_ERROR ) );
			foreach ( $errors as $error ) {
				$form->addElement ( new HtmlElement ( '', $error ) );
			}
		}
		
		exec ( "/usr/bin/whois $ip", $out );
		$out = implode ( "<br/>", $out );
		$form->addElement ( new HtmlElement ( USERMANAGER_WHOIS, $out ) );
		
		$content->addData ( $form->render () );
		
		$apiKey = ModUtils::getCurrentModule ()->getConfigValue ( 'admin.googleKey', '' );
		PageData::addHeader ( '<script src="http://maps.google.com/maps?file=api&v=2&key=' . $apiKey . '" type="text/javascript"></script>' );
		
		$info = USERMANAGER_IP . ': <strong>' . $locations [0] ['Ip'] . '</strong><br/>';
		$info .= USERMANAGER_CITY . ': <strong>' . $locations [0] ['City'] . '</strong><br/>';
		$info .= USERMANAGER_LATITUDE . ': <strong>' . $locations [0] ['Latitude'] . '</strong><br/>';
		$info .= USERMANAGER_LONGITUDE . ': <strong>' . $locations [0] ['Longitude'] . '</strong>';
		
		$content->addData ( '<script type="text/javascript">
        if (GBrowserIsCompatible()) { 
            var map = new GMap2(document.getElementById("ip_map"));                 
            map.addControl(new GSmallZoomControl3D());
            
            map.addControl(new GLargeMapControl3D());
            map.addControl(new GMapTypeControl());
            var point = new GLatLng(' . $locations [0] ['Latitude'] . ',' . $locations [0] ['Longitude'] . '); 
            map.setCenter(point, 5);
            var marker = new GMarker(point);
            map.addOverlay(marker);
            marker.openInfoWindowHtml("' . $info . '");
            GEvent.addListener(marker, "click", function() {
            	marker.openInfoWindowHtml("' . $info . '");
          	});
        }</script>' );
		return $content;
	}
	
	public static function listUsers() {
		$database = DBManager::getInstance ();
		
		$result = $database->query ( "SELECT * FROM #__users" );
		$pn = new PageNavigation ( $result->rowCount (), "index.php?section=userManager&task=admin&page=listUsers" );
		
		$database->query ( "SELECT * FROM #__users" );
		$query = "SELECT uid FROM #__users LIMIT {$pn->getGlobalStart()}, {$pn->getRowLimit()}";
		
		$result = $database->query ( $query );
		$total = $result->rowCount ();
		$rows = $result->fetchAll ();
		
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "userManger" ) );
		$form->addElement ( new Hidden ( "task", "admin" ) );
		$form->addElement ( new Hidden ( 'page', 'listUsers' ) );
		
		$pn->getHeader ();
		
		$form->addHTML ( '<tr><td><table class="list"><tr><th width="3%">#</th><th width="3%">' );
		$form->addHTML ( Resources::getSysIcon ( 'user_delete', 1, USERMANAGER_USERS_DELETE ) );
		$form->addHTML ( '</th><th width="40%">' . USERMANAGER_USERNAME . '</th><th width="30%">' );
		$form->addHTML ( USERMANAGER_NAME . '</th><th width="24%">' . USERMANAGER_REGISTRATIONDATE );
		$form->addHTML ( '</th></tr>' );
		if ($total == 0) {
			$form->addHTML ( "<tr class=\"empty\"><td colspan='5'>" . _SITE_EMPTY . "</td></tr></table>" );
		} else {
			foreach ( $rows as $row ) {
				$pageRow = $pn->current ();
				$user = UserUtils::getUser ( $row->uid );
				$form->addHTML ( "<tr {$pageRow->getStyleID()}>" );
				$form->addHTML ( "<td class=\"index\">{$pageRow->getGlobalID()}</td>" );
				$form->addHTML ( "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=admin&page=delete&id={$user->getId()}\">" . Resources::getSysIcon ( 'user_delete', 1, USERMANAGER_USERS_DELETE ) . "</a></td>" );
				$form->addHTML ( "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=admin&page=userDetails&uid={$user->getId()}\">{$user->getUsername()}</a></td>" );
				$form->addHTML ( "<td>{$user->getName()}</td>" );
				$form->addHTML ( "<td>" . LocaleUtils::time ( $user->getRegDate () ) . "</td>" );
				$form->addHTML ( "</tr>" );
				$pn->next ();
			}
			$form->addHTML ( '<tr class="menu"><td colspan="5">' . $pn->getMenu () . '</td></tr></table>' );
		}
		
		$form->addHTML ( '<br /></td></tr>' );
		$form->closeForm ();
		$content = new Content ();
		$content->addData ( $form->getHTML () );
		return $content;
	}
	
	public static function edit_form() {
		$uid = PageData::getParam ( 'uid', 0 );
		$user = UserUtils::getUser ( $uid );
		if ($user->getId () == 0) {
			throw new ComponentException ( "User with ID $uid not found.", 0, USERMANAGER_USERS_NOTFOUND );
		}
		
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "save" ) );
		
		$nameField = new Text ( "name", USERMANAGER_NAME );
		$nameField->setValue ( $user->getName () );
		$form->addElement ( $nameField );
		
		$userField = new Text ( "username", USERMANAGER_USERNAME );
		$userField->setValue ( $user->getUsername () );
		$form->addElement ( $userField );
		
		$emailField = new Text ( "email", USERMANAGER_EMAIL );
		$emailField->setValue ( $user->getEmail () );
		$form->addElement ( $emailField );
		
		$form->addElement ( new Password ( "newpass", USERMANAGER_PASSWORD ) );
		$form->addElement ( new Password ( "newpass2", USERMANAGER_PASSWORD2 ) );
		
		$groupSelect = new Select ( 'group', $user->getGroup ()->getID (), USERMANAGER_GROUP );
		foreach ( GroupUtils::getGroups () as $group ) {
			$groupSelect->addOption ( new SelectOption ( $group->getName (), $group->getID () ) );
		}
		$form->addElement ( $groupSelect );
		
		$form->addElement ( new LanguageSelect ( "user_lang", USERMANAGER_USERLANG ) );
		
		$regField = new Text ( '', USERMANAGER_REGISTRATIONDATE );
		$regField->setValue ( LocaleUtils::time ( $user->getRegDate () ) );
		$form->addElement ( $regField );
		
		$visitField = new Text ( '', USERMANAGER_VISITDATE );
		$visitField->setValue ( LocaleUtils::time ( $user->getVisitDate () ) );
		$form->addElement ( $visitField );
		
		if (ModUtils::getCurrentModule ()->getConfigValue ( 'account.enableOpenID', false )) {
			$database = DBManager::getInstance ();
			$result = $database->query ( "SELECT * FROM #__users_openID WHERE uid={$user->getId()}" );
			if ($result->rowCount () < 1) {
				$url = '';
				$active = false;
			} else {
				//OpenID settings
				$row = $result->fetch ();
				$url = $row->identifier;
				$active = true;
			}
			
			$form->addElement ( new Separator ( Resources::getBigIcon ( 'openID', 1 ) . USERMANAGER_OPENID_LOGIN ) );
			$form->addElement ( new HtmlElement ( '', USERMANAGER_OPENID_INFO ) );
			
			$oidActive = new Checkbox ( 'oID_active', USERMANAGER_OPENID_ENABLE );
			$oidActive->setChecked ( $active );
			$form->addElement ( $oidActive );
			
			$oidProvider = new Text ( 'oID_provider', USERMANAGER_OPENID_PROVIDER );
			$oidProvider->setValue ( $url );
			$form->addElement ( $oidProvider );
		}
		
		PageData::addToolbarButton ( new SubmitButton ( $form->getFormID () ) );
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}
	
	public static function readPM_form() {
		$PMid = ( int ) PageData::getParam ( 'id', 0 );
		$database = DBManager::getInstance ();
		
		$query = "SELECT pm.subject , pm.message , pm.date , pm.fromID , pm.toID";
		$query .= " FROM #__users_pm AS pm , #__users AS userT";
		$query .= " WHERE AND pm.fromID=userT.uid AND pm.id={$PMid}";
		$result = $database->query ( $query );
		if ($result->rowCount () != 1) {
			throw new UserManagerException ( "Message with ID $PMid not found", 0, USERMANAGER_ACCOUNT_PM_NOTFOUND );
		}
		$row = $result->fetch ();
		$sender = UserUtils::createUser ( $row->fromID );
		
		if ($row->toID == UserUtils::getCurrentUser ()->getId ()) {
			$itemid = PageData::getParam ( 'itemid' );
			PageData::addToolbarButton ( new ToolbarButton ( USERMANAGER_ACCOUNT_PM_DELETE, 'index.php?section=userManager&task=pm_delete&itemid=' . $itemid, 'delete' ) );
			PageData::addToolbarButton ( new ToolbarButton ( USERMANAGER_ACCOUNT_PM_REPLY, 'index.php?section=userManager&task=pm_new&recipient=' . $sender->getId () . '&subject=Re:' . $row->subject, 'mail_send' ) );
		}
		$form = new Form ();
		$content = new Content ( $row->subject );
		$form->addElement ( new HtmlElement ( sprintf ( USERMANAGER_ACCOUNT_PM_FROM, '<a href="' . BASEURL . '/index.php?section=userManager&task=userdetails&itemid=' . $sender->getId () . '">' . $sender->getUsername () . "</a>", LocaleUtils::time ( $row->date ) ) ) );
		$form->addElement ( new HtmlArea ( html_entity_decode ( $row->message ) ) );
		
		$content->addData ( $form->render () );
		return $content;
	}
	
	public static function listPM_form() {
		$database = DBManager::getInstance ();
		
		$result = $database->query ( "SELECT * FROM #__users_pm" );
		$pn = new PageNavigation ( $result->rowCount (), "index.php?section=userManager&task=pm" );
		
		$database->query ( "SELECT * FROM #__users_pm" );
		$query = "SELECT pm.id , pm.fromID , pm.subject , pm.date , pm.isread , users.username";
		$query .= " FROM #__users_pm AS pm , #__users AS users";
		$query .= " WHERE pm.fromID = users.uid LIMIT {$pn->getGlobalStart()}, {$pn->getRowLimit()}";
		
		$result = $database->query ( $query );
		$total = $result->rowCount ();
		$rows = $result->fetchAll ();
		
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "userManger" ) );
		$form->addElement ( new Hidden ( "task", "pm" ) );
		$result = $database->query ( "SELECT * FROM #__users_pm WHERE isread=0 " );
		$new = $result->rowCount ();
		$form->addElement ( new Separator ( USERMANAGER_ACCOUNT_PM_RECEIVED . " ($new " . USERMANAGER_ACCOUNT_PM_UNREAD . ")" ) );
		$pn->getHeader ();
		
		$html = '<tr><td><table class="list"><tr><th width="3%">#</th><th width="3%">';
		$html .= Resources::getSysIcon ( 'email_delete', 1, USERMANAGER_ACCOUNT_PM_DELETE );
		$html .= '</th><th width="40%">' . USERMANAGER_ACCOUNT_PM_SUBJECT . '</th><th width="30%">';
		$html .= USERMANAGER_ACCOUNT_PM_SENDER . '</th><th width="24%">' . USERMANAGER_ACCOUNT_PM_DATE;
		$html .= '</th></tr>';
		if ($total == 0) {
			$html .= "<tr class=\"empty\"><td colspan='5'>" . USERMANAGER_ACCOUNT_PM_EMPTY . "</td></tr></table>";
		} else {
			foreach ( $rows as $row ) {
				$pageRow = $pn->current ();
				$title = ($row->isread == 0) ? "<strong>" . $row->subject . "</strong>" : $row->subject;
				$html .= "<tr {$pageRow->getStyleID()}>";
				$html .= "<td class=\"index\">{$pageRow->getGlobalID()}</td>";
				$html .= "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=pm_delete&itemid=$row->id\">" . Resources::getSysIcon ( 'email_delete', 1, USERMANAGER_ACCOUNT_PM_DELETE ) . "</a></td>";
				$html .= "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=pm_read&itemid=$row->id\">$title</a></td>";
				$html .= "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=userDetails&itemid=$row->fromID\">$row->username</a></td>";
				$html .= "<td>" . LocaleUtils::time ( $row->date ) . "</td>";
				$html .= "</tr>";
				$pn->next ();
			}
			$form->addHTML ( '<tr class="menu"><td colspan="5">' . $pn->getMenu () . '</td></tr></table>' );
		}
		
		$html .= '<br /></td></tr>';
		
		$form->addElement ( new HtmlElement ( '', $html ) );
		
		$result = $database->query ( "SELECT * FROM #__users_pm" );
		$pn = new PageNavigation ( $result->rowCount (), "index.php?section=userManager&task=pm" );
		
		$query = "SELECT pm.id , pm.subject , pm.date , pm.isread , pm.toID";
		$query .= " FROM #__users_pm AS pm LIMIT {$pn->getGlobalStart()}, {$pn->getRowLimit()}";
		
		$result = $database->query ( $query );
		$total = $result->rowCount ();
		
		$rows = $result->fetchAll ();
		$form->addElement ( new Separator ( USERMANAGER_ACCOUNT_PM_SENT ) );
		$html = '<tr><td><table class="list" style="overflow: auto;"><tr><th width="3%">#</th>';
		$html .= '<th width="3%">' . USERMANAGER_ACCOUNT_PM_ISREAD . '</th><th width="40%">';
		$html .= USERMANAGER_ACCOUNT_PM_SUBJECT . '</th><th width="30%">' . USERMANAGER_ACCOUNT_PM_RECIPIENT;
		$html .= '</th><th width="27%">' . USERMANAGER_ACCOUNT_PM_DATE . '</th></tr>';
		if ($total == 0) {
			$html .= "<tr class=\"empty\"><td colspan='5'>" . USERMANAGER_ACCOUNT_PM_EMPTY . "</td></tr></table>";
		} else {
			foreach ( $rows as $row ) {
				$pageRow = $pn->current ();
				$html .= "<tr {$pageRow->getStyleID()}>";
				$html .= "<td class=\"index\">{$pageRow->getGlobalID()}</td>";
				$html .= '<td>' . Resources::getSysIcon ( 'bool', '', '', $row->isread ) . "</td>";
				$html .= "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=pm_read&itemid=$row->id\">$row->subject</a></td>";
				$html .= "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=userdetails&itemid=$row->toID\">" . UserUtils::getUser ( $row->toID )->getUsername () . "</a></td>";
				$html .= '<td>' . LocaleUtils::time ( $row->date ) . "</td>";
				$html .= "</tr>";
				$pn->next ();
			}
			$html .= '<tr class="menu"><td colspan="5">' . $pn->getMenu () . '</td></tr></table>';
		}
		$html .= '</td></tr>';
		$form->addElement ( new HtmlElement ( '', $html ) );
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}
	
	public static function userDetails() {
		$uid = ( int ) PageData::getParam ( 'uid', 0 );
		$sessionID = PageData::getParam ( 'sid', 0 );
		
		if ($uid == 0)
			$info = UserUtils::getConnectedUser ( $sessionID );
		else
			$info = UserUtils::getUser ( $uid );
		
		$form = new Form ();
		
		//Show session info
		if ($info->getExpire () > 0) {
			$form->addElement ( new Separator ( USERMANAGER_SESSIONINFO ) );
			$form->addElement ( new HtmlElement ( USERMANAGER_IP, $info->getIP () ) );
			$form->addElement ( new HtmlElement ( USERMANAGER_CLIENT, $info->getClient () ) );
			$form->addElement ( new HtmlElement ( USERMANAGER_HIDDEN, Resources::getSysIcon ( 'bool', '', '', $info->getHidden () ) ) );
			$form->addElement ( new HtmlElement ( USERMANAGER_EXPIRE, LocaleUtils::time ( $info->getExpire () ) ) );
			$form->addElement ( new HtmlElement ( USERMANAGER_LANGUAGE, $info->getLanguage () ) );
			$form->addElement ( new HtmlElement ( USERMANAGER_URL, $info->getUrl () ) );
			$form->addElement ( new HtmlElement ( USERMANAGER_REFERER, $info->getReferer () ) );
		}
		
		//Show user info
		if ($uid != 0) {
			$form->separator ( USERMANAGER_DETAILS );
			$form->addElement ( new HtmlElement ( USERMANAGER_USERNAME, $info->getUsername () ) );
			$form->addElement ( new HtmlElement ( USERMANAGER_GROUP, $info->getGroup ()->getName () ) );
			$form->addElement ( new HtmlElement ( USERMANAGER_REGISTRATIONDATE, LocaleUtils::time ( $info->getRegDate (), 0, - 1 ) ) );
		}
		
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}
}
?>