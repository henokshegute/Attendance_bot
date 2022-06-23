<?php
function register($chat_id)
{
    global $botAPI;
    $keyboard = array(array("Register"));
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text= Please register here and the admin will aprove when they got it &reply_markup=" . $reply);
}
