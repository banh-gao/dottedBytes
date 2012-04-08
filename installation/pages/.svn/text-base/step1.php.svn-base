<?php
require_once BASEPATH . '/../libs/html/form/OOForm/Form.php';

use OOForm\elements;
use OOForm\elements\basic;
use OOForm\elements\group;

?>
<html>
<body>
<?php

$form = new OOForm\Form ( 'sampleForm' );
$form->setAttribute ( 'action', 'elaboration.php' );

$form->addElement ( new elements\Separator ( "OOForm basic form" ) );

//Text
$form->addElement ( new basic\Text ( "text1", '', 'Textbox' ) );

//Password
$form->addElement ( new basic\Password ( "password", '', 'Password box' ) );

//Basic Editor
$basicEditor = new OOForm\elements\editor\Editor ( 'basicEditor', '', 'Basic rich editor' );
$basicEditor->setMode ( $basicEditor::MODE_BASIC );
$form->addElement ( $basicEditor );

//Captcha
$form->addElement ( new OOForm\elements\recaptcha\Recaptcha ( "6Le9xMASAAAAAD72b8Dfu57ShgDOQYsNdk3tiVBg" ) );

//Submit
$form->addElement ( new basic\Submit ( "Submit Button" ) );

echo $form->render();
?>
</body>
</html>