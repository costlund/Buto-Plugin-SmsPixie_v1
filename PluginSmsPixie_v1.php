<?php
class PluginSmsPixie_v1{
  public static function send($data){
    wfPlugin::includeonce('wf/array');
    /**
     * Defaults
     */
    $default = new PluginWfArray();
    $default->set('country', '46');
    $default->set('account', '_account');
    $default->set('sender', '_sender');
    $default->set('pwd', '_pwd');
    $default->set('to', '_to');
    $default->set('message', '_message');
    $default->set('cc', array());
    /**
     * Merge defaults.
     */
    $default = new PluginWfArray(array_merge($default->get(), $data->get()));
    /**
     * Handle number.
     */
    
    $default->set('to', PluginSmsPixie_v1::phone_clean($default->get('to')));
    /**
     * receivers
     */
    $receivers = $default->get('country').$default->get('to');
    foreach ($default->get('cc') as $key => $value) {
      /**
       * cc
       */
      $value = PluginSmsPixie_v1::phone_clean($value);
      $receivers .= ','.$default->get('country').$value;
    }
    /**
     * Build url.
     */
    $url = 'http://smsserver.pixie.se/sendsms?account='.$default->get('account');
    $url .= '&pwd='.$default->get('pwd');
    $url .= '&receivers='.$receivers;
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
      throw new Exception('PluginSmsPixie_v1 says: Could not connect to server smsserver.pixie.se.');
    }
    /**
     * Response
     */
    $xml = simplexml_load_string($buffer, "SimpleXMLElement", LIBXML_NOCDATA);
    $xml = json_encode($xml);
    $xml = json_decode($xml,TRUE);
    $xml = new PluginWfArray($xml);
    $data->set('response', $xml->get());
    /**
     * Log
     */
    $settings = wfPlugin::getPluginSettings('sms/pixie_v1', true);
    if($settings->get('data/log')){
      $log = new PluginWfYml(wfGlobals::getAppDir().$settings->get('data/log'));
      $log->set('log/'.date('ymdHis'), $data->get());
      $log->save();
    }
    /**
     * 
     */
    return $data->get();
  }
  public static function phone_clean($phone){
    if(substr($phone, 0, 1) == '0'){
      $phone = substr($phone, 1);
    }
    return $phone;
  }
}
