<?php
/**
 * JAF Interface Container
 * @package JAF
  * @author sebastian[at]mitos-kalandiel.me
  * @since 0.1
 */

/**
 * JAF Main interface
 *
 * This interface must be implemented into all subclasses of JAF
 * @package JAF
  * @author sebastian[at]mitos-kalandiel.me
 * @version 0.3
 */
interface JAF_I {
	/**
	 * @return string The name of the loaded class
	 */
	function getName();
}

/**
 * Config Interface
 *
 * This interface allows for reading/writing settings into the jaf-config
 * @package JAF
 * @subpackage [Config]
 * @author sebastian[at]mitos-kalandiel.me
 * @version 0.3
 */
interface config_I extends JAF_I {
	/**
	 * Read a setting by whatever means the class itself employs
	 * @param string $key Read which setting?
	 * @return mixed The setting-value that has been requested
	 */
	function getConf($key);

	/**
	 * Write a setting by whatever means the class itself employs
	 * @param string $key What setting do we manipulate?
	 * @param mixed $value What do we write into the setting?
	 */
	function setConf($key,$value);
}

/**
 * Template Interface
 *
 * This interface allows for manipulation of the template
 * @package JAF
 * @subpackage [Template]
 * @author sebastian[at]mitos-kalandiel.me
 * @version 0.3
 */
interface template_I extends JAF_I {
	/**
	 * Pull the template together and output to whatever medium
	 * @return string The full html/pdf/rss/xml source of the page
	 */
	function output();

	/**
	 * Load the right templating engine, according to theme-information
	 */
	function loadEngine();
}

/**
 * Theme Interface
 *
 * This interface allows for reading any theme for JAF
 * @package JAF
 * @subpackage [Theme]
 * @author sebastian[at]mitos-kalandiel.me
 * @version 0.3
 *
 */
interface theme_I extends JAF_I {
	/**
	 * This loads all necessary information about a theme into JAF
	 */
	function getTheme();
}

/**
 * Localization Interface
 *
 * This interface allows for language manipulation
 * @package JAF
 * @subpackage [L10n]
 * @author sebastian[at]mitos-kalandiel.me
 * @version 0.3
 *
 */
interface l10n_I extends JAF_I {
	/**
	 * Get a translated string out of the internal l10n-db
	 * @param string $key Which array-key shall we get?
	 * @return string the translated string out of the database
	 */
	function getString($key);

	/**
	 * Add a string into the translation table
	 * @param string $s The short string to add
	 */
	function addString($s);
}

/**
 * Environment Interface
 *
 * This interface allows for language manipulation
 * @package JAF
 * @subpackage [Environment]
 * @author sebastian[at]mitos-kalandiel.me
 * @version 0.3
 * @todo create function that allows to read out of JAF::[Environment]
 *
 */
interface environment_I extends JAF_I {
	/**
	 * This adds an allowed variable into a specific variable-set
	 * @param string $var Where do we allow it? ($_GET/Session etc.)
	 * @param string $val What do we allow?
	 */
	function addAllowed($var,$val);
}
?>