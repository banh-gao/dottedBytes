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

use OOForm\elements\table\TableRow;

use OOForm\elements\table\Table;

use dottedBytes\modules\siteConfig\helpers\SiteConfigHelper;

use OOForm\elements\HtmlElement;

use dottedBytes\libs\html\form\Captcha;

use OOForm\elements\editor\Editor;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use OOForm\elements\basic\Submit;

use OOForm\elements\basic\Text;

use OOForm\elements\basic\Hidden;

use dottedBytes\libs\html\rss\FeedItem;

use dottedBytes\libs\html\rss\RSSGenerator;

use dottedBytes\modules\contentMgr\toolbar\RssButton;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\html\PageNavigation;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\modules\ModFactory;

use dottedBytes\libs\html\toolbar\PrintButton;

use dottedBytes\modules\contentMgr\toolbar\DeleteContentButton;

use dottedBytes\modules\contentMgr\toolbar\EditContentButton;

use dottedBytes\modules\contentMgr\toolbar\NewContentButton;

use dottedBytes\libs\modules\PageNotFoundException;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\pageBuilder\Resources;
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class HTML_siteConfig {
	
	public static function config_form() {
		$form = new Form ();
		$form->addElement ( new Hidden ( 'section', 'siteConfig' ) );
		$form->addElement ( new Hidden ( 'task', 'save' ) );
		
		$parentPath = PageData::getParam ( 'parentPath', '.' );
		
		foreach ( SiteConfigHelper::getChildrenFields ( $parentPath ) as $field )
			$form->addElement ( $field );
		
		$content = new Content ( sprintf ( SITECONFIG_CONF_OF, $parentPath ), 'config' );
		$content->setData ( $form->render () );
		PageData::clearBreadcrubs ();
		$lastName = '';
		foreach ( explode ( '.', $parentPath ) as $shortName ) {
			$lastName .= '.'.$shortName;
			$link = 'index.php?section=siteConfig&parentPath=' . $lastName;
			
			PageData::addToBreadcrubs ( sprintf ( SITECONFIG_CONF_OF, $shortName ), $link );
		}
		return $content;
	}
	
	public static function listeners_form() {
		$form = new Form();
		$table = new Table('Tabella listeners',new ListenersTableModel());
		
		
		$form->addElement($table);
		return $form->render();
	}
}
?>