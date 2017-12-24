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

function via_socket_config() {
    $configarray = array(
        "name" => "Socket",
        "description" => "WHMCS ViaSocket Addon. You can see details from: https://github.com/via_socket/ViaSocket-WHMCS-Plugin",
        "version" => "1.1",
        "author" => "Sameer Rathod",
		"language" => "english",
    );
    return $configarray;
}

function via_socket_activate() {

    $query = "CREATE TABLE IF NOT EXISTS `mod_via_socket_logs` (`id` int(11) NOT NULL AUTO_INCREMENT,`event` varchar(40) NOT NULL,`text` text,`status` varchar(10) DEFAULT NULL,`errors` text,`logs` text,`datetime` datetime NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	mysql_query($query);

    $query = "CREATE TABLE `mod_via_socket_settings` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `api` VARCHAR(512) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
    full_query($query);

    $query = "INSERT INTO `mod_via_socket_settings` (`api`) VALUES ('');";
    full_query($query);

    $query = "CREATE TABLE IF NOT EXISTS `mod_via_socket_events` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(50) CHARACTER SET utf8 NOT NULL,`type` enum('client','admin') CHARACTER SET utf8 NOT NULL,`admingsm` varchar(255) CHARACTER SET utf8 NOT NULL,`active` tinyint(1) NOT NULL,`extra` varchar(3) CHARACTER SET utf8 NOT NULL,`description` text CHARACTER SET utf8,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
	mysql_query($query);

    //Creating hooks
	require_once("utils.php");
    $class = new Viasocket();
    $class->checkHooks();

    return array('status'=>'success','description'=>'ViaSocket succesfully activated :)');



}

function via_socket_deactivate() {

    //$query = "DROP TABLE `mod_MSG91sms_templates`";
	//mysql_query($query);
    $query = "DROP TABLE `mod_via_socket_settings`";
    full_query($query);
    //$query = "DROP TABLE `mod_MSG91sms_messages`";
    //mysql_query($query);

    return array('status'=>'success','description'=>'Via Socket succesfully deactivated :(');
}

function via_socket_upgrade($vars) {
    $version = $vars['version'];

    switch($version){
        case "1":
            break;

    }

    $class = new Viasocket();
    $class->checkHooks();
}

function via_socket_output($vars){
	$modulelink = $vars['modulelink'];
	$version = $vars['version'];
	$LANG = $vars['_lang'];
	putenv("TZ=Europe/Istanbul");

    $class = new Viasocket();

    $tab = $_GET['tab'];
    $echo = '
    <div id="clienttabs">
        <ul>
            <li class="' . (($tab == "settings")?"tabselected":"tab") . '"><a href="addonmodules.php?module=via_socket&tab=settings">'.$LANG['settings'].'</a></li>
            
        </ul>
    </div>
    ';
    echo '
    <style type="text/css">
    .vsocket .container{
        background: #f9f9f9;
        width: 100%;
    }
    
    .vsocket .link{
        text-decoration: none;
        color: #282828;
        float: right;
        line-height: 60px;
    }
    
    .vsocket form{
        padding-bottom: 60px;
    }
    
    .vsocket .heading{
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .vsocket.check{
        padding-top: 120px;
    }
    
    
    .vsocket .up{
        padding-left: 8px;
    }
    
    .vsocket .sp{
        margin-left: -20px;
    }
    </style>
    ';


    if (!isset($tab) || $tab == "settings")
    {
        /* UPDATE SETTINGS */
        if ($_POST['api'] && $_POST['action']=="save") {
            $update = array(
                "api" => $_POST['api']
            );
            update_query("mod_via_socket_settings", $update, "");

            if ($_POST['evnt']) {
                $class->updateEvents($_POST['evnt']);
            }

//            $class->callSocket($_POST['api'],array("invoicecheck"=>"checked"));
        }
        /* UPDATE SETTINGS */

        $settings = $class->getSettings();
        $eventResults = $class->getEvents();



        echo '
        <script type="text/javascript">
            $(document).ready(function(){
                $("#api").change(function(){
                    $("#form").submit();
                });
            });
        </script>
        <div class="vsocket">
        <div class="container">
			<div class="up">
				<h5 class="heading">Webhook Configuration</h5>
			<hr>
			</div>
				<div class="col-md-12">
					<form action="" method="post" id="form">
						<input type="hidden" name="action" value="save" />

						<div class="form-row sp col-md-12">
					    <div class="form-group col-md-6 row">
					      <label class="heading" for="inputEmail4">webhook url</label>
					      <input name="api" type="text" class="form-control" id="inputEmail4"
								 placeholder="https://yourdomain.com/my-webhook" value="'. $settings['api'] .'">
					    </div>
					  </div>
					  <div class="check">
					  	<h5 class="heading">which notification do you want to receive?</h5>
					  <hr class="semi">';
					  while ($data = mysql_fetch_array($eventResults)) {
                          $temp = ($data["active"] == 1)?'checked=checked':'';
                          echo '<div class="form-group">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input class="form-check-input" '.$temp.' type="checkbox" name="evnt[]" value="'.$data["id"].'"> '.$data["name"].'<br>
                                
                              </label>
                            </div>
                          </div>';
                      }

					  echo '
					  </div>
					  <button type="submit" class="btn btn-primary">'.$LANG['save'].'</button>
					</form>
				</div>
			</div>
        </div>
        ';
    }
    elseif($tab == "update"){
        //to change the url here.
        $currentversion = file_get_contents("https://raw.github.com/SendOTP/viaSocket-Plugin/master/version.txt");
        echo '<div style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px;">';
        if($version != $currentversion){
            echo $LANG['newversion'];
        }else{
            echo $LANG['uptodate'].'<br><br>';
        }
        echo '</div>';
    }
	echo $LANG['lisans'];
}
