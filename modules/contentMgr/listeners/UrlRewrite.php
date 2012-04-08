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

use dottedBytes\libs\io\PageData;
use dottedBytes\libs\modules\Module;
use dottedBytes\libs\pageBuilder\listener\ModuleListener;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class UrlRewrite implements ModuleListener {
	
	/* (non-PHPdoc)
	 * @see dottedBytes\libs\pageBuilder\listener.ModuleListener::process()
	 */
	public function process(Module $module) {
		if (REWRITE_ENABLE) {
			$mimes = array ('text/html', 'text/xml', 'application/rss+xml' );
			if (array_search ( $module->getContent ()->getMimeType (), $mimes ) === false)
				return;
			
			$text = $module->getContent ()->getData ();
			foreach ( self::getSortedSefRules () as $sefRule ) {
				$search = $sefRule [0];
				$replace = $sefRule [1];
				$text = preg_replace ( "|" . preg_quote ( $search ) . "([^\"']*)|", $replace . '$1', $text );
			}
		}
		$text = $this->getValidHTML ( $text );
		
		$module->getContent ()->setData ( $text );
	}
	
	private static function getSortedSefRules() {
		//Sort the array so first will match the longest patterns
		$sortedPatterns = array ();
		foreach ( PageData::getRewriteRules () as $search => $replace ) {
			$sortedPatterns [] = array ($search, $replace );
		}
		usort ( $sortedPatterns, array ('UrlRewrite', 'lengthSort' ) );
		return $sortedPatterns;
	}
	
	private static function lengthSort($a, $b) {
		$la = strlen ( $a [0] );
		$lb = strlen ( $b [0] );
		if ($la < $lb)
			return 1;
		else
			return $lb - $la;
	}
	
	private function getValidHTML($text) {
		preg_match_all ( '/<a\s[^>]*href=\"([^\"]*)\"[^>]*>/siU', $text, $matches );
		foreach ( $matches [1] as $match ) {
			$newLink = preg_replace ( '|(=[a-zA-Z0-9]+)(&)((?!amp;)[a-zA-Z0-9])|', '$1&amp;$3', $match );
			$text = str_replace ( $match, $newLink, $text );
		}
		return $text;
	}
}
?>