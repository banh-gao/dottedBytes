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

use OOForm\validator\DomainValidator;

use dottedBytes\modules\userManager\authSystems\FormLogin;

use OOForm\validator\EmailValidator;

use OOForm\validator\UrlValidator;

use OOForm\validator\EmptyValidator;

use OOForm\validator\RegexValidator;

use dottedBytes\libs\html\form\Form;

use dottedBytes\modules\userManager\authSystems\openID\OpenIDLogin;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\io\Mail;

use dottedBytes\libs\configuration\Configuration;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\html\toolbar\ToolbarButton;

use dottedBytes\libs\utils\collections\ObjectSet;

use dottedBytes\libs\users\auth\PermissionException;

use dottedBytes\libs\users\auth\AuthException;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\utils\collections\ObjectList;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\database\DBManager;

use PDO;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class UserHelper {
	
	private static $connectedUsers = null;
	
	public static function login() {
		try {
			$user = UserUtils::getCurrentUser ();
			$authSystem = self::getAuthSystem ();
			$user->login ( $authSystem );
		} catch ( AuthException $e ) {
			if ($e instanceof PermissionException) {
				if (self::isActivationWaitingUser ( $e->getUid () )) {
					//Propose to resend validation email
					return HTML_userManager::resend_email_form ( UserUtils::getUser ( $e->getUid () ) );
				}
			}
			self::redirectInvalidUser ( $e );
		}
		self::redirectValidUser ();
	}
	
	private static function isActivationWaitingUser($uid) {
		if ($uid != 0) {
			$database = DBManager::getInstance ();
			$result = $database->query ( "SELECT uid FROM #__users WHERE uid='$uid' AND activation!=''" );
			if ($result->rowCount () == 1)
				return true;
		}
		return false;
	}
	
	private static function getAuthSystem() {
		$act = PageData::getParam ( 'act' );
		if ($act == 'openID' || $act == 'openID_confirm')
			return new OpenIDLogin ();
		else
			return new FormLogin ();
	}
	
	private static function redirectInvalidUser($exception) {
		$referer = PageData::getParam ( 'referer', false );
		PageData::redirect ( BASEURL . '/index.php?section=userManager&referer=' . $referer, $exception->getExceptionMessage () );
	}
	
	private static function redirectValidUser() {
		$user = UserUtils::getCurrentUser ();
		$referer = PageData::getParam ( 'referer', false );
		$referer = ($referer == false) ? $user->getReferer () : urldecode ( $referer );
		PageData::redirect ( $referer );
	}
	
	public static function logout() {
		$user = UserUtils::getCurrentUser ();
		$referer = $user->getReferer ();
		$user->logout ();
		PageData::redirect ( $referer, USERMANAGER_LOGGEDOUT );
	}
	
	private static function fetchConnectedClients() {
		if (self::$connectedUsers != null)
			return;
		
		self::$connectedUsers = new ObjectList ();
		
		$database = DBManager::getInstance ();
		$result = $database->query ( "SELECT uid,sessionID FROM #__session" );
		
		foreach ( $result->fetchAll () as $row ) {
			if ($row->uid == 0)
				$user = UserUtils::getConnectedUser ( $row->sessionID );
			else
				$user = UserUtils::getUser ( $row->uid );
			
			self::$connectedUsers->add ( $user );
		}
	}
	
	/**
	 * Return a set that contains the connected users
	 * @param $showHidden
	 * @return ObjectSet
	 */
	public static function getConnectedUsers($showHidden = false) {
		self::fetchConnectedClients ();
		
		$result = new ObjectSet ();
		foreach ( self::$connectedUsers as $user ) {
			if ($user->getId () != 0) {
				if ($user->getHidden ()) {
					if ($showHidden)
						$result->add ( $user );
				} else {
					$result->add ( $user );
				}
			}
		}
		return $result;
	}
	
	public static function getDistinctUsersCount($showHidden = false) {
		$distinctUsers = array ();
		foreach ( self::getConnectedUsers ( $showHidden ) as $user ) {
			if (! in_array ( $user->getIP (), $distinctUsers ))
				$distinctUsers [] = $user->getIP ();
		}
		return count ( $distinctUsers );
	}
	
	/**
	 * Return a list that contains the connected guests
	 * @param $showHidden
	 * @return ObjectList
	 */
	public static function getConnectedGuests($showHidden = false) {
		self::fetchConnectedClients ();
		
		$result = new ObjectList ();
		foreach ( self::$connectedUsers as $user ) {
			if ($user->getId () == 0) {
				if ($user->getHidden ()) {
					if ($showHidden)
						$result->add ( $user );
				} else {
					$result->add ( $user );
				}
			}
		}
		return $result;
	}
	
	public static function getDistinctGuestsCount($showHidden = false) {
		$distinctGuests = array ();
		foreach ( self::getConnectedGuests ( $showHidden ) as $guest ) {
			if (! in_array ( $guest->getIP (), $distinctGuests ))
				$distinctGuests [] = $guest->getIP ();
		}
		return count ( $distinctGuests );
	}
	
	public static function get_edit_form() {
		PageData::clearBreadcrubs ();
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT, 'index.php?section=userManager' );
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT_EDIT, 'index.php?section=userManager&task=edit_user' );
		$content = new Content ( USERMANAGER_ACCOUNT_EDIT, 'user_edit' );
		$content->addData ( HTML_userManager::edit_form () );
		return $content;
	}
	
	public static function get_email_form() {
		PageData::clearBreadcrubs ();
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT, 'index.php?section=userManager' );
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT_EDIT, 'index.php?section=userManager&task=edit_user' );
		PageData::addToBreadcrubs ( USERMANAGER_CHANGEEMAIL_EDIT, 'index.php?section=userManager&task=edit_email' );
		$content = new Content ( USERMANAGER_CHANGEEMAIL_EDIT, 'user_edit' );
		$content->addData ( HTML_userManager::edit_email_form () );
		return $content;
	}
	
	public static function confirmEmail() {
		if (UserHelper::confirm_new_email ( PageData::getParam ( 'valcode', '' ) )) {
			PageData::redirect ( 'index.php?section=userManager&task=edit_user', USERMANAGER_ACCOUNT_EDITED );
		} else {
			PageData::back ( _SITE_ERROR );
		}
	}
	
	public static function saveInfo() {
		$database = DBManager::getInstance ();
		$user = UserUtils::getCurrentUser ();
		$request = Form::getRequest ();
		$name = $request->getValue ( 'name', '', new RegexValidator ( UsernameValidator::USERNAME_PATTERN ) );
		
		//If password field is empty use the old password else check the 2 passwords
		if ($request->getValue( 'newpass', '' ) == '') {
			$password = $user->getPassword ();
		} else {
			$passValidator = new RegexValidator( RegistrationHelper::PASSWORD_PATTERN );
			$passValidator->setErrorMessage(USERMANAGER_REGISTRATION_CHECKPASS);
			$plainPass = $request->getValue( 'newpass', '', $passValidator );
			
			if ($plainPass !== $request->getValue( 'newpass2', '' ))
				$request->setError( "newpass2", USERMANAGER_REGISTRATION_CHECKEQUALPASS );
			else {
				$password = UserUtils::generatePassword ( $plainPass );
				self::sendNotification ( 'changePass', array ('name' => $name, 'username' => $user->getUsername (), 'pass' => $plainPass, 'email' => $user->getEmail () ) );
			}
		}
		
		$lang = $request->getValue( 'user_lang', '', new EmptyValidator() );
		
		$openIDActive = $request->getValue( 'oID_active', false );
		
		//Check for valid host
		if ($openIDActive !== false) {
			$openIDvalidator = new DomainValidator();
			$openIDvalidator->setErrorMessage(USERMANAGER_ACCOUNT_OPENID_IDERROR);
			$openIDProvider = $request->getValue('oID_provider', '' , $openIDvalidator);
		} else {
			$openIDProvider = '';
		}
		
		$request->sendErrors();
		
		$query = $database->prepare ( "UPDATE #__users SET name=? , password=? , language=? WHERE uid=?" );
		$query->bindParam ( 1, $name, PDO::PARAM_STR );
		$query->bindParam ( 2, $password, PDO::PARAM_STR );
		$query->bindParam ( 3, $lang, PDO::PARAM_STR );
		$uid = $user->getId ();
		$query->bindParam ( 4, $uid, PDO::PARAM_INT );
		$query->execute ();
		
		//Update openID informations
		if (ModUtils::getCurrentModule ()->getConfigValue ( 'account.enableOpenID', false )) {
			$query = $database->prepare ( "UPDATE #__users_openID SET identifier=? , active=? WHERE uid=?" );
			$query->bindParam ( 1, $openIDProvider, PDO::PARAM_STR );
			$query->bindParam ( 2, $openIDActive, PDO::PARAM_BOOL );
			$uid = $user->getId ();
			$query->bindParam ( 3, $uid, PDO::PARAM_INT );
			$query->execute ();
		}
		
		UserUtils::getCurrentUser ()->logActivity ( "Profile information updated", 'User account' );
		
		PageData::redirect ( BASEURL . '/index.php?section=userManager', USERMANAGER_ACCOUNT_EDITED );
	}
	
	public static function newMailRequest() {
		$request = Form::getRequest();
		$email = $request->getValue('email' , '' , new EmailValidator() );
		
		$request->sendErrors();
		
		if ($email == UserUtils::getCurrentUser ()->getEmail ())
			$request->setError( 'email', USERMANAGER_CHANGEEMAIL_EDIT_EXISTS );
		
		$request->sendErrors();
		
		if (self::changeEmail ( $email ))
			return sprintf ( USERMANAGER_CHANGEEMAIL_SENT, $email );
		else
			PageData::back ( _SITE_ERROR );
	}
	
	public static function getDetailsPage() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'showUsersInfo', false ))
			throw new UserManagerException ( 'Users information service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		PageData::clearBreadcrubs ();
		$itemid = PageData::getParam ( 'itemid' );
		$content = new Content ( USERMANAGER_DETAILS, 'info' );
		if ($itemid != UserUtils::getCurrentUser ()->getId ()) {
			PageData::addToBreadcrubs ( USERMANAGER_DETAILS );
			$content->addData ( HTML_userManager::userDetails ( $itemid ) );
			return $content;
		} else {
			PageData::redirect ( BASEURL . '/index.php?section=userManager' );
		}
	}
	
	public static function getAccountHome() {
		$content = new Content ();
		if (UserUtils::getCurrentUser ()->getId () != 0) {
			$content->setTitle ( USERMANAGER_ACCOUNT );
			$content->setIcon ( 'profile' );
			PageData::clearBreadcrubs ();
			PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT, 'index.php?section=userManager' );
			PageData::addToolbarButton ( new ToolbarButton ( USERMANAGER_ACCOUNT_EDIT, 'index.php?section=userManager&task=edit_user', 'user_edit' ) );
			$content->addData ( HTML_userManager::account_home () );
		} else {
			PageData::clearBreadcrubs ();
			PageData::addToBreadcrubs ( USERMANAGER_LOGIN );
			$content->setTitle ( USERMANAGER_LOGIN );
			$content->setIcon ( 'lock' );
			$content->addData ( HTML_userManager::login_form () );
		}
		return $content;
	}
	
	static public function changeEmail($email) {
		// Generate unique confirmation id
		$valcode = String::rand ( 30, 30 );
		
		$database = DBManager::getInstance ();
		$user = UserUtils::getCurrentUser ();
		
		$query = $database->prepare ( "UPDATE #__users SET activation=? , params=? WHERE uid=?" );
		$query->bindParam ( 1, $valcode, PDO::PARAM_STR );
		$query->bindParam ( 2, $email, PDO::PARAM_STR );
		$uid = $user->getId ();
		$query->bindParam ( 3, $uid, PDO::PARAM_INT );
		$query->execute ();
		
		$result = self::sendNotification ( 'changeEmail', array ('name' => $user->getName (), 'email' => $email, 'valcode' => $valcode ) );
		
		$user->logActivity ( "Email change requested from {$user->getEmail()} to $email", 'User account' );
		
		return $result;
	}
	
	static public function confirm_new_email($valcode) {
		$database = DBManager::getInstance ();
		
		$query = $database->prepare ( "SELECT * FROM #__users WHERE activation=?" );
		$query->bindParam ( 1, $valcode, PDO::PARAM_STR );
		$query->execute ();
		
		if ($query->rowCount () == 1) {
			$row = $query->fetch ();
			$query = $database->prepare ( "UPDATE #__users SET activation='', email=? , params='' WHERE uid=?" );
			$query->bindParam ( 1, $row->params, PDO::PARAM_STR );
			$query->bindParam ( 2, $row->uid, PDO::PARAM_INT );
			$query->execute ();
			
			UserUtils::getCurrentUser ()->logActivity ( "Email changed from $row->email to $row->params for $row->username(#$row->uid)", 'User account' );
			return true;
		}
		return false;
	}
	
	static public function user_list() {
		$database = DBManager::getInstance ();
		$result = $database->query ( "SELECT uid FROM #__users WHERE uid != " . UserUtils::getCurrentUser ()->getId () );
		if ($result->rowCount () == 0) {
			return array ();
		}
		
		$users = array ();
		foreach ( $result->fetchAll () as $row ) {
			$users [] = UserUtils::getUser ( $row->uid );
		}
		
		return $users;
	}
	
	public static function sendNotification($type, $data = array()) {
		$siteName = Configuration::getValue ( 'system.site.name' );
		switch ($type) {
			case 'registration' :
				$title = USERMANAGER_REGISTRATION_EMAIL_TITLE;
				$message = '<p>' . USERMANAGER_REGISTRATION_EMAIL_PART1 . ' ' . $data ['name'] . ', ' . USERMANAGER_REGISTRATION_EMAIL_PART2 . ' <a href="' . BASEURL . '">' . $siteName . '</a>.</p>
<p>' . USERMANAGER_REGISTRATION_EMAIL_PART3 . ':<br />
<a href="' . BASEURL . '/index.php?section=userManager&task=registration_confirm&valcode=' . $data ['valcode'] . '">
' . BASEURL . '/index.php?section=userManager&task=registration_confirm&valcode=' . $data ['valcode'] . '</a>
</p>
<p>' . USERMANAGER_REGISTRATION_EMAIL_PART4 . ':
<div style="border:1px solid #aaaaaa;padding:5px;margin-bottom:3px;"><span style="font-weight:bold;float:left;width:200px;">' . USERMANAGER_NAME . ':</span>' . $data ['name'] . '</div>
<div style="border:1px solid #aaaaaa;padding:5px;margin-bottom:3px;"><span style="font-weight:bold;float:left;width:200px;">' . USERMANAGER_USERNAME . ':</span>' . $data ['username'] . '</div>
<div style="border:1px solid #aaaaaa;padding:5px;margin-bottom:3px;"><span style="font-weight:bold;float:left;width:200px;">' . USERMANAGER_EMAIL . ':</span>' . $data ['email'] . '</div>
<div style="border:1px solid #aaaaaa;padding:5px;margin-bottom:3px;"><span style="font-weight:bold;float:left;width:200px;">' . USERMANAGER_REGISTRATIONDATE . ':</span>' . LocaleUtils::time () . ' UTC</div>
</p>';
				break;
			
			case 'forgot' :
				$title = USERMANAGER_FORGOT_EMAIL_TITLE;
				$message = '<p>' . USERMANAGER_REGISTRATION_EMAIL_PART1 . ' ' . $data ['name'] . ', ' . USERMANAGER_FORGOT_EMAIL_PART1 . ' <a href="' . BASEURL . '">' . $siteName . '</a>.</p>
<p>' . USERMANAGER_FORGOT_EMAIL_PART2 . ':
<div style="border:1px solid #aaaaaa;padding:5px;margin-bottom:3px;"><span style="font-weight:bold;float:left;width:200px;">' . USERMANAGER_USERNAME . ':</span>' . $data ['username'] . '</div>
<div style="border:1px solid #aaaaaa;padding:5px;margin-bottom:3px;"><span style="font-weight:bold;float:left;width:200px;">' . USERMANAGER_NEWPASSWORD . ':</span>' . $data ['genpass'] . '</div>
</p>';
				break;
			
			case 'changePass' :
				$title = USERMANAGER_CHANGEPASS_EMAIL_TITLE;
				$message = '<p>' . USERMANAGER_REGISTRATION_EMAIL_PART1 . ' ' . $data ['name'] . ', ' . USERMANAGER_CHANGEPASS_EMAIL_PART1 . ' <a href="' . BASEURL . '">' . $siteName . '</a>.</p>
<p>' . USERMANAGER_CHANGEPASS_EMAIL_PART2 . ':
<div style="border:1px solid #aaaaaa;padding:5px;margin-bottom:3px;"><span style="font-weight:bold;float:left;width:200px;">' . USERMANAGER_USERNAME . ':</span>' . $data ['username'] . '</div>
<div style="border:1px solid #aaaaaa;padding:5px;margin-bottom:3px;"><span style="font-weight:bold;float:left;width:200px;">' . USERMANAGER_NEWPASSWORD . ':</span>' . $data ['pass'] . '</div>
</p>';
				break;
			
			case 'newPM' :
				$title = USERMANAGER_ACCOUNT_PM_EMAIL_TITLE;
				$message = '<p>' . USERMANAGER_REGISTRATION_EMAIL_PART1 . ' ' . $data ['name'] . ', ' . USERMANAGER_ACCOUNT_PM_EMAIL_PART1 . ' <b>' . UserUtils::getCurrentUser ()->getUsername () . '</b>.</p>
<p>' . USERMANAGER_ACCOUNT_PM_EMAIL_PART2 . ':
<div style="border:1px solid #aaaaaa;padding:5px;margin-bottom:3px;"><a href="' . BASEURL . '/index.php?section=userManager&task=pm_read&itemid=' . $data ['PMid'] . '">
' . BASEURL . '/index.php?section=userManager&task=pm_read&itemid=' . $data ['PMid'] . '</a></div>
</p>';
				break;
			case 'changeEmail' :
				$title = USERMANAGER_CHANGEEMAIL_EMAIL_TITLE;
				$message = '<p>' . USERMANAGER_REGISTRATION_EMAIL_PART1 . ' ' . $data ['name'] . ', ' . USERMANAGER_CHANGEEMAIL_EMAIL_PART1 . ' <a href="' . BASEURL . '">' . $siteName . '</a>.</p>
<p>' . USERMANAGER_CHANGEEMAIL_EMAIL_PART2 . ':<br/>
<div style="font-style:italic"><a href="' . BASEURL . '/index.php?section=userManager&task=change_email&valcode=' . $data ['valcode'] . '">' . BASEURL . '/index.php?section=userManager&task=change_email&valcode=' . $data ['valcode'] . '</a></div></div>
</p>
</p>';
				break;
		}
		//Send email
		return Mail::sendMail ( array ($data ['name'] => $data ['email'] ), $title . " " . $siteName, $message );
	}
}

?>