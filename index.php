<?php
/**
 * The JAF-Bootloader
 *
 * This script starts the configuration script at inc/configure.php, includes the script inc/functions.php to start the Joker Application Framework
 * <pre>This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 *
 * This file is released under the GPL
 * "GNU General Public License"
 * More information can be found here:
 * {@link http://www.gnu.org/licenses/gpl.html}</pre>
 *
 * @package JAF
 * @subpackage Core
 * @author sebastian[at]mitos-kalandiel.me
 * @copyright  Copyright (c) 2008, Joker Solutions
 * @version 0.61
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @release pre-beta
 */

/**
 *  @var array $jafPaths A global that contains for a short time all paths necessary for JAF
 */
$jafPaths = array();

/**
 * @var array $jafUrls A global that contains for a short time all URL's necessary for JAF
 */
$jafUrls = array();

/**
 * @var string contains the OS-specific Directory Separator
 */
define(DS,DIRECTORY_SEPARATOR);

/**
 * @var string Contains the URL-Seperator '/'
 */
define(US,'/');

/**
 * @var string contains an md5 sum for control purposes
 */
define(JAF,md5(__FILE__));

require_once('inc'.DS.'configure.php');

initJAF();
startJAF();
unset($jafPaths,$jafUrls);
dummyData();
print_r(exeJAF());
?>