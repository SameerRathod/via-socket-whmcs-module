<?php
$hook = array(
    'hook' => 'AfterRegistrarRegistration',
    'function' => 'AfterRegistrarRegistration',
    'description' => array(
        'english' => 'After domain registration'
    ),
    'type' => 'client',
    'extra' => '',

);
if (!function_exists('AfterRegistrarRegistration')) {
    function AfterRegistrarRegistration($args)
    {
        $class = new Viasocket();
        $settings = $class->getSettings();
        if (!$settings['api']) {
            return null;
        }
        $event = $class->getEventStatus(__FUNCTION__);
        if($event['active'] == 0){
            return null;
        }
        $args['event'] = __FUNCTION__;

        $class->callSocket($settings['api'], $args);

    }
}

return $hook;