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

namespace dottedBytes\modules\contentMgr\helpers\editor;

use dottedBytes\libs\io\PageData;

use dottedBytes\libs\pageBuilder\Content;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ContentMgr_editor {
	
	private $content;
	
	public function getContent() {
		$_SESSION ['isEditorUser'] = true;
		$this->content = new Content ();
		$task = PageData::getParam ( 'task' );
		$page = substr ( $task, strpos ( $task, '_' ) + 1 );
		switch ($page) {
			case 'new' :
				$this->content = HTML_editor_content::new_form ();
				break;
			case 'edit' :
				$this->content = HTML_editor_content::edit_form ();
				break;
			case 'save' :
				CLASS_content::save ();
				break;
			case 'update' :
				CLASS_content::update ();
				break;
			case 'tags' :
				$l = new TagsSuggestion();
				$l->processRequest();
				break;
			case 'publish' :
				CLASS_content::publish ();
				break;
			case 'unpublish' :
				CLASS_content::unpublish ();
				break;
			case 'delete' :
				CLASS_content::delete ();
				break;
			case 'imageManager' :
				exit ();
				break;
		}
		
		return $this->content;
	}
}

?>