<?php
$hook = array(
    'hook' => 'TicketClose',
    'function' => 'TicketClose',
    'description' => array(
        'english' => 'When the ticket is closed it sends a message.'
    ),
    'type' => 'client',
    'extra' => '',

);

if(!function_exists('TicketClose')){
    function TicketClose($args){
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
