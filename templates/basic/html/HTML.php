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

use dottedBytes\templates\basic\BasicManager;

use dottedBytes\libs\configuration\Configuration;
use dottedBytes\libs\io\PageData;
use dottedBytes\templates\basic\html\TemplateHtml;
use dottedBytes\libs\users\UserUtils;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

$user = UserUtils::getCurrentUser ();
$template = BasicManager::getCurrentTemplate ();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
	lang="<?php
	echo $user->getShortLanguage ();
	?>"
	xml:lang="<?php
	echo $user->getShortLanguage ();
	?>">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
$template->autoRefresh ();
$template->loadMetadata ();
$template->loadHtmlHeaders ();
?>
<link rel="stylesheet" type="text/css"
	href="<?php
	echo BASEURL;
	?>/css/layout.css,style.css,content.css,forms.css"
	media="screen,handheld" />
<link rel="stylesheet" type="text/css"
	href="<?php
	echo BASEURL;
	?>/templates/basic/html/css/print.css"
	media="print" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="<?php
echo BASEURL;
?>/templates/basic/html/css/ieHacks.css" media="screen" />
	<![endif]-->
<title><?php
$title = $template->getContent ()->getTitle ();
if ($title != '')
	echo $title . ' - ';

echo PageData::getSiteTitle ();
?></title>
</head>
<body>
<div class="main"><?php
$template->loadMessage ();
?>
<div class="header"><a href="<?php
echo BASEURL;
?>"><?php
echo PageData::getSiteTitle ();
?></a></div>
	<?php
	if ($template->countPanels ( TemplateHtml::TOP ) > 0) {
		echo '<div class="menubar">';
		$template->loadPanels ( TemplateHtml::TOP );
		echo '</div>';
	}
	$template->loadBreadcrubs ();
	
	echo '<div class="center" id="centerCol"><div class="centerpad">';
	$template->loadToolbar ();
	echo '<div class="content">';
	$template->loadContent ();
	echo "</div></div>";
	echo "</div>";
	
	if ($template->countPanels ( TemplateHtml::RIGHT ) > 0) {
		echo '<div class="right">';
		echo "<ul class=\"rightModules\">";
		$template->loadPanels ( TemplateHtml::RIGHT );
		echo "</ul></div>";
	}
	
	if ($template->countPanels ( TemplateHtml::LEFT ) > 0) {
		echo '<div class="left">';
		echo "<ul class=\"leftModules\">";
		$template->loadPanels ( TemplateHtml::LEFT );
		echo "</ul></div>";
	}
	?>
<div class="footer">Copyright &copy; <?php
echo date ( 'Y' ) . ', ' . Configuration::getValue ( 'system.site.name' );
?><br />
<span style="font-size: 10px">Development by Daniel Zozin</span></div>
<?php
$template->loadPanels ( TemplateHtml::BOTTOM );
?></div>
</body>
</html>
