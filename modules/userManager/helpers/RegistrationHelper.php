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

use dottedBytes\libs\users\permissions\Permission;

use OOForm\FormRequest;

use OOForm\validator\UrlValidator;

use dottedBytes\modules\userManager\authSystems\openID\OpenIDException;

use dottedBytes\libs\html\form\Captcha;

use dottedBytes\libs\html\form\CaptchaValidator;

use OOForm\validator\RegexValidator;

use OOForm\validator\EmailValidator;

use OOForm\validator\EmptyValidator;

use dottedBytes\libs\html\form\Form;

use dottedBytes\modules\contentMgr\authSystems\openID\OpenIDLogin;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\database\DBManager;

use dottedBytes\modules\userManager\authSystems\openID\OpenIDUtils;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\modules\ModUtils;

use PDO;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class RegistrationHelper {
	
	const NAME_PATTERN = '/([- a-zA-Z0-9\.]){4,30}/';
	const PASSWORD_PATTERN = '/.{8,20}/';
	
	public static function get_registration_form() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'registration.enable', false ))
			throw new UserManagerException ( 'Registration service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		PageData::clearBreadcrubs ();
		PageData::addToBreadcrubs ( USERMANAGER_REGISTRATION );
		$content = new Content ( USERMANAGER_REGISTRATION, 'user_add' );
		
		$content->addData ( HTML_userManager::registration_form () );
		return $content;
	}
	
	public static function fast_registration_request() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'account.enableOpenID', false ))
			throw new UserManagerException ( 'OpenID service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$request = Form::getRequest ();
		$identifier = $request->getValue ( 'openid_identifier', '' );
		$return_to = BASEURL . '/index.php?formID=form&section=userManager&task=fastRegistrationFilled';
		try {
			OpenIDUtils::sendOpenIDUserRequest ( $identifier, $return_to );
		} catch ( OpenIDException $e ) {
			$request->setError ( 'openid_identifier', $e->getMessage () );
			$request->sendErrors ();
		}
	}
	
	public static function get_fast_registration_form() {
		PageData::clearBreadcrubs ();
		PageData::addToBreadcrubs ( USERMANAGER_REGISTRATION_SREG_TITLE );
		$content = new Content ( USERMANAGER_REGISTRATION_SREG_TITLE, 'openID' );
		
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'account.enableOpenID', false ))
			throw new UserManagerException ( 'OpenID service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$content->addData ( HTML_userManager::fast_registration_form () );
		return $content;
	}
	
	public static function get_fast_registration_filled_form() {
		PageData::clearBreadcrubs ();
		PageData::addToBreadcrubs ( USERMANAGER_REGISTRATION_SREG_TITLE );
		$content = new Content ( USERMANAGER_REGISTRATION_SREG_TITLE, 'openID' );
		
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'account.enableOpenID', false ))
			throw new UserManagerException ( 'OpenID service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$content->addData ( HTML_userManager::fast_registration_filled_form () );
		return $content;
	}
	
	public static function completeRegistration() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'registration.enable', false ))
			throw new UserManagerException ( 'Registration service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		PageData::clearBreadcrubs ();
		PageData::addToBreadcrubs ( USERMANAGER_REGISTRATION );
		
		$request = Form::getRequest ();
		
		//Trigger error if not checked
		$privacyValidator = new EmptyValidator ();
		$privacyValidator->setErrorMessage ( USERMANAGER_REGISTRATION_PRIVACY_ERROR );
		$request->getValue ( 'privacy', '', $privacyValidator );
		
		$email = $request->getValue ( 'email', '', new UniqueMailValidator () );
		$name = $request->getValue ( 'name', '', new RegexValidator ( self::NAME_PATTERN ) );
		$user = $request->getValue ( 'user', '', new UsernameValidator () );
		$language = $request->getValue ( 'language', '', new EmptyValidator () );
		
		if (!array_key_exists('openid_identifier', $_POST)) {
			$openID = '';
			$passwordValidator = new RegexValidator ( self::PASSWORD_PATTERN );
			$passwordValidator->setErrorMessage ( USERMANAGER_REGISTRATION_CHECKPASS );
			$tempPass = $request->getValue ( 'password', '', $passwordValidator );
			
			if ($tempPass !== $request->getValue ( 'password2', '' ))
				$request->setError ( 'password2', USERMANAGER_REGISTRATION_CHECKEQUALPASS );
				
			Form::checkCaptcha ();
		} else {
			$tempPass = '';
			$openID = $request->getValue ( 'openid_identifier', new EmptyValidator () );
		}
		$request->sendErrors ();
		
		$UData = array ('email' => $email, 'username' => $user, 'password' => $tempPass, 'name' => $name, 'language' => $language);
		
		$result = self::complete ( $UData, $openID );
		
		$content = new Content ( USERMANAGER_REGISTRATION, 'user_add' );
		if ($result) {
			$content->addData ( USERMANAGER_REGISTRATION_SENDMAIL );
		} else {
			$content->addData ( USERMANAGER_REGISTRATION_FAIL );
		}
		return $content;
	}
	
	public static function confirmRegistration() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'registration.enable', false ))
			throw new UserManagerException ( 'Registration service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
			
		if (self::confirm ( PageData::getParam('valcode' ) )) {
			PageData::redirect ( BASEURL . '/index.php?section=userManager', USERMANAGER_REGISTRATION_COMPLETE );
		} else {
			PageData::redirect ( BASEURL . '/index.php?section=userManager', USERMANAGER_REGISTRATION_FAIL );
		}
	}
	
	public static function sendNewRegMail() {
		if(!array_key_exists("resendUID", $_SESSION))
			PageData::back();
		$uid = $_SESSION["resendUID"];
		unset($_SESSION["resendUID"]);
		$user = UserUtils::getUser ( $uid );
		if ($user->getId () == 0)
			throw new UserManagerException ();
		$username = $user->getUsername ();
		$email = $user->getEmail ();
		
		$database = DBManager::getInstance ();
		
		// Generate the new password
		$plainPassword = String::rand ( 6, 10 );
		
		$UData = array ('valcode' => $user->getAttribute ( 'activation' ), 'email' => $user->getEmail (), 'username' => $user->getUsername (), 'password' => $plainPassword, 'name' => $user->getName (), 'language' => $user->getLanguage () );
		
		// Update the password in database
		$hashedPassword = UserUtils::generatePassword ( $plainPassword );
		
		$database->beginTransaction ();
		$result = $database->query ( "UPDATE #__users SET password='$hashedPassword' WHERE uid=$uid" );
		if ($result->rowCount () > 0) {
			//Create email
			if (UserHelper::sendNotification ( 'registration', $UData )) {
				$database->commit ();
				UserUtils::getCurrentUser ()->logActivity ( "New registration mail sent to $email for " . $username . "(#" . $uid . ")", 'User Registration' );
				return true;
			}
		}
		$database->rollback ();
		return false;
	}
	
	/**
	 * Return a json response wil the availability of a username
	 * @return Content
	 */
	public static function checkUserAvailable() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'registration.enable', false ))
			throw new UserManagerException ( 'Registration service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$database = DBManager::getInstance ();
		
		$username = PageData::getParam ( 'itemid', '' );
		$query = $database->prepare ( "SELECT username FROM #__users WHERE username=?" );
		$query->bindParam ( 1, $username, PDO::PARAM_STR );
		$query->execute ();
		
		$content = new Content ();
		if ($query->rowCount () > 0) {
			$msg = sprintf ( USERMANAGER_REGISTRATION_UNOTAVAILABLE, $username );
			$available = 'false';
		} else {
			$msg = sprintf ( USERMANAGER_REGISTRATION_UISAVAILABLE, $username );
			$available = 'true';
		}
		
		$msg = PageData::JSMessage ( $msg );
		
		$content->addData ( "{\"available\":$available,\"message\":\"$msg\"}" );
		$content->setMimeType( 'application/json' );
		
		return $content;
	}
	
	public static function getForgotForm() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'registration.enableRecovery', false ))
			throw new UserManagerException ( 'Password recovery service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		PageData::clearBreadcrubs ();
		PageData::addToBreadcrubs ( USERMANAGER_REGISTRATION_FORGOT );
		$content = new Content ( USERMANAGER_REGISTRATION_FORGOT, 'info' );
		$content->addData ( HTML_userManager::forgot_form () );
		return $content;
	}
	
	public static function sendNewPassword() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'registration.enableRecovery', false ))
			throw new UserManagerException ( 'Password recovery service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$request = Form::getRequest();
			
		$username = $request->getValue( 'user', '');
		$email = $request->getValue( 'email', new EmailValidator() );
		Form::checkCaptcha ();
		
		$request->sendErrors();
		
		if (self::forgot ( $username, $email )) {
			$content = new Content ( USERMANAGER_REGISTRATION_FORGOT, 'info' );
			$content->addData ( sprintf ( USERMANAGER_REGISTRATION_NEWPASS, $email ) );
			return $content;
		} else {
			PageData::back ( USERMANAGER_REGISTRATION_NEWPASS_FAIL );
		}
	}
	
	static public function forgot($username, $email) {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'registration.enableRecovery', false ))
			throw new UserManagerException ( 'Password recovery service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$database = DBManager::getInstance ();
		
		// Control if the user exist
		$query = $database->prepare ( "SELECT * FROM #__users WHERE username=? AND email=?" );
		$query->bindParam ( 1, $username, PDO::PARAM_STR );
		$query->bindParam ( 2, $email, PDO::PARAM_STR );
		$query->execute ();
		
		if ($query->rowCount () != 1) {
			return false;
		}
		
		$row = $query->fetch ();
		
		// Generate the new password
		$salt = "abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$plainPassword = String::rand ( 6, 10 );
		
		// Update the password in database
		$hashedPassword = UserUtils::generatePassword ( $plainPassword );
		
		//Clean attepts count in database
		$database->query ( "DELETE FROM #__users_login WHERE uid=$row->uid" );
		
		$database->beginTransaction ();
		$result = $database->query ( "UPDATE #__users SET password='$hashedPassword' WHERE uid=$row->uid" );
		if ($result->rowCount () > 0) {
			//Create email
			if (UserHelper::sendNotification ( 'forgot', array ('name' => $row->name, 'username' => $username, 'genpass' => $plainPassword, 'email' => $row->email ) )) {
				$database->commit ();
				UserUtils::getCurrentUser ()->logActivity ( "New password sent to $email for " . $username . "(#" . $row->uid . ")", 'User Account' );
				return true;
			}
		}
		$database->rollback ();
		return false;
	}
	
	static public function confirm($valcode) {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'registration.enable', false ))
			throw new UserManagerException ( 'Registration service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$database = DBManager::getInstance ();
		$query = $database->prepare ( "SELECT * FROM #__users WHERE activation=?" );
		$query->bindParam ( 1, $valcode, PDO::PARAM_STR );
		$query->execute ();
		
		if ($query->rowCount () == 1) {
			$row = $query->fetch ();
			$database->query ( "UPDATE #__users SET activation='' WHERE uid='" . $row->uid . "'" );
			$permID = Permission::getByName ( 'access' )->getId ();
			$database->query ( "INSERT INTO #__users_perms VALUES($row->uid,'$permID')" );
			UserUtils::getCurrentUser ()->logActivity ( "Account activated with validation code for $row->username(#$row->uid)" );
			return true;
		}
		return false;
	}
	
	static public function complete($data, $openID) {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'registration.enable', false ))
			throw new UserManagerException ( 'Registration service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$database = DBManager::getInstance ();
		
		// Generate unique confirmation id
		$valcode = String::rand ( 30, 30 );
		////////////////////////////////////////////
		

		$plainPassword = $data ['password'];
		$hashedPassword = ($openID != '') ? '' : UserUtils::generatePassword ( $plainPassword );
		
		//// Add user to database
		$query = "INSERT INTO #__users (name,username,email,password,gid,regDate,visitDate,activation,language) VALUES (? , ? , ? , ?  , NULL , NOW() , NOW() , ?  , ?)";
		$stm = $database->prepare ( $query );
		
		$stm->bindParam ( 1, $data ['name'], PDO::PARAM_STR );
		$stm->bindParam ( 2, $data ['username'], PDO::PARAM_STR );
		$stm->bindParam ( 3, $data ['email'], PDO::PARAM_STR );
		$stm->bindParam ( 4, $hashedPassword, PDO::PARAM_STR );
		$stm->bindParam ( 5, $valcode, PDO::PARAM_STR );
		$stm->bindParam ( 6, $data ['language'], PDO::PARAM_STR );
		$stm->execute ();
		
		$uid = $database->getInsertId ();
		
		//Create openID record
		$database->query ( "INSERT INTO #__users_openID VALUES($uid,'{$openID}',1)" );
		
		// Send confirmation email
		UserHelper::sendNotification ( 'registration', array_merge ( $data, array ('valcode' => $valcode ) ) );
		
		if ($openID != '') {
			UserUtils::getCurrentUser ()->logActivity ( "OpenID registration requested for {$data['username']}(#" . $uid . ")", 'User account' );
		} else {
			UserUtils::getCurrentUser ()->logActivity ( "Registration requested for {$data['username']}(#" . $uid . ")", 'User account' );
		}
		return true;
	}
}
?>