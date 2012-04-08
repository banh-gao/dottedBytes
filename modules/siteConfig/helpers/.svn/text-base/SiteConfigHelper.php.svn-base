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

namespace dottedBytes\modules\siteConfig\helpers;

use OOForm\elements\basic\Button;

use OOForm\elements\LabeledElement;

use OOForm\elements\basic\Text;

use dottedBytes\libs\configuration\ConfigurationItem;

use dottedBytes\libs\configuration\Configuration;

use dottedBytes\libs\html\form\Captcha;

use OOForm\validator\EmptyValidator;

use OOForm\validator\EmailValidator;

use OOForm\elements\HtmlElement;

use OOForm\elements\basic\Hidden;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\modules\ModFactory;

use dottedBytes\libs\users\User;

use dottedBytes\libs\users\UserBuilder;

use dottedBytes\libs\modules\PageNotFoundException;

use dottedBytes\libs\utils\collections\ObjectSet;

use dottedBytes\libs\html\PageNavigation;

use dottedBytes\libs\utils\collections\ObjectList;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\io\PageData;

use PDO;

use DateTime;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class SiteConfigHelper {
	
	/**
	 * Returns an array of InputFields based on the configuration items values
	 * @param string $parentPath
	 * @return array
	 */
	public static function getChildrenFields($parentPath) {
		$db = DBManager::getInstance ();
		$fields = array ();
		foreach ( Configuration::getChildren ( $parentPath ) as $child ) {
			switch ($child->getType ()) {
				case '' :
					$fields [] = self::buildParentLink ( $child );
					break;
				default :
					$fields [] = self::buildTextfield ( $child );
					break;
			}
		}
		return $fields;
	}
	
	/**
	 * Build a text field
	 * @param ConfigurationItem $item
	 * @return Text
	 */
	private static function buildTextfield(ConfigurationItem $item) {
		$fullName = $item->getName ();
		$shortName = substr ( $fullName, strrpos ( $fullName, '.' ) + 1 );
		$field = new Text ( $fullName, $shortName );
		
		if ($item->getComment () != '')
			$field->setTooltip ( $item->getComment () );
		return $field;
	}
	
	private static function buildParentLink(ConfigurationItem $item) {
		$fullName = $item->getName ();
		$shortName = substr ( $fullName, strrpos ( $fullName, '.' ) + 1 );
		$html = '<a href="index.php?section=siteConfig&parentPath=' . $fullName . '">' . $fullName . '</a>';
		return new Button($fullName,'', $fullName );
	}
}

?>
