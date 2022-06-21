<?php
function morningchekincheckout($chat_id)
{
    global $botAPI;
    $keyboard = array(array("Morning Checkin", "Morning Checkout"));
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text= Location Confirmed! &reply_markup=" . $reply);
}
function afternoonchekincheckout($chat_id)
{
    global $botAPI;
    $keyboard = array(array("Afternoon Checkin", "Afternoon Checkout"));
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text= Location Confirmed! &reply_markup=" . $reply);
}