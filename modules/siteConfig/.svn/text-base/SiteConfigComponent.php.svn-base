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

namespace dottedBytes\modules\siteConfig;

use dottedBytes\libs\users\permissions\Permission;

use dottedBytes\libs\users\permissions\PermissionSet;

use dottedBytes\libs\io\FileUtils;

use dottedBytes\modules\siteConfig\helpers\HTML_siteConfig;

use OOForm\elements\group\Fieldset;

use OOForm\elements\basic\Text;

use dottedBytes\libs\modules\ModUtils;

use OOForm\elements\basic\Checkbox;

use OOForm\elements\LabeledElement;

use dottedBytes\libs\html\form\Separator;

use OOForm\elements\HtmlElement;

use OOForm\elements\group\ElementGroup;

use OOForm\elements\basic\Hidden;

use dottedBytes\libs\html\form\Form;

use dottedBytes\libs\html\toolbar\SubmitButton;

use dottedBytes\libs\html\toolbar\BackButton;

use dottedBytes\libs\errorHandling\ErrorToException;

use dottedBytes\libs\database\DBManager;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\configuration\Configuration;

use dottedBytes\libs\users\auth\PermissionException;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\pageBuilder\Content;

use dottedBytes\libs\modules\Component;
use dottedBytes\libs\pageBuilder\Resources;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class SiteConfigComponent extends Component {
	
	const CONFIG_PERM = 'siteConfig';
	
	public function buildContent() {
		$content = new Content ();
		
		$task = PageData::getParam ( 'task' );
		switch ($task) {
			case 'listeners' :
				$content->addData ( HTML_siteConfig::listeners_form () );
				break;
			case 'save' :
				$this->save ();
				break;
			case 'combined' :
				FileUtils::loadFile ( 'modules/siteConfig/helpers/CombinedResources' );
				exit ();
				break;
			default :
				$content->addData ( HTML_siteConfig::config_form () );
				break;
		}
		return $content;
	}
	
	public function checkPermissions(PermissionSet $userPermissions) {
		$task = PageData::getParam ( 'task' );
		if($task == 'combined') 
				return true;
			
		if(Permission::check($userPermissions,self::CONFIG_PERM))
			return true;
		
		throw new PermissionException(self::CONFIG_PERM);
	}
	
	public function edit_form() {
		$pageContent = new Content ();
		
		$database = DBManager::getInstance ();
		$result = $database->query ( "SELECT * FROM #__configurations ORDER BY parentId ASC" );
		
		$form = new Form ();
		$form->addElement ( new Hidden ( "section", "siteConfig" ) );
		$form->addElement ( new Hidden ( "task", "save" ) );
		
		$form->addElement ( self::configFileInfo () );
		
		$form->addElement ( self::gAnalyticsInfo () );
		
		PageData::addToolbarButton ( new BackButton () );
		PageData::addToolbarButton ( new SubmitButton ( $form->getFormID () ) );
		
		$pageContent->addData ( $form->render () );
		return $pageContent;
	}
	
	private static function configFileInfo() {
		$group = new Fieldset ( SITECONFIG_CONFFILE );
		$group->addElement ( new HtmlElement ( SITECONFIG_CONFFILE_TIP ) );
		$group->addElement ( new Separator ( SITECONFIG_PATH ) );
		$group->addElement ( new HtmlElement ( SITECONFIG_BASEPATH, BASEPATH ) );
		$group->addElement ( new HtmlElement ( SITECONFIG_BASEPATH_DETECTED, dirname ( $_SERVER ['SCRIPT_FILENAME'] ) ) );
		$group->addElement ( new HtmlElement ( SITECONFIG_BASEURL, BASEURL ) );
		$group->addElement ( new HtmlElement ( SITECONFIG_BASEURL_DETECTED, dirname ( "http://" . $_SERVER ['HTTP_HOST'] . $_SERVER ['PHP_SELF'] ) ) );
		
		$group->addElement ( new Separator ( SITECONFIG_ERRORS ) );
		$group->addElement ( new HtmlElement ( SITECONFIG_LOGDIR, LOGDIR ) );
		$hiddenErrors = array ();
		foreach ( unserialize ( CMS_HIDDENERRORS ) as $errNo )
			$hiddenErrors [] = ErrorToException::resolveErrorType ( $errNo );
		$group->addElement ( new HtmlElement ( SITECONFIG_HIDDENERRORS, implode ( ', ', $hiddenErrors ) ) );
		$group->addElement ( new HtmlElement ( SITECONFIG_DEBUGMODE, Resources::getSysIcon ( 'bool', '', '', CMS_DEBUGMODE ) ) );
		
		$group->addElement ( new Separator ( SITECONFIG_DB ) );
		$group->addElement ( new HtmlElement ( SITECONFIG_DB_DSN, CMS_DB_DSN ) );
		$group->addElement ( new HtmlElement ( SITECONFIG_DB_PREFIX, CMS_DB_PREFIX ) );
		
		$group->addElement ( new Separator ( SITECONFIG_REWRITE ) );
		$group->addElement ( new HtmlElement ( SITECONFIG_REWRITE_ENABLE, Resources::getSysIcon ( 'bool', '', '', REWRITE_ENABLE ) ) );
		$group->addElement ( new HtmlElement ( SITECONFIG_REWRITE_SYMBOL, REWRITE_SYMBOL ) );
		$group->addElement ( new HtmlElement ( SITECONFIG_REWRITE_EXTENSION, REWRITE_EXTENSION ) );
		return $group;
	}
	
	private static function gAnalyticsInfo() {
		$group = new Fieldset ( SITECONFIG_G_ANALYTICS );
		
		$enabled = ModUtils::getCurrentModule ()->getConfigValue ( 'gAnalytics.enable', false );
		$enableBox = new Checkbox ( 'gAnalytics_enable', SITECONFIG_G_ANALYTICS_ENABLE );
		$enableBox->setChecked ( $enabled );
		$group->addElement ( $enableBox );
		
		$account = ModUtils::getCurrentModule ()->getConfigValue ( 'gAnalytics.account', '' );
		$accountBox = new Text ( 'gAnalytics_account', SITECONFIG_G_ANALYTICS_ACCOUNT );
		$accountBox->setValue ( $account );
		$accountBox->setTooltip ( SITECONFIG_G_ANALYTICS_ACCOUNT_TIP );
		$group->addElement ( $accountBox );
		return $group;
	}
}

?>