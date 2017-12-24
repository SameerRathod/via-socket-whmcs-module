<?php
$hook = array(
    'hook' => 'TicketAdminReply',
    'function' => 'TicketAdminReply',
    'description' => array(
        'english' => 'After ticket replied by admin'
    ),
    'type' => 'client',
    'extra' => '',
 
);

if(!function_exists('TicketAdminReply')){
    function TicketAdminReply($args){
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
