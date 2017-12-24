<?php
$hook = array(
    'hook' => 'AdminLogin',
    'function' => 'AdminLogin_admin',
    'type' => 'admin',
    'extra' => '',
);
if(!function_exists('AdminLogin_admin')){
    function AdminLogin_admin($args){
        $class = new Viasocket();
        $event = $class->getEventStatus(__FUNCTION__);
        if($event['active'] == 0){
            return null;
        }
        $args['event'] = __FUNCTION__;

        $settings = $class->getSettings();
        if(!$settings['api']){
            return null;
        }
        $class->callSocket($settings['api'],$args);
    }
}

return $hook;
