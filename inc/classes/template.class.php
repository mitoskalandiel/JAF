<?php
/**
 * JAF Template-engine
 *
 * This file contains the complete template-class of jaf
 * it handles how dwoo or smarty pull their content together and display it
 * <pre>This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 *
 * This file is released under the GPL
 * "GNU General Public License"
 * More information can be found here:
 * {@link http://www.gnu.org/licenses/gpl.html}</pre>
 *
 * @package JAF
 * @subpackage [Template]
 * @author sebastian[at]mitos-kalandiel.me
 * @since 0.2
 * @todo Created proper articulated theme/template that uses yaml and smarty/dwoo
 */

/**
 * JAF Template Class
 *
 * This Class deals with Dwoo or Smarty to generate user-readable output
 * @package JAF
 * @subpackage [Template]
 * @author sebastian[at]mitos-kalandiel.me
 * @version 0.4
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
class template_C extends JAF implements template_I {

	/**
	 * @staticvar string $name Contains the name of the Class
	 */
	protected static $name = NULL;

	/**
	 * @staticvar mixed $myTemplate Contains an instance of the actual templating-engine
	 */
	protected static $myTemplate;

	/**
	 * @staticvar string $myContent Contains the complete page-layout
	 */
	protected static $myContent = NULL;

	/**
	 * Sets the class name and initializes it. After that initEnginw() is being called
	 */
	function  __construct() {
		self::$name = 'JAF::Template';
		self::initEngine();
	}

	/**
	 * Destroys and empties the content of this Class
	 */
	function __destruct() {
		self::$name = NULL;
	}

	/**
	 * This collects all three parts of the template that make out a complete page
	 */
	private function collectContent() {
		self::$myContent = self::$myTemplate->fetch('header.tpl');
		self::$myContent .= self::$myTemplate->fetch('body.tpl');
		self::$myContent .= self::$myTemplate->fetch('footer.tpl');
	}

	/**
	 * Displays the final content (eventually)
	 * @return string The complete HTML Source of the page
	 */
	function output() {
		self::assignVars();
		self::collectContent();
		return self::$myContent;
	}

	/**
	 * Adds a smarty-variable out of a php value
	 * @param string $k Name of the Smarty-Variable
	 * @param string $v The value of the smarty-variable
	 */
	function addVar($k,$v) {
		self::$myTemplate->assign($k,$v);
	}

	/**
	 * Assign PHP variables to the Template
	 */
	private function assignVars() {
		self::$myTemplate->assign('title',parent::getName());
	}

	/**
	 * This loads the templating engine and initializes an instance into self::$myTemplate
	 */
	function loadEngine() {
		include_once(parent::$config->getConf('engine_dir'));
		$e = parent::$config->getConf('engine');
		self::$myTemplate = new $e();
		switch (parent::$config->getConf('engine')) {
			case 'Smarty':
				self::$myTemplate->template_dir = parent::$theme->getInfo('base_dir').parent::$theme->getDir('template_dir');
				self::$myTemplate->compile_dir = parent::$theme->getInfo('base_dir').parent::$theme->getDir('compile_dir');
				self::$myTemplate->config_dir = parent::$theme->getInfo('base_dir').parent::$theme->getDir('config_dir');
				self::$myTemplate->cache_dir = parent::$theme->getInfo('base_dir').parent::$theme->getDir('cache_dir');
				break;
			case 'Dwoo':
				//summit
				break;
			default:
				die(JAF::$l10n->getString('EngineInitFailed'));
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
	 * This determines the right path/file for loading the templating engine and then calls self::loadEngine()
	 */
	private function initEngine() {
		parent::$theme->getTheme();
		switch (parent::$config->getConf('engine')) {
			case 'Smarty':
				$p = extractPath('libs::smarty').'Smarty.class.php';
				parent::$config->setConf('engine_dir',$p);
				break;
			case 'Dwoo':
				$p = extractPath('libs::dwoo').'Dwoo.php';
				parent::$config->setConf('engine_dir',$p);
				break;
			default:
				die(JAF::$l10n->getString('NoEngineDefined'));
		}
		self::loadEngine();
	}

}

?>