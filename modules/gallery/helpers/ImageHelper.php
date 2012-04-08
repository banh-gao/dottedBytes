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

namespace dottedBytes\modules\gallery\helpers;

use dottedBytes\libs\modules\ModUtils;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class ImageHelper {
	
	public static function createThumb(GalleryElement $element) {
		if ($element->getType () == 'album')
			return false;
		
		$fullfile = $element->getPath ();
		
		/* File di destinazione */
		$new_file = $element->getThumbPath ();
		
		/* Info Immagine originale */
		$info = getimagesize ( $fullfile );
		
		$new_width = $info [0];
		$new_height = $info [1];
		
		// Se l'immagine e' piu' larga dei valori di configurazione, riassegno le dimensioni
		$thumbWidth = ModUtils::getCurrentModule ()->getConfigValue ( 'images.thumbWidth', 120 );
		if ($new_width > $thumbWidth) {
			$new_width = $thumbWidth;
			$new_height = ceil ( $new_width * $info [1] / $info [0] );
		}
		
		// Se l'immagine e' piu' alta dei valori di configurazione, riassegno le dimensioni
		$thumbHeight = ModUtils::getCurrentModule ()->getConfigValue ( 'images.thumbHeight', 120 );
		if ($new_height > $thumbHeight) {
			$new_height = $thumbHeight;
			$new_width = ceil ( $new_height * $info [0] / $info [1] );
		}
		$extension = substr ( $fullfile, strrpos ( $fullfile, '.' ) + 1 );
		switch ($extension) {
			case "gif" :
				$source = imagecreatefromgif ( $fullfile );
				$out = 1;
				break;
			case "png" :
				$source = imagecreatefrompng ( $fullfile );
				$out = 2;
				break;
			case "jpg" :
				$source = imagecreatefromjpeg ( $fullfile );
				$out = 3;
				break;
			case "jpeg" :
				$source = imagecreatefromjpeg ( $fullfile );
				$out = 4;
				break;
			default :
				$source = imagecreatefromjpeg ( $fullfile );
				$out = 3;
		}
		
		// Creo un immagine delle dimensioni desiderate
		$thumb = imagecreatetruecolor ( $new_width, $new_height );
		
		// Se e' un file gif setto un background "trasparente"
		if ($extension == "gif" || $extension == "png") {
			$transparent = imagecolorallocatealpha ( $thumb, 255, 255, 255, 127 );
			imagefill ( $thumb, 0, 0, $transparent );
		}
		
		imagecopyresampled ( $thumb, $source, 0, 0, 0, 0, $new_width, $new_height, imagesx ( $source ), imagesy ( $source ) );
		
		switch ($out) {
			case 1 :
				function_exists ( 'imagegif' ) ? imagegif ( $thumb, $new_file ) : imagepng ( $thumb, $new_file, 9 );
				break;
			case 2 :
				imagepng ( $thumb, $new_file, 9 );
				break;
			case 3 :
				imagejpeg ( $thumb, $new_file, 80 );
				break;
			case 4 :
				imagejpeg ( $thumb, $new_file, 80 );
				break;
			default :
				imagejpeg ( $thumb, $new_file, 80 );
		}
		
		imagedestroy ( $source );
		return true;
	}
	
	public static function resizeOriginal(GalleryElement $album) {
		$fullfile = $album->getPath ();
		
		$new_file = $album->getPath () . ".normal.tmp";
		
		$info = getimagesize ( $fullfile );
		
		$new_width = $info [0];
		$new_height = $info [1];
		
		// Se l'immagine e' piu' larga dei valori di configurazione, riassegno le dimensioni
		if ($new_width > ModUtils::getCurrentModule ()->getConfigValue( 'images.maxWidth' )) {
			$new_width = ModUtils::getCurrentModule ()->getConfigValue ( 'images.maxWidth' );
			$new_height = ceil ( $new_width * $info [1] / $info [0] );
		}
		
		$extension = substr ( $fullfile, strrpos ( $fullfile, '.' ) + 1 );
		switch ($extension) {
			case "gif" :
				$source = imagecreatefromgif ( $fullfile );
				$out = 1;
				break;
			case "png" :
				$source = imagecreatefrompng ( $fullfile );
				$out = 2;
				break;
			case "jpg" :
				$source = imagecreatefromjpeg ( $fullfile );
				$out = 3;
				break;
			case "jpeg" :
				$source = imagecreatefromjpeg ( $fullfile );
				$out = 4;
				break;
			default :
				$source = imagecreatefromjpeg ( $fullfile );
				$out = 3;
		}
		
		// Creo un immagine delle dimensioni desiderate
		$thumb = imagecreatetruecolor ( $new_width, $new_height );
		
		// Se e' un file gif setto un background "trasparente"
		if ($extension == "gif" || $extension == "png") {
			$transparent = imagecolorallocatealpha ( $thumb, 255, 255, 255, 127 );
			imagefill ( $thumb, 0, 0, $transparent );
		}
		
		imagecopyresampled ( $thumb, $source, 0, 0, 0, 0, $new_width, $new_height, imagesx ( $source ), imagesy ( $source ) );
		
		switch ($out) {
			case 1 :
				function_exists ( 'imagegif' ) ? imagegif ( $thumb, $new_file ) : imagepng ( $thumb, $new_file, 9 );
				break;
			case 2 :
				imagepng ( $thumb, $new_file, 9 );
				break;
			case 3 :
				imagejpeg ( $thumb, $new_file, 80 );
				break;
			case 4 :
				imagejpeg ( $thumb, $new_file, 80 );
				break;
			default :
				imagejpeg ( $thumb, $new_file, 80 );
		}
		
		imagedestroy ( $source );
		
		// Se l'immagine originale era gia' pronta per il layout la cancello e tengo solo la nuova creata
		if ($new_width < ModUtils::getCurrentModule ()->getConfigValue ( 'images.maxWidth' )) {
			unlink ( $fullfile );
		}
		rename ( $new_file, $fullfile );
	}
}

?>