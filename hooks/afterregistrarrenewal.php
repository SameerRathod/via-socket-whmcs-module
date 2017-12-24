<?php
$hook = array(
    'hook' => 'AfterRegistrarRenewal',
    'function' => 'AfterRegistrarRenewal',
    'description' => array(
        'english' => 'After domain renewal'
    ),
    'type' => 'client',
    'extra' => '',

);
if(!function_exists('AfterRegistrarRenewal')){
    function AfterRegistrarRenewal($args){
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