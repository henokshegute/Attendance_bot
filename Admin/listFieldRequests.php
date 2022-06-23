<?php
function listFieldWork($chat_id)
{
    global $con;
    global $botAPI;
    $admin = "SELECT * FROM user WHERE role='admin' AND chat_id = '$chat_id' ";
    $adminQ = mysqli_query($con, $admin);
    $listRequest = $con->query("SELECT * FROM Field_temp");
    while ($ro = mysqli_fetch_array($adminQ)) {
        $admin_id = $ro['chat_id'];
    }
    if (mysqli_num_rows($listRequest) > 0) {
        while ($ro = mysqli_fetch_array($listRequest)) {
            $chat_id = $ro['chat_id'];
            $name = $ro['name'];
            $num_of_hours = $ro['num_of_hour'];
            $time_of_work = $ro['time_of_work'];
            $date = $ro['date'];
            $marksHTML = "";
            $marksHTML .= "Name :-" . strtolower($name) . "%0A";
            $marksHTML .= "number of hours :- " . strtolower($num_of_hours) . "%0A";
            $marksHTML .= "time of work :- " . $time_of_work . "%0A";
            $marksHTML .= "date :- " . $date . "%0A";
            $hel = "<b>Aprove:</b>%0A";
            $hel .= $marksHTML . "%0A";
            $acceptField = "f ";
            $acceptField .= $chat_id;
            $declineField = "r ";
            $declineField .= $chat_id;
            $keyboard = json_encode(["inline_keyboard" => [[
                ["text" => " ✔️ Accept", "callback_data" => $acceptField],
                ["text" => " ❌ Decline", "callback_data" => $declineField],
            ],], 'resize_keyboard' => true, "one_time_keyboard" => true]);
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $admin_id . "&text=" . $hel . "&parse_mode=html&reply_markup={$keyboard}");
        }
    } else {
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=There is no leave requestes for now");
    }
}
