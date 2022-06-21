<?php
//include "Common/menus.php";
function lengthOfTheWork($chat_id)
{
    global $botAPI;
    $keyboard = array(array("Fullday", "Halfday",));
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=How long will it take? &reply_markup=" . $reply);
}
function timeOfTheWork($chat_id)
{
    global $botAPI;
    $keyboard = array(array("📝 Morning", "📝 Afternoon",));
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please Select the time &reply_markup=" . $reply);
}
function notifyadmin($chat_id){
    global $botAPI;
    global $con;
    $admin = "SELECT * FROM user WHERE role='admin'";
    $adminQ = mysqli_query($con, $admin);
    while ($ro = mysqli_fetch_array($adminQ)) {
        $admin_id = $ro['chat_id'];
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $admin_id . "&text=Dear Admin, There are a request for Field work you need to approve");
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=your request have been sent successfully.");
    }
    
}
