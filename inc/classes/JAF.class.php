<?php
/**
 * JAF CoreClass
 *
 * This file contains the complete abstract class JAF which builds the foundation
 * for the whole Application
 * <pre>This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 *
 * This file is released under the GPL
 * "GNU General Public License"
 * More information can be found here:
 * {@link http://www.gnu.org/licenses/gpl.html}</pre>
 *
 * @package JAF
 * @author sebastian[at]mitos-kalandiel.me
 * @since 0.4
 */

/**
 * JAF Class
 *
 * This Class is the Core of Joker Application Framework.
 *
 * @package JAF
 * @author sebastian[at]mitos-kalandiel.me
 * @version 0.6
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @release pre-beta
 * @todo create a module class which loads the different JAF-modules (such as pages/blog etc)
 * @todo create a database layer that allows for reading out of mysql/postgresql/sqlite
 */
abstract class JAF {

	/**
	 * @staticvar mixed $config Contains JAF::config_C as an instance
	 * @see config_C
	 */
	public static $config;

	/**
	 * @staticvar mixed $template Contains JAF::template_C as an instance
	 * @see template_C
	 */
	public static $template;

	/**
	 * @staticvar mixed $theme Contains JAF::theme_C as an instance
	 * @see theme_C
	 */
	public static $theme;

	/**
	 * @staticvar mixed $l10n Contains JAF::l10n_C as an instance
	 * @see l10n_C
	 */
	public static $l10n;

	/**
	 * @staticvar mixed $env Contains JAF::environment_C as an instance
	 * @see environment_C
	 */
	public static $env;

	/**
	 * @var string The name of each load AND initialized class
	 */
	protected static $name = NULL;

	/**
	 * returns the name of the class
	 * @return string The name of this class
	 */
	public function getName() {
		return self::$name;
	}

	/**
	 * Set the name for the JAF instance
	 * @param $name string
	 */
	public function setName($name) {
		self::$name = $name;
	}
}
?>