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

namespace dottedBytes\modules\contentMgr\helpers;

// no direct access
use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\users\permissions\Permission;

use dottedBytes\libs\modules\menu\MenuNode;

use dottedBytes\libs\modules\menu\Menu;

use dottedBytes\libs\modules\PageNotFoundException;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\io\PageData;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access is not allowed' );

class ContentMenu extends MenuNode {
	
	public function addArticle(ArticleContent $article) {
		if ($article->getId () == 0)
			return;
		
		$published = (UserUtils::getCurrentUser()->hasPermission( 'editor' )) ? true : $article->isPublished ();
		
		if (! $published)
			return false;
		
		if ($article instanceof LinkContent) {
			$link = BASEURL . '/' . $article->getText ();
			$sefRules = explode ( "|", $article->getSubtitle () );
			$rules = array ();
			foreach ( $sefRules as $sefRule ) {
				$sefRule = explode ( ":", $sefRule );
				if (count ( $sefRule ) == 2)
					$rules [$sefRule [0]] = intval ( $sefRule [1] );
			}
			PageData::setSefReplaceRule ( $link, $rules );
		} else {
			$link = BASEURL . '/index.php?section=contentMgr&itemid=' . $article->getId ();
		
		}
		
		//Wrap log titles
		$label = String::wordSplit ( $article->getTitle (), 0, HTML_content::TITLE_MAXLENGTH );
		if (strlen ( $article->getTitle () ) > HTML_content::TITLE_MAXLENGTH)
			$label .= '...';
		$tooltip = $article->getTitle ();
		
		if (! $article->isPublished ())
			$label = '[' . $label . ']';
		
		$child = new MenuNode ( $article->getId () );
		//FIXME: use tags hierachy
		//$node->setParentId ( $article->getParentId () );
		$child->setLabel ( $label );
		$child->setLink ( $link );
		$child->setImage ( $article->getIcon () );
		$child->setTooltip ( $tooltip );
		
		$this->addChild ( $child );
		
		if (PageData::getParam ( 'itemid', 0 ) == $article->getId ()) {
			$child->setSelected ( true );
		}
	}
	
	public function render($class = 'menu') {
		//Add children and parents only for selected node
		try {
		
		} catch ( PageNotFoundException $e ) {
		
		}
		
		return parent::render ( $class );
	}
}

?>