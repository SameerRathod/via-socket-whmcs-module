<?php
$hook = array(
    'hook' => 'AfterModuleSuspend',
    'function' => 'AfterModuleSuspend',
    'description' => array(
        'english' => 'After module suspended'
    ),
    'type' => 'client',
    'extra' => '',

);
if(!function_exists('AfterModuleSuspend')){
    function AfterModuleSuspend($args){
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