<?php
$hook = array(
    'hook' => 'InvoicePaid',
    'function' => 'InvoicePaid',
    'description' => array(
        'english' => 'Whenyou have paidthe billsends a message.'
    ),
    'type' => 'client',
    'extra' => '',

);
if(!function_exists('InvoicePaid')){
    function InvoicePaid($args){

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
