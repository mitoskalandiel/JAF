<?php
/**
 * JAF Localization
 *
 * This file contains the complete l10n-class of jaf
 * it handles how translations are being retrieved
 * <pre>This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 *
 * This file is released under the GPL
 * "GNU General Public License"
 * More information can be found here:
 * {@link http://www.gnu.org/licenses/gpl.html}</pre>
 *
 * @package JAF
 * @subpackage [L10n]
 * @author sebastian[at]mitos-kalandiel.me
 * @since 0.4
 */

/**
 * JAF Localization Class
 *
 * This Class deals with all translations
 * @package JAF
 * @subpackage [L10n]
 * @author sebastian[at]mitos-kalandiel.me
 * @version 0.4
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
class l10n_C extends JAF implements l10n_I {

	/**
	 * @staticvar string $name Contains the name of the Class
	 */
	protected static $name = NULL;

	/**
	 * @staticvar array $myI18n Contains all translated strings
	 */
	protected static $myI18n = array();

	/**
	 * Sets the class name and initializes it. After that it calls the two functions loadLanguage() & makeTranslations()
	 */
	function  __construct() {
		self::$name = 'JAF::L10n';
		self::loadLanguage(parent::$config->getConf('language'));
		self::makeTranslations();
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
	 * First set the locale, then point to the right path, and finally call translateStrings() to fill the internal array
	 * @param string $lang Which locale shall we load?
	 */
	private function loadLanguage($lang) {
		setlocale(LC_ALL, $lang);
		bindtextdomain('JAF', extractPath('lang'));
		textdomain('JAF');
	}

	/**
	 * Add a translation string into the $myI18n array
	 * @param string $s What string shall we add?
	 */
	function addString($s) {
		self::$myI18n[$s] = _($s);
	}

	/**
	 * Get a specific string out of $myI18n
	 * @param string $k which array key from $myI18n shall i get?
	 * @return string The translated string
	 */
	function getString($k) {
		return self::$myI18n[$k];
	}

	/**
	 * This creates a predefined set of translated strings for JAF by using l10n::addString() for each string
	 */
	private function makeTranslations() {
		self::addString('JAF');
		self::addString('NoEngineInTheme');
		self::addString('EngineInitFailed');
		self::addString('NoEngineDefined');
		self::addString('NameSetInternal');
	}
}
?>