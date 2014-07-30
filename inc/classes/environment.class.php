<?php
/**
 * JAF Environment-engine
 *
 * This file contains the complete environment-class of jaf
 * it handles how JAF reads any variables from the environment (such as $_GET/$_POST/Cookies & Sessions
 * <pre>This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 *
 * This file is released under the GPL
 * "GNU General Public License"
 * More information can be found here:
 * {@link http://www.gnu.org/licenses/gpl.html}</pre>
 *
 * @package JAF
 * @subpackage [Environment]
 * @author sebastian[at]mitos-kalandiel.me
 * @since 0.5
 */

/**
 * JAF Environment Class
 *
 * This Class deals with the whole environment of PHP
 * @package JAF
 * @subpackage [Environment]
 * @author sebastian[at]mitos-kalandiel.me
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @todo Create initiator that runs thru the different arrays and filters the environmental variables.
 */
class environment_C extends JAF implements environment_I {

	/**
	 * @staticvar string $name Contains the name of the Class
	 */
	protected static $name = NULL;

	/**
	 * @staticvar array $myEnvironment Contains all environment variables after filtering them.
	 */
	protected static $myEnvironment = array();

	/**
	 * @staticvar array $allowed_GET_keys Contains an array of allowed keys in $_GET
	 */
	private static $allowed_GET_keys = array();

	/**
	 * @staticvar array $allowed_POST_keys Contains an array of allowed keys in $_POST
	 */
	private static $allowed_POST_keys = array();

	/**
	 * @staticvar array $myGET Contains the filtered $_GET keys and values
	 */
	protected static $myGET = array();

	/**
	 * @staticvar array $myPOST Contains the filtered $_POST keys and values
	 */
	protected static $myPOST = array();

	/**
	 * Sets the class name and initializes it.
	 */
	function  __construct() {
		self::$name = 'JAF::Environment';
	}

	/**
	 * Destroys and empties the content of this Class
	 */
	function __destruct() {
		self::$name = NULL;
	}

	/**
	 * returns the name of the class
	 * @return string The name of this class
	 */
	function getName() {
		return self::$name;
	}

	/**
	 * Set the name for the JAF instance
	 * @param $name string
	 */
	function setName($name) {
		die(parent::$l10n->getString('NameSetInternal'));
	}

	/**
	 * This adds an allowed key into one of the allowed environmental-variables
	 */
	function addAllowed($name,$value) {
		switch ($name) {
			case 'get':
				array_push(self::$allowed_GET_keys,$value);
				break;
			case 'post':
				array_push(self::$allowed_POST_keys,$value);
				break;
		}
	}
}
?>