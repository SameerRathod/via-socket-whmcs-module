<?php
$hook = array(
    'hook' => 'ClientAdd',
    'function' => 'ClientAdd',
    'description' => array(
        'english' => 'After client register'
    ),
    'type' => 'client',
    'extra' => '',

);
if(!function_exists('ClientAdd')){
    function ClientAdd($args){
        $class = new Viasocket();
        $event = $class->getEventStatus(__FUNCTION__);
        if($event['active'] == 0){
            return null;
        }

        $settings = $class->getSettings();
        if(!$settings['api']){
            return null;
        }
        $args['event'] = __FUNCTION__;
        $class->callSocket($settings['api'],$args);
    }
}

return $hook;