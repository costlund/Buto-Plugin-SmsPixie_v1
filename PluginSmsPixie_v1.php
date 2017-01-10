<?php
/**
 <h1>SMS using Pixie service.</h1>
 */
class PluginSmsPixie_v1{
  public static function send($data){
    wfPlugin::includeonce('wf/array');
    /**
     * Defaults..
     */
    $default = new PluginWfArray();
    $default->set('country', '46');
    $default->set('account', '_account');
    $default->set('sender', '_sender');
    $default->set('pwd', '_pwd');
    $default->set('to', '_to');
    $default->set('message', '_message');
    /**
     * Merge defaults.
     */
    $default = new PluginWfArray(array_merge($default->get(), $data->get()));
    /**
     * Handle number.
     */
    if(substr($default->get('to'), 0, 1) == '0'){
      $default->set('to', substr($default->get('to'), 1));
    }
    /**
     * Build url.
     */
    $url = 'http://smsserver.pixie.se/sendsms?account='.$default->get('account');
    $url .= '&pwd='.$default->get('pwd');
    $url .= '&receivers='.$default->get('country').$default->get('to');
    $url .= '&sender='.$default->get('sender');
    $url .= '&message='.$default->get('message');
    /**
     * Send.
     */
    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL, $url);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);
    if (empty($buffer)){
      return null;
    }
    else{
      return $buffer;
    }
  }
}