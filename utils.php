<?php
/* WHMCS ViaSocket Addon with GNU/GPL Licence
 * ViaSocket - http://www.viasocket.com
 *
 * https://github.com/SameerRathod/via-socket-whmcs-module.git
 *
 * 
 * Licence: GPLv3 (http://www.gnu.org/licenses/gpl-3.0.txt)
 * */

class Viasocket
{
    var $sender;
    var $errors = array();
    var $logs = array();

    public $params;
    public $message = '';

    /**
     * @return mixed
     */
    public function getMessage(){
        return $this->message;
    }
    /**
     * @return array
     */
    public function getSettings()
    {
        $result = select_query("mod_via_socket_settings", "*");
        return mysql_fetch_array($result);
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        $result = select_query("mod_via_socket_events", "*");
        return $result;
    }

    function getHooks()
    {
        if ($handle = opendir(dirname(__FILE__) . '/hooks')) {
            while (false !== ($entry = readdir($handle))) {
                if (substr($entry, strlen($entry) - 4, strlen($entry)) == ".php") {
                    $file[] = require_once('hooks/' . $entry);
                }
            }
            closedir($handle);
        }
        return $file;
    }

    function saveToDb($event, $status, $errors = null, $logs = null)
    {
        $now = date("Y-m-d H:i:s");
        $table = "mod_via_socket_logs";
        $values = array(
            "event" => $event,
            "text" => $this->getMessage(),
            "status" => $status,
            "errors" => $errors,
            "logs" => $logs,
            "datetime" => $now
        );
        insert_query($table, $values);

        $this->addLog("Saved to database");
    }


    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function addLog($log)
    {
        $this->logs[] = $log;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        $res = '<pre><p><ul>';
        foreach ($this->errors as $d) {
            $res .= "<li>$d</li>";
        }
        $res .= '</ul></p></pre>';
        return $res;
    }

    /**
     * @return array
     */
    public function getLogs()
    {
        $res = '<pre><p><strong>Debug Result</strong><ul>';
        foreach ($this->logs as $d) {
            $res .= "<li>$d</li>";
        }
        $res .= '</ul></p></pre>';
        return $res;
    }

    /*
     * Runs at addon install/update
     * This function controls that if there is any change at hooks files. Such as new hook, variable changes at hooks.
     */
    function checkHooks($hooks = null)
    {
        if ($hooks == null) {
            $hooks = $this->getHooks();
        }

        $i = 0;
        foreach ($hooks as $hook) {
            $sql = "SELECT `id` FROM `mod_via_socket_events` WHERE `name` = '" . $hook['function'] . "' AND `type` = '" . $hook['type'] . "' LIMIT 1";
            $result = mysql_query($sql);
            $num_rows = mysql_num_rows($result);
            if ($num_rows == 0) {
                if ($hook['type']) {
                    $values = array(
                        "name" => $hook['function'],
                        "type" => $hook['type'],
                        "extra" => $hook['extra'],
                        "description" => json_encode(@$hook['description']),
                        "active" => 1
                    );
                    insert_query("mod_via_socket_events", $values);
                    $i++;
                }
            } else {
                $values = array(
                    "variables" => $hook['variables']
                );
                update_query("mod_via_socket_events", $values, "name = '" . $hook['name'] . "'");
            }
        }
        return $i;
    }


    public function callSocket($url, $argArray)
    {
        $this->addLog("arg: " . json_encode($argArray));
        $this->addLog("url: " . $url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            json_encode($argArray));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $this->addLog("response: " . $server_output);
        $this->saveToDb($argArray['event'], 'info', '', $this->getLogs());
        return $server_output;
    }

    function callUpdateQueryOnEvent($idsArr,$status){
        $eventUpdateArray = array(
            "active" => $status
        );
        $eventIds = implode(',',$idsArr);
        update_query("mod_via_socket_events", $eventUpdateArray, "id IN (".$eventIds.")");
    }
    public function updateEvents($eventArray){
        $result = $this->getEvents();
        $statusZeroArray = [];
        while ($row =  mysql_fetch_array($result)){
            if(!in_array($row['id'],$eventArray)){
                $statusZeroArray[] = $row['id'];
            }
        }
        $this->callUpdateQueryOnEvent($eventArray,1);
        $this->callUpdateQueryOnEvent($statusZeroArray,0);

    }
    function getEventStatus($eventName = null){
        $where = array("name" => $eventName);
        $result = select_query("mod_via_socket_events", "*", $where);
        $data = mysql_fetch_assoc($result);

        return $data;
    }


}
