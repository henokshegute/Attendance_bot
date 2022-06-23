<?php
function acceptAsUser($id, $chat_id, $message_id)
{
    global $botAPI;
    global $con;
    $sql = "select * from user_temp where chat_id ='$id'";
    $query = mysqli_query($con, $sql);
    $row_num = mysqli_num_rows($query);
    if ($row_num > 0) {
        while ($ro = mysqli_fetch_array($query)) {
            $name = $ro['name'];
        }
        $qu = "INSERT INTO user(chat_id,name,role) VALUES('$id','$name','user')";
        mysqli_query($con, $qu);
        $del = "DELETE FROM user_temp WHERE chat_id='$id'";
        mysqli_query($con, $del);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $id . "&text=You are registerd successfully !!!");
        $approve_as_user = "u ";
        $approve_as_user .= $chat_id;
        $keyboard = json_encode(["inline_keyboard" => [[
            ["text" => "✔️ User", "callback_data" => $approve_as_user],
        ],], 'resize_keyboard' => true, "one_time_keyboard" => true]);
        file_get_contents($botAPI . "/editMessageReplyMarkup?chat_id=" . $chat_id . "&message_id=" . $message_id . "&reply_markup={$keyboard}");
    }
}
function acceptAsAdmin($id, $chat_id, $message_id)
{
    global $botAPI;
    global $con;
    global $msg;

    $sql = "select * from user_temp where chat_id ='$id'";
    $query = mysqli_query($con, $sql);
    $row_num = mysqli_num_rows($query);
    if ($row_num > 0) {
        while ($ro = mysqli_fetch_array($query)) {
            $name = $ro['name'];
        }
        $qu = "INSERT INTO user(chat_id,name,role) VALUES('$id','$name','admin')";
        mysqli_query($con, $qu);
        $del = "DELETE FROM user_temp WHERE chat_id='$id'";
        mysqli_query($con, $del);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $id . "&text=You are registerd successfully !!!");
        $approve_as_admin = "a ";
        $approve_as_admin .= $chat_id;
        $keyboard = json_encode(["inline_keyboard" => [[
            ["text" => "✔️ Admin", "callback_data" => $approve_as_admin],
        ],], 'resize_keyboard' => true, "one_time_keyboard" => true]);
        file_get_contents($botAPI . "/editMessageReplyMarkup?chat_id=" . $chat_id . "&message_id=" . $message_id . "&reply_markup={$keyboard}");
    }
}
function delete($id, $chat_id, $message_id)
{
    global $botAPI;
    global $con;
    $sql = "select * from user_temp where chat_id ='$id'";
    $query = mysqli_query($con, $sql);
    $row_num = mysqli_num_rows($query);
    if ($row_num > 0) {
        while ($ro = mysqli_fetch_array($query)) {
            $name = $ro['name'];
        }
        $del = "DELETE FROM user_temp WHERE chat_id='$id'";
        mysqli_query($con, $del);

        file_get_contents($botAPI . "/sendmessage?chat_id=" . $id . "&text=You are not allowed to register !!!");

        $delete = "d ";
        $delete .= $chat_id;
        $keyboard = json_encode(["inline_keyboard" => [[
            ["text" => "❌ Deleted", "callback_data" => $delete],
        ],], 'resize_keyboard' => true, "one_time_keyboard" => true]);
        file_get_contents($botAPI . "/editMessageReplyMarkup?chat_id=" . $chat_id . "&message_id=" . $message_id . "&reply_markup={$keyboard}");
    }
}
