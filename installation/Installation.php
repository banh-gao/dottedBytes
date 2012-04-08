<?php

define('LAST_PAGE' , (int) (array_key_exists ( 'lastPage', $_SESSION )) ? $_SESSION ['lastPage'] : 0);

$_SESSION['lastPage'] = LAST_PAGE;

class Installation {
	
	public static function loadNextPage() {
		if(LAST_PAGE != 0)
			self::checkStep();
		else
			self::loadCurrentPage();
	}
	
	private static function checkStep($lastPage) {
		include './pages/step' . ( int ) LAST_PAGE . '_check.php';
	}
	
	public static function setCheckResult($isValid) {
		if($isValid)
			self::loadCurrentPage();
		else
			include './pages/step' . LAST_PAGE . '.php';
	}
	
	private static function loadCurrentPage() {
		$path = './pages/step' . (LAST_PAGE + 1) . '.php';
		
		if (! file_exists ( $path )) {
			self::loadErrorPage ("File ".$path." not found");
			return;
		}
		
		include $path;
		$_SESSION ['lastPage'] += 1;
	}
	
	private static function loadErrorPage($cause) {
		$_REQUEST['cause'] = $cause;
		include './pages/error.php';
	}
}

?>