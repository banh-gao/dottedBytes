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

namespace OOForm;

use OOForm\FormRequest;

//Provide class autoload using namespace structure
function autoload($class) {
	if (substr ( $class, 0, 6 ) != 'OOForm')
		return;
	$path = dirname ( dirname ( __FILE__ ) ) . '/' . str_replace ( '\\', '/', $class ) . '.php';
	
	if (file_exists ( $path ))
		require_once ($path);
}
spl_autoload_register ( 'OOForm\autoload' );

use ArrayObject;
use OOForm\decorator\FormDecorator;
use OOForm\decorator\DefaultDecorator;
use OOForm\elements\FormElement;
use OOForm\elements\HtmlTag;
use OOForm\elements\ElementContainer;
use OOForm\elements\basic\Hidden;
use OOForm\elements\basic\File;

if (session_id () == '')
	session_start ();

class Form extends HtmlTag implements ElementContainer {
	
	/**
	 * Decorator that renders the elements
	 * @var FormDecorator
	 */
	private $decorator;
	
	/**
	 * Language of the form
	 * @var string
	 */
	private $language;
	
	/**
	 * Error message of the form
	 * @var string
	 */
	private $errorMessage;
	
	/**
	 * Store form request object
	 * @var FormRequest
	 */
	private static $formRequest = 0;
	
	/**
	 * Base url of the framework
	 * @var string
	 */
	private static $baseUrl;
	
	/**
	 * Base path of the framework
	 * @var string
	 */
	private static $basePath;
	
	/**
	 * Create a form with the specified ID, the formID will be passed to the elaboration page with a hidden field
	 * @param string $formID
	 */
	public function __construct($formID) {
		parent::__construct ( 'form' );
		
		$this->setAttribute ( 'id', $formID );
		
		$_SESSION ["forms"] [$this->getFormId ()] ["FORM_PAGE"] = $this->getCurrentPage ();
		$this->addElement ( new Hidden ( 'formID', $this->getFormId () ) );
		
		//Set default decorator
		$this->decorator = new DefaultDecorator ();
		
		//Set default attributes
		$this->setAttribute ( 'enctype', 'multipart/form-data' );
		$this->setAttribute ( 'method', 'post' );
		$this->setAttribute ( 'action', $_SERVER ['PHP_SELF'] );
		$this->setAttribute ( 'class', 'form' );
		
		//Set default language (browser language)
		$this->setLanguage ();
		
		$this->errorMessage = $this->detectErrorMessage ();
	}
	
	/**
	 * Set the current form page, it will be used during the validation to come back to this form
	 * @param string $formPage
	 */
	public function setFormPage($formPage) {
		$_SESSION ["forms"] [$this->getFormId ()] ["FORM_PAGE"] = $formPage;
	}
	
	/**
	 * Set a session value
	 * @param string $name
	 * @param mixed $value
	 */
	public function setSessionValue($name, $value) {
		if (! array_key_exists ( "PARAMS", $_SESSION ["forms"] [$this->getFormId ()] ))
			$_SESSION ["forms"] [$this->getFormId ()] ["PARAMS"] = array ();
		$_SESSION ["forms"] [$this->getFormId ()] ["PARAMS"] [$name] = $value;
	}
	
	/**
	 * Get a session value or null if not exists
	 * @param string $name
	 * @return mixed
	 */
	public function getSessionValue($name) {
		if (array_key_exists ( "PARAMS", $_SESSION ["forms"] [$this->getFormId ()] ))
			if (array_key_exists ( $name, $_SESSION ["forms"] [$this->getFormId ()] ["PARAMS"] ))
				return $_SESSION ["forms"] [$this->getFormId ()] ["PARAMS"] [$name];
		return null;
	}
	
	/**
	 * Unset a session value if any
	 * @param string $name
	 */
	public function unsetSessionValue($name) {
		if (array_key_exists ( "PARAMS", $_SESSION ["forms"] [$this->getFormId ()] ))
			if (array_key_exists ( $name, $_SESSION ["forms"] [$this->getFormId ()] ["PARAMS"] ))
				unset ( $_SESSION ["forms"] [$this->getFormId ()] ["PARAMS"] [$name] );
	}
	
	/**
	 * Get the current form page
	 * @return string
	 */
	public function getFormPage() {
		return $_SESSION ["forms"] [$this->getFormId ()] ["FORM_PAGE"];
	}
	
	private function getCurrentPage() {
		$pageURL = (isset ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] == 'on') ? 'https://' : 'http://';
		$pageURL .= $_SERVER ['SERVER_PORT'] != '80' ? $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"] : $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'];
		return $pageURL;
	}
	
	/**
	 * Set the language file of the specified language, defaults use the browser language
	 * @param string $language two character language code
	 */
	public function setLanguage($language = '') {
		if ($language == '')
			$language = $this->getClientLanguage ();
		
		$langFile = dirname ( __FILE__ ) . '/language/form_' . $language . '.php';
		$this->language = $language;
	}
	
	private function loadLanguage() {
		require_once dirname ( __FILE__ ) . '/language/form_' . $this->language . '.php';
	}
	
	private function getClientLanguage() {
		$oldDir = getcwd ();
		chdir ( dirname ( __FILE__ ) . '/language' );
		$availableLanguages = glob ( 'form_*.php' );
		if (isset ( $_SERVER ['HTTP_ACCEPT_LANGUAGE'] )) {
			$langs = explode ( ',', $_SERVER ['HTTP_ACCEPT_LANGUAGE'] );
			foreach ( $langs as $value ) {
				$choice = substr ( $value, 0, 2 );
				if (in_array ( 'form_' . $choice . '.php', $availableLanguages )) {
					chdir ( $oldDir );
					return $choice;
				}
			}
		}
		chdir ( $oldDir );
		return 'en';
	}
	
	/**
	 * Get the language used for all the forms in the page
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}
	
	private function detectErrorMessage() {
		if (! array_key_exists ( "forms", $_SESSION ))
			return "";
		if (! array_key_exists ( $this->getFormId (), $_SESSION ["forms"] ))
			return "";
		if (! array_key_exists ( "FORM_ERROR", $_SESSION ["forms"] [$this->getFormId ()] ))
			return "";
		
		return $_SESSION ["forms"] [$this->getFormId ()] ["FORM_ERROR"];
	}
	
	/**
	 * Get the form error message
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}
	
	/**
	 * Get the errors reported from the validation
	 * @return array
	 */
	public function getErrors() {
		if (! array_key_exists ( "forms", $_SESSION ))
			return array ();
		if (! array_key_exists ( $this->getFormId (), $_SESSION ["forms"] ))
			return array ();
		if (! array_key_exists ( "USER_ERRORS", $_SESSION ["forms"] [$this->getFormId ()] ))
			return array ();
		
		return $_SESSION ["forms"] [$this->getFormId ()] ["USER_ERRORS"];
	}
	
	/**
	 * Returns true if there was an error in submission
	 * @return boolean
	 */
	public function isError() {
		$formID = $this->getFormID ();
		if (! array_key_exists ( "forms", $_SESSION ))
			return false;
		if (! array_key_exists ( $formID, $_SESSION ["forms"] ))
			return false;
		return array_key_exists ( "USER_ERRORS", $_SESSION ["forms"] [$formID] );
	}
	
	/**
	 * Add an element to the form, the element will be added only one time per form.
	 * The elements are compared using the {@link FormElement::getFormElementID()} method.
	 * An element can be associated only to one form, if the element is added to another form then it will be removed from the first form.
	 * However the element is also rendered in the first form if the render call comes before the other form adding call.
	 * @param FormElement $element
	 */
	public function addElement(FormElement $element) {
		$element->setForm ( $this );
		
		if ($element->getParentContainer () == null)
			$element->setParentContainer ( $this );
		
		$this->addChild ( $element );
	}
	
	/**
	 * Remove the specified element from the form
	 * @param FormElement $element
	 */
	public function removeElement(FormElement $element) {
		return $this->removeChild ( $element );
	}
	
	/**
	 * Returns an array that contains all the elements in the form
	 * @return array
	 */
	public function getElements() {
		$res = array ();
		foreach ( $this->getChildren () as $child ) {
			if ($child instanceof FormElement)
				$res [] = $child;
		}
		return $res;
	}
	
	/**
	 * Returns the formID
	 * @return string
	 */
	public function getFormId() {
		return $this->getAttribute ( 'id' );
	}
	
	/**
	 * Render the form using the decorator specified with {@link Form::setDecorator()}, defaults use the DefaultDecorator
	 * @return string the generated html code 
	 */
	public function render() {
		$this->loadLanguage ();
		
		$this->decorator->addJavascript ( self::getBaseUrl () . '/libs/jquery/jquery.js' );
		
		$rendered = $this->decorator->render ( $this );
		unset ( $_SESSION ["forms"] [$this->getFormId ()] ["FORM_ERROR"] );
		unset ( $_SESSION ["forms"] [$this->getFormId ()] ["USER_ERRORS"] );
		unset ( $_SESSION ["forms"] [$this->getFormId ()] ["VALUES"] );
		return $rendered;
	}
	
	/**
	 * Returns the renderer use by the form 
	 * @return FormDecorator
	 */
	public function getDecorator() {
		return $this->decorator;
	}
	
	/**
	 * Set the renderer that the form must use to render itself
	 * @param FormDecorator $FormDecorator
	 */
	public function setDecorator(FormDecorator $decorator) {
		$this->decorator = $decorator;
	}
	
	/**
	 * Get an object that represent the values of the submitted form, throw a FormException if there is no request
	 * @return FormRequest
	 * @throws FormException
	 */
	public static function getRequest() {
		if (self::$formRequest === 0) {
			if (! array_key_exists ( 'formID', $_POST ) || $_POST ['formID'] == '' || ! array_key_exists ( "forms", $_SESSION )) {
				throw new FormException ( 'No valid OOForm request detected' );
			}
			self::$formRequest = new FormRequest ();
		}
		return self::$formRequest;
	}
	
	/**
	 * Get the absolute base url of the framework
	 * @return string
	 */
	public static function getBaseUrl() {
		if (self::$baseUrl == '')
			self::$baseUrl = dirname ( "http://" . $_SERVER ['HTTP_HOST'] . str_replace ( $_SERVER ['DOCUMENT_ROOT'], '', __FILE__ ) );
		return self::$baseUrl;
	}
	
	/**
	 * Get the absolute base path of the framework
	 * @return string
	 */
	public static function getBasePath() {
		if (self::$basePath == '')
			self::$basePath = dirname ( __FILE__ );
		return self::$basePath;
	}
}

?>