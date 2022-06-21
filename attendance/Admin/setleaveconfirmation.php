<?php
//include "adminafternoonmenu.php";
//include "adminmorningmenu.php";
function SetShiftConfirmation($chat_id)
{
    $keyboard = array(array("Set", "Unset"));
    global $con;
    global $botAPI;
    $marksHTML = "";
    $hel = "<b>Confirm</b>%0A";
    $sql = "select * from shift_temp where chat_id ='$chat_id'";
    $rep = mysqli_query($con, $sql);
    while ($ro = mysqli_fetch_array($rep)) {
        $Mstarttime = $ro['morning'];
        $Mendtime = $ro['lunch'];
        $Astarttime = $ro['afternoon'];
        $Aendtime = $ro['night'];
    }

    $marksHTML .= "morning entry time :- " . $Mstarttime . "%0A";
    $marksHTML .= "lunch time :- " . $Mendtime . "%0A";
    $marksHTML .= "afternoon entry time :- " . $Astarttime . "%0A";
    $marksHTML .= "shift end time :- " . $Aendtime . "%0A";

    $hel .= $marksHTML;
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=" . $hel . "&parse_mode=html");
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please Confirm: &reply_markup=" . $reply);
}
function confirmshift($chat_id)
{
    global $botAPI;
    global $con;
    $timenow = date('H:i');
    $shift = $con->query("SELECT * FROM shift_temp");
    while ($ro = mysqli_fetch_array($shift)) {
        $chat_id = $ro['chat_id'];
        $Mstarttime = $ro['morning'];
        $Mendtime = $ro['lunch'];
        $Astarttime = $ro['afternoon'];
        $Aendtime = $ro['night'];
    }
    $qu = "UPDATE shift_table SET morning='$Mstarttime',lunch='$Mendtime',afternoon='$Astarttime',night='$Aendtime' WHERE chat_id='$chat_id'";
    mysqli_query($con, $qu);
    $del = "DELETE FROM shift_temp WHERE chat_id='$chat_id'";
    mysqli_query($con, $del);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=The shift updated successfully");
    if ($Mstarttime <= $timenow && $Mendtime >= $timenow) {
        adminMorningMenu($chat_id);
    } else if ($Astarttime <= $timenow && $Aendtime >= $timenow) {
        adminAfternoonMenu($chat_id);
    }
}