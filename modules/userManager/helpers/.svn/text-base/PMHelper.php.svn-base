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

use OOForm\validator\EmptyValidator;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\html\toolbar\ToolbarButton;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\io\PageData;

use \PDO;

use dottedBytes\libs\modules\ModUtils;
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class PMHelper {
	
	public static function read() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'pm.enable', false ))
			throw new UserManagerException ( 'Private messages service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$itemid = PageData::getParam ( 'itemid' );
		PageData::clearBreadcrubs ();
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT, 'index.php?section=userManager' );
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT_PM, 'index.php?section=userManager&task=pm' );
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT_PM_READ );
		PageData::addToolbarButton ( new ToolbarButton ( USERMANAGER_ACCOUNT_PM_CLOSE, 'index.php?section=userManager&task=pm', 'back' ) );
		$content = new Content ( USERMANAGER_ACCOUNT_PM_READ, 'mail_open' );
		$content->addData ( HTML_userManager::readPM_form ( $itemid ) );
		return $content;
	}
	
	public static function send() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'pm.enable', false ))
			throw new UserManagerException ( 'Private messages service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
			
		$request = Form::getRequest();
		$recipient = $request->getValue( 'recipient', '',new RecipientValidator() );
		
		$subject = $request->getValue( 'subject', '', new EmptyValidator() );
		$message = $request->getValue( 'message', '', new EmptyValidator() );
		
		$recipient = explode(",", $recipient);
		
		$request->sendErrors();
		
		self::sendpm ( $recipient, $subject, $message );
		
		PageData::redirect ( BASEURL . "/index.php?section=userManager&task=pm", USERMANAGER_ACCOUNT_PM_SENTOK );
	}
	
	static public function sendpm($recipient, $subject, $message) {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'pm.enable', false ))
			throw new UserManagerException ( 'Private messages service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$logrec = array ();
		foreach ( $recipient as $rec ) {
			$recipient = UserUtils::getUser ( $rec );
			$logrec [] = $recipient->getUsername ();
			$database = DBManager::getInstance ();
			$query = $database->prepare ( "INSERT INTO #__users_pm (subject,message,fromid,toid,date) VALUES(?,?,?,?,NOW())" );
			$query->bindParam ( 1, $subject, PDO::PARAM_STR );
			$query->bindParam ( 2, $message, PDO::PARAM_STR );
			$senderId = UserUtils::getCurrentUser ()->getId ();
			$query->bindParam ( 3, $senderId, PDO::PARAM_INT );
			$recipientId = $recipient->getId ();
			$query->bindParam ( 4, $recipientId, PDO::PARAM_INT );
			$query->execute ();
			
			$status = UserHelper::sendNotification ( 'newPM', array ('PMid' => $database->getInsertId (), 'name' => $recipient->getName (), 'email' => $recipient->getEmail () ) );
			$logrec [] = $recipient->getUsername ();
		}
		
		$logrec = implode ( ";", $logrec );
		UserUtils::getCurrentUser ()->logActivity ( "PM " . $subject . " sent to $logrec", 'Private Messages' );
		return $status;
	}
	
	public static function getNewForm() {
		if (! ModUtils::getCurrentModule ()->getConfigValue( 'pm.enable', false ))
			throw new UserManagerException ( 'Private messages service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		PageData::clearBreadcrubs ();
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT, 'index.php?section=userManager' );
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT_PM, 'index.php?section=userManager&task=pm' );
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT_PM_NEW );
		$content = new Content ( USERMANAGER_ACCOUNT_PM_NEW, 'article' );
		$content->addData ( HTML_userManager::newPM_form () );
		return $content;
	}
	
	public static function delete() {
		if (! ModUtils::getCurrentModule ()->getConfigValue( 'pm.enable', false ))
			throw new UserManagerException ( 'Private messages service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		$database = DBManager::getInstance ();
		$itemid = ( int ) PageData::getParam ( 'itemid', 0 );
		$uid = UserUtils::getCurrentUser ()->getId ();
		$result = $database->query ( "DELETE FROM #__users_pm WHERE id=$itemid AND toID=$uid" );
		
		if ($result->rowCount () < 1) {
			PageData::redirect ( BASEURL . "/index.php?section=userManager&task=pm", USERMANAGER_ACCOUNT_PM_NOTFOUND );
		} else {
			UserUtils::getCurrentUser ()->logActivity ( "PM #" . $itemid . " deleted", 'Private Messages' );
			PageData::redirect ( BASEURL . "/index.php?section=userManager&task=pm", USERMANAGER_ACCOUNT_PM_DELETED );
		}
	}
	
	public static function getListForm() {
		if (! ModUtils::getCurrentModule ()->getConfigValue ( 'pm.enable', false ))
			throw new UserManagerException ( 'Private messages service disabled', 0, _SITE_SERVICE_UNAVAILABLE );
		
		PageData::clearBreadcrubs ();
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT, 'index.php?section=userManager' );
		PageData::addToBreadcrubs ( USERMANAGER_ACCOUNT_PM, 'index.php?section=userManager&task=pm' );
		PageData::addToolbarButton ( new ToolbarButton ( USERMANAGER_ACCOUNT_PM_NEW, 'index.php?section=userManager&task=pm_new', 'article' ) );
		$content = new Content ( USERMANAGER_ACCOUNT_PM, 'mail' );
		$content->addData ( HTML_userManager::listPM_form () );
		return $content;
	}
}

?>