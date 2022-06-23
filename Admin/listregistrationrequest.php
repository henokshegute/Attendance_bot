<?php
function listrequesteduser($chat_id){
global $botAPI;
global $con;
$conf = "SELECT * FROM user_temp";
$confQ = mysqli_query($con, $conf);
$admin = "SELECT * FROM user WHERE role='admin' AND chat_id = '$chat_id' ";
$adminQ = mysqli_query($con, $admin);
while ($ro = mysqli_fetch_array($adminQ)) {
    $admin_id = $ro['chat_id'];
    }
if (mysqli_num_rows($confQ) > 0)
    while ($ro = mysqli_fetch_array($confQ)) {
            $chat_id = $ro['chat_id'];
            $name = $ro['name'];
            $marksHTML = "";
            $marksHTMLL = "";
            list($firstname, $secondname) = explode(" ", $name);
            echo $firstname . " " . $secondname;
            $fname = rawurlencode($firstname);
            $sname = $secondname;
            $marksHTML .= "Name :- " . $fname;
            $marksHTMLL .= $sname;
            $hel = "<b>Aprove:</b>%0A";
            $hel .= rawurlencode($marksHTML);
            $approve_as_user = "u ";
            $approve_as_user .= $chat_id;
            $approve_as_admin = "a ";
            $approve_as_admin .= $chat_id;
            $delete = "d ";
            $delete .= $chat_id;
            $keyboard = json_encode(["inline_keyboard" => [[
                ["text" => " ✔️ User", "callback_data" => $approve_as_user],
                ["text" => " ✔️ Admin", "callback_data" => $approve_as_admin],
                ["text" => " ❌ Delete", "callback_data" => $delete],
            ],], 'resize_keyboard' => true, "one_time_keyboard" => true]);
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $admin_id . "&text=" . $hel . "%20" . $marksHTMLL . "&parse_mode=html&reply_markup={$keyboard}");
    }
    else {
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=There is no registration requestes for now");
}
}