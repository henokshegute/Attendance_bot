<?php
function acceptFieldWork($id, $chat_id, $message_id)
{
    global $botAPI;
    global $con;
    date_default_timezone_set('Africa/Addis_Ababa');
    $today = date('y-m-d');
    ///////////////////////
    $attendanceCheck = $con->query("SELECT * FROM attendance_shit where chat_id ='$id' And date = '$today'");
    $num_of_user = mysqli_num_rows($attendanceCheck);
    
    ///////////////////////
    
    $listfield = $con->query("select * from field_temp ");
    while ($ro = mysqli_fetch_array($listfield)) {

        //$id = $ro['chat_id'];
        $name = $ro['name'];
        $num_of_hours = $ro['num_of_hour'];
        $time_of_work = $ro['time_of_work'];
        $date = $ro['date'];
    }
    ///////////////////////
    $sql = $con->query("SELECT * FROM field_temp WHERE chat_id ='$id'");
    $list_num = mysqli_num_rows($sql);
    
    if ($list_num > 0) {
        if ($num_of_hours == 'Fullday') {
            $attendance_query = "INSERT INTO attendance_shit(employee_name,morning,date,chat_id,morning_in,morning_out,afternoon_in,afternoon_out) VALUES('$name','Field Work','$date','$id','Field Work','Field Work','Field Work','Field Work')";
            mysqli_query($con, $attendance_query);
            $del = "DELETE FROM field_temp WHERE chat_id='$id'";
            mysqli_query($con, $del);
            file_get_contents($botAPI . "/sendmessage?chat_id=" . $id . "&text=Your request is approved !!!");
            $acceptField = "f ";
            $acceptField .= $chat_id;
            $keyboard = json_encode(["inline_keyboard" => [[
                ["text" => "✔️ Accepted", "callback_data" => $acceptField]
            ]]]);
            file_get_contents($botAPI . "/editMessageReplyMarkup?chat_id=" . $chat_id . "&message_id=" . $message_id . "&reply_markup={$keyboard}");
        } else if ($num_of_hours == 'Half Day') {
            if ($time_of_work == "📝 Morning") {
                $attendance_query = "INSERT INTO attendance_shit(employee_name,morning,date,chat_id,morning_in,morning_out) VALUES('$name','Field Work','$date','$id','Field Work','Field Work')";
                mysqli_query($con, $attendance_query);
                $del = "DELETE FROM field_temp WHERE chat_id='$id'";
                mysqli_query($con, $del);
                file_get_contents($botAPI . "/sendmessage?chat_id=" . $id . "&text=Your request is approved !!!");
                $acceptField = "f ";
                $acceptField .= $chat_id;
                $keyboard = json_encode(["inline_keyboard" => [[
                    ["text" => "✔️ Accepted", "callback_data" => $acceptField]
                ]]]);
                file_get_contents($botAPI . "/editMessageReplyMarkup?chat_id=" . $chat_id . "&message_id=" . $message_id . "&reply_markup={$keyboard}");
            } else if ($time_of_work == '📝 Afternoon') {
                if ($num_of_user == 0) {
                    $attendance_query = "INSERT INTO attendance_shit (employee_name,afternoon,date,chat_id,afternoon_in,afternoon_out) VALUES('$name','Field Work','$date','$id','Field Work','Field Work')";
                    mysqli_query($con, $attendance_query);
                    $del = "DELETE FROM field_temp WHERE chat_id='$id'";
                    mysqli_query($con, $del);
                    file_get_contents($botAPI . "/sendmessage?chat_id=" . $id . "&text=Your request is approved !!!");
                    $acceptField = "f ";
                    $acceptField .= $chat_id;
                    $keyboard = json_encode(["inline_keyboard" => [[
                        ["text" => "✔️ Accepted", "callback_data" => $acceptField]
                    ]]]);
                    file_get_contents($botAPI . "/editMessageReplyMarkup?chat_id=" . $chat_id . "&message_id=" . $message_id . "&reply_markup={$keyboard}");
                } else if ($num_of_user > 0) {
                    $update_query = "UPDATE attendance_shit SET afternoon_in='Field Work',afternoon_out='Field Work' WHERE date= '$today' AND chat_id= '$id'";
                    mysqli_query($con, $update_query);
                    $del = "DELETE FROM field_temp WHERE chat_id='$id'";
                    mysqli_query($con, $del);
                    file_get_contents($botAPI . "/sendmessage?chat_id=" . $id . "&text=Your  request is approved !!!");
                    $acceptField = "f ";
                    $acceptField .= $chat_id;
                    $keyboard = json_encode(["inline_keyboard" => [[
                        ["text" => "✔️ Accepted", "callback_data" => $acceptField]
                    ]]]);
                    file_get_contents($botAPI . "/editMessageReplyMarkup?chat_id=" . $chat_id . "&message_id=" . $message_id . "&reply_markup={$keyboard}");
                }
            }
        }
    }
}
function declineFieldWork($id, $chat_id, $message_id)
{
    global $botAPI;
    global $con;
    $sql = "select * from field_temp where chat_id ='$id'";
    $sql = mysqli_query($con, $sql);
    $row_num = mysqli_num_rows($sql);
    if ($row_num > 0) {
        while ($ro = mysqli_fetch_array($sql)) {
            $id = $ro['chat_id'];
        }
        $del = "DELETE FROM field_temp WHERE chat_id='$id'";
        mysqli_query($con, $del);
        file_get_contents($botAPI . "/sendmessage?chat_id=" . $id . "&text=Your Field Work request is not approved !!!");
        $declineField = "r ";
        $declineField .= $chat_id;
        $keyboard = json_encode(["inline_keyboard" => [[
            ["text" => "❌ Declined", "callback_data" => $declineField]
        ]]]);
        file_get_contents($botAPI . "/editMessageReplyMarkup?chat_id=" . $chat_id . "&message_id=" . $message_id . "&reply_markup={$keyboard}");
    }
}
