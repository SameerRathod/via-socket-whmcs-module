<?php
$hook = array(
    'hook' => 'AfterModuleCreate',
    'function' => 'AfterModuleCreate_Hosting',
    'description' => array(
        'english' => 'After hosting create'
    ),
    'type' => 'client',
    'extra' => '',

);
if(!function_exists('AfterModuleCreate_Hosting')){
    function AfterModuleCreate_Hosting($args){
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
