<?php
$hook = array(
    'hook' => 'AcceptOrder',
    'function' => 'AcceptOrder_SMS',
    'description' => array(
        'english' => 'After order accepted'
    ),
    'type' => 'client',
    'extra' => '',
);
if(!function_exists('AcceptOrder_SMS')){
    function AcceptOrder_SMS($args){
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