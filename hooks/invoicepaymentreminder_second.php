<?php
$hook = array(
    'hook' => 'InvoicePaymentReminder',
    'function' => 'InvoicePaymentReminder_secondoverdue',
    'description' => array(
        'english' => 'Invoice payment second for first overdue'
    ),
    'type' => 'client',
    'extra' => '',

);

if(!function_exists('InvoicePaymentReminder_secondoverdue')){
    function InvoicePaymentReminder_secondoverdue($args){
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
