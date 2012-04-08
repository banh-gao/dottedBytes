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

use OOForm\elements\file\UploadedFile;

use OOForm\elements\file\FileValidator;

use OOForm\FormRequest;
use OOForm\validator\InputValidator;
use OOForm\elements\recaptcha;
use OOForm\validator\ValidationException;

if (session_id () == '')
	session_start ();

/**
 * Static class that retrieve the form parameters and validate them
 * @author Daniel Zozin
 *
 */
class FormRequest {
	
	/**
	 * The validation will throw a {@link FormValidationException} when the checked value is not valid
	 * @var int
	 */
	const VALIDATION_EXCEPTION = 1;
	
	/**
	 * The validation will return the default value, if the validator fails the error will
	 * be saved to redisplay it in the form
	 * Use the method {@link FormRequest::sendErrors()} before using the validated values,
	 * if there was some error the form will be redisplayed with error messages 
	 * @var int
	 */
	const VALIDATION_EXPLICIT = 2;
	
	/**
	 * If the validation fails the default value will be returned,
	 * the validation error message can be retrieved through the {@link FormRequest::getErrors()} method
	 * @var int
	 */
	const VALIDATION_NONE = 4;
	
	private $formID = '';
	private $validationMode = self::VALIDATION_EXPLICIT;
	private $errors = array ();
	private $formPage;
	
	/**
	 * This method initialized the form request class
	 * @param string $language 2 letters language code to use, defaults will be used the browser language
	 * @throws FormException When no valid OOForm was detected
	 */
	public function __construct($language = '') {
		$this->loadLanguage ( $language );
		
		if (array_key_exists ( 'formID', $_POST ))
			$this->formID = $_POST ['formID'];
		if (array_key_exists ( "forms", $_SESSION ) && array_key_exists ( $this->formID, $_SESSION ["forms"] ))
			$this->formPage = $_SESSION ["forms"] [$this->formID] ["FORM_PAGE"];
		
		//Unset old values
		unset ( $_SESSION ["forms"] [$this->formID] ["VALUES"] );
		
		$_SESSION ["forms"] [$this->formID] ["FORM_PAGE"] = $this->formPage;
		
		foreach ( $_POST as $fieldName => $value )
			$_SESSION ["forms"] [$this->formID] ["VALUES"] [$fieldName] = $value;
		return true;
	}
	
	private function loadLanguage($language = '') {
		if ($language == '')
			$language = $this->getClientLanguage ();
		
		$langFile = dirname ( __FILE__ ) . '/language/request_' . $language . '.php';
		
		require_once $langFile;
	}
	
	private function getClientLanguage() {
		$oldDir = getcwd ();
		chdir ( dirname ( __FILE__ ) . '/language' );
		$availableLanguages = glob ( 'request_*.php' );
		if (isset ( $_SERVER ['HTTP_ACCEPT_LANGUAGE'] )) {
			$langs = explode ( ',', $_SERVER ['HTTP_ACCEPT_LANGUAGE'] );
			foreach ( $langs as $value ) {
				$choice = substr ( $value, 0, 2 );
				if (in_array ( 'request_' . $choice . '.php', $availableLanguages )) {
					chdir ( $oldDir );
					return $choice;
				}
			}
		}
		chdir ( $oldDir );
		return 'en';
	}
	
	/**
	 * Set the validationMode used to report errors, use the class constants VALIDATION_*
	 * @see FormRequest::VALIDATION_EXCEPTION
	 * @see FormRequest::VALIDATION_EXPLICIT
	 * @see FormRequest::VALIDATION_NONE
	 */
	public function setValidationMode($validationMode) {
		$this->validationMode = $validationMode;
	}
	
	/**
	 * Get the validationMode used to report errors, the value returned is a class costant of type VALIDATION_*
	 * @see FormRequest::VALIDATION_EXCEPTION
	 * @see FormRequest::VALIDATION_EXPLICIT
	 * @see FormRequest::VALIDATION_NONE
	 * @return int
	 */
	public function getValidationMode() {
		return $this->validationMode;
	}
	
	/**
	 * Get the value of a field and optionally validate it, the default value is returned
	 * only if it is not defined or if no validator is specified , if a validator failed null will returned
	 * The validators can be added as arguments, they will be applied in the passed order,
	 * if a validation fails the call returns immediately without executing the subsequents
	 *
	 * @param string $name The name of the form field to elaborate
	 * @param string $default The default value to return
	 * @param InputValidator $_ Validate the user input, if not specified all input is assumed to be valid
	 * @return string
	 */
	public function getValue($name, $default = null) {
		$validators = array_slice ( func_get_args (), 2 );
		
		if (! array_key_exists ( $name, $_POST )) {
			if (count ( $validators ) == 0)
				return $default;
			
			$value = '';
		} else {
			$value = $_POST [$name];
		}
		
		foreach ( $validators as $validator ) {
			if (! ($validator instanceof InputValidator)) {
				$type = is_object ( $validator ) ? get_class ( $validator ) : gettype ( $validator );
				throw new FormException ( 'Passed validator of type ' . $type . ' is not a valid InputValidator' );
			}
			if (! $this->validate ( $name, $validator ))
				return null;
		}
		return $value;
	}
	
	/**
	 * Validate a form value with the specified validators
	 * @param string $name the field to validate
	 * @param InputValidator $validator Validator of the user input
	 * @return boolean
	 */
	private function validate($name, InputValidator $validator) {
		$value = $this->getValue ( $name );
		
		if (! $validator->validate ( $value )) {
			$this->errors [$name] = $validator->getErrorMessage ();
			switch ($this->validationMode) {
				case self::VALIDATION_EXCEPTION :
					throw new ValidationException ( $name, $validator->getErrorMessage () );
					break;
				case self::VALIDATION_EXPLICIT :
					$_SESSION ["forms"] [$this->formID] ["USER_ERRORS"] [$name] = $validator->getErrorMessage ();
					return false;
					break;
				default :
					return false;
					break;
			}
		}
		return true;
	}
	
	/**
	 * Get all passed values without validating it
	 * @return array
	 */
	public function getValues() {
		return $_POST;
	}
	
	/**
	 * Get an array of uploaded files and optionally validate they, the returned array contain only the valid files
	 * The file validators can be added as arguments, they will be applied in the passed order,
	 * if a validation fails the call returns immediately without executing the subsequents
	 *
	 * @param string $name The name of the file field to elaborate
	 * @param FileValidator $_ Validate the uploaded file, if not specified all uploaded files are assumed to be valid
	 * @return array
	 */
	public function getFiles($field) {
		$validators = array_slice ( func_get_args (), 1 );
		
		$files = array ();
		
		if (! array_key_exists ( $field, $_FILES ))
			return $files;
		
		$value = $_FILES [$field];
		try {
			if (! is_array ( $value ["name"] )) {
				$file = new UploadedFile ( $value ["name"], $value ["tmp_name"], $value ["type"], $value ["size"], $value ["error"] );
				if ($this->validateFile ( $field, $file, $validators ))
					$files [] = $file;
			} else {
				for($i = 0; $i < count ( $value ); $i ++) {
					$file = new UploadedFile ( $value ["name"] [$i], $value ["tmp_name"] [$i], $value ["type"] [$i], $value ["size"] [$i], $value ["error"] [$i] );
					
					if ($this->validateFile ( $field, $file, $validators ))
						$files [] = $file;
				}
			}
		} catch ( FormException $e ) {
			throw new FormException ( $e->getMessage () );
		}
		return $files;
	}
	
	private function validateFile($field, UploadedFile $file, $validators) {
		foreach ( $validators as $validator ) {
			
			if (! ($validator instanceof FileValidator)) {
				$type = is_object ( $validator ) ? get_class ( $validator ) : gettype ( $validator );
				throw new FormException ( 'Passed validator of type ' . $type . ' is not a valid FileValidator' );
			}
			//No error, next validator
			if ($file->getError () == '' && $validator->validate ( $file ))
				continue;
			
			if ($file->getError () != '')
				$this->errors [$field] = OOFORM_VALIDATOR_UPLOAD;
			else
				$this->errors [$field] = $validator->getErrorMessage ();
			
			switch ($this->validationMode) {
				case self::VALIDATION_EXCEPTION :
					throw new ValidationException ( $field, $this->errors [$field] );
					break;
				case self::VALIDATION_EXPLICIT :
					$_SESSION ["forms"] [$this->formID] ["USER_ERRORS"] [$field] = $this->errors [$field];
					return false;
					break;
				default :
					return false;
					break;
			}
		}
		return true;
	}
	
	/**
	 * Set a custom error message for the specified field
	 * @param string $fieldName
	 * @param string $message
	 */
	public function setError($fieldName, $message = '') {
		if ($message == '')
			$message = OOFORM_VALIDATOR_GENERIC;
		$_SESSION ["forms"] [$this->formID] ["USER_ERRORS"] [$fieldName] = $message;
		$this->errors [$fieldName] = $message;
	}
	
	/**
	 * Unset an error that was previous generated
	 * @param string $fieldName
	 */
	public function unsetError($fieldName) {
		if (array_key_exists ( $fieldName, $_SESSION ["forms"] [$this->formID] ["USER_ERRORS"] ))
			unset ( $_SESSION ["forms"] [$this->formID] ["USER_ERRORS"] [$fieldName] );
		if (array_key_exists ( $fieldName, $this->errors ))
			unset ( $this->errors [$fieldName] );
	}
	
	/**
	 * Returns all the errors generated at this point
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	 * When validationMode is set to {@link FormRequest::VALIDATION_EXPLICIT} this method must be called before the form values are used.
	 * This method send back the user to the form if there are some error and interrupt the execution after this point
	 *
	 * @param string $message
	 */
	public function sendErrors($message = '') {
		if (count ( self::getErrors () ) > 0) {
			$_SESSION ["forms"] [$this->formID] ["FORM_ERROR"] = $message;
			if (headers_sent ()) {
				echo "<script type=\"text/javascript\">document.location.href='" . $this->formPage . "';</script>\n";
			} else {
				header ( "Location: " . $this->formPage );
			}
			exit ();
		}
	}
	/**
	 * Get the page URL where the form was filled
	 * @return string $formPage
	 */
	public function getFormPage() {
		return $this->formPage;
	}
	
	/**
	 * Get the formID of the submitted form
	 * @return string $formID
	 */
	public function getFormID() {
		return $this->$formID;
	}
	
	/**
	 * set the formID of the submitted form
	 * @param string $formID
	 */
	public function setFormID($formID) {
		$this->formID = $formID;
	}
	
	/**
	 * set the page URL where the form was filled
	 * @param string $formPage
	 */
	public function setFormPage($formPage) {
		$this->formPage = $formPage;
	}

}

?>