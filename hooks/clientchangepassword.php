<?php
$hook = array(
    'hook' => 'ClientChangePassword',
    'function' => 'ClientChangePassword',
    'description' => array(
        'english' => 'After client change password'
    ),
    'type' => 'client',
    'extra' => '',

);

if(!function_exists('ClientChangePassword')){
    function ClientChangePassword($args){
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