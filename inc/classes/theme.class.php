<?php
/**
 * JAF Theme-engine
 *
 * This file contains the complete theme-class of jaf
 * it handles how the template_C class is handling and reading any theme
 * <pre>This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 *
 * This file is released under the GPL
 * "GNU General Public License"
 * More information can be found here:
 * {@link http://www.gnu.org/licenses/gpl.html}</pre>
 *
 * @package JAF
 * @subpackage [Theme]
 * @author sebastian[at]mitos-kalandiel.me
 * @since 0.3
 */

/**
 * JAF Theme Class
 *
 * This Class deals with the theme and all files/information in it
 * @package JAF
 * @subpackage [Theme]
 * @author sebastian[at]mitos-kalandiel.me
 * @version 0.3
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
class theme_C extends JAF implements theme_I {

	/**
	 * @staticvar string $name Contains the name of the Class
	 */
	protected static $name = NULL;

	/**
	 * @staticvar mixed $myTheme Contains all information about the loaded theme
	 */
	protected static $myTheme = array();


	/**
	 * Sets the class name and initializes it.
	 */
	function  __construct() {
		self::$name = 'JAF::Theme';
	}

	/**
	 * Destroys and empties the content of this Class
	 */
	function __destruct() {
		self::$name = NULL;
	}

	/**
	 * This reads the theme.info.php file out of the active theme and sets the right templating engine in JAF::$config
	 */
	function getTheme() {
		self::$myTheme['name'] = parent::$config->getConf('theme');
		self::$myTheme['base_dir'] = extractPath('themes').self::$myTheme['name'].DS;
		$f = self::$myTheme['base_dir'].'theme.info.php';
		self::$myTheme['info_file'] = $f;
		include_once($f);
		self::$myTheme['paths'] = $c;
		unset($f,$c);
		if (isset($e)) {
			switch ($e) {
				case 's':
					parent::$config->setConf('engine','Smarty');
					break;
				case 'd':
					parent::$config->setConf('engine','Dwoo');
					break;
				default:
					die(JAF::$l10n->getString('NoEngineInTheme'));
			}
			unset($e);
		} else {
			die(JAF::$l10n->getString('NoEngineInTheme'));
		}
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
	 * This read config-values from theme-config
	 * @param string $k Key to read from theme-config
	 * @return mixed The config value from the theme
	 */
	function getInfo($k) {
		return self::$myTheme[$k];
	}

	/**
	 * This returns a specific directory out of the theme information
	 * @param string $k The key in the paths-array to read
	 * @return string The extracted Directory
	 */
	function getDir($k) {
		$p = self::$myTheme['paths'];
		return $p[$k];
	}

}
?>