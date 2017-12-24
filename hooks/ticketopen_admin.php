<?php
$hook = array(
    'hook' => 'TicketOpen',
    'function' => 'TicketOpen_admin',
    'type' => 'admin',
    'extra' => '',

);

if(!function_exists('TicketOpen_admin')){
    function TicketOpen_admin($args){
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
