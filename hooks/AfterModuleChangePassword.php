<?php
$hook = array(
    'hook' => 'AfterModuleChangePassword',
    'function' => 'AfterModuleChangePassword',
    'description' => array(
        'english' => 'After module change password'
    ),
    'type' => 'client',
    'extra' => '',

);
if(!function_exists('AfterModuleChangePassword')){
    function AfterModuleChangePassword($args){
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
