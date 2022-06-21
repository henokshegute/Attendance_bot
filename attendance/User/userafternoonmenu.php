<?php
function userAfternoonMenu($chat_id)
{
    global $botAPI;
    $keyboard = array(array("📝 Afternoon Checkin ", "📝 Afternoon Checkout",), array("Leave","Field Work"));
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text= Continue. . . &reply_markup=" . $reply);
}