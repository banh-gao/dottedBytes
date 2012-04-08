<?php

namespace OOForm\elements\group;

/**
 * Set the group view as fieldset, use the {@link ElementGroup::setFieldsetLabel()} to set the fieldset label.
 * If no label is specified the fieldset label is retrieved from the call of {@link InputField::getLabel()}
 * @param boolean $fieldset
 */
use OOForm\decorator\FormDecorator;

class Fieldset extends ElementGroup {
	
	public function __construct($label = '') {
		parent::__construct ( $label );
		$this->tagName = 'fieldset';
		$this->setAttribute ( 'id', 'fieldset' . $this->getFormElementID () );
	}
	
	public function renderLabel(FormDecorator $renderer) {
		return '';
	}
	
	public function renderTag(FormDecorator $decorator) {
		$out = '<legend>' . $this->getLabel () . '</legend>' . "\n";
		$out .= '<div class="body" id="fieldset' . $this->getFormElementID () . 'Body">' . "\n";
		$out .= parent::renderTag( $decorator );
		$out .= "</div>";
		return $out;
	}
}

?>