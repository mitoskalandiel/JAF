<?php
/**
 * JAF configuration-script
 * @package JAF
 * @subpackage Core
 * @author sebastian[at]mitos-kalandiel.me
 * @since 0.1
 */

/**
  * creates a path from the script-path and puts it into the global $jafPaths array
 * @param string $path the path to be constructed
 * @param string $sub a subpath (if existent)
 */
function makePath($path,$sub=NULL) {
	global $jafPaths;
	$d = getcwd();
	if (!empty($sub)) {
		$td = $d.DS.$path.DS.$sub.DS;
		$jafPaths[$path.'::'.$sub] = $td;
	} else {
		$jafPaths[$path] = $d.DS.$path.DS;
	}
}

/**
 * runs makePath() for all directories
 */
function generatePaths() {
	global $jafPaths;
	$jafPaths['base'] = getcwd().DS;
	makePath('css');
	makePath('db');
	makePath('img');
	makePath('inc');
	makePath('inc','classes');
	makePath('js');
	makePath('js','jquery');
	makePath('js','overlib');
	makePath('js','thickbox');
	makePath('js','tinymce');
	makePath('js','yaml');
	makePath('lang');
	makePath('libs');
	makePath('libs','dwoo');
	makePath('libs','smarty');
	makePath('libs','tinymce-compressor');
	makePath('themes');
	makePath('yaml');
}

/**
  * creates an url from the script-url and puts it into the global $jafUrls array
 * @param string $path the path to be constructed
 * @param string $sub a subpath (if existent)
 */
function makeUrl($path,$sub=NULL) {
	global $jafUrls;
	$d = $jafUrls['base'];
	if (!empty($sub)) {
		$td = $d.US.$path.US.$sub.US;
		$jafUrls[$path.'::'.$sub] = $td;
	} else {
		$jafUrls[$path] = $d.US.$path.US;
	}
}

/**
 * runs makeUrl() for all directories
 */
function generateUrls() {
	global $jafUrls;
	$jafUrls['base'] = 'http://'.$_SERVER['SERVER_NAME'];
	makeUrl('css');
	makeUrl('db');
	makeUrl('img');
	makeUrl('js');
	makeUrl('js','jquery');
	makeUrl('js','overlib');
	makeUrl('js','thickbox');
	makeUrl('js','tinymce');
	makeUrl('js','yaml');
	makeUrl('themes');
	makeUrl('yaml');
}

/**
 * This loads all necessary class-files
 */
function jafClasses() {
	loadClass('JAF');
	loadClass('config');
	loadClass('l10n');
	loadClass('template');
	loadClass('theme');
	loadClass('environment');
}

/**
 * This is the initiator for the JAF start. It generates all directory-entries and includes two necessary files
 */
function initJAF() {
	global $jafPaths;
	generatePaths();
	generateUrls();
	$t1 = $jafPaths['inc'].'interfaces.php';
	$t2 = $jafPaths['inc'].'functions.php';
	require_once($t1);
	require_once($t2);
}

/**
 * This function starts the Joker Application Framework!
 */
function startJAF() {
	global $jafPaths;
	global $jafUrls;

	jafClasses();

	JAF::$config = new config_C();
	JAF::$config->setConf('paths',$jafPaths);
	JAF::$config->setConf('urls', $jafUrls);
	JAF::$config->setConf('theme','default');
	JAF::$config->setConf('language','en_GB.utf8');

	JAF::$l10n = new l10n_C();
	JAF::$theme = new theme_C();
	JAF::$template = new template_C();
	JAF::$env = new environment_C();

	JAF::setName(JAF::$l10n->getString('JAF'));
	JAF::$env->addAllowed('get','user');
}

/**
 * This effectively outputs the generated HTML-Code of JAF
 * @return string The complete HTML-source of the page
 */
function exeJAF() {
	return JAF::$template->output();
}
?>