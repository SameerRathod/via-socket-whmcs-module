<?php
$hook = array(
    'hook' => 'AfterModuleUnsuspend',
    'function' => 'AfterModuleUnsuspend',
    'description' => array(
        'english' => 'After module unsuspend'
    ),
    'type' => 'client',
    'extra' => '',

);
if(!function_exists('AfterModuleUnsuspend')){
    function AfterModuleUnsuspend($args){
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