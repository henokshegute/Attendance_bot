<?php
function userDataConfirmation($chat_id)
{
    $keyboard = array(array("Confirm", "Discard"));
    global $con;
    global $botAPI;
    $marksHTML = "";
    $marksHTMLL = "";
    $hel = "<b>Confirm</b>%0A";
    $sql = "select * from user_temp where chat_id ='$chat_id'";
    $rep = mysqli_query($con, $sql);
    while ($ro = mysqli_fetch_array($rep)) {
        $name = $ro['name'];
    }
    list($firstname, $secondname) = explode(" ", $name);
    $fname = rawurlencode($firstname);
    $sname = $secondname;
    $marksHTML .= "Name :- " . $fname;
    $marksHTMLL .= $sname;
    $hel .= rawurlencode($marksHTML);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=" . $hel . "%20" . $marksHTMLL . "&parse_mode=html");
    $resp = array("keyboard" => $keyboard, "resize_keyboard" => true, "one_time_keyboard" => true);
    $reply = json_encode($resp);
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=Please Confirm: &reply_markup=" . $reply);
}