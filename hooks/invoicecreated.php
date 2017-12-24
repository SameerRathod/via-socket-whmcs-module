<?php
$hook = array(
    'hook' => 'InvoiceCreated',
    'function' => 'InvoiceCreated',
    'description' => array(
        
        'english' => 'After invoice created'
    ),
    'type' => 'client',
    'extra' => '',

);
if(!function_exists('InvoiceCreated')){
    function InvoiceCreated($args){

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
