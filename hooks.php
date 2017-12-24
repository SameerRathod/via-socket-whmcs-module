<?php
/* WHMCS ViaSocket Addon with GNU/GPL Licence
 * ViaSocket - http://www.viasocket.com
 *
 * https://github.com/SameerRathod/via-socket-whmcs-module.git
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