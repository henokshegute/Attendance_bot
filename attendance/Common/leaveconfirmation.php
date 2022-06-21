<?php
//include "Admin/adminafternoonmenu.php";
//include "Admin/adminmorningmenu.php";
//include "User/userafternoonmenu.php";
//include "User/usermorningmenu.php";
//include "Common/menus.php";
function confirmationLeaveReplay($chat_id)
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
    $admin = "SELECT * FROM user WHERE role='admin'";
    $adminQ = mysqli_query($con, $admin);
    while ($ro = mysqli_fetch_array($adminQ)) {
        $admin_id = $ro['chat_id'];
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $admin_id . "&text=Dear Admin, There are a request for leave you need to approve");
    }
    $info = "";
    ////////////////////////////////////////
    if ($Mstarttime <= $timenow && $Mendtime >= $timenow) {
        if ($cou >= 1) {
            $info .= "Your leave request sent successfully.";
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=" . $info . "&parse_mode=html");
            userMorningMenu($chat_id);
        } else {
            $info .= "Your leave request sent successfully.";
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=" . $info . "&parse_mode=html");
            adminMorningMenu($chat_id);
        }
    } else if ($Astarttime <= $timenow && $Aendtime >= $timenow) {
        if ($cou >= 1) {
            $info .= "Your leave request sent successfully.";
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=" . $info . "&parse_mode=html");
            userAfternoonMenu($chat_id);
        } else {
            $info .= "Your leave request sent successfully.";
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=" . $info . "&parse_mode=html");
            adminAfternoonMenu($chat_id);
        }
    } else {
        if ($cou >= 1) {
            $info .= "Your leave request sent successfully.";
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=" . $info . "&parse_mode=html");
            userEndSession($chat_id);
        } else {
            $info .= "Your leave request sent successfully.";
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=" . $info . "&parse_mode=html");
            adminEndSession($chat_id);
        }
    }
}
function leaveDataConfirmation($chat_id)
{
    $keyboard = array(array("Confirm Leave", "Discard!"));
    global $botAPI;
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please Confirm: &reply_markup=" . $reply);
}
