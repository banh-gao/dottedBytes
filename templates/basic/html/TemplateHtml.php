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

namespace dottedBytes\templates\basic\html;

use dottedBytes\libs\configuration\Configuration;
use dottedBytes\libs\io\PageData;
use dottedBytes\libs\users\UserUtils;
use dottedBytes\libs\pageBuilder\template\HtmlTemplate;
use dottedBytes\libs\io\FileUtils;
use dottedBytes\libs\modules\PanelException;
use dottedBytes\libs\modules\ModUtils;
use dottedBytes\libs\modules\Component;
use dottedBytes\libs\modules\Panel;
use dottedBytes\libs\pageBuilder\template\PanelList;
use dottedBytes\libs\pageBuilder\template\Template;
if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

	//Build the real html template
class TemplateHtml extends HtmlTemplate {
	
	const BREADCRUBS_SYMBOL = "&raquo;";
	
	/**
	 * Load panels in page by sending it in output
	 *
	 * @param int $position
	 */
	public function loadPanels($position) {
		$panels = $this->panelList->getPanelsByPos ( $position );
		//Write html head
		foreach ( $panels as $panel ) {
			/* @var $panel Panel */
			/* @var $content Content */
			
			//Don't display panels without content
			if ($panel->getContent ()->getData () == '')
				continue;
			
			$content = $panel->getContent();
			
			if ($panel->getParams () == 'special') {
				echo $content;
			} else {
				echo "<li class=\"module\">";
				if ($content->getTitle () != "") {
					$icon = ($content->getIcon() != '') ? ' <img src="'.$content->getIcon().'" alt="'.$content->getTitle().'" height="15"/>' : '';
					echo "<div class=\"head\">{$content->getTitle()}{$icon}</div>";
				}
				echo "<div class=\"body\">";
				echo $content->getData ();
				echo "</div></li>\n";
			}
		}
	}
	
	public function render() {
		FileUtils::loadFile('templates/basic/html/HTML');
	}
	
	public function getFormDecorator() {
		return new BasicFormDecorator();
	}
	
	public function loadToolbar() {
		$bar = $this->getTitle ();
		$toolbar = PageData::getToolbar ()->getHTML ();
		if ($toolbar != '')
			$bar .= '<div class="buttons">' . $toolbar . '</div>';
		
		if ($bar != '') {
			echo '<div class="toolbar">';
			echo $bar;
			echo '</div>';
		}
	}
	
	public function loadContent() {
		echo $this->getContent ()->getData ();
	}
	
	public function loadMessage() {
		$msg = UserUtils::getCurrentUser ()->getMsg ();
		if ($msg)
			echo '<div class="message">' . $msg . '</div>';
	}
	
	private function getTitle() {
		$icon = $this->getContent ()->getIcon ();
		$style = ($icon == false) ? 'noIcon' : 'icon';
		$title = '<img src="'.$icon.'" title="'.$this->getContent ()->getTitle ().'"/>' . $this->getContent ()->getTitle ();
		if ($title != '')
			return '<span class="' . $style . '"></span><span class="title">' . $title . '</span>';
		return '';
	}
	
	public function loadBreadcrubs() {
		$html = '<div class="breadcrubs"><span>';
		$breadcrubs = PageData::getBreadcrubs ();
		for($i = 0; $i < count ( $breadcrubs ) - 1; $i ++) {
			$label = $breadcrubs [$i] [0];
			$link = $breadcrubs [$i] [1];
			if ($link == '#')
				$html .= $label . ' ' . self::BREADCRUBS_SYMBOL . ' ';
			else
				$html .= '<a href="' . $link . '">' . $label . '</a> ' . self::BREADCRUBS_SYMBOL . ' ';
		}
		$label = $breadcrubs [$i] [0];
		$link = $breadcrubs [$i] [1];
		if ($link == '#')
			$html .= '<a class="active">' . $label . '</a></span></div>';
		else
			$html .= '<a class="active" href="' . $link . '">' . $label . '</a></span></div>';
		echo $html;
	}
	
	public function autoRefresh() {
		$expire = Configuration::getValue ( 'system.users.sessionExpire' );
		if ($expire != 0)
			echo '<meta http-equiv="refresh" content="' . ($expire + 1) . '" />' . "\n";
	}
}

?>