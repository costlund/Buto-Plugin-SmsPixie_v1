# Buto-Plugin-SmsPixie_v1
SMS using <a href="http://www.pixie.se" target="_blank">Pixie</a> service.

## PHP
Alphanum sender can have max 11 characters, number senders max 15. User cc param to send copies.
```
$data = new PluginWfArray();
$data->set('country', '46');
$data->set('account', '_account_');
$data->set('sender', '_anything_');
$data->set('pwd', '_pwd_');
$data->set('to', '_phone_number_');
$data->set('message', '_message_');
$data->set('cc', array(''));
wfPlugin::includeonce('sms/pixie_v1');
$response = PluginSmsPixie_v1::send($data);
$response = new PluginWfArray($response);
if($response->get('response/@attributes/code')==0){
  echo 'ok';
}
```

## Log
To log data to file.
```
plugin:
  sms:
    pixie_v1:
      data:
        log: '/../buto_data/theme/[theme]/pixie_log.yml'
```

## Test widget
One could test by using this widget.
```
type: widget
settings:
  role:
    item:
      - webmaster
data:
  plugin: 'sms/pixie_v1'
  method: test
  data:
    account: _
    pwd: _
    sender: 'Anything'
    to: 'mobile number'
    message: 'Test message...'
```
