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

namespace dottedBytes\libs\io;

use dottedBytes\libs\pageBuilder\LocaleUtils;

use dottedBytes\libs\utils\String;

use dottedBytes\libs\users\UserUtils;

use dottedBytes\libs\configuration\Configuration;

use PHPMailer;
use phpmailerException;

if (! defined ( 'VALID_REQUEST' ))
	die ( 'Direct access denied!' );

class Mail {
	/**
	 * Send email with default site options in html format
	 *
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 * @param string $FromMail
	 * @param string $FromName
	 * @param string $header
	 * @param string $add_params
	 * @return bool
	 */
	public static function sendMail($to = array(), $subject, $message, $header = NULL, $attachments = array(), $addReply = false) {
		$siteName = Configuration::getValue ( 'system.site.name' );
		FileUtils::loadFile ( 'libs/io/phpMailer/class.phpmailer' );
		$mail = new PHPMailer ( true );
		
		$mail->CharSet = 'UTF-8';
		
		$mail->IsHTML ( true );
		
		$mail->From = Configuration::getValue ( 'system.email.fromMail' );
		$mail->FromName = $siteName;
		
		if ($addReply == true) {
			$replyMail = Configuration::getValue ( 'system.email.replyMail' );
			if ($replyMail == '')
				$replyMail = $mail->from;
			$mail->AddReplyTo ( $replyMail, $siteName );
		}
		$mail->Subject = $subject;
		
		$body = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8">
		</head><body style="font-size:12px;font-family:arial;">';
		$body .= $message;
		
		$body .= '<div style="font-size:10px;font-style:italic;margin-top:150px;border-top:1px dashed #aaaaaa;">';
		if ($mail->From == '')
			$body .= '<p>' . _SITE_EMAIL_FOOTER1 . '</p>';
		$body .= '<p>' . _SITE_EMAIL_FOOTER2 . ' ' . UserUtils::getCurrentUser ()->getIP () . ' (' . LocaleUtils::time () . ').</p>';
		$body .= '<p>&#169;' . date ( 'o' ) . ' - ' . $siteName . '</p></div></body></html>';
		
		$mail->MsgHTML ( $body );
		
		$mail->IsHTML ( true );
		
		if (Configuration::getValue ( 'system.email.smtp.enable', false ) == true) {
			$mail->IsSMTP ();
			$mail->Host = Configuration::getValue ( 'system.email.smtp.host' );
			$mail->Port = Configuration::getValue ( 'system.email.smtp.port' );
			if (Configuration::getValue ( 'system.email.smtp.useAuth' )) {
				$mail->SMTPSecure = "ssl";
				$mail->SMTPAuth = true;
				$mail->Username = Configuration::getValue ( 'system.email.smtp.username' );
				$mail->Password = Configuration::getValue ( 'system.email.smtp.password' );
			}
		} else {
			$mail->IsMail ();
		}
		
		if (! is_array ( $attachments ))
			$attachments = array ($attachments );
		
		if (! is_array ( $to ))
			$to = array ($to => $to );
		
		foreach ( $to as $name => $mailAddr ) {
			if (count ( $to ) == 1) {
				$mail->AddAddress ( $mailAddr, $name );
				break;
			}
			$mail->AddBCC ( $mailAddr, $name );
		}
		
		if (! is_null ( $header ))
			$mail->AddCustomHeader ( $header );
		
		foreach ( $attachments as $attachment ) {
			if (FileUtils::file_exists ( $attachment )) {
				$fileName = String::split ( $attachment, '/' );
				end ( $fileName );
				$mail->AddAttachment ( $attachment, $fileName );
			}
		}
		
		try {
			$mail->Send ();
		} catch ( phpmailerException $e ) {
			throw new IOException ( "MAILER ERROR: " . $e->getMessage () );
		}
		return true;
	}
}

?>