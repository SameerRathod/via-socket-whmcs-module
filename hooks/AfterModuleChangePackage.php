<?php
$hook = array(
    'hook' => 'AfterModuleChangePackage',
    'function' => 'AfterModuleChangePackage',
    'description' => array(
        'english' => 'After module Change Package'
    ),
    'type' => 'client',
    'extra' => '',
);
if(!function_exists('AfterModuleChangePackage')){
    function AfterModuleChangePackage($args){
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
