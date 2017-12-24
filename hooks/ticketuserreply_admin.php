<?php
$hook = array(
    'hook' => 'TicketUserReply',
    'function' => 'TicketUserReply_admin',
    'type' => 'admin',
    'extra' => '',
   
);

if(!function_exists('TicketUserReply_admin')){
    function TicketUserReply_admin($args){
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
