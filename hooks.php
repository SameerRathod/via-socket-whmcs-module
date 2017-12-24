<?php
/* WHMCS ViaSocket Addon with GNU/GPL Licence
 * ViaSocket - http://www.MSG91.com
 *
 * https://github.com/ViaSocket/ViaSocket-WHMCS-Plugin
 *
 *
 * Licence: GPLv3 (http://www.gnu.org/licenses/gpl-3.0.txt)
 * */
if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

require_once("utils.php");
$class = new Viasocket();
$hooks = $class->getHooks();



foreach($hooks as $hook){
    add_hook($hook['hook'], 1, $hook['function'], "");
}