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

use \PDO;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\database\DBManager;

use OOForm\validator\EmailValidator;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class UniqueMailValidator extends EmailValidator {
	public function validate($value) {
		return parent::validate ( $value ) && $this->uniqueValidate ( $value );
	}
	
	private function uniqueValidate($email) {
		$database = DBManager::getInstance ();
		if (ModUtils::getCurrentModule ()->getConfigValue ( 'registration.uniqueMail' )) {
			$query = $database->prepare ( "SELECT uid FROM #__users WHERE email=?" );
			$query->bindParam ( 1, $email, PDO::PARAM_STR );
			$query->execute ();
			
			if ($query->rowCount () != 0) {
				$this->setErrorMessage(USERMANAGER_REGISTRATION_EMAILNOTAVAILABLE);
				return false;
			}
			return true;
		}
	}
}

?>