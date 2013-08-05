<?php
/**
   * advanced-wordpress-configuration-pluginAPI
   * Some general methods
   *
   * @author     T. Boehning
   * @version	1
   */
class advancedwordpressconfigurationpluginAPI {

  	public function __construct()   {

	}


	/**
	 * Helper method which toggles the value of a boolean variable.
	 * @param bool $boolean
	 */
	protected function swapBoolean(&$boolean) {
		$boolean = !$boolean;
	}


	/**
	 * Helper method which prints the given data (text/array) in a nice way
	 *
	 * @param bool $content
	 * @return void
	 */
	public function debug($content) {
		echo '<pre>';
		echo '<br>BEGIN DEBUGPRINT<br>';
		print_r($content);
		echo '<br>END DEBUGPRINT<br>';
		echo '</pre>';
		echo '<br>';
	}


	/**
	 * Returns a given message if user is logged in
	 * @param string $message the message you want to display
	 * * @return string or false
	*/
	public function noticeLoggedInUser($message) {

 		if ( is_user_logged_in() ) {
 			return '<div class="internalNotice">'.$message.'</div>';
 		}
		return false;
	}



	/**
	* sends a notification email to website@email.com
	* @param string $content
	*/
	public function sendEmailNotification($content) {
		$email_to = array( 'email@email.email' );
		$subject = 'Website Notification';
		$message_headers = '';

		foreach ( $email_to as $email )
			@wp_mail($email, $subject, $content, $message_headers);
	}



	/**
	 * Truncates a passed text to a defined number of chars. If the text is
	 * shorter than the passed limit, the text is simply returned. If the text is
	 * truncated, three dots (...) are appended. The text is only shortened at white
	 * spaces and not in the middle of a word.
	 *
	 * @param $text text to truncate.
	 * @param $limit limit of maximum chars.
	 */
	public function truncate($text, $limit) {
		if (strlen($text) <= $limit) {
			// if text's length is within limit simply return the text unchanged.
			return $text;
		} else {
			// ... otherwise cut text after last space and add three dots.
			$subtext = mb_substr($text, 0, $limit - 3);
			$words = explode(' ', $subtext);
			$excut = - (mb_strlen($words[count($words) - 1]));
			if ($excut < 0) {
				$text = mb_substr($subtext, 0, $excut);
			} else {
				$text = $subtext;
			}
			return $text . "...";
		}

	}


	/**
	 * Extract file extension from filename.
	 * Returns empty string if extension could not
	 * be extracted successfully.
	 */
	private function getFileExtension($filename) {
		$dotPos = strrpos($filename, '.');
		if ($dotPos === FALSE || $dotPos <= 1) {
			return '';
		}
		return substr($filename, $dotPos + 1);
	}

}

?>
