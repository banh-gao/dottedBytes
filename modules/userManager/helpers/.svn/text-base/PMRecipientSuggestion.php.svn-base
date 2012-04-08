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

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\database\DBManager;

use OOForm\elements\ajax\AjaxListener;

use dottedBytes\libs\io\FileUtils;
use \PDO;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );
	
class PMRecipientSuggestion extends AjaxListener {
	
	public function getServiceUrl() {
		return BASEURL . '/index.php?section=userManager&task=pm_ajax';
	}

	/* (non-PHPdoc)
	 * @see OOForm\elements\ajax.AjaxListener::getResponse()
	 */
	protected function getResponse($query) {
		$db = DBManager::getInstance();
		$stm = $db->prepare("SELECT username FROM #__users WHERE uid!=? AND username LIKE ?");
		$query = $query.'%';
		$stm->bindParam(1, UserUtils::getCurrentUser()->getId(),PDO::PARAM_INT);
		$stm->bindParam(2, $query,PDO::PARAM_STR);
		$stm->execute();
		$sqlResult = $stm->fetchAll();
		$result = array();
		foreach($sqlResult as $row) {
			$result[] = $row->username;
		}
		return $result;
	}


}

?>