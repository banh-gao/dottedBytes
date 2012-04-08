<?php
/**
 * @package		OOForm
 * @copyright	Copyright (C) 2010 OOForm. All rights reserved.
 * @author		Daniel Zozin
 * @license		GNU/GPL, see COPYING file
 *
 * This file is part of OOForm.
 * OOForm is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * OOForm is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OOForm.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OOForm\elements\editor;

use OOForm\elements\InputField;

use OOForm\Form;
use OOForm\elements\basic\Textarea;
use OOForm\decorator\FormDecorator;
use CKEditor;

require_once dirname ( __FILE__ ) . '/ckeditor/ckeditor_php5.php';

class Editor extends InputField {
	
	/**
	 * Render an editor with the basic controls
	 * @var int
	 */
	const MODE_BASIC = 1;
	
	/**
	 * Render an editor with full advanced controls
	 * @var int
	 */
	const MODE_FULL = 2;
	
	private $mode = self::MODE_BASIC;
	
	private $config;
	
	private static $included = false;
	
	/**
	 * Create a WYSIWYG editor using ckeditor javascript library
	 */
	public function __construct($name, $value, $label) {
		parent::__construct ( $name, $label );
		$this->tagName = 'div';
		$this->setValue ( $value );
		$this->config = array ();
		$this->config ['skin'] = 'office2003';
		$this->config ['resize_enabled'] = false;
		$this->config ['emailProtection'] = 'encode';
		$this->config ['height'] = '400';
		$this->config ['toolbarCanCollapse'] = false;
		$this->config ['extraPlugins'] = 'MediaEmbed';
		
		$this->config ['toolbar_basic'] = array (array ('Bold', 'Italic', 'Underline', 'Strike', '-', 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyFull', '-', 'Link', 'Unlink', '-', 'FontSize', '-', 'TextColor', 'BGColor', '-', 'Smiley' ) );
		$this->config ['toolbar_complete'] = array (array ('Source', '-', 'Preview', '-', 'SpellChecker', '-', 'Undo', 'Redo', '-', 'RemoveFormat', '-', 'Link', 'Unlink', '-', 'Image', 'MediaEmbed', 'Flash', 'Table', 'Rule', 'Smiley', '-', 'TextColor', 'BGColor' ), '/', array ('Bold', 'Italic', 'Underline', 'Strike', '-', 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyFull', '-', 'Font', 'FontSize' ) );
	}
	
	/**
	 * Set the mode of the editor. The available modes are defined in the class constants MODE_*
	 * @param int $mode
	 * @see Editor::::MODE_BASIC
	 * @see Editor::MODE_FULL
	 */
	public function setMode($mode) {
		$this->mode = $mode;
	}
	
	/**
	 * Get the mode of the editor defined in class constants MODE_*
	 * @return int
	 */
	public function getMode() {
		return $this->mode;
	}
	
	/**
	 * Set a configuration attribute for the ckeditor
	 * @param string $name
	 * @param string $value
	 * @see CKEditor
	 */
	public function setConfigAttribute($name, $value) {
		$this->config [$name] = $value;
	}
	
	/**
	 * get a configuration attribute of the ckeditor
	 * @param string $name
	 * @return array
	 * @see CKEditor
	 */
	public function getConfigAttribute($name) {
		return (array_key_exists ( $name, $this->config )) ? $this->config [$name] : '';
	}
	
	/**
	 * get the configuration attributes of the ckeditor
	 * @return array
	 * @see CKEditor
	 */
	public function getConfigAttributes() {
		return $this->config;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see OOForm\elements.InputField::renderTag()
	 */
	public function renderTag(FormDecorator $decorator) {
		$ckeditor = new CKEditor ();
		$ckeditor->returnOutput = true;
		$ckeditor->basePath = Form::getBaseUrl () . '/elements/editor/ckeditor/';
		
		foreach ( $this->getAttributes () as $name => $value ) {
			if ($name != 'name' && $name != 'value')
				$ckeditor->textareaAttributes [$name] = $value;
		}
		
		switch ($this->mode) {
			case self::MODE_BASIC :
				$this->config ['toolbar'] = 'basic';
				break;
			case self::MODE_FULL :
				$this->config ['toolbar'] = 'complete';
				break;
		}
		
		if ($this->getForm () != null)
			$this->config ['language'] = $this->getForm ()->getLanguage ();
		
		return $ckeditor->editor ( $this->getName (), $this->getValue (), $this->config );
	}
}

?>