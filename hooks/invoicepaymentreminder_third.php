<?php
$hook = array(
    'hook' => 'InvoicePaymentReminder',
    'function' => 'InvoicePaymentReminder_thirdoverdue',
    'description' => array(
        'english' => 'Invoice payment third for first overdue'
    ),
    'type' => 'client',
    'extra' => '',

);

if(!function_exists('InvoicePaymentReminder_thirdoverdue')){
    function InvoicePaymentReminder_thirdoverdue($args){

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
