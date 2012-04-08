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

namespace dottedBytes\libs\pageBuilder;

use dottedBytes\libs\configuration\Configuration;

use dottedBytes\libs\utils\String;

use \DateTime;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class LocaleUtils {
	
	private static $lang_lookup = array ('abk' => 'abkhazian', 'afr' => 'afrikaans', 'alb' => 'albanian', 'ale' => 'aleut', 'alg' => 'algonquian languages', 'amh' => 'amharic', 'ara' => 'arabic', 'arc' => 'aramaic', 'arm' => 'armenian', 'asm' => 'assamese', 'aze' => 'azerbaijani', 'ban' => 'balinese', 'bat' => 'baltic(Other)', 'bnt' => 'bantu(Other)', 'bas' => 'basa', 'bak' => 'bashkir', 'baq' => 'basque', 'ben' => 'bengali', 'ber' => 'berber(Other)', 'bho' => 'bhojpuri', 'bre' => 'breton', 'bul' => 'bulgarian', 'bel' => 'byelorussian', 'cat' => 'catalan', 'cel' => 'celtic(Other)', 'chi' => 'chinese', 'cop' => 'coptic', 'cos' => 'corsian', 'cre' => 'cree', 'cze' => 'czech', 'dan' => 'danish', 'dut' => 'dutch', 'eng' => 'english', 'epo' => 'esperanto', 'est' => 'estonian', 'fao' => 'faroese', 'fij' => 'fijian', 'fin' => 'finnish', 'fra' => 'french', 'gez' => 'geez', 'geo' => 'georgian', 'deu' => 'german', 'grb' => 'grebo', 'grc' => 'greek', 'kal' => 'greenlandic', 'guj' => 'gujarati', 'haw' => 'hawaiian', 'heb' => 'hebrew', 'hin' => 'hindi', 'hmo' => 'hiri Motu', 'hun' => 'hungarian', 'ice' => 'icelandic', 'ind' => 'indonesian', 'gai' => 'irish', 'ita' => 'italian', 'jpn' => 'japanese', 'jav' => 'javanese', 'kan' => 'kannada', 'kas' => 'kashmiri', 'kaz' => 'kazakh', 'khm' => 'khmer', 'kho' => 'khotanese', 'kor' => 'korean', 'kur' => 'kurdish', 'lao' => 'lao', 'lav' => 'latvian', 'lit' => 'lithuanian', 'luo' => 'luo(Kenya and Tanzania)', 'mac' => 'macedonian', 'may' => 'malay', 'mal' => 'malayalam', 'mlt' => 'maltese', 'mar' => 'marathi', 'mol' => 'moldavian', 'mon' => 'mongolian', 'nep' => 'nepali', 'nor' => 'norwegian', 'nno' => 'norwegian (Nynorsk)', 'ori' => 'oriya', 'pan' => 'panjabi', 'fas' => 'persian', 'pol' => 'polish', 'por' => 'portuguese', 'pus' => 'pushto', 'ron' => 'romanian', 'rus' => 'russian', 'smo' => 'samoan', 'srd' => 'sardinian', 'ser' => 'serbian', 'scr' => 'serbo-Croatian', 'snd' => 'sindhi', 'sin' => 'singhalese', 'slk' => 'slovak', 'slv' => 'slovenian', 'som' => 'somali', 'esl' => 'spanish', 'sun' => 'sudanese', 'swa' => 'swahili', 'ssw' => 'swazi', 'sve' => 'swedish', 'syr' => 'syriac', 'tgl' => 'tagalog', 'tah' => 'tahitian', 'tgk' => 'tajik', 'tam' => 'tamil', 'tat' => 'tatar', 'tel' => 'telugu', 'tha' => 'thai', 'bod' => 'tibetan', 'tig' => 'tigre', 'tir' => 'tigrinya', 'tur' => 'turkish', 'tuk' => 'turkmen', 'ukr' => 'ukrainian', 'urd' => 'urdu', 'uzb' => 'uzbek', 'vie' => 'vietnamese', 'cym' => 'welsh', 'yao' => 'yao', 'yap' => 'yap', 'yad' => 'yiddish', 'zul' => 'zulu' );
	
	/**
	 * Get browser language
	 *
	 * @param bool $short
	 * @return string
	 */
	public static function getBrowserLanguage($short = false) {
		
		$acceptLangs = String::split ( getenv ( 'HTTP_ACCEPT_LANGUAGE' ), ',' );
		$langs = array ();
		if (($end = strpos ( $acceptLangs [0], "-" )) !== false)
			$acceptLang = substr ( $acceptLangs [0], 0, $end );
		else
			$acceptLang = $acceptLangs [0];
		
		$acceptLang = self::convertToISO4217 ( $acceptLang );
		
		if ($acceptLang === false)
			return false;
		
		if ($short)
			return $acceptLang;
		
		$langs = self::langlist ();
		
		if (! array_key_exists ( $acceptLang, $langs ))
			return $langs ['eng'];
		
		$detectLanguage = $langs [$acceptLang];
		
		return strtolower ( $detectLanguage );
	}
	
	public static function convertToISO4217($shortLanguage) {
		$langs = array_keys ( self::langlist () );
		foreach ( $langs as $short ) {
			if ($shortLanguage == substr ( $short, 0, 2 ))
				return $short;
		}
		return '';
	}
	
	public static function langlist($short = NULL) {
		if ($short == NULL) {
			return self::$lang_lookup;
		}
		
		if ($short == true) {
			return array_keys ( self::$lang_lookup );
		}
		
		return array_values ( self::$lang_lookup );
	}
	
	/**
	 * Format the time in default site time format
	 *
	 * @param string $time a unix timestamp or a valid date string
	 * @param string $format
	 * @param int $mode -1=date 0=time 1=date+time
	 * @return string
	 */
	public static function time($time = false, $format = false, $mode = 1) {
		if ($time instanceof DateTime)
		/* @var $time DateTime */
		$time = $time->format ( 'U' );
		
		//Check for valid timestamp and try to get time from string
		if (! is_numeric ( $time ) && strlen ( $time ) != 10 && $time !== false) {
			$time = strtotime ( $time );
			if ($time == false)
				return false;
		}
		if ($time == false || ! is_numeric ( $time )) {
			$time = time ();
		}
		$timestamp = $time;
		
		if ($format == false) {
			switch ($mode) {
				case - 1 :
					$format = Configuration::getValue ( 'system.site.dateFormat' );
					break;
				
				case 0 :
					$format = Configuration::getValue ( 'system.site.timeFormat' );
					break;
				
				default :
					$format = Configuration::getValue ( 'system.site.dateFormat' ) . " " . Configuration::getValue ( 'system.site.timeFormat' );
					break;
			}
		}
		return strftime ( $format, $timestamp );
	}
}

?>