<?php

//shadowban_for_VK_script;

$access = '';
$userid = '331810227';

while (1) {
    $messages = file_get_contents('https://api.vk.com/method/messages.getHistory?access_token=' . $access . '&user_id=' . $userid . '&v=5.56&count=0');
echo($messages);
//echo(var_dump(json_decode($messages)));

    $json = json_decode($messages);

    $count = ($json->response->unread);
    $out = '';

    if ($count > 0) {
        $last = $json->response->out_read;
        for ($i = $last; $i > $last - $count; $i--) {
            $out = $out . $i . ',';
        }
        $out = substr($out, 0, -1);
        //echo($out);
        $del = file_get_contents('https://api.vk.com/method/messages.delete?access_token=' . $access . '&message_ids=' . $out . '&v=5.56&count=0');
    echo($del);
    }
    sleep(1);
}

?>