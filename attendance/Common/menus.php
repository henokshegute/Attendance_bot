<?php
//include "Admin/adminafternoonmenu.php";
//include "Admin/adminmorningmenu.php";
//include "User/userafternoonmenu.php";
//include "User/usermorningmenu.php";
function disable($chat_id)
{
    global $botAPI;
    $keyboard = array(array(" "));
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text= when you get notification you press start from the menu &reply_markup=" . $reply);
}
function returnmenu($chat_id)
{
    global $botAPI;
    global $con;
    $user = "SELECT * FROM user WHERE role='user' AND chat_id = '$chat_id'";
    $userQ = mysqli_query($con, $user);
    $cou = mysqli_num_rows($userQ);
    $shift = $con->query("SELECT * FROM shift_table");
    while ($ro = mysqli_fetch_array($shift)) {
        $Mstarttime = $ro['morning'];
        $Mendtime = $ro['lunch'];
        $Astarttime = $ro['afternoon'];
        $Aendtime = $ro['night'];
    }
    date_default_timezone_set('Africa/Addis_Ababa');
    $timenow = date('H:i');
    if ($Mstarttime <= $timenow && $Mendtime >= $timenow) {
        if ($cou >= 1) {
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Done!");
            userMorningMenu($chat_id);
        } else {
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Done!");
            adminMorningMenu($chat_id);
        }
    } else if ($Astarttime <= $timenow && $Aendtime >= $timenow) {
        if ($cou >= 1) {
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Done!");
            userAfternoonMenu($chat_id);
        } else {
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Done!");
            adminAfternoonMenu($chat_id);
        }
    } else {
        if ($cou >= 1) {
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Done!");
            userEndSession($chat_id);
        } else {
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Done!");
            adminEndSession($chat_id);
        }
    }
}
function userEndSession($chat_id)
{
    global $botAPI;
    $keyboard = array(array("Leave"));
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=The work hour is not started yet you can request leave &reply_markup=" . $reply);
}
function adminEndSession($chat_id)
{
    global $botAPI;
    $keyboard = array(array("Leave", "Approve_Leave",));
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=The work hour is not started yet you can request leave &reply_markup=" . $reply);
}
function disableForwarding($chat_id)
{
    global $botAPI;
    $keyboard = array(array("/start"));
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=you can't forward location!please send the location directly &reply_markup=" . $reply);
}
