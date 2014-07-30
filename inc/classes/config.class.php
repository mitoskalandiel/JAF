<?php
/**
 * JAF Configuration
 *
 * This file contains the complete configuration-class of jaf
 * it handles the whole configuration-set including database connections etc
 * <pre>This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 *
 * This file is released under the GPL
 * "GNU General Public License"
 * More information can be found here:
 * {@link http://www.gnu.org/licenses/gpl.html}</pre>
 *
 * @package JAF
 * @subpackage [Config]
 * @author sebastian[at]mitos-kalandiel.me
 * @since 0.1
 */

/**
 * JAF Configuration Class
 *
 * This Class deals with all config settings. Reads/writes them and makes them available to the other
 * members of jaf.
 * @package JAF
 * @subpackage [Config]
 * @author sebastian[at]mitos-kalandiel.me
 * @version 0.4
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @tutorial config_C.cls
 */
class config_C extends JAF implements config_I {

	/**
	 * @var array $myConfig Contains all jaf-config settings
	 */
	protected static $myConfig = array();

	/**
	 * @var string $name Contains the name of the Class
	 */
	protected static $name = NULL;

	/**
	 * Sets the class name and initializes it.
	 */
	function  __construct() {
		self::$name = 'JAF::Config';
	}

	/**
	 * Destroys and empties the content of this Class
	 */
	function __destruct() {
		self::$name = NULL;
	}

	/**
	 * Read a config-value
	 * @param string $k The key to read from the config
	 * @return string The config value
	 */
	function getConf($k) {
		return self::$myConfig[$k];
	}

	/**
	 * Set a config value
	 * @param string $k The Key in config to change
	 * @param string $v The Value that is new in the config
	 */
	function setConf($k,$v) {
		self::$myConfig[$k] = $v;
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

}

?>