<?php

//lamp_for_VK_script;
date_default_timezone_set ('Europe/Moscow');

$access = 'токен';
$chatid = '116';

$startDay = new DateTime('10-09-2014 18:10');
$endDay = new DateTime('now');
$interval = $endDay->diff($startDay);

$birth = $interval->format('лет: %y, месяцев: %m, дней: %d');


for ($i = 0; $i < 50; $i++) {
    $messages = file_get_contents('https://api.vk.com/method/messages.getChatUsers?access_token='.$access.'&chat_id='.$chatid.'&v=5.56');

$json = json_decode($messages, true);
$currCount = (count($json['response']));
$oldCount = file_get_contents('lamp.txt');
if ($oldCount > $currCount) {
    $fp = fopen('lamp.txt', 'w+');
    fwrite($fp, $currCount);
    fclose($fp);

    $send = file_get_contents('https://api.vk.com/method/messages.send?access_token='.$access.'&chat_id='.$chatid.'&v=5.56&message='.urlencode("Время удалений в лампе! Или банов. Или репрессий. В любом случае, на одного участника теперь меньше."));
    $log=strip_tags($send);
    addlog('Answer: '.$log);
    $log=strip_tags($messages);
    addlog('Captured: '.$log);

} else if ($oldCount < $currCount) {
    $fp = fopen('lamp.txt', 'w+');
    fwrite($fp, $currCount);
    fclose($fp);
    $hello = file_get_contents('lamp_welcome1.txt').$birth.file_get_contents('lamp_welcome2.txt');
   $send = file_get_contents('https://api.vk.com/method/messages.send?access_token='.$access.'&chat_id='.$chatid.'&v=5.56&message='.urlencode($hello));
    $log=strip_tags($send);
    addlog('Answer: '.$log);
    $log=strip_tags($messages);
    addlog('Captured: '.$log);


}
    sleep(1);
}

function addlog($logtext){
    $fp = fopen( './lamp_log.txt', 'a' );
    fwrite( $fp, '['.date( 'd.m.Y H:i:s', time() ).'] '.$logtext.PHP_EOL);
}

?>