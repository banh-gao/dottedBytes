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

use dottedBytes\libs\users\permissions\Permission;

use dottedBytes\libs\users\permissions\PermissionSet;

use dottedBytes\modules\userManager\helpers\PMRecipientSuggestion;

use dottedBytes\modules\userManager\helpers\AdminHelper;

use dottedBytes\modules\userManager\helpers\PMRecipientListener;

use dottedBytes\modules\userManager\helpers\PMHelper;

use dottedBytes\modules\userManager\helpers\RegistrationHelper;

use dottedBytes\modules\userManager\helpers\UserHelper;
use dottedBytes\libs\users\auth\PermissionException;
use dottedBytes\libs\io\PageData;
use dottedBytes\libs\modules\Component;
use dottedBytes\libs\pageBuilder\Content;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class UserManagerComponent extends Component {
	
	const USERADMIN_PERM = 'userAdmin';
	const ACCESS_PERM = 'access';
	const GUESTACCESS_PERM = 'guestAccess';
	
	public function buildContent() {
		$content = new Content ();
		$task = PageData::getParam ( 'task' );
		$itemid = PageData::getParam ( 'itemid' );
		switch ($task) {
			//Administrator panels
			case 'admin' :
				$content->addData ( AdminHelper::getAdminPage () );
				break;
			
			// 1. Registration form
			case 'registration' :
				$content->addData ( RegistrationHelper::get_registration_form () );
				break;
			
			case 'fastRegistration' :
				$content->addData ( RegistrationHelper::get_fast_registration_form () );
				break;
			
			case 'fastRegistrationFilled' :
				$content->addData ( RegistrationHelper::get_fast_registration_filled_form () );
				break;
			
			case 'fastRegistrationRequest' :
				RegistrationHelper::fast_registration_request ();
				break;
			
			// 2. Control username avaibility
			case 'registration_available' :
				$content = RegistrationHelper::checkUserAvailable ();
				break;
			
			// 3. Registration complete
			case 'registration_complete' :
				$content->addData ( RegistrationHelper::completeRegistration () );
				break;
			
			// 4. Confirm registration by sending email
			case 'registration_confirm' :
				RegistrationHelper::confirmRegistration ();
				break;
			
			//3. Send new registration email
			case 'registration_newmail' :
				$content = new Content ( USERMANAGER_REGISTRATION, 'user_add' );
				
				if (RegistrationHelper::sendNewRegMail ()) {
					$content->addData ( USERMANAGER_REGISTRATION_SENDMAIL );
				} else {
					$content->addData ( USERMANAGER_REGISTRATION_FAIL );
				}
				break;
			
			// Password forgot form
			case 'pwdforgot' :
				$content->addData ( RegistrationHelper::getForgotForm () );
				break;
			
			//Send new password
			case 'send_pwd' :
				$content->addData ( RegistrationHelper::sendNewPassword () );
				break;
			
			/***************************************************************************************
			 * PRIVATE MESSAGES
			 ***************************************************************************************/
			case 'pm_read' :
				$content->addData ( PMHelper::read () );
				break;
			
			case 'pm_send' :
				PMHelper::send ();
				break;
			
			case 'pm_new' :
				$content->addData ( PMHelper::getNewForm () );
				break;
			
			case 'pm_delete' :
				PMHelper::delete ();
				break;
			
			case 'pm' :
				$content->addData ( PMHelper::getListForm () );
				break;
			
			case 'pm_ajax' :
				$l = new PMRecipientSuggestion ();
				$l->processRequest ();
				break;
			
			/***************************************************************************************
			 * USER SERVICES
			 ***************************************************************************************/
			
			case 'login' :
				$content->addData ( UserHelper::login () );
				break;
			
			case 'logout' :
				$content->addData ( UserHelper::logout () );
				break;
			
			// User options editor
			case 'edit_user' :
				$content->addData ( UserHelper::get_edit_form () );
				break;
			
			// Edit email address
			case 'edit_email' :
				$content->addData ( UserHelper::get_email_form () );
				break;
			
			// Confirm new email address
			case 'change_email' :
				UserHelper::confirmEmail ();
				break;
			
			//Update user options
			case 'save' :
				UserHelper::saveInfo ();
				break;
			
			//Request to change to the new email address
			case 'save_email' :
				$content->addData ( UserHelper::newMailRequest () );
				break;
			
			// Show a page with user details
			case 'userdetails' :
				$content->addData ( UserHelper::getDetailsPage () );
				break;
			
			default :
				$content->addData ( UserHelper::getAccountHome () );
				break;
		}
		return $content;
	}
	
	public function checkPermissions(PermissionSet $userPermissions) {
		$task = PageData::getParam ( 'task' );
		switch ($task) {
			case 'admin' :
				if (! Permission::check ( $userPermissions, self::USERADMIN_PERM ))
					throw new PermissionException ( self::USERADMIN_PERM );
			case 'registration' :
			case 'fastRegistration' :
			case 'fastRegistrationFilled' :
			case 'fastRegistrationRequest' :
			case 'registration_available' :
			case 'registration_complete' :
			case 'registration_confirm' :
			case 'registration_newmail' :
			case 'pwdforgot' :
			case 'send_pwd' :
				if (! Permission::check ( $userPermissions, self::GUESTACCESS_PERM ))
					throw new PermissionException ( self::GUESTACCESS_PERM );
				break;
			
			case 'pm_read' :
			case 'pm_send' :
			case 'pm_new' :
			case 'pm_delete' :
			case 'pm' :
			case 'pm_ajax' :
				if (! Permission::check ( $userPermissions, self::ACCESS_PERM ))
					throw new PermissionException ( self::ACCESS_PERM );
				break;
			
			case 'login' :
				if (! Permission::check ( $userPermissions, self::GUESTACCESS_PERM ))
					throw new PermissionException ( self::GUESTACCESS_PERM );
				break;
			
			case 'logout' :
			case 'edit_user' :
			case 'edit_email' :
			case 'change_email' :
			case 'save' :
			case 'save_email' :
			case 'userdetails' :
				if (! Permission::check ( $userPermissions, self::ACCESS_PERM ))
					throw new PermissionException ( self::ACCESS_PERM );
				break;
		}
		return true;
	}
}
?>