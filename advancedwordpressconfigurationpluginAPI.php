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
		$email_to = array( 'website@email.com' );
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
	 * Returns a string for a class attribute, which has either the
	 * values 'firstItem' or 'lastItem' (or both). Whether the
	 * these values apply is determined by the position of the element
	 * and the maximum positions available. If the element is neither
	 * the first nor the last element, an empty string is returned.
	 *
	 * @param int $position
	 * @param int $max
	 */
	protected function getPositionExtraClass($position, $max) {
		$extraClass = '';

		// set extra class when item is first
		if ($position == 0) {
			$extraClass .= 'firstItem';
		}

		// set extra class when item is last
		if ($position == $max) {
			if ($extraClass) {
				$extraClass .= ' '; // add spacer if 'firstItem' is already set.
			}
			$extraClass .= 'lastItem';
		}

		return $extraClass;
	}


	/**
	 * Splits the content into two columns based on the more-tag in wordpress backend
	 * columns get the following classes: column, columnLeft/columnRight
	 *
	 * @param string $content (optional) the content which will be split, if not set, the_content will be used.
	 * @return string $return
	 */
	public function  split_content($content = null) {
		global $more;
		$more = true;

		//repeater fields are passed to the function, regular content like in works just call this function and get the main content field
		if($content === null) {
			$content = get_the_content('more');

			//split the content at "more" tags
			$content = preg_split('/<span id="more-\d+"><\/span>/i', $content);
		}

		else {
			//split the content at "more" tags, filters have already been applied, thats why we look for the comment...
			$content = explode('<!--more-->', $content);
		}


		//if there is a more tag, return two columns
		if(count($content) > 1 ) {

			//save the parts to array
			for($c = 0, $csize = count($content); $c < $csize; $c++) {
				$content[$c] = apply_filters('the_content', $content[$c]);
			}

			$return = '<div class="column columnLeft">'. array_shift($content). '</div>';
			//dump all the rest in second column
			$return .= '<div class="column columnRight">'. $this->remove_empty_p(implode($content)).'</div>';
		}

		//no more tag? just return the content
		else {
			$return = $content[0];
		}

		return $return;
	}

	/**
 	 * removes empty paragraphs
	 * @param string $content
	 * @return string html content
	 */
	function remove_empty_p($content){
		$content = force_balance_tags($content);
		return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
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
