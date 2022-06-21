<?php
function acceptLeave($id, $chat_id, $message_id)
{
    global $botAPI;
    global $con;
    $sql = "select * from leave_temp where chat_id ='$id'";
    $query = mysqli_query($con, $sql);

    $lev_num = mysqli_num_rows($query);
    if ($lev_num > 0) {
        while ($ro = mysqli_fetch_array($query)) {

            $name = $ro['name'];
            $number_of_days = $ro['number_of_days'];
            $starting_from = $ro['starting_from'];
            $reason = $ro['reason'];
        }
        $qu = "INSERT INTO leave_table (chat_id,name,number_of_days,starting_from,reason) VALUES('$id','$name','$number_of_days','$starting_from','$reason')";
        mysqli_query($con, $qu);
        $del = "DELETE FROM leave_temp WHERE chat_id='$id'";
        mysqli_query($con, $del);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $id . "&text=Your leave request is approved !!!");
        $accept = "l ";
        $accept .= $chat_id;
        $keyboard = json_encode(["inline_keyboard" => [[
            ["text" => "✔️ Accepted", "callback_data" => $accept]
        ]],]);
        file_get_contents($botAPI . "/editMessageReplyMarkup?chat_id=" . $chat_id . "&message_id=" . $message_id . "&reply_markup={$keyboard}");
    }
}
function declineLeave($id, $chat_id, $message_id)
{
    global $botAPI;
    global $con;
    $sql = "select * from leave_temp where chat_id ='$id'";
    $query = mysqli_query($con, $sql);
    $row_num = mysqli_num_rows($query);
    if ($row_num > 0) {
        while ($ro = mysqli_fetch_array($query)) {
            $id = $ro['chat_id'];
        }
        $del = "DELETE FROM leave_temp WHERE chat_id='$id'";
        mysqli_query($con, $del);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $id . "&text=Your leave is not approved !!!");
        $declineleave = "d ";
        $declineleave .= $chat_id;
        $keyboard = json_encode(["inline_keyboard" => [[
            ["text" => rawurlencode("❌ Declined"), "callback_data" => rawurlencode($declineleave)],
        ]],]);

        file_get_contents($botAPI . "/editMessageReplyMarkup?chat_id=" . $chat_id . "&message_id=" . $message_id . "&reply_markup={$keyboard}");
    }
}
