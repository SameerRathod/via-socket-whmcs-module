<?php
$hook = array(
    'hook' => 'ClientLogin',
    'function' => 'ClientLogin_admin',
    'type' => 'admin',
    'extra' => '',

);

if(!function_exists('ClientLogin_admin')){
    function ClientLogin_admin($args){
        $class = new Viasocket();
        $settings = $class->getSettings();
        if(!$settings['api']){
            return null;
        }
        $event = $class->getEventStatus(__FUNCTION__);
        if($event['active'] == 0){
            return null;
        }
        $args['event'] = __FUNCTION__;

        $class->callSocket($settings['api'],$args);
    }
}

return $hook;
