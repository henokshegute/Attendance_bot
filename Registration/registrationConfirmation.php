<?php
function confirmetionreplay($chat_id)
{
    global $botAPI;
    global $con;
    $admin = "SELECT * FROM user WHERE role='admin'";
    $adminQ = mysqli_query($con, $admin);
    while ($ro = mysqli_fetch_array($adminQ)) {
        $admin_id = $ro['chat_id'];
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $admin_id . "&text=Dear Admin, There are a request for leave you need to approve");
    }
    $info = "";
    $info .= "Your registraton detail is sent successfully.";
    file_get_contents($botAPI . "/sendmessage?chat_id=" . $chat_id . "&text=" . $info . "&parse_mode=html");
    disable($chat_id);
}
