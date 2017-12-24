<?php
$hook = array(
    'hook' => 'InvoicePaymentReminder',
    'function' => 'InvoicePaymentReminder_Firstoverdue',
    'description' => array(
        'english' => 'Invoice payment reminder for first overdue'
    ),
    'type' => 'client',
    'extra' => '',

);

if(!function_exists('InvoicePaymentReminder_Firstoverdue')){
    function InvoicePaymentReminder_Firstoverdue($args){

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