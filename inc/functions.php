<?php
/**
 * JAF Functions that don't belong to any subclass of JAF
 * @author sebastian[at]mitos-kalandiel.me
 * @package JAF
 * @subpackage Core
 * @since 0.1
 */

/**
 * Load a Class out of $jafPaths['inc::classes'] and include it into the code
 * @param string $file The name of the Class to load
 */
function loadClass($file) {
	global $jafPaths;
	$f = $jafPaths['inc::classes'].$file.'.class.php';
	include_once($f);
}

/**
 * This reads a specific path out off JAF::$config->$myConfig
 * @param string $mod Which module path do we need to extract
 * @return string The full path to the requested module
 */
function extractPath($mod) {
	$tp = JAF::$config->getConf('paths');
	return $tp[$mod];
}

/**
 * This reads a specific url out off JAF::$config->$myConfig
 * @param string $mod Which module url do we need to extract
 * @return string The full url to the requested module
 */
function extractUrl($mod) {
	$tp = JAF::$config->getConf('urls');
	return $tp[$mod];
}

/**
 * This fills in some dummy data for the template etc...
 */
function dummyData() {
	JAF::$l10n->addString('NotAllowed');

	$s1 = extractPath('base');
	$s2 = JAF::$config->getConf('engine');
	$s3 = JAF::$config->getConf('engine_dir');
	$s4 = JAF::$l10n->getString('NotAllowed');

	JAF::$template->addVar('str1',$s1);
	JAF::$template->addVar('str2',$s2);
	JAF::$template->addVar('str3',$s3);
	JAF::$template->addVar('str4',$s4);

	JAF::$template->addVar('base_url', extractUrl('base'));
	JAF::$template->addVar('css_url', extractUrl('css'));
}
?>