<?php

namespace dottedBytes\libs\pageBuilder;

use dottedBytes\libs\users\User;

use dottedBytes\libs\errorHandling\ErrorToException;
use dottedBytes\libs\pageBuilder\PageBuilder;
use dottedBytes\libs\io\FileUtils;

class Resources {
	/**
	 * Get image url for system icon (16x16)
	 *
	 * @param string $imageName
	 * @return string
	 */
	public static function getSysIcon($imageName,$asTag=false,$tagTitle='') {
		return self::getIcon($imageName, 'small',$asTag,$tagTitle);
	}
	
	/**
	 * Get image url for medium icon (32x32)
	 *
	 * @param string $imageName
	 * @return string
	 */
	public static function getMediumIcon($imageName,$asTag=false,$tagTitle='') {
		return self::getIcon($imageName, 'medium',$asTag,$tagTitle);
	}
	
	/**
	 * Get image url for for big icon (48x48)
	 *
	 * @param string $t
	 * @return string
	 */
	public static function getBigIcon($imageName,$asTag=false,$tagTitle='') {
		return self::getIcon($imageName, 'big',$asTag,$tagTitle);
	}
	
	/**
	 * Get image url used in the current template
	 * @param string $imageName - The name of the image without extension
	 * @param string $imageDir - The subdirectory in the icons directory of the current template
	 * @return mixed - The image url or the img tag, empty string if not found
	 */
	public static function getIcon($imageName, $imageDir,$asTag=false,$tagTitle='') {
		$templatePath = BASEPATH . '/templates/' . PageBuilder::getInstance ()->getTemplateName () . '/images/icons/' . $imageDir;
		if (file_exists ( "$templatePath/$imageName.png" )) {
			$templateUrl = BASEURL . FileUtils::stripBasePath ( $templatePath );
			$url = $templateUrl . "/$imageName.png";
		} else {
			$url = '';
		}
		
		if($asTag) {
			return self::getImageTag($url, $tagTitle);
		}
		
		return $url;
	}
	
	public static function getImageTag($url,$title) {
		return '<img src="'.$url.'" alt="'.$title.'" title="'.$title.'"/>';
	}
	
	public static function getGravatar(User $user, $size = 80, $params = null) {
		$url = 'http://www.gravatar.com/avatar/' . md5 ( strtolower ( trim ( $user->getEmail () ) ) ) . "?s=$size&d=mm";
		try {
			if (file_get_contents ( $url ) === false)
				$url = self::getBigIcon ( 'user', '', '', '', 1 );
		} catch ( ErrorToException $e ) {
			$url = self::getBigIcon ( 'user', '', '', '', 1 );
		}
		return '<img src="' . $url . "\" alt=\"{$user->getUsername()}\" title=\"{$user->getUsername()}\"$params/>";
	}
}

?>