<?php
$hook = array(
    'hook' => 'AfterRegistrarRegistrationFailed',
    'function' => 'AfterRegistrarRegistrationFailed',
    'description' => array(
        'english' => 'After domain registration failed'
    ),
    'type' => 'client',
    'extra' => '',

);
if(!function_exists('AfterRegistrarRegistrationFailed')){
    function AfterRegistrarRegistrationFailed($args){
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
