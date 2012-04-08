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

namespace dottedBytes\libs\pageBuilder;

use dottedBytes\libs\configuration\Configuration;

use dottedBytes\libs\users\auth\AuthException;

use dottedBytes\libs\modules\Module;

use dottedBytes\libs\pageBuilder\template\Position;

use dottedBytes\libs\pageBuilder\template\HtmlTemplate;

use dottedBytes\templates\basic\html\TemplateHtml;

use dottedBytes\libs\logging\LogFactory;

use dottedBytes\libs\logging\Logger;

use dottedBytes\libs\pageBuilder\template\TemplateException;

use dottedBytes\libs\pageBuilder\template\TemplateManager;

use dottedBytes\libs\pageBuilder\template\DefaultManager;

use dottedBytes\libs\modules\Component;

use dottedBytes\libs\errorHandling\ErrorToException;

use dottedBytes\libs\utils\IllegalStateException;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\modules\ModFactory;

use dottedBytes\libs\users\auth\PermissionException;

use dottedBytes\libs\modules\PageNotFoundException;

use dottedBytes\libs\modules\Page;

use dottedBytes\libs\modules\ModUtils;

use dottedBytes\libs\pageBuilder\template\Template;

use dottedBytes\libs\pageBuilder\template\EmptyTemplate;

use dottedBytes\libs\io\IOException;

use dottedBytes\libs\errorHandling\CmsException;

use dottedBytes\libs\errorHandling\ExceptionHandler;

use dottedBytes\libs\io\PageData;
use dottedBytes\libs\io\FileUtils;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class PageBuilder {
	private static $instance = null;
	
	/**
	 * The page template manager
	 *
	 * @var TemplateManager
	 */
	private $templateManager;
	
	/**
	 * The template used in the requested page
	 * @var Template
	 */
	private $currentTemplate;
	
	private $templateName;
	
	/**
	 * The main component of the page
	 * @var Component
	 */
	private $component;
	
	/**
	 * The panels to show for html
	 * @var array
	 */
	private $panels = array ();
	
	private $mimeType;
	
	private $charset;
	
	/**
	 * Return a PageBuilder instance
	 *
	 * @return PageBuilder
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new PageBuilder ();
		}
		return self::$instance;
	}
	
	/**
	 * Set the name of the template to use
	 * @param string $templateName
	 */
	public function setTemplateName($templateName) {
		if (! FileUtils::file_exists ( BASEPATH . '/templates/' . $templateName )) {
			throw new CmsException ( "Template $templateName not found in \"" . BASEPATH . "/templates/\"" );
		}
		$this->templateName = $templateName;
	}
	
	/**
	 * Get the name of the template that will be used
	 * @return string
	 */
	public function getTemplateName() {
		return $this->templateName;
	}
	
	/**
	 * Generate the page and returns
	 */
	public function generatePage() {
		//Run initialization scripts
		ModUtils::notifyInitListeners ();
		
		$this->loadTemplateManager ();
		
		self::loadSiteLanguage ();
		
		//Generate the component to associate with the page
		$this->component = $this->generateComponent ();
		
		$this->mimeType = $this->component->getContent ()->getMimeType ();
		$this->charset = $this->component->getContent ()->getCharset ();
		
		$this->currentTemplate = $this->getTemplate ( $this->mimeType, $this->charset );
		
		//Attach the component to the template
		$this->currentTemplate->addModule ( $this->component );
		
		if ($this->mimeType == 'text/html') {
			//Attach the panels to the template
			$this->panels = $this->generatePanels ();
			
			foreach ( $this->panels as $panel )
				$this->currentTemplate->addModule ( $panel );
		}
		ob_start ();
		$this->currentTemplate->render ();
		return ob_get_clean ();
	}
	
	private function loadTemplateManager() {
		try {
			FileUtils::loadFile ( 'templates/' . $this->templateName . '/' . ucfirst ( $this->templateName ) . 'Manager' );
		} catch ( IOException $e ) {
			$this->templateManager = new DefaultManager ();
			return;
		}
		
		$className = 'dottedBytes\templates\\' . $this->templateName . '\\' . ucfirst ( $this->templateName ) . "Manager";
		
		if (class_exists ( $className ))
			$this->templateManager = new $className ();
		else
			throw new CmsException ( "Cannot find the template manager class $className for template $this->templateName" );
		
		if (! ($this->templateManager instanceof TemplateManager))
			throw new CmsException ( "The template manager class $className must implement the interface TemplateManager" );
	}
	
	private function generateComponent() {
		PageData::setScreenPosition ( Position::CENTER );
		
		switch (PageData::getParam ( 'error', '' )) {
			case 'notFound' :
				$component = new Component ();
				$component->setInitializeException ( new PageNotFoundException () );
				break;
			case 'noAuth' :
				$component = new Component ();
				$component->setInitializeException ( new PermissionException ( $component->getPermission () ) );
				break;
			default :
				try {
					$section = PageData::getParam ( 'section', Configuration::getValue ( 'system.module.default', '' ) );
					$component = ModFactory::createComponent ($section);
				} catch ( CmsException $e ) {
					$component = new Component ();
					$component->setInitializeException ( $e );
				}
				break;
		}
		
		ModUtils::setCurrentModule ( $component );
		
		ModUtils::loadLanguage ( $component );
		
		//Check permissions
		if (! $component->checkPermissions ( UserUtils::getCurrentUser ()->getPermissions () ))
			throw new AuthException ( _SITE_ERROR_NOAUTH );
		
		ModUtils::notifyModuleListeners ( $component );
		return $component;
	}
	
	private function generatePanels() {
		$panelList = ModUtils::getPanels();
		$panels = array ();
		foreach ( $panelList as $panel ) {
			ModUtils::setCurrentModule ( $panel );
			try {
				if (! $panel->checkPermissions ( UserUtils::getCurrentUser ()->getPermissions () ))
					continue;
			} catch ( PermissionException $e ) {
				//Ignore not accessible panel
				continue;
			}
			
			PageData::setScreenPosition ( $panel->getPosition () );
			ModUtils::loadLanguage ( $panel );
			
			$panel->setCurrentComponent ( $this->component );
			
			ModUtils::notifyModuleListeners ( $panel );
			$panels [] = $panel;
		}
		return $panels;
	}
	
	/**
	 * Get the template of the specified type from the current template manager
	 * @param string $type
	 * @return Template
	 */
	private function getTemplate($mime, $charset) {
		try {
			$template = $this->templateManager->getTemplate ( $mime, $charset );
		} catch ( TemplateException $e ) {
			$this->templateManager = new DefaultManager ();
			$template = $this->templateManager->getTemplate ( $mime, $charset );
		}
		return $template;
	}
	
	public function getCurrentTemplate() {
		return $this->currentTemplate;
	}
	
	/**
	 * Get the mimetype of the last rendered page
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}
	
	/**
	 * Get the charset of the last rendered page
	 * @return string
	 */
	public function getCharset() {
		return $this->charset;
	}
	
	/**
	 * @return TemplateManager
	 */
	public function getTemplateManager() {
		return $this->templateManager;
	}
	
	public static function getExceptionPage(CmsException $exception) {
		$content = new Content ( $exception->getTitle (), 'alert' );
		
		if ($exception->isFatal () && PageData::getScreenPosition () == Position::CENTER) {
			PageData::clearBreadcrubs ();
			PageData::addToBreadcrubs ( $exception->getTitle () );
		}
		
		$content->addData ( '<h1>' );
		$content->addData ( $exception->getExceptionMessage () );
		$content->addData ( '</h1>' );
		$details = ExceptionHandler::handler ( $exception, true );
		if (CMS_DEBUGMODE) {
			$content->addData ( '<br/><br/><br/><br/>' );
			$content->addData ( '<div style="border:1px dashed #000000;font-size:12px;padding:5px;background-color:#efefef;height:110px;overflow:auto;"><p>' );
			$content->addData ( $details );
			$content->addData ( '</p></div>' );
		}
		return $content;
	}
	
	public function getComponent() {
		return $this->component;
	}
	
	private static function loadSiteLanguage() {
		$languages = FileUtils::fileTree ( 'language', '*.php' );
		$user = UserUtils::getCurrentUser ();
		$path = array_search ( $user->getLanguage () . '.php', $languages );
		if ($path == false) {
			foreach ( $languages as $file => $language ) {
				if (preg_match ( '/(.*)*(_default\.php)+/', $language )) {
					$path = $file;
				}
			}
			if ($path == false)
				return false;
		}
		$path = substr ( $path, 0, strrpos ( $path, '.' ) );
		FileUtils::loadFile ( $path );
		return true;
	}
}

?>