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

// no direct access
use OOForm\elements\table\Table;

use dottedBytes\libs\html\form\HtmlArea;

use OOForm\elements\editor\Editor;

use OOForm\elements\ajax\Suggestion;

use OOForm\elements\group\CheckboxGroup;

use dottedBytes\libs\html\PageNavigation;

use dottedBytes\libs\html\toolbar\ToolbarButton;

use dottedBytes\libs\html\toolbar\SubmitButton;

use dottedBytes\libs\users\User;

use OOForm\FormRequest;

use OOForm\elements\group\ElementGroup;

use OOForm\elements\basic\Checkbox;

use OOForm\elements\basic\Textarea;

use dottedBytes\libs\html\form\Captcha;

use dottedBytes\libs\html\form\LanguageSelect;

use OOForm\elements\basic\SelectOption;

use OOForm\elements\basic\Select;

use OOForm\elements\basic\Button;

use dottedBytes\libs\html\form\Separator;

use OOForm\elements\HtmlElement;

use OOForm\elements\basic\Password;

use OOForm\elements\basic\Submit;

use OOForm\elements\basic\Text;

use dottedBytes\modules\userManager\authSystems\openID\OpenIDException;

use dottedBytes\modules\userManager\authSystems\openID\OpenIDUtils;

use OOForm\elements\basic\Hidden;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\modules\ComponentException;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\pageBuilder\Resources;
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

class HTML_userManager {
	
	public static function login_form() {
		$form = new Form ( 'login' );
		
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "login" ) );
		$form->addElement ( new Hidden ( 'referer', urlencode ( PageData::getParam ( 'referer', BASEURL . '/index.php?section=userManager' ) ) ) );
		$form->addElement ( new Text ( "username", USERMANAGER_USERNAME ) );
		$form->addElement ( new Password ( "password", USERMANAGER_PASSWORD ) );
		$form->addElement ( new Submit ( USERMANAGER_LOGIN ) );
		if (ModUtils::getCurrentModule ()->getConfigValue ( 'registration.enableRecovery', false ))
			$form->addElement ( new HtmlElement ( '', '<a href="' . BASEURL . '/index.php?section=userManager&task=pwdforgot">' . USERMANAGER_REGISTRATION_FORGOT . '</a><br/>' ) );
		
		if (ModUtils::getCurrentModule ()->getConfigValue ( 'registration.enable', false ))
			$form->addElement ( new HtmlElement ( '', '<a href="' . BASEURL . '/index.php?section=userManager&task=registration">' . USERMANAGER_REGISTRATION . '</a>' ) );
		
		$content = new Content ();
		
		$content->addData ( $form->render () );
		
		//OpenID login
		if (ModUtils::getCurrentModule ()->getConfigValue ( 'account.enableOpenID', false )) {
			$html = self::getOpenIDForm ();
			$content->addData ( $html );
		}
		return $content;
	}
	
	private static function getOpenIDForm() {
		$form = new Form ();
		
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "login" ) );
		$form->addElement ( new Hidden ( "act", "openID" ) );
		$form->addElement ( new Hidden ( 'referer', urlencode ( PageData::getParam ( 'referer', BASEURL . '/index.php?section=userManager' ) ) ) );
		$form->addElement ( new Separator ( USERMANAGER_OPENID_LOGIN , 'openID') );
		$image = Resources::getSysIcon ( 'openID' );
		$openIDbox = new Text ( 'openid_identifier', USERMANAGER_OPENID_PROVIDER );
		$openIDbox->setAttribute ( "style", 'background-image:url(' . $image . ');background-repeat:no-repeat;background-position: left center;padding-left:18px;width:300px;' );
		$form->addElement ( $openIDbox );
		$form->addElement ( new Submit ( USERMANAGER_LOGIN ) );
		return $form->render ();
	}
	
	public static function registration_form() {
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "registration_complete" ) );
		
		if (ModUtils::getCurrentModule ()->getConfigValue ( 'account.enableOpenID', false )) {
			$fastRegButton = new Button ( 'fastReg', USERMANAGER_REGISTRATION_SREG_BUTTON );
			$icon = Resources::getSysIcon ( 'openID' );
			$fastRegButton->setAttribute ( 'style', "background-image:url('$icon');background-repeat:no-repeat;padding-left:20px" );
			$fastRegButton->setAttribute ( 'onClick', "location.href='index.php?section=userManager&task=fastRegistration'" );
			$form->addElement ( $fastRegButton );
		}
		$form->addElement ( new Text ( 'name', USERMANAGER_NAME ) );
		$form->addElement ( new Text ( 'user', USERMANAGER_USERNAME ) );
		$form->addElement ( new Text ( 'email', USERMANAGER_EMAIL ) );
		
		$passwordBox = new Password ( 'password', USERMANAGER_PASSWORD );
		$passwordBox->setTooltip ( USERMANAGER_REGISTRATION_CHECKPASS );
		$form->addElement ( $passwordBox );
		$form->addElement ( new Password ( 'password2', USERMANAGER_PASSWORD2 ) );
		
		$form->addElement ( new LanguageSelect ( 'language', USERMANAGER_USERLANG ) );
		
		$form->addElement ( new Captcha () );
		
		self::addPrivacyField ( $form );
		$form->addElement ( new Submit ( USERMANAGER_REGISTRATION ) );
		
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}
	
	public static function fast_registration_form() {
		$form = new Form ( 'openID' );
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "fastRegistrationRequest" ) );
		$form->addElement ( new HtmlElement ( '', USERMANAGER_OPENID_INFO ) );
		
		$idBox = new Text ( 'openid_identifier', USERMANAGER_OPENID_PROVIDER );
		$image = Resources::getSysIcon ( 'openID');
		$idBox->setAttribute ( 'style', "background-image:url('$image');background-repeat:no-repeat;background-position: left center;padding-left:18px;width:300px;" );
		$form->addElement ( $idBox );
		
		$loadInfo = new Button ( '', USERMANAGER_REGISTRATION_SREG_GETINFO );
		$loadInfo->setAttribute ( 'onClick', "this.form.submit()" );
		
		$form->addElement ( $loadInfo );
		
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}
	
	private static function addPrivacyField(Form $form) {
		$privacyPath = ModUtils::getCurrentModule ()->getBasePath () . "/privacy/privacy_" . UserUtils::getCurrentUser ()->getISOLanguage () . '.html';
		if (! file_exists ( $privacyPath )) {
			$privacyPath = ModUtils::getCurrentModule ()->getBasePath () . "/privacy/privacy.html";
			if (! file_exists ( $privacyPath )) {
				throw new ComponentException ( 'Privacy document not found in privacy directory', 0 );
			}
		}
		
		$privacy = file_get_contents ( $privacyPath );
		$privacyArea = new HtmlArea ( USERMANAGER_REGISTRATION_PRIVACY, $privacy );
		$form->addElement ( $privacyArea );
		$form->addElement ( new Checkbox ( 'privacy', USERMANAGER_REGISTRATION_PRIVACY_ACCEPT ) );
	}
	
	public static function fast_registration_filled_form() {
		$form = new Form ();
		$form->setFormPage ( BASEURL . '/index.php?section=userManager&task=fastRegistrationFilled' );
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "registration_complete" ) );
		
		//Catch a fast registration response
		try {
			$requestUrl = BASEURL . '/index.php?section=userManager&task=fastRegistration';
			$request = new FormRequest ();
			$request->setFormPage ( $requestUrl );
			$request->setFormID ( 'openID' );
			
			$user = OpenIDUtils::catchOpenIDResponse ( $requestUrl );
			$identifier = $user->getAttribute ( 'openID' )->getUrl ();
			$form->addElement ( new Hidden ( 'openid_identifier', $identifier ) );
			$form->addElement ( new HtmlElement ( USERMANAGER_OPENID_PROVIDER, $identifier ) );
			//User already registered
			if ($user->getId () != 0) {
				$request->setError ( 'openid_identifier', sprintf ( USERMANAGER_REGISTRATION_SREG_NOTAVAILABLE, $identifier ) );
				$request->sendErrors ();
			}
		} catch ( OpenIDException $e ) {
			$request->setError ( 'openid_identifier', $e->getMessage () );
			$request->sendErrors ();
		}
		
		$nameField = new Text ( "name", USERMANAGER_NAME );
		$nameField->setValue ( $user->getName () );
		$form->addElement ( $nameField );
		
		$usernameField = new Text ( "user", USERMANAGER_USERNAME );
		$usernameField->setValue ( $user->getUsername () );
		$form->addElement ( $usernameField );
		
		$emailField = new Text ( "email", USERMANAGER_EMAIL );
		$emailField->setValue ( $user->getEmail () );
		$form->addElement ( $emailField );
		$form->addElement ( new LanguageSelect ( 'language', USERMANAGER_USERLANG, $user->getISOLanguage () ) );
		self::addPrivacyField ( $form );
		$form->addElement ( new Submit ( USERMANAGER_REGISTRATION ) );
		
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}
	
	public static function resend_email_form(User $user) {
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "registration_newmail" ) );
		//Use session to avoid manual modifications
		$_SESSION ["resendUID"] = $user->getId ();
		$form->addElement ( new Separator ( sprintf ( USERMANAGER_REGISTRATION_RESEND, $user->getEmail () ) ) );
		$form->addElement(new Captcha());
		$form->addElement ( new Submit ( USERMANAGER_REGISTRATION_RESEND_BUTTON ) );
		
		PageData::clearBreadcrubs ();
		PageData::addToBreadcrubs ( USERMANAGER_REGISTRATION );
		$content = new Content ( USERMANAGER_REGISTRATION, 'user_add' );
		$content->addData ( $form->render () );
		return $content;
	}
	
	public static function forgot_form() {
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "send_pwd" ) );
		$form->addElement ( new Text ( "user", USERMANAGER_USERNAME ) );
		$form->addElement ( new Text ( "email", USERMANAGER_EMAIL ) );
		$form->addElement ( new Captcha () );
		$form->addElement ( new Submit ( USERMANAGER_FORGOT_PWD ) );
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}
	
	public static function edit_form() {
		$user = UserUtils::getCurrentUser ();
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "save" ) );
		
		$nameBox = new Text ( "name", USERMANAGER_NAME );
		$nameBox->setValue ( $user->getName () );
		$form->addElement ( $nameBox );
		
		$userBox = new Text ( '', USERMANAGER_USERNAME );
		$userBox->setValue ( $user->getUsername () );
		$userBox->setReadonly ( true );
		$form->addElement ( $userBox );
		
		$mailBox = new Text ( "email", USERMANAGER_EMAIL );
		$mailBox->setValue ( $user->getEmail () );
		$mailBox->setReadonly ( true );
		$form->addElement ( $mailBox );
		
		$mailButton = new Button ( '', USERMANAGER_CHANGEEMAIL_EDIT );
		$mailButton->setAttribute ( 'onClick', "document.location.href='index.php?section=userManager&task=edit_email'" );
		$form->addElement ( $mailButton );
		
		$form->addElement ( new Password ( "newpass", USERMANAGER_PASSWORD ) );
		$form->addElement ( new Password ( "newpass2", USERMANAGER_PASSWORD2 ) );
		
		$groupBox = new Text ( '', USERMANAGER_GROUP );
		$groupBox->setValue ( $user->getGroup ()->getName () );
		$groupBox->setReadonly ( true );
		$form->addElement ( $groupBox );
		
		$form->addElement ( new LanguageSelect ( "user_lang", USERMANAGER_USERLANG ) );
		
		$regBox = new Text ( '', USERMANAGER_REGISTRATIONDATE );
		$regBox->setValue ( LocaleUtils::time ( $user->getRegDate () ) );
		$regBox->setReadonly ( true );
		$form->addElement ( $regBox );
		
		$lastBox = new Text ( '', USERMANAGER_VISITDATE );
		$lastBox->setValue ( LocaleUtils::time ( $user->getVisitDate () ) );
		$lastBox->setReadonly ( true );
		$form->addElement ( $lastBox );
		
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
	
	public static function edit_email_form() {
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "save_email" ) );
		$form->addElement ( new HtmlElement ( '', USERMANAGER_CHANGEEMAIL_EDIT_TEXT ) );
		$form->addElement ( new Text ( "email", USERMANAGER_CHANGEEMAIL_EDIT_NEW ) );
		$form->addElement ( new Submit () );
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}
	
	public static function readPM_form($PMid) {
		$PMid = ( int ) $PMid;
		$database = DBManager::getInstance ();
		$user = UserUtils::getCurrentUser ();
		
		$query = "SELECT pm.subject , pm.message , pm.date , pm.fromID , pm.toID";
		$query .= " FROM #__users_pm AS pm , #__users AS userT";
		$query .= " WHERE (pm.toID={$user->getId()} OR pm.fromID={$user->getId()})";
		$query .= " AND pm.fromID=userT.uid";
		$query .= " AND pm.id={$PMid}";
		$result = $database->query ( $query );
		if ($result->rowCount () != 1) {
			throw new UserManagerException ( "Message with ID $PMid not found", 0, USERMANAGER_ACCOUNT_PM_NOTFOUND );
		}
		$row = $result->fetch ();
		$sender = UserUtils::getUser ( $row->fromID );
		
		if ($row->toID == UserUtils::getCurrentUser ()->getId ()) {
			$itemid = PageData::getParam ( 'itemid' );
			PageData::addToolbarButton ( new ToolbarButton ( USERMANAGER_ACCOUNT_PM_DELETE, 'index.php?section=userManager&task=pm_delete&itemid=' . $itemid, 'delete' ) );
			PageData::addToolbarButton ( new ToolbarButton ( USERMANAGER_ACCOUNT_PM_REPLY, 'index.php?section=userManager&task=pm_new&recipient=' . $sender->getId () . '&subject=Re:' . $row->subject, 'mail_send' ) );
		}
		$form = new Form ();
		$content = new Content ( $row->subject );
		$form->addElement ( new HtmlElement ( '', sprintf ( USERMANAGER_ACCOUNT_PM_FROM, '<a href="' . BASEURL . '/index.php?section=userManager&task=userdetails&itemid=' . $sender->getId () . '">' . $sender->getUsername () . "</a>", LocaleUtils::time ( $row->date ) ) ) );
		$form->addElement ( new HtmlElement ( '', html_entity_decode ( $row->message ) ) );
		$content->addData ( $form->render () );
		
		//Set message as readed
		$database->query ( "UPDATE #__users_pm SET isread=1 WHERE id =$PMid AND toID={$user->getId()}" );
		return $content;
	}
	
	public static function listPM_form() {
		$database = DBManager::getInstance ();
		$user = UserUtils::getCurrentUser ();
		
		$result = $database->query ( "SELECT * FROM #__users_pm WHERE toid='{$user->getId()}'" );
		$pn = new PageNavigation ( $result->rowCount (), "index.php?section=userManager&task=pm" );
		
		$database->query ( "SELECT * FROM #__users_pm WHERE toid='" . $user->getId () . "'" );
		$query = "SELECT pm.id , pm.fromID , pm.subject , pm.date , pm.isread , users.username";
		$query .= " FROM #__users_pm AS pm , #__users AS users";
		$query .= " WHERE pm.toID ='{$user->getId()}'";
		$query .= " AND pm.fromID = users.uid LIMIT {$pn->getGlobalStart()}, {$pn->getRowLimit()}";
		
		$result = $database->query ( $query );
		$total = $result->rowCount ();
		$rows = $result->fetchAll ();
		
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "userManger" ) );
		$form->addElement ( new Hidden ( "task", "pm" ) );
		$result = $database->query ( "SELECT * FROM #__users_pm WHERE toID={$user->getId()} AND isread=0 " );
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
				$html .= "<tr {$pageRow->getStyleID()}>\n";
				$html .= "<td class=\"index\">{$pageRow->getGlobalID()}</td>\n";
				$html .= "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=pm_delete&itemid=$row->id\">" . Resources::getSysIcon ( 'email_delete', 1, USERMANAGER_ACCOUNT_PM_DELETE ) . "</a></td>\n";
				$html .= "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=pm_read&itemid=$row->id\">$title</a></td>\n";
				$html .= "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=userdetails&itemid=$row->fromID\">$row->username</a></td>\n";
				$html .= "<td>" . LocaleUtils::time ( $row->date ) . "</td>\n";
				$html .= "</tr>\n";
				$pn->next ();
			}
			$html .= '<tr class="menu"><td colspan="5">' . $pn->getMenu () . '</td></tr></table>';
		}
		
		$html .= '<br/></td></tr>';
		
		$form->addElement ( new HtmlElement ( '', $html ) );
		
		$result = $database->query ( "SELECT * FROM #__users_pm WHERE fromID='{$user->getId()}'" );
		$pn = new PageNavigation ( $result->rowCount (), "index.php?section=userManager&task=pm" );
		
		$query = "SELECT pm.id , pm.subject , pm.date , pm.isread , pm.toID";
		$query .= " FROM #__users_pm AS pm WHERE pm.fromID={$user->getId()}";
		$query .= " LIMIT {$pn->getGlobalStart()}, {$pn->getRowLimit()}";
		
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
				$html .= "<tr {$pageRow->getStyleID()}>\n";
				$html .= "<td class=\"index\">{$pageRow->getGlobalID()}</td>\n";
				$html .= '<td>' . Resources::getSysIcon ( 'bool', '', '', $row->isread ) . "</td>\n";
				$html .= "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=pm_read&itemid=$row->id\">$row->subject</a></td>\n";
				$html .= "<td><a href=\"" . BASEURL . "/index.php?section=userManager&task=userdetails&itemid=$row->toID\">" . UserUtils::getUser ( $row->toID )->getUsername () . "</a></td>\n";
				$html .= '<td>' . LocaleUtils::time ( $row->date ) . "</td>\n";
				$html .= "</tr>\n";
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
	
	public static function newPM_form() {
		$itemid = ( int ) PageData::getParam ( 'itemid', 0 );
		$subject = PageData::getParam ( 'subject', NULL );
		$recipient = ( int ) PageData::getParam ( 'recipient', 0 );
		$message = PageData::getParam ( 'message', NULL );
		$users = UserHelper::user_list ();
		$userList = array ();
		foreach ( $users as $user ) {
			$userList [$user->getUsername ()] = $user->getId ();
		}
		
		if ($recipient != 0) {
			$database = DBManager::getInstance ();
			$result = $database->query ( "SELECT uid FROM #__users WHERE uid=$recipient" );
			$recipient = ($result->rowCount () == 1) ? array ($recipient ) : array ();
		
		}
		
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "userManager" ) );
		$form->addElement ( new Hidden ( "task", "pm_send" ) );
		
		$maxRec = ModUtils::getCurrentModule ()->getConfigValue ( 'pm.multipleLimit' );
		$recBox = new Suggestion ( 'recipient', USERMANAGER_ACCOUNT_PM_RECIPIENT, new PMRecipientSuggestion () );
		$recBox->setTooltip ( sprintf ( USERMANAGER_ACCOUNT_PM_RECIPIENT_TIP, $maxRec ) );
		$form->addElement ( $recBox );
		
		$subjectBox = new Text ( "subject", USERMANAGER_ACCOUNT_PM_SUBJECT );
		$subjectBox->setValue ( $subject );
		$form->addElement ( $subjectBox );
		
		$form->addElement ( new Editor ( 'message', $message, USERMANAGER_ACCOUNT_PM_MESSAGE ) );
		$form->addElement ( new Submit ( USERMANAGER_ACCOUNT_PM_SEND ) );
		
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}
	
	public static function userDetails($uid) {
		
		$info = UserUtils::getUser ( $uid );
		if ($info->getId () == 0) {
			throw new UserManagerException ( _SITE_ERROR );
		}
		
		$form = new Form ();
		$userBox = new Text ( '', USERMANAGER_USERNAME );
		$userBox->setValue ( $info->getUsername () );
		$userBox->setReadonly ( true );
		$form->addElement ( $userBox );
		
		$groupBox = new Text ( '', USERMANAGER_GROUP );
		$groupBox->setValue ( $info->getGroup ()->getName () );
		$groupBox->setReadonly ( true );
		$form->addElement ( $groupBox );
		
		$regBox = new Text ( '', USERMANAGER_REGISTRATIONDATE );
		$regBox->setValue ( LocaleUtils::time ( $info->getRegDate (), 0, - 1 ) );
		$regBox->setReadonly ( true );
		$form->addElement ( $regBox );
		$form->addElement ( new HtmlElement ( Resources::getSysIcon ( 'bool', '', '', $info->getExpire () ), USERMANAGER_CONNECTED ) );
		
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}
	
	public static function account_home() {
		$database = DBManager::getInstance ();
		$user = UserUtils::getCurrentUser ();
		$form = new Form ();
		
		$form->addElement ( new Separator ( USERMANAGER_ACCOUNT_ARTICLES ) );
		$articleTable = new Table ( '', new ArticlesTableModel ( $user->getId () ) );
		$form->addElement ( $articleTable );
		
		$form->addElement ( new Separator ( USERMANAGER_ACCOUNT_COMMENTS ) );
		$commentTable = new Table ( '', new CommentsTableModel ( $user->getId () ) );
		$commentTable->setEmptyMessage ( USERMANAGER_ACCOUNT_PM_EMPTY );
		$form->addElement ( $commentTable );
		
		$content = new Content ();
		$content->addData ( $form->render () );
		return $content;
	}

}
?>