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

use dottedBytes\libs\utils\String;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\modules\ModUtils;

use PDO;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

use OOForm\validator\InputValidator;

class RecipientValidator extends InputValidator {
	
	private $userLimit;
	
	public function __construct() {
		$this->userLimit = ModUtils::getCurrentModule ()->getConfigValue ( 'pm.multipleLimit' );
	}
	
	public function validate($value) {
		$value = explode ( ",", $value );
		if (count ( $value ) < 1) {
			$this->setErrorMessage ( sprintf ( USERMANAGER_ACCOUNT_PM_RECIPIENT_TIP, $this->userLimit ) );
			return false;
		} elseif (count ( $value ) > $this->userLimit) {
			$this->setErrorMessage ( sprintf ( USERMANAGER_ACCOUNT_PM_OVERUSER, $this->userLimit ) );
			return false;
		}
		$db = DBManager::getInstance ();
		
		$unknownUsers = array ();
		$stm = $db->prepare ( "SELECT uid WHERE username=?" );
		foreach ( $value as $username ) {
			$username = String::trim ( $username );
			$stm->bindParam ( 1, $username, PDO::PARAM_STR );
			$stm->execute ();
			if ($stm->rowCount () == 0)
				$unknownUsers [] = $username;
		}
		if (count ( $unknownUsers ) > 0) {
			$this->setErrorMessage ( sprintf ( USERMANAGER_ACCOUNT_PM_NOUSER, implode ( ", ", $unknownUsers ) ) );
			return false;
		}
		
		return true;
	}
}

?>