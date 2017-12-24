<?php
$hook = array(
    'hook' => 'DailyCronJob',
    'function' => 'DomainRenewalNotice',
    'description' => array(
        'english' => 'Donmain renewal notice before {x} days ago'
    ),
    'type' => 'client',
    'extra' => '15',

);
if(!function_exists('DomainRenewalNotice')){
    function DomainRenewalNotice($args){

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