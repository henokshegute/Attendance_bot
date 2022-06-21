<?php function listleaverequest($chat_id)
{
    global $con;
    global $botAPI;
    $cont = "SELECT * FROM leave_temp";
    $confs = mysqli_query($con, $cont);
    $admin = "SELECT * FROM user WHERE role='admin' AND chat_id = '$chat_id' ";
    $adminQ = mysqli_query($con, $admin);
    while ($ro = mysqli_fetch_array($adminQ)) {
        $admin_id = $ro['chat_id'];
    }
    if (mysqli_num_rows($confs) > 0) {
        while ($ro = mysqli_fetch_array($confs)) {
            $chat_id = $ro['chat_id'];
            $name = $ro['name'];
            $number_of_days = $ro['number_of_days'];
            $starting_from = $ro['starting_from'];
            $reason = $ro['reason'];
            $marksHTML = "";
            $marksHTML .= "Name :-" . strtolower($name) . "%0A";
            $marksHTML .= "number_of_days :- " . $number_of_days . "%0A";
            $marksHTML .= "starting_from :- " . $starting_from . "%0A";
            $marksHTML .= "reason :- " . $reason . "%0A";
            $hel = "<b>Aprove:</b>%0A";
            $hel .= $marksHTML . "%0A";
            $acceptleave = "l ";
            $acceptleave .= $chat_id;
            $declineleave = "n ";
            $declineleave .= $chat_id;
            $keyboard = json_encode(["inline_keyboard" => [[
                ["text" => " ✔️ Accept", "callback_data" => $acceptleave],
                ["text" => " ❌ Decline", "callback_data" => $declineleave],
            ],], 'resize_keyboard' => true, "one_time_keyboard" => true]);
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $admin_id . "&text=" . $hel . "&parse_mode=html&reply_markup={$keyboard}");
        }
    } else {
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=There is no leave requestes for now");
    }
}
